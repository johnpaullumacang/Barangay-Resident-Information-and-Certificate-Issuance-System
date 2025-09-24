<?php include("../config/db.php"); ?>
<?php include("../includes/header.php"); ?>
<?php include("../includes/sidebar_admin.php"); ?>

<style>
  .content { margin-left:240px; padding:20px; }
</style>

<div class="content">
  <h2>Edit Resident</h2>
  <?php
  $id = $_GET['id'] ?? '';
  $result = $conn->query("SELECT * FROM resident WHERE resident_id='$id'");
  $resident = $result->fetch_assoc();

  if (isset($_POST['update'])) {
      $first_name     = $conn->real_escape_string($_POST['first_name']);
      $last_name      = $conn->real_escape_string($_POST['last_name']);
      $gender         = $conn->real_escape_string($_POST['gender']);
      $purok          = $conn->real_escape_string($_POST['purok']);
      $barangay       = $conn->real_escape_string($_POST['barangay']);
      $municipality   = $conn->real_escape_string($_POST['municipality']);
      $province       = $conn->real_escape_string($_POST['province']);
      $contact_number = $conn->real_escape_string($_POST['contact_number']);
      $birth_date     = $conn->real_escape_string($_POST['birth_date']);
      
      // photo upload
      $photo = $resident['photo'];
      if (!empty($_FILES['photo']['name'])) {
          $targetDir = "../uploads/residents/";
          if (!is_dir($targetDir)) mkdir($targetDir, 0777, true);
          $photo = $id . "_" . basename($_FILES['photo']['name']);
          move_uploaded_file($_FILES['photo']['tmp_name'], $targetDir . $photo);
      }

      $sql = "UPDATE resident SET 
              first_name='$first_name', last_name='$last_name', gender='$gender',
              purok='$purok', barangay='$barangay', municipality='$municipality',
              province='$province', contact_number='$contact_number', 
              birth_date='$birth_date', photo='$photo'
              WHERE resident_id='$id'";
      if ($conn->query($sql)) {
          echo "<div class='alert alert-success'>Resident Updated</div>";
          $resident = $conn->query("SELECT * FROM resident WHERE resident_id='$id'")->fetch_assoc();
      } else {
          echo "<div class='alert alert-danger'>Error: ".$conn->error."</div>";
      }
  }
  ?>

  <form method="POST" enctype="multipart/form-data" class="row g-3">
    <div class="col-md-3"><input type="text" name="first_name" value="<?php echo $resident['first_name']; ?>" class="form-control" required></div>
    <div class="col-md-3"><input type="text" name="last_name" value="<?php echo $resident['last_name']; ?>" class="form-control" required></div>
    <div class="col-md-2">
      <select name="gender" class="form-control" required>
        <option value="Male" <?php if($resident['gender']=="Male") echo "selected"; ?>>Male</option>
        <option value="Female" <?php if($resident['gender']=="Female") echo "selected"; ?>>Female</option>
      </select>
    </div>
    <div class="col-md-3">
      <input type="file" name="photo" class="form-control">
      <?php if (!empty($resident['photo'])): ?>
        <img src="../uploads/residents/<?php echo $resident['photo']; ?>" width="80" class="mt-2">
      <?php endif; ?>
    </div>
    <div class="col-md-2"><input type="text" name="purok" value="<?php echo $resident['purok']; ?>" class="form-control"></div>
    <div class="col-md-2"><input type="text" name="barangay" value="<?php echo $resident['barangay']; ?>" class="form-control"></div>
    <div class="col-md-2"><input type="text" name="municipality" value="<?php echo $resident['municipality']; ?>" class="form-control"></div>
    <div class="col-md-2"><input type="text" name="province" value="<?php echo $resident['province']; ?>" class="form-control"></div>
    <div class="col-md-2"><input type="text" name="contact_number" value="<?php echo $resident['contact_number']; ?>" class="form-control"></div>
    <div class="col-md-2"><input type="date" name="birth_date" value="<?php echo $resident['birth_date']; ?>" class="form-control"></div>
    <div class="col-md-2 d-grid">
      <button type="submit" name="update" class="btn btn-success">Update</button>
    </div>
  </form>
</div>

<?php include("../includes/footer.php"); ?>
