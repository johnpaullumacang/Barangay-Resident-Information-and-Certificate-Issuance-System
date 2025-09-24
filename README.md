Barangay Resident Information and Certificate Issuance System
📌 DESCRIPTION
The Barangay Resident Information and Certificate Issuance System is an initial prototype built with PHP (vanilla) and MySQL (XAMPP) using VS Code. It is designed to manage barangay resident records, medical histories, and certificate requests while providing role-based dashboards.

The current version includes:
Admin Dashboard – approve resident accounts, manage users, and issue certificates.
Health Worker Dashboard – manage medical records of residents.
Resident Dashboard – register an account (pending admin approval) and request certificates.

⚠️ Note: This is not the final version. Features may be incomplete, and some functions are still under development.

🚀 CURRENT VERSION (NOT FINISHED)
Resident registration with admin approval required
Separate dashboards for Admin, Health Worker, and Resident
Basic certificate request and management system
Initial medical history tracking for residents
Database connection and login system set up

🛠️ Requirements

XAMPP
 (PHP + MySQL)

VS Code or any text editor

Web browser (Chrome, Edge, Firefox, etc.)

⚙️ Installation Guide

Download or Clone the Project
Place the project folder inside your XAMPP htdocs directory:

C:\xampp\htdocs\barangay_system


Create a Database

Start XAMPP and open http://localhost/phpmyadmin

Create a new database, e.g., barangay_db.

Import Database

Locate the .sql file in your project folder (e.g., barangay_db.sql).

Import it into the barangay_db.

Configure Database Connection
Edit config/db.php with your settings:

<?php
$host = "localhost";
$user = "root";     // default XAMPP user
$pass = "";         // default password (blank if none)
$dbname = "barangay_db";

$conn = new mysqli($host, $user, $pass, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>


Run the System

Start Apache and MySQL in XAMPP

Open your browser and go to:

http://localhost/barangay_system/

👩‍💻 User Roles (Initial)
🔹 Admin

Approves resident accounts before they can log in

Issues certificates

Manages health workers and resident profiles

🔹 Health Worker

Updates and monitors residents’ medical history

Supports medical-related certificate requests

🔹 Resident

Registers for an account (pending approval)

Logs in after approval by admin

Requests barangay certificates

📊 Notes & Limitations

This is the initial system only (not yet final).

Some features are placeholders or under development.

Bugs, UI issues, or missing functions may be present.

Future updates will include advanced certificate processing, reporting, and enhanced medical record management.
