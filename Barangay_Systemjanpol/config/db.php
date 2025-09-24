<?php
// config/db.php
$host = "localhost";
$user = "root"; // your MySQL username
$pass = "";     // your MySQL password
$db   = "barangay_systemjanpol"; // your database name

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Always start session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function check_login() {
    if (!isset($_SESSION['user_id'])) {
        header("Location: ../auth/login.php");
        exit();
    }
}
?>
