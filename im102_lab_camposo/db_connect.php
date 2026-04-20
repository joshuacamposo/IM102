<?php

$host = "localhost";
$dbname = "im102_lab_camposo";
$username = "root";
$password = "";

function getDbConnection() {
    global $host, $dbname, $username, $password;

    $conn = new mysqli($host, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Database connection failed: " . $conn->connect_error);
    }

    $conn->set_charset("utf8mb4");

    return $conn;
}
?>