<?php
// Start session only if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/**
 * Check if user is logged in
 */
if (!function_exists('requireLogin')) {
    function requireLogin() {
        if (!isset($_SESSION['user_id'])) {
            header("Location: login.php");
            exit();
        }
    }
}

/**
 * Check if user has a specific role
 */
if (!function_exists('requireRole')) {
    function requireRole($role) {
        if (!isset($_SESSION['role']) || $_SESSION['role'] !== $role) {
            header("Location: login.php");
            exit();
        }
    }
}

/**
 * Redirect user based on role after login
 */
if (!function_exists('redirectByRole')) {
    function redirectByRole() {
        if (isset($_SESSION['role'])) {
            if ($_SESSION['role'] === 'admin') {
                header("Location: admin_dashboard.php");
                exit();
            } else {
                header("Location: user_dashboard.php");
                exit();
            }
        }
    }
}

/**
 * Get logged-in username safely
 */
if (!function_exists('getUsername')) {
    function getUsername() {
        return isset($_SESSION['username']) 
            ? htmlspecialchars($_SESSION['username']) 
            : 'Guest';
    }
}
?>