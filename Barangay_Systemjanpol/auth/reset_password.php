<?php
session_start();
include("../config/db.php");
include("../includes/header.php");

// Check if token is provided
if (!isset($_GET['token'])) {
    die("<div class='alert alert-danger mt-5 text-center'>Invalid request. No token provided.</div>");
}

$token = $_GET['token'];

// Verify token and expiry
$stmt = $conn->prepare("SELECT * FROM users WHERE reset_token=? AND reset_expiry > NOW()");
$stmt->bind_param("s", $token);
$stmt->execute();
$result = $stmt->get_result();

if (!$row = $result->fetch_assoc()) {
    die("<div class='alert alert-danger mt-5 text-center'>Invalid or expired token.</div>");
}

// Handle password reset form
if (isset($_POST['reset_password'])) {
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    if ($new_password !== $confirm_password) {
        $errorMsg = "Passwords do not match.";
    } else {
        $hashed = password_hash($new_password, PASSWORD_DEFAULT);

        $update = $conn->prepare("UPDATE users SET password=?, reset_token=NULL, reset_expiry=NULL WHERE reset_token=?");
        $update->bind_param("ss", $hashed, $token);

        if ($update->execute()) {
            $successMsg = "Your password has been reset successfully. <a href='login.php'>Login here</a>.";
        } else {
            $errorMsg = "Something went wrong. Please try again.";
        }
    }
}
?>

<style>
body { background: #f5f6fa; font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif; }
.reset-card {
    max-width: 450px;
    margin: 80px auto;
    background: #ffffff;
    border-radius: 12px;
    box-shadow: 0 6px 18px rgba(0,0,0,0.1);
    padding: 30px;
    text-align: center;
}
.reset-card img { width: 90px; margin-bottom: 15px; }
.reset-card h3 { margin-bottom: 20px; font-weight: bold; color: #2c3e50; }
.form-control { border-radius: 8px; }
.btn-custom {
    background: #28a745;
    border: none;
    border-radius: 8px;
    padding: 10px;
    font-size: 16px;
}
.btn-custom:hover { background: #218838; }
.extra-links { margin-top: 15px; font-size: 14px; }
.extra-links a { text-decoration: none; color: #0069d9; font-weight: 500; }
.extra-links a:hover { text-decoration: underline; }
</style>

<div class="reset-card">
    <img src="../assets/img/barangay_logo.png" alt="Barangay Logo">
    <h3>Reset Password</h3>
    <p class="text-muted">Enter your new password below.</p>

    <?php if (isset($successMsg)) echo "<div class='alert alert-success'>$successMsg</div>"; ?>
    <?php if (isset($errorMsg)) echo "<div class='alert alert-danger'>$errorMsg</div>"; ?>

    <?php if (!isset($successMsg)): ?>
        <form method="POST">
            <input type="password" name="new_password" class="form-control mb-3" placeholder="New Password" required>
            <input type="password" name="confirm_password" class="form-control mb-3" placeholder="Confirm Password" required>
            <button type="submit" name="reset_password" class="btn btn-custom w-100">Reset Password</button>
        </form>
    <?php endif; ?>

    <div class="extra-links">
        <p><a href="login.php">Back to Login</a></p>
    </div>
</div>

<?php include("../includes/footer.php"); ?>
