-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 27, 2025 at 06:28 AM
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
-- Table structure for table `budgets`
--

CREATE TABLE `budgets` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `category` varchar(50) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `period` enum('Weekly','Monthly','Yearly') NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `budgets`
--

INSERT INTO `budgets` (`id`, `user_id`, `category`, `amount`, `period`, `created_at`, `updated_at`) VALUES
(1, 4, 'transportation', 2000.00, 'Weekly', '2025-05-27 02:25:42', '2025-05-27 02:25:42'),
(2, 4, 'transportation', 2000.00, 'Weekly', '2025-05-27 02:29:01', '2025-05-27 02:29:01'),
(3, 4, 'transportation', 2000.00, 'Weekly', '2025-05-27 02:31:26', '2025-05-27 02:31:26');

-- --------------------------------------------------------

--
-- Table structure for table `expenses`
--

CREATE TABLE `expenses` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `description` varchar(255) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `category` varchar(50) NOT NULL,
  `date` date NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `expenses`
--

INSERT INTO `expenses` (`id`, `user_id`, `description`, `amount`, `category`, `date`, `created_at`, `updated_at`) VALUES
(1, 4, 'test', 10000.00, 'Housing', '2025-05-27', '2025-05-27 01:13:02', '2025-05-27 01:13:02');

-- --------------------------------------------------------

--
-- Table structure for table `financial_goals`
--

CREATE TABLE `financial_goals` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `target_amount` decimal(10,2) NOT NULL,
  `current_amount` decimal(10,2) NOT NULL DEFAULT 0.00,
  `deadline` date NOT NULL,
  `description` text DEFAULT NULL,
  `status` varchar(122) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `financial_goals`
--

INSERT INTO `financial_goals` (`id`, `user_id`, `title`, `target_amount`, `current_amount`, `deadline`, `description`, `status`, `created_at`, `updated_at`) VALUES
(1, 4, 'test', 1000.00, 500.00, '2025-05-27', 'test', 'active', '2025-05-27 00:47:06', '2025-05-27 02:35:16'),
(2, 4, 'test2', 10000.00, 1000.00, '2025-05-27', 'test', '', '2025-05-27 00:47:46', '2025-05-27 00:47:46'),
(3, 4, 'test', 1000.00, 0.00, '2025-05-27', NULL, '', '2025-05-27 02:25:10', '2025-05-27 02:25:10');

-- --------------------------------------------------------

--
-- Table structure for table `financial_transactions`
--

CREATE TABLE `financial_transactions` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `type` enum('income','expense') NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `category` varchar(50) NOT NULL,
  `date` date NOT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `financial_transactions`
--

INSERT INTO `financial_transactions` (`id`, `user_id`, `type`, `amount`, `category`, `date`, `description`, `created_at`) VALUES
(1, 4, 'expense', 1200.00, 'food', '2025-05-27', NULL, '2025-05-27 02:40:14'),
(2, 4, 'expense', 1200.00, 'food', '2025-05-27', NULL, '2025-05-27 02:40:18'),
(3, 4, 'expense', 1200.00, 'food', '2025-05-27', NULL, '2025-05-27 02:40:59'),
(4, 4, 'expense', 1200.00, 'food', '2025-05-27', NULL, '2025-05-27 02:41:05'),
(5, 4, 'expense', 1200.00, 'food', '2025-05-27', NULL, '2025-05-27 02:41:20'),
(6, 4, 'expense', 1200.00, 'food', '2025-05-27', NULL, '2025-05-27 02:41:26'),
(7, 4, 'expense', 1200.00, 'food', '2025-05-27', NULL, '2025-05-27 02:41:45'),
(8, 4, 'income', 50000.00, 'salary', '2025-05-27', NULL, '2025-05-27 02:59:08'),
(9, 4, 'income', 50000.00, 'salary', '2025-05-27', NULL, '2025-05-27 03:02:58'),
(10, 4, 'income', 50000.00, 'salary', '2025-05-27', NULL, '2025-05-27 03:12:45'),
(11, 4, 'income', 50000.00, 'salary', '2025-05-27', NULL, '2025-05-27 03:13:09'),
(12, 4, 'income', 50000.00, 'salary', '2025-05-27', NULL, '2025-05-27 03:13:17');

-- --------------------------------------------------------

--
-- Table structure for table `journal_entries`
--

CREATE TABLE `journal_entries` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `mood` varchar(50) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `nutrition_logs`
--

