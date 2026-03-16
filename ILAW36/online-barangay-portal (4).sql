-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 22, 2024 at 12:05 PM
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
-- Table structure for table `announcement_post`
--

CREATE TABLE `announcement_post` (
  `post_id` int(11) NOT NULL,
  `post_title` varchar(50) NOT NULL,
  `post_body` text NOT NULL,
  `post_date_time` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `announcement_post`
--

INSERT INTO `announcement_post` (`post_id`, `post_title`, `post_body`, `post_date_time`) VALUES
(19, 'Welcome to Ilagan Association for Women Website', 'Welcome to Our Announcements Page! Here, you\'ll find all the latest updates, important notices, and upcoming events to keep you informed. Stay tuned for timely information that matters to you!', '10/20/2024 8:51 PM'),
(20, 'Distribution of Pamaskong Handog ', 'Brgy. Baculud, Bagumbayan, Sta. Barbara, Centro, Poblacion, San Vicente, Camunatan, Sto. Tomas, Guinatan, Osmeña\r\n', '10/31/2024 7:00 AM');

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
(45, 'ILAW APPLICATION FORM (new).pdf', 'This contains important form for application .', '10/20/2024 6:30 PM', 'documents/');

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
  `official_username` varchar(50) CHARACTER SET utf8 COLLATE utf8_bin DEFAULT NULL,
  `official_password` varchar(50) CHARACTER SET utf8 COLLATE utf8_bin DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `official_info`
--

INSERT INTO `official_info` (`official_id`, `official_position`, `official_first_name`, `official_middle_name`, `official_last_name`, `official_sex`, `official_contact_info`, `official_email`, `official_username`, `official_password`) VALUES
(16, 'President Emeritus', 'Evelyn', 'C', 'Diaz', 'Alibagu', '09762646047', 'evelyn@gmail.com', 'evelyn', 'evelyn'),
(17, 'President', 'Adelaida', 'M.', 'Almachar', 'Guinatan', '09762646048', 'adelaida@gmail.com', 'adelaida', 'adelaida'),
(18, 'Vice president (Internal)', 'Evelyn', 'C.', 'Ong', 'Alinguigan', '09762646049', 'evelyn_ong@gmail.com', 'evelyn_ong', 'evelyn'),
(19, 'Vice president (External)', 'Marissa', 'M.', 'Alvarez', 'Ballacong', '09762646050', 'marissa@gmail.com', 'marissa', 'marissa'),
(20, 'Auditor 1', 'Rosario', 'C.', 'Manalo', 'Bangag', '09762646051', 'rosario@gmail.com', '', ''),
(21, 'Treasurer', 'Luisita', 'L. ', 'Lim', 'Batong Labang', '09762646051', 'luisita@gmail.com', '', ''),
(22, 'Executive Secretary', 'Jeanette', 'B.', 'Baggao', 'Cadu', '09762646052', 'jeanette@gmail.com', 'jeanette', 'jeanette'),
(23, 'Business Manager', 'Juanita', 'P.', 'Chua', 'Capellan', '09762646053', 'juanita@gmail.com', '', ''),
(25, 'Eastern Barangay Coordinator', 'Marilyn', 'A.', 'Canceran', 'Fuyo', '09762646053', 'marilyn@gmail.com', '', ''),
(26, 'Northern Barangay Coordinator', 'Felina', 'B.', 'Sabularse', 'Manaring', '09762646061', 'felina@gmail.com', '', ''),
(27, 'Western Poblacion Barangay Coordinator', 'Jonalyn', 'C.', 'Cabanggan', 'Marana 1st', '09762646062', 'jonalyn@gmail.com', '', ''),
(28, 'Southern Region Barangay Coordinator', 'Juvida', 'D.', 'Catabay', 'Morado', '09762646065', 'juvida@gmail.com', '', ''),
(40, 'Info', 'Norma', 'B.', 'Bulauan', 'Alibagu', '09269764536', 'norma@gmail.com', '', ''),
(41, 'Assistant Secretary', 'Jayca', 'J.', 'Domingo', 'Purok 2 guinatan', '09358597705', 'jaycadomingo@gmail.com', '', ''),
(42, 'Assistant Business Manager', 'Ma. Carmina', 'T.', 'Diaz', 'Alibagu', '09123456787', 'mariacarminadiaz@gmail.com', '', ''),
(43, 'Assistant Info', 'Trisha Marie', 'A.', 'Diaz', 'Purok 2 guinatan', '09358597709', 'trishamarieAdiaz@gmail.com', '', ''),
(44, 'Auditor 2', 'Ma. Isabela', 'G.', 'Singsson', 'Cabisera 10', '09212381645', 'maisabelaGsingsson@gmail.com', '', ''),
(45, 'Assistant Treasurer', 'Antoinette Myda', 'P.', 'Foronda', 'Marana 1st', '09671574512', 'antoinettemydaPforonda@gmail.com', '', '');

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
('24-01', 'Jay', 'Pogi', 'Malenab', '2', 'Marana 1st', 'Cabagan', 'RC', '2002-02-02', 'Recently Moved', 'N/A', 'N/A', 'piolo ', 'Single', 'Private', 'pascual', 'enrique', 'gil', '5', 'Member', 'jay', 'jay', '2024-10-20'),
('24-02', 'Samuel', 'Magsilang', 'Santiago', '2', 'Bagong Silang', 'Cabagan', 'Jehova', '2003-03-03', 'Since Birth', 'Berta', 'Alberta', 'Philip', 'Married', 'Private', 'Salvador', 'Bini', 'Maloi', '1', NULL, 'sam', 'sam', '2024-10-20'),
('24-03', 'Jetrix', 'Gwapo', 'Jimenez', '2', 'Baculud', 'Ilagan', 'INC', '2004-04-04', 'Recently Moved', 'N/A', 'N/A', 'James', 'Single', 'Private', 'Reid', 'Angel', 'Locsin', '2', NULL, 'jetrix', 'jetrix', '2024-10-20'),
('24-04', 'Antonio ', 'Zinaggang', 'Futad', '4', 'Alibagu', 'Ilagan', 'Muslim', '1990-02-02', 'Recently Moved', 'Samuel ', 'Santiago', 'Jackie', 'Widowed', 'Self-employed', 'Chan', 'marry', 'Mikha', '10', 'Member', 'raymundo', 'raymundo', '2024-10-20'),
('24-05', 'Raymund', 'Antonio', 'Futad', '5', 'Ballacong', 'New York', 'Muslim', '1999-05-05', 'Since Birth', 'Joey', 'Boi', 'Bruce', 'Married', 'Self-employed', 'Lee', 'Lee', 'Changge', '2', NULL, 'antonio', 'antonio', '2024-10-20'),
('24-06', 'Rochel', 'Rolly', 'Ferrer', '5', 'Bagumbayan', 'Ilagan', 'INC', '1998-02-02', 'Since Birth', 'Jung', 'Cook', 'Robin ', 'Married', 'Private', 'Padilla', 'Mariel', 'Padilla', '12', NULL, 'rochel', 'rochel', '2024-10-20'),
('24-07', 'Gerante', 'Aguilar', 'Eusebio', '4', 'Bigao', 'Ilagan', 'Roman Catholic', '2001-01-01', 'Since Birth', 'N/A', 'N/A', 'Romel', 'Single', 'Private', 'Roblez', 'Annie', 'Roblez', '2', NULL, 'gerante', 'gerante', '2024-10-20'),
('24-08', 'Jack', 'Bor', 'Perez', '4', 'Carikkikan Norte', 'North Korea', 'Roman Catholic', '1998-04-01', 'Since Birth', 'N/A', 'N/A', 'Cardo', 'Single', 'Private', 'Rods', 'Annie', 'Roban', '6', NULL, 'jack', 'jack', '2024-10-20'),
('24-10', 'Enrique', 'Dam', 'Gil', '6', 'Ballacong', 'Ilagan', 'INC', '2000-02-09', 'Recently Moved', 'N/A', 'N/A', 'Ram', 'Single', 'Government', 'Rom', 'Rem', 'Rim', '5', NULL, 'Enrique', 'enrique', '2024-10-20'),
('24-11', 'Jasmine', 'Curtis', 'Smith', '6', 'Cabisera 17-21', 'Ilagan', 'Jehova', '1996-12-09', 'Recently Moved', 'N/A', 'N/A', 'Ronnie', 'Single', 'Private', 'Rickets', 'Mae', 'Marah', '5', NULL, 'jasmine', 'jasmine', '2024-10-20'),
('24-12', 'Immanuel', 'Goryu', 'Nacion', '5', 'Bagong Silang', 'Ilagan', 'Roman Catholic', '2000-12-12', 'Since Birth', 'Maria', 'Makiling', 'Mario', 'Single', 'Private', 'Perez', 'Maria', 'Perez', '5', NULL, 'nacion ', 'nacion', '2024-10-20'),
('24-13', 'jkasfk', 'fkaslfk', 'fasklfka', '5', 'Aggasian', 'Ilagan', 'Roman Catholic', '2001-01-01', 'Since Birth', 'jm', 'adorio', 'mico', 'Single', 'Government', 'balmaceda', 'jerry', 'mae', '5', NULL, 'ANNE', 'ANNE', '2024-10-20'),
('24-14', 'jfdakl', 'fakljfk', 'kjlfasklf', '5', 'Aggasian', 'Ilagan', 'Roman Catholic', '2001-01-01', 'Since Birth', 'jm', 'adorio', 'dominic', 'Single', 'Government', 'almazan', 'jerry', 'mae', '5', NULL, 'gerante27', 'gerante', '2024-10-20');

-- --------------------------------------------------------

--
-- Table structure for table `transaction`
--

CREATE TABLE `transaction` (
  `id` int(11) NOT NULL,
  `resident_id` varchar(10) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `submission_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `transaction`
--

INSERT INTO `transaction` (`id`, `resident_id`, `description`, `submission_date`) VALUES
(15, '24-01', NULL, '2024-10-20'),
(16, '24-02', NULL, '2024-10-20'),
(17, '24-03', NULL, '2024-10-20'),
(18, '24-05', NULL, '2024-10-20'),
(19, '24-06', NULL, '2024-10-20'),
(20, '24-07', NULL, '2024-10-20'),
(21, '24-08', NULL, '2024-10-20'),
(22, '24-10', NULL, '2024-10-20'),
(23, '24-11', NULL, '2024-10-20'),
(24, '24-12', NULL, '2024-10-20'),
(25, '24-01', NULL, '2024-10-20'),
(26, '24-01', NULL, '2024-10-20'),
(27, '24-01', NULL, '2024-10-20'),
(28, '24-06', NULL, '2024-10-20'),
(29, '24-01', NULL, '2024-10-20'),
(30, '24-01', NULL, '2024-10-20');

-- --------------------------------------------------------

--
-- Table structure for table `uploads`
--

CREATE TABLE `uploads` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `directory` varchar(150) NOT NULL,
  `resident_id` varchar(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `uploads`
--

INSERT INTO `uploads` (`id`, `name`, `directory`, `resident_id`) VALUES
(35, 'images.jpg', 'uploads/', '24-03'),
(36, 'download.png', 'uploads/', '24-03');

--
-- Indexes for dumped tables
--

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
-- AUTO_INCREMENT for table `announcement_post`
--
ALTER TABLE `announcement_post`
  MODIFY `post_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `documents`
--
ALTER TABLE `documents`
  MODIFY `id` int(50) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=46;

--
-- AUTO_INCREMENT for table `official_info`
--
ALTER TABLE `official_info`
  MODIFY `official_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=46;

--
-- AUTO_INCREMENT for table `transaction`
--
ALTER TABLE `transaction`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `uploads`
--
ALTER TABLE `uploads`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
