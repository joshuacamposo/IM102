<?php
// auth_functions.php

function hasRole($role) {
    return isset($_SESSION['role']) && $_SESSION['role'] === $role;
}

function requireRole($role) {
    requireLogin();
    
    if (!hasRole($role)) {
        die('Access Denied!');
    }
}
function requireLogin() {
    session_start();
    if (!isset($_SESSION['user_id'])) {
        header('Location: login.php');
        exit();
    }
}

function requireRole($requiredRole) {
    requireLogin(); // First, ensure they are logged in
    
    if ($_SESSION['role'] !== $requiredRole) {
        die('Access Denied. Insufficient permissions.');
    }
}
?>