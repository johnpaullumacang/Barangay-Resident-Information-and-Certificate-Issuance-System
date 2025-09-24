<?php include("../config/db.php"); ?>
<?php include("../includes/header.php"); ?>
<?php include("../includes/sidebar_admin.php"); ?>

<div class="col-md-10 p-4">
  <h2>Payments</h2>
  <?php
  $result = $conn->query("SELECT * FROM payments");
  echo "<table class='table table-bordered'><tr><th>ID</th><th>Resident</th><th>Amount</th><th>Purpose</th><th>Date</th></tr>";
  while ($row = $result->fetch_assoc()) {
    echo "<tr><td>{$row['payment_id']}</td><td>{$row['resident_id']}</td><td>{$row['amount']}</td><td>{$row['purpose']}</td><td>{$row['payment_date']}</td></tr>";
  }
  echo "</table>";
  ?>
</div>

<?php include("../includes/footer.php"); ?>
