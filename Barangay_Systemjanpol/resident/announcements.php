<?php
session_start();
include("../config/db.php");
include("../includes/header.php");
include("../includes/sidebar_resident.php");
?>

<div class="col-md-10 p-4">
  <h2 class="mb-4">📢 Announcements</h2>

  <div class="row g-4">
    <!-- Example Announcement 1 -->
    <div class="col-md-6">
      <div class="card shadow-sm border-0 h-100">
        <div class="card-body">
          <h5 class="card-title">Barangay Assembly</h5>
          <p class="card-text">Join us for the general assembly on <b>Sept 30, 2025</b> at the Barangay Hall.</p>
          <small class="text-muted">Posted on Sept 10, 2025</small>
        </div>
      </div>
    </div>

    <!-- Example Announcement 2 -->
    <div class="col-md-6">
      <div class="card shadow-sm border-0 h-100">
        <div class="card-body">
          <h5 class="card-title">Free Medical Checkup</h5>
          <p class="card-text">Free health services will be available at the covered court on <b>Oct 5, 2025</b>.</p>
          <small class="text-muted">Posted on Sept 12, 2025</small>
        </div>
      </div>
    </div>

    <!-- Example Announcement 3 -->
    <div class="col-md-6">
      <div class="card shadow-sm border-0 h-100">
        <div class="card-body">
          <h5 class="card-title">Community Clean-Up Drive</h5>
          <p class="card-text">Residents are encouraged to join the clean-up on <b>Oct 15, 2025</b>.</p>
          <small class="text-muted">Posted on Sept 14, 2025</small>
        </div>
      </div>
    </div>
  </div>
</div>

<?php include("../includes/footer.php"); ?>
