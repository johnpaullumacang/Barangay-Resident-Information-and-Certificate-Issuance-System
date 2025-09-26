<?php
session_start();
include("../config/db.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $cert_type = $_POST['cert_type'];
    $cert_type_id = $_POST['cert_type_id'];
    $amount = $_POST['amount'];
    $purpose = $_POST['purpose'];

    // Handle file upload
    if(isset($_FILES['requirements']) && $_FILES['requirements']['error'] === 0){
        $file_name = time() . '_' . $_FILES['requirements']['name'];
        move_uploaded_file($_FILES['requirements']['tmp_name'], "../uploads/$file_name");
    } else {
        $file_name = null;
    }

    // Save to DB
    $stmt = $conn->prepare("INSERT INTO certificate_request (resident_id, cert_type, cert_type_id, amount, purpose, requirements) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ississ", $_SESSION['resident_id'], $cert_type, $cert_type_id, $amount, $purpose, $file_name);
    $stmt->execute();

    echo "Request submitted successfully!";
}
