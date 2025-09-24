<?php
include("../config/db.php");
include("../includes/header.php");

$user_id = $_GET['user_id'] ?? null;
if (!$user_id) die("Invalid access. Please register first.");

// Handle form submission
if (isset($_POST['save_resident'])) {
    $resident_id = substr(uniqid("RES"), 0, 15);
    $first_name  = trim($_POST['first_name']);
    $mi          = trim($_POST['mi']);
    $last_name   = trim($_POST['last_name']);
    $birth_date  = $_POST['birth_date'];
    $gender      = $_POST['gender'];
    $contact     = trim($_POST['contact_number']);
    $civil_status= trim($_POST['civil_status']);
    $purok       = trim($_POST['purok']);
    $barangay    = trim($_POST['barangay']);
    $municipality= trim($_POST['municipality']);
    $province    = trim($_POST['province']);
    $cedula      = trim($_POST['cedula_number'] ?? '');
    $date_reg    = date("Y-m-d");
    $verified    = "Pending";

    // Handle proof_of_residency upload
    $proof_name = null;
    if (isset($_FILES['proof_of_residency']) && $_FILES['proof_of_residency']['error'] == 0) {
        $allowed_ext = ['jpg','jpeg','png','pdf'];
        $file_ext = strtolower(pathinfo($_FILES['proof_of_residency']['name'], PATHINFO_EXTENSION));
        if (in_array($file_ext, $allowed_ext)) {
            if (!is_dir("../uploads")) mkdir("../uploads", 0755, true);
            $proof_name = $resident_id . "_" . time() . "." . $file_ext;
            move_uploaded_file($_FILES['proof_of_residency']['tmp_name'], "../uploads/" . $proof_name);
        } else {
            echo "<div class='alert alert-danger'>Invalid file type. Only JPG, PNG, PDF allowed.</div>";
        }
    } else {
        echo "<div class='alert alert-danger'>Proof of residency is required.</div>";
    }

    // Prevent duplication in resident table
    if (!empty($cedula)) {
        $dup_stmt = $conn->prepare("SELECT * FROM resident WHERE cedula_number = ? LIMIT 1");
        $dup_stmt->bind_param("s", $cedula);
    } else {
        $dup_stmt = $conn->prepare("SELECT * FROM resident WHERE first_name = ? AND last_name = ? AND birth_date = ? AND barangay = ? LIMIT 1");
        $dup_stmt->bind_param("ssss", $first_name, $last_name, $birth_date, $barangay);
    }
    $dup_stmt->execute();
    $dup_result = $dup_stmt->get_result();
    if ($dup_result->num_rows > 0) {
        echo "<div class='alert alert-danger'>Resident already exists in the system.</div>";
        $dup_stmt->close();
    } else {
        $dup_stmt->close();

        // Auto verification
        if (!empty($cedula)) {
            $stmt = $conn->prepare("SELECT * FROM master_list WHERE cedula_number = ? LIMIT 1");
            $stmt->bind_param("s", $cedula);
        } else {
            $stmt = $conn->prepare("SELECT * FROM master_list WHERE first_name = ? AND last_name = ? AND birth_date = ? AND barangay = ? LIMIT 1");
            $stmt->bind_param("ssss", $first_name, $last_name, $birth_date, $barangay);
        }
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) $verified = "Verified";
        $stmt->close();

        // Insert resident info
        $insert_stmt = $conn->prepare("INSERT INTO resident 
            (resident_id, user_id, first_name, mi, last_name, birth_date, gender, contact_number, civil_status, purok, barangay, municipality, province, status, date_registered, proof_of_residency, cedula_number) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $insert_stmt->bind_param("sssssssssssssssss", 
            $resident_id, $user_id, $first_name, $mi, $last_name, $birth_date,
            $gender, $contact, $civil_status, $purok, $barangay, $municipality, $province, $verified, $date_reg, $proof_name, $cedula);

        if ($insert_stmt->execute()) {
            echo "<div class='alert alert-success'>Resident information submitted successfully. Status: <strong>$verified</strong>.</div>";
        } else {
            echo "<div class='alert alert-danger'>Error: " . $insert_stmt->error . "</div>";
        }
        $insert_stmt->close();
    }
}
?>

