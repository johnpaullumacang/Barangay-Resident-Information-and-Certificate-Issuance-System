<?php include("../config/db.php"); ?>
<?php include("../includes/header.php"); ?>
<?php include("../includes/sidebar_admin.php"); ?>

<style>
  .content { margin-left:240px; padding:20px; }
  .table img { border-radius:50%; }
</style>

<div class="content">
  <h2>Residents Management</h2>

  <!-- ✅ Search and Filter -->
  <form method="GET" class="row g-3 mb-3">
    <div class="col-md-4">
      <input type="text" name="search" value="<?php echo $_GET['search'] ?? ''; ?>" 
             class="form-control" placeholder="Search name, purok, contact...">
    </div>
    <div class="col-md-3">
      <select name="gender" class="form-control">
        <option value="">All Genders</option>
        <option value="Male" <?php if(($_GET['gender'] ?? '')=="Male") echo "selected"; ?>>Male</option>
        <option value="Female" <?php if(($_GET['gender'] ?? '')=="Female") echo "selected"; ?>>Female</option>
      </select>
    </div>
    <div class="col-md-2 d-grid">
      <button type="submit" class="btn btn-primary">Filter</button>
    </div>
    <div class="col-md-2 d-grid">
      <a href="residents.php" class="btn btn-secondary">Reset</a>
    </div>
  </form>

  <!-- ✅ Table -->
  <table class="table table-bordered table-striped">
    <thead class="table-dark">
      <tr>
        <th>Photo</th>
        <th>Resident ID</th>
        <th>Name</th>
        <th>Gender</th>
        <th>Purok</th>
        <th>Barangay</th>
        <th>Municipality</th>
        <th>Province</th>
        <th>Contact</th>
        <th>Birth Date</th>
        <th>Actions</th>
      </tr>
    </thead>
    <tbody>
      <?php
        // ✅ Build query with search + filter
        $where = "WHERE 1=1 ";
        if (!empty($_GET['search'])) {
            $search = $conn->real_escape_string($_GET['search']);
            $where .= "AND (first_name LIKE '%$search%' 
                        OR last_name LIKE '%$search%'
                        OR purok LIKE '%$search%'
                        OR contact_number LIKE '%$search%') ";
        }
        if (!empty($_GET['gender'])) {
            $gender = $conn->real_escape_string($_GET['gender']);
            $where .= "AND gender='$gender' ";
        }

        $result = $conn->query("SELECT * FROM resident $where ORDER BY last_name ASC");
        if ($result->num_rows > 0):
          while ($row = $result->fetch_assoc()):
      ?>
      <tr>
        <td>
          <?php if (!empty($row['photo'])): ?>
            <img src="../uploads/residents/<?php echo $row['photo']; ?>" width="50" height="50">
          <?php else: ?>
            <img src="../uploads/residents/default.png" width="50" height="50">
          <?php endif; ?>
        </td>
        <td><?php echo $row['resident_id']; ?></td>
        <td><strong><?php echo $row['first_name']." ".$row['last_name']; ?></strong></td>
        <td><?php echo $row['gender']; ?></td>
        <td><?php echo $row['purok']; ?></td>
        <td><?php echo $row['barangay']; ?></td>
        <td><?php echo $row['municipality']; ?></td>
        <td><?php echo $row['province']; ?></td>
        <td><?php echo $row['contact_number']; ?></td>
        <td><?php echo $row['birth_date']; ?></td>
        <td>
          <a href="edit_resident.php?id=<?php echo $row['resident_id']; ?>" class="btn btn-sm btn-warning">Edit</a>
          <a href="delete_resident.php?id=<?php echo $row['resident_id']; ?>" 
             onclick="return confirm('Are you sure?')" 
             class="btn btn-sm btn-danger">Delete</a>
        </td>
      </tr>
      <?php endwhile; else: ?>
      <tr><td colspan="11" class="text-center">No residents found</td></tr>
      <?php endif; ?>
    </tbody>
  </table>
</div>

<?php include("../includes/footer.php"); ?>
