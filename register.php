<?php
session_start();

require_once 'db_connect.php';
require_once 'validate.php';

$errors = [];
$success = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $errors = validateRegistration($_POST);

    if ($_POST['password'] !== $_POST['confirm_password']) {
        $errors[] = "Passwords do not match!";
    }

    if (empty($errors)) {

        // ✅ FIX: PROPER CONNECTION
        $conn = getDbConnection();

        $username = trim($_POST['username']);
        $email = trim($_POST['email']);
        $password = $_POST['password'];

        // check existing user
        $stmt = $conn->prepare("SELECT id FROM users WHERE username = ? OR email = ?");
        $stmt->bind_param("ss", $username, $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $errors[] = "Username or email already exists!";
        } else {

            $password_hash = password_hash($password, PASSWORD_DEFAULT);

            $stmt = $conn->prepare("
                INSERT INTO users (username, email, password_hash, role)
                VALUES (?, ?, ?, 'user')
            ");

            $stmt->bind_param("sss", $username, $email, $password_hash);

            if ($stmt->execute()) {
                header("Location: login.php?registered=success");
                exit();
            } else {
                $errors[] = "Registration failed!";
            }
        }

        $conn->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
<div class="container">
    <style>
    .error {
        color: red;
        margin-bottom: 10px;
        
    }

    .success {
        color: green;
        margin-bottom: 10px;
        
    }
</style>

<h2>Register</h2>

<!-- Display errors -->
<?php if (!empty($errors)): ?>
    <div class="error">
        <ul>
            <?php foreach($errors as $err): ?>
                <li><?php echo htmlspecialchars($err); ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>

<!-- Display success -->
<?php if (!empty($success)): ?>
    <div class="success">
        <?php echo htmlspecialchars($success); ?>
    </div>
<?php endif; ?>

<form method="post" action="register.php">
    <input type="text" name="username" placeholder="Username" value="<?php echo htmlspecialchars($username ?? ''); ?>" required>
    <input type="email" name="email" placeholder="Email" value="<?php echo htmlspecialchars($email ?? ''); ?>" required>
    <input type="password" name="password" placeholder="Password" required>
    <input type="password" name="confirm_password" placeholder="Confirm Password" required>
    <button type="submit">Register</button>
    <p>Already have an account? <a href="login.php">Login</a></p>
</form>

</div>
</body>
</html>