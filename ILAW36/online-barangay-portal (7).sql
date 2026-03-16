-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 27, 2025 at 01:21 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `online-barangay-portal`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin_logs`
--

CREATE TABLE `admin_logs` (
  `log_id` int(11) NOT NULL,
  `official_username` varchar(100) NOT NULL,
  `login_time` datetime DEFAULT NULL,
  `logout_time` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin_logs`
--

INSERT INTO `admin_logs` (`log_id`, `official_username`, `login_time`, `logout_time`) VALUES
(31, 'evelyn', '2024-12-13 02:39:07', '2024-12-13 02:39:14'),
(32, 'evelyn', '2024-12-13 02:39:21', '2024-12-13 02:39:26'),
(33, 'evelyn', '2024-12-13 02:44:08', '2024-12-13 02:44:13'),
(34, 'evelyn', '2024-12-13 02:44:22', '2024-12-13 02:44:26'),
(35, 'evelyn', '2024-12-13 02:44:51', '2024-12-13 02:44:56'),
(36, 'evelyn', '2024-12-13 02:45:02', '2024-12-13 02:45:55'),
(37, 'evelyn', '2024-12-13 02:46:00', '2024-12-13 02:46:03'),
(38, 'evelyn', '2024-12-13 02:46:16', '2024-12-13 02:46:21'),
(39, 'evelyn', '2024-12-13 02:46:27', NULL),
(40, 'evelyn', '2024-12-13 09:12:04', NULL),
(41, 'evelyn', '2024-12-13 09:28:30', NULL),
(42, 'evelyn', '2024-12-13 10:05:08', '2024-12-13 10:05:45'),
(43, 'evelyn', '2024-12-13 10:07:39', '2024-12-13 10:07:50'),
(44, 'evelyn', '2024-12-13 10:25:22', '2024-12-13 14:04:43'),
(45, 'evelyn', '2024-12-13 16:01:26', NULL),
(46, 'evelyn', '2025-02-19 20:55:43', '2025-02-19 20:59:51'),
(47, 'evelyn', '2025-02-19 21:16:32', '2025-02-19 21:53:31'),
(48, 'evelyn', '2025-02-19 22:11:58', '2025-02-19 22:46:29'),
(49, 'evelyn', '2025-02-19 22:52:31', '2025-02-19 22:52:47'),
(50, 'evelyn', '2025-02-19 22:53:08', '2025-02-19 22:54:21'),
(51, 'evelyn', '2025-02-20 22:17:55', NULL),
(52, 'evelyn', '2025-02-21 13:06:41', '2025-02-21 13:06:45'),
(53, 'evelyn', '2025-02-21 14:21:27', '2025-02-21 15:35:35'),
(54, 'evelyn', '2025-02-21 15:54:30', '2025-02-21 15:59:39'),
(55, 'evelyn', '2025-02-21 16:00:09', NULL),
(56, 'evelyn', '2025-02-27 18:28:49', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `announcement_post`
--

CREATE TABLE `announcement_post` (
  `post_id` int(11) NOT NULL,
  `post_title` varchar(50) NOT NULL,
  `post_body` text NOT NULL,
  `post_date_time` varchar(50) NOT NULL,
  `post_photo` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `announcement_post`
--

INSERT INTO `announcement_post` (`post_id`, `post_title`, `post_body`, `post_date_time`, `post_photo`) VALUES
(19, 'Welcome to Ilagan Association for Women Website', 'Welcome to Our Announcements Page! Here, you\'ll find all the latest updates, important notices, and upcoming events to keep you informed. Stay tuned for timely information that matters to you!', '10/20/2024 8:51 PM', NULL),
(47, 'capstone', 'present', '12/13/2024 10:36 AM', '[\"uploads\\/posts\\/EASTERN QR CODe (1).png\",\"uploads\\/posts\\/bdors_backup_1733943678.sql\"]');

-- --------------------------------------------------------

--
-- Table structure for table `documents`
--

CREATE TABLE `documents` (
  `id` int(50) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `upload_date_time` varchar(50) NOT NULL,
  `directory` varchar(150) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `documents`
--

INSERT INTO `documents` (`id`, `name`, `description`, `upload_date_time`, `directory`) VALUES
(49, 'EASTERN QR CODe.png', 'Alinguigan 1st\r\nAlinguigan 2nd\r\nAlinguigan 3rd\r\nBallacong\r\nBangag\r\nBatong Labang\r\nCadu\r\nCapellan\r\nCapo\r\nFuyo\r\nManaring\r\nMarana 1st\r\nMarana 2nd\r\nMarana 3rd\r\nMinabang\r\nMorado\r\nNanaguan\r\nPasa\r\nQuimalabasa\r\nRang-Ayan\r\nRugao\r\nSan Andres\r\nSan Isidro\r\nSan Juan\r\nSan Lorenzo\r\nSan Pablo\r\nSan Rodrigo\r\nSipay\r\nSta. Catalina\r\nSta. Victoria\r\nTangcul\r\nVilla Imelda', '12/09/2024 11:31 PM', 'documents/');

-- --------------------------------------------------------

--
-- Table structure for table `official_info`
--

CREATE TABLE `official_info` (
  `official_id` int(11) NOT NULL,
  `official_position` varchar(50) NOT NULL,
  `official_first_name` varchar(50) NOT NULL,
  `official_middle_name` varchar(50) NOT NULL,
  `official_last_name` varchar(50) NOT NULL,
  `official_sex` varchar(50) NOT NULL,
  `official_contact_info` varchar(50) NOT NULL,
  `official_email` varchar(50) NOT NULL,
  `official_year` varchar(4) DEFAULT NULL,
  `official_username` varchar(50) CHARACTER SET utf8 COLLATE utf8_bin DEFAULT NULL,
  `official_password` varchar(50) CHARACTER SET utf8 COLLATE utf8_bin DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `official_info`
--

INSERT INTO `official_info` (`official_id`, `official_position`, `official_first_name`, `official_middle_name`, `official_last_name`, `official_sex`, `official_contact_info`, `official_email`, `official_year`, `official_username`, `official_password`) VALUES
(16, 'President Emeritus', 'Evelyn', 'C', 'Diaz', 'Alibagu', '09762646047', 'evelyn@gmail.com', '2010', 'evelyn', 'evelyn'),
(17, 'President', 'Adelaida', 'M.', 'Almachar', 'Guinatan', '09762646048', 'adelaida@gmail.com', '2010', 'adelaida', 'adelaida'),
(18, 'Vice president (Internal)', 'Evelyn', 'C.', 'Ong', 'Alinguigan', '09762646049', 'evelyn_ong@gmail.com', '2010', 'evelyn_ong', 'evelyn'),
(19, 'Vice president (External)', 'Marissa', 'M.', 'Alvarez', 'Ballacong', '09762646050', 'marissa@gmail.com', '2010', 'marissa', 'marissa'),
(20, 'Auditor 1', 'Rosario', 'C.', 'Manalo', 'Bangag', '09762646051', 'rosario@gmail.com', '2010', '', ''),
(21, 'Treasurer', 'Luisita', 'L. ', 'Lim', 'Batong Labang', '09762646051', 'luisita@gmail.com', '2010', '', ''),
(22, 'Executive Secretary', 'Jeanette', 'B.', 'Baggao', 'Cadu', '09762646052', 'jeanette@gmail.com', '2010', 'jeanette', 'jeanette'),
(23, 'Business Manager', 'Juanita', 'P.', 'Chua', 'Capellan', '09762646053', 'juanita@gmail.com', '2010', '', ''),
(25, 'Eastern Barangay Coordinator', 'Marilyn', 'A.', 'Canceran', 'Fuyo', '09762646053', 'marilyn@gmail.com', '2010', '', ''),
(26, 'Northern Barangay Coordinator', 'Felina', 'B.', 'Sabularse', 'Manaring', '09762646061', 'felina@gmail.com', '2010', '', ''),
(27, 'Western Poblacion Barangay Coordinator', 'Jonalyn', 'C.', 'Cabanggan', 'Marana 1st', '09762646062', 'jonalyn@gmail.com', '2010', '', ''),
(28, 'Southern Region Barangay Coordinator', 'Juvida', 'D.', 'Catabay', 'Morado', '09762646065', 'juvida@gmail.com', '2010', '', ''),
(40, 'Info', 'Norma', 'B.', 'Bulauan', 'Alibagu', '09269764536', 'norma@gmail.com', '2010', '', ''),
(41, 'Assistant Secretary', 'Jayca', 'J.', 'Domingo', 'Purok 2 guinatan', '09358597705', 'jaycadomingo@gmail.com', '2010', '', ''),
(42, 'Assistant Business Manager', 'Ma. Carmina', 'T.', 'Diaz', 'Alibagu', '09123456787', 'mariacarminadiaz@gmail.com', '2010', '', ''),
(43, 'Assistant Info', 'Trisha Marie', 'A.', 'Diaz', 'Purok 2 guinatan', '09358597709', 'trishamarieAdiaz@gmail.com', '2010', '', ''),
(44, 'Auditor 2', 'Ma. Isabela', 'G.', 'Singsson', 'Cabisera 10', '09212381645', 'maisabelaGsingsson@gmail.com', '2010', '', ''),
(45, 'Assistant Treasurer', 'Antoinette Myda', 'P.', 'Foronda', 'Marana 1st', '09671574512', 'antoinettemydaPforonda@gmail.com', '2010', '', ''),
(47, 'President Emeritus', 'gerante', 'aguilar', 'eusebio', 'guinatan', '09762646047', 'geranteeusebiojr@gmail.com', '2011', 'gerante', 'gerante'),
(50, 'President Emeritus', 'Genaleen', 'Anne', 'Eusebio', 'Guinatan', '09762646048', 'genaleen@gmail.com', '2012', '', ''),
(51, 'President', 'Cristina ', 'Aguilar', 'Santiago', 'Guinatan', '09656548819', 'cris@gmail.com', '2011', '', '');

-- --------------------------------------------------------

--
-- Table structure for table `resident_info`
--

CREATE TABLE `resident_info` (
  `resident_id` varchar(10) NOT NULL,
  `first_name` varchar(50) NOT NULL,
  `middle_name` varchar(50) NOT NULL,
  `last_name` varchar(50) NOT NULL,
  `purok` varchar(50) NOT NULL,
  `barangay` varchar(50) NOT NULL,
  `suffix` varchar(50) NOT NULL,
  `alias` varchar(50) NOT NULL,
  `birthday` varchar(50) NOT NULL,
  `sex` varchar(50) NOT NULL,
  `mobile_no` varchar(50) NOT NULL,
  `email` varchar(50) NOT NULL,
  `religion` varchar(50) NOT NULL,
  `civil_stat` varchar(50) NOT NULL,
  `voter_stat` varchar(50) NOT NULL,
  `fathers_last_name` varchar(50) NOT NULL,
  `mothers_first_name` varchar(50) NOT NULL,
  `mothers_last_name` varchar(50) NOT NULL,
  `num_children` varchar(50) NOT NULL,
  `stat` varchar(255) DEFAULT NULL,
  `username` varchar(50) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `password` varchar(50) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `submission_date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `resident_info`
--

INSERT INTO `resident_info` (`resident_id`, `first_name`, `middle_name`, `last_name`, `purok`, `barangay`, `suffix`, `alias`, `birthday`, `sex`, `mobile_no`, `email`, `religion`, `civil_stat`, `voter_stat`, `fathers_last_name`, `mothers_first_name`, `mothers_last_name`, `num_children`, `stat`, `username`, `password`, `submission_date`) VALUES
('24-01', 'Ruperth', 'Perto', 'Aggarao', '3', 'Baculud', 'Ilagan', 'Roman catholic', '1945-01-22', 'Since Birth', 'Mitch', 'Guiao', 'Sam ', 'Annuled', 'Self-employed', 'Santiago', 'Rochel', 'Ferrer', '34', 'Member', 'ruperth', 'ruperth', '2024-10-27'),
('24-02', 'Toni', 'Andolini', 'Corleone', '1', 'Baculud', 'Ilagan', 'Roman catholic', '2003-01-23', 'Since Birth', 'None', 'None', 'Rosita', 'Single', 'Government', 'Corleone', 'Rosmerita', 'Roblez', '10', 'Member', 'Futad', 'Futad', '2024-10-28'),
('24-03', 'Jay', 'Cauan', 'Malenab', '1', 'Aggasian', 'Ilagan', 'Roman catholic', '2001-01-01', 'Since Birth', 'Jocelyn', 'Jose', 'George', 'Single', 'Government', 'Malenab', 'Josie', 'Cauan', '1', NULL, 'JAY', 'JAY', '2024-10-28');

-- --------------------------------------------------------

--
-- Table structure for table `transaction`
--

CREATE TABLE `transaction` (
  `id` int(11) NOT NULL,
  `resident_id` varchar(10) NOT NULL,
  `last_name` varchar(50) DEFAULT NULL,
  `middle_name` varchar(50) DEFAULT NULL,
  `first_name` varchar(50) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `submission_date` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `transaction`
--

INSERT INTO `transaction` (`id`, `resident_id`, `last_name`, `middle_name`, `first_name`, `description`, `submission_date`) VALUES
(42, '24-02', 'Toni', 'Andolini', 'Corleone', NULL, '2024-10-28 09:58:00'),
(43, '24-01', 'Ruperth', 'Perto', 'Aggarao', NULL, '2024-10-28 15:08:00'),
(44, '24-01', 'Ruperth', 'Perto', 'Aggarao', NULL, '2024-10-28 15:14:00'),
(45, '24-03', 'Jay', 'Cauan', 'Malenab', NULL, '2024-12-10 22:39:00'),
(46, '24-03', 'Jay', 'Cauan', 'Malenab', NULL, '2024-12-10 22:56:00');

-- --------------------------------------------------------

--
-- Table structure for table `uploads`
--

CREATE TABLE `uploads` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `directory` varchar(150) NOT NULL,
  `resident_id` varchar(10) DEFAULT NULL,
  `last_name` varchar(255) NOT NULL,
  `middle_name` varchar(255) NOT NULL,
  `first_name` varchar(255) NOT NULL,
  `submission_date` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `uploads`
--

INSERT INTO `uploads` (`id`, `name`, `directory`, `resident_id`, `last_name`, `middle_name`, `first_name`, `submission_date`) VALUES
(61, 'djane.jpg', 'uploads/', '24-03', 'Malenab', 'Cauan', 'Jay', '2024-12-10 22:57:23'),
(62, '479996437_1671223773806205_1050181143090244911_n.jpg', 'uploads/', '24-01', 'Aggarao', 'Perto', 'Ruperth', '2025-02-21 15:59:59');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin_logs`
--
ALTER TABLE `admin_logs`
  ADD PRIMARY KEY (`log_id`);

--
-- Indexes for table `announcement_post`
--
ALTER TABLE `announcement_post`
  ADD PRIMARY KEY (`post_id`);

--
-- Indexes for table `documents`
--
ALTER TABLE `documents`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `official_info`
--
ALTER TABLE `official_info`
  ADD PRIMARY KEY (`official_id`);

--
-- Indexes for table `resident_info`
--
ALTER TABLE `resident_info`
  ADD PRIMARY KEY (`resident_id`),
  ADD UNIQUE KEY `UNIQUE` (`username`);

--
-- Indexes for table `transaction`
--
ALTER TABLE `transaction`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `uploads`
--
ALTER TABLE `uploads`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin_logs`
--
ALTER TABLE `admin_logs`
  MODIFY `log_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=57;

--
-- AUTO_INCREMENT for table `announcement_post`
--
ALTER TABLE `announcement_post`
  MODIFY `post_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=48;

--
-- AUTO_INCREMENT for table `documents`
--
ALTER TABLE `documents`
  MODIFY `id` int(50) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=51;

--
-- AUTO_INCREMENT for table `official_info`
--
ALTER TABLE `official_info`
  MODIFY `official_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=52;

--
-- AUTO_INCREMENT for table `transaction`
--
ALTER TABLE `transaction`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=47;

--
-- AUTO_INCREMENT for table `uploads`
--
ALTER TABLE `uploads`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=63;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
