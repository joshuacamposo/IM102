<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/../db_connect.php';


function uploadProfilePicture($file) {
    // Check if file exists
    if ($file['error'] === 4) {
        return ['error' => 'No file selected'];
    }
    
    // Check for upload error
    if ($file['error'] !== 0) {
        return ['error' => 'Upload failed. Error code: ' . $file['error']];
    }
    
    // Check file size (max 2MB)
    $maxSize = 2 * 1024 * 1024;
    if ($file['size'] > $maxSize) {
        return ['error' => 'File too big! Max 2MB only.'];
    }
    
    // Check file type (only images)
    $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
    if (!in_array($file['type'], $allowedTypes)) {
        return ['error' => 'Only JPG, PNG, GIF allowed!'];
    }
    
    // Create folder if not exists
    $uploadDir = __DIR__ . '/../uploads/profiles';
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0755, true);
    }
    
    // Generate safe filename
    $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
    $newName = 'profile_' . uniqid() . '.' . $extension;
    
    // Move file to uploads folder
    $destination = $uploadDir . '/' . $newName;
    if (move_uploaded_file($file['tmp_name'], $destination)) {
        return ['success' => true, 'filename' => $newName];
    }
    
    return ['error' => 'Failed to save file'];
}