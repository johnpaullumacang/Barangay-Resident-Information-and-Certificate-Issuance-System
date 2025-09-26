<?php
session_start();
include("../config/db.php");

// Ensure user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit();
}

// Get resident ID
$stmt = $conn->prepare("SELECT resident_id FROM resident WHERE user_id = ? LIMIT 1");
$stmt->bind_param("i", $_SESSION['user_id']);
$stmt->execute();
$resident = $stmt->get_result()->fetch_assoc();
$resident_id = $resident['resident_id'] ?? 0;

// Fetch all certificate requests by this resident
$stmt_requests = $conn->prepare("
    SELECT cr.certificate_id, cr.status AS cert_status, cr.request_date, cr.release_date,
           p.payment_id, p.amount, p.status AS payment_status, p.created_at,
           ct.cert_name
    FROM certificate_request cr
    LEFT JOIN payments p ON cr.certificate_id = p.certificate_id
    JOIN certificate_type ct ON cr.cert_type_id = ct.cert_type_id
    WHERE cr.resident_id = ?
    ORDER BY cr.request_date DESC
");
$stmt_requests->bind_param("i", $resident_id);
$stmt_requests->execute();
$result_requests = $stmt_requests->get_result();

// Fetch certificates that are paid AND released
$stmt_available = $conn->prepare("
    SELECT p.payment_id, p.created_at, ct.cert_name
    FROM payments p
    JOIN certificate_request cr ON p.certificate_id = cr.certificate_id
    JOIN certificate_type ct ON cr.cert_type_id = ct.cert_type_id
    WHERE p.resident_id = ? 
      AND p.status = 'paid'
      AND cr.status = 'Released'
    ORDER BY p.created_at DESC
");
$stmt_available->bind_param("i", $resident_id);
$stmt_available->execute();
$result_available = $stmt_available->get_result();
?>

<?php include("../includes/header.php"); ?>
<?php include("../includes/sidebar_resident.php"); ?>

<div class="content-wrapper" style="margin-left:250px; padding:20px;">
    <h2 class="mb-4">Certificate Requests History</h2>

    <?php if ($result_requests && $result_requests->num_rows > 0): ?>
    <table class="table table-bordered table-striped">
        <thead class="table-dark">
            <tr>
                <th>#</th>
                <th>Certificate Type</th>
                <th>Amount</th>
                <th>Status</th>
                <th>Request Date</th>
                <th>Release Date</th>
            </tr>
        </thead>
        <tbody>
            <?php $i = 1; while($row = $result_requests->fetch_assoc()): ?>
            <tr>
                <td><?= $i++; ?></td>
                <td><?= htmlspecialchars($row['cert_name']); ?></td>
                <td>
                    <?= $row['amount'] ? '₱'.number_format($row['amount'], 2) : '—'; ?>
                </td>
                <td>
                    <?php
                    switch($row['cert_status']){
                        case 'Validated':
                            echo '<span class="badge bg-success">Validated</span>';
                            break;
                        case 'Released':
                            echo '<span class="badge bg-primary">Released</span>';
                            break;
                        case 'Pending':
                        default:
                            echo '<span class="badge bg-warning text-dark">Not Yet Validated</span>';
                    }
                    ?>
                </td>
                <td><?= date("M d, Y h:i A", strtotime($row['request_date'])); ?></td>
                <td>
                    <?= $row['release_date'] ? date("M d, Y", strtotime($row['release_date'])) : '—'; ?>
                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
    <?php else: ?>
        <div class="alert alert-info">You have not requested any certificates yet.</div>
    <?php endif; ?>

    <hr class="my-5">

    <h2 class="mb-4">Available Certificates for Download</h2>

    <?php if ($result_available && $result_available->num_rows > 0): ?>
    <table class="table table-bordered table-striped">
        <thead class="table-dark">
            <tr>
                <th>#</th>
                <th>Certificate Type</th>
                <th>Date Released</th>
                <th>Download/View</th>
            </tr>
        </thead>
        <tbody>
            <?php $j = 1; while($row = $result_available->fetch_assoc()): ?>
            <tr>
                <td><?= $j++; ?></td>
                <td><?= htmlspecialchars($row['cert_name']); ?></td>
                <td><?= date("M d, Y h:i A", strtotime($row['created_at'])); ?></td>
                <td>
                    <a href="download_certificate.php?id=<?= $row['payment_id']; ?>" 
                       class="btn btn-sm btn-primary">Download</a>
                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
    <?php else: ?>
        <div class="alert alert-info">No certificates are available for download yet.</div>
    <?php endif; ?>
</div>

<?php include("../includes/footer.php"); ?>