<!-- HTML & CSS remain unchanged -->
<style>
/* Match register.php styles */
.register-wrapper { 
    position: relative; 
    z-index: 2; 
    display: flex; 
    justify-content: center; 
    align-items: center; 
    width: 100%; 
    min-height: 90vh;
}
.register-card {
    max-width: 500px;
    width: 100%;
    background: #09f8f8ff;
    border-radius: 15px;
    box-shadow: 0 8px 25px rgba(0,0,0,0.2);
    padding: 40px 30px;
    text-align: center;
    animation: fadeIn 0.6s ease-in-out;
}
.register-card h3 { margin-bottom: 10px; font-weight: 700; color: #2c3e50; }
.register-card p { margin-bottom: 25px; font-size: 14px; color: #6c757d; }
.form-control { 
    border-radius: 10px; 
    padding: 12px; 
    border: 1px solid #ddd; 
    transition: all 0.3s ease; 
    font-size: 14px;
    color: #2c3e50;
}
.form-control::placeholder { color: #6c757d; }
.form-control:focus { border-color: #28a745; box-shadow: 0 0 6px rgba(40,167,69,0.4); }
.btn-custom { 
    background: linear-gradient(135deg, #2575fc, #6a11cb); 
    border: none; 
    border-radius: 10px; 
    padding: 12px; 
    font-size: 16px; 
    font-weight: 600; 
    color: #fff; 
    cursor: pointer; 
    transition: all 0.3s ease; 
}
.btn-custom:hover { 
    background: linear-gradient(135deg, #6a11cb, #2575fc); 
    transform: translateY(-2px); 
    box-shadow: 0 6px 15px rgba(0,0,0,0.25); 
}
.btn-custom:active { transform: scale(0.97); }
.extra-links { margin-top: 20px; font-size: 14px; }
.extra-links a { text-decoration: none; color: #2575fc; font-weight: 500; }
.extra-links a:hover { text-decoration: underline; }
.alert { margin-top: 15px; padding: 12px; border-radius: 8px; font-size: 14px; }
@keyframes fadeIn { from { opacity: 0; transform: translateY(-15px); } to { opacity: 1; transform: translateY(0); } }
@media (prefers-color-scheme: dark) {
    .register-card { background: #1e1e2f; color: #f0f0f0; }
    .register-card h3 { color: #11fddeff; }
    .form-control { background: #2a2a3d; border: 1px solid #444; color: #f0f0f0; }
    .form-control::placeholder { color: #aaa; }
    .form-control:focus { border-color: #28a745; box-shadow: 0 0 6px rgba(40,167,69,0.6); }
    .extra-links a { color: #6a11cb; }
}
</style>

<div class="register-wrapper">
    <div class="register-card">
        <h3>Resident Information</h3>
        <p>Fill out your details to complete your registration.</p>

        <!-- Back Button -->
        <button type="button" class="btn btn-custom mb-3" style="background: #6c757d;" onclick="history.back();">
            &larr; Back
        </button>

        <form method="POST" enctype="multipart/form-data">
            <input type="text" name="first_name" class="form-control mb-3" placeholder="First Name" required>
            <input type="text" name="mi" class="form-control mb-3" placeholder="M.I.">
            <input type="text" name="last_name" class="form-control mb-3" placeholder="Last Name" required>
            <input type="date" name="birth_date" class="form-control mb-3" required>
            <select name="gender" class="form-control mb-3" required>
                <option value="">-- Gender --</option>
                <option value="Male">Male</option>
                <option value="Female">Female</option>
            </select>
            <input type="text" name="contact_number" class="form-control mb-3" placeholder="Contact Number" required>
            <input type="text" name="civil_status" class="form-control mb-3" placeholder="Civil Status" required>
            <input type="text" name="purok" class="form-control mb-3" placeholder="Purok" required>
            <input type="text" name="barangay" class="form-control mb-3" placeholder="Barangay" required>
            <input type="text" name="municipality" class="form-control mb-3" placeholder="Municipality" required>
            <input type="text" name="province" class="form-control mb-3" placeholder="Province" required>

            <label class="mb-1">Upload Barangay Clearance / ID (Proof of Residency) <span style="color:red;">*Required</span></label>
            <input type="file" name="proof_of_residency" class="form-control mb-3" required>

            <label class="mb-1">Cedula Number (Optional)</label>
            <input type="text" name="cedula_number" class="form-control mb-3">

            <button type="submit" name="save_resident" class="btn btn-custom w-100">Submit Info</button>
        </form>
    </div>
</div>
