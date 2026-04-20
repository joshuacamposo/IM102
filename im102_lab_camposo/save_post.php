<?php
session_start();
require_once 'db_connect.php';

// Check if logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Check if form was submitted
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: create_post.php");
    exit();
}

// Get form data
$title = isset($_POST['title']) ? trim($_POST['title']) : '';
$content = isset($_POST['content']) ? trim($_POST['content']) : '';
$user_id = $_SESSION['user_id'];

// Validate input
if (empty($title)) {
    $_SESSION['error'] = 'Title is required';
    header("Location: create_post.php");
    exit();
}

if (empty($content)) {
    $_SESSION['error'] = 'Content is required';
    header("Location: create_post.php");
    exit();
}

// Connect to database
$conn = getDbConnection();

// Check if posts table exists, if not create it
$tableCheck = $conn->query("SHOW TABLES LIKE 'posts'");
if ($tableCheck->num_rows == 0) {
    $createTableSQL = "
        CREATE TABLE posts (
            id INT AUTO_INCREMENT PRIMARY KEY,
            user_id INT NOT NULL,
            title VARCHAR(255) NOT NULL,
            content LONGTEXT NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
        )
    ";
    
    if (!$conn->query($createTableSQL)) {
        $_SESSION['error'] = 'Failed to create posts table';
        $conn->close();
        header("Location: create_post.php");
        exit();
    }
}

// Insert post into database
$stmt = $conn->prepare("INSERT INTO posts (user_id, title, content) VALUES (?, ?, ?)");
$stmt->bind_param("iss", $user_id, $title, $content);

if ($stmt->execute()) {
    $_SESSION['message'] = 'Post created successfully!';
    $stmt->close();
    $conn->close();
    header("Location: view_post.php");
    exit();
} else {
    $_SESSION['error'] = 'Failed to create post: ' . $stmt->error;
    $stmt->close();
    $conn->close();
    header("Location: create_post.php");
    exit();
}
?>
