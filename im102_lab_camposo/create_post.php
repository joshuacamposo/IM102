<?php
session_start();
require_once 'db_connect.php';

// Check if logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$message = '';
$error = '';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Create Post</title>
    <style>
        body { font-family: Arial; max-width: 600px; margin: 50px auto; padding: 20px; }
        .container { background: white; padding: 30px; border-radius: 8px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); }
        h2 { color: #333; }
        form { margin-top: 20px; }
        input[type="text"], textarea { width: 100%; padding: 10px; margin: 10px 0; border: 1px solid #ddd; border-radius: 5px; font-family: Arial; }
        textarea { min-height: 200px; resize: vertical; }
        button { padding: 10px 20px; background: #007bff; color: white; border: none; border-radius: 5px; cursor: pointer; font-weight: 600; }
        button:hover { background: #0056b3; }
        .message { padding: 10px; background: #d4edda; color: #155724; border-radius: 5px; margin: 10px 0; }
        .error { padding: 10px; background: #f8d7da; color: #721c24; border-radius: 5px; margin: 10px 0; }
        a { color: #007bff; text-decoration: none; }
        a:hover { text-decoration: underline; }
    </style>
</head>
<body>
    <div class="container">
        <h2>Create Post</h2>
        
        <?php if ($message): ?>
            <div class="message"><?php echo htmlspecialchars($message); ?></div>
        <?php endif; ?>
        
        <?php if ($error): ?>
            <div class="error"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>
        
        <form method="POST" action="save_post.php">
            <input type="text" name="title" placeholder="Post Title" required>
            <textarea name="content" placeholder="Write your post content..." required></textarea>
            <button type="submit">Create Post</button>
        </form>
        
        <br>
        <a href="admin_dashboard.php">← Back to Dashboard</a>
    </div>
</body>
</html>