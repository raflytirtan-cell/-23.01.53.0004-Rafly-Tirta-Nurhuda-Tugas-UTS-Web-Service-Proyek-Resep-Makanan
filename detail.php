<?php
include 'koneksi.php';

if (!isset($_GET['id']) || empty($_GET['id'])) {
    header('Location: index.php');
    exit();
}

$id = (int)$_GET['id'];
$sql = "SELECT r.*, IFNULL(u.username, 'Anon') AS author FROM recipes r LEFT JOIN users u ON r.user_id = u.user_id WHERE r.recipe_id = ? LIMIT 1";
$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $id);
$stmt->execute();
$res = $stmt->get_result();
$recipe = $res->fetch_assoc();
if (!$recipe) {
    header('Location: index.php');
    exit();
}

?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Resep - <?php echo htmlspecialchars($recipe['judul_resep']); ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<div class="container my-4 p-4 bg-white rounded shadow-sm">
    <a href="index.php">&laquo; Kembali ke Daftar Resep</a>
    <hr class="my-4">

    <h1 class="display-5"><?php echo htmlspecialchars($recipe['judul_resep']); ?></h1>
    <div class="p-3 my-3 bg-body-tertiary rounded recipe-meta">
        Porsi: <?php echo (int)$recipe['porsi']; ?> orang | Lama Memasak: <?php echo (int)$recipe['lama_memasak_menit']; ?> menit
        <div class="text-muted small">oleh <?php echo htmlspecialchars($recipe['author']); ?></div>
    </div>

    <h2 class="mt-4">Instruksi Memasak</h2>
    <div class="mt-3 instructions">
        <?php echo nl2br(htmlspecialchars($recipe['instruksi_memasak'])); ?>
    </div>

    <div class="mt-4">
        <a href="edit.php?id=<?php echo $recipe['recipe_id']; ?>" class="btn btn-warning">Edit</a>
        <a href="hapus.php?id=<?php echo $recipe['recipe_id']; ?>" class="btn btn-danger" onclick="return confirm('Yakin ingin menghapus resep ini?');">Hapus</a>
        <a href="index.php" class="btn btn-secondary">Kembali</a>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php $stmt->close(); $conn->close(); ?>
