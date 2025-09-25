<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    echo "<script>window.location.href = 'login.php';</script>";
}
include 'db.php';
$user_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $day = $_POST['day'];
    $start_time = $_POST['start_time'];
    $end_time = $_POST['end_time'];
    $slot_duration = $_POST['slot_duration'];

    $sql = "INSERT INTO availabilities (user_id, day_of_week, start_time, end_time, slot_duration) 
            VALUES ($user_id, '$day', '$start_time', '$end_time', $slot_duration)
            ON DUPLICATE KEY UPDATE start_time='$start_time', end_time='$end_time', slot_duration=$slot_duration";
    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('Availability set!'); window.location.href = 'dashboard.php';</script>";
    } else {
        echo "Error: " . $conn->error;
    }
}

// Get current availabilities
$avail = $conn->query("SELECT * FROM availabilities WHERE user_id = $user_id");
$avail_data = [];
while ($row = $avail->fetch_assoc()) {
    $avail_data[$row['day_of_week']] = $row;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Set Availability</title>
    <style>
        body { font-family: 'Arial', sans-serif; margin: 0; padding: 0; background-image: url('https://images.unsplash.com/photo-1455587734955-081b22074882?ixlib=rb-4.0.3&auto=format&fit=crop&w=1470&q=80'); background-size: cover; color: #333; }
        .container { max-width: 600px; margin: auto; padding: 20px; background: rgba(255, 255, 255, 0.8); border-radius: 10px; margin-top: 50px; }
        form { display: grid; gap: 10px; }
        select, input { padding: 10px; border-radius: 5px; border: 1px solid #ccc; }
        button { background: #d4af37; color: white; border: none; padding: 10px; cursor: pointer; border-radius: 5px; }
        button:hover { background: #b9932e; }
        .current { margin-top: 20px; }
        @media (max-width: 768px) { .container { padding: 10px; } }
    </style>
</head>
<body>
    <div class="container">
        <h2>Set Your Availability</h2>
        <form method="POST">
            <select name="day" required>
                <option value="Monday">Monday</option>
                <option value="Tuesday">Tuesday</option>
                <option value="Wednesday">Wednesday</option>
                <option value="Thursday">Thursday</option>
                <option value="Friday">Friday</option>
                <option value="Saturday">Saturday</option>
                <option value="Sunday">Sunday</option>
            </select>
            <input type="time" name="start_time" required>
            <input type="time" name="end_time" required>
            <input type="number" name="slot_duration" placeholder="Slot Duration (minutes)" value="30" required>
            <button type="submit">Save</button>
        </form>
        <div class="current">
            <h3>Current Availability</h3>
            <?php foreach ($avail_data as $day => $data): ?>
                <p><?php echo $day; ?>: <?php echo $data['start_time']; ?> - <?php echo $data['end_time']; ?> (<?php echo $data['slot_duration']; ?> min slots)</p>
            <?php endforeach; ?>
        </div>
        <a href="dashboard.php"><button>Back to Dashboard</button></a>
    </div>
</body>
</html>
