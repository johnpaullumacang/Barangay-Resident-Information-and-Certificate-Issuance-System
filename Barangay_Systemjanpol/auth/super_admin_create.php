<?php include("../config/db.php"); ?>
<?php include("../includes/header.php"); ?>
<?php include("../includes/sidebar_admin.php"); ?>

<style>
  .content { margin-left:240px; padding:20px; }
</style>

<div class="content">
  <h2>Create Admin</h2>

  <?php
  if (isset($_POST['create'])) {
      $username = $conn->real_escape_string($_POST['username']);
      $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
      $photo = "";

      if (!empty($_FILES['photo']['name'])) {
          $targetDir = "../uploads/admins/";
          if (!is_dir($targetDir)) mkdir($targetDir, 0777, true);
          $photo = time() . "_" . basename($_FILES['photo']['name']);
          move_uploaded_file($_FILES['photo']['tmp_name'], $targetDir . $photo);
      }

      $sql = "INSERT INTO admin_users (username, password, photo) VALUES ('$username', '$password', '$photo')";
      if ($conn->query($sql)) {
          echo "<div class='alert alert-success'>Admin Created</div>";
      } else {
          echo "<div class='alert alert-danger'>Error: ".$conn->error."</div>";
      }
  }
  ?>

  <form method="POST" enctype="multipart/form-data" class="row g-3">
    <div class="col-md-3"><input type="text" name="username" placeholder="Username" class="form-control" required></div>
    <div class="col-md-3"><input type="password" name="password" placeholder="Password" class="form-control" required></div>
    <div class="col-md-3"><input type="file" name="photo" class="form-control" accept="image/*"></div>
    <div class="col-md-2 d-grid">
      <button type="submit" name="create" class="btn btn-success">Create</button>
    </div>
  </form>
</div>

<?php include("../includes/footer.php"); ?>
