-- phpMyAdmin SQL Dump
-- version 5.0.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 21, 2022 at 04:10 PM
-- Server version: 10.4.17-MariaDB
-- PHP Version: 8.0.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `pcs_1178`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id` int(11) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL,
  `nama` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id`, `email`, `password`, `nama`) VALUES
(1, 'vickyirwanto2001@gmail.com', '$2y$10$ZExeE6j5bqwvevvNuKlAPe2U4VwjmLbBIUQW89.NIdcFf6vJq.ibO', 'Vicky Irwanto'),
(2, 'vickyirwanto2001@gmail.com', '$2y$10$Z10binBj9kSdTY1COXXsWeWm0KX/qCaxytkudosdh6sRNvoHx.Mw6', 'Vicky Irwanto'),
(3, 'vickyir300401@gamil.com', '$2y$10$I7UC9d41z4ueDs2DfR/lCePJNshHyh3Mb.lF2h/DQPrlALnbEt1nG', 'Vicky Irwanto'),
(4, 'reyhan0207@gmail.com', '$2y$10$2EdRjDcMk1eKtVRve28IEuOnDqpaaGZHqeXoaGeK7ENwBiX/RWL8C', 'Reyhan Al Ikhfan'),
(5, 'reyhan0207@gmail.com', '$2y$10$3yNIpWguZewsc3xps3DSZel9QWuvv/WNLY61Lk5iJYbYEdOLkM/3a', 'Reyhan Al Ikhfan'),
(6, 'reyhan0207@gmail.com', '$2y$10$qsh.AcKCF99lB2tzCBuzuOaodxuCuWQgcj6FkANclA25kWRxpbzsa', 'Reyhan Al Ikhfan'),
(10, 'brian.1242@students.amikom.ac.id', '$2y$10$SbKmsunFFTZfwdgBXdnpiOCEGTfx.MfR.KHUNogRUUqbD1s8vUlbq', 'Brian Dico '),
(11, 'sindy12@gmail.com', '$2y$10$Km5kPHnPgahwv.S24ZEHU.2q7Wh1MDUT1ar5WBipR08k07zwIMiy2', 'sindy'),
(12, 'dinda0101@gmail.com', '$2y$10$XgPJP/J8SjHagJkUQWVDtedIbu3YnNZ2yOCawp2TcNtogSn95mahG', 'Dinda');

-- --------------------------------------------------------

--
-- Table structure for table `item_transaksi`
--

CREATE TABLE `item_transaksi` (
  `id` int(11) NOT NULL,
  `transaksi_id` int(11) DEFAULT NULL,
  `product_id` int(11) DEFAULT NULL,
  `qty` int(11) DEFAULT NULL,
  `harga_saat_transaksi` int(11) DEFAULT NULL,
  `sub_total` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `item_transaksi`
--

INSERT INTO `item_transaksi` (`id`, `transaksi_id`, `product_id`, `qty`, `harga_saat_transaksi`, `sub_total`) VALUES
(3, 3, 4, 1, 300000, 0),
(4, 3, 4, 10, 300000, 0),
(5, 3, 4, 3, 300000, 0),
(6, 3, 4, 7, 300000, 0),
(13, 8, 4, 4, 100000, 400000),
(14, 9, 4, 1, 100000, 100000);

-- --------------------------------------------------------

--
-- Table structure for table `product`
--

CREATE TABLE `product` (
  `id` int(11) NOT NULL,
  `admin_id` int(11) DEFAULT NULL,
  `product_name` varchar(100) NOT NULL,
  `harga` int(11) NOT NULL,
  `stok` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `product`
--

INSERT INTO `product` (`id`, `admin_id`, `product_name`, `harga`, `stok`) VALUES
(4, 12, 'RAM 8gb 1', 100000, 1),
(6, 1, 'Nvidia GTX 1050', 1500000, 120),
(9, 12, 'Seagate SSD 120gb ', 265000, 10),
(12, 12, 'Samsung 850 EVO ', 2000000, 10),
(13, 12, 'Tes 2', 12000, 10),
(14, 12, 'produk baru 1', 13000, 12);

-- --------------------------------------------------------

--
-- Table structure for table `transaksi`
--

CREATE TABLE `transaksi` (
  `id` int(11) NOT NULL,
  `admin_id` int(11) DEFAULT NULL,
  `tanggal` datetime NOT NULL DEFAULT current_timestamp(),
  `total` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `transaksi`
--

INSERT INTO `transaksi` (`id`, `admin_id`, `tanggal`, `total`) VALUES
(3, 1, '2022-01-19 18:37:00', 100000),
(4, 1, '2022-01-19 18:37:00', 15000),
(5, 12, '2022-01-19 01:40:39', 2400000),
(6, 12, '2022-01-19 01:48:49', 3200000),
(7, 12, '2022-01-19 02:01:44', 2400000),
(8, 12, '2022-01-19 02:06:16', 400000),
(9, 12, '2022-01-20 05:08:25', 100000);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `item_transaksi`
--
ALTER TABLE `item_transaksi`
  ADD PRIMARY KEY (`id`),
  ADD KEY `item_transaksi_ibfk_1` (`transaksi_id`),
  ADD KEY `item_transaksi_ibfk_2` (`product_id`);

--
-- Indexes for table `product`
--
ALTER TABLE `product`
  ADD PRIMARY KEY (`id`),
  ADD KEY `admin_id` (`admin_id`);

--
-- Indexes for table `transaksi`
--
ALTER TABLE `transaksi`
  ADD PRIMARY KEY (`id`),
  ADD KEY `admin_id` (`admin_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `item_transaksi`
--
ALTER TABLE `item_transaksi`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `product`
--
ALTER TABLE `product`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `transaksi`
--
ALTER TABLE `transaksi`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `item_transaksi`
--
ALTER TABLE `item_transaksi`
  ADD CONSTRAINT `item_transaksi_ibfk_1` FOREIGN KEY (`transaksi_id`) REFERENCES `transaksi` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `item_transaksi_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `product` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `product`
--
ALTER TABLE `product`
  ADD CONSTRAINT `product_ibfk_1` FOREIGN KEY (`admin_id`) REFERENCES `admin` (`id`);

--
-- Constraints for table `transaksi`
--
ALTER TABLE `transaksi`
  ADD CONSTRAINT `transaksi_ibfk_1` FOREIGN KEY (`admin_id`) REFERENCES `admin` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
