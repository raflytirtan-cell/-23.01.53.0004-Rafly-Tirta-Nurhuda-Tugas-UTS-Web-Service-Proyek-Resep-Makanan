<?php
include 'koneksi.php';

// Fetch ingredients and units for dropdowns
$sql_ingredients = "SELECT * FROM ingredients ORDER BY nama_bahan";
$all_ingredients = $conn->query($sql_ingredients);

$sql_units = "SELECT * FROM units ORDER BY nama_satuan";
$all_units = $conn->query($sql_units);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Resep Baru</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<div class="container my-4 p-4 bg-white rounded shadow-sm">
    <h1 class="display-5">Formulir Tambah Resep Baru</h1>
    <a href="index.php">&laquo; Kembali ke Daftar Resep</a>
    <hr class="my-4">

    <form action="proses_tambah.php" method="POST">
        <div class="mb-3">
            <label for="judul_resep" class="form-label">Judul Resep:</label>
            <input type="text" id="judul_resep" name="judul_resep" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="porsi" class="form-label">Porsi (orang):</label>
            <input type="number" id="porsi" name="porsi" class="form-control" value="1">
        </div>
        <div class="mb-3">
            <label for="lama_memasak" class="form-label">Lama Memasak (menit):</label>
            <input type="number" id="lama_memasak" name="lama_memasak_menit" class="form-control" value="30">
        </div>
        <div class="mb-3">
            <label for="instruksi_memasak" class="form-label">Instruksi Memasak:</label>
            <textarea id="instruksi_memasak" name="instruksi_memasak" rows="8" class="form-control" required></textarea>
        </div>

        <hr>
        <h3>Bahan-Bahan</h3>

        <div class="mb-4">
            <div id="ingredients-container">
                <div class="row mb-2 ingredient-row">
                    <div class="col-md-5">
                        <select name="ingredients[]" class="form-select" required>
                            <option value="">Pilih Bahan</option>
                            <?php if ($all_ingredients): while($ing = $all_ingredients->fetch_assoc()): ?>
                                <option value="<?php echo $ing['ingredient_id']; ?>"><?php echo htmlspecialchars($ing['nama_bahan']); ?></option>
                            <?php endwhile; endif; ?>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <input type="text" name="quantities[]" class="form-control" placeholder="Jumlah" required>
                    </div>
                    <div class="col-md-3">
                        <select name="units[]" class="form-select" required>
                            <option value="">Pilih Satuan</option>
                            <?php if ($all_units): while($u = $all_units->fetch_assoc()): ?>
                                <option value="<?php echo $u['unit_id']; ?>"><?php echo htmlspecialchars($u['nama_satuan']); ?></option>
                            <?php endwhile; endif; ?>
                        </select>
                    </div>
                    <div class="col-md-1">
                        <button type="button" class="btn btn-danger btn-sm remove-ingredient">×</button>
                    </div>
                </div>
            </div>
            <button type="button" class="btn btn-success btn-sm mt-2" id="add-ingredient">+ Tambah Bahan</button>
        </div>

        <div class="mt-3">
            <button type="submit" class="btn btn-primary btn-lg">Simpan Resep</button>
            <a href="index.php" class="btn btn-secondary">Batal</a>
        </div>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const container = document.getElementById('ingredients-container');
    const addButton = document.getElementById('add-ingredient');

    // Use a template string that will be filled by cloning
    function getIngredientRowTemplate() {
        const row = document.createElement('div');
        row.className = 'row mb-2 ingredient-row';
        row.innerHTML = `
            <div class="col-md-5">
                <select name="ingredients[]" class="form-select" required>
                    <option value="">Pilih Bahan</option>
                    <?php // Re-query ingredients for template rendering
                    $tmp_ing = $conn->query($sql_ingredients);
                    if ($tmp_ing) while($ti = $tmp_ing->fetch_assoc()): ?>
                        <option value="<?php echo $ti['ingredient_id']; ?>"><?php echo htmlspecialchars($ti['nama_bahan']); ?></option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div class="col-md-3">
                <input type="text" name="quantities[]" class="form-control" placeholder="Jumlah" required>
            </div>
            <div class="col-md-3">
                <select name="units[]" class="form-select" required>
                    <option value="">Pilih Satuan</option>
                    <?php $tmp_u = $conn->query($sql_units); if ($tmp_u) while($tu = $tmp_u->fetch_assoc()): ?>
                        <option value="<?php echo $tu['unit_id']; ?>"><?php echo htmlspecialchars($tu['nama_satuan']); ?></option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div class="col-md-1">
                <button type="button" class="btn btn-danger btn-sm remove-ingredient">×</button>
            </div>
        `;
        return row;
    }

    addButton.addEventListener('click', function() {
        container.appendChild(getIngredientRowTemplate());
    });

    container.addEventListener('click', function(e) {
        if (e.target.classList.contains('remove-ingredient')) {
            const rows = container.getElementsByClassName('ingredient-row');
            if (rows.length > 1) {
                e.target.closest('.ingredient-row').remove();
            }
        }
    });
});
</script>
</body>
</html>
