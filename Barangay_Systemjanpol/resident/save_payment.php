<?php
session_start();
include("../config/db.php");

if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit();
}

// Function to generate the next payment ID in PAYXXX format
function generatePaymentID($conn) {
    $prefix = "PAY";

    // Only consider existing PAYXXX IDs
    $res = $conn->query("SELECT payment_id FROM payments WHERE payment_id LIKE '$prefix%' ORDER BY created_at DESC LIMIT 1");

    if ($res && $res->num_rows > 0) {
        $row = $res->fetch_assoc();
        $lastId = $row['payment_id']; // e.g., PAY007
        $num = intval(substr($lastId, strlen($prefix))) + 1; // extract number and add 1
    } else {
        $num = 1; // first payment
    }

    return $prefix . str_pad($num, 3, "0", STR_PAD_LEFT); // e.g., PAY001
}


if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $user_id = $_SESSION['user_id'];
    $cert_name = trim($_POST['cert_type']); // name of certificate selected

    // Get resident_id for logged-in user
    $res = $conn->query("SELECT resident_id FROM resident WHERE user_id='$user_id' LIMIT 1");
    if (!$res || $res->num_rows == 0) {
        header("Location: certificates.php?error=" . urlencode("Resident not found."));
        exit();
    }
    $resident = $res->fetch_assoc();
    $resident_id = $resident['resident_id'];

    // Get cert_type_id and fee from certificate_type
    $stmt_cert = $conn->prepare("SELECT cert_type_id, fee FROM certificate_type WHERE cert_name = ? LIMIT 1");
    $stmt_cert->bind_param("s", $cert_name);
    $stmt_cert->execute();
    $result_cert = $stmt_cert->get_result();

    if (!$result_cert || $result_cert->num_rows == 0) {
        header("Location: certificates.php?error=" . urlencode("Certificate type not found."));
        exit();
    }

    $cert = $result_cert->fetch_assoc();
    $cert_type_id = $cert['cert_type_id'];
    $amount = floatval($cert['fee']);

    // Generate unique certificate_id
    $certificate_id = "CERT".date("Ymd")."-".rand(100,999);
    $barangay_id = "BRGY001"; // replace if dynamic
    $purpose = "Requested via resident portal";
    $status = "pending"; // initial request
    $release_date = NULL;
    $remarks = NULL;

    // Insert certificate request
    $stmt_req = $conn->prepare("INSERT INTO certificate_request (certificate_id, barangay_id, resident_id, cert_type_id, request_date, purpose, status, release_date, remarks) VALUES (?, ?, ?, ?, NOW(), ?, ?, ?, ?)");
    $stmt_req->bind_param("ssssssss", $certificate_id, $barangay_id, $resident_id, $cert_type_id, $purpose, $status, $release_date, $remarks);

    if (!$stmt_req->execute()) {
        header("Location: certificates.php?error=" . urlencode($stmt_req->error));
        exit();
    }

    // Create payment if fee > 0
    if ($amount > 0) {
        $payment_id = generatePaymentID($conn); // PAYXXX format
        $stmt_pay = $conn->prepare("INSERT INTO payments (payment_id, resident_id, payment_type, amount, status, created_at) VALUES (?, ?, ?, ?, 'pending', NOW())");
        $stmt_pay->bind_param("sssd", $payment_id, $resident_id, $cert_name, $amount);
        $stmt_pay->execute();
    }

    header("Location: certificates.php?success=1");
    exit();

} else {
    header("Location: certificates.php");
    exit();
}
?>
