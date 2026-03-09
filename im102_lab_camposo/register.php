<?php
require_once 'db_connect.php';

$errors = [];
$success = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    // Retrieve data
    $username = trim($_POST['username'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';

    // Validate input
    if (empty($username) || empty($email) || empty($password) || empty($confirm_password)) {
        $errors[] = "All fields are required.";
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format.";
    }

    if ($password !== $confirm_password) {
        $errors[] = "Passwords do not match.";
    }

    // Check for existing user (Prepared Statement)
    if (empty($errors)) {
        $stmt = $conn->prepare("SELECT id FROM users WHERE username = ? OR email = ?");
        $stmt->bind_param("ss", $username, $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $errors[] = "Username or email already exists.";
        }

        $stmt->close();
    }

    // Insert user if no errors
    if (empty($errors)) {

        // Hash password
        $password_hash = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $conn->prepare("INSERT INTO users (username, email, password_hash) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $username, $email, $password_hash);

        if ($stmt->execute()) {
            $success = "Registration successful!";
            header("Location: login.php"); // Redirect to login page after successful registration
            exit(); // Stop further code execution
        } else {
            $errors[] = "Something went wrong. Please try again.";
        }

        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Register</title>
<link rel="stylesheet" href="styles.css">
</head>

<body>

<div class="container">

<h2>Register</h2>

<form method="post" action="register.php">
<input type="text" name="username" placeholder="Username" required>
<input type="email" name="email" placeholder="Email" required>
<input type="password" name="password" placeholder="Password" required>
<input type="password" name="confirm_password" placeholder="Confirm Password" required>
<button type="submit">Register</button>
<p>Already have an account? <a href="login.php">Login</a></p>

</form>

</div>

</body>
</html>