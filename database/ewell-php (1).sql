-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 26, 2025 at 04:29 AM
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
-- Table structure for table `answers`
--

CREATE TABLE `answers` (
  `id` int(11) NOT NULL,
  `question_id` int(11) NOT NULL,
  `answer` varchar(255) NOT NULL,
  `is_correct` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `answers`
--

INSERT INTO `answers` (`id`, `question_id`, `answer`, `is_correct`, `created_at`) VALUES
(1, 16, 'A. Increased risk of heart disease', 0, '2025-05-25 12:16:49'),
(2, 16, 'B. Decreased energy levels', 0, '2025-05-25 12:16:49'),
(3, 16, 'C. Improved mental health', 1, '2025-05-25 12:16:49'),
(4, 16, 'D. Weakened immune system', 0, '2025-05-25 12:16:49'),
(1, 1, 'Daily', 1, '2025-05-25 13:20:15'),
(2, 1, '3-4 times a week', 0, '2025-05-25 13:20:15'),
(3, 1, '1-2 times a week', 0, '2025-05-25 13:20:15'),
(4, 1, 'Rarely or never', 0, '2025-05-25 13:20:15'),
(5, 2, '7-8 hours', 1, '2025-05-25 13:20:15'),
(6, 2, '6 hours', 0, '2025-05-25 13:20:15'),
(7, 2, '5 hours or less', 0, '2025-05-25 13:20:15'),
(8, 2, 'More than 8 hours', 0, '2025-05-25 13:20:15'),
(9, 3, '8 or more glasses', 1, '2025-05-25 13:20:15'),
(10, 3, '6-7 glasses', 0, '2025-05-25 13:20:15'),
(11, 3, '4-5 glasses', 0, '2025-05-25 13:20:15'),
(12, 3, 'Less than 4 glasses', 0, '2025-05-25 13:20:15'),
(13, 4, 'Daily', 1, '2025-05-25 13:20:15'),
(14, 4, '4-6 times a week', 0, '2025-05-25 13:20:15'),
(15, 4, '1-3 times a week', 0, '2025-05-25 13:20:15'),
(16, 4, 'Rarely or never', 0, '2025-05-25 13:20:15'),
(17, 5, 'Rarely or never', 1, '2025-05-25 13:20:15'),
(18, 5, 'Sometimes', 0, '2025-05-25 13:20:15'),
(19, 5, 'Often', 0, '2025-05-25 13:20:15'),
(20, 5, 'Very often', 0, '2025-05-25 13:20:15'),
(21, 6, 'Daily', 1, '2025-05-25 13:20:15'),
(22, 6, '3-4 times a week', 0, '2025-05-25 13:20:15'),
(23, 6, '1-2 times a week', 0, '2025-05-25 13:20:15'),
(24, 6, 'Rarely or never', 0, '2025-05-25 13:20:15'),
(25, 7, 'Very good', 1, '2025-05-25 13:20:15'),
(26, 7, 'Good', 0, '2025-05-25 13:20:15'),
(27, 7, 'Fair', 0, '2025-05-25 13:20:15'),
(28, 7, 'Poor', 0, '2025-05-25 13:20:15'),
(29, 8, 'Every hour', 1, '2025-05-25 13:20:15'),
(30, 8, 'Every 2 hours', 0, '2025-05-25 13:20:15'),
(31, 8, 'Every 3-4 hours', 0, '2025-05-25 13:20:15'),
(32, 8, 'Rarely or never', 0, '2025-05-25 13:20:15'),
(33, 9, 'Excellent', 1, '2025-05-25 13:20:15'),
(34, 9, 'Good', 0, '2025-05-25 13:20:15'),
(35, 9, 'Fair', 0, '2025-05-25 13:20:15'),
(36, 9, 'Poor', 0, '2025-05-25 13:20:15'),
(37, 10, 'Daily', 1, '2025-05-25 13:20:15'),
(38, 10, '3-4 times a week', 0, '2025-05-25 13:20:15'),
(39, 10, '1-2 times a week', 0, '2025-05-25 13:20:15'),
(40, 10, 'Rarely or never', 0, '2025-05-25 13:20:15'),
(41, 11, 'Every 3-4 months', 1, '2025-05-25 13:20:15'),
(42, 11, 'Every 6 months', 0, '2025-05-25 13:20:15'),
(43, 11, 'Once a year', 0, '2025-05-25 13:20:15'),
(44, 11, 'Rarely or never', 0, '2025-05-25 13:20:15'),
(45, 12, 'Daily', 1, '2025-05-25 13:20:15'),
(46, 12, '3-4 times a week', 0, '2025-05-25 13:20:15'),
(47, 12, '1-2 times a week', 0, '2025-05-25 13:20:15'),
(48, 12, 'Rarely or never', 0, '2025-05-25 13:20:15');

-- --------------------------------------------------------

--
-- Table structure for table `questions`
--

CREATE TABLE `questions` (
  `id` int(11) NOT NULL,
  `quiz_id` int(11) NOT NULL,
  `question` varchar(255) NOT NULL,
  `points` int(11) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `questions`
--

INSERT INTO `questions` (`id`, `quiz_id`, `question`, `points`, `created_at`) VALUES
(16, 1, 'Which of the following is considered a benefit of regular physical exercise?', 1, '2025-05-25 12:16:49'),
(1, 1, 'How often do you engage in physical exercise?', 10, '2025-05-25 13:20:15'),
(2, 1, 'How many hours of sleep do you get on average?', 10, '2025-05-25 13:20:15'),
(3, 1, 'How many glasses of water do you drink daily?', 10, '2025-05-25 13:20:15'),
(4, 1, 'How often do you eat fruits and vegetables?', 10, '2025-05-25 13:20:15'),
(5, 1, 'How often do you feel stressed or anxious?', 10, '2025-05-25 13:20:15'),
(6, 1, 'How often do you practice mindfulness or meditation?', 10, '2025-05-25 13:20:15'),
(7, 1, 'How would you rate your overall mood?', 10, '2025-05-25 13:20:15'),
(8, 1, 'How often do you take breaks during work?', 10, '2025-05-25 13:20:15'),
(9, 1, 'How would you rate your work-life balance?', 10, '2025-05-25 13:20:15'),
(10, 1, 'How often do you engage in hobbies or leisure activities?', 10, '2025-05-25 13:20:15'),
(11, 1, 'How often do you take vacations or time off?', 10, '2025-05-25 13:20:15'),
(12, 1, 'How often do you maintain social connections?', 10, '2025-05-25 13:20:15');

-- --------------------------------------------------------

--
-- Table structure for table `quizzes`
--

CREATE TABLE `quizzes` (
  `quiz_id` int(11) NOT NULL,
  `title` varchar(122) NOT NULL,
  `description` varchar(122) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `quizzes`
--

INSERT INTO `quizzes` (`quiz_id`, `title`, `description`, `created_at`) VALUES
(1, 'Health Assessment', 'A comprehensive assessment of your physical, mental, and lifestyle health.', '2025-05-25 13:20:15');

-- --------------------------------------------------------

--
-- Table structure for table `quiz_answers`
--

CREATE TABLE `quiz_answers` (
  `id` int(11) NOT NULL,
  `quiz_result_id` int(11) NOT NULL,
  `question_number` int(11) NOT NULL,
  `answer_value` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `quiz_results`
--

CREATE TABLE `quiz_results` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `physical_score` int(11) NOT NULL,
  `mental_score` int(11) NOT NULL,
  `lifestyle_score` int(11) NOT NULL,
  `created_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `scores`
--

CREATE TABLE `scores` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `score` int(10) NOT NULL,
  `played_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `first_name` varchar(50) NOT NULL,
  `last_name` varchar(50) NOT NULL,
  `Email` varchar(50) NOT NULL,
  `password` varchar(50) NOT NULL,
  `role` enum('Admin','User') NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `first_name`, `last_name`, `Email`, `password`, `role`, `created_at`) VALUES
(1, 'James ', 'Pama', 'James', '$2y$10$BAGrz12tn4vuz50fvHWAReudRc1RV9OsTTBGw.L4zCl', 'Admin', '2025-05-22 03:19:27'),
(3, 'John Abner', 'Garzon', 'Admin1@gmail.com', '$2y$10$DvJVL1GhO3Ivik73LBwm0eSGLdeg79az34q5E1KyYbh', 'Admin', '2025-05-25 13:00:25'),
(4, 'Test1', 'Test1', 'Test1@ewell-php.gov', '$2y$10$qjzlrbg0hThzIpuHrq9NNOmHngOzuICS6MJFT5SHjuF', 'Admin', '2025-05-25 13:09:46');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
