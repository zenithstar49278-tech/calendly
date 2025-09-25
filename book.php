<?php
include 'db.php';
if (!isset($_GET['user'])) {
    die("User not specified.");
}
$username = mysqli_real_escape_string($conn, $_GET['user']);
$sql = "SELECT id FROM users WHERE username = '$username'";
$result = $conn->query($sql);
if ($result->num_rows == 0) {
    die("User not found.");
}
$user = $result->fetch_assoc();
$user_id = $user['id'];

// If date selected, show slots
$selected_date = isset($_POST['date']) ? $_POST['date'] : null;
$slots = [];
if ($selected_date) {
    $day_of_week = date('l', strtotime($selected_date)); // e.g., Monday
    $avail_sql = "SELECT * FROM availabilities WHERE user_id = $user_id AND day_of_week = '$day_of_week'";
    $avail_result = $conn->query($avail_sql);
    if ($avail_result->num_rows > 0) {
        $avail = $avail_result->fetch_assoc();
        $start = strtotime($avail['start_time']);
        $end = strtotime($avail['end_time']);
        $duration = $avail['slot_duration'] * 60; // seconds

        for ($time = $start; $time < $end; $time += $duration) {
            $slot_start = date('H:i', $time);
            $slot_end = date('H:i', $time + $duration);
            
            // Check if booked
            $booked_sql = "SELECT * FROM bookings WHERE user_id = $user_id AND date = '$selected_date' AND start_time = '$slot_start' AND status != 'cancelled'";
            $booked = $conn->query($booked_sql);
            if ($booked->num_rows == 0) {
                $slots[] = ['start' => $slot_start, 'end' => $slot_end];
            }
        }
    }
}

// Book the slot
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['slot_start'])) {
    $date = $_POST['date'];
    $start_time = $_POST['slot_start'];
    $end_time = $_POST['slot_end'];
    $booker_name = mysqli_real_escape_string($conn, $_POST['booker_name']);
    $booker_email = mysqli_real_escape_string($conn, $_POST['booker_email']);

    $sql = "INSERT INTO bookings (user_id, date, start_time, end_time, booker_name, booker_email) VALUES ($user_id, '$date', '$start_time', '$end_time', '$booker_name', '$booker_email')";
    if ($conn->query($sql) === TRUE) {
        // Send email
        $to = $booker_email;
        $subject = "Booking Confirmation";
        $message = "Your booking with $username on $date at $start_time - $end_time is confirmed.";
        mail($to, $subject, $message);

        // Email to owner
        $owner_sql = "SELECT email FROM users WHERE id = $user_id";
        $owner_email = $conn->query($owner_sql)->fetch_assoc()['email'];
        mail($owner_email, "New Booking", $message);

        echo "<script>alert('Booking confirmed!'); window.location.href = 'index.php';</script>";
    } else {
        echo "Error: " . $conn->error;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Appointment with <?php echo $username; ?></title>
    <style>
        body { font-family: 'Arial', sans-serif; margin: 0; padding: 0; background-image: url('https://images.unsplash.com/photo-1549637641-9d2c2638be69?ixlib=rb-4.0.3&auto=format&fit=crop&w=1470&q=80'); background-size: cover; color: #333; }
        .container { max-width: 600px; margin: auto; padding: 20px; background: rgba(255, 255, 255, 0.8); border-radius: 10px; margin-top: 50px; }
        form { display: grid; gap: 10px; }
        input, button { padding: 10px; border-radius: 5px; border: 1px solid #ccc; }
        button { background: #d4af37; color: white; border: none; cursor: pointer; }
        button:hover { background: #b9932e; }
        .slot { margin: 10px 0; }
        @media (max-width: 768px) { .container { padding: 10px; } }
    </style>
    <script>
        function bookSlot(start, end, date) {
            document.getElementById('slot_start').value = start;
            document.getElementById('slot_end').value = end;
            document.getElementById('date_hidden').value = date;
            document.getElementById('book_form').style.display = 'block';
        }
    </script>
</head>
<body>
    <div class="container">
        <h2>Book with <?php echo $username; ?></h2>
        <form method="POST" action="">
            <input type="date" name="date" min="<?php echo date('Y-m-d'); ?>" required>
            <button type="submit">Show Available Slots</button>
        </form>
        
        <?php if ($selected_date): ?>
            <h3>Available Slots on <?php echo $selected_date; ?></h3>
            <?php foreach ($slots as $slot): ?>
                <div class="slot">
                    <?php echo $slot['start'] . ' - ' . $slot['end']; ?>
                    <button onclick="bookSlot('<?php echo $slot['start']; ?>', '<?php echo $slot['end']; ?>', '<?php echo $selected_date; ?>')">Book</button>
                </div>
            <?php endforeach; ?>
            <?php if (empty($slots)): ?>
                <p>No slots available.</p>
            <?php endif; ?>
        <?php endif; ?>
        
        <form id="book_form" method="POST" style="display: none;">
            <input type="hidden" id="date_hidden" name="date">
            <input type="hidden" id="slot_start" name="slot_start">
            <input type="hidden" id="slot_end" name="slot_end">
            <input type="text" name="booker_name" placeholder="Your Name" required>
            <input type="email" name="booker_email" placeholder="Your Email" required>
            <button type="submit">Confirm Booking</button>
        </form>
        
        <a href="index.php"><button>Back to Home</button></a>
    </div>
</body>
</html>
