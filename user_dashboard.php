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
</head>
<body>

<div class="container">
    <h2>User Dashboard</h2>
    <p>Welcome, <strong><?php echo htmlspecialchars($username); ?></strong></p>
    <p>Role: <strong><?php echo htmlspecialchars($role); ?></strong></p>

    <hr>

    <h3>Navigation</h3>

    <?php if ($role === 'admin'): ?>
        <p><a href="admin_dashboard.php">Go to Admin Dashboard</a></p>
    <?php endif; ?>

    <p><a href="logout.php">Logout</a></p>
</div>

</body>
</html>