-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 07, 2024 at 02:30 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `maycha`
--

-- --------------------------------------------------------

--
-- Table structure for table `chitietgiohang`
--

CREATE TABLE `chitietgiohang` (
  `MaCTGH` int(6) NOT NULL,
  `MaSP` int(11) NOT NULL,
  `SoLuong` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `chitietgiohang`
--

INSERT INTO `chitietgiohang` (`MaCTGH`, `MaSP`, `SoLuong`) VALUES
(1, 1, 1),
(2, 2, 3);

-- --------------------------------------------------------

--
-- Table structure for table `danhmucsanpham`
--

CREATE TABLE `danhmucsanpham` (
  `MaDMSP` int(11) NOT NULL,
  `TenDMSP` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `danhmucsanpham`
--

INSERT INTO `danhmucsanpham` (`MaDMSP`, `TenDMSP`) VALUES
(1, 'Kem'),
(2, 'Trà Trái Cây'),
(3, 'Okinawa'),
(4, 'Thức uống đá xay'),
(5, 'Thức uống đặc biệt'),
(6, 'Trà sữa'),
(7, 'Trà nguyên chất'),
(8, 'Topping');

-- --------------------------------------------------------

--
-- Table structure for table `sanpham`
--

CREATE TABLE `sanpham` (
  `MaSP` int(11) NOT NULL,
  `MaDMSP` int(11) NOT NULL,
  `TenSanPham` varchar(100) NOT NULL,
  `DonGia` float NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `sanpham`
--

INSERT INTO `sanpham` (`MaSP`, `MaDMSP`, `TenSanPham`, `DonGia`) VALUES
(1, 1, 'Kem Trà Sữa', 30000),
(2, 1, 'Kem Trà Sữa & Trân Châu Đen', 35000),
(3, 2, 'Trà Alisan Trái Cây', 54000),
(4, 2, 'Đào Hồng Mận Hạt É', 52000),
(5, 2, 'Trà Oolong Vải', 52000),
(6, 2, 'Trà Đen Đào', 54000),
(7, 3, 'Trà Sữa Okinawa', 57000),
(8, 3, 'Sữa Tươi Okinawa', 51000),
(9, 3, 'Okinawa Latte', 57000),
(10, 3, 'Okinawa Oreo Cream Milk Tea', 57000),
(11, 4, 'Yakult Đào Đá Xay', 68000),
(12, 4, 'Strawberry Oreo Smoothie', 68000),
(13, 4, 'Khoai Môn Đá Xay', 68000),
(14, 4, 'Matcha Đá Xay', 68000),
(15, 5, 'Mango Sago', 55000),
(16, 5, 'Trà Bí Đao Gong Cha', 60000),
(17, 5, 'Trà Oolong Gong Cha', 56000),
(18, 5, 'Trà Đen Gong Cha', 48000),
(19, 6, 'Trà Sữa Đào', 50000),
(20, 6, 'Trà Sữa Xoài', 50000),
(21, 6, 'Trà Sữa Trà Đen', 45000),
(22, 6, 'Trà Sữa Oolong', 50000),
(23, 7, 'Trà Bí Đao', 35000),
(24, 7, 'Trà Alisan', 40000),
(25, 7, 'Trà Xanh', 40000),
(26, 7, 'Trà Đen', 35000),
(27, 8, 'Kem Sữa', 10000),
(28, 8, 'Nha Đam', 15000),
(29, 8, 'Đậu Đỏ', 5000),
(30, 8, 'Sương Sáo', 10000);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `chitietgiohang`
--
ALTER TABLE `chitietgiohang`
  ADD PRIMARY KEY (`MaCTGH`),
  ADD KEY `FK_MaSP` (`MaSP`);

--
-- Indexes for table `danhmucsanpham`
--
ALTER TABLE `danhmucsanpham`
  ADD PRIMARY KEY (`MaDMSP`);

--
-- Indexes for table `sanpham`
--
ALTER TABLE `sanpham`
  ADD PRIMARY KEY (`MaSP`),
  ADD KEY `FK_MaDMSP` (`MaDMSP`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `chitietgiohang`
--
ALTER TABLE `chitietgiohang`
  MODIFY `MaCTGH` int(6) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `danhmucsanpham`
--
ALTER TABLE `danhmucsanpham`
  MODIFY `MaDMSP` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `sanpham`
--
ALTER TABLE `sanpham`
  MODIFY `MaSP` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `chitietgiohang`
--
ALTER TABLE `chitietgiohang`
  ADD CONSTRAINT `FK_MaSP` FOREIGN KEY (`MaSP`) REFERENCES `sanpham` (`MaSP`);

--
-- Constraints for table `sanpham`
--
ALTER TABLE `sanpham`
  ADD CONSTRAINT `FK_MaDMSP` FOREIGN KEY (`MaDMSP`) REFERENCES `danhmucsanpham` (`MaDMSP`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
