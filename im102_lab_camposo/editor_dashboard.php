<?php
session_start();

// Check if logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Check if role is editor
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'editor') {
    header("Location: login.php");
    exit();
}

$username = $_SESSION['username'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Editor Dashboard</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>

<div class="container">
    <h2>Editor Dashboard</h2>

    <p>Welcome, <strong><?php echo htmlspecialchars($username); ?></strong></p>
    <p>You are logged in as: <strong>Editor</strong></p>

    <hr>

    <h3>Editor Controls</h3>

    <p><a href="create_post.php">Create Post</a></p>
    <p><a href="edit_post.php">Edit Posts</a></p>
    <p><a href="view_posts.php">View All Posts</a></p>

    <hr>

    <h3>Navigation</h3>

    <p><a href="user_dashboard.php">User Dashboard</a></p>
    <p><a href="admin_dashboard.php">Admin Dashboard</a></p>
    <p><a href="logout.php">Logout</a></p>

</div>

</body>
</html>