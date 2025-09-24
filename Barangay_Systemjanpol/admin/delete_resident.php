<?php
include("../config/db.php");

$id = $_GET['id'] ?? '';
$result = $conn->query("SELECT * FROM resident WHERE resident_id='$id'");
$resident = $result->fetch_assoc();

if ($resident) {
    if (!empty($resident['photo']) && file_exists("../uploads/residents/".$resident['photo'])) {
        unlink("../uploads/residents/".$resident['photo']);
    }
    $conn->query("DELETE FROM resident WHERE resident_id='$id'");
}

header("Location: residents.php");
exit;
?>
