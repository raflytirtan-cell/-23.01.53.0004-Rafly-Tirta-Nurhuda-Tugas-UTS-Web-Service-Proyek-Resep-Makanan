-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 07 Nov 2025 pada 05.29
-- Versi server: 10.4.32-MariaDB
-- Versi PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_resep`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `categories`
--

CREATE TABLE `categories` (
  `category_id` int(11) NOT NULL,
  `nama_kategori` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `categories`
--

INSERT INTO `categories` (`category_id`, `nama_kategori`) VALUES
(2, 'Appetizer'),
(6, 'Beverage'),
(3, 'Dessert'),
(1, 'Main Course'),
(7, 'Side Dish'),
(5, 'Snack'),
(4, 'Soup');

-- --------------------------------------------------------

--
-- Struktur dari tabel `comments`
--

CREATE TABLE `comments` (
  `comment_id` int(11) NOT NULL,
  `recipe_id` int(11) NOT NULL,
  `nama_pengunjung` varchar(100) NOT NULL,
  `isi_komentar` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `ingredients`
--

CREATE TABLE `ingredients` (
  `ingredient_id` int(11) NOT NULL,
  `nama_bahan` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `ingredients`
--

INSERT INTO `ingredients` (`ingredient_id`, `nama_bahan`) VALUES
(6, 'Bawang merah'),
(5, 'Bawang putih'),
(13, 'Bihun'),
(27, 'Biji selasih'),
(7, 'Cabe rawit'),
(25, 'Cincau'),
(3, 'Daging ayam'),
(8, 'Daging sapi'),
(16, 'Daun bawang'),
(10, 'Daun jeruk'),
(23, 'Gula pasir'),
(20, 'Kecap manis'),
(26, 'Kelapa muda'),
(19, 'Kerupuk'),
(14, 'Kol'),
(12, 'Lengkuas'),
(1, 'Nasi putih'),
(21, 'Pisang kepok'),
(9, 'Santan kental'),
(11, 'Serai'),
(28, 'Sirup merah'),
(29, 'Susu kental manis'),
(17, 'Tahu'),
(15, 'Tauge'),
(4, 'Telur'),
(18, 'Tempe'),
(22, 'Tepung terigu'),
(2, 'Udang'),
(24, 'Vanili');

-- --------------------------------------------------------

--
-- Struktur dari tabel `recipes`
--

CREATE TABLE `recipes` (
  `recipe_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `judul_resep` varchar(255) NOT NULL,
  `instruksi_memasak` text NOT NULL,
  `porsi` int(11) DEFAULT NULL,
  `lama_memasak_menit` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `recipes`
--

INSERT INTO `recipes` (`recipe_id`, `user_id`, `judul_resep`, `instruksi_memasak`, `porsi`, `lama_memasak_menit`, `created_at`) VALUES
(3, 2, 'Nasi Goreng Spesial', '1. Tumis bumbu halus hingga harum\n2. Masukkan telur, buat orak-arik\n3. Masukkan ayam dan udang\n4. Tambahkan nasi dan bumbu-bumbu\n5. Aduk rata hingga matang', 2, 20, '2025-11-07 04:05:23'),
(4, 2, 'Rendang Daging', '1. Haluskan bumbu rendang\n2. Tumis bumbu dengan serai dan daun jeruk\n3. Masukkan daging\n4. Tambahkan santan\n5. Masak hingga mengering', 4, 240, '2025-11-07 04:05:23'),
(5, 2, 'Soto Ayam', '1. Rebus ayam dengan bumbu soto\n2. Siapkan mangkuk dengan bihun dan sayuran\n3. Tuang kuah panas\n4. Taburi bawang goreng', 4, 60, '2025-11-07 04:05:23'),
(6, 2, 'Nasi Goreng Spesial', '1. Tumis bumbu halus hingga harum\n2. Masukkan telur, buat orak-arik\n3. Masukkan ayam dan udang\n4. Tambahkan nasi dan bumbu-bumbu\n5. Aduk rata hingga matang', 2, 20, '2025-11-07 04:11:25'),
(7, 2, 'Wagyu A5', 'terserah', 3, 30, '2025-11-07 04:26:00');

-- --------------------------------------------------------

--
-- Struktur dari tabel `recipe_categories`
--

