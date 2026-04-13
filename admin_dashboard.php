<?php
session_start();

// Check if logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Check if admin
if ($_SESSION['role'] !== 'admin') {
    header("Location: admin_dashboard.php");
    exit();
}

$username = $_SESSION['username'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>

<div class="container">
    <h2>Admin Dashboard</h2>
    <p>Welcome Admin, <strong><?php echo htmlspecialchars($username); ?></strong></p>

    <hr>

    <h3>Admin Controls</h3>
    <p><a href="manage_users.php">Manage Users</a></p>
    <p><a href="create_post.php">Create Post</a></p>
    <p><a href="view_posts.php">View All Posts</a></p>

    <hr>

    <h3>Navigation</h3>
    <p><a href="user_dashboard.php">User Dashboard</a></p>
    <p><a href="editor_dashboard.php">Editor Dashboard</a></p>
    <p><a href="logout.php">Logout</a></p>
</div>

</body>
</html>