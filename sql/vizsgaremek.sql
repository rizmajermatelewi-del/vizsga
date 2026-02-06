-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Gép: 127.0.0.1
-- Létrehozás ideje: 2026. Feb 06. 13:36
-- Kiszolgáló verziója: 10.4.32-MariaDB
-- PHP verzió: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Adatbázis: `vizsgaremek`
--

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `bookings`
--

CREATE TABLE `bookings` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `service_id` int(11) NOT NULL,
  `customer_name` varchar(255) NOT NULL,
  `booking_date` date NOT NULL,
  `booking_time` time NOT NULL,
  `status` enum('pending','approved','rejected') DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `tel` varchar(20) DEFAULT NULL,
  `email` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_hungarian_ci;

--
-- A tábla adatainak kiíratása `bookings`
--

INSERT INTO `bookings` (`id`, `user_id`, `service_id`, `customer_name`, `booking_date`, `booking_time`, `status`, `created_at`, `tel`, `email`) VALUES
(9, 5, 1, 'mate', '2026-02-06', '10:00:00', 'pending', '2026-02-05 09:32:56', NULL, NULL),
(12, 10, 1, 'patrik', '2026-02-20', '13:00:00', 'pending', '2026-02-06 12:18:40', NULL, NULL);

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `messages`
--

CREATE TABLE `messages` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `message` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- A tábla adatainak kiíratása `messages`
--

INSERT INTO `messages` (`id`, `name`, `email`, `phone`, `message`, `created_at`) VALUES
(1, 'Madew', 'madew@gmail.com', '', 'adadada', '2026-02-04 13:40:28'),
(2, 'adada', 'adad@gmail.com', '+3123123123131', 'adadad', '2026-02-06 12:30:06');

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `reviews`
--

