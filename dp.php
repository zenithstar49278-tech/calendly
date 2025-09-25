<?php
$servername = "localhost";
$username = "upbek8wm1lktc";
$password = "wkctga6nhgu8";
$dbname = "dbwvyzp2rwxl0s";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
