<?php
session_start();
if (!isset($_SESSION['user_id']) || !isset($_GET['id'])) {
    echo "<script>window.location.href = 'login.php';</script>";
}
include 'db.php';
$id = $_GET['id'];
$user_id = $_SESSION['user_id'];

// For reschedule, we'll delete the old and redirect to book new (simple implementation)
$sql = "DELETE FROM bookings WHERE id = $id AND user_id = $user_id";
if ($conn->query($sql) === TRUE) {
    echo "<script>alert('Old booking removed. Please book a new slot.'); window.location.href = 'book.php?user=" . $_SESSION['username'] . "';</script>";
} else {
    echo "Error: " . $conn->error;
}
?>
