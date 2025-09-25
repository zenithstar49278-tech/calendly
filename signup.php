<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include 'db.php'; // Change to '../db.php' if needed

$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (!isset($conn) || $conn->connect_error) {
        $error = "Database connection failed. Check db.php and server logs.";
    } else {
        $username = mysqli_real_escape_string($conn, trim($_POST['username']));
        $email = mysqli_real_escape_string($conn, $_POST['email']);
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $booking_link = $username;

        $sql_check = "SELECT id FROM users WHERE username = '$username' OR email = '$email'";
        $check = $conn->query($sql_check);
        if ($check->num_rows > 0) {
            $error = "Username or email already exists.";
        } else {
            $sql = "INSERT INTO users (username, email, password, booking_link) VALUES ('$username', '$email', '$password', '$booking_link')";
            if ($conn->query($sql) === TRUE) {
                echo "<script>window.location.href = 'login.php';</script>";
                exit;
            } else {
                $error = "Error: " . $conn->error;
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up</title>
    <style>
        body { font-family: 'Arial', sans-serif; margin: 0; padding: 0; background-image: url('https://images.unsplash.com/photo-1455587734955-081b22074882?ixlib=rb-4.0.3&auto=format&fit=crop&w=1470&q=80'); background-size: cover; color: #333; }
        .form-container { max-width: 400px; margin: auto; padding: 20px; background: rgba(255, 255, 255, 0.9); border-radius: 10px; margin-top: 100px; }
        input { width: 100%; padding: 10px; margin: 10px 0; border-radius: 5px; border: 1px solid #ccc; }
        button { background: #d4af37; color: white; border: none; padding: 10px; cursor: pointer; width: 100%; border-radius: 5px; }
        button:hover { background: #b9932e; }
        .error { color: red; margin-bottom: 10px; }
        @media (max-width: 768px) { .form-container { margin-top: 50px; padding: 10px; } }
    </style>
</head>
<body>
    <div class="form-container">
        <h2>Sign Up</h2>
        <?php if ($error): ?>
            <p class="error"><?php echo $error; ?></p>
        <?php endif; ?>
        <form method="POST">
            <input type="text" name="username" placeholder="Username" required>
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit">Sign Up</button>
        </form>
        <p>Already have an account? <a href="login.php">Log In</a></p>
    </div>
</body>
</html>
