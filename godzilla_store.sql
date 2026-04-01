-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 28, 2026 at 01:34 AM
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
-- Database: `godzilla_store`
--

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `id` int(11) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`id`, `email`, `password`) VALUES
(1, 'admin@godzilla.com', '$2y$10$tXhn47/o9fH7ETUAQBOte.7esL1KGkjDPea91AZ66oRh/B4YWWlHi');

-- --------------------------------------------------------

--
-- Table structure for table `coupons`
--

CREATE TABLE `coupons` (
  `id` int(11) NOT NULL,
  `code` varchar(50) NOT NULL,
  `type` enum('percentage','fixed') NOT NULL,
  `value` decimal(10,2) NOT NULL,
  `expiry_date` date NOT NULL,
  `status` enum('active','inactive') DEFAULT 'active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `customer_name` varchar(255) DEFAULT NULL,
  `customer_phone` varchar(50) DEFAULT NULL,
  `customer_address` text DEFAULT NULL,
  `total_price` decimal(10,2) DEFAULT NULL,
  `payment_method` varchar(50) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` varchar(20) NOT NULL DEFAULT 'Pending',
  `payment_status` varchar(20) DEFAULT 'Pending',
  `transaction_id` varchar(100) DEFAULT NULL,
  `coupon_code` varchar(50) DEFAULT NULL,
  `discount` decimal(10,2) DEFAULT 0.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `customer_name`, `customer_phone`, `customer_address`, `total_price`, `payment_method`, `created_at`, `status`, `payment_status`, `transaction_id`, `coupon_code`, `discount`) VALUES
(44, 'ENG•ZooZ', '01033748811', 'Cairo University Road, Oula, Al Giza, Egypt', 7800.00, 'cod', '2026-03-26 23:07:10', 'Pending', 'Pending', NULL, NULL, 0.00),
(45, 'CAP•ZooZ', '01033748812', 'Cairo University Road, Oula, Al Giza, Egypt', 10800.00, 'cod', '2026-03-26 23:14:49', 'Processing', 'Pending', NULL, NULL, 0.00),
(46, 'Mohamed', '01099657196', 'Cairo University Road, Oula, Al Giza, Egypt', 5550.00, 'cod', '2026-03-26 23:15:33', 'Shipped', 'Pending', NULL, NULL, 0.00),
(47, 'Khaled', '01068001415', 'Cairo University Road, Oula, Al Giza, Egypt', 3000.00, 'cod', '2026-03-26 23:16:39', 'Completed', 'Pending', NULL, NULL, 0.00),
(48, 'Abdelrahman', '01014684495', 'Cairo University Road, Oula, Al Giza, Egypt', 7050.00, 'cod', '2026-03-26 23:18:03', 'Cancelled', 'Pending', NULL, NULL, 0.00),
(49, 'zyad sobhy', '01033748811', 'Cairo University Road, Oula, Al Giza, Egypt', 6250.00, 'cod', '2026-03-27 23:58:01', 'Pending', 'Pending', NULL, NULL, 0.00);

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `id` int(11) NOT NULL,
  `order_id` int(11) DEFAULT NULL,
  `product_id` int(11) DEFAULT NULL,
  `quantity` int(11) DEFAULT NULL,
  `price` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`id`, `order_id`, `product_id`, `quantity`, `price`) VALUES
(1, 4, 5, 6, NULL),
(2, 4, 4, 3, NULL),
(3, 5, 5, 2, NULL),
(4, 5, 4, 3, NULL),
(5, 6, 5, 2, NULL),
(8, 8, 5, 3, NULL),
(9, 9, 5, 3, NULL),
(10, 10, 5, 2, NULL),
(11, 10, 4, 2, NULL),
(12, 11, 5, 2, NULL),
(15, 13, 4, 2, NULL),
(16, 14, 5, 2, NULL),
(17, 15, 4, 2, NULL),
(18, 16, 4, 1, NULL),
(19, 17, 5, 1, NULL),
(20, 18, 5, 1, NULL),
(21, 19, 5, 1, NULL),
(22, 20, 4, 2, NULL),
(23, 21, 5, 1, NULL),
(24, 21, 4, 1, NULL),
(25, 22, 7, 1, NULL),
(26, 22, 4, 3, NULL),
(27, 23, 4, 7, NULL),
(28, 24, 7, 5, 1800.00),
(29, 25, 7, 1, 1800.00),
(30, 25, 5, 1, 750.00),
(31, 26, 5, 1, 750.00),
(32, 26, 4, 1, 1500.00),
(33, 27, 7, 1, 1800.00),
(34, 27, 5, 1, 750.00),
(35, 28, 7, 1, 1800.00),
(36, 28, 5, 1, 750.00),
(37, 29, 4, 1, 1500.00),
(38, 30, 5, 1, 750.00),
(39, 31, 4, 7, 1500.00),
(40, 32, 5, 1, 750.00),
(41, 32, 4, 1, 1500.00),
(42, 33, 5, 1, 750.00),
(43, 33, 4, 1, 1500.00),
(44, 34, 5, 1, 750.00),
(45, 34, 4, 1, 1500.00),
(46, 34, 1, 1, 1500.00),
(47, 35, 4, 1, 1500.00),
(48, 36, 5, 1, 750.00),
(49, 36, 4, 1, 1500.00),
(50, 37, 5, 1, 750.00),
(51, 37, 4, 1, 1500.00),
(52, 38, 4, 1, 1500.00),
(53, 38, 5, 4, 750.00),
(54, 39, 4, 2, 1500.00),
(55, 39, 5, 1, 750.00),
(56, 40, 4, 3, 1500.00),
(57, 40, 5, 1, 750.00),
(58, 40, 7, 6, 1800.00),
(59, 41, 5, 5, 750.00),
(60, 41, 4, 1, 1500.00),
(61, 42, 4, 1, 1500.00),
(62, 43, 4, 3, 1500.00),
(63, 43, 1, 1, 1500.00),
(64, 44, 1, 3, 1500.00),
(65, 44, 5, 2, 750.00),
(66, 44, 7, 1, 1800.00),
(67, 45, 13, 2, 1000.00),
(68, 45, 8, 2, 2000.00),
(69, 45, 5, 2, 750.00),
(70, 45, 7, 1, 1800.00),
(71, 45, 4, 1, 1500.00),
(72, 46, 4, 1, 1500.00),
(73, 46, 1, 1, 1500.00),
(74, 46, 5, 1, 750.00),
(75, 46, 7, 1, 1800.00),
(76, 47, 5, 4, 750.00),
(77, 48, 1, 1, 1500.00),
(78, 48, 5, 3, 750.00),
(79, 48, 7, 1, 1800.00),
(80, 48, 4, 1, 1500.00),
(81, 49, 5, 1, 750.00),
(82, 49, 1, 2, 1500.00),
(83, 49, 4, 1, 1500.00),
(84, 49, 22, 1, 1000.00);

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `price` decimal(10,2) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `category` varchar(100) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `img` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `price`, `description`, `image`, `category`, `created_at`, `img`) VALUES
(4, 'Whey Complex', 55.00, NULL, '1772848248_whey complex.webp', NULL, '2026-03-07 01:50:38', NULL),
(5, 'One Raw', 150.00, NULL, '1772848373_one raw.webp', NULL, '2026-03-07 01:52:53', NULL),
(7, 'Creatine 1', 100.00, NULL, '1772852845_creatine1.webp', NULL, '2026-03-07 03:07:05', NULL),
(8, 'D_Y', 70.00, NULL, '1773177024_DY.webp', NULL, '2026-03-10 21:10:24', NULL),
(13, 'Tractor160', 50.00, NULL, '1774492242_tractor160.webp', NULL, '2026-03-26 02:30:42', NULL),
(22, 'Red Rex', 75.00, NULL, '1774655687_Red Rex.webp', NULL, '2026-03-27 23:54:47', NULL),
(23, 'Creatine 5000', 100.00, NULL, '1774657426_advanced.webp', NULL, '2026-03-28 00:23:46', NULL),
(24, 'Mass gainer', 200.00, NULL, '1774657621_mass gainer.webp', NULL, '2026-03-28 00:27:01', NULL),
(25, 'Citrulline Pure Ganic', 120.00, NULL, '1774657702_citrulline pure ganic.webp', NULL, '2026-03-28 00:28:22', NULL),
(26, 'Novogen', 110.00, NULL, '1774657734_novogen.webp', NULL, '2026-03-28 00:28:54', NULL),
(27, 'Crea Power', 90.00, NULL, '1774657781_crea power.webp', NULL, '2026-03-28 00:29:41', NULL),
(28, 'Creatine Dragon', 150.00, NULL, '1774657817_creatine dragon.webp', NULL, '2026-03-28 00:30:17', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `role` varchar(50) DEFAULT 'user'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `coupons`
--
ALTER TABLE `coupons`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `code` (`code`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `coupons`
--
ALTER TABLE `coupons`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=50;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=85;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
