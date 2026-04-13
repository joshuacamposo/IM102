<?php
session_start();
require_once 'db_connect.php';
require_once 'validate.php';
require_once 'auth_function.php';
?>

<h2>Create Post</h2>

<form method="POST" action="save_post.php">
    <input type="text" name="title" placeholder="Title" required><br><br>
    <textarea name="content" placeholder="Write something..." required></textarea><br><br>
    <button type="submit">Post</button>
</form>