<?php
session_start();
include("../config/db.php");
header('Content-Type: application/json');

// -------------------- Require login --------------------
if (!isset($_SESSION['user_id'])) {
    echo json_encode(["success" => false, "message" => "Not logged in"]);
    exit();
}

// -------------------- POST data --------------------
$resident_id  = trim($_POST['resident_id'] ?? '');
$cert_type_id = trim($_POST['cert_type_id'] ?? '');
$purpose      = trim($_POST['purpose'] ?? '');
$payment_type = trim($_POST['payment_type'] ?? 'Gcash');

// -------------------- Validate required fields --------------------
if ($resident_id === '' || $cert_type_id === '' || $purpose === '') {
    echo json_encode(["success" => false, "message" => "All fields are required"]);
    exit();
}

// -------------------- Validate payment type --------------------
$validPaymentTypes = ['Gcash','Cash','Other Online Option','Other'];
if (!in_array($payment_type, $validPaymentTypes, true)) {
    $payment_type = 'Gcash';
}

// -------------------- Get certificate fee --------------------
$amount = 0.0;
$stmtFee = $conn->prepare("SELECT fee FROM certificate_type WHERE cert_type_id = ? LIMIT 1");
if (!$stmtFee) {
    echo json_encode(["success" => false, "message" => "Prepare failed (fee): " . $conn->error]);
    exit();
}
$stmtFee->bind_param("s", $cert_type_id);
$stmtFee->execute();
$resFee = $stmtFee->get_result();
if ($resFee && $row = $resFee->fetch_assoc()) {
    $amount = (float)$row['fee'];
}
$stmtFee->close();

// -------------------- Handle file upload --------------------
$requirementsFileName = null;
if (isset($_FILES['requirements']) && isset($_FILES['requirements']['error']) && $_FILES['requirements']['error'] === 0) {
    $uploadDir = "../uploads/";
    if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);

    $origName = basename($_FILES['requirements']['name']);
    $safeName = time() . '_' . preg_replace('/[^a-zA-Z0-9._-]/', '_', $origName);
    $targetPath = $uploadDir . $safeName;

    if (!move_uploaded_file($_FILES['requirements']['tmp_name'], $targetPath)) {
        echo json_encode(["success" => false, "message" => "Failed to move uploaded file."]);
        exit();
    }
    $requirementsFileName = $safeName;
}

// -------------------- Helper: generate next payment ID --------------------
function generatePaymentID($conn) {
    $prefix = "PAY";
    $sql = "SELECT payment_id FROM payments WHERE payment_id LIKE '{$prefix}%' 
            ORDER BY CAST(SUBSTRING(payment_id, " . (strlen($prefix)+1) . ") AS UNSIGNED) DESC LIMIT 1";
    $res = $conn->query($sql);
    if ($res && $res->num_rows > 0) {
        $row = $res->fetch_assoc();
        $num = intval(preg_replace('/\D/', '', $row['payment_id'])) + 1;
    } else {
        $num = 1;
    }
    return $prefix . str_pad($num, 3, "0", STR_PAD_LEFT);
}

// -------------------- Generate certificate ID --------------------
// Fits VARCHAR(15) limit
$certificate_id = 'CERT' . date('ymd') . rand(100, 999); // e.g., C250925123
$certificate_status = 'Not Yet Validated'; // matches ENUM
$payment_status = 'Not Yet Validated';

// -------------------- Start transaction --------------------
$conn->begin_transaction();

try {
    // Insert certificate request
    $stmt = $conn->prepare("
        INSERT INTO certificate_request
            (certificate_id, resident_id, cert_type_id, request_date, purpose, status, requirements)
        VALUES (?, ?, ?, NOW(), ?, ?, ?)
    ");
    if (!$stmt) throw new Exception("Prepare failed (certificate_request): " . $conn->error);

    $stmt->bind_param("ssssss", $certificate_id, $resident_id, $cert_type_id, $purpose, $certificate_status, $requirementsFileName);
    if (!$stmt->execute()) throw new Exception("Execute failed (certificate_request): " . $stmt->error);
    $stmt->close();

    // Insert payment if fee > 0
    $payment_id = null;
    if ($amount > 0.0) {
        $payment_id = generatePaymentID($conn);
        $stmtPay = $conn->prepare("
            INSERT INTO payments
                (payment_id, certificate_id, resident_id, payment_type, amount, status, created_at)
            VALUES (?, ?, ?, ?, ?, ?, NOW())
        ");
        if (!$stmtPay) throw new Exception("Prepare failed (payments): " . $conn->error);

        $stmtPay->bind_param("sssdss", $payment_id, $certificate_id, $resident_id, $payment_type, $amount, $payment_status);
        if (!$stmtPay->execute()) throw new Exception("Execute failed (payments): " . $stmtPay->error);
        $stmtPay->close();
    }

    $conn->commit();

    echo json_encode([
        "success" => true,
        "message" => "Certificate request submitted successfully!",
        "certificate_id" => $certificate_id,
        "payment_id" => $payment_id,
        "amount" => number_format($amount, 2, '.', '')
    ]);
    exit();

} catch (Exception $e) {
    $conn->rollback();
    if (!empty($requirementsFileName) && file_exists("../uploads/" . $requirementsFileName)) {
        @unlink("../uploads/" . $requirementsFileName);
    }
    echo json_encode(["success" => false, "message" => $e->getMessage()]);
    exit();
}
