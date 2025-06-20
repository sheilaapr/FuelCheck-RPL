-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jun 20, 2025 at 07:54 AM
-- Server version: 9.0.1
-- PHP Version: 8.1.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_fuelcheck`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin_verifikasi`
--

CREATE TABLE `admin_verifikasi` (
  `id` int NOT NULL,
  `admin_id` int NOT NULL,
  `laporan_id` int NOT NULL,
  `status_verifikasi` enum('valid','tidak valid') COLLATE utf8mb4_general_ci NOT NULL,
  `catatan` text COLLATE utf8mb4_general_ci,
  `verified_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `banding`
--

CREATE TABLE `banding` (
  `id` int NOT NULL,
  `user_id` int NOT NULL,
  `spbu_id` int NOT NULL,
  `laporan_id` int NOT NULL,
  `verifikasi_id` int NOT NULL,
  `isi_banding` text COLLATE utf8mb4_general_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `bukti_laporan`
--

CREATE TABLE `bukti_laporan` (
  `id` int NOT NULL,
  `laporan_id` int NOT NULL,
  `file_path` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `file_type` enum('image','video') COLLATE utf8mb4_general_ci NOT NULL,
  `uploaded_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `feedback`
--

CREATE TABLE `feedback` (
  `id` int NOT NULL,
  `user_id` int DEFAULT NULL,
  `admin_id` int DEFAULT NULL,
  `nama` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `pesan` text COLLATE utf8mb4_general_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `laporan`
--

CREATE TABLE `laporan` (
  `id` int NOT NULL,
  `user_id` int NOT NULL,
  `spbu_id` int NOT NULL,
  `transaksi_id` int NOT NULL,
  `deskripsi` text COLLATE utf8mb4_general_ci NOT NULL,
  `status` enum('pending','verifikasi','selesai') COLLATE utf8mb4_general_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `rating`
--

CREATE TABLE `rating` (
  `id` int NOT NULL,
  `user_id` int NOT NULL,
  `spbu_id` int NOT NULL,
  `nilai` int NOT NULL,
  `komentar` text COLLATE utf8mb4_general_ci,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `spbu`
--

CREATE TABLE `spbu` (
  `id` int NOT NULL,
  `nama_spbu` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `lokasi` varchar(255) COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ulasan`
--

CREATE TABLE `ulasan` (
  `id` int NOT NULL,
  `id_user` int DEFAULT NULL,
  `spbu` varchar(255) DEFAULT NULL,
  `rating` int DEFAULT NULL,
  `komentar` text,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int NOT NULL,
  `nama` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `role` enum('user','admin') COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `nama`, `email`, `password`, `role`) VALUES
(1, 'sheila', 'shei@gmail.com', '$2y$10$GwCcUFf81i8gOlKl5zf/j.AwhCNgEXT./WNWgd1Hyl5d2ck0xTNnW', 'user'),
(2, 'IBRANN', 'LALAA@gmail.com', '$2y$10$Aj/1ZVZ9xXYr4ax3PI8fneeex/jL/ByvJhXcYQbm6gqqePpaeticG', 'user'),
(3, 'sheila', 'shei2@gmail.com', '$2y$10$h1iqMRHTbeZljlyG87NYEuMTeITHS8Qo7K5ni0RIyNSIxzPxSzYOK', 'user'),
(4, 'nabila', 'nandaabintang@gmail.com', '$2y$10$vg4tf.mgf3I6Ixms6zM8COwRXOeiLDIT2XodFOZSbM6D9aeJKrAUy', 'user'),
(6, 'pakAgung', 'nndaaa@gmail.com', '$2y$10$PFYXbpluu/yN7zLQSc69kOxKKc.EVhOHhJwpzagKbjiutBkLIAGfe', 'admin'),
(7, 'Sheila Apriliani Putri', '230605110005@student.uin-malang.ac.id', '$2y$10$3X6iUGToyBd9XYev/IW4Pe75pNXh919WhjQJv0QZcwLBJj31oruhq', 'admin');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin_verifikasi`
--
ALTER TABLE `admin_verifikasi`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `laporan_id` (`laporan_id`),
  ADD KEY `admin_id` (`admin_id`);

--
-- Indexes for table `banding`
--
ALTER TABLE `banding`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `spbu_id` (`spbu_id`),
  ADD KEY `laporan_id` (`laporan_id`),
  ADD KEY `verifikasi_id` (`verifikasi_id`);

--
-- Indexes for table `bukti_laporan`
--
ALTER TABLE `bukti_laporan`
  ADD PRIMARY KEY (`id`),
  ADD KEY `laporan_id` (`laporan_id`);

--
-- Indexes for table `feedback`
--
ALTER TABLE `feedback`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `admin_id` (`admin_id`);

--
-- Indexes for table `laporan`
--
ALTER TABLE `laporan`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `spbu_id` (`spbu_id`);

--
-- Indexes for table `rating`
--
ALTER TABLE `rating`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `spbu_id` (`spbu_id`);

--
-- Indexes for table `spbu`
--
ALTER TABLE `spbu`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ulasan`
--
ALTER TABLE `ulasan`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin_verifikasi`
--
ALTER TABLE `admin_verifikasi`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `banding`
--
ALTER TABLE `banding`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `bukti_laporan`
--
ALTER TABLE `bukti_laporan`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `feedback`
--
ALTER TABLE `feedback`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `laporan`
--
ALTER TABLE `laporan`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `rating`
--
ALTER TABLE `rating`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `spbu`
--
ALTER TABLE `spbu`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `ulasan`
--
ALTER TABLE `ulasan`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `admin_verifikasi`
--
ALTER TABLE `admin_verifikasi`
  ADD CONSTRAINT `admin_verifikasi_ibfk_1` FOREIGN KEY (`admin_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `admin_verifikasi_ibfk_2` FOREIGN KEY (`laporan_id`) REFERENCES `laporan` (`id`);

--
-- Constraints for table `banding`
--
ALTER TABLE `banding`
  ADD CONSTRAINT `banding_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `banding_ibfk_2` FOREIGN KEY (`spbu_id`) REFERENCES `spbu` (`id`),
  ADD CONSTRAINT `banding_ibfk_3` FOREIGN KEY (`laporan_id`) REFERENCES `laporan` (`id`),
  ADD CONSTRAINT `banding_ibfk_4` FOREIGN KEY (`verifikasi_id`) REFERENCES `admin_verifikasi` (`id`);

--
-- Constraints for table `bukti_laporan`
--
ALTER TABLE `bukti_laporan`
  ADD CONSTRAINT `bukti_laporan_ibfk_1` FOREIGN KEY (`laporan_id`) REFERENCES `laporan` (`id`);

--
-- Constraints for table `feedback`
--
ALTER TABLE `feedback`
  ADD CONSTRAINT `feedback_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `feedback_ibfk_2` FOREIGN KEY (`admin_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `laporan`
--
ALTER TABLE `laporan`
  ADD CONSTRAINT `laporan_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `laporan_ibfk_2` FOREIGN KEY (`spbu_id`) REFERENCES `spbu` (`id`);

--
-- Constraints for table `rating`
--
ALTER TABLE `rating`
  ADD CONSTRAINT `rating_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `rating_ibfk_2` FOREIGN KEY (`spbu_id`) REFERENCES `spbu` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
