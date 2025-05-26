-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 25, 2025 at 03:10 PM
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

--
-- Dumping data for table `quizzes`
--

INSERT INTO `quizzes` (`quiz_id`, `title`, `description`, `created_at`) VALUES
(1, 'Health Assessment', 'A comprehensive assessment of your physical, mental, and lifestyle health.', NOW());

--
-- Dumping data for table `questions`
--

INSERT INTO `questions` (`id`, `quiz_id`, `question`, `points`, `created_at`) VALUES
(1, 1, 'How often do you engage in physical exercise?', 10, NOW()),
(2, 1, 'How many hours of sleep do you get on average?', 10, NOW()),
(3, 1, 'How many glasses of water do you drink daily?', 10, NOW()),
(4, 1, 'How often do you eat fruits and vegetables?', 10, NOW()),
(5, 1, 'How often do you feel stressed or anxious?', 10, NOW()),
(6, 1, 'How often do you practice mindfulness or meditation?', 10, NOW()),
(7, 1, 'How would you rate your overall mood?', 10, NOW()),
(8, 1, 'How often do you take breaks during work?', 10, NOW()),
(9, 1, 'How would you rate your work-life balance?', 10, NOW()),
(10, 1, 'How often do you engage in hobbies or leisure activities?', 10, NOW()),
(11, 1, 'How often do you take vacations or time off?', 10, NOW()),
(12, 1, 'How often do you maintain social connections?', 10, NOW());

--
-- Dumping data for table `answers`
--

INSERT INTO `answers` (`id`, `question_id`, `answer`, `is_correct`, `created_at`) VALUES
-- Physical Health Questions
(1, 1, 'Daily', 1, NOW()),
(2, 1, '3-4 times a week', 0, NOW()),
(3, 1, '1-2 times a week', 0, NOW()),
(4, 1, 'Rarely or never', 0, NOW()),

(5, 2, '7-8 hours', 1, NOW()),
(6, 2, '6 hours', 0, NOW()),
(7, 2, '5 hours or less', 0, NOW()),
(8, 2, 'More than 8 hours', 0, NOW()),

(9, 3, '8 or more glasses', 1, NOW()),
(10, 3, '6-7 glasses', 0, NOW()),
(11, 3, '4-5 glasses', 0, NOW()),
(12, 3, 'Less than 4 glasses', 0, NOW()),

(13, 4, 'Daily', 1, NOW()),
(14, 4, '4-6 times a week', 0, NOW()),
(15, 4, '1-3 times a week', 0, NOW()),
(16, 4, 'Rarely or never', 0, NOW()),

-- Mental Health Questions
(17, 5, 'Rarely or never', 1, NOW()),
(18, 5, 'Sometimes', 0, NOW()),
(19, 5, 'Often', 0, NOW()),
(20, 5, 'Very often', 0, NOW()),

(21, 6, 'Daily', 1, NOW()),
(22, 6, '3-4 times a week', 0, NOW()),
(23, 6, '1-2 times a week', 0, NOW()),
(24, 6, 'Rarely or never', 0, NOW()),

(25, 7, 'Very good', 1, NOW()),
(26, 7, 'Good', 0, NOW()),
(27, 7, 'Fair', 0, NOW()),
(28, 7, 'Poor', 0, NOW()),

(29, 8, 'Every hour', 1, NOW()),
(30, 8, 'Every 2 hours', 0, NOW()),
(31, 8, 'Every 3-4 hours', 0, NOW()),
(32, 8, 'Rarely or never', 0, NOW()),

-- Lifestyle Questions
(33, 9, 'Excellent', 1, NOW()),
(34, 9, 'Good', 0, NOW()),
(35, 9, 'Fair', 0, NOW()),
(36, 9, 'Poor', 0, NOW()),

(37, 10, 'Daily', 1, NOW()),
(38, 10, '3-4 times a week', 0, NOW()),
(39, 10, '1-2 times a week', 0, NOW()),
(40, 10, 'Rarely or never', 0, NOW()),

(41, 11, 'Every 3-4 months', 1, NOW()),
(42, 11, 'Every 6 months', 0, NOW()),
(43, 11, 'Once a year', 0, NOW()),
(44, 11, 'Rarely or never', 0, NOW()),

(45, 12, 'Daily', 1, NOW()),
(46, 12, '3-4 times a week', 0, NOW()),
(47, 12, '1-2 times a week', 0, NOW()),
(48, 12, 'Rarely or never', 0, NOW());

--
-- Indexes for dumped tables
--

--
-- Indexes for table `answers`
--
ALTER TABLE `answers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `question_id` (`question_id`);

--
-- Indexes for table `questions`
--
ALTER TABLE `questions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `quiz_id` (`quiz_id`);

--
-- Indexes for table `quizzes`
--
ALTER TABLE `quizzes`
  ADD PRIMARY KEY (`quiz_id`);

--
-- Indexes for table `quiz_answers`
--
ALTER TABLE `quiz_answers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `quiz_result_id` (`quiz_result_id`);

--
-- Indexes for table `quiz_results`
--
ALTER TABLE `quiz_results`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `scores`
--
ALTER TABLE `scores`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `answers`
--
ALTER TABLE `answers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=49;

--
-- AUTO_INCREMENT for table `questions`
--
ALTER TABLE `questions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `quizzes`
--
ALTER TABLE `quizzes`
  MODIFY `quiz_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `quiz_answers`
--
ALTER TABLE `quiz_answers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `quiz_results`
--
ALTER TABLE `quiz_results`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `scores`
--
ALTER TABLE `scores`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `answers`
--
ALTER TABLE `answers`
  ADD CONSTRAINT `answers_ibfk_1` FOREIGN KEY (`question_id`) REFERENCES `questions` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `questions`
--
ALTER TABLE `questions`
  ADD CONSTRAINT `questions_ibfk_1` FOREIGN KEY (`quiz_id`) REFERENCES `quizzes` (`quiz_id`) ON DELETE CASCADE;

--
-- Constraints for table `quiz_answers`
--
ALTER TABLE `quiz_answers`
  ADD CONSTRAINT `quiz_answers_ibfk_1` FOREIGN KEY (`quiz_result_id`) REFERENCES `quiz_results` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `quiz_results`
--
ALTER TABLE `quiz_results`
  ADD CONSTRAINT `quiz_results_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `scores`
--
ALTER TABLE `scores`
  ADD CONSTRAINT `scores_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
