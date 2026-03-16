<?php
// admin_dashboard.php

session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// Check if user has admin role
if ($_SESSION['role'] !== 'admin') {
    // User is logged in but not an admin
    // Show "Access Denied" or redirect to a regular dashboard
    die('Access Denied. You do not have permission to view this page.');
}

echo "<h1>Admin Dashboard</h1>";
echo "<p>Welcome, Admin " . htmlspecialchars($_SESSION['username']) . "</p>";
echo "<a href='logout.php'>Logout</a>";
?>