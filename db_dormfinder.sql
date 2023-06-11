-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Jun 11, 2023 at 07:51 AM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_dormfinder`
--

-- --------------------------------------------------------

--
-- Table structure for table `tbl_adminusers`
--

CREATE TABLE `tbl_adminusers` (
  `id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` text NOT NULL,
  `date_created` date NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_adminusers`
--

INSERT INTO `tbl_adminusers` (`id`, `email`, `password`, `date_created`) VALUES
(1, 'admin@admin', '123456', '2023-04-11');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_bookmarks`
--

CREATE TABLE `tbl_bookmarks` (
  `id` int(11) NOT NULL,
  `dormref` varchar(13) DEFAULT NULL,
  `userref` varchar(13) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_bookmarks`
--

INSERT INTO `tbl_bookmarks` (`id`, `dormref`, `userref`) VALUES
(1, 'i1QjNuLJTMZIB', 'qzPHvK8kHTy3i'),
(2, 'BFHSJXnnAUkI2', 'LhVQ3FMv6d6lW'),
(3, 'Npqap0xFEsMlT', 'tyFmSQJWc9HwZ'),
(4, 'Npqap0xFEsMlT', 'qzPHvK8kHTy3i'),
(5, 'lABWe6poOzMcM', 'qzPHvK8kHTy3i'),
(6, 'i1QjNuLJTMZIB', 'tyFmSQJWc9HwZ'),
(7, 'lABWe6poOzMcM', 'tyFmSQJWc9HwZ'),
(8, 'BFHSJXnnAUkI2', 'qzPHvK8kHTy3i'),
(9, 'Npqap0xFEsMlT', 'LhVQ3FMv6d6lW');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_documents`
--

CREATE TABLE `tbl_documents` (
  `id` int(11) NOT NULL,
  `doc_1` text NOT NULL,
  `doc1_status` int(11) NOT NULL,
  `user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_documents`
--

INSERT INTO `tbl_documents` (`id`, `doc_1`, `doc1_status`, `user_id`) VALUES
(1, 'bir.jpg', 1, 1),
(2, 'local_media4110352670734011178.pdf', 1, 1122);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_dormreviews`
--