CREATE TABLE `recipe_categories` (
  `recipe_id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `recipe_categories`
--

INSERT INTO `recipe_categories` (`recipe_id`, `category_id`) VALUES
(6, 1);

-- --------------------------------------------------------

--
-- Struktur dari tabel `recipe_ingredients`
--

CREATE TABLE `recipe_ingredients` (
  `recipe_ingredient_id` int(11) NOT NULL,
  `recipe_id` int(11) NOT NULL,
  `ingredient_id` int(11) NOT NULL,
  `unit_id` int(11) NOT NULL,
  `jumlah` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `recipe_ingredients`
--

INSERT INTO `recipe_ingredients` (`recipe_ingredient_id`, `recipe_id`, `ingredient_id`, `unit_id`, `jumlah`) VALUES
(4, 6, 6, 4, '4'),
(5, 6, 5, 4, '3'),
(6, 6, 7, 3, '3'),
(7, 6, 3, 1, '100'),
(8, 6, 1, 5, '2'),
(9, 6, 4, 8, '2'),
(10, 6, 2, 1, '100'),
(11, 7, 8, 3, '3'),
(12, 7, 19, 3, '5');

-- --------------------------------------------------------

--
-- Struktur dari tabel `recipe_tags`
--

CREATE TABLE `recipe_tags` (
  `recipe_id` int(11) NOT NULL,
  `tag_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `recipe_tags`
--

INSERT INTO `recipe_tags` (`recipe_id`, `tag_id`) VALUES
(6, 1);

-- --------------------------------------------------------

--
-- Struktur dari tabel `tags`
--

CREATE TABLE `tags` (
  `tag_id` int(11) NOT NULL,
  `nama_tag` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `tags`
--

INSERT INTO `tags` (`tag_id`, `nama_tag`) VALUES
(6, 'Modern'),
(1, 'Mudah'),
(4, 'Populer'),
(2, 'Sedang'),
(3, 'Sulit'),
(5, 'Tradisional');

-- --------------------------------------------------------

--
-- Struktur dari tabel `units`
--

CREATE TABLE `units` (
  `unit_id` int(11) NOT NULL,
  `nama_satuan` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `units`
--

INSERT INTO `units` (`unit_id`, `nama_satuan`) VALUES
(3, 'buah'),
(8, 'butir'),
(1, 'gram'),
(9, 'ikat'),
(7, 'lembar'),
(6, 'ml'),
(10, 'papan'),
(5, 'piring'),
(11, 'sendok makan'),
(12, 'sendok teh'),
(4, 'siung');

-- --------------------------------------------------------

--
-- Struktur dari tabel `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `nama_lengkap` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `users`
--

INSERT INTO `users` (`user_id`, `username`, `password`, `password_hash`, `nama_lengkap`) VALUES
(2, 'admin', '$2y$10$vK1z6MT8MRCfLBr83QUbQe6tIuaKyuVRIgF53M1fMMwLP.Hsi8LcO', '', NULL);

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`category_id`),
  ADD UNIQUE KEY `nama_kategori` (`nama_kategori`);

--
-- Indeks untuk tabel `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`comment_id`),
  ADD KEY `recipe_id` (`recipe_id`);

--
-- Indeks untuk tabel `ingredients`
--
ALTER TABLE `ingredients`
  ADD PRIMARY KEY (`ingredient_id`),
  ADD UNIQUE KEY `nama_bahan` (`nama_bahan`);

--
-- Indeks untuk tabel `recipes`
--
ALTER TABLE `recipes`
  ADD PRIMARY KEY (`recipe_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indeks untuk tabel `recipe_categories`
--
ALTER TABLE `recipe_categories`
  ADD PRIMARY KEY (`recipe_id`,`category_id`),
  ADD KEY `category_id` (`category_id`);

--
-- Indeks untuk tabel `recipe_ingredients`
--
ALTER TABLE `recipe_ingredients`
  ADD PRIMARY KEY (`recipe_ingredient_id`),
  ADD KEY `recipe_id` (`recipe_id`),
  ADD KEY `ingredient_id` (`ingredient_id`),
  ADD KEY `unit_id` (`unit_id`);

--
-- Indeks untuk tabel `recipe_tags`
--
ALTER TABLE `recipe_tags`
  ADD PRIMARY KEY (`recipe_id`,`tag_id`),
  ADD KEY `tag_id` (`tag_id`);

--
-- Indeks untuk tabel `tags`
--
ALTER TABLE `tags`
  ADD PRIMARY KEY (`tag_id`),
  ADD UNIQUE KEY `nama_tag` (`nama_tag`);

--
-- Indeks untuk tabel `units`
--
ALTER TABLE `units`
  ADD PRIMARY KEY (`unit_id`),
  ADD UNIQUE KEY `nama_satuan` (`nama_satuan`);

--
-- Indeks untuk tabel `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `categories`
--
ALTER TABLE `categories`
  MODIFY `category_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT untuk tabel `comments`
--
ALTER TABLE `comments`
  MODIFY `comment_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `ingredients`
--
ALTER TABLE `ingredients`
  MODIFY `ingredient_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=59;

--
-- AUTO_INCREMENT untuk tabel `recipes`
--
ALTER TABLE `recipes`
  MODIFY `recipe_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT untuk tabel `recipe_ingredients`
--
ALTER TABLE `recipe_ingredients`
  MODIFY `recipe_ingredient_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT untuk tabel `tags`
--
ALTER TABLE `tags`
  MODIFY `tag_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT untuk tabel `units`
--
ALTER TABLE `units`
  MODIFY `unit_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT untuk tabel `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `comments`
--
ALTER TABLE `comments`
  ADD CONSTRAINT `comments_ibfk_1` FOREIGN KEY (`recipe_id`) REFERENCES `recipes` (`recipe_id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `recipes`
--
ALTER TABLE `recipes`
  ADD CONSTRAINT `recipes_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `recipe_categories`
--
ALTER TABLE `recipe_categories`
  ADD CONSTRAINT `recipe_categories_ibfk_1` FOREIGN KEY (`recipe_id`) REFERENCES `recipes` (`recipe_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `recipe_categories_ibfk_2` FOREIGN KEY (`category_id`) REFERENCES `categories` (`category_id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `recipe_ingredients`
--
ALTER TABLE `recipe_ingredients`
  ADD CONSTRAINT `recipe_ingredients_ibfk_1` FOREIGN KEY (`recipe_id`) REFERENCES `recipes` (`recipe_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `recipe_ingredients_ibfk_2` FOREIGN KEY (`ingredient_id`) REFERENCES `ingredients` (`ingredient_id`),
  ADD CONSTRAINT `recipe_ingredients_ibfk_3` FOREIGN KEY (`unit_id`) REFERENCES `units` (`unit_id`);

--
-- Ketidakleluasaan untuk tabel `recipe_tags`
--
ALTER TABLE `recipe_tags`
  ADD CONSTRAINT `recipe_tags_ibfk_1` FOREIGN KEY (`recipe_id`) REFERENCES `recipes` (`recipe_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `recipe_tags_ibfk_2` FOREIGN KEY (`tag_id`) REFERENCES `tags` (`tag_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
