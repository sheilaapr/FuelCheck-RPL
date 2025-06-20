-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 20, 2025 at 02:22 PM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

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
  `id` int(11) NOT NULL,
  `admin_id` int(11) NOT NULL,
  `laporan_id` int(11) NOT NULL,
  `status_verifikasi` enum('valid','tidak valid') NOT NULL,
  `catatan` text DEFAULT NULL,
  `verified_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `banding`
--

CREATE TABLE `banding` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `spbu_id` int(11) NOT NULL,
  `laporan_id` int(11) NOT NULL,
  `verifikasi_id` int(11) NOT NULL,
  `isi_banding` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `bukti_laporan`
--

CREATE TABLE `bukti_laporan` (
  `id` int(11) NOT NULL,
  `laporan_id` int(11) NOT NULL,
  `file_path` varchar(255) NOT NULL,
  `file_type` enum('image','video') NOT NULL,
  `uploaded_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `feedback`
--

CREATE TABLE `feedback` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `admin_id` int(11) DEFAULT NULL,
  `nama` varchar(255) DEFAULT NULL,
  `pesan` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `laporan`
--

CREATE TABLE `laporan` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `spbu_id` int(11) NOT NULL,
  `transaksi_id` int(11) NOT NULL,
  `deskripsi` text NOT NULL,
  `status` enum('pending','verifikasi','selesai') NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `rating`
--

CREATE TABLE `rating` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `spbu_id` int(11) NOT NULL,
  `nilai` int(11) NOT NULL,
  `komentar` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `spbu`
--

CREATE TABLE `spbu` (
  `id` int(11) NOT NULL,
  `nama_spbu` varchar(255) NOT NULL,
  `lokasi` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `nama` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('user','admin') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `banding`
--
ALTER TABLE `banding`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `bukti_laporan`
--
ALTER TABLE `bukti_laporan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `feedback`
--
ALTER TABLE `feedback`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `laporan`
--
ALTER TABLE `laporan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `rating`
--
ALTER TABLE `rating`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `spbu`
--
ALTER TABLE `spbu`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

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
