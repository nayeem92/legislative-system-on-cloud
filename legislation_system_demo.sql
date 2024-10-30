-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 28, 2024 at 12:34 PM
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
-- Database: `legislation_system_demo`
--

-- --------------------------------------------------------

--
-- Table structure for table `amendments`
--

CREATE TABLE `amendments` (
  `amendment_id` int(11) NOT NULL,
  `bill_id` int(11) NOT NULL,
  `reviewer_id` int(11) NOT NULL,
  `suggested_title` varchar(255) DEFAULT NULL,
  `suggested_description` text DEFAULT NULL,
  `comments` text DEFAULT NULL,
  `status` enum('Pending','Accepted','Rejected') DEFAULT 'Pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `amendments`
--

INSERT INTO `amendments` (`amendment_id`, `bill_id`, `reviewer_id`, `suggested_title`, `suggested_description`, `comments`, `status`, `created_at`) VALUES
(2, 4, 2, 'Climate act', ' The Climate Action and Clean Energy Act aims to reduce carbon emissions and promote renewable energy use through incentives and stricter environmental regulations. It targets a 40% carbon reduction by 2031.', 'changed the year of completion', 'Accepted', '2024-10-26 16:44:47'),
(3, 4, 2, 'Climate act', 'The Climate Action and Clean Energy Act aims to reduce carbon emissions and promote renewable energy use through incentives and stricter environmental regulations. It targets a 40% carbon reduction by 2031.', 'Changed year', 'Rejected', '2024-10-26 17:22:31'),
(4, 4, 2, 'Climate act', 'The Climate Action and Clean Energy Act aims to reduce carbon emissions and promote renewable energy use through incentives and stricter environmental regulations. It targets a 40% carbon reduction by 2031.', 'change in year', 'Accepted', '2024-10-26 17:26:41'),
(5, 4, 2, 'Climate act', 'The Climate Action and Clean Energy Act aims to reduce carbon emissions and promote renewable energy use through incentives and stricter environmental regulations. It targets a 40% carbon reduction by 2031.', 'Changed year', 'Accepted', '2024-10-26 18:04:40'),
(6, 4, 2, 'Climate act', 'The Climate Action and Clean Energy Act aims to reduce carbon emissions and promote renewable energy use through incentives and stricter environmental regulations. It targets a 40% carbon reduction by 2030.', 'Changed the year', 'Rejected', '2024-10-26 18:05:35'),
(7, 4, 2, 'Climate act', ' The Climate Action and Clean Energy Act aims to reduce carbon emissions and promote renewable energy use through incentives and stricter environmental regulations. It targets a 40% carbon reduction by 2031.', 'year', 'Accepted', '2024-10-26 18:06:31'),
(8, 8, 2, 'Clean act 2', 'Clean environment', 'few changes', 'Rejected', '2024-10-28 10:23:10'),
(9, 8, 2, 'Clean act 2', 'Clean environment', 'few changes', 'Accepted', '2024-10-28 10:26:57'),
(10, 8, 2, 'Clean act ', 'Clean environment,', 'changed back', 'Accepted', '2024-10-28 10:28:29'),
(11, 8, 2, 'Clean act 2', 'Clean environment,', 'Changes ', 'Accepted', '2024-10-28 10:29:25');

-- --------------------------------------------------------

--
-- Table structure for table `bills`
--

CREATE TABLE `bills` (
  `bill_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `author_id` int(11) DEFAULT NULL,
  `status` enum('Draft','Under Review','Voting','Passed','Rejected','Amended','Ready for Voting') NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `bills`
--

INSERT INTO `bills` (`bill_id`, `title`, `description`, `author_id`, `status`, `created_at`) VALUES
(4, 'Climate act', ' The Climate Action and Clean Energy Act aims to reduce carbon emissions and promote renewable energy use through incentives and stricter environmental regulations. It targets a 40% carbon reduction by 2031.', 3, 'Under Review', '2024-10-25 15:47:22'),
(7, 'Economy act', 'A bill to support the digital economy by fostering innovation in technology sectors, reducing barriers to entry for startups, and promoting e-commerce', 3, 'Under Review', '2024-10-27 15:20:45'),
(8, 'Clean act 2', 'Clean environment,', 3, 'Voting', '2024-10-28 03:37:14'),
(9, 'Clean act 3', 'Clean society', 4, 'Draft', '2024-10-28 10:59:44');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `role` enum('Administrator','Reviewer','Member of Parliament') NOT NULL,
  `email` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `username`, `password_hash`, `role`, `email`) VALUES
(1, 'admin', '$2y$10$U57bSsJ7s2TjwsQKEoMCreM7BxpW9WSPaRTvpN0H9xDhOi63i09Se', 'Administrator', 'admin@example.com'),
(2, 'reviewer1', '$2y$10$RzAgaexMBIqPyerThdILpOk2kuigvS/ZNnf3k7jdUzewTpj/LNPQy', 'Reviewer', 'reviewer1@example.com'),
(3, 'mp1', '$2y$10$SviXOpYFBaTLirZizro87.ovgjOfbVeXd/s4ug6KZ3lA7U96lHxPa', 'Member of Parliament', 'mp1@example.com'),
(4, 'mp2', '$2y$10$UMWfC1epFGlHp4D6PbfyB.QtPQuvjxQVyhYolkVrmvpMqbG1qdenq', 'Member of Parliament', 'mp2@example.com'),
(5, 'mp3', '$2y$10$kP1yX1zGBQ.2HJ4fjxHRV.ICBrMLLLuVXinJ8722WAbXIhVY0KRoO', 'Member of Parliament', 'mp3@example.com'),
(6, 'mp4', '$2y$10$ffm/9fRwCO/xgFXipqkK1.zJ3n.5QQGtrq.NcAz33dWGK5jfJqD9e', 'Member of Parliament', 'mp4@example.com');

-- --------------------------------------------------------

--
-- Table structure for table `votes`
--

CREATE TABLE `votes` (
  `vote_id` int(11) NOT NULL,
  `bill_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `vote` enum('For','Against','Abstain') NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `votes`
--

INSERT INTO `votes` (`vote_id`, `bill_id`, `user_id`, `vote`, `created_at`) VALUES
(9, 4, 3, 'For', '2024-10-27 15:54:45'),
(10, 4, 3, 'For', '2024-10-27 15:54:51'),
(11, 4, 3, 'Against', '2024-10-27 15:55:08'),
(12, 4, 3, 'For', '2024-10-27 15:57:21'),
(13, 4, 3, 'For', '2024-10-27 16:02:14'),
(14, 4, 3, 'For', '2024-10-27 16:02:27'),
(15, 4, 3, 'Against', '2024-10-27 16:14:04'),
(16, 8, 3, 'For', '2024-10-28 10:30:29'),
(17, 8, 3, 'For', '2024-10-28 11:06:31'),
(18, 8, 4, 'For', '2024-10-28 11:06:44'),
(19, 8, 5, 'Against', '2024-10-28 11:06:55'),
(20, 8, 6, 'For', '2024-10-28 11:07:06');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `amendments`
--
ALTER TABLE `amendments`
  ADD PRIMARY KEY (`amendment_id`),
  ADD KEY `bill_id` (`bill_id`),
  ADD KEY `reviewer_id` (`reviewer_id`);

--
-- Indexes for table `bills`
--
ALTER TABLE `bills`
  ADD PRIMARY KEY (`bill_id`),
  ADD KEY `author_id` (`author_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`);

--
-- Indexes for table `votes`
--
ALTER TABLE `votes`
  ADD PRIMARY KEY (`vote_id`),
  ADD KEY `bill_id` (`bill_id`),
  ADD KEY `user_id` (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `amendments`
--
ALTER TABLE `amendments`
  MODIFY `amendment_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `bills`
--
ALTER TABLE `bills`
  MODIFY `bill_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `votes`
--
ALTER TABLE `votes`
  MODIFY `vote_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `amendments`
--
ALTER TABLE `amendments`
  ADD CONSTRAINT `amendments_ibfk_1` FOREIGN KEY (`bill_id`) REFERENCES `bills` (`bill_id`),
  ADD CONSTRAINT `amendments_ibfk_2` FOREIGN KEY (`reviewer_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `bills`
--
ALTER TABLE `bills`
  ADD CONSTRAINT `bills_ibfk_1` FOREIGN KEY (`author_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `votes`
--
ALTER TABLE `votes`
  ADD CONSTRAINT `votes_ibfk_1` FOREIGN KEY (`bill_id`) REFERENCES `bills` (`bill_id`),
  ADD CONSTRAINT `votes_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
