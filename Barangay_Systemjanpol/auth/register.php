<?php
include("../config/db.php");

// ======================
// Registration Processing
// Must be before any HTML output to allow header() redirect
// ======================
if (isset($_POST['register'])) {
    $full_name = trim($_POST['full_name']);
    $username  = trim($_POST['username']);
    $email     = trim($_POST['email']);
    $password  = password_hash($_POST['password'], PASSWORD_BCRYPT);
    $role      = 'resident';
    $user_id   = substr(uniqid("USR"), 0, 15);

    // Check if username or email already exists
    $check_stmt = $conn->prepare("SELECT * FROM users WHERE username = ? OR email = ?");
    $check_stmt->bind_param("ss", $username, $email);
    $check_stmt->execute();
    $check_result = $check_stmt->get_result();

    if ($check_result->num_rows > 0) {
        $error_msg = "Username or email already exists.";
    } else {
        // Insert new user
        $stmt = $conn->prepare("INSERT INTO users (user_id, username, email, password, role, full_name) 
                                VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssss", $user_id, $username, $email, $password, $role, $full_name);

        if ($stmt->execute()) {
            // Redirect to resident info form
            header("Location: resident_form.php?user_id=" . $user_id);
            exit;
        } else {
            $error_msg = "Error: " . $stmt->error;
        }
        $stmt->close();
    }
    $check_stmt->close();
}
?>

<?php
include("../includes/header.php");
?>

<style>
/* Background + overlay */
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
.register-wrapper { position: relative; z-index: 2; display: flex; justify-content: center; align-items: center; width: 100%; }
.register-card {
    max-width: 450px;
    width: 100%;
    background: #09f8f8ff;
    border-radius: 25%;
    object-fit: cover;
    border: 3px solid #fff;
    box-shadow: 0 4px 8px rgba(0,0,0,0.3);
    padding: 10px 30px;
    text-align: center;
    animation: fadeIn 0.6s ease-in-out;
}
.register-card img { width: 100px; object-fit: cover; border-radius: 500px; margin-bottom: 10px; }
.register-card h3 { margin-bottom: 10px; font-weight: 700; color: #2c3e50; }
.register-card p { margin-bottom: 25px; font-size: 14px; color: #6c757d; }
.form-control { border-radius: 10px; padding: 12px; border: 1px solid #ddd; transition: all 0.3s ease; }
.form-control:focus { border-color: #28a745; box-shadow: 0 0 6px rgba(40,167,69,0.4); }
.btn-custom { background: linear-gradient(135deg, #2575fc, #6a11cb); border: none; border-radius: 10px; padding: 12px; font-size: 16px; font-weight: 600; color: #fff; cursor: pointer; transition: all 0.3s ease; }
.btn-custom:hover { background: linear-gradient(135deg, #6a11cb, #2575fc); transform: translateY(-2px); box-shadow: 0 6px 15px rgba(0,0,0,0.25); }
.btn-custom:active { transform: scale(0.97); }
.extra-links { margin-top: 20px; font-size: 14px; }
.extra-links a { text-decoration: none; color: #2575fc; font-weight: 500; }
.extra-links a:hover { text-decoration: underline; }
.alert { margin-top: 15px; padding: 12px; border-radius: 8px; font-size: 14px; }
@keyframes fadeIn { from { opacity: 0; transform: translateY(-15px); } to { opacity: 1; transform: translateY(0); } }
@media (prefers-color-scheme: dark) {
    .register-card { background: #1e1e2f; color: #f0f0f0; }
    .register-card h3 { color: #11fddeff; }
    .form-control { background: #2a2a3d; border: 1px solid #444; color: #f0f0f0; }
    .form-control:focus { border-color: #28a745; box-shadow: 0 0 6px rgba(40,167,69,0.6); }
    .extra-links a { color: #6a11cb; }
}
</style>

<div class="register-wrapper">
    <div class="register-card">
        <img src="../assets/img/logobrgy.jpg" alt="Barangay Logo">
        <h3>Resident Registration</h3>
        <p>Create your account to access the system.</p>

        <form method="POST">
            <input type="text" name="full_name" class="form-control mb-3" placeholder="Full Name" required>
            <input type="text" name="username" class="form-control mb-3" placeholder="Username" required>
            <input type="email" name="email" class="form-control mb-3" placeholder="Email" required>
            <input type="password" name="password" class="form-control mb-3" placeholder="Password" required>
            <button type="submit" name="register" class="btn btn-custom w-100">Register</button>
        </form>

        <div class="extra-links">
            <p>Already have an account? <a href="login.php">Login here</a></p>
        </div>

        <?php
        // Display error message if any
        if (!empty($error_msg)) {
            echo "<div class='alert alert-danger'>{$error_msg}</div>";
        }
        ?>
    </div>
</div>

<?php include("../includes/footer.php"); ?>
