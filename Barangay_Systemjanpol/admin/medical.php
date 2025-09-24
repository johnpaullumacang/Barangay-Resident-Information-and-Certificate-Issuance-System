<?php include("../config/db.php"); ?>
<?php include("../includes/header.php"); ?>

<div class="container-fluid">
  <div class="row">
    <!-- Sidebar -->
    <div class="col-md-2 p-0">
      <?php include("../includes/sidebar_admin.php"); ?>
    </div>

    <!-- Main Content -->
    <div class="col-md-10 p-4">
      <h2>Manage Medical Records</h2>

      <form method="POST" class="row g-2 mb-3">
        <div class="col-md-3">
          <select name="resident_id" class="form-control" required>
            <option value="">Select Resident</option>
            <?php
            $residents = $conn->query("SELECT resident_id, CONCAT(first_name,' ',last_name) as name FROM resident");
            while ($row = $residents->fetch_assoc()) {
              echo "<option value='{$row['resident_id']}'>{$row['name']}</option>";
            }
            ?>
          </select>
        </div>
        <div class="col-md-3">
          <input type="text" name="medical_condition" placeholder="Condition" class="form-control" required>
        </div>
        <div class="col-md-2">
          <input type="date" name="diagnosis_date" class="form-control">
        </div>
        <div class="col-md-3">
          <input type="text" name="notes" placeholder="Notes" class="form-control">
        </div>
        <div class="col-md-1">
          <button type="submit" name="add" class="btn btn-primary">Add</button>
        </div>
      </form>

      <?php
      if (isset($_POST['add'])) {
        $resident_id = $_POST['resident_id'];
        $condition = $_POST['medical_condition'];
        $date = $_POST['diagnosis_date'];
        $notes = $_POST['notes'];

        $sql = "INSERT INTO medical_history (resident_id, medical_condition, diagnosis_date, notes)
                VALUES ('$resident_id', '$condition', '$date', '$notes')";
        if ($conn->query($sql)) {
          echo "<div class='alert alert-success'>Record Added</div>";
        } else {
          echo "<div class='alert alert-danger'>Error: ".$conn->error."</div>";
        }
      }

      $result = $conn->query("SELECT mh.med_id, r.first_name, r.last_name, mh.medical_condition, mh.diagnosis_date, mh.notes 
                              FROM medical_history mh
                              JOIN resident r ON mh.resident_id = r.resident_id");
      echo "<table class='table table-bordered'><tr><th>ID</th><th>Resident</th><th>Condition</th><th>Date</th><th>Notes</th></tr>";
      while ($row = $result->fetch_assoc()) {
        echo "<tr><td>{$row['med_id']}</td><td>{$row['first_name']} {$row['last_name']}</td><td>{$row['medical_condition']}</td><td>{$row['diagnosis_date']}</td><td>{$row['notes']}</td></tr>";
      }
      echo "</table>";
      ?>
    </div>
  </div>
</div>

<?php include("../includes/footer.php"); ?>
