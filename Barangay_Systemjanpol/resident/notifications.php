<?php include("../config/db.php"); ?>
<?php include("../includes/header.php"); ?>
<?php include("../includes/sidebar_resident.php"); ?>

<div class="col-md-10 p-4">
  <h2>My Notifications</h2>

  <?php
  $uid = $_SESSION['user_id'];

  $result = $conn->query("SELECT * FROM notifications WHERE user_id='$uid' ORDER BY created_at DESC");

  if ($result->num_rows > 0) {
    echo "<ul class='list-group'>";
    while ($row = $result->fetch_assoc()) {
      echo "<li class='list-group-item d-flex justify-content-between align-items-center'>
              {$row['message']}
              <span class='badge bg-secondary'>{$row['created_at']}</span>
            </li>";
    }
    echo "</ul>";
  } else {
    echo "<div class='alert alert-info'>No notifications yet.</div>";
  }
  ?>
</div>

<?php include("../includes/footer.php"); ?>
