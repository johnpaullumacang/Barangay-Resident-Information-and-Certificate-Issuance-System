<?php include("../config/db.php"); ?>
<?php include("../includes/header.php"); ?>
<?php include("../includes/sidebar_resident.php"); ?>

<div class="col-md-10 p-4">
  <h2>Request Certificate</h2>
  <form method="POST" class="row g-2 mb-3">
    <div class="col-md-6">
      <input type="text" name="cert_type" placeholder="Certificate Type" class="form-control" required>
    </div>
    <div class="col-md-2">
      <button type="submit" name="request" class="btn btn-primary">Request</button>
    </div>
  </form>

  <?php
  if (isset($_POST['request'])) {
    $uid = $_SESSION['user_id'];
    $res = $conn->query("SELECT resident_id FROM resident WHERE user_id='$uid'");
    $resident = $res->fetch_assoc()['resident_id'];

    $conn->query("INSERT INTO certificates (resident_id, cert_type) VALUES ('$resident', '{$_POST['cert_type']}')");
    echo "<div class='alert alert-success'>Certificate Requested</div>";
  }
  ?>
</div>

<?php include("../includes/footer.php"); ?>
