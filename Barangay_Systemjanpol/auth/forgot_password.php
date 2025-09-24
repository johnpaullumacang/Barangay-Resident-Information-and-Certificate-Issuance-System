<?php
session_start();
include("../config/db.php");
include("../includes/header.php");

// PHPMailer
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Correct paths using __DIR__
require __DIR__ . '/../vendor/PHPMailer/src/Exception.php';
require __DIR__ . '/../vendor/PHPMailer/src/PHPMailer.php';
require __DIR__ . '/../vendor/PHPMailer/src/SMTP.php';

// Function to generate reset token
function generateToken($length = 50) {
    return bin2hex(random_bytes($length / 2));
}

// Handle form submission
if (isset($_POST['send_link'])) {
    $email = trim($_POST['email']);

    // Check if email exists
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        // Generate reset token & expiry (1 hour)
        $token = generateToken();
        $expiry = date("Y-m-d H:i:s", strtotime("+1 hour"));

        $update = $conn->prepare("UPDATE users SET reset_token=?, reset_expiry=? WHERE email=?");
        $update->bind_param("sss", $token, $expiry, $email);
        $update->execute();

        // Reset link
        $resetLink = "http://localhost/Barangay_Systemjanpol/auth/reset_password.php?token=" . $token;

        // PHPMailer setup
        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com';
            $mail->SMTPAuth   = true;
            $mail->Username   = 'your_email@gmail.com'; // Your Gmail
            $mail->Password   = 'your_app_password';    // Gmail App Password
            $mail->SMTPSecure = 'tls';
            $mail->Port       = 587;

            $mail->setFrom('your_email@gmail.com', 'Barangay System');
            $mail->addAddress($email, $row['full_name']);

            $mail->isHTML(true);
            $mail->Subject = 'Password Reset Request';
            $mail->Body    = "Hello <b>{$row['full_name']}</b>,<br><br>
                              Click the link below to reset your password:<br>
                              <a href='$resetLink'>$resetLink</a><br><br>
                              This link will expire in 1 hour.";

            $mail->send();
            $successMsg = "A password reset link has been sent to your email.";
        } catch (Exception $e) {
            $errorMsg = "Email could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }
    } else {
        $errorMsg = "Email not found. Please check or register first.";
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
    background: #0069d9;
    border: none;
    border-radius: 8px;
    padding: 10px;
    font-size: 16px;
}
.btn-custom:hover { background: #0056b3; }
.extra-links { margin-top: 15px; font-size: 14px; }
.extra-links a { text-decoration: none; color: #0069d9; font-weight: 500; }
.extra-links a:hover { text-decoration: underline; }
</style>

<div class="reset-card">
    <img src="../assets/img/barangay_logo.png" alt="Barangay Logo">
    <h3>Forgot Password</h3>
    <p class="text-muted">Enter your email to receive a reset link.</p>

    <?php if (isset($successMsg)) echo "<div class='alert alert-success'>$successMsg</div>"; ?>
    <?php if (isset($errorMsg)) echo "<div class='alert alert-danger'>$errorMsg</div>"; ?>

    <form method="POST">
        <input type="email" name="email" class="form-control mb-3" placeholder="Enter your email" required>
        <button type="submit" name="send_link" class="btn btn-custom w-100">Send Reset Link</button>
    </form>

    <div class="extra-links">
        <p><a href="login.php">Back to Login</a></p>
    </div>
</div>

<?php include("../includes/footer.php"); ?>
