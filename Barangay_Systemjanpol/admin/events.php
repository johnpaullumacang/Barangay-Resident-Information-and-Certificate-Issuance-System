<?php
session_start();
include("../config/db.php");
include("../includes/header.php");

// Fetch all events
$events = $conn->query("SELECT * FROM events ORDER BY event_date DESC");
?>

<div class="container mt-4">
    <h2 class="fw-bold">📅 Manage Events</h2>
    <a href="event_add.php" class="btn btn-primary mb-3">+ Add New Event</a>

    <table class="table table-bordered table-striped">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Title</th>
                <th>Date & Time</th>
                <th>Location</th>
                <th>Description</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php while($row = $events->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $row['event_id']; ?></td>
                    <td><?php echo htmlspecialchars($row['title']); ?></td>
                    <td><?php echo date("M d, Y", strtotime($row['event_date'])) . " " . $row['event_time']; ?></td>
                    <td><?php echo htmlspecialchars($row['location']); ?></td>
                    <td><?php echo htmlspecialchars($row['description']); ?></td>
                    <td>
                        <a href="event_edit.php?id=<?php echo $row['event_id']; ?>" class="btn btn-warning btn-sm">Edit</a>
                        <a href="event_delete.php?id=<?php echo $row['event_id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Delete this event?');">Delete</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

<?php include("../includes/footer.php"); ?>
