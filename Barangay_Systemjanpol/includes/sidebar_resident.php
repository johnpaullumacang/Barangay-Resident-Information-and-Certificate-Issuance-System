<!-- sidebar_resident.php -->
<div class="d-flex flex-column flex-shrink-0 p-3 text-bg-dark" style="width: 250px; height: 100vh; position: fixed;">
  <a href="dashboard.php" class="d-flex align-items-center mb-3 mb-md-0 me-md-auto text-white text-decoration-none">
    <span class="fs-4">👤 Resident Panel</span>
  </a>
  <hr>
  <ul class="nav nav-pills flex-column mb-auto">
    <li class="nav-item">
      <a href="dashboard.php" class="nav-link text-white">
        <i class="bi bi-speedometer2"></i> Dashboard
      </a>
    </li>
    <li>
      <a href="profile.php" class="nav-link text-white">
        <i class="bi bi-person"></i> Profile
      </a>
    </li>
    <li>
      <a href="medical_history.php" class="nav-link text-white">
        <i class="bi bi-heart-pulse"></i> Medical History
      </a>
    </li>
    <li>
      <a href="request.php" class="nav-link text-white">
        <i class="bi bi-file-earmark-text"></i> Request Certificate
      </a>
    </li>
    <li>
      <a href="payments.php" class="nav-link text-white">
        <i class="bi bi-cash-stack"></i> Payments
      </a>
    </li>
    <li>
      <a href="certificates.php" class="nav-link text-white">
        <i class="bi bi-award"></i> Certificates
      </a>
    </li>
    <li>
      <a href="notifications.php" class="nav-link text-white">
        <i class="bi bi-bell"></i> Notifications
      </a>
    </li>
  </ul>
  <hr>
  <div>
    <a href="../auth/logout.php" class="btn btn-danger w-100">
      <i class="bi bi-box-arrow-right"></i> Logout
    </a>
  </div>
</div>

<!-- Bootstrap Icons -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
