-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- 主機： 127.0.0.1
-- 產生時間： 2024-10-06 16:37:04
-- 伺服器版本： 10.4.32-MariaDB
-- PHP 版本： 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- 資料庫： `phmsfinal`
--

-- --------------------------------------------------------

--
-- 資料表結構 `admin_users`
--

CREATE TABLE `admin_users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `role` varchar(50) DEFAULT 'admin'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- 傾印資料表的資料 `admin_users`
--

INSERT INTO `admin_users` (`id`, `username`, `password`, `email`, `role`) VALUES
(1, 'bob', 'andyyoyo430', NULL, 'admin');

-- --------------------------------------------------------

--
-- 資料表結構 `cleaning_records`
--

CREATE TABLE `cleaning_records` (
  `id` int(11) NOT NULL,
  `pet_id` int(11) NOT NULL,
  `clean_location` varchar(255) NOT NULL,
  `clean_time` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `cleanliness_level` int(11) NOT NULL,
  `notes` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- 傾印資料表的資料 `cleaning_records`
--

INSERT INTO `cleaning_records` (`id`, `pet_id`, `clean_location`, `clean_time`, `cleanliness_level`, `notes`) VALUES
(1, 1, '寵物房', '2024-10-05 02:30:00', 100, '0');

-- --------------------------------------------------------

--
-- 資料表結構 `feeding_records`
--

CREATE TABLE `feeding_records` (
  `id` int(11) NOT NULL,
  `pet_id` int(11) NOT NULL,
  `feeding_time` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `food_type` varchar(255) NOT NULL,
  `quantity` int(11) NOT NULL,
  `notes` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- 傾印資料表的資料 `feeding_records`
--

INSERT INTO `feeding_records` (`id`, `pet_id`, `feeding_time`, `food_type`, `quantity`, `notes`) VALUES
(1, 1, '2024-10-05 00:00:00', '乾糧', 200, '0');

-- --------------------------------------------------------

--
-- 資料表結構 `gas_sensor_records`
--

CREATE TABLE `gas_sensor_records` (
  `id` int(11) NOT NULL,
  `gas_type` varchar(255) NOT NULL,
  `concentration_ppm` int(11) NOT NULL,
  `recorded_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- 傾印資料表的資料 `gas_sensor_records`
--

INSERT INTO `gas_sensor_records` (`id`, `gas_type`, `concentration_ppm`, `recorded_at`) VALUES
(1, 'CO2', 401, '2024-10-04 22:40:00'),
(2, 'CO2', 400, '2024-10-04 22:41:00');

-- --------------------------------------------------------

--
-- 資料表結構 `pets`
--

CREATE TABLE `pets` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `pet_name` varchar(255) NOT NULL,
  `pet_type` varchar(255) DEFAULT NULL,
  `pet_age` int(11) DEFAULT NULL,
  `pet_breed` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- 傾印資料表的資料 `pets`
--

INSERT INTO `pets` (`id`, `user_id`, `pet_name`, `pet_type`, `pet_age`, `pet_breed`, `created_at`) VALUES
(1, 1, 'Lucky', '狗', 3, '黃金獵犬', '2024-10-05 01:20:16'),
(2, 2, 'cindy', 'cat', 5, '白毛羊', '2024-10-05 02:52:51');

-- --------------------------------------------------------

--
-- 資料表結構 `temp_humidity_records`
--

