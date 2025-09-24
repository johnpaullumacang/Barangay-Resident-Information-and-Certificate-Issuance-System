<?php
$password = "admin123"; // password nga gusto nimo
$hashed = password_hash($password, PASSWORD_BCRYPT);
echo $hashed;
?>
