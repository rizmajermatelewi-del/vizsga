-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Gép: 127.0.0.1
-- Létrehozás ideje: 2026. Feb 04. 15:19
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
  `phone` varchar(20) DEFAULT NULL,
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

INSERT INTO `bookings` (`id`, `user_id`, `service_id`, `customer_name`, `phone`, `booking_date`, `booking_time`, `status`, `created_at`, `tel`, `email`) VALUES
(1, 2, 1, 'vendeg_mari', NULL, '2026-01-31', '19:00:00', 'pending', '2026-01-30 12:01:31', NULL, NULL),
(2, 2, 3, 'vendeg_mari', NULL, '2026-01-31', '11:00:00', 'pending', '2026-01-30 12:39:55', NULL, NULL),
(3, 2, 1, 'vendeg_mari', NULL, '2026-01-31', '16:00:00', 'pending', '2026-01-30 12:41:17', NULL, NULL),
(4, 2, 6, 'vendeg_mari', NULL, '2026-01-31', '09:00:00', 'pending', '2026-01-30 12:45:48', NULL, NULL),
(5, 2, 4, 'vendeg_mari', NULL, '2026-01-31', '12:00:00', 'pending', '2026-01-30 12:48:44', NULL, NULL),
(6, 2, 4, 'vendeg_mari', NULL, '2026-02-08', '09:00:00', 'pending', '2026-01-30 12:51:36', NULL, NULL),
(7, 2, 2, 'vendeg_mari', NULL, '2026-03-07', '17:00:00', 'pending', '2026-02-04 10:44:12', NULL, NULL),
(8, 2, 6, 'vendeg_tamas', NULL, '2026-03-07', '10:00:00', 'pending', '2026-02-04 12:56:17', NULL, NULL);

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
(1, 'Madew', 'madew@gmail.com', '', 'adadada', '2026-02-04 13:40:28');

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `reviews`
--

