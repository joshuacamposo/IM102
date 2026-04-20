<?php
session_start();

// Check if logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$username = $_SESSION['username'];
$role = $_SESSION['role']; // admin or user
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>User Dashboard</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        body { font-family: Arial, sans-serif; margin: 0; padding: 0; }
        .container { max-width: 800px; margin: 50px auto; padding: 20px; }
        hr { margin: 20px 0; }
        ul { list-style-type: none; padding-left: 0; }
        ul li { margin: 5px 0; }
        ul li a { text-decoration: none; color: #15a150;}
        ul li a:hover { text-decoration: underline; }
    </style>
</head>
<body>

<div class="container">
    <h2>User Dashboard</h2>
    <p>Welcome, <strong><?php echo htmlspecialchars($username); ?></strong></p>
    <p>Role: <strong><?php echo htmlspecialchars($role); ?></strong></p>

    <hr>

    <h3>Navigation</h3>
    <ul>
        <li><a href="uploads/profile.php">Profile</a></li>
        <?php if ($role === 'admin'): ?>
            <li><a href="admin_dashboard.php">Go to Admin Dashboard</a></li>
        <?php endif; ?>
        <li><a href="logout.php">Logout</a></li>
    </ul>
</div>

</body>
</html>