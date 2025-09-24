<?php
session_start();
include("../config/db.php");
include("../includes/header.php");
include("../includes/sidebar_resident.php");
?>

<div class="col-md-10 p-4">
  <h2 class="mb-4">Resident Dashboard</h2>

  <!-- Welcome Card -->
  <div class="card shadow-sm mb-4 border-0">
    <div class="card-body">
      <h4 class="card-title">👋 Welcome, <?php echo htmlspecialchars($_SESSION['user_id']); ?>!</h4>
      <p class="card-text">This is your personal Barangay Portal where you can view and manage your information.</p>
    </div>
  </div>

  <!-- Quick Access Section -->
  <div class="row g-4 mb-4">
    <div class="col-md-4">
      <div class="card text-white bg-primary shadow-sm border-0 h-100 d-flex flex-column">
        <div class="card-body d-flex flex-column">
          <h5 class="card-title">📄 My Profile</h5>
          <p class="card-text flex-grow-1">View and update your personal information.</p>
          <a href="profile.php" class="btn btn-light mt-auto">View Profile</a>
        </div>
      </div>
    </div>

    <div class="col-md-4">
      <div class="card text-white bg-success shadow-sm border-0 h-100 d-flex flex-column">
        <div class="card-body d-flex flex-column">
          <h5 class="card-title">📢 Announcements</h5>
          <p class="card-text flex-grow-1">Stay updated with barangay news and events.</p>
          <a href="announcements.php" class="btn btn-light mt-auto">View Announcements</a>
        </div>
      </div>
    </div>

    <div class="col-md-4">
      <div class="card text-white bg-warning shadow-sm border-0 h-100 d-flex flex-column">
        <div class="card-body d-flex flex-column">
          <h5 class="card-title">📝 Requests</h5>
          <p class="card-text flex-grow-1">Submit and track your document requests.</p>
          <a href="request.php" class="btn btn-light mt-auto">Make Request</a>
        </div>
      </div>
    </div>
  </div>

  <!-- Barangay Info Section -->
  <div class="card shadow-sm border-0">
    <div class="card-body">
      <h5 class="card-title">🏘️ Barangay Information</h5>
      <p class="card-text">
        Access important information about your barangay, including services offered,
        community projects, and upcoming activities.
      </p>
      <a href="barangay_info.php" class="btn btn-primary btn-sm">Learn More</a>
    </div>
  </div>
</div>

<?php include("../includes/footer.php"); ?>
