<?php
session_start();

// If already logged in, redirect based on role
if (isset($_SESSION['role'])) {
  if ($_SESSION['role'] == 'admin') {
    header("Location: admin/dashboard.php");
    exit();
  } else {
    header("Location: resident/dashboard.php");
    exit();
  }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Barangay Resident Information & Certificate Issuance System</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
     background: url('../assets/img/hall 1.jpg') no-repeat center center fixed; 
      height: 100vh;
      display: flex;
      justify-content: center;
      align-items: center;
      color: #36ffd4ff;
    }
    .card {
      padding: 30px;
      background: #06ffdeff;
      border-radius: 24px;
      box-shadow: 0px 5px 15px rgba(0,0,0,0.3);
    }
    .btn-custom {
      width: 100%;
      margin-bottom: 10px;
    }
  </style>
</head>
<body>
  <div class="card text-center bg-light text-dark">
    <h2 class="mb-4">Barangay Resident   System</h2>
    <a href="auth/login.php" class="btn btn-primary btn-custom">Login</a>
    <a href="auth/register.php" class="btn btn-success btn-custom">Register as Resident</a>
  </div>
</body>
</html>
