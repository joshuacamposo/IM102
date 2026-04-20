<?php
session_start();
require_once 'db_connect.php';

// Check if logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Get database connection
$conn = getDbConnection();

// Fetch all posts
$query = "
    SELECT posts.id, posts.title, posts.content, posts.created_at, users.username
    FROM posts
    JOIN users ON posts.user_id = users.id
    ORDER BY posts.created_at DESC
";

$stmt = $conn->prepare($query);
$stmt->execute();
$result = $stmt->get_result();
$posts = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>View All Posts</title>
    <style>
        body { font-family: Arial; max-width: 800px; margin: 50px auto; padding: 20px; }
        .container { background: white; padding: 30px; border-radius: 8px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); }
        h2 { color: #333; margin-bottom: 20px; }
        .post { border: 1px solid #ddd; padding: 15px; margin-bottom: 20px; border-radius: 5px; background: #f9f9f9; }
        .post h3 { color: #007bff; margin-top: 0; }
        .post-meta { font-size: 0.9em; color: #666; margin: 10px 0; }
        .post-content { color: #333; line-height: 1.6; margin: 15px 0; }
        a { color: #007bff; text-decoration: none; }
        a:hover { text-decoration: underline; }
        .btn-back { display: inline-block; margin-bottom: 20px; }
        .no-posts { text-align: center; color: #999; padding: 40px 0; }
    </style>
</head>
<body>
    <div class="container">
        <h2>All Posts</h2>
        
        <a href="admin_dashboard.php" class="btn-back">← Back to Dashboard</a>
        
        <hr>
        
        <?php if (empty($posts)): ?>
            <div class="no-posts">
                <p>No posts found. <a href="create_post.php">Create one now</a></p>
            </div>
        <?php else: ?>
            <?php foreach ($posts as $post): ?>
                <div class="post">
                    <h3><?php echo htmlspecialchars($post['title']); ?></h3>
                    <div class="post-meta">
                        <strong>By:</strong> <?php echo htmlspecialchars($post['username']); ?> | 
                        <strong>Date:</strong> <?php echo date('M d, Y H:i', strtotime($post['created_at'])); ?>
                    </div>
                    <div class="post-content">
                        <?php echo nl2br(htmlspecialchars($post['content'])); ?>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</body>
</html>