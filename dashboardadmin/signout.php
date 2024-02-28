<?php
session_start();

// Hapus sesi
unset($_SESSION["user_id"]);

// Redirect ke halaman login
header("Location: ../login.php");
exit;
?>