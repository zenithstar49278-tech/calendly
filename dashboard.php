<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    echo "<script>window.location.href = 'login.php';</script>";
}
include 'db.php';
$user_id = $_SESSION['user_id'];

// Get upcoming and past bookings
$upcoming = $conn->query("SELECT * FROM bookings WHERE user_id = $user_id AND date >= CURDATE() ORDER BY date, start_time");
$past = $conn->query("SELECT * FROM bookings WHERE user_id = $user_id AND date < CURDATE() ORDER BY date DESC, start_time");

// Notifications: Upcoming in next 7 days
$notifications = $conn->query("SELECT * FROM bookings WHERE user_id = $user_id AND date BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL 7 DAY) AND status = 'confirmed'");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <style>
        body { font-family: 'Arial', sans-serif; margin: 0; padding: 0; background-image: url('https://images.unsplash.com/photo-1445991842772-097ffd5d7b2d?ixlib=rb-4.0.3&auto=format&fit=crop&w=1470&q=80'); background-size: cover; color: #333; }
        .container { max-width: 1200px; margin: auto; padding: 20px; background: rgba(255, 255, 255, 0.8); border-radius: 10px; }
        table { width: 100%; border-collapse: collapse; margin: 20px 0; }
        th, td { border: 1px solid #ccc; padding: 10px; text-align: left; }
        th { background: #d4af37; color: white; }
        button { background: #d4af37; color: white; border: none; padding: 5px 10px; cursor: pointer; border-radius: 5px; }
        button:hover { background: #b9932e; }
        .notification { background: #ffeb3b; padding: 10px; margin: 10px 0; border-radius: 5px; }
        @media (max-width: 768px) { table { font-size: 14px; } .container { padding: 10px; } }
    </style>
</head>
<body>
    <div class="container">
        <h1>Welcome, <?php echo $_SESSION['username']; ?></h1>
        <p>Your booking link: <a href="book.php?user=<?php echo $_SESSION['username']; ?>">book.php?user=<?php echo $_SESSION['username']; ?></a></p>
        <a href="set_availability.php"><button>Set Availability</button></a>
        <a href="logout.php"><button>Log Out</button></a>
        
        <h2>Notifications</h2>
        <?php while ($notif = $notifications->fetch_assoc()): ?>
            <div class="notification">Upcoming meeting with <?php echo $notif['booker_name']; ?> on <?php echo $notif['date']; ?> at <?php echo $notif['start_time']; ?></div>
        <?php endwhile; ?>
        
        <h2>Upcoming Appointments</h2>
        <table>
            <tr><th>ID</th><th>Date</th><th>Time</th><th>Booker</th><th>Status</th><th>Actions</th></tr>
            <?php while ($row = $upcoming->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $row['id']; ?></td>
                    <td><?php echo $row['date']; ?></td>
                    <td><?php echo $row['start_time'] . ' - ' . $row['end_time']; ?></td>
                    <td><?php echo $row['booker_name']; ?> (<?php echo $row['booker_email']; ?>)</td>
                    <td><?php echo $row['status']; ?></td>
                    <td>
                        <a href="cancel.php?id=<?php echo $row['id']; ?>"><button>Cancel</button></a>
                        <a href="reschedule.php?id=<?php echo $row['id']; ?>"><button>Reschedule</button></a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </table>
        
        <h2>Past Appointments</h2>
        <table>
            <tr><th>ID</th><th>Date</th><th>Time</th><th>Booker</th><th>Status</th></tr>
            <?php while ($row = $past->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $row['id']; ?></td>
                    <td><?php echo $row['date']; ?></td>
                    <td><?php echo $row['start_time'] . ' - ' . $row['end_time']; ?></td>
                    <td><?php echo $row['booker_name']; ?> (<?php echo $row['booker_email']; ?>)</td>
                    <td><?php echo $row['status']; ?></td>
                </tr>
            <?php endwhile; ?>
        </table>
    </div>
</body>
</html>