CREATE TABLE `nutrition_logs` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `meal_type` varchar(50) NOT NULL,
  `food_items` text NOT NULL,
  `calories` int(11) NOT NULL,
  `logged_at` datetime NOT NULL DEFAULT current_timestamp()
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
  `quiz_id` int(11) NOT NULL,
  `score` int(11) NOT NULL,
  `total_points` int(11) NOT NULL,
  `completed_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `recurring_transactions`
--

CREATE TABLE `recurring_transactions` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `type` enum('income','expense') NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `category` varchar(50) NOT NULL,
  `frequency` enum('daily','weekly','monthly','yearly') NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date DEFAULT NULL,
  `description` text DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `relaxation_sessions`
--

CREATE TABLE `relaxation_sessions` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `session_type` varchar(50) NOT NULL,
  `duration` int(11) NOT NULL,
  `completed_at` datetime NOT NULL DEFAULT current_timestamp()
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
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('Admin','User') NOT NULL DEFAULT 'User',
  `last_login` datetime DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `first_name`, `last_name`, `email`, `password`, `role`, `last_login`, `created_at`, `updated_at`) VALUES
(3, 'Admin', 'User', 'admin@ewell.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Admin', NULL, '2025-05-26 13:41:41', NULL),
(4, 'Test', 'User', 'test@ewell.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'User', NULL, '2025-05-26 13:41:41', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `budgets`
--
ALTER TABLE `budgets`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_budgets_user_id` (`user_id`),
  ADD KEY `idx_budgets_category` (`category`);

--
-- Indexes for table `expenses`
--
ALTER TABLE `expenses`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_expenses_user_id` (`user_id`),
  ADD KEY `idx_expenses_date` (`date`),
  ADD KEY `idx_expenses_category` (`category`);

--
-- Indexes for table `financial_goals`
--
ALTER TABLE `financial_goals`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_financial_goals_user_id` (`user_id`),
  ADD KEY `idx_financial_goals_deadline` (`deadline`);

--
-- Indexes for table `financial_transactions`
--
ALTER TABLE `financial_transactions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `journal_entries`
--
ALTER TABLE `journal_entries`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_journal_user` (`user_id`);

--
-- Indexes for table `nutrition_logs`
--
ALTER TABLE `nutrition_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_nutrition_user` (`user_id`);

--
-- Indexes for table `quiz_results`
--
ALTER TABLE `quiz_results`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_quiz_user` (`user_id`);

--
-- Indexes for table `recurring_transactions`
--
ALTER TABLE `recurring_transactions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `relaxation_sessions`
--
ALTER TABLE `relaxation_sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_relaxation_user` (`user_id`);

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
-- AUTO_INCREMENT for table `budgets`
--
ALTER TABLE `budgets`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `expenses`
--
ALTER TABLE `expenses`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `financial_goals`
--
ALTER TABLE `financial_goals`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `financial_transactions`
--
ALTER TABLE `financial_transactions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `journal_entries`
--
ALTER TABLE `journal_entries`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `nutrition_logs`
--
ALTER TABLE `nutrition_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `quiz_results`
--
ALTER TABLE `quiz_results`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `recurring_transactions`
--
ALTER TABLE `recurring_transactions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `relaxation_sessions`
--
ALTER TABLE `relaxation_sessions`
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
-- Constraints for table `budgets`
--
ALTER TABLE `budgets`
  ADD CONSTRAINT `budgets_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `expenses`
--
ALTER TABLE `expenses`
  ADD CONSTRAINT `expenses_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `financial_goals`
--
ALTER TABLE `financial_goals`
  ADD CONSTRAINT `financial_goals_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `financial_transactions`
--
ALTER TABLE `financial_transactions`
  ADD CONSTRAINT `financial_transactions_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `journal_entries`
--
ALTER TABLE `journal_entries`
  ADD CONSTRAINT `fk_journal_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `nutrition_logs`
--
ALTER TABLE `nutrition_logs`
  ADD CONSTRAINT `fk_nutrition_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `quiz_results`
--
ALTER TABLE `quiz_results`
  ADD CONSTRAINT `fk_quiz_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `recurring_transactions`
--
ALTER TABLE `recurring_transactions`
  ADD CONSTRAINT `recurring_transactions_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `relaxation_sessions`
--
ALTER TABLE `relaxation_sessions`
  ADD CONSTRAINT `fk_relaxation_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
