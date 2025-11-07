<?php
include 'koneksi.php';

// Cek apakah 'id' ada di URL
if (isset($_GET['id'])) {
    $recipe_id = $_GET['id'];

    // Siapkan statement DELETE (Pakai prepared statement agar aman)
    $sql = "DELETE FROM recipes WHERE recipe_id = ?";
    $stmt = $conn->prepare($sql);

    if ($stmt === false) {
        die("Error preparing statement: " . $conn->error);
    }

    // Bind ID ke statement
    $stmt->bind_param("i", $recipe_id);

    // Eksekusi
    if ($stmt->execute()) {
        // Jika berhasil, kembali ke index.php
        header("Location: index.php");
        exit();
    } else {
        echo "Error menghapus resep: " . $stmt->error;
    }

    $stmt->close();
} else {
    // Jika tidak ada ID, kembalikan ke index.php
    header("Location: index.php");
    exit();
}

$conn->close();
?>