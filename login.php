<?php
session_start();
include 'db.php';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = $_POST['password'];

    $sql = "SELECT * FROM users WHERE email = '$email'";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            echo "<script>window.location.href = 'dashboard.php';</script>";
        } else {
            echo "Invalid password.";
        }
    } else {
        echo "No user found.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Log In</title>
    <style>
        body { font-family: 'Arial', sans-serif; margin: 0; padding: 0; background-image: url('https://images.unsplash.com/photo-1549637641-9d2c2638be69?ixlib=rb-4.0.3&auto=format&fit=crop&w=1470&q=80'); background-size: cover; color: #333; }
        .form-container { max-width: 400px; margin: auto; padding: 20px; background: rgba(255, 255, 255, 0.9); border-radius: 10px; margin-top: 100px; }
        input { width: 100%; padding: 10px; margin: 10px 0; border-radius: 5px; border: 1px solid #ccc; }
        button { background: #d4af37; color: white; border: none; padding: 10px; cursor: pointer; width: 100%; border-radius: 5px; }
        button:hover { background: #b9932e; }
        @media (max-width: 768px) { .form-container { margin-top: 50px; padding: 10px; } }
    </style>
</head>
<body>
    <div class="form-container">
        <h2>Log In</h2>
        <form method="POST">
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit">Log In</button>
        </form>
        <p>Don't have an account? <a href="signup.php">Sign Up</a></p>
    </div>
</body>
</html>
