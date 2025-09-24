<?php
session_start();
include("../config/db.php");

$error = ""; // store error messages

// Handle login before any HTML output
if (isset($_POST['login'])) {
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT * FROM users WHERE username=?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {

        // Check password
        if (password_verify($password, $row['password'])) {

            $_SESSION['user_id'] = $row['user_id'];

            // Normalize role for consistency
            $role = strtolower(str_replace(' ', '-', trim($row['role'])));
            $_SESSION['role'] = $role;

            // Redirect based on role
            switch ($role) {
                case 'superadmin':
                case 'super-admin':
                case 'admin':
                    header("Location: ../admin/dashboard.php");
                    exit;
                case 'resident':
                    header("Location: ../resident/profile.php");
                    exit;
                default:
                    $error = "Role not recognized.";
            }

        } else {
            $error = "Invalid password.";
        }

    } else {
        $error = "User not found.";
    }
    $stmt->close();
}
?>

<?php include("../includes/header.php"); ?>

<style>
/* Fullscreen container */
body { 
    margin: 0;
    padding: 0;
    height: 100vh;
    width: 100%;
    background: url('../assets/img/jan.jpg') no-repeat center center fixed; 
    background-size: cover;
    font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif; 
    display: flex;
    justify-content: center;
    align-items: center;
    position: relative;
    overflow: hidden;
}
body::before {
    content: "";
    position: absolute;
    top: 0; left: 0; right: 0; bottom: 0;
    background: rgba(0,0,0,0.4);
    backdrop-filter: blur(3px);
}

/* Center container */
.login-wrapper {
    position: relative;
    z-index: 2;
    display: flex;
    justify-content: center;
    align-items: center;
    width: 100%;
}

/* Login card */
.login-card {
    max-width: 450px;
    width: 100%;
    background: #06fcc6ff;
    border: 3px solid #fff;
    border-radius: 85px;
    box-shadow: 0 8px 25px rgba(0,0,0,0.2);
    padding: 40px 30px;
    text-align: center;
    animation: fadeIn 0.6s ease-in-out;
}
.login-card img { 
    width: 200px;
    border-radius: 95px;
    object-fit: cover; 
    height: 200px;
    margin-bottom: 15px; 
}
.login-card h3 { 
    margin-bottom: 25px; 
    font-weight: 700; 
    color: #2c3e50; 
}

/* Inputs */
.form-control { 
    border-radius: 10px; 
    padding: 12px;
    border: 1px solid #ddd;
    transition: all 0.3s ease;
}
.form-control:focus {
    border-color: #2575fc;
    box-shadow: 0 0 6px rgba(37,117,252,0.5);
}

/* Button */
.btn-custom {
    background: linear-gradient(135deg, #2575fc, #6a11cb);
    border: none;
    border-radius: 10px;
    padding: 12px;
    font-size: 16px;
    font-weight: 600;
    color: #fff;
    cursor: pointer;
    transition: all 0.3s ease;
}
.btn-custom:hover { 
    background: linear-gradient(135deg, #6a11cb, #2575fc);
    transform: translateY(-2px);
    box-shadow: 0 6px 15px rgba(0,0,0,0.25);
}
.btn-custom:active {
    transform: scale(0.97);
}

/* Links */
.extra-links { 
    margin-top: 20px; 
    font-size: 14px; 
}
.extra-links a { 
    text-decoration: none; 
    color: #2575fc; 
    font-weight: 500; 
}
.extra-links a:hover { 
    text-decoration: underline; 
}

/* Alerts */
.alert-custom {
    margin-top: 15px;
    padding: 12px;
    border-radius: 8px;
    font-size: 14px;
}

/* Fade animation */
@keyframes fadeIn {
    from { opacity: 0; transform: translateY(-15px); }
    to { opacity: 1; transform: translateY(0); }
}

/* Dark mode support */
@media (prefers-color-scheme: dark) {
    .login-card {
        background: #1e1e2f;
        color: #f0f0f0;
    }
    .login-card h3 {
        color: #f9f9f9;
    }
    .form-control {
        background: #2a2a3d;
        border: 1px solid #444;
        color: #f0f0f0;
    }
    .form-control:focus {
        border-color: #6a11cb;
        box-shadow: 0 0 6px rgba(106,17,203,0.6);
    }
    .extra-links a {
        color: #6a11cb;
    }
}
</style>

<div class="login-wrapper">
    <div class="login-card">
        <img src="../assets/img/logobrgy.jpg" alt="Barangay Logo">
        <h3>LOGIN</h3>
        <form method="POST">
            <input type="text" name="username" class="form-control mb-3" placeholder="Username" required>
            <input type="password" name="password" class="form-control mb-3" placeholder="Password" required>
            <button type="submit" name="login" class="btn btn-custom w-100">Login</button>
        </form>

        <div class="extra-links">
            <p><a href="forgot_password.php">Forgot Password?</a></p>
            <p>Don't have an account? <a href="register.php">Register here</a></p>
        </div>

        <?php if (!empty($error)): ?>
            <div class="alert alert-danger alert-custom">
                <?= htmlspecialchars($error) ?>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php include("../includes/footer.php"); ?>
