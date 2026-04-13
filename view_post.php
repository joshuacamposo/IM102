<?php
require_once 'db_connect.php';

$stmt = $conn->query("
    SELECT posts.*, users.username
    FROM posts
    JOIN users ON posts.user_id = users.id
    ORDER BY created_at DESC
");

$posts = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<h2>All Posts</h2>

<a href="create_post.php">Create Post</a>

<hr>

<?php foreach ($posts as $post): ?>
    <div style="border:1px solid #ccc; padding:10px; margin-bottom:10px;">
        <h3><?= htmlspecialchars($post['title']) ?></h3>
        <p><?= nl2br(htmlspecialchars($post['content'])) ?></p>
        <small>
            Posted by: <b><?= htmlspecialchars($post['username']) ?></b> |
            <?= $post['created_at'] ?>
        </small>
    </div>
<?php endforeach; ?>