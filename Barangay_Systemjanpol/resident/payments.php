<?php
session_start();
include("../config/db.php");
include("../includes/header.php");
include("../includes/sidebar_resident.php");

if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit();
}

// Get the resident ID of the logged-in user
$user_id = $_SESSION['user_id'];
$res = $conn->query("SELECT resident_id FROM resident WHERE user_id='$user_id' LIMIT 1");
$resident = $res->fetch_assoc();
$resident_id = $resident['resident_id'];
?>

<div class="content-wrapper" style="margin-left:250px; padding:20px; max-width: calc(100% - 250px);">
    <h2 class="page-title">My Payments</h2>

    <?php
    // Fetch resident payments
    $sql = "
        SELECT 
            p.payment_id,
            p.certificate_id,
            ct.cert_name,
            cr.purpose,
            p.amount,
            p.payment_type,
            p.status AS payment_status,
            p.created_at
        FROM payments p
        JOIN certificate_request cr ON p.certificate_id = cr.certificate_id
        JOIN certificate_type ct ON cr.cert_type_id = ct.cert_type_id
        WHERE p.resident_id = ?
        ORDER BY p.created_at DESC
    ";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $resident_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows > 0):
    ?>
        <div class="table-responsive mt-4">
            <table class="table table-bordered table-striped">
                <thead class="table-dark">
                    <tr>
                        <th>Payment ID</th>
                        <th>Certificate</th>
                        <th>Purpose</th>
                        <th>Amount</th>
                        <th>Payment Type</th>
                        <th>Status</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
<?php while ($row = $result->fetch_assoc()): ?>
    <tr>
        <td><?= htmlspecialchars($row['payment_id']) ?></td>
        <td><?= htmlspecialchars($row['cert_name']) ?></td>
        <td><?= htmlspecialchars($row['purpose']) ?></td>
        <td>₱<?= number_format($row['amount'], 2) ?></td>
        <td class="text-center">Gcash</td> <!-- Always show GCash -->
        <td>
            <?php if($row['payment_status']=='Validated'): ?>
                <span class="badge bg-success">Validated</span>
            <?php else: ?>
                <span class="badge bg-warning text-dark">Not Yet Validated</span>
            <?php endif; ?>
        </td>
        <td><?= date("M d, Y h:i A", strtotime($row['created_at'])) ?></td>
    </tr>
<?php endwhile; ?>
</tbody>

            </table>
        </div>
    <?php else: ?>
        <div class="alert alert-info mt-4">You have no payment records yet.</div>
    <?php endif; ?>

</div>

<?php
$stmt->close();
$conn->close();
include("../includes/footer.php");
?>
