<?php include("../config/db.php"); ?>
<?php include("../includes/header.php"); ?>
<?php include("../includes/sidebar_admin.php"); ?>

<div class="container-fluid">
  <div class="row">
    <!-- Sidebar is already included via sidebar_admin.php -->

    <!-- Main Content -->
    <div class="col p-4" style="margin-left:250px; min-height:100vh;">
      <h2 class="mb-4">Certificate Requests</h2>
      <?php
      // ✅ Fetch certificates with resident name & type
      $sql = "SELECT cr.certificate_id, 
                     CONCAT(
                         r.first_name, ' ',
                         COALESCE(r.mi, ''), 
                         IF(r.mi IS NOT NULL AND r.mi != '', '. ', ''), 
                         r.last_name,
                         IF(r.suffix IS NOT NULL AND r.suffix != '', CONCAT(' ', r.suffix), '')
                     ) AS resident_name,
                     ct.cert_name AS cert_type, 
                     cr.status, cr.request_date, cr.release_date
              FROM certificate_request cr
              LEFT JOIN certificate_type ct 
                     ON cr.cert_type_id = ct.cert_type_id
              LEFT JOIN resident r
                     ON cr.resident_id = r.resident_id
              ORDER BY cr.request_date DESC";
      $result = $conn->query($sql);

      echo "<div class='table-responsive'>
              <table class='table table-bordered table-striped align-middle'>
                <thead class='table-dark'>
                  <tr>
                    <th>ID</th>
                    <th>Resident</th>
                    <th>Certificate Type</th>
                    <th>Request Date</th>
                    <th>Status</th>
                    <th>Release Date</th>
                    <th>Action</th>
                  </tr>
                </thead>
                <tbody>";

      if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
          echo "<tr>
            <td>{$row['certificate_id']}</td>
            <td>" . htmlspecialchars($row['resident_name']) . "</td>
            <td>{$row['cert_type']}</td>
            <td>" . date("M d, Y", strtotime($row['request_date'])) . "</td>
            <td>{$row['status']}</td>
            <td>".($row['release_date'] ? date("M d, Y", strtotime($row['release_date'])) : "-")."</td>
            <td>
              <a href='certificates.php?release={$row['certificate_id']}' 
                 class='btn btn-primary btn-sm'>Release</a>
            </td>
          </tr>";
        }
      } else {
        echo "<tr><td colspan='7' class='text-center'>No certificate requests found.</td></tr>";
      }

      echo "</tbody></table></div>";

      // ✅ Handle Release
      if (isset($_GET['release'])) {
        $certificate_id = $conn->real_escape_string($_GET['release']);
        $today = date("Y-m-d");
        $conn->query("UPDATE certificate_request 
                      SET status='Released', release_date='$today' 
                      WHERE certificate_id='$certificate_id'");
        header("Location: certificates.php");
        exit();
      }
      ?>
    </div>
  </div>
</div>

<?php include("../includes/footer.php"); ?>