CREATE TABLE `reviews` (
  `id` int(11) NOT NULL,
  `user_name` varchar(255) NOT NULL,
  `rating` int(11) DEFAULT NULL,
  `comment` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- A tábla adatainak kiíratása `reviews`
--

INSERT INTO `reviews` (`id`, `user_name`, `rating`, `comment`, `created_at`) VALUES
(1, 'Kovácsné Erzsébet', 5, 'A svédmasszázs fantasztikus volt, teljesen felfrissültem. A környezet nagyon tiszta és nyugodt.', '2026-01-15 08:00:00'),
(2, 'Tóth Gábor', 5, 'Hátfájással érkeztem, de a gyógymasszázs után sokkal könnyebb a mozgás. Profi szakember!', '2026-01-16 13:20:00'),
(3, 'Szabó Vivien', 4, 'Nagyon kedves volt a masszőr, az illóolajok pedig isteniek. Egy kicsit hűvös volt a szobában.', '2026-01-17 10:10:00'),
(4, 'Molnár Péter', 2, 'Sajnos 15 percet késtek a kezdéssel, és a masszázs is rövidebb lett emiatt.', '2026-01-18 15:45:00'),
(5, 'Nagy Adrienn', 5, 'A talpmasszázs után úgy éreztem, mintha a fellegekben járnék. Biztosan visszatérek!', '2026-01-19 09:30:00'),
(6, 'Farkas László', 3, 'Az erősség rendben volt, de a zene egy kicsit hangos volt a relaxációhoz.', '2026-01-20 12:00:00'),
(7, 'Balla Csilla', 5, 'Ajándékba kaptam a bérletet, és ez volt a legjobb meglepetés! Igazi kényeztetés.', '2026-01-21 07:15:00');

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
(3, 'Gyógymasszázs', 10500, 45, 'Célzott kezelés mozgásszervi panaszok és fájdalom enyhítésére.'),
(4, 'Aromaterápiás masszázs', 18000, 90, 'Illóolajokkal végzett relaxáló kezelés a teljes ellazulásért.'),
(5, 'Lávaköves masszázs', 22000, 75, 'Melegített vulkáni kövekkel végzett mélyszöveti kényeztetés.'),
(6, 'Thai masszázs', 15000, 60, 'Tradicionális száraz masszázs nyújtó és energetizáló elemekkel.'),
(7, 'Sportmasszázs', 13000, 50, 'Intenzív technika sportolók részére, regeneráció céljából.'),
(8, 'Talpreflexológia', 8500, 30, 'Speciális zónamasszázs a belső szervek egyensúlyáért.'),
(9, 'Kismama masszázs', 11000, 45, 'Gyengéd, biztonságos lazítás várandós hölgyeknek.');

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
  `phone` varchar(20) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `tel` varchar(20) DEFAULT NULL,
  `newsletter` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- A tábla adatainak kiíratása `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `role`, `email`, `phone`, `created_at`, `tel`, `newsletter`) VALUES
(1, 'admin', '$2y$10$S6jcpOtCp031lkyHuP/kcus2X.mRq6nr424a3bRctg6ryE/Qh4P4u', 'admin', 'user1@example.com', NULL, '2026-01-30 11:44:27', NULL, 0),
(2, 'Madew', '$2y$10$a808EQ7LHUE./7n3SMSy3OdCFrCwHP/e6CStZkipV8bWVhmR3wBAK', 'user', 'madew@gmail.com', '06301314353', '2026-01-30 11:44:27', NULL, 0),
(3, 'toth_peti', '$2y$10$baPiYzxiIoN0a8HTSVA9h.eEJTpv5lTS7PNzl5Joz4ApKiomMxuW.', 'user', 'user3@example.com', NULL, '2026-01-30 11:44:27', NULL, 0),
(4, 'Robi', '$2y$10$BtS6o6hVJAfaT06Uy3ut5uQPnV7zS2CDMfKOgoaGtUk7FF8DAVWxu', 'user', 'user4@example.com', NULL, '2026-01-30 11:44:27', NULL, 0);

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
(1, NULL, '56269DE4', NULL, 15000, '2026-02-01', 'active', '2026-01-28 12:51:44', NULL, NULL),
(2, NULL, 'ZEN-26-SBZN', NULL, 10000, '2027-01-28', 'active', '2026-01-28 14:12:43', NULL, NULL),
(3, NULL, 'ZEN-26-LBQW', NULL, 10000, '2027-01-30', 'active', '2026-01-30 11:45:29', NULL, NULL),
(4, NULL, 'ZEN-26-Q58D', NULL, 10000, '2027-01-30', 'active', '2026-01-30 11:47:51', NULL, NULL),
(5, NULL, 'ZEN-26-2XHF', NULL, 10000, '2027-01-30', 'active', '2026-01-30 11:49:45', NULL, NULL),
(6, NULL, 'ZEN-26-CFBD', NULL, 10000, '2027-01-30', 'active', '2026-01-30 11:52:32', NULL, NULL),
(7, NULL, 'ZEN-26-EKF3', NULL, 10000, '2027-01-30', 'active', '2026-01-30 11:55:54', NULL, NULL),
(8, NULL, 'ZEN-26-NSRT', NULL, 10000, '2027-01-30', 'active', '2026-01-30 11:57:32', NULL, NULL),
(9, NULL, 'ZEN-26-GSCB', NULL, 10000, '2027-01-30', 'active', '2026-01-30 12:20:58', NULL, NULL),
(10, NULL, 'ZEN-26-N9AB', NULL, 10000, '2027-01-30', 'active', '2026-01-30 12:42:42', NULL, NULL),
(11, NULL, 'ZEN-26-XB5U', NULL, 10000, '2027-01-30', 'active', '2026-01-30 12:44:47', NULL, NULL),
(12, NULL, 'ZEN-26-NVJX', NULL, 50000, '2027-01-30', 'active', '2026-01-30 12:45:36', NULL, NULL),
(13, NULL, 'ZEN-26-CF3V', NULL, 25000, '2027-01-30', 'active', '2026-01-30 12:46:46', NULL, NULL),
(14, NULL, 'ZEN-26-HQTY', NULL, 25000, '2027-01-30', 'active', '2026-01-30 12:48:50', NULL, NULL),
(15, NULL, 'ZEN-B8468F', NULL, 10000, '2027-02-04', 'active', '2026-02-04 10:49:25', NULL, NULL),
(16, NULL, 'ZEN-066F83', NULL, 50000, '2027-02-04', 'active', '2026-02-04 10:53:26', NULL, NULL),
(18, NULL, 'ZEN-B6C843', 'Máté', 50000, '2027-02-04', 'active', '2026-02-04 10:54:22', NULL, NULL),
(19, NULL, 'ZEN-E36CC3', 'Máté', 10000, '2027-02-04', 'active', '2026-02-04 11:02:31', NULL, NULL),
(20, NULL, 'ZEN-7ADDF9', 'Máté', 10000, '2027-02-04', 'active', '2026-02-04 11:04:53', NULL, NULL),
(21, 2, 'ZEN-402EC7', 'Máté', 50000, '2027-02-04', 'active', '2026-02-04 13:20:04', 'madew@gmail.com', '06301314353'),
(22, 2, 'ZEN-80473F', 'Máté', 10000, '2027-02-04', 'active', '2026-02-04 13:23:46', 'madew@gmail.com', '06301314353'),
(23, 2, 'ZEN-CC4D0E', 'Máté', 25000, '2027-02-04', 'active', '2026-02-04 13:25:25', 'madew@gmail.com', '06301314353'),
(24, 2, 'ZEN-896A10', 'Máté', 10000, '2027-02-04', 'active', '2026-02-04 13:26:36', 'madew@gmail.com', '06301314353'),
(25, 2, 'AB-26-AEC05B', 'Máté', 25000, '2027-02-04', 'active', '2026-02-04 13:28:24', 'madew@gmail.com', '06301314353');

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT a táblához `messages`
--
ALTER TABLE `messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT a táblához `reviews`
--
ALTER TABLE `reviews`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT a táblához `services`
--
ALTER TABLE `services`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT a táblához `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT a táblához `vouchers`
--
ALTER TABLE `vouchers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
