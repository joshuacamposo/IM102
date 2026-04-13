<?php
session_start();
require_once 'auth_functions.php';

// Only allow admins
requireRole('admin');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <!-- Navigation Bar -->
    <nav>
        <a href="dashboard.php">Home</a>
        
        <?php if (isset($_SESSION['user_id'])): ?>
            <!-- Links visible to all logged-in users -->
            <a href="profile.php">Profile</a>
            <a href="logout.php">Logout (<?php echo htmlspecialchars($_SESSION['username']); ?>)</a>
            
            <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
                <!-- Links visible only to admins -->
                <a href="admin_dashboard.php">Admin Dashboard</a>
                <a href="manage_users.php">Manage Users</a>
                <a href="system_settings.php">Settings</a>
            <?php endif; ?>
            
        <?php else: ?>
            <!-- Links visible to guests -->
            <a href="login.php">Login</a>
            <a href="register.php">Register</a>
        <?php endif; ?>
    </nav>

    <div class="container">
        <h1>Admin Dashboard</h1>
        <p>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>! You have admin privileges.</p>
        
        <h2>Admin Functions</h2>
        <ul>
            <li><a href="admin_dashboard.php">View User Statistics</a></li>
            <li><a href="manage_users.php">Manage Users</a></li>
            <li><a href="system_settings.php">System Settings</a></li>
        </ul>
        
        <a href="dashboard.php">Back to User Dashboard</a> | 
        <a href="logout.php">Logout</a>
    </div>
</body>
</html>