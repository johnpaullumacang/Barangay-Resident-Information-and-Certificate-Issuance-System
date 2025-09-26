<?php
session_start();
include("../config/db.php");

if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$res = $conn->query("SELECT resident_id FROM resident WHERE user_id='$user_id' LIMIT 1");
$resident = $res->fetch_assoc();
$resident_id = $resident['resident_id'];
?>

<?php include("../includes/header.php"); ?>
<?php include("../includes/sidebar_resident.php"); ?>

<style>
/* --- General Styles --- */
html, body { overflow-x:hidden; font-family:"Segoe UI", Arial, sans-serif; background:#f8f9fa; color:#212529; margin:0; padding:0; }
.content-wrapper { display:flex; flex-direction:column; align-items:center; padding:30px; min-height:100vh; margin-left:250px; width:calc(100% - 250px); box-sizing:border-box; }
.page-title { font-weight:700; color:#0d6efd; margin-top:20px; margin-bottom:60px; border-left:6px solid #0d6efd; padding-left:14px; display:flex; gap:10px; }

/* --- Certificate Cards --- */
.row.g-4 { display:flex; flex-wrap:wrap; gap:20px; justify-content:center; max-width:1200px; width:100%; align-items:stretch; }
.cert-item { flex:0 1 calc(33.333% - 20px); display:flex; box-sizing:border-box; }
.cert-card { background:#fff; border-radius:16px; padding:25px 20px; display:flex; flex-direction:column; justify-content:space-between; cursor:pointer; flex:1; box-shadow:0 8px 25px rgba(0,0,0,0.08); transition: transform 0.3s ease, box-shadow 0.3s ease; }
.cert-card:hover { transform:translateY(-8px); box-shadow:0 12px 35px rgba(0,0,0,0.15); }
.cert-title { font-size:20px; font-weight:700; color:#0d6efd; margin-bottom:12px; text-align:center; }
.cert-desc { font-size:14px; color:#6c757d; text-align:center; margin-bottom:20px; flex-grow:1; }
#certSearch { margin-bottom:30px; padding:10px 15px; width:300px; border-radius:8px; border:1px solid #ced4da; transition:all 0.2s ease; }
#certSearch:focus { outline:none; border-color:#0d6efd; box-shadow:0 0 5px rgba(13,110,253,0.5); }
/* ===== Buttons ===== */
.proceed-btn, .payment-card .btn {
    font-weight: 600;
    border-radius: 12px;
    padding: 12px 20px;
    cursor: pointer;
    transition: all 0.2s ease;
    text-align: center;
    min-width: 150px;
    margin: 8px;
}

/* Primary (Blue) */
.proceed-btn, .payment-card .btn-primary {
    background-color: #0d6efd;
    color: #fff;
    border: none;
}
.proceed-btn:hover, .payment-card .btn-primary:hover {
    background-color: #0b5ed7;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
}

/* Success (Green) */
.payment-card .btn-success {
    background: #198754;
    color: #fff;
    border: none;
}
.payment-card .btn-success:hover {
    background: #157347;
    transform: translateY(-2px);
    box-shadow: 0 6px 15px rgba(25,135,84,0.25);
}

/* Cancel (Gray) */
.payment-card .btn-cancel {
    background: #6c757d;
    color: #fff;
    border: none;
}
.payment-card .btn-cancel:hover {
    background: #5c636a;
    transform: translateY(-2px);
    box-shadow: 0 6px 15px rgba(108,117,125,0.25);
}

/* ===== Modal Overlay ===== */
.modal-overlay {
    position: fixed;
    top: 0; left: 0;
    width: 100%; height: 100%;
    display: none;
    justify-content: center;
    align-items: center;
    backdrop-filter: blur(6px);
    background: rgba(0,0,0,0.3);
    z-index: 2000;
}

/* ===== Modal Card ===== */
.payment-card {
    background: #fff;
    border-radius: 16px;
    padding: 40px 30px;
    max-width: 500px;
    width: 100%;
    box-shadow: 0 12px 30px rgba(0,0,0,0.25);
    text-align: center;
    position: relative;
    animation: popIn 0.4s forwards;
}
@keyframes popIn {
    0% { transform: scale(0.8); opacity: 0; }
    100% { transform: scale(1); opacity: 1; }
}
.payment-card h2 {
    margin-bottom: 25px;
    font-weight: 700;
    color: #0d6efd;
}
.payment-card .amount {
    font-size: 24px;
    font-weight: 600;
    color: #198754;
    margin-bottom: 25px;
}
.qr-placeholder {
    background: #e9ecef;
    border-radius: 12px;
    padding: 40px 20px;
    margin-bottom: 25px;
    font-weight: 600;
    color: #495057;
}

/* ===== Close Button ===== */
.close-btn {
    position: absolute;
    top: 15px; right: 15px;
    background: transparent;
    border: none;
    font-size: 28px;
    cursor: pointer;
    color: #495057;
    transition: color 0.2s;
}
.close-btn:hover {
    color: #dc3545;
}

/* ===== Responsive ===== */
@media (max-width: 992px) { .cert-item { flex: 0 1 calc(50% - 20px); } }
@media (max-width: 768px) { .cert-item { flex: 0 1 100%; } }

/* ===== Modal Form ===== */
.modal-form {
    display: flex;
    flex-direction: column;
    gap: 20px;
    margin-top: 20px;
}
.form-group {
    display: flex;
    flex-direction: column;
    text-align: left;
}
.form-group label {
    font-weight: 600;
    margin-bottom: 8px;
    color: #495057;
    font-size: 0.95rem;
}
.form-group textarea,
.form-group input[type="file"] {
    border: 1px solid #ced4da;
    border-radius: 10px;
    padding: 12px;
    font-size: 0.95rem;
    transition: border-color 0.2s, box-shadow 0.2s;
}
.form-group textarea:focus,
.form-group input[type="file"]:focus {
    outline: none;
    border-color: #0d6efd;
    box-shadow: 0 0 6px rgba(13,110,253,0.25);
}
.form-actions {
    display: flex;
    justify-content: center;
    gap: 15px;
    margin-top: 10px;
}

/* ===== File Upload Styling ===== */
.file-upload {
    border: 2px dashed #ced4da;
    border-radius: 12px;
    padding: 25px;
    background: #f8f9fa;
    text-align: center;
    cursor: pointer;
    position: relative;
    transition: all 0.2s ease;
}
.file-upload:hover {
    background: #f1f3f5;
    border-color: #0d6efd;
}
.file-upload input[type="file"] {
    width: 100%;
    height: 100px;
    opacity: 0;
    cursor: pointer;
    position: absolute;
    top:0; left:0;
}
.file-upload::before {
    content: "Click or Drag & Drop files here";
    display: block;
    color: #6c757d;
    font-size: 1rem;
    font-weight: 500;
    margin-top: 10px;
}
</style>
<div class="content-wrapper">
    <h2 class="page-title"><i class="bi bi-file-earmark-text"></i> Request a Barangay Certificate</h2>
    <input type="text" id="certSearch" placeholder="Search certificates...">

    <div class="row g-4 justify-content-center" id="certContainer">
        <?php
        $sql = "SELECT * FROM certificate_type ORDER BY cert_name ASC";
        $result = $conn->query($sql);
        if($result && $result->num_rows > 0):
            while($cert = $result->fetch_assoc()):
                $icon = !empty($cert['icon']) ? $cert['icon'] : 'bi-file-earmark-text';
        ?>
        <div class="col-lg-4 col-md-6 col-sm-12 cert-item">
            <div class="cert-card"
                 data-name="<?= htmlspecialchars($cert['cert_name'], ENT_QUOTES) ?>"
                 data-desc="<?= htmlspecialchars($cert['description'], ENT_QUOTES) ?>"
                 data-fee="<?= $cert['fee'] ?>"
                 data-id="<?= $cert['cert_type_id'] ?>">
                
                <div class="cert-title">
                    <i class="bi <?= htmlspecialchars($icon, ENT_QUOTES) ?>"></i>
                    <?= htmlspecialchars($cert['cert_name'], ENT_QUOTES) ?>
                </div>

               
                <p class="cert-desc"><?= htmlspecialchars($cert['description'], ENT_QUOTES) ?></p>
                <button type="button" class="btn btn-primary">
                    <?= $cert['fee'] > 0 ? "Proceed - ₱" . number_format($cert['fee'],2) : "Proceed - Free" ?>
                </button>
            </div>
        </div>
        <?php
            endwhile;
        else:
        ?>
        <p>No certificates available.</p>
        <?php endif; ?>
    </div>
</div>

<<!-- Modal 1: Certificate Details -->
<div class="modal-overlay" id="certDetailsModal">
  <div class="payment-card">
    <button class="close-btn" onclick="closeAllModals()">&times;</button>

    <!-- Title with Icon -->
    <h2>
      <i id="modalCertIcon" class="bi bi-file-earmark-text me-2"></i>
      <span id="modalCertTitle"></span>
    </h2>
    <p id="modalCertDesc" class="modal-subtext"></p>

    <form id="certForm" class="modal-form">
      <input type="hidden" name="cert_type" id="formCertType">
      <input type="hidden" name="cert_type_id" id="formCertTypeId">
      <input type="hidden" name="amount" id="formAmount">

      <!-- Purpose -->
      <div class="form-group">
        <label for="formPurpose">
          <i class="bi bi-pencil-square me-1"></i> Purpose
        </label>
        <textarea name="purpose" id="formPurpose" rows="3" placeholder="Enter purpose here..." required></textarea>
      </div>

      <!-- Requirements -->
      <div class="form-group">
        <label for="formRequirements">
          <i class="bi bi-upload me-1"></i> Upload Requirements
        </label>
        <div class="file-upload">
          <input type="file" name="requirements" id="formRequirements" accept="image/*">
        
        </div>
      </div>

     <!-- Action Buttons -->
<div class="form-actions">
  <button type="button" class="btn btn-primary" onclick="validateCertForm()">
     Proceed
  </button>
  <button type="button" class="btn btn-cancel" onclick="closeAllModals()">
    Cancel
  </button>
</div>

      </div>
    </form>
  </div>
</div>


<!-- Modal 2: Payment or Free -->
<div class="modal-overlay" id="paymentModal">
  <div class="payment-card">
    <button class="close-btn" onclick="closeAllModals()">&times;</button>
    <h2 id="paymentTitle"></h2>
    <p id="paymentInfo"></p>
    <div id="qrCodeBox" class="qr-placeholder" style="display:none;">[ QR Code Placeholder ]</div>

    <div>
      <button type="button" class="btn btn-success" onclick="submitCertRequest()">Confirm Payment</button>
      <button type="button" class="btn btn-cancel" onclick="closeAllModals()">Cancel</button>
    </div>
  </div>
</div>

<!-- Modal 3: Confirmation -->
<div class="modal-overlay" id="confirmModal">
  <div class="payment-card">
    <button class="close-btn" onclick="closeAllModals()">&times;</button>
    <h2>Request Submitted</h2>
    <p>Your certificate request has been submitted successfully!</p>
    <button type="button" class="btn btn-primary" onclick="closeAllModals()">Close</button>
  </div>
</div>

<script>
// Open certificate details modal
document.querySelectorAll('.cert-card').forEach(card => {
    card.addEventListener('click', () => {
        const name = card.getAttribute('data-name');
        const desc = card.getAttribute('data-desc');
        const fee = card.getAttribute('data-fee');
        const id = card.getAttribute('data-id');

        document.getElementById('modalCertTitle').innerText = name;
        document.getElementById('modalCertDesc').innerText = desc;
        document.getElementById('formCertTypeId').value = id;
        document.getElementById('formAmount').value = fee;
        document.getElementById('formPurpose').value = '';
        document.getElementById('formRequirements').value = '';

        document.getElementById('certDetailsModal').style.display = 'flex';
    });
});

// Ensure inner button clicks also open modal
document.querySelectorAll('.cert-card .btn').forEach(btn => {
    btn.addEventListener('click', e => {
        e.stopPropagation();
        btn.closest('.cert-card').click();
    });
});

function validateCertForm(){
    const purpose = document.getElementById("formPurpose").value.trim();
    // const files = document.getElementById("formRequirements").files.length; // REMOVE THIS check
    if(!purpose){ 
        alert("Please enter the purpose."); 
        return; 
    }
    goToPaymentModal();
}


function goToPaymentModal(){
    const amount = parseFloat(document.getElementById('formAmount').value);
    document.getElementById('certDetailsModal').style.display='none';
    document.getElementById('paymentModal').style.display='flex';
    if(amount>0){
        document.getElementById('paymentTitle').innerText="Payment Required";
        document.getElementById('paymentInfo').innerText="Please pay ₱"+amount.toFixed(2);
        document.getElementById('qrCodeBox').style.display='block';
    } else {
        document.getElementById('paymentTitle').innerText="Free Certificate";
        document.getElementById('paymentInfo').innerText="No payment required.";
        document.getElementById('qrCodeBox').style.display='none';
    }
}

function submitCertRequest(){
    const form = document.getElementById("certForm");
    const purpose = form.querySelector("#formPurpose").value.trim();
    const cert_type_id = form.querySelector("#formCertTypeId").value;
    const fileInput = form.querySelector("#formRequirements");

    if (!purpose) { alert("Please enter the purpose."); return; }

    const formData = new FormData();
    formData.append("resident_id", "<?= $resident_id ?>"); // must match PHP
    formData.append("cert_type_id", cert_type_id);
    formData.append("purpose", purpose);

    if (fileInput.files.length > 0) {
        formData.append("requirements", fileInput.files[0]);
    }

    fetch("save_certificate.php", { method: "POST", body: formData })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                closeAllModals();
                document.getElementById('confirmModal').style.display = 'flex';
            } else {
                alert("Error: " + data.message);
            }
        }).catch(err => {
            console.error(err);
            alert("Something went wrong.");
        });
}


function closeAllModals(){
    document.querySelectorAll('.modal-overlay').forEach(m=>m.style.display='none');
}

// Live search by certificate name
document.getElementById('certSearch').addEventListener('input', function(){
    const query = this.value.toLowerCase();
    document.querySelectorAll('.cert-item').forEach(card=>{
        const title = card.querySelector('.cert-title').innerText.toLowerCase();
        card.style.display = title.includes(query)?'flex':'none';
    });
});
</script>

<?php include("../includes/footer.php"); ?>

