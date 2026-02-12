-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Host: db
-- Generation Time: Feb 12, 2026 at 11:01 AM
-- Server version: 8.0.45
-- PHP Version: 8.3.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `ab_masszazs_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `bookings`
--

CREATE TABLE `bookings` (
  `id` int NOT NULL,
  `user_id` int DEFAULT NULL,
  `service_id` int NOT NULL,
  `customer_name` varchar(255) COLLATE utf8mb4_hungarian_ci NOT NULL,
  `booking_date` date NOT NULL,
  `booking_time` time NOT NULL,
  `status` enum('pending','approved','rejected') COLLATE utf8mb4_hungarian_ci DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `tel` varchar(30) COLLATE utf8mb4_hungarian_ci DEFAULT NULL,
  `email` varchar(100) COLLATE utf8mb4_hungarian_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_hungarian_ci;

--
-- Dumping data for table `bookings`
--

INSERT INTO `bookings` (`id`, `user_id`, `service_id`, `customer_name`, `booking_date`, `booking_time`, `status`, `created_at`, `tel`, `email`) VALUES
(9, 5, 1, 'mate', '2026-02-06', '10:00:00', 'pending', '2026-02-05 09:32:56', NULL, NULL),
(12, 10, 1, 'patrik', '2026-02-20', '13:00:00', 'pending', '2026-02-06 12:18:40', NULL, NULL),
(13, NULL, 1, 'Teszt Vendég', '2026-03-10', '14:30:00', 'pending', '2026-02-11 14:41:24', NULL, NULL),
(14, NULL, 2, 'Máté', '2026-02-12', '10:00:00', 'pending', '2026-02-11 15:38:08', NULL, NULL),
(15, NULL, 8, 'Máté', '2026-02-12', '11:00:00', 'pending', '2026-02-11 15:40:50', NULL, NULL),
(16, NULL, 8, 'Máté', '2026-03-06', '10:00:00', 'pending', '2026-02-11 15:43:32', NULL, NULL),
(17, NULL, 2, 'Máté', '2026-02-20', '10:00:00', 'pending', '2026-02-11 16:26:58', NULL, NULL),
(18, NULL, 2, 'Máté', '2026-02-12', '09:00:00', 'pending', '2026-02-11 16:58:53', NULL, NULL),
(19, NULL, 1, 'Máté', '2026-02-14', '16:00:00', 'pending', '2026-02-11 16:59:23', NULL, NULL),
(20, NULL, 2, 'Máté', '2026-02-15', '15:00:00', 'pending', '2026-02-12 09:22:56', '+36111111111', 'matelewi@gmail.com'),
(21, NULL, 2, 'Máté', '2026-02-15', '15:00:00', 'pending', '2026-02-12 09:22:58', '+36111111111', 'matelewi@gmail.com'),
(22, NULL, 2, 'Máté', '2026-02-15', '15:00:00', 'pending', '2026-02-12 09:22:58', '+36111111111', 'matelewi@gmail.com'),
(23, NULL, 2, 'Máté', '2026-02-27', '11:00:00', 'pending', '2026-02-12 09:42:07', '+36111111111', 'matelewi@gmail.com'),
(24, NULL, 2, 'Máté', '2026-02-27', '11:00:00', 'pending', '2026-02-12 09:42:08', '+36111111111', 'matelewi@gmail.com'),
(25, NULL, 2, 'Máté', '2026-02-13', '09:00:00', 'pending', '2026-02-12 10:37:06', '+36301314353', 'matelewi@gmail.com'),
(26, NULL, 2, 'Máté', '2026-03-01', '09:00:00', 'pending', '2026-02-12 10:39:51', '+36111111111', 'matelewi@gmail.com');

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

CREATE TABLE `messages` (
  `id` int NOT NULL,
  `name` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `email` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `tel` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `message` text COLLATE utf8mb4_general_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `messages`
--

INSERT INTO `messages` (`id`, `name`, `email`, `tel`, `message`, `created_at`) VALUES
(1, 'Madew', 'madew@gmail.com', '', 'adadada', '2026-02-04 13:40:28'),
(2, 'adada', 'adad@gmail.com', '+3123123123131', 'adadad', '2026-02-06 12:30:06'),
(3, 'Teszt Elek', 'teszt@gmail.com', '+36301234567', 'Szia, szeretnék érdeklődni a masszázs árakról!', '2026-02-11 14:38:42'),
(4, 'Máté', 'adada@gmail.com', '+36313131313', 'adadad', '2026-02-11 15:37:35'),
(5, 'dadadadada', 'adada@gmail.com', '131313131313', 'aaa', '2026-02-11 15:43:44'),
(6, 'Máté', 'matelewi@gmail.com', '+36313131313', 'aaaaaaaaaa', '2026-02-12 09:42:16');

-- --------------------------------------------------------

--
-- Table structure for table `reviews`
--

CREATE TABLE `reviews` (
  `id` int NOT NULL,
  `user_id` int UNSIGNED DEFAULT NULL,
  `service_name` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `user_name` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `rating` int DEFAULT NULL,
  `comment` text COLLATE utf8mb4_general_ci,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `reviews`
--

INSERT INTO `reviews` (`id`, `user_id`, `service_name`, `user_name`, `rating`, `comment`, `created_at`) VALUES
(1, NULL, NULL, 'Kovácsné Erzsébet', 5, 'A svédmasszázs fantasztikus volt, teljesen felfrissültem. A környezet nagyon tiszta és nyugodt.', '2026-01-15 08:00:00'),
(2, NULL, NULL, 'Tóth Gábor', 5, 'Hátfájással érkeztem, de a gyógymasszázs után sokkal könnyebb a mozgás. Profi szakember!', '2026-01-16 13:20:00'),
(3, NULL, NULL, 'Szabó Vivien', 4, 'Nagyon kedves volt a masszőr, az illóolajok pedig isteniek. Egy kicsit hűvös volt a szobában.', '2026-01-17 10:10:00'),
(4, NULL, NULL, 'Molnár Péter', 2, 'Sajnos 15 percet késtek a kezdéssel, és a masszázs is rövidebb lett emiatt.', '2026-01-18 15:45:00'),
(5, NULL, NULL, 'Nagy Adrienn', 5, 'A talpmasszázs után úgy éreztem, mintha a fellegekben járnék. Biztosan visszatérek!', '2026-01-19 09:30:00'),
(6, NULL, NULL, 'Farkas László', 3, 'Az erősség rendben volt, de a zene egy kicsit hangos volt a relaxációhoz.', '2026-01-20 12:00:00'),
(7, NULL, NULL, 'Balla Csilla', 5, 'Ajándékba kaptam a bérletet, és ez volt a legjobb meglepetés! Igazi kényeztetés.', '2026-01-21 07:15:00'),
(8, 10, 'Yumeiho', '', 5, 'nem mentem el, de szar volt', '2026-02-06 12:23:10'),
(9, 10, 'Svédmasszázs', '', 2, 'adadad', '2026-02-06 12:24:46'),
(10, 10, 'Talpreflexológia', '', 2, 'adada', '2026-02-06 12:26:24'),
(11, 10, 'Svédmasszázs', '', 3, 'adada', '2026-02-06 12:27:54'),
(12, 10, 'Svédmasszázs', '', 2, 'adada', '2026-02-06 12:28:18'),
(13, 10, 'Svédmasszázs', '', 2, 'dadad', '2026-02-06 12:29:45'),
(14, NULL, 'Svédmasszázs', 'adada', 2, 'adad', '2026-02-12 09:24:22'),
(15, NULL, 'Svédmasszázs', 'adada', 3, 'aaaaaa', '2026-02-12 09:42:21');

-- --------------------------------------------------------

--
-- Table structure for table `services`
--

CREATE TABLE `services` (
  `id` int NOT NULL,
  `name` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `price` int DEFAULT NULL,
  `duration` int DEFAULT NULL,
  `description` text COLLATE utf8mb4_general_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `services`
--

INSERT INTO `services` (`id`, `name`, `price`, `duration`, `description`) VALUES
(1, 'Yumeiho', 15000, 60, NULL),
(2, 'Svédmasszázs', 12000, 60, 'Klasszikus izomlazító masszázs, amely javítja a keringést.'),
(8, 'Talpreflexológia', 8500, 30, 'Speciális zónamasszázs a belső szervek egyensúlyáért.');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int NOT NULL,
  `username` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `role` varchar(20) COLLATE utf8mb4_general_ci DEFAULT 'user',
  `email` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `tel` varchar(20) COLLATE utf8mb4_general_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `role`, `email`, `created_at`, `tel`) VALUES
(1, 'admin', '$2y$10$S6jcpOtCp031lkyHuP/kcus2X.mRq6nr424a3bRctg6ryE/Qh4P4u', 'admin', 'user1@example.com', '2026-01-30 11:44:27', NULL),
(2, 'Madew', '$2y$10$a808EQ7LHUE./7n3SMSy3OdCFrCwHP/e6CStZkipV8bWVhmR3wBAK', 'user', 'madew@gmail.com', '2026-01-30 11:44:27', NULL),
(3, 'toth_peti', '$2y$10$baPiYzxiIoN0a8HTSVA9h.eEJTpv5lTS7PNzl5Joz4ApKiomMxuW.', 'user', 'user3@example.com', '2026-01-30 11:44:27', NULL),
(4, 'Robi', '$2y$10$BtS6o6hVJAfaT06Uy3ut5uQPnV7zS2CDMfKOgoaGtUk7FF8DAVWxu', 'user', 'user4@example.com', '2026-01-30 11:44:27', NULL),
(5, 'mate', '$2y$10$nc0WgTl4d.sbwjfrBu5JMuyZ95YzOOkIk4EsT37bLmhr/gOYhOZN2', 'user', 'mate@gmail.com', '2026-02-05 09:32:07', '36301314333'),
(6, 'Madew3', '$2y$10$QIIi49750L6awt/xqwtfk.tUp5Bla5mmGwFu/nZ/7TXfzDQsn2RN6', 'user', 'madew3@gmail.com', '2026-02-06 11:19:34', '36303033333'),
(7, 'Madew4', '$2y$10$iOfbWUCTYqMYqIc1jUpDZ.noCqJqnyaMgoiulWSDHmY10nhUHUlfu', 'user', 'madew4@gmail.com', '2026-02-06 11:25:48', '36333333333'),
(8, 'Madew10', '$2y$10$iyUugRLMU3IyVgca03DYqecjLOCxuxxVPLk/ivyhiW/3omHaQXg4S', 'user', 'madew10@gmail.com', '2026-02-06 11:33:53', '36301331313131313131'),
(9, 'Madew11', '$2y$10$lw9d4xKkCXTgteVB5QbTbul3Wu.N9zijrU2PLrFid9TjdePWujJGm', 'user', 'madew11@gmail.com', '2026-02-06 11:36:30', '363333333333'),
(10, 'Madew12', '$2y$10$cJUAfCv6/vF.kl6V0ckfZu.6OR0Lkp5xD02q5YdsGPytn3UF7Vw32', 'user', 'madew12@gmail.com', '2026-02-06 11:38:51', '361212121211'),
(12, 'TESZT7', '$2y$10$6i0I2zuUQORirlY8qQhDHO02YosUDGJILQdhmPPKo08vfxiP795pa', 'user', 'matelewi@gmail.com', '2026-02-11 16:49:38', '361111111111'),
(16, 'TESZT67', '$2y$10$UY92vyJDDQTr9Jl8TrrS4OjBhXL3VcDxhAL/F/tjlBJD8T26GA9Qa', 'user', 'rizmajermatelewi@gmail.com', '2026-02-11 16:53:50', '36301314353'),
(17, 'TESZT99', '$2y$10$HDQcGXgPS0wF3rGpNa0ziOHdZrhbOGWZhAfTJ2VC.00S6rSHUGwSO', 'user', 'matelewicsgo@gmail.com', '2026-02-11 16:57:37', '36301313131');

-- --------------------------------------------------------

--
-- Table structure for table `vouchers`
--

CREATE TABLE `vouchers` (
  `id` int NOT NULL,
  `user_id` int DEFAULT NULL,
  `code` varchar(20) COLLATE utf8mb4_general_ci NOT NULL,
  `recipient_name` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `amount` int NOT NULL,
  `expiry_date` date NOT NULL,
  `status` enum('active','used') COLLATE utf8mb4_general_ci DEFAULT 'active',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `buyer_email` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `buyer_tel` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `vouchers`
--

INSERT INTO `vouchers` (`id`, `user_id`, `code`, `recipient_name`, `amount`, `expiry_date`, `status`, `created_at`, `buyer_email`, `buyer_tel`) VALUES
(25, 2, 'AB-26-AEC05B', 'Máté', 25000, '2027-02-04', 'active', '2026-02-04 13:28:24', 'madew@gmail.com', '06301314353'),
(26, 5, 'AB-26-AF38F8', 'Mate', 10000, '2027-02-05', 'active', '2026-02-05 09:33:21', 'mate@gmail.com', '36301314333'),
(27, 5, 'AB-26-BF2762', 'Mate', 10000, '2027-02-05', 'active', '2026-02-05 09:54:59', 'mate@gmail.com', '36301314333'),
(28, 10, 'AB-26-9B512C', 'Máté', 25000, '2027-02-06', 'active', '2026-02-06 11:55:14', 'madew@gmail.com', '+363030303030'),
(29, 17, 'AB-26-A3EAD5', 'MÁté', 10000, '2027-02-12', 'active', '2026-02-12 08:42:46', 'matelewi@gmail.com', '+36333333333'),
(30, 17, 'AB-26-BFB2C2', 'dadad', 50000, '2027-02-12', 'active', '2026-02-12 09:03:47', 'matelewi@gmail.com', NULL),
(31, 17, 'AB-26-284A2D', 'MÁté', 50000, '2027-02-12', 'active', '2026-02-12 09:04:30', 'matelewi@gmail.com', NULL),
(32, 17, 'AB-26-59C771', 'MÁté', 25000, '2027-02-12', 'active', '2026-02-12 09:26:27', 'matelewi@gmail.com', NULL),
(33, 17, 'AB-26-EED369', 'Viki', 50000, '2027-02-12', 'active', '2026-02-12 09:51:11', 'matelewi@gmail.com', '+36333333333');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `bookings`
--
ALTER TABLE `bookings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `reviews`
--
ALTER TABLE `reviews`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `services`
--
ALTER TABLE `services`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `tel` (`tel`);

--
-- Indexes for table `vouchers`
--
ALTER TABLE `vouchers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `code` (`code`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `bookings`
--
ALTER TABLE `bookings`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `messages`
--
ALTER TABLE `messages`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `reviews`
--
ALTER TABLE `reviews`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `services`
--
ALTER TABLE `services`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `vouchers`
--
ALTER TABLE `vouchers`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
