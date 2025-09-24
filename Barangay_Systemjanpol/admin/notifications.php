<?php include("../config/db.php"); ?>
<?php include("../includes/header.php"); ?>
<?php include("../includes/sidebar_admin.php"); ?>

<div class="col-md-10 p-4">
  <h2>Send Notifications</h2>

  <form method="POST" class="row g-2 mb-3">
    <div class="col-md-4">
      <input type="text" name="user_id" placeholder="User ID" class="form-control" required>
    </div>
    <div class="col-md-6">
      <input type="text" name="message" placeholder="Message" class="form-control" required>
    </div>
    <div class="col-md-2">
      <button type="submit" name="send" class="btn btn-primary">Send</button>
    </div>
  </form>

  <?php
  if (isset($_POST['send'])) {
    $conn->query("INSERT INTO notifications (user_id, message) VALUES ('{$_POST['user_id']}', '{$_POST['message']}')");
    echo "<div class='alert alert-success'>Notification Sent</div>";
  }
  ?>
</div>

<?php include("../includes/footer.php"); ?>
