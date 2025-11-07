<?php
// Debugging-enabled login handler
session_start();
// Tuned for development: show errors and log to error log
error_reporting(E_ALL);
ini_set('display_errors', '1');
ini_set('log_errors', '1');
ini_set('error_log', __DIR__ . '/php-error.log');

include 'koneksi.php';

// Ambil data dari form
$username = trim($_POST['username'] ?? '');
$password = $_POST['password'] ?? '';

// Validasi input dasar
if (empty($username) || empty($password)) {
    $_SESSION['login_error'] = "Username dan password harus diisi.";
    error_log("[login] Missing username or password");
    header("Location: login.php");
    exit();
}

// Query untuk mencari user (gunakan prepared statement)
$sql = "SELECT user_id, username, password FROM users WHERE username = ? LIMIT 1";
$stmt = $conn->prepare($sql);

if ($stmt === false) {
    $err = $conn->error ?? 'prepare failed';
    error_log("[login] MySQL prepare failed: " . $err);
    $_SESSION['login_error'] = "Error system: tidak dapat memproses permintaan.";
    header("Location: login.php");
    exit();
}

$stmt->bind_param("s", $username);
if (! $stmt->execute()) {
    error_log("[login] Statement execute failed: " . $stmt->error);
    $_SESSION['login_error'] = "Error system: gagal mengeksekusi query.";
    $stmt->close();
    $conn->close();
    header("Location: login.php");
    exit();
}

$result = $stmt->get_result();
$user = $result ? $result->fetch_assoc() : null;

// Debug: log whether user record exists
error_log("[login] Attempt for username='" . $username . "' - user_found=" . ($user ? '1' : '0'));

// Verifikasi password (gunakan password_verify karena password di DB di-hash)
if ($user) {
    if (password_verify($password, $user['password'])) {
        // Login berhasil
        $_SESSION['user_id'] = $user['user_id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['is_admin'] = true; // Untuk contoh ini semua user dianggap admin

        error_log("[login] Success for user_id=" . $user['user_id'] . " username=" . $user['username']);
        $stmt->close();
        $conn->close();
        header("Location: index.php");
        exit();
    } else {
        // Password mismatch
        error_log("[login] Password mismatch for username='" . $username . "'");
        $_SESSION['login_error'] = "Username atau password salah.";
        $stmt->close();
        $conn->close();
        header("Location: login.php");
        exit();
    }

} else {
    // No such user
    error_log("[login] No user record for username='" . $username . "'");
    $_SESSION['login_error'] = "Username atau password salah.";
    $stmt->close();
    $conn->close();
    header("Location: login.php");
    exit();
}

?>