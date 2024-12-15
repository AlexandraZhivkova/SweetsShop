-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 15, 2024 at 08:10 PM
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
-- Database: `sweets_shop`
--
CREATE DATABASE sweets_shop;
CREATE USER 'sweetsshop_admin'@'localhost' IDENTIFIED BY 'Sweets_2024';
GRANT ALL PRIVILEGES ON sweets_shop.* TO `sweetsshop_admin`@`localhost`;


-- --------------------------------------------------------

--
-- Table structure for table `desserts`
--

CREATE TABLE `desserts` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `price` decimal(18,2) NOT NULL,
  `image` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `desserts`
--

INSERT INTO `desserts` (`id`, `name`, `price`, `image`) VALUES
(1, 'Тирамису', 5.50, '1734278223_tiramisu.png'),
(2, 'Наполеон', 7.59, '1734289582_napoleon.png'),
(3, 'Чийзкейк', 4.99, '1734289612_cheesecake.png'),
(4, 'Френски макарон', 9.00, '1734289639_macaron.png');

-- --------------------------------------------------------

--
-- Table structure for table `favourite_desserts_users`
--

CREATE TABLE `favourite_desserts_users` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `dessert_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `types`
--

CREATE TABLE `types` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `types`
--

INSERT INTO `types` (`id`, `name`) VALUES
(1, 'user'),
(2, 'admin');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `names` varchar(255) NOT NULL,
  `type_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `email`, `password_hash`, `names`, `type_id`) VALUES
(2, 'alex@gmail.com', '$argon2i$v=19$m=65536,t=4,p=1$Zzd0aGdFdW1jb1V5UEdlSw$2UwYAf/8dZBZZn/MN8uFc1XaqQaLmp9bg19BfX1ZYeM', 'Александра', 1),
(3, 'alex_user@test.com', '$argon2i$v=19$m=65536,t=4,p=1$SWFQWDlBUnRNOVE2LkZGeg$yf/qDFaXjDWXrEY1+pxb8dJe5udpGuoG3ZGebhXSqAw', 'Александра', 1),
(4, 'alex_admin@test.com', '$argon2i$v=19$m=65536,t=4,p=1$YWIva3dpcjZLOVcxU1hVMw$zrNP1aOzkWHJ9DV/IN2j5SveCBvq887yaTmen9/uBBs', 'Александра', 2);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `desserts`
--
ALTER TABLE `desserts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `favourite_desserts_users`
--
ALTER TABLE `favourite_desserts_users`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `dessert_id` (`dessert_id`);

--
-- Indexes for table `types`
--
ALTER TABLE `types`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD KEY `type_id` (`type_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `desserts`
--
ALTER TABLE `desserts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `favourite_desserts_users`
--
ALTER TABLE `favourite_desserts_users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `types`
--
ALTER TABLE `types`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `favourite_desserts_users`
--
ALTER TABLE `favourite_desserts_users`
  ADD CONSTRAINT `favourite_desserts_users_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `favourite_desserts_users_ibfk_2` FOREIGN KEY (`dessert_id`) REFERENCES `desserts` (`id`);

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_ibfk_1` FOREIGN KEY (`type_id`) REFERENCES `types` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
