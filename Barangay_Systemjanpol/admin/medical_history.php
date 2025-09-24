<?php include("../config/db.php"); ?>
<?php include("../includes/header.php"); ?>
<?php include("../includes/sidebar_resident.php"); ?>

<div class="col-md-10 p-4">
  <h2>My Medical History</h2>
  <?php
  $uid = $_SESSION['user_id'];
  $res = $conn->query("SELECT resident_id FROM resident WHERE user_id='$uid'");
  $resident = $res->fetch_assoc()['resident_id'];

  $result = $conn->query("SELECT medical_condition, diagnosis_date, notes FROM medical_history WHERE resident_id='$resident'");
  echo "<table class='table table-bordered'><tr><th>Condition</th><th>Date</th><th>Notes</th></tr>";
  while ($row = $result->fetch_assoc()) {
    echo "<tr><td>{$row['medical_condition']}</td><td>{$row['diagnosis_date']}</td><td>{$row['notes']}</td></tr>";
  }
  echo "</table>";
  ?>
</div>

<?php include("../includes/footer.php"); ?>
