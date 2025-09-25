<?php
session_start();
if (!isset($_SESSION['user_id']) || !isset($_GET['id'])) {
    echo "<script>window.location.href = 'login.php';</script>";
}
include 'db.php';
$id = $_GET['id'];
$user_id = $_SESSION['user_id'];

$sql = "UPDATE bookings SET status = 'cancelled' WHERE id = $id AND user_id = $user_id";
if ($conn->query($sql) === TRUE) {
    echo "<script>alert('Booking cancelled.'); window.location.href = 'dashboard.php';</script>";
} else {
    echo "Error: " . $conn->error;
}
?>
