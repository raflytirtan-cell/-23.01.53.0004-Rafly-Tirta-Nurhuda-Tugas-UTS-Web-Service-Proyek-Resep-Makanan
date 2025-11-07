<?php
include 'koneksi.php';

// Start transaction
$conn->begin_transaction();

try {
    // 1. Ambil data dari formulir
    $judul = $_POST['judul_resep'];
    $porsi = $_POST['porsi'];
    $lama_memasak = $_POST['lama_memasak_menit'];
    $instruksi = $_POST['instruksi_memasak'];
    $user_id = 2; // Using admin user_id

    // 2. Insert recipe
    $sql = "INSERT INTO recipes (user_id, judul_resep, instruksi_memasak, porsi, lama_memasak_menit) 
            VALUES (?, ?, ?, ?, ?)";
    
    $stmt = $conn->prepare($sql);
    if ($stmt === false) {
        throw new Exception("Error preparing recipe statement: " . $conn->error);
    }
    
    $stmt->bind_param("issii", $user_id, $judul, $instruksi, $porsi, $lama_memasak);
    
    if (!$stmt->execute()) {
        throw new Exception("Error inserting recipe: " . $stmt->error);
    }
    
    $recipe_id = $conn->insert_id;
    $stmt->close();
    
    // 3. Insert ingredients
    if (isset($_POST['ingredients']) && is_array($_POST['ingredients'])) {
        $sql_ingredient = "INSERT INTO recipe_ingredients (recipe_id, ingredient_id, unit_id, jumlah) 
                          VALUES (?, ?, ?, ?)";
        
        $stmt_ingredient = $conn->prepare($sql_ingredient);
        if ($stmt_ingredient === false) {
            throw new Exception("Error preparing ingredient statement: " . $conn->error);
        }
        
        foreach ($_POST['ingredients'] as $index => $ingredient_id) {
            if (empty($ingredient_id)) continue;
            
            $unit_id = $_POST['units'][$index];
            $quantity = $_POST['quantities'][$index];
            
            $stmt_ingredient->bind_param("iiis", $recipe_id, $ingredient_id, $unit_id, $quantity);
            if (!$stmt_ingredient->execute()) {
                throw new Exception("Error inserting ingredient: " . $stmt_ingredient->error);
            }
        }
        $stmt_ingredient->close();
    }
    
    // If we got here, commit the transaction
    $conn->commit();
    header("Location: index.php");
    exit();
    
} catch (Exception $e) {
    // Something went wrong, rollback changes
    $conn->rollback();
    $error = htmlspecialchars($e->getMessage());
    echo "Gagal menambahkan resep: " . $error . "<br><a href='tambah.php'>Kembali</a>";
    exit();
} finally {
    $conn->close();
}

?>