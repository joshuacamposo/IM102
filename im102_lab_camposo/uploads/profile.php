<?php
session_start();

// Safe requires using __DIR__
require_once __DIR__ . '/../db_connect.php';
require_once __DIR__ . '/upload.php'; // Make sure upload.php exists

// Redirect if not logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$userId = $_SESSION['user_id'];
$message = '';
$error = '';

// Connect to database
$conn = getDbConnection();

// Get current user data
$stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
$stmt->bind_param("i", $userId);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();
$stmt->close();

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['profile_picture'])) {
    $result = uploadProfilePicture($_FILES['profile_picture']);

    if (isset($result['error'])) {
        $error = $result['error'];
    } else {
        // Delete old picture if exists
        if (!empty($user['profile_picture'])) {
            $oldFile = __DIR__ . '/../uploads/profiles/' . $user['profile_picture'];
            if (file_exists($oldFile)) {
                unlink($oldFile);
            }
        }

        // Update database
        $newFilename = $result['filename'];
        $stmt = $conn->prepare("UPDATE users SET profile_picture = ? WHERE id = ?");
        $stmt->bind_param("si", $newFilename, $userId);
        $stmt->execute();
        $stmt->close();

        $message = "Profile picture uploaded!";
        $user['profile_picture'] = $newFilename;
    }
}

$conn->close();

// Get profile picture path
$profilePic = (!empty($user['profile_picture']) && file_exists(__DIR__ . '/../uploads/profiles/' . $user['profile_picture'])) 
    ? '../uploads/profiles/' . $user['profile_picture'] 
    : 'https://via.placeholder.com/150?text=No+Photo';
?>

<!DOCTYPE html>
<html>
<head>
    <title>My Profile</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { 
            font-family: Arial, sans-serif; 
            background-color: #f5f5f5;
            margin: 0;
            padding: 20px;
        }
        .card { 
            background: white; 
            border: 1px solid #ddd;
            border-radius: 8px; 
            padding: 30px; 
            text-align: center;
            max-width: 500px;
            margin: 50px auto;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }
        h1 { 
            color: #333;
            font-size: 1.8em;
            margin-bottom: 10px;
        }
        .profile-img { 
            width: 150px; 
            height: 150px; 
            border-radius: 50%; 
            object-fit: cover; 
            border: 3px solid #007bff;
            margin: 20px auto;
        }
        .info { 
            margin: 20px 0; 
            text-align: left; 
        }
        .info p { 
            padding: 10px; 
            background: #f9f9f9;
            margin: 8px 0; 
            border-radius: 5px;
            color: #333;
        }
        .message { 
            padding: 10px; 
            background: #d4edda;
            color: #155724; 
            border-radius: 5px; 
            margin: 10px 0;
            border: 1px solid #c3e6cb;
        }
        .error { 
            padding: 10px; 
            background: #f8d7da;
            color: #721c24; 
            border-radius: 5px; 
            margin: 10px 0;
            border: 1px solid #f5c6cb;
        }
        h3 {
            color: #333;
            margin-top: 20px;
            margin-bottom: 15px;
            font-size: 1.1em;
        }
        form { 
            margin-top: 20px;
            padding: 15px;
            background: #fafafa;
            border-radius: 5px;
        }
        input[type="file"] { 
            margin: 10px 0;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 5px;
            width: 100%;
        }
        small {
            display: block;
            margin: 10px 0;
            color: #666;
        }
        button { 
            padding: 10px 20px; 
            background: #007bff;
            color: white; 
            border: none; 
            border-radius: 5px; 
            cursor: pointer;
            font-weight: 600;
            margin-top: 10px;
        }
        button:hover { 
            background: #0056b3;
        }
        .btn-back { 
            display: inline-block; 
            margin-top: 20px; 
            color: #007bff; 
            text-decoration: none;
            font-weight: 600;
        }
        .btn-back:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="card">
        <h1>My Profile</h1>
        
        <?php if ($message): ?>
            <div class="message"><?php echo htmlspecialchars($message); ?></div>
        <?php endif; ?>
        
        <?php if ($error): ?>
            <div class="error"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>
        
        <img src="<?php echo htmlspecialchars($profilePic); ?>" alt="Profile" class="profile-img">
        
        <div class="info">
            <p><strong>Username:</strong> <?php echo htmlspecialchars($user['username']); ?></p>
            <p><strong>Email:</strong> <?php echo htmlspecialchars($user['email']); ?></p>
            <p><strong>Role:</strong> <?php echo htmlspecialchars($user['role']); ?></p>
        </div>
        
        <h3>Change Profile Picture</h3>
        <form method="POST" enctype="multipart/form-data">
            <input type="file" name="profile_picture" accept="image/*">
            <br>
            <small style="color: #666;">Allowed: JPG, PNG, GIF (Max: 2MB)</small>
            <br><br>
            <button type="submit">Upload</button>
        </form>
        
        <a href="../dashboard.php" class="btn-back">← Back to Dashboard</a>
    </div>
</body>
</html>