-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Dec 16, 2024 at 01:22 PM
-- Server version: 9.1.0
-- PHP Version: 8.3.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `vocabquiz`
--

-- --------------------------------------------------------

--
-- Table structure for table `options`
--

DROP TABLE IF EXISTS `options`;
CREATE TABLE IF NOT EXISTS `options` (
  `option_id` int NOT NULL AUTO_INCREMENT,
  `question_id` int NOT NULL,
  `option_text` text NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`option_id`),
  KEY `fk_options_question` (`question_id`)
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `options`
--

INSERT INTO `options` (`option_id`, `question_id`, `option_text`, `created_at`, `updated_at`) VALUES
(1, 1, 'Paris', '2024-12-16 20:08:05', '2024-12-16 20:08:05'),
(2, 1, 'Berlin', '2024-12-16 20:08:05', '2024-12-16 20:08:05'),
(3, 1, 'Rome', '2024-12-16 20:08:05', '2024-12-16 20:08:05'),
(4, 1, 'Madrid', '2024-12-16 20:08:05', '2024-12-16 20:08:05'),
(5, 2, 'Earth', '2024-12-16 20:08:05', '2024-12-16 20:08:05'),
(6, 2, 'Mars', '2024-12-16 20:08:05', '2024-12-16 20:08:05'),
(7, 2, 'Jupiter', '2024-12-16 20:08:05', '2024-12-16 20:08:05'),
(8, 2, 'Saturn', '2024-12-16 20:08:05', '2024-12-16 20:08:05'),
(9, 3, 'Harper Lee', '2024-12-16 20:08:05', '2024-12-16 20:08:05'),
(10, 3, 'Mark Twain', '2024-12-16 20:08:05', '2024-12-16 20:08:05'),
(11, 3, 'Ernest Hemingway', '2024-12-16 20:08:05', '2024-12-16 20:08:05'),
(12, 3, 'F. Scott Fitzgerald', '2024-12-16 20:08:05', '2024-12-16 20:08:05'),
(13, 4, 'Assessing company strengths and weaknesses', '2024-12-16 20:08:05', '2024-12-16 20:08:05'),
(14, 4, 'Financial auditing', '2024-12-16 20:08:05', '2024-12-16 20:08:05'),
(15, 4, 'Market research', '2024-12-16 20:08:05', '2024-12-16 20:08:05'),
(16, 4, 'Human resource management', '2024-12-16 20:08:05', '2024-12-16 20:08:05'),
(17, 5, 'Joseph Schumpeter', '2024-12-16 20:08:05', '2024-12-16 20:08:05'),
(18, 5, 'Michael Porter', '2024-12-16 20:08:05', '2024-12-16 20:08:05'),
(19, 5, 'Peter Drucker', '2024-12-16 20:08:05', '2024-12-16 20:08:05'),
(20, 5, 'Philip Kotler', '2024-12-16 20:08:05', '2024-12-16 20:08:05'),
(21, 6, 'Key Performance Indicator', '2024-12-16 20:08:05', '2024-12-16 20:08:05'),
(22, 6, 'Knowledge Process Integration', '2024-12-16 20:08:05', '2024-12-16 20:08:05'),
(23, 6, 'Key Project Implementation', '2024-12-16 20:08:05', '2024-12-16 20:08:05'),
(24, 6, 'Knowledge Performance Index', '2024-12-16 20:08:05', '2024-12-16 20:08:05');

-- --------------------------------------------------------

--
-- Table structure for table `questions`
--