CREATE TABLE `tbl_dormreviews` (
  `id` int(11) NOT NULL,
  `userref` varchar(13) DEFAULT NULL,
  `dormref` varchar(13) DEFAULT NULL,
  `rating` int(11) DEFAULT NULL,
  `comment` text DEFAULT NULL,
  `createdAt` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_dormreviews`
--

INSERT INTO `tbl_dormreviews` (`id`, `userref`, `dormref`, `rating`, `comment`, `createdAt`) VALUES
(1, 'tyFmSQJWc9HwZ', 'i1QjNuLJTMZIB', 3, 'Donec posuere metus vitae ipsum.', '2022-12-12'),
(2, 'LhVQ3FMv6d6lW', 'Npqap0xFEsMlT', 2, 'Duis consequat dui nec nisi volutpat eleifend. Donec ut dolor. Morbi vel lectus in quam fringilla rhoncus.', '2022-04-05'),
(3, 'qzPHvK8kHTy3i', 'lABWe6poOzMcM', 5, 'Proin leo odio, porttitor id, consequat in, consequat ut, nulla. Sed accumsan felis. Ut at dolor quis odio consequat varius.', '2022-05-25'),
(5, 'tyFmSQJWc9HwZ', 'BFHSJXnnAUkI2', 3, 'Vestibulum sed magna at nunc commodo placerat. Praesent blandit.', '2022-02-18'),
(6, 'LhVQ3FMv6d6lW', 'lABWe6poOzMcM', 1, 'Curabitur in libero ut massa volutpat convallis.', '2022-08-19'),
(7, 'LhVQ3FMv6d6lW', 'BFHSJXnnAUkI2', 3, 'Aliquam erat volutpat. In congue.', '2022-04-16'),
(8, 'qzPHvK8kHTy3i', 'i1QjNuLJTMZIB', 1, 'In eleifend quam a odio. In hac habitasse platea dictumst.', '2022-12-06'),
(10, 'LhVQ3FMv6d6lW', 'Npqap0xFEsMlT', 1, 'In hac habitasse platea dictumst.', '2022-03-12');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_dorms`
--

CREATE TABLE `tbl_dorms` (
  `id` varchar(13) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `userref` varchar(13) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `address` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `longitude` float(9,6) NOT NULL,
  `latitude` float(9,6) NOT NULL,
  `price` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `slots` int(11) DEFAULT 0,
  `desc` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `hei` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `amenities` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `images` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `visitors` int(11) NOT NULL DEFAULT 0,
  `pets` int(11) NOT NULL DEFAULT 0,
  `curfew` int(11) NOT NULL DEFAULT 0,
  `adv_dep` varchar(255) DEFAULT 'N/A',
  `sec_dep` varchar(255) DEFAULT 'N/A',
  `util` varchar(255) DEFAULT 'N/A',
  `min_stay` varchar(255) DEFAULT 'N/A',
  `createdAt` date DEFAULT current_timestamp(),
  `updatedAt` date DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `tbl_dorms`
--

INSERT INTO `tbl_dorms` (`id`, `userref`, `name`, `address`, `longitude`, `latitude`, `price`, `slots`, `desc`, `hei`, `amenities`, `images`, `visitors`, `pets`, `curfew`, `adv_dep`, `sec_dep`, `util`, `min_stay`, `createdAt`, `updatedAt`) VALUES
('BFHSJXnnAUkI2', 'qzPHvK8kHTy3i', 'Ooba', '903 Engracia Reyes St, Ermita, Manila', 120.981796, 14.579275, '$1319.76', 12, 'brand interactive mindshare', 'PUP, FEU, UST', 'Air Conditioning, Elevator, Beddings, Kitchen, Wi-Fi', 'adam-winger-5zX1KAjPl4o-unsplash.jpg,alen-rojnic-GfvHuhw2Iqg-unsplash.jpg,alen-rojnic-T1Yvmf4oleQ-unsplash.jpg,blake-woolwine-lz9W775oDyI-unsplash.jpg,chino-rocha-LDhXIvAqRJw-unsplash.jpg', 0, 0, 0, 'N/A', 'N/A', 'N/A', 'N/A', '2022-09-21', '2023-06-01'),
('i1QjNuLJTMZIB', 'qzPHvK8kHTy3i', 'Quimba', '396 Zaragoza St, Tondo, Manila', 120.962776, 14.602634, '$1937.13', 16, 'innovate 24/365 web services', 'UP, UST, NU, FEU, PUP', 'Lounge, Parking, Security, Study Room', 'daria-shevtsova-RP4mtXJM7es-unsplash.jpg,fred-kleber-gTbaxaVLvsg-unsplash.jpg,gabriel-beaudry-WuQME0I_oZA-unsplash.jpg,lissete-laverde-9XdCMuK8zlQ-unsplash.jpg,marcus-loke-WQJvWU_HZFo-unsplash.jpg', 0, 0, 0, 'N/A', 'N/A', 'N/A', 'N/A', '2022-02-14', '2023-10-24'),
('KKtGjBpTq9VAI', 'LhVQ3FMv6d6lW', 'Tagcat', '303 Recto Ave, Binondo, Manila', 120.974670, 14.606092, '$3214.48', 17, 'extend granular infomediaries', 'PUP', 'Laundry, Lounge, Parking, Wi-Fi', 'nguyen-dang-hoang-nhu-HHs_PrvxSQk-unsplash.jpg,rnaol-oKHHspCSWHQ-unsplash.jpg,samuel-regan-asante-CbptaPcrFCc-unsplash.jpg,shashi-chaturvedula-UlHN7wFhtvU-unsplash.jpg,shashi-chaturvedula-xdY1s1I6J8U-unsplash.jpg', 0, 0, 0, 'N/A', 'N/A', 'N/A', 'N/A', '2022-06-14', '2023-05-02'),
('lABWe6poOzMcM', 'LhVQ3FMv6d6lW', 'Zooveo', '261 2nd St, Port Area, Manila', 120.964195, 14.593937, '$2628.09', 7, 'repurpose 24/365 interfaces', 'UP, UST, NU, FEU, PUP', 'Lounge, Security, Study Room', 'shche_-team-PFi1uWHh2dQ-unsplash.jpg,sigmund-CwTfKH5edSk-unsplash.jpg,spacejoy-808a4AWu8jE-unsplash.jpg,spacejoy-vOa-PSimwg4-unsplash.jpg,taiga-ishii-mukO8Po_LZ8-unsplash.jpg', 0, 0, 0, 'N/A', 'N/A', 'N/A', 'N/A', '2021-04-04', '2023-09-09'),
('Npqap0xFEsMlT', 'LhVQ3FMv6d6lW', 'Zoonder', '71 Wagas St, Tondo, Manila', 120.963966, 14.606765, '$1381.52', 3, 'recontextualize front-end experiences', 'UP, UST, NU, FEU', 'Beddings, Parking, Wi-Fi', 'alen-rojnic-T1Yvmf4oleQ-unsplash.jpg,gabriel-beaudry-WuQME0I_oZA-unsplash.jpg,samuel-regan-asante-CbptaPcrFCc-unsplash.jpg,spacejoy-808a4AWu8jE-unsplash.jpg', 0, 0, 0, 'N/A', 'N/A', 'N/A', 'N/A', '2022-03-19', '2023-03-05');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_notifications`
--

CREATE TABLE `tbl_notifications` (
  `id` int(11) NOT NULL,
  `user_ref` varchar(222) NOT NULL,
  `title` varchar(500) NOT NULL,
  `ndesc` varchar(500) NOT NULL,
  `created` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `scheduled` timestamp NOT NULL DEFAULT current_timestamp(),
  `notif_uniqid` varchar(222) DEFAULT NULL,
  `doc_status` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_notifications`
--

INSERT INTO `tbl_notifications` (`id`, `user_ref`, `title`, `ndesc`, `created`, `scheduled`, `notif_uniqid`, `doc_status`) VALUES
(1, '1122', 'DormFinder', 'Your documents have been verified! You can now publish your Dorm.', '2023-03-13 06:35:21', '2023-03-10 06:21:35', '1', 0),
(31, '1122', 'DormFinder', 'Your documents have been verified! You can now publish your Dorm.', '2023-04-23 08:53:53', '2023-04-23 08:54:53', '1', 0),
(32, '1122', 'DormFinder', 'Your documents have been verified! You can now publish your Dorm.', '2023-04-23 08:54:06', '2023-04-23 08:55:06', '1', 0);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_users`
--

CREATE TABLE `tbl_users` (
  `id` varchar(13) NOT NULL,
  `username` varchar(255) NOT NULL,
  `identifier` varchar(255) NOT NULL,
  `imageUrl` varchar(255) NOT NULL,
  `is_verified` int(11) NOT NULL DEFAULT 0 COMMENT '0 - none\r\n1 - verified',
  `password` varchar(255) NOT NULL,
  `created_at` date NOT NULL,
  `updated_at` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_users`
--

INSERT INTO `tbl_users` (`id`, `username`, `identifier`, `imageUrl`, `is_verified`, `password`, `created_at`, `updated_at`) VALUES
('LhVQ3FMv6d6lW', 'Juan dela Cruz', 'juandlc@gmail.com', 'http:/192.168.0.24/DormFinder-Admin/uploads/userImages/LhVQ3FMv6d6lW/1.jpg', 1, '09876543', '2022-04-01', '2023-06-04'),
('qzPHvK8kHTy3i', 'Willis Standing', '09024680123', 'http:/192.168.0.24/DormFinder-Admin/uploads/userImages/qzPHvK8kHTy3i/7.jpg', 1, 'KtnA5CW6', '2022-03-02', '2023-05-27'),
('tyFmSQJWc9HwZ', 'Giacobo Ziemke', 'gziemke2@tamu.edu', 'http:/192.168.0.24/DormFinder-Admin/uploads/userImages/tyFmSQJWc9HwZ/5.jpg', 0, 'c1nM8r1JBEu6', '2022-04-29', '2023-10-30');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tbl_adminusers`
--
ALTER TABLE `tbl_adminusers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_bookmarks`
--
ALTER TABLE `tbl_bookmarks`
  ADD PRIMARY KEY (`id`),
  ADD KEY `dormref` (`dormref`),
  ADD KEY `userref` (`userref`);

--
-- Indexes for table `tbl_documents`
--
ALTER TABLE `tbl_documents`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_dormreviews`
--
ALTER TABLE `tbl_dormreviews`
  ADD PRIMARY KEY (`id`),
  ADD KEY `userref` (`userref`),
  ADD KEY `dormref` (`dormref`);

--
-- Indexes for table `tbl_dorms`
--
ALTER TABLE `tbl_dorms`
  ADD PRIMARY KEY (`id`),
  ADD KEY `userref` (`userref`);

--
-- Indexes for table `tbl_notifications`
--
ALTER TABLE `tbl_notifications`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_users`
--
ALTER TABLE `tbl_users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tbl_adminusers`
--
ALTER TABLE `tbl_adminusers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `tbl_bookmarks`
--
ALTER TABLE `tbl_bookmarks`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `tbl_documents`
--
ALTER TABLE `tbl_documents`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `tbl_dormreviews`
--
ALTER TABLE `tbl_dormreviews`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `tbl_notifications`
--
ALTER TABLE `tbl_notifications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `tbl_bookmarks`
--
ALTER TABLE `tbl_bookmarks`
  ADD CONSTRAINT `tbl_bookmarks_ibfk_1` FOREIGN KEY (`dormref`) REFERENCES `tbl_dorms` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `tbl_bookmarks_ibfk_2` FOREIGN KEY (`userref`) REFERENCES `tbl_users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `tbl_dormreviews`
--
ALTER TABLE `tbl_dormreviews`
  ADD CONSTRAINT `tbl_dormreviews_ibfk_1` FOREIGN KEY (`dormref`) REFERENCES `tbl_dorms` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `tbl_dormreviews_ibfk_2` FOREIGN KEY (`userref`) REFERENCES `tbl_users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `tbl_dorms`
--
ALTER TABLE `tbl_dorms`
  ADD CONSTRAINT `tbl_dorms_ibfk_1` FOREIGN KEY (`userref`) REFERENCES `tbl_users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
