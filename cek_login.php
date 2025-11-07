<?php
// File ini akan di-include di halaman yang butuh proteksi login
session_start();

// Cek apakah user sudah login
if (!isset($_SESSION['user_id']) || !isset($_SESSION['is_admin'])) {
    // Simpan halaman yang ingin dituju
    $_SESSION['redirect_after_login'] = $_SERVER['PHP_SELF'];
    
    header("Location: login.php");
    exit();
}
?>