<?php include("../config/db.php"); ?>
<?php include("../includes/header.php"); ?>
<?php include("../includes/sidebar_admin.php"); ?>

<div class="col-md-10 p-4">
  <h2>Certificates Requests</h2>
  <?php
  $result = $conn->query("SELECT * FROM certificate_request");
  echo "<table class='table table-bordered'><tr><th>ID</th><th>Resident</th><th>Type</th><th>Status</th><th>Action</th></tr>";
  while ($row = $result->fetch_assoc()) {
    echo "<tr>
      <td>{$row['cert_id']}</td>
      <td>{$row['resident_id']}</td>
      <td>{$row['cert_type']}</td>
      <td>{$row['status']}</td>
      <td>
        <a href='certificates.php?approve={$row['cert_id']}' class='btn btn-success btn-sm'>Approve</a>
        <a href='certificates.php?release={$row['cert_id']}' class='btn btn-primary btn-sm'>Release</a>
      </td>
    </tr>";
  }
  echo "</table>";

  if (isset($_GET['approve'])) {
    $conn->query("UPDATE certificate_request SET status='Approved' WHERE cert_id={$_GET['approve']}");
    header("Location: certificates.php");
  }
  if (isset($_GET['release'])) {
    $conn->query("UPDATE certificate_request SET status='Released' WHERE cert_id={$_GET['release']}");
    header("Location: certificates.php");
  }
  ?>
</div>

<?php include("../includes/footer.php"); ?>
