<?php include("../config/db.php"); ?>
<?php include("../includes/header.php"); ?>
<?php include("../includes/sidebar_admin.php"); ?>

<div class="container-fluid">
  <div class="row">
    <!-- Sidebar is included via sidebar_admin.php -->

    <!-- Main Content -->
    <div class="col-md-9 col-lg-10 p-4" style="margin-left:250px; max-width: calc(100% - 250px);">
      <h2>Payments</h2>

      <?php
      // ✅ Fetch payments with properly formatted resident name
      $sql = "
        SELECT 
            p.payment_id,
            p.certificate_id,  -- ✅ Keep certificate_id for later updates
            CONCAT(
                r.first_name, ' ',
                COALESCE(r.mi, ''), 
                IF(r.mi IS NOT NULL AND r.mi != '', '. ', ''), 
                r.last_name,
                IF(r.suffix IS NOT NULL AND r.suffix != '', CONCAT(' ', r.suffix), '')
            ) AS resident_name,
            ct.cert_name,
            cr.purpose,
            p.amount,
            p.payment_type,
            p.status AS payment_status,
            p.created_at
        FROM payments p
        JOIN resident r ON p.resident_id = r.resident_id
        JOIN certificate_request cr ON p.certificate_id = cr.certificate_id
        JOIN certificate_type ct ON cr.cert_type_id = ct.cert_type_id
        ORDER BY p.created_at DESC
      ";

      $result = $conn->query($sql);

      if ($result && $result->num_rows > 0) {
          echo "<div class='table-responsive'>";
          echo "<table class='table table-bordered table-striped'>
                  <thead class='table-dark'>
                    <tr>
                      <th>ID</th>
                      <th>Resident</th>
                      <th>Certificate</th>
                      <th>Purpose</th>
                      <th>Amount</th>
                      <th>Payment Type</th>
                      <th>Status</th>
                      <th>Date</th>
                      <th>Action</th>
                    </tr>
                  </thead>
                  <tbody>";

          while ($row = $result->fetch_assoc()) {
              echo "<tr>
                      <td>{$row['payment_id']}</td>
                      <td>" . htmlspecialchars($row['resident_name']) . "</td>
                      <td>" . htmlspecialchars($row['cert_name']) . "</td>
                      <td>" . htmlspecialchars($row['purpose']) . "</td>
                      <td>₱" . number_format($row['amount'], 2) . "</td>
                      <td>{$row['payment_type']}</td>
                      <td>
                        " . ($row['payment_status'] === 'Validated' 
                            ? "<span class='badge bg-success'>Validated</span>" 
                            : "<span class='badge bg-warning text-dark'>Not Yet Validated</span>") . "
                      </td>
                      <td>" . date("M d, Y h:i A", strtotime($row['created_at'])) . "</td>
                      <td>
                        " . ($row['payment_status'] === 'Validated'
                            ? "<a href='payments.php?invalidate={$row['payment_id']}&cert_id={$row['certificate_id']}' class='btn btn-sm btn-danger'>Invalidate</a>"
                            : "<a href='payments.php?validate={$row['payment_id']}&cert_id={$row['certificate_id']}' class='btn btn-sm btn-success'>Validate</a>") . "
                      </td>
                    </tr>";
          }

          echo "</tbody></table>";
          echo "</div>";
      } else {
          echo "<div class='alert alert-info'>No payments found.</div>";
      }

      // ✅ Handle Validation
      if (isset($_GET['validate']) && isset($_GET['cert_id'])) {
          $payment_id = $conn->real_escape_string($_GET['validate']);
          $cert_id = $conn->real_escape_string($_GET['cert_id']);

          // Update payment
          $conn->query("UPDATE payments SET status='Validated' WHERE payment_id='$payment_id'");

          // ✅ Also update related certificate request
          $conn->query("UPDATE certificate_request SET status='Validated' WHERE certificate_id='$cert_id'");

          header("Location: payments.php");
          exit();
      }

      // ✅ Handle Invalidation
      if (isset($_GET['invalidate']) && isset($_GET['cert_id'])) {
          $payment_id = $conn->real_escape_string($_GET['invalidate']);
          $cert_id = $conn->real_escape_string($_GET['cert_id']);

          // Update payment
          $conn->query("UPDATE payments SET status='Not Yet Validated' WHERE payment_id='$payment_id'");

          // ✅ Also update related certificate request back
          $conn->query("UPDATE certificate_request SET status='Pending' WHERE certificate_id='$cert_id'");

          header("Location: payments.php");
          exit();
      }
      ?>
    </div>
  </div>
</div>

<?php include("../includes/footer.php"); ?>
