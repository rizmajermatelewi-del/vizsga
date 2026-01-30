-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Gép: 127.0.0.1
-- Létrehozás ideje: 2026. Jan 30. 13:24
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
(1, 2, 1, 'vendeg_mari', '2026-01-31', '19:00:00', 'pending', '2026-01-30 12:01:31', NULL, NULL);

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
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `tel` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- A tábla adatainak kiíratása `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `role`, `email`, `created_at`, `tel`) VALUES
(1, 'admin', '$2y$10$S6jcpOtCp031lkyHuP/kcus2X.mRq6nr424a3bRctg6ryE/Qh4P4u', 'admin', 'user1@example.com', '2026-01-30 11:44:27', NULL),
(2, 'vendeg_tamas', '$2y$10$3k8IUcqiZVV722qzvwHQ1ug7uXyuL6Wo1eKHCqZ7JD6sYvpP9z37W', 'user', 'user2@example.com', '2026-01-30 11:44:27', NULL),
(3, 'toth_peti', '$2y$10$baPiYzxiIoN0a8HTSVA9h.eEJTpv5lTS7PNzl5Joz4ApKiomMxuW.', 'user', 'user3@example.com', '2026-01-30 11:44:27', NULL),
(4, 'Robi', '$2y$10$BtS6o6hVJAfaT06Uy3ut5uQPnV7zS2CDMfKOgoaGtUk7FF8DAVWxu', 'user', 'user4@example.com', '2026-01-30 11:44:27', NULL);

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `vouchers`
--

CREATE TABLE `vouchers` (
  `id` int(11) NOT NULL,
  `code` varchar(20) NOT NULL,
  `amount` int(11) NOT NULL,
  `expiry_date` date NOT NULL,
  `status` enum('active','used') DEFAULT 'active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- A tábla adatainak kiíratása `vouchers`
--

INSERT INTO `vouchers` (`id`, `code`, `amount`, `expiry_date`, `status`, `created_at`) VALUES
(1, '56269DE4', 15000, '2026-02-01', 'active', '2026-01-28 12:51:44'),
(2, 'ZEN-26-SBZN', 10000, '2027-01-28', 'active', '2026-01-28 14:12:43'),
(3, 'ZEN-26-LBQW', 10000, '2027-01-30', 'active', '2026-01-30 11:45:29'),
(4, 'ZEN-26-Q58D', 10000, '2027-01-30', 'active', '2026-01-30 11:47:51'),
(5, 'ZEN-26-2XHF', 10000, '2027-01-30', 'active', '2026-01-30 11:49:45'),
(6, 'ZEN-26-CFBD', 10000, '2027-01-30', 'active', '2026-01-30 11:52:32'),
(7, 'ZEN-26-EKF3', 10000, '2027-01-30', 'active', '2026-01-30 11:55:54'),
(8, 'ZEN-26-NSRT', 10000, '2027-01-30', 'active', '2026-01-30 11:57:32'),
(9, 'ZEN-26-GSCB', 10000, '2027-01-30', 'active', '2026-01-30 12:20:58');

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
