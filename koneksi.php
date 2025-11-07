<?php

$servername = "localhost"; // Biarkan 'localhost' jika pakai XAMPP
$username = "root";        // Default username XAMPP
$password = "";            // Default password XAMPP (kosong)
$dbname = "db_resep";      // Nama database yang Anda buat

// Membuat koneksi
$conn = new mysqli($servername, $username, $password, $dbname);

// Cek koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// echo "Koneksi berhasil"; // Hapus atau beri komentar setelah tes berhasil
?>