DROP TABLE IF EXISTS `questions`;
CREATE TABLE IF NOT EXISTS `questions` (
  `question_id` int NOT NULL AUTO_INCREMENT,
  `quiz_id` int NOT NULL,
  `question_text` text NOT NULL,
  `correct_option_id` int DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`question_id`),
  KEY `fk_questions_quiz` (`quiz_id`),
  KEY `fk_questions_correct_option` (`correct_option_id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `questions`
--

INSERT INTO `questions` (`question_id`, `quiz_id`, `question_text`, `correct_option_id`, `created_at`, `updated_at`) VALUES
(1, 1, 'What is the capital of France?', 1, '2024-12-16 20:08:05', '2024-12-16 20:08:05'),
(2, 1, 'Which planet is known as the Red Planet?', 6, '2024-12-16 20:08:05', '2024-12-16 20:08:05'),
(3, 1, 'Who wrote \"To Kill a Mockingbird\"?', 9, '2024-12-16 20:08:05', '2024-12-16 20:08:05'),
(4, 2, 'What is SWOT analysis used for?', 13, '2024-12-16 20:08:05', '2024-12-16 20:08:05'),
(5, 2, 'Who is known for the theory of \"Creative Destruction\"?', 17, '2024-12-16 20:08:05', '2024-12-16 20:08:05'),
(6, 2, 'What does KPI stand for?', 21, '2024-12-16 20:08:05', '2024-12-16 20:08:05');

-- --------------------------------------------------------

--
-- Table structure for table `quizzes`
--

DROP TABLE IF EXISTS `quizzes`;
CREATE TABLE IF NOT EXISTS `quizzes` (
  `quiz_id` int NOT NULL AUTO_INCREMENT,
  `quiz_title` varchar(255) NOT NULL,
  `description` text,
  `category` enum('easy','hard','business') NOT NULL,
  `created_by` int NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`quiz_id`),
  KEY `fk_quizzes_created_by` (`created_by`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `quizzes`
--

INSERT INTO `quizzes` (`quiz_id`, `quiz_title`, `description`, `category`, `created_by`, `created_at`, `updated_at`) VALUES
(1, 'General Knowledge', 'A quiz to test your general knowledge across various topics.', 'easy', 1, '2024-12-16 20:08:05', '2024-12-16 20:08:05'),
(2, 'Business Strategies', 'Assess your understanding of key business strategies and concepts.', 'business', 1, '2024-12-16 20:08:05', '2024-12-16 20:08:05');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `user_id` int NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `full_name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `role` enum('admin','student','professional') NOT NULL DEFAULT 'student',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`user_id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `username`, `password`, `full_name`, `email`, `role`, `created_at`, `updated_at`) VALUES
(1, 'admin', 'admin123', 'Admin User', 'admin@example.com', 'admin', '2024-12-16 20:08:05', '2024-12-16 20:08:05'),
(2, 'student', 'student123', 'Student User', 'student@example.com', 'student', '2024-12-16 20:08:05', '2024-12-16 20:08:05'),
(3, 'professional', 'professional123', 'Professional User', 'professional@example.com', 'professional', '2024-12-16 20:08:05', '2024-12-16 20:08:05');

-- --------------------------------------------------------

--
-- Table structure for table `user_answers`
--

DROP TABLE IF EXISTS `user_answers`;
CREATE TABLE IF NOT EXISTS `user_answers` (
  `answer_id` int NOT NULL AUTO_INCREMENT,
  `attempt_id` int NOT NULL,
  `question_id` int NOT NULL,
  `chosen_option_id` int NOT NULL,
  `correct` tinyint(1) NOT NULL DEFAULT '0',
  `answered_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`answer_id`),
  KEY `fk_answers_attempt` (`attempt_id`),
  KEY `fk_answers_question` (`question_id`),
  KEY `fk_answers_option` (`chosen_option_id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `user_answers`
--

INSERT INTO `user_answers` (`answer_id`, `attempt_id`, `question_id`, `chosen_option_id`, `correct`, `answered_at`) VALUES
(1, 1, 1, 1, 1, '2024-01-10 10:05:00'),
(2, 1, 2, 6, 1, '2024-01-10 10:10:00'),
(3, 1, 3, 9, 1, '2024-01-10 10:15:00'),
(4, 2, 4, 14, 0, '2024-02-12 14:35:00'),
(5, 2, 5, 17, 1, '2024-02-12 14:40:00'),
(6, 2, 6, 22, 0, '2024-02-12 14:50:00'),
(7, 3, 1, 1, 1, '2024-03-05 09:25:00'),
(8, 3, 2, 6, 1, '2024-03-05 09:30:00'),
(9, 3, 3, 9, 1, '2024-03-05 09:35:00');

-- --------------------------------------------------------

--
-- Table structure for table `user_quiz_attempts`
--

DROP TABLE IF EXISTS `user_quiz_attempts`;
CREATE TABLE IF NOT EXISTS `user_quiz_attempts` (
  `attempt_id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `quiz_id` int NOT NULL,
  `start_time` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `end_time` datetime DEFAULT NULL,
  `score` decimal(5,2) DEFAULT NULL,
  `completed` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`attempt_id`),
  KEY `fk_attempts_user` (`user_id`),
  KEY `fk_attempts_quiz` (`quiz_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `user_quiz_attempts`
--

INSERT INTO `user_quiz_attempts` (`attempt_id`, `user_id`, `quiz_id`, `start_time`, `end_time`, `score`, `completed`) VALUES
(1, 2, 1, '2024-01-10 10:00:00', '2024-01-10 10:15:00', 3.00, 1),
(2, 2, 2, '2024-02-12 14:30:00', '2024-02-12 14:50:00', 2.00, 1),
(3, 3, 1, '2024-03-05 09:20:00', '2024-03-05 09:35:00', 4.00, 1);

--
-- Constraints for dumped tables
--

--
-- Constraints for table `options`
--
ALTER TABLE `options`
  ADD CONSTRAINT `fk_options_question` FOREIGN KEY (`question_id`) REFERENCES `questions` (`question_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `questions`
--
ALTER TABLE `questions`
  ADD CONSTRAINT `fk_questions_correct_option` FOREIGN KEY (`correct_option_id`) REFERENCES `options` (`option_id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_questions_quiz` FOREIGN KEY (`quiz_id`) REFERENCES `quizzes` (`quiz_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `quizzes`
--
ALTER TABLE `quizzes`
  ADD CONSTRAINT `fk_quizzes_created_by` FOREIGN KEY (`created_by`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `user_answers`
--
ALTER TABLE `user_answers`
  ADD CONSTRAINT `fk_answers_attempt` FOREIGN KEY (`attempt_id`) REFERENCES `user_quiz_attempts` (`attempt_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_answers_option` FOREIGN KEY (`chosen_option_id`) REFERENCES `options` (`option_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_answers_question` FOREIGN KEY (`question_id`) REFERENCES `questions` (`question_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `user_quiz_attempts`
--
ALTER TABLE `user_quiz_attempts`
  ADD CONSTRAINT `fk_attempts_quiz` FOREIGN KEY (`quiz_id`) REFERENCES `quizzes` (`quiz_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_attempts_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
