-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 02, 2025 at 04:18 AM
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
-- Database: `ewell-php`
--

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `first_name` varchar(50) NOT NULL,
  `last_name` varchar(50) NOT NULL,
  `birthdate` date DEFAULT NULL,
  `gender` enum('Male','Female','Other','Prefer not to say') NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone_no` varchar(11) NOT NULL,
  `height` float(10,2) NOT NULL,
  `weight` float(10,2) NOT NULL,
  `activity_level` enum('Sedentary','Lightly Active','Moderately Active','Very Active','Extra Active') NOT NULL,
  `health_goals` enum('Weight Loss','Muscle Gain','Maintenance','General Fitness') NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('Admin','User') NOT NULL DEFAULT 'User',
  `last_login` datetime DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `first_name`, `last_name`, `birthdate`, `gender`, `email`, `phone_no`, `height`, `weight`, `activity_level`, `health_goals`, `password`, `role`, `last_login`, `created_at`, `updated_at`) VALUES
(3, 'Admin', 'User', NULL, 'Male', 'admin@ewell.com', '0', 0.00, 0.00, 'Sedentary', 'Weight Loss', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Admin', NULL, '2025-05-26 13:41:41', NULL),
(4, 'Test', 'User', NULL, 'Male', 'test@ewell.com', '0', 0.00, 0.00, 'Sedentary', 'Weight Loss', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'User', NULL, '2025-05-26 13:41:41', NULL),
(11, 'jake', 'jake', NULL, 'Male', 'jake@gmail.com', '', 0.00, 0.00, 'Sedentary', 'Weight Loss', '$2y$10$sCmVSa22KBhQKvuu6rBOY..cJ934I2HSMVGnA8Vd2azSLiF0DYc4K', 'User', NULL, '2025-05-30 15:49:42', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
