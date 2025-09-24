<?php
session_start();
include("../config/db.php");
include("../includes/sidebar_admin.php");


// Check if logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit();
}

// Fetch admin info
$adminId   = $_SESSION['user_id'];
$adminName = $conn->query("SELECT full_name FROM users WHERE user_id='$adminId'")->fetch_assoc()['full_name'];

// Fetch stats
$totalResidentsPending  = $conn->query("SELECT COUNT(*) as count FROM resident WHERE status='Pending'")->fetch_assoc()['count'];
$totalResidentsVerified = $conn->query("SELECT COUNT(*) as count FROM resident WHERE status='Verified' OR status='Approved'")->fetch_assoc()['count'];
$totalResidentsRejected = $conn->query("SELECT COUNT(*) as count FROM resident WHERE status='Rejected'")->fetch_assoc()['count'];
$totalAdmins    = $conn->query("SELECT COUNT(*) as count FROM users WHERE role='admin'")->fetch_assoc()['count'];
$totalEvents    = $conn->query("SELECT COUNT(*) as count FROM events")->fetch_assoc()['count'];
$totalPayments  = $conn->query("SELECT COUNT(*) as count FROM payments")->fetch_assoc()['count'];

// Fetch latest events
$latestEvents = $conn->query("SELECT title, event_date FROM events ORDER BY event_date DESC LIMIT 3");
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Admin Dashboard</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
<style>
body { background: #f4f6f9; }
.main-content { margin-left: 260px; padding: 25px; }
.stats-card { border-radius: 15px; padding: 20px; text-align: center; color: #fff; box-shadow: 0 6px 15px rgba(0,0,0,0.15); transition: transform 0.3s, box-shadow 0.3s; }
.stats-card:hover { transform: translateY(-5px); box-shadow: 0 12px 25px rgba(0,0,0,0.2); }
.stats-card h3 { font-size: 32px; margin-bottom: 8px; font-weight: bold; }
.stats-card p { font-size: 15px; margin: 0; }
.recent-activity { background: #fff; padding: 20px; border-radius: 12px; box-shadow: 0 4px 12px rgba(0,0,0,0.1); }
</style>
</head>
<body>

<?php include("../includes/sidebar_admin.php"); ?>

<div class="main-content">
    <h2 class="fw-bold">👋 Welcome, <?php echo htmlspecialchars($adminName); ?></h2>
    <p class="text-muted">This is your Barangay Management System Admin Dashboard.</p>

    <!-- Resident Stats Cards -->
    <div class="row g-4 mt-2">
        <div class="col-md-3">
            <div class="stats-card" style="background: linear-gradient(135deg, #ffc107, #ffd966);">
                <h3><?php echo $totalResidentsPending; ?></h3>
                <p>Pending Residents</p>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stats-card" style="background: linear-gradient(135deg, #28a745, #85e085);">
                <h3><?php echo $totalResidentsVerified; ?></h3>
                <p>Verified/Approved Residents</p>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stats-card" style="background: linear-gradient(135deg, #dc3545, #ff8080);">
                <h3><?php echo $totalResidentsRejected; ?></h3>
                <p>Rejected Residents</p>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stats-card" style="background: linear-gradient(135deg, #007bff, #00c6ff);">
                <h3><?php echo $totalAdmins; ?></h3>
                <p>Total Admins</p>
            </div>
        </div>
    </div>

    <!-- Other Stats Cards -->
    <div class="row g-4 mt-3">
        <div class="col-md-3">
            <div class="stats-card" style="background: linear-gradient(135deg, #6f42c1, #d63384);">
                <h3><?php echo $totalEvents; ?></h3>
                <p>Total Events</p>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stats-card" style="background: linear-gradient(135deg, #fd7e14, #ffc107);">
                <h3><?php echo $totalPayments; ?></h3>
                <p>Total Payments</p>
            </div>
        </div>
    </div>

    <!-- Charts + Activity + Events -->
    <div class="row mt-5 g-4">
        <!-- Chart -->
        <div class="col-lg-6">
            <div class="recent-activity">
                <h5 class="mb-3"><i class="bi bi-graph-up"></i> Residents Growth</h5>
                <canvas id="residentsChart"></canvas>
            </div>
        </div>

        <!-- Recent Activity -->
        <div class="col-lg-3">
            <div class="recent-activity">
                <h5 class="mb-3"><i class="bi bi-clock-history"></i> Recent Activity</h5>
                <ul class="list-group list-group-flush">
                    <li class="list-group-item">New resident submitted form</li>
                    <li class="list-group-item">Resident verified</li>
                    <li class="list-group-item">Payment recorded</li>
                    <li class="list-group-item">Admin account created</li>
                </ul>
            </div>
        </div>

        <!-- Upcoming Events -->
        <div class="col-lg-3">
            <div class="recent-activity">
                <h5 class="mb-3"><i class="bi bi-calendar-event"></i> Upcoming Events</h5>
                <ul class="list-group list-group-flush">
                    <?php if ($latestEvents->num_rows > 0): ?>
                        <?php while($event = $latestEvents->fetch_assoc()): ?>
                            <li class="list-group-item">
                                <strong><?php echo htmlspecialchars($event['title']); ?></strong>
                                <span class="text-muted"> (<?php echo date("M d, Y", strtotime($event['event_date'])); ?>)</span>
                            </li>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <li class="list-group-item text-muted">No events available</li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </div>
</div>

<!-- Chart.js Script -->
<script>
const ctx = document.getElementById('residentsChart').getContext('2d');
new Chart(ctx, {
    type: 'line',
    data: {
        labels: ['Jan','Feb','Mar','Apr','May','Jun'],
        datasets: [{
            label: 'Residents',
            data: [<?php echo $totalResidentsPending; ?>, <?php echo $totalResidentsVerified; ?>, <?php echo $totalResidentsRejected; ?>, 0, 0, 0],
            borderColor: '#007bff',
            backgroundColor: 'rgba(0,123,255,0.2)',
            fill: true,
            tension: 0.3,
            pointBackgroundColor: '#007bff'
        }]
    },
    options: { responsive: true, plugins: { legend: { display: false } } }
});
</script>

<?php include("../includes/footer.php"); ?>
</body>
</html>