CREATE TABLE `temp_humidity_records` (
  `id` int(11) NOT NULL,
  `temperature` decimal(5,2) NOT NULL,
  `humidity` decimal(5,2) NOT NULL,
  `recorded_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- 傾印資料表的資料 `temp_humidity_records`
--

INSERT INTO `temp_humidity_records` (`id`, `temperature`, `humidity`, `recorded_at`) VALUES
(1, 23.50, 60.20, '2024-10-05 03:11:21'),
(2, 24.00, 58.60, '2024-10-05 03:11:21'),
(3, 22.80, 62.30, '2024-10-05 03:11:21');

-- --------------------------------------------------------

--
-- 資料表結構 `total_visits`
--

CREATE TABLE `total_visits` (
  `id` int(11) NOT NULL,
  `visit_date` date NOT NULL,
  `visit_count` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- 傾印資料表的資料 `total_visits`
--

INSERT INTO `total_visits` (`id`, `visit_date`, `visit_count`) VALUES
(2, '2024-10-05', 57),
(3, '2024-10-06', 7);

-- --------------------------------------------------------

--
-- 資料表結構 `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `age` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- 傾印資料表的資料 `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `email`, `age`, `created_at`) VALUES
(1, '12', '123', 'ag@gmail.com', 4, '2024-10-04 16:07:08'),
(2, 'andy', 'andyyoyo430', 'ag@gmail.com', 3, '2024-10-05 02:50:18');

-- --------------------------------------------------------

--
-- 資料表結構 `user_page_views`
--

CREATE TABLE `user_page_views` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `page_name` varchar(255) NOT NULL,
  `view_time` datetime NOT NULL,
  `view_count` int(11) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- 傾印資料表的資料 `user_page_views`
--

INSERT INTO `user_page_views` (`id`, `user_id`, `page_name`, `view_time`, `view_count`) VALUES
(1, 1, 'temp_humidity_records', '2024-10-06 22:07:28', 19),
(2, 1, 'user_dashboard', '2024-10-06 22:07:30', 71),
(3, 1, 'cleaning_records', '2024-10-06 16:07:24', 25),
(4, 1, 'feeding_records', '2024-10-06 16:07:29', 17),
(5, 2, 'user_dashboard', '2024-10-05 11:35:50', 5),
(6, 2, 'cleaning_records', '2024-10-05 05:35:50', 2),
(7, 2, 'feeding_records', '2024-10-05 05:35:45', 1),
(8, 2, 'temp_humidity_records', '2024-10-05 11:35:46', 1);

--
-- 已傾印資料表的索引
--

--
-- 資料表索引 `admin_users`
--
ALTER TABLE `admin_users`
  ADD PRIMARY KEY (`id`);

--
-- 資料表索引 `cleaning_records`
--
ALTER TABLE `cleaning_records`
  ADD PRIMARY KEY (`id`),
  ADD KEY `pet_id` (`pet_id`);

--
-- 資料表索引 `feeding_records`
--
ALTER TABLE `feeding_records`
  ADD PRIMARY KEY (`id`),
  ADD KEY `pet_id` (`pet_id`);

--
-- 資料表索引 `gas_sensor_records`
--
ALTER TABLE `gas_sensor_records`
  ADD PRIMARY KEY (`id`);

--
-- 資料表索引 `pets`
--
ALTER TABLE `pets`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- 資料表索引 `temp_humidity_records`
--
ALTER TABLE `temp_humidity_records`
  ADD PRIMARY KEY (`id`);

--
-- 資料表索引 `total_visits`
--
ALTER TABLE `total_visits`
  ADD PRIMARY KEY (`id`);

--
-- 資料表索引 `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- 資料表索引 `user_page_views`
--
ALTER TABLE `user_page_views`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- 在傾印的資料表使用自動遞增(AUTO_INCREMENT)
--

--
-- 使用資料表自動遞增(AUTO_INCREMENT) `admin_users`
--
ALTER TABLE `admin_users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- 使用資料表自動遞增(AUTO_INCREMENT) `cleaning_records`
--
ALTER TABLE `cleaning_records`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- 使用資料表自動遞增(AUTO_INCREMENT) `feeding_records`
--
ALTER TABLE `feeding_records`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- 使用資料表自動遞增(AUTO_INCREMENT) `gas_sensor_records`
--
ALTER TABLE `gas_sensor_records`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- 使用資料表自動遞增(AUTO_INCREMENT) `pets`
--
ALTER TABLE `pets`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- 使用資料表自動遞增(AUTO_INCREMENT) `temp_humidity_records`
--
ALTER TABLE `temp_humidity_records`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- 使用資料表自動遞增(AUTO_INCREMENT) `total_visits`
--
ALTER TABLE `total_visits`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- 使用資料表自動遞增(AUTO_INCREMENT) `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- 使用資料表自動遞增(AUTO_INCREMENT) `user_page_views`
--
ALTER TABLE `user_page_views`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- 已傾印資料表的限制式
--

--
-- 資料表的限制式 `cleaning_records`
--
ALTER TABLE `cleaning_records`
  ADD CONSTRAINT `cleaning_records_ibfk_1` FOREIGN KEY (`pet_id`) REFERENCES `pets` (`id`) ON DELETE CASCADE;

--
-- 資料表的限制式 `feeding_records`
--
ALTER TABLE `feeding_records`
  ADD CONSTRAINT `feeding_records_ibfk_1` FOREIGN KEY (`pet_id`) REFERENCES `pets` (`id`) ON DELETE CASCADE;

--
-- 資料表的限制式 `pets`
--
ALTER TABLE `pets`
  ADD CONSTRAINT `pets_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- 資料表的限制式 `user_page_views`
--
ALTER TABLE `user_page_views`
  ADD CONSTRAINT `user_page_views_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
