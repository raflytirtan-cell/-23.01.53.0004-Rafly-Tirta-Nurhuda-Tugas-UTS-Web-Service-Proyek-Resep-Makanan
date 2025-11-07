<?php
include 'koneksi.php';

if (!isset($_GET['id']) || empty($_GET['id'])) {
    header('Location: index.php');
    exit();
}

$id = (int)$_GET['id'];

// Get recipe details
$sql = "SELECT * FROM recipes WHERE recipe_id = ? LIMIT 1";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$res = $stmt->get_result();
$recipe = $res->fetch_assoc();
if (!$recipe) {
    header('Location: index.php');
    exit();
}

// Get recipe ingredients
$sql_ingredients = "SELECT ri.*, i.nama_bahan, u.nama_satuan 
                   FROM recipe_ingredients ri
                   JOIN ingredients i ON ri.ingredient_id = i.ingredient_id
                   JOIN units u ON ri.unit_id = u.unit_id
                   WHERE ri.recipe_id = ?";
$stmt_ingredients = $conn->prepare($sql_ingredients);
$stmt_ingredients->bind_param("i", $id);
$stmt_ingredients->execute();
$ingredients = $stmt_ingredients->get_result()->fetch_all(MYSQLI_ASSOC);

// Get all available ingredients for dropdown
$sql_all_ingredients = "SELECT * FROM ingredients ORDER BY nama_bahan";
$all_ingredients = $conn->query($sql_all_ingredients)->fetch_all(MYSQLI_ASSOC);

// Get all available units for dropdown
$sql_all_units = "SELECT * FROM units ORDER BY nama_satuan";
$all_units = $conn->query($sql_all_units)->fetch_all(MYSQLI_ASSOC);

?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Resep</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<div class="container my-4 p-4 bg-white rounded shadow-sm">
    <h1 class="display-5">Formulir Edit Resep</h1>
    <a href="index.php">&laquo; Kembali ke Daftar Resep</a>
    <hr class="my-4">

    <form action="proses_edit.php" method="POST">
        <input type="hidden" name="recipe_id" value="<?php echo (int)$recipe['recipe_id']; ?>">
        <div class="mb-3">
            <label for="judul_resep" class="form-label">Judul Resep:</label>
            <input type="text" id="judul_resep" name="judul_resep" class="form-control" value="<?php echo htmlspecialchars($recipe['judul_resep']); ?>" required>
        </div>
        <div class="mb-3">
            <label for="porsi" class="form-label">Porsi (orang):</label>
            <input type="number" id="porsi" name="porsi" class="form-control" value="<?php echo (int)$recipe['porsi']; ?>">
        </div>
        <div class="mb-3">
            <label for="lama_memasak" class="form-label">Lama Memasak (menit):</label>
            <input type="number" id="lama_memasak" name="lama_memasak_menit" class="form-control" value="<?php echo (int)$recipe['lama_memasak_menit']; ?>">
        </div>
        <div class="mb-3">
            <label for="instruksi_memasak" class="form-label">Instruksi Memasak:</label>
            <textarea id="instruksi_memasak" name="instruksi_memasak" rows="8" class="form-control" required><?php echo htmlspecialchars($recipe['instruksi_memasak']); ?></textarea>
        </div>

        <div class="mb-4">
            <label class="form-label">Bahan-bahan:</label>
            <div id="ingredients-container">
                <?php foreach ($ingredients as $index => $ingredient): ?>
                <div class="row mb-2 ingredient-row">
                    <div class="col-md-5">
                        <select name="ingredients[]" class="form-select" required>
                            <option value="">Pilih Bahan</option>
                            <?php foreach ($all_ingredients as $ing): ?>
                                <option value="<?php echo $ing['ingredient_id']; ?>" 
                                    <?php echo ($ing['ingredient_id'] == $ingredient['ingredient_id']) ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($ing['nama_bahan']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <input type="text" name="quantities[]" class="form-control" 
                            value="<?php echo htmlspecialchars($ingredient['jumlah']); ?>" 
                            placeholder="Jumlah" required>
                    </div>
                    <div class="col-md-3">
                        <select name="units[]" class="form-select" required>
                            <option value="">Pilih Satuan</option>
                            <?php foreach ($all_units as $unit): ?>
                                <option value="<?php echo $unit['unit_id']; ?>"
                                    <?php echo ($unit['unit_id'] == $ingredient['unit_id']) ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($unit['nama_satuan']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-1">
                        <button type="button" class="btn btn-danger btn-sm remove-ingredient">×</button>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
            <button type="button" class="btn btn-success btn-sm mt-2" id="add-ingredient">+ Tambah Bahan</button>
        </div>

        <div class="mt-3">
            <button type="submit" class="btn btn-warning btn-lg">Simpan Perubahan</button>
            <a href="index.php" class="btn btn-secondary">Batal</a>
        </div>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const container = document.getElementById('ingredients-container');
    const addButton = document.getElementById('add-ingredient');

    // Template for new ingredient row
    function getIngredientRowTemplate() {
        const row = document.createElement('div');
        row.className = 'row mb-2 ingredient-row';
        row.innerHTML = `
            <div class="col-md-5">
                <select name="ingredients[]" class="form-select" required>
                    <option value="">Pilih Bahan</option>
                    <?php foreach ($all_ingredients as $ing): ?>
                        <option value="<?php echo $ing['ingredient_id']; ?>">
                            <?php echo htmlspecialchars($ing['nama_bahan']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-3">
                <input type="text" name="quantities[]" class="form-control" placeholder="Jumlah" required>
            </div>
            <div class="col-md-3">
                <select name="units[]" class="form-select" required>
                    <option value="">Pilih Satuan</option>
                    <?php foreach ($all_units as $unit): ?>
                        <option value="<?php echo $unit['unit_id']; ?>">
                            <?php echo htmlspecialchars($unit['nama_satuan']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-1">
                <button type="button" class="btn btn-danger btn-sm remove-ingredient">×</button>
            </div>
        `;
        return row;
    }

    // Add new ingredient row
    addButton.addEventListener('click', function() {
        container.appendChild(getIngredientRowTemplate());
    });

    // Remove ingredient row
    container.addEventListener('click', function(e) {
        if (e.target.classList.contains('remove-ingredient')) {
            e.target.closest('.ingredient-row').remove();
        }
    });

    // If no ingredients exist, add an empty row
    if (container.children.length === 0) {
        container.appendChild(getIngredientRowTemplate());
    }
});
</script>

</body>
</html>

<?php 
$stmt->close();
$stmt_ingredients->close();
$conn->close(); 
?>
