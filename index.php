<?php
session_start();
include 'db.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Premium Scheduling - Home</title>
    <style>
        body { font-family: 'Arial', sans-serif; margin: 0; padding: 0; background-image: url('https://images.unsplash.com/photo-1445991842772-097ffd5d7b2d?ixlib=rb-4.0.3&auto=format&fit=crop&w=1470&q=80'); background-size: cover; background-position: center; color: #333; }
        header { background: rgba(0, 0, 0, 0.5); color: white; padding: 20px; text-align: center; }
        .container { max-width: 1200px; margin: auto; padding: 20px; background: rgba(255, 255, 255, 0.8); border-radius: 10px; }
        button { background: #d4af37; color: white; border: none; padding: 10px 20px; cursor: pointer; border-radius: 5px; font-size: 18px; }
        button:hover { background: #b9932e; }
        .section { margin: 20px 0; }
        @media (max-width: 768px) { .container { padding: 10px; } button { font-size: 16px; } }
    </style>
</head>
<body>
    <header>
        <h1>Welcome to Premium Scheduling</h1>
        <p>Effortless meeting scheduling like never before. Inspired by luxury experiences.</p>
    </header>
    <div class="container">
        <div class="section">
            <h2>How It Works</h2>
            <p>Sign up to create your personal booking link. Set your availability and share your link. Visitors can book time slots easily. Manage everything from your dashboard.</p>
        </div>
        <div class="section">
            <?php if (!isset($_SESSION['user_id'])): ?>
                <a href="signup.php"><button>Sign Up</button></a>
                <a href="login.php"><button>Log In</button></a>
            <?php else: ?>
                <a href="dashboard.php"><button>Go to Dashboard</button></a>
            <?php endif; ?>
            <a href="book.php"><button>Book a Meeting</button></a> <!-- Leads to scheduling page -->
        </div>
    </div>
</body>
</html>
