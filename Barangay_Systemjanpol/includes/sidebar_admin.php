<!-- sidebar_admin.php -->
<div class="d-flex flex-column flex-shrink-0 p-3 text-bg-dark" style="width: 250px; height: 100vh; position: fixed;">
  <a href="dashboard.php" class="d-flex align-items-center mb-3 mb-md-0 me-md-auto text-white text-decoration-none">
    <span class="fs-4">⚙️ Admin Panel</span>
  </a>
  <hr>
  <ul class="nav nav-pills flex-column mb-auto">
    <li class="nav-item">
      <a href="dashboard.php" class="nav-link text-white">
        <i class="bi bi-speedometer2"></i> Dashboard
      </a>
    </li>
    <li>
      <a href="residents.php" class="nav-link text-white">
        <i class="bi bi-people"></i> Residents
      </a>
    </li>
    <li>
      <a href="medical.php" class="nav-link text-white">
        <i class="bi bi-heart-pulse"></i> Medical Records
      </a>
    </li>
    <li>
      <a href="certificates.php" class="nav-link text-white">
        <i class="bi bi-file-earmark-text"></i> Certificates
      </a>
    </li>
    <li>
      <a href="payments.php" class="nav-link text-white">
        <i class="bi bi-cash-stack"></i> Payments
      </a>
    </li>
    <li>
      <a href="issuance.php" class="nav-link text-white">
        <i class="bi bi-journal-text"></i> Issuance
      </a>
    </li>
    <li>
      <a href="reports.php" class="nav-link text-white">
        <i class="bi bi-bar-chart"></i> Reports
      </a>
    </li>
    <li>
      <a href="notifications.php" class="nav-link text-white">
        <i class="bi bi-bell"></i> Notifications
      </a>
    </li>
    <li>
      <a href="audit.php" class="nav-link text-white">
        <i class="bi bi-list-check"></i> Audit Log
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
