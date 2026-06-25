-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jun 23, 2026 at 05:11 PM
-- Server version: 9.2.0
-- PHP Version: 8.1.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_padi`
--

-- --------------------------------------------------------

--
-- Table structure for table `diagnosa`
--

CREATE TABLE `diagnosa` (
  `kode_diagnosa` varchar(10) NOT NULL,
  `nama_diagnosa` varchar(255) NOT NULL,
  `tipe` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `diagnosa`
--

INSERT INTO `diagnosa` (`kode_diagnosa`, `nama_diagnosa`, `tipe`) VALUES
('H01', 'Hama Tikus', 'diagnosis_hama'),
('H02', 'Hama Wereng Coklat', 'diagnosis_hama'),
('H03', 'Hama Sundep', 'diagnosis_hama'),
('H04', 'Hama Beluk', 'diagnosis_hama'),
('H05', 'Hama Keong Mas', 'diagnosis_hama'),
('H06', 'Hama Ulat Daun', 'diagnosis_hama'),
('H07', 'Hama Walang Sangit', 'diagnosis_hama'),
('H08', 'Hama Ganjur', 'diagnosis_hama'),
('P01', 'Penyakit Blast Daun', 'diagnosis_penyakit'),
('P02', 'Penyakit Blast Leher', 'diagnosis_penyakit'),
('P03', 'Penyakit Tungro', 'diagnosis_penyakit'),
('P04', 'Hawar Daun Bakteri (HDB)', 'diagnosis_penyakit'),
('P05', 'Penyakit Bercak Coklat', 'diagnosis_penyakit'),
('P06', 'HDB Fase Lanjut', 'diagnosis_penyakit'),
('P07', 'Gangguan Fisiologis', 'gangguan_fisiologis'),
('S01', 'Serangan Wereng Berat', 'severity');

-- --------------------------------------------------------

--
-- Table structure for table `gejala`
--

CREATE TABLE `gejala` (
  `kode_gejala` varchar(10) NOT NULL,
  `nama_gejala` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `gejala`
--

INSERT INTO `gejala` (`kode_gejala`, `nama_gejala`) VALUES
('G01', 'Bercak belah ketupat di daun'),
('G02', 'Tepian kuning'),
('G03', 'Tengah abu-abu/coklat keputihan'),
('G04', 'Pangkal leher malai membusuk/coklat'),
('G05', 'Malai patah atau terkulai'),
('G06', 'Bulir hampa'),
('G07', 'Batang terpotong'),
('G08', 'Bekas gigitan di pangkal batang'),
('G09', 'Kerusakan menyebar dalam petak'),
('G10', 'Serangga kecil coklat di pangkal batang'),
('G11', 'Tanaman tampak terbakar'),
('G12', 'Tanaman kerdil'),
('G13', 'Daun jingga/kuning dari ujung ke bawah'),
('G14', 'Pucuk batang layu/kering'),
('G15', 'Pucuk mudah dicabut'),
('G16', 'Fase vegetatif'),
('G17', 'Malai putih/kering'),
('G18', 'Fase generatif'),
('G19', 'Tanaman muda dimakan'),
('G20', 'Telur merah jambu'),
('G21', 'Sawah tergenang'),
('G22', 'Gejala mulai dari tepi daun'),
('G23', 'Menyebar ke ujung/pangkal'),
('G24', 'Daun hawar/kering'),
('G25', 'Daun menggulung/melipat'),
('G26', 'Terdapat ulat/larva kecil'),
('G27', 'Serangga berbau menyengat pada malai'),
('G28', 'Bercak coklat bulat/oval'),
('G29', 'Kekurangan unsur hara N atau K'),
('G30', 'Tunas berbentuk pentil/daun bawang'),
('G31', 'Populasi wereng sangat tinggi'),
('G32', 'Terdapat eksudat putih kekuningan'),
('G33', 'Tanaman tumbuh normal');

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int UNSIGNED NOT NULL,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1);

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `rules`
--

CREATE TABLE `rules` (
  `kode_rule` varchar(10) NOT NULL,
  `kode_diagnosa` varchar(10) NOT NULL,
  `cf_rule` decimal(4,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `rules`
--

INSERT INTO `rules` (`kode_rule`, `kode_diagnosa`, `cf_rule`) VALUES
('R01', 'P01', '0.95'),
('R02', 'P02', '0.85'),
('R03', 'H01', '0.80'),
('R04', 'H02', '0.95'),
('R05', 'P03', '0.75'),
('R06', 'H03', '0.80'),
('R07', 'H04', '0.85'),
('R08', 'H05', '0.85'),
('R09', 'P04', '0.80'),
('R10', 'H06', '0.75'),
('R11', 'H07', '0.75'),
('R12', 'P05', '0.70'),
('R13', 'H08', '0.75'),
('R14', 'S01', '0.90'),
('R15', 'P06', '0.80'),
('R16', 'P07', '0.60');

-- --------------------------------------------------------

--
-- Table structure for table `rule_detail`
--

CREATE TABLE `rule_detail` (
  `id` int NOT NULL,
  `kode_rule` varchar(10) NOT NULL,
  `kode_gejala` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `rule_detail`
--

INSERT INTO `rule_detail` (`id`, `kode_rule`, `kode_gejala`) VALUES
(1, 'R01', 'G01'),
(2, 'R01', 'G02'),
(3, 'R01', 'G03'),
(4, 'R02', 'G04'),
(5, 'R02', 'G05'),
(6, 'R02', 'G06'),
(7, 'R03', 'G07'),
(8, 'R03', 'G08'),
(9, 'R03', 'G09'),
(10, 'R04', 'G10'),
(11, 'R04', 'G11'),
(12, 'R05', 'G12'),
(13, 'R05', 'G13'),
(14, 'R06', 'G14'),
(15, 'R06', 'G15'),
(16, 'R06', 'G16'),
(17, 'R07', 'G17'),
(18, 'R07', 'G06'),
(19, 'R07', 'G18'),
(20, 'R08', 'G19'),
(21, 'R08', 'G20'),
(22, 'R08', 'G21'),
(23, 'R09', 'G22'),
(24, 'R09', 'G23'),
(25, 'R09', 'G24'),
(26, 'R10', 'G25'),
(27, 'R10', 'G26'),
(28, 'R11', 'G06'),
(29, 'R11', 'G27'),
(30, 'R12', 'G28'),
(31, 'R12', 'G29'),
(32, 'R13', 'G30'),
(33, 'R14', 'G11'),
(34, 'R14', 'G31'),
(35, 'R15', 'G24'),
(36, 'R15', 'G32'),
(37, 'R16', 'G13'),
(38, 'R16', 'G33');

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `diagnosa`
--
ALTER TABLE `diagnosa`
  ADD PRIMARY KEY (`kode_diagnosa`);

--
-- Indexes for table `gejala`
--
ALTER TABLE `gejala`
  ADD PRIMARY KEY (`kode_gejala`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `rules`
--
ALTER TABLE `rules`
  ADD PRIMARY KEY (`kode_rule`),
  ADD KEY `kode_diagnosa` (`kode_diagnosa`);

--
-- Indexes for table `rule_detail`
--
ALTER TABLE `rule_detail`
  ADD PRIMARY KEY (`id`),
  ADD KEY `kode_rule` (`kode_rule`),
  ADD KEY `kode_gejala` (`kode_gejala`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `rule_detail`
--
ALTER TABLE `rule_detail`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `rules`
--
ALTER TABLE `rules`
  ADD CONSTRAINT `rules_ibfk_1` FOREIGN KEY (`kode_diagnosa`) REFERENCES `diagnosa` (`kode_diagnosa`);

--
-- Constraints for table `rule_detail`
--
ALTER TABLE `rule_detail`
  ADD CONSTRAINT `rule_detail_ibfk_1` FOREIGN KEY (`kode_rule`) REFERENCES `rules` (`kode_rule`),
  ADD CONSTRAINT `rule_detail_ibfk_2` FOREIGN KEY (`kode_gejala`) REFERENCES `gejala` (`kode_gejala`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

DESCRIBE users;