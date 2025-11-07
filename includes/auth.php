<?php
// File ini akan di-include di halaman yang butuh proteksi login
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Cek apakah user sudah login sebagai admin
if (!isset($_SESSION['user_id']) || !isset($_SESSION['is_admin'])) {
    // Simpan halaman yang ingin dituju
    $_SESSION['redirect_after_login'] = $_SERVER['REQUEST_URI'];
    $_SESSION['login_error'] = "Anda harus login sebagai admin untuk mengakses halaman ini.";
    
    header("Location: login.php");
    exit();
}
?>