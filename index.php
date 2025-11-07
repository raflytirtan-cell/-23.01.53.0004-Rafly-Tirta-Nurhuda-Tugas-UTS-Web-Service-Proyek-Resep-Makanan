<?php
session_start();
include 'koneksi.php';

// Ambil daftar resep (gabungkan user jika ada)
$sql = "SELECT r.recipe_id, r.judul_resep, r.porsi, r.lama_memasak_menit, IFNULL(u.username, 'Anon') AS author
        FROM recipes r
        LEFT JOIN users u ON r.user_id = u.user_id
        ORDER BY r.recipe_id DESC";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Resep - Web Resep Masakanku</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
    <style> /* kecilkan override lokal */
    .card-footer a { margin-right: .5rem; }
    </style>
</head>
<body>

<div class="container my-4 p-4 bg-white rounded shadow-sm">
    <!-- Header dengan Login/Logout -->
    <div class="d-flex justify-content-between align-items-start mb-4">
        <div>
            <h1 class="display-5">🍳 Web Resep Masakanku</h1>
            <p class="lead mb-0">Kumpulan resep sederhana untuk dicoba di rumah.</p>
        </div>
        <div class="text-end">
            <?php if (isset($_SESSION['username'])): ?>
                <p class="text-muted mb-2">Login sebagai: <?php echo htmlspecialchars($_SESSION['username']); ?></p>
                <a href="logout.php" class="btn btn-outline-danger">Logout</a>
            <?php else: ?>
                <a href="login.php" class="btn btn-outline-primary">Login Admin</a>
            <?php endif; ?>
        </div>
    </div>

    <!-- Tombol Tambah (hanya untuk admin) -->
    <?php if (isset($_SESSION['is_admin'])): ?>
        <a href="tambah.php" class="btn btn-primary btn-lg my-3">+ Tambah Resep Baru</a>
    <?php endif; ?>
    <hr>

    <h2>Daftar Resep</h2>
    <div class="row g-4 mt-3">
        <?php if ($result && $result->num_rows > 0): ?>
            <?php while ($row = $result->fetch_assoc()): ?>
                <div class="col-md-6">
                    <div class="card h-100 shadow-sm recipe-card">
                        <div class="card-body">
                            <h3 class="card-title">
                                <a href="detail.php?id=<?php echo $row['recipe_id']; ?>" class="text-decoration-none text-dark">
                                    <?php echo htmlspecialchars($row['judul_resep']); ?>
                                </a>
                            </h3>
                            <p class="card-text text-muted">
                                Porsi: <?php echo (int)$row['porsi']; ?> orang | 
                                Waktu: <?php echo (int)$row['lama_memasak_menit']; ?> menit
                            </p>
                            <p class="text-muted small">oleh <?php echo htmlspecialchars($row['author']); ?></p>
                        </div>
                        <div class="card-footer bg-white border-0 pb-3">
                            <?php if (isset($_SESSION['is_admin'])): ?>
                                <a href="edit.php?id=<?php echo $row['recipe_id']; ?>" class="btn btn-sm btn-warning">Edit</a>
                                <a href="hapus.php?id=<?php echo $row['recipe_id']; ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Yakin ingin menghapus resep ini?');">Hapus</a>
                            <?php endif; ?>
                            <a href="detail.php?id=<?php echo $row['recipe_id']; ?>" class="btn btn-sm btn-secondary">Detail</a>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <div class="col-12">
                <div class="alert alert-info">Belum ada resep. Silakan tambahkan resep baru.</div>
            </div>
        <?php endif; ?>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php $conn->close(); ?>
