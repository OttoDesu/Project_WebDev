-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 07, 2026 at 09:26 AM
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
-- Database: `job_board`
--

-- --------------------------------------------------------

--
-- Table structure for table `applications`
--

CREATE TABLE `applications` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `job_id` int(11) NOT NULL,
  `cover_letter` text NOT NULL,
  `status` enum('Pending','Accepted','Rejected') NOT NULL DEFAULT 'Pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `full_name` varchar(255) DEFAULT NULL,
  `email` varchar(120) DEFAULT NULL,
  `phone_number` varchar(50) DEFAULT NULL,
  `resume` varchar(255) DEFAULT NULL,
  `applied_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `applications`
--

INSERT INTO `applications` (`id`, `user_id`, `job_id`, `cover_letter`, `status`, `created_at`, `full_name`, `email`, `phone_number`, `resume`, `applied_at`) VALUES
(1, 2, 1, 'I want this job', 'Rejected', '2026-01-06 17:26:01', NULL, NULL, NULL, NULL, '2026-01-07 05:52:32'),
(2, 2, 2, '', 'Pending', '2026-01-07 05:55:39', 'Mashitah', 'mashi@example.com', '019999999', 'uploads/resumes/resume_2_1767765339.pdf', '2026-01-07 05:55:39');

-- --------------------------------------------------------

--
-- Table structure for table `contact_messages`
--

CREATE TABLE `contact_messages` (
  `id` int(11) NOT NULL,
  `name` varchar(150) NOT NULL,
  `email` varchar(150) NOT NULL,
  `message` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `contact_messages`
--

INSERT INTO `contact_messages` (`id`, `name`, `email`, `message`, `created_at`) VALUES
(1, 'Mashitah', 'mashi@example.com', 'Is it real?', '2026-01-07 07:51:43');

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` int(11) NOT NULL,
  `title` varchar(150) NOT NULL,
  `description` text NOT NULL,
  `salary` varchar(120) DEFAULT NULL,
  `location` varchar(120) DEFAULT NULL,
  `closing_date` date DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `requirements` text DEFAULT NULL,
  `benefits` text DEFAULT NULL,
  `company` varchar(150) DEFAULT 'Company'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `jobs`
--

INSERT INTO `jobs` (`id`, `title`, `description`, `salary`, `location`, `closing_date`, `created_by`, `created_at`, `requirements`, `benefits`, `company`) VALUES
(1, 'Associate Officer (Card Ops-Application Processing)', 'The candidate should have a Diploma, preferably 1 - 2 years of experience. Fresh graduates are encouraged to apply.', 'RM 3,000 - RM 3,500', 'Kuala Lumpur', '2026-02-28', NULL, '2026-01-06 16:49:43', 'Diploma Holder\r\n\r\nPreferably 1 - 2 years of relevant experience.\r\n\r\nAble to multi-task and cope with change and diversity in a fast-paced environment\r\n\r\nPossess strong analytical and problem-solving skills, able to think objectively and \'think outside the box\' when analyzing issues\r\n\r\nMeticulous with an eye for details and quality mindset\r\n\r\nStrong cross-functional collaboration and communications skills, driving outcomes through influence and negotiations\r\n\r\nProficient in the use of Microsoft Office, specifically Microsoft Excel, Word & PowerPoint\r\n\r\nTeam player, self-motivated and resourceful', 'AL 15 days\r\nBirthday Leave', 'XYZ Truly Agency'),
(2, 'Software Engineer', 'We are looking for a passionate software engineer with experience in Java and React. Minimum 2 years experience required.', 'RM 4,500 - RM 6,000', 'Petaling Jaya', '2026-03-10', NULL, '2026-01-06 16:49:43', NULL, NULL, 'Company'),
(3, 'Marketing Executive', 'Join our marketing team to promote our brand. Strong communication and social media skills are a must.', 'RM 3,000 - RM 4,000', 'Subang Jaya', '2026-03-15', NULL, '2026-01-06 16:49:43', NULL, NULL, 'Company'),
(4, 'HR Specialist', 'Assist with recruitment, employee relations, and payroll management. 2 years of HR experience preferred.', 'RM 4,000 - RM 5,500', 'Kuala Lumpur', '2026-03-20', NULL, '2026-01-06 16:49:43', NULL, NULL, 'Company'),
(5, 'Business Analyst', 'Analyze business needs, define requirements, and deliver effective solutions. Must have strong problem-solving skills.', 'RM 5,000 - RM 6,500', 'Cyberjaya', '2026-03-25', NULL, '2026-01-06 16:49:43', NULL, NULL, 'Company'),
(6, 'Sales Manager', 'Lead the sales team to achieve company sales targets. 3 years of experience in a leadership role required.', 'RM 6,000 - RM 8,000', 'Penang', '2026-04-01', NULL, '2026-01-06 16:49:43', NULL, NULL, 'Company');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(120) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `role` enum('admin','user') NOT NULL DEFAULT 'user',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password_hash`, `role`, `created_at`) VALUES
(1, 'Admin', 'admin@example.com', '$2y$10$OMS1vgvzlfzBx2O1ZBIg3emzasYc25aUDfbQ4qvidA6jZZa4IfBSO', 'admin', '2026-01-06 16:27:08'),
(2, 'Mashitah', 'mashi@example.com', '$2y$10$j3ATywiEB3oX7AoANMbSzuEyVt6n8b9HytKaYX6yWycskJgB7EYhe', 'user', '2026-01-06 16:40:24');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `applications`
--
ALTER TABLE `applications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `job_id` (`job_id`);

--
-- Indexes for table `contact_messages`
--
ALTER TABLE `contact_messages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `created_by` (`created_by`);

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
-- AUTO_INCREMENT for table `applications`
--
ALTER TABLE `applications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `contact_messages`
--
ALTER TABLE `contact_messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `applications`
--
ALTER TABLE `applications`
  ADD CONSTRAINT `applications_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `applications_ibfk_2` FOREIGN KEY (`job_id`) REFERENCES `jobs` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `jobs`
--
ALTER TABLE `jobs`
  ADD CONSTRAINT `jobs_ibfk_1` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
