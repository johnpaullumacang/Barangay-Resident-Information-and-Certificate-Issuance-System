<?php
include("../config/db.php");

// Make sure the function exists
if (!function_exists("check_login")) {
    function check_login() {
        if (!isset($_SESSION['user_id'])) {
            header("Location: ../auth/login.php");
            exit();
        }
    }
}

check_login();

// Get logged-in resident’s ID from users table
$user_id = $_SESSION['user_id'];
$resident = $conn->query("SELECT resident_id FROM resident WHERE user_id='$user_id' LIMIT 1")->fetch_assoc();
$resident_id = $resident['resident_id'];

// Fetch payment history
$sql = "SELECT * FROM payments WHERE resident_id='$resident_id' ORDER BY created_at DESC";
$result = $conn->query($sql);
?>

<?php include("../includes/header.php"); ?>
<?php include("../includes/sidebar_resident.php"); ?>

<div class="col-md-10 p-4">
  <h2 class="mb-4">My Payments</h2>

  <?php if ($result->num_rows > 0): ?>
    <table class="table table-bordered table-striped">
      <thead class="table-dark">
        <tr>
          <th>#</th>
          <th>Payment Type</th>
          <th>Amount</th>
          <th>Status</th>
          <th>Date</th>
        </tr>
      </thead>
      <tbody>
        <?php 
        $i = 1;
        while ($row = $result->fetch_assoc()): 
        ?>
          <tr>
            <td><?= $i++; ?></td>
            <td><?= htmlspecialchars($row['payment_type']); ?></td>
            <td>₱<?= number_format($row['amount'], 2); ?></td>
            <td>
              <?php if ($row['status'] == 'paid'): ?>
                <span class="badge bg-success">Paid</span>
              <?php else: ?>
                <span class="badge bg-warning text-dark"><?= htmlspecialchars($row['status']); ?></span>
              <?php endif; ?>
            </td>
            <td><?= date("M d, Y h:i A", strtotime($row['created_at'])); ?></td>
          </tr>
        <?php endwhile; ?>
      </tbody>
    </table>
  <?php else: ?>
    <div class="alert alert-info">You don’t have any recorded payments yet.</div>
  <?php endif; ?>
</div>

<?php include("../includes/footer.php"); ?>
