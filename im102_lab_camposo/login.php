<?php
session_start();

require_once 'db_connect.php';

// Use ONLY this connection method
$conn = getDbConnection();
$conn->set_charset("utf8mb4");

$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $user = trim($_POST['username']);
    $pass = $_POST['password'];

    $sql = "SELECT id, username, password_hash, role FROM users WHERE username = ?";
    $stmt = $conn->prepare($sql);

    if (!$stmt) {
        die("Prepare failed: " . $conn->error);
    }

    $stmt->bind_param("s", $user);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {

        $row = $result->fetch_assoc();

        // Verify password
        if (password_verify($pass, $row['password_hash'])) {

            $_SESSION['user_id'] = $row['id'];
            $_SESSION['username'] = $row['username'];

            // store role if exists
            if (!empty($row['role'])) {
                $_SESSION['role'] = $row['role'];
            }

            // Redirect
            header("Location: user_dashboard.php");
            exit();

        } else {
            $error = "Invalid username or password!";
        }

    } else {
        $error = "Invalid username or password!";
    }

    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="styles.css">

    <style>
        .error { color: red; margin-bottom: 10px; }
        .container { width: 400px; margin: auto; }
        .form-group { margin-bottom: 10px; }
    </style>
</head>
<body>

<div class="container">

    <h2>Login</h2>

    <?php if (!empty($error)): ?>
        <div class="error">
            <?php echo htmlspecialchars($error); ?>
        </div>
    <?php endif; ?>

    <form method="POST" action="">

        <div class="form-group">
            <label for="username">Username:</label>
            <input type="text" name="username" id="username" required>
        </div>

        <div class="form-group">
            <label for="password">Password:</label>
            <input type="password" name="password" id="password" required>
        </div>

        <button type="submit">Login</button>

    </form>

    <div class="register-link">
        Don't have an account? <a href="register.php">Register here</a>
    </div>

</div>

</body>
</html>