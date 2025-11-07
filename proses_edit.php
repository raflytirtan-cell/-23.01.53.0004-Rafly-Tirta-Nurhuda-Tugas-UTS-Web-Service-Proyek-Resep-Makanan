<?php
include 'koneksi.php';

// Cek apakah data dikirim dengan method POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // Start transaction
    $conn->begin_transaction();
    
    try {
    // 1. Ambil semua data dari formulir (dengan sanitasi dasar)
    $recipe_id = isset($_POST['recipe_id']) ? (int)$_POST['recipe_id'] : 0;
    $judul = isset($_POST['judul_resep']) ? trim($_POST['judul_resep']) : '';
    $porsi = isset($_POST['porsi']) ? (int)$_POST['porsi'] : null;
    $lama_memasak = isset($_POST['lama_memasak_menit']) ? (int)$_POST['lama_memasak_menit'] : null;
    $instruksi = isset($_POST['instruksi_memasak']) ? trim($_POST['instruksi_memasak']) : '';
        
        // 2. Update recipe details
        $sql = "UPDATE recipes 
                SET judul_resep = ?, 
                    instruksi_memasak = ?, 
                    porsi = ?, 
                    lama_memasak_menit = ? 
                WHERE recipe_id = ?";
        
        $stmt = $conn->prepare($sql);
        if ($stmt === false) {
            throw new Exception("Error preparing recipe update statement: " . $conn->error);
        }
        
        $stmt->bind_param("ssiii", $judul, $instruksi, $porsi, $lama_memasak, $recipe_id);
        if (!$stmt->execute()) {
            throw new Exception("Error updating recipe: " . $stmt->error);
        }
        $stmt->close();
        
        // 3. Delete existing ingredients
        $sql_delete = "DELETE FROM recipe_ingredients WHERE recipe_id = ?";
        $stmt_delete = $conn->prepare($sql_delete);
        if ($stmt_delete === false) {
            throw new Exception("Error preparing delete statement: " . $conn->error);
        }
        
        $stmt_delete->bind_param("i", $recipe_id);
        if (!$stmt_delete->execute()) {
            throw new Exception("Error deleting ingredients: " . $stmt_delete->error);
        }
        $stmt_delete->close();
        
        // 4. Insert new ingredients
        if (isset($_POST['ingredients']) && is_array($_POST['ingredients'])) {
            $sql_insert = "INSERT INTO recipe_ingredients (recipe_id, ingredient_id, unit_id, jumlah) VALUES (?, ?, ?, ?)";
            $stmt_insert = $conn->prepare($sql_insert);
            if ($stmt_insert === false) {
                throw new Exception("Error preparing insert statement: " . $conn->error);
            }
            
            foreach ($_POST['ingredients'] as $index => $ingredient_id) {
                // basic checks: ingredient id must be present and numeric
                if (empty($ingredient_id) || !is_numeric($ingredient_id)) continue;

                // ensure corresponding unit and quantity exist
                if (!isset($_POST['units'][$index]) || !isset($_POST['quantities'][$index])) continue;

                $unit_id = $_POST['units'][$index];
                $quantity = trim($_POST['quantities'][$index]);

                if ($unit_id === '' || $quantity === '') continue;

                $ingredient_id = (int)$ingredient_id;
                $unit_id = (int)$unit_id;

                $stmt_insert->bind_param("iiis", $recipe_id, $ingredient_id, $unit_id, $quantity);
                if (!$stmt_insert->execute()) {
                    throw new Exception("Error inserting ingredient: " . $stmt_insert->error);
                }
            }
            $stmt_insert->close();
        }
        
        // If we got here, commit the transaction
        $conn->commit();
        header("Location: index.php");
        exit();
        
    } catch (Exception $e) {
        // Something went wrong, rollback the transaction
        $conn->rollback();
        $error = htmlspecialchars($e->getMessage());
        echo "Gagal memperbarui resep: " . $error . "<br><a href=\"edit.php?id=" . intval($recipe_id) . "\">Kembali</a>";
        exit();
    } finally {
        $conn->close();
    }
    
} else {
    // Jika file diakses langsung tanpa POST, arahkan ke index
    header("Location: index.php");
    exit();
}
?>