-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 07, 2025 at 04:48 PM
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
-- Table structure for table `announcements`
--

CREATE TABLE `announcements` (
  `announ_id` int(12) NOT NULL,
  `title` varchar(255) NOT NULL,
  `content` varchar(255) NOT NULL,
  `status` varchar(255) NOT NULL,
  `created_by` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

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
(0, 1, '2 cups', 0, '2025-05-31 14:30:27'),
(0, 1, '4 glasses', 0, '2025-05-31 14:30:27'),
(0, 1, '8 glasses', 1, '2025-05-31 14:30:27'),
(0, 1, '12 glasses', 0, '2025-05-31 14:30:27'),
(0, 2, 'Vitamin A', 0, '2025-05-31 14:30:27'),
(0, 2, 'Vitamin C', 0, '2025-05-31 14:30:27'),
(0, 2, 'Vitamin D', 1, '2025-05-31 14:30:27'),
(0, 2, 'Vitamin E', 0, '2025-05-31 14:30:27'),
(0, 3, 'Heart', 0, '2025-05-31 14:30:27'),
(0, 3, 'Kidney', 0, '2025-05-31 14:30:27'),
(0, 3, 'Liver', 1, '2025-05-31 14:30:27'),
(0, 3, 'Lungs', 0, '2025-05-31 14:30:27'),
(0, 4, 'Biceps', 0, '2025-05-31 14:30:27'),
(0, 4, 'Heart', 0, '2025-05-31 14:30:27'),
(0, 4, 'Masseter (jaw)', 1, '2025-05-31 14:30:27'),
(0, 4, 'Gluteus Maximus', 0, '2025-05-31 14:30:27'),
(0, 5, 'Iron', 0, '2025-05-31 14:30:27'),
(0, 5, 'Calcium', 1, '2025-05-31 14:30:27'),
(0, 5, 'Zinc', 0, '2025-05-31 14:30:27'),
(0, 5, 'Magnesium', 0, '2025-05-31 14:30:27');

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
-- Table structure for table `lecture_videos`
--

CREATE TABLE `lecture_videos` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `video_url` varchar(255) NOT NULL,
  `category` enum('Physical Health','Mental Health','Nutrition','Exercise','Wellness') NOT NULL,
  `thumbnail_url` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `lecture_videos`
--

INSERT INTO `lecture_videos` (`id`, `title`, `description`, `video_url`, `category`, `thumbnail_url`, `created_at`, `updated_at`) VALUES
(3, 'Multo - Cup of Joe (Official Lyric Video)', 'Minulto ako ng damdamin ko :(', 'https://www.youtube.com/embed/Rht8rS4cR1s', 'Mental Health', 'https://img.youtube.com/vi/Rht8rS4cR1s/maxresdefault.jpg', '2025-06-07 13:22:01', '2025-06-07 13:22:01'),
(4, 'Basshunter - Vi sitter i ventrilo och spelar DotA', 'Duta 2', 'https://www.youtube.com/embed/aTJncWndUB8', 'Exercise', 'https://img.youtube.com/vi/aTJncWndUB8/mqdefault.jpg', '2025-06-07 14:18:02', '2025-06-07 14:18:02'),
(5, 'DotA - WoDotA Top10 Weekly Vol.104', 'ban ggaurd', 'https://www.youtube.com/embed/OMLd2S9UrwY', 'Physical Health', 'https://img.youtube.com/vi/OMLd2S9UrwY/maxresdefault.jpg', '2025-06-07 14:19:34', '2025-06-07 14:19:34'),
(7, 'Super Saiyan Goku vs Beerus (FLOW - Hero)', 'hero', 'https://www.youtube.com/embed/Ue5A_5zJPUc', 'Wellness', 'https://img.youtube.com/vi/Ue5A_5zJPUc/maxresdefault.jpg', '2025-06-07 14:32:47', '2025-06-07 14:32:47');

-- --------------------------------------------------------

--
-- Table structure for table `news_articles`
--

CREATE TABLE `news_articles` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `content` text DEFAULT NULL,
  `category` enum('World News','Local News') NOT NULL,
  `status` enum('Active','Inactive','Draft','Archived') NOT NULL DEFAULT 'Draft',
  `publication_date` date DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `author` varchar(100) DEFAULT NULL,
  `summary` text DEFAULT NULL,
  `image_url` varchar(500) DEFAULT NULL,
  `slug` varchar(255) DEFAULT NULL,
  `news_link` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `news_articles`
--

INSERT INTO `news_articles` (`id`, `title`, `content`, `category`, `status`, `publication_date`, `created_at`, `updated_at`, `author`, `summary`, `image_url`, `slug`, `news_link`) VALUES
(2, 'Testing of the News', 'Content of this is hooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooo', 'Local News', 'Inactive', '2025-05-29', '2025-05-29 15:24:11', '2025-05-30 00:46:23', '', 'Summary of hoooooooooooooooooooooooooooooo', 'uploads/news/68387c1bc0f75.webp', 'testing-of-the-news', ''),
(3, 'Test of this', 'test drove vehicle vehicle vehicle vehicle vehicle vehicle', 'World News', 'Inactive', '2025-05-29', '2025-05-29 15:38:50', '2025-05-29 17:21:42', '', 'test vehicle vehicle vehicle vehicle', 'uploads/news/68387f8a2be40.jpg', 'test-of-this', ''),
(5, 'SOCCSKSARGEN sets new standard for immunization', 'KORONADAL CITY, South Cotabato (PIA) — A United Nations Children’s Fund official commended the Department of Health-Center for Health Development (DOH-CHD) in Region 12 for initiating the SOCCSKSARGEN Regional Immunization Coalition (SRIC), the first of its kind in the Philippines.\\\\\\\\\\\\\\\\r\\\\\\\\\\\\\\\\n\\\\\\\\\\\\\\\\r\\\\\\\\\\\\\\\\nDr. Carla Orozco, an immunization specialist with UNICEF Philippines, described the SRIC as a “very important initiative.”\\\\\\\\\\\\\\\\r\\\\\\\\\\\\\\\\n\\\\\\\\\\\\\\\\r\\\\\\\\\\\\\\\\n“Your groundbreaking initiative is a good testament of partners coming together and showcasing it to other regions for them to also learn from,” Orozco said during a recent SRIC steering committee forum in Davao City.\\\\\\\\\\\\\\\\r\\\\\\\\\\\\\\\\n\\\\\\\\\\\\\\\\r\\\\\\\\\\\\\\\\n“This is how we envision the immunization program to be—this is not the sole responsibility of the health sector or of the Department of Health. Really, it would take a village to raise a child, and that would take different stakeholders to support the government’s program to improve coverage,” Orozco added.\\\\\\\\\\\\\\\\r\\\\\\\\\\\\\\\\n\\\\\\\\\\\\\\\\r\\\\\\\\\\\\\\\\nUNICEF, she emphasized, “is genuinely enthusiastic about this pioneering strategy, which also aligns with our global commitment to ensuring that every child receives the lifesaving vaccines they need.”\\\\\\\\\\\\\\\\r\\\\\\\\\\\\\\\\n\\\\\\\\\\\\\\\\r\\\\\\\\\\\\\\\\nOrozco pledged UNICEF’s consistent technical assistance and support to the coalition.\\\\\\\\\\\\\\\\r\\\\\\\\\\\\\\\\n\\\\\\\\\\\\\\\\r\\\\\\\\\\\\\\\\nDr. Edvir Jane Montañer, DOH-CHD medical officer and regional program manager for the National Immunization Program, said the SRIC is a unified, multi-sectoral body designed to strengthen immunization programs and ensure equitable vaccine access in Region 12.\\\\\\\\\\\\\\\\r\\\\\\\\\\\\\\\\n\\\\\\\\\\\\\\\\r\\\\\\\\\\\\\\\\n“The specific functions of the SRIC are to: strengthen immunization governance and policy integration; support service delivery and workforce capacity; and support demand generation and community engagement with a focus on gender equity,” Montañer said.\\\\\\\\\\\\\\\\r\\\\\\\\\\\\\\\\n\\\\\\\\\\\\\\\\r\\\\\\\\\\\\\\\\nSRIC comprises government agencies, non-governmental organizations, academic institutions, private sector partners, the business community, faith-based organizations, cultural groups, civil society organizations, women’s and youth groups, local government units, LGU-based leagues and various immunization and health partners.\\\\\\\\\\\\\\\\r\\\\\\\\\\\\\\\\n\\\\\\\\\\\\\\\\r\\\\\\\\\\\\\\\\nFrom March 25 to 28, DOH-CHD Soccsksargen and UNICEF convened coalition member agency heads and SRIC focal persons in Davao City to finalize the SRIC’s structure and functions, along with member agency and partner roles and deliverables, before the coalition’s April 30, 2025, launch.\\\\\\\\\\\\\\\\r\\\\\\\\\\\\\\\\n\\\\\\\\\\\\\\\\r\\\\\\\\\\\\\\\\nThe proposal to establish the coalition arose during the SOCCSKSARGEN Immunization Summit in February 2024. The alliance was further strengthened through an intersectoral meeting in June, a symposium on immunization featuring local chief executives in September and coalition-building workshops in December 2024 and January 2025.\\\\\\\\\\\\\\\\r\\\\\\\\\\\\\\\\n\\\\\\\\\\\\\\\\r\\\\\\\\\\\\\\\\nThe SRIC was featured during the National Immunization Summit on Jan. 30, 2025.\\\\\\\\\\\\\\\\r\\\\\\\\\\\\\\\\n\\\\\\\\\\\\\\\\r\\\\\\\\\\\\\\\\nDOH-CHD data indicates that from 2019 to 2024, SOCCSKSARGEN Region achieved only 61 percent immunization coverage, leaving about 200,000 children unvaccinated.\\\\\\\\\\\\\\\\r\\\\\\\\\\\\\\\\n\\\\\\\\\\\\\\\\r\\\\\\\\\\\\\\\\nThese gaps, DOH-CHD 12 noted, resulted in pockets of vaccine-preventable diseases in 2023 and 2024.\\\\\\\\\\\\\\\\r\\\\\\\\\\\\\\\\n\\\\\\\\\\\\\\\\r\\\\\\\\\\\\\\\\nMontañer said the multifaceted gaps and challenges in the immunization program require a collective, multisectoral approach, highlighting the need for the SRIC. (DED, PIA Region 12)', 'World News', 'Active', '2025-05-29', '2025-05-29 15:51:59', '2025-05-30 00:17:54', 'hey', 'testTest Test', 'uploads/news/6838829fc6ad4.jpg', 'soccsksargen-sets-new-standard-for-immunization', ''),
(6, 'Hello', 'Hi', 'World News', 'Draft', '2025-05-30', '2025-05-29 17:09:27', '2025-05-30 00:48:45', '', 'Test', 'uploads/news/683894c7dbf00.jpg', 'hello', ''),
(7, 'Woh', 'Hehe', 'Local News', 'Active', '2025-05-29', '2025-05-29 17:29:32', '2025-05-29 17:29:32', 'Test', 'Hoho', 'uploads/news/6838997c06750.png', 'woh', ''),
(8, 'Heeheh', 'Henlooooooo', 'World News', 'Archived', '2025-05-30', '2025-05-30 00:19:24', '2025-05-30 00:59:55', 'someoen', 'Hi', 'uploads/news/6838f98cba826.jpg', 'heeheh', '');

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
(1, 1, 'How much water is recommended for an average adult to drink daily?', 1, '2025-05-31 14:30:13'),
(2, 1, 'What vitamin does the body produce from sunlight?', 1, '2025-05-31 14:30:13'),
(3, 1, 'Which organ detoxifies the blood?', 1, '2025-05-31 14:30:13'),
(4, 1, 'What is the strongest muscle in the human body by weight?', 1, '2025-05-31 14:30:13'),
(5, 1, 'Which mineral is essential for strong bones and teeth?', 1, '2025-05-31 14:30:13');

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
(1, 'Health Trivia Quiz', 'A fun and educational quiz about health and wellness.', '2025-05-31 14:29:59');

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
-- Indexes for table `announcements`
--
ALTER TABLE `announcements`
  ADD PRIMARY KEY (`announ_id`);

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
-- Indexes for table `lecture_videos`
--
ALTER TABLE `lecture_videos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_category` (`category`),
  ADD KEY `idx_created_at` (`created_at`);

--
-- Indexes for table `news_articles`
--
ALTER TABLE `news_articles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`),
  ADD KEY `idx_category` (`category`),
  ADD KEY `idx_status` (`status`),
  ADD KEY `idx_publication_date` (`publication_date`);

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
-- AUTO_INCREMENT for table `announcements`
--
ALTER TABLE `announcements`
  MODIFY `announ_id` int(12) NOT NULL AUTO_INCREMENT;

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
-- AUTO_INCREMENT for table `lecture_videos`
--
ALTER TABLE `lecture_videos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `news_articles`
--
ALTER TABLE `news_articles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

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
