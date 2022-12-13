-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Dec 13, 2022 at 09:16 AM
-- Server version: 10.4.24-MariaDB
-- PHP Version: 7.4.29

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `tagihan`
--

-- --------------------------------------------------------

--
-- Table structure for table `jenis_voucher`
--

CREATE TABLE `jenis_voucher` (
  `id` int(11) NOT NULL,
  `voucher` varchar(255) NOT NULL,
  `harga_satuan` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `jenis_voucher`
--

INSERT INTO `jenis_voucher` (`id`, `voucher`, `harga_satuan`) VALUES
(6, '24 jam', 3000);

-- --------------------------------------------------------

--
-- Table structure for table `reseller`
--

CREATE TABLE `reseller` (
  `id` int(11) NOT NULL,
  `nama` varchar(255) NOT NULL,
  `flashnet` varchar(255) NOT NULL,
  `no_reff` varchar(11) NOT NULL,
  `email` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `reseller`
--

INSERT INTO `reseller` (`id`, `nama`, `flashnet`, `no_reff`, `email`) VALUES
(8, 'user', 'flashnet18', 'flashnet18', ''),
(9, 'user', 'flashnet17', 'flashnet17', '');

-- --------------------------------------------------------

--
-- Table structure for table `tagihan`
--

CREATE TABLE `tagihan` (
  `id` int(11) NOT NULL,
  `email` varchar(250) NOT NULL,
  `role_id` varchar(200) NOT NULL,
  `tgl_input` datetime NOT NULL,
  `tgl_transaksi` date NOT NULL,
  `nama` varchar(255) NOT NULL,
  `no_reff` varchar(11) NOT NULL,
  `kode_flash` varchar(255) NOT NULL,
  `jenis_voucher` varchar(255) NOT NULL,
  `stok_sebelumnya` int(11) NOT NULL,
  `stok_tambahan` int(11) NOT NULL,
  `stok_awal` int(11) NOT NULL,
  `stok_akhir` int(11) NOT NULL,
  `stok_terjual` int(11) NOT NULL,
  `jenis_saldo` enum('debit','kredit','','') NOT NULL,
  `total_bayar` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `tagihan_reseller`
--

CREATE TABLE `tagihan_reseller` (
  `id` int(11) NOT NULL,
  `tgl_input` datetime NOT NULL,
  `tgl_transaksi` date NOT NULL,
  `nama` varchar(255) NOT NULL,
  `no_reff` varchar(11) NOT NULL,
  `kode_flash` varchar(255) NOT NULL,
  `jenis_voucher` varchar(255) NOT NULL,
  `stok_sebelumnya` int(11) NOT NULL,
  `stok_tambahan` int(11) NOT NULL,
  `stok_awal` int(11) NOT NULL,
  `stok_akhir` int(11) NOT NULL,
  `stok_terjual` int(11) NOT NULL,
  `jenis_saldo` enum('debit','kredit','','') NOT NULL,
  `total_bayar` int(11) NOT NULL,
  `email_reseller` varchar(250) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `nama` varchar(255) NOT NULL,
  `no_reff` varchar(255) NOT NULL,
  `alamat` varchar(255) NOT NULL,
  `nomor` varchar(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role_id` varchar(200) NOT NULL,
  `is_active` varchar(200) NOT NULL,
  `date_created` int(11) NOT NULL,
  `total_bayar` varchar(158) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `nama`, `no_reff`, `alamat`, `nomor`, `email`, `password`, `role_id`, `is_active`, `date_created`, `total_bayar`) VALUES
(1, 'administrator', '', '', '', 'admin@gmail.com', '$2y$10$sBqPHRFZFQjPHZ91HjvG9eGM0EZ1EAII0JRa33RW1E/OODfp4meVy', 'Admin', 'aktif', 0, ''),
(4, 'user', 'flashnet18', '', '', 'user@gmail.com', '$2y$10$sBqPHRFZFQjPHZ91HjvG9eGM0EZ1EAII0JRa33RW1E/OODfp4meVy', 'Reseller', 'aktif', 0, '0');

-- --------------------------------------------------------

--
-- Table structure for table `user_access_menu`
--

CREATE TABLE `user_access_menu` (
  `id` int(11) NOT NULL,
  `role_id` int(11) NOT NULL,
  `menu_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `user_access_menu`
--

INSERT INTO `user_access_menu` (`id`, `role_id`, `menu_id`) VALUES
(1, 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `user_menu`
--

CREATE TABLE `user_menu` (
  `id` int(11) NOT NULL,
  `menu` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `user_menu`
--

INSERT INTO `user_menu` (`id`, `menu`) VALUES
(1, 'Administrator');

-- --------------------------------------------------------

--
-- Table structure for table `user_reseller`
--

CREATE TABLE `user_reseller` (
  `id` int(11) NOT NULL,
  `nama` varchar(255) NOT NULL,
  `alamat` varchar(255) NOT NULL,
  `nomor` varchar(200) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role_id` varchar(200) NOT NULL,
  `is_active` varchar(200) NOT NULL,
  `date_created` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `user_reseller`
--

INSERT INTO `user_reseller` (`id`, `nama`, `alamat`, `nomor`, `email`, `password`, `role_id`, `is_active`, `date_created`) VALUES
(1, 'nur syamsyah', 'Jl. Botuliodu', '085145787625', 'nursyamsyah@gmail.com', '$2y$10$RPbSWnAn2g5.RYEHklQHiuD86bH..p9ZXaf.8J7QZsiwGarceQuc.', 'Reseller', 'aktif', 1652060920);

-- --------------------------------------------------------

--
-- Table structure for table `user_sub_menu`
--

CREATE TABLE `user_sub_menu` (
  `id` int(11) NOT NULL,
  `menu_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `url` varchar(255) NOT NULL,
  `icon` varchar(255) NOT NULL,
  `is_active` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `user_sub_menu`
--

INSERT INTO `user_sub_menu` (`id`, `menu_id`, `title`, `url`, `icon`, `is_active`) VALUES
(1, 1, 'Dashboard', 'admin', 'ni ni-tv-2 text-primary text-sm opacity-10', 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `jenis_voucher`
--
ALTER TABLE `jenis_voucher`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `reseller`
--
ALTER TABLE `reseller`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tagihan`
--
ALTER TABLE `tagihan`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tagihan_reseller`
--
ALTER TABLE `tagihan_reseller`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user_access_menu`
--
ALTER TABLE `user_access_menu`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user_menu`
--
ALTER TABLE `user_menu`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user_reseller`
--
ALTER TABLE `user_reseller`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user_sub_menu`
--
ALTER TABLE `user_sub_menu`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `jenis_voucher`
--
ALTER TABLE `jenis_voucher`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `reseller`
--
ALTER TABLE `reseller`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `tagihan`
--
ALTER TABLE `tagihan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `tagihan_reseller`
--
ALTER TABLE `tagihan_reseller`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `user_access_menu`
--
ALTER TABLE `user_access_menu`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `user_menu`
--
ALTER TABLE `user_menu`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `user_reseller`
--
ALTER TABLE `user_reseller`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `user_sub_menu`
--
ALTER TABLE `user_sub_menu`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
