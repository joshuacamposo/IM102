<?php
session_start();
require_once 'db_connect.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$userId = $_SESSION['user_id'];

// Get user data
$conn = getDbConnection();
$stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
$stmt->bind_param("i", $userId);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();
$conn->close();

// Get profile picture
$profilePic = 'uploads/profiles/' . $user['profile_picture'];
if (!$user['profile_picture'] || !file_exists($profilePic)) {
    $profilePic = 'https://via.placeholder.com/50?text=👤';
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Dashboard</title>
    <style>
        body { font-family: Arial; max-width: 800px; margin: 50px auto; padding: 20px; }
        .header { display: flex; align-items: center; gap: 20px; background: white; padding: 20px; border-radius: 10px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); }
        .avatar { width: 60px; height: 60px; border-radius: 50%; object-fit: cover; }
        .welcome { font-size: 1.2em; }
        .nav { margin-top: 20px; }
        .nav a { display: inline-block; padding: 10px 20px; background: #10cf70; color: white; text-decoration: none; border-radius: 5px; margin-right: 10px; }
        .nav a:hover { background: #10d874; }
        .logout { background: #dc3545 !important; }
    </style>
</head>
<body>
    <div class="header">
        <img src="<?php echo htmlspecialchars($profilePic); ?>" alt="Profile" class="avatar">
        <div>
            <h1>Welcome, <?php echo htmlspecialchars($user['username']); ?>!</h1>
            <p>Role: <strong><?php echo htmlspecialchars($user['role']); ?></strong></p>
        </div>
    </div>
    
    <div class="nav">
        <a href="uploads/profile.php">My Profile</a>
        <?php if ($user['role'] === 'admin'): ?>
            <a href="admin_dashboard.php">Admin Dashboard</a>
        <?php endif; ?>
        <a href="logout.php" class="logout">Logout</a>
    </div>
</body>
</html>