CREATE TABLE `reviews` (
  `id` int(11) NOT NULL,
  `user_id` int(11) UNSIGNED DEFAULT NULL,
  `service_name` varchar(50) DEFAULT NULL,
  `user_name` varchar(255) NOT NULL,
  `rating` int(11) DEFAULT NULL,
  `comment` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- A tábla adatainak kiíratása `reviews`
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
(13, 10, 'Svédmasszázs', '', 2, 'dadad', '2026-02-06 12:29:45');

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `services`
--

CREATE TABLE `services` (
  `id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `price` int(11) DEFAULT NULL,
  `duration` int(11) DEFAULT NULL,
  `description` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- A tábla adatainak kiíratása `services`
--

INSERT INTO `services` (`id`, `name`, `price`, `duration`, `description`) VALUES
(1, 'Yumeiho', 15000, 60, NULL),
(2, 'Svédmasszázs', 12000, 60, 'Klasszikus izomlazító masszázs, amely javítja a keringést.'),
(8, 'Talpreflexológia', 8500, 30, 'Speciális zónamasszázs a belső szervek egyensúlyáért.');

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `role` varchar(20) DEFAULT 'user',
  `email` varchar(100) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `tel` varchar(20) DEFAULT NULL,
  `newsletter` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- A tábla adatainak kiíratása `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `role`, `email`, `created_at`, `tel`, `newsletter`) VALUES
(1, 'admin', '$2y$10$S6jcpOtCp031lkyHuP/kcus2X.mRq6nr424a3bRctg6ryE/Qh4P4u', 'admin', 'user1@example.com', '2026-01-30 11:44:27', NULL, 0),
(2, 'Madew', '$2y$10$a808EQ7LHUE./7n3SMSy3OdCFrCwHP/e6CStZkipV8bWVhmR3wBAK', 'user', 'madew@gmail.com', '2026-01-30 11:44:27', NULL, 0),
(3, 'toth_peti', '$2y$10$baPiYzxiIoN0a8HTSVA9h.eEJTpv5lTS7PNzl5Joz4ApKiomMxuW.', 'user', 'user3@example.com', '2026-01-30 11:44:27', NULL, 0),
(4, 'Robi', '$2y$10$BtS6o6hVJAfaT06Uy3ut5uQPnV7zS2CDMfKOgoaGtUk7FF8DAVWxu', 'user', 'user4@example.com', '2026-01-30 11:44:27', NULL, 0),
(5, 'mate', '$2y$10$nc0WgTl4d.sbwjfrBu5JMuyZ95YzOOkIk4EsT37bLmhr/gOYhOZN2', 'user', 'mate@gmail.com', '2026-02-05 09:32:07', '36301314333', 0),
(6, 'Madew3', '$2y$10$QIIi49750L6awt/xqwtfk.tUp5Bla5mmGwFu/nZ/7TXfzDQsn2RN6', 'user', 'madew3@gmail.com', '2026-02-06 11:19:34', '36303033333', 0),
(7, 'Madew4', '$2y$10$iOfbWUCTYqMYqIc1jUpDZ.noCqJqnyaMgoiulWSDHmY10nhUHUlfu', 'user', 'madew4@gmail.com', '2026-02-06 11:25:48', '36333333333', 0),
(8, 'Madew10', '$2y$10$iyUugRLMU3IyVgca03DYqecjLOCxuxxVPLk/ivyhiW/3omHaQXg4S', 'user', 'madew10@gmail.com', '2026-02-06 11:33:53', '36301331313131313131', 0),
(9, 'Madew11', '$2y$10$lw9d4xKkCXTgteVB5QbTbul3Wu.N9zijrU2PLrFid9TjdePWujJGm', 'user', 'madew11@gmail.com', '2026-02-06 11:36:30', '363333333333', 0),
(10, 'Madew12', '$2y$10$cJUAfCv6/vF.kl6V0ckfZu.6OR0Lkp5xD02q5YdsGPytn3UF7Vw32', 'user', 'madew12@gmail.com', '2026-02-06 11:38:51', '361212121211', 0);

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `vouchers`
--

CREATE TABLE `vouchers` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `code` varchar(20) NOT NULL,
  `recipient_name` varchar(100) DEFAULT NULL,
  `amount` int(11) NOT NULL,
  `expiry_date` date NOT NULL,
  `status` enum('active','used') DEFAULT 'active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `buyer_email` varchar(100) DEFAULT NULL,
  `buyer_phone` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- A tábla adatainak kiíratása `vouchers`
--

INSERT INTO `vouchers` (`id`, `user_id`, `code`, `recipient_name`, `amount`, `expiry_date`, `status`, `created_at`, `buyer_email`, `buyer_phone`) VALUES
(25, 2, 'AB-26-AEC05B', 'Máté', 25000, '2027-02-04', 'active', '2026-02-04 13:28:24', 'madew@gmail.com', '06301314353'),
(26, 5, 'AB-26-AF38F8', 'Mate', 10000, '2027-02-05', 'active', '2026-02-05 09:33:21', 'mate@gmail.com', '36301314333'),
(27, 5, 'AB-26-BF2762', 'Mate', 10000, '2027-02-05', 'active', '2026-02-05 09:54:59', 'mate@gmail.com', '36301314333'),
(28, 10, 'AB-26-9B512C', 'Máté', 25000, '2027-02-06', 'active', '2026-02-06 11:55:14', 'madew@gmail.com', '+363030303030');

--
-- Indexek a kiírt táblákhoz
--

--
-- A tábla indexei `bookings`
--
ALTER TABLE `bookings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `tel` (`tel`),
  ADD UNIQUE KEY `email` (`email`);

--
-- A tábla indexei `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`id`);

--
-- A tábla indexei `reviews`
--
ALTER TABLE `reviews`
  ADD PRIMARY KEY (`id`);

--
-- A tábla indexei `services`
--
ALTER TABLE `services`
  ADD PRIMARY KEY (`id`);

--
-- A tábla indexei `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `tel` (`tel`);

--
-- A tábla indexei `vouchers`
--
ALTER TABLE `vouchers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `code` (`code`);

--
-- A kiírt táblák AUTO_INCREMENT értéke
--

--
-- AUTO_INCREMENT a táblához `bookings`
--
ALTER TABLE `bookings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT a táblához `messages`
--
ALTER TABLE `messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT a táblához `reviews`
--
ALTER TABLE `reviews`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT a táblához `services`
--
ALTER TABLE `services`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT a táblához `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT a táblához `vouchers`
--
ALTER TABLE `vouchers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
