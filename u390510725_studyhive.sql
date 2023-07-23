-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Jul 17, 2023 at 11:05 AM
-- Server version: 10.5.19-MariaDB-cll-lve
-- PHP Version: 7.2.34

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `u390510725_studyhive`
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
(46, 'admin@admin', 'b7hAXgmZP', '2023-07-09'),
(47, 'info.studyhive@gmail.com', 'udwlH2L97', '2023-07-09'),
(52, 'gilotriciac@gmail.com', '%&tp$$Jd^', '2023-07-09');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_amenities`
--

CREATE TABLE `tbl_amenities` (
  `id` int(11) NOT NULL,
  `dormref` varchar(13) NOT NULL,
  `aircon` int(11) NOT NULL DEFAULT 0,
  `elevator` int(11) NOT NULL DEFAULT 0,
  `beddings` int(11) NOT NULL DEFAULT 0,
  `kitchen` int(11) NOT NULL DEFAULT 0,
  `laundry` int(11) NOT NULL DEFAULT 0,
  `lounge` int(11) NOT NULL DEFAULT 0,
  `parking` int(11) NOT NULL DEFAULT 0,
  `security` int(11) NOT NULL DEFAULT 0,
  `study_room` int(11) NOT NULL DEFAULT 0,
  `wifi` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_amenities`
--

INSERT INTO `tbl_amenities` (`id`, `dormref`, `aircon`, `elevator`, `beddings`, `kitchen`, `laundry`, `lounge`, `parking`, `security`, `study_room`, `wifi`) VALUES
(2, 'i1QjNuLJTMZIB', 1, 0, 1, 0, 1, 1, 0, 1, 0, 1),
(3, 'KKtGjBpTq9VAI', 1, 1, 1, 1, 1, 1, 1, 1, 1, 1),
(4, 'lABWe6poOzMcM', 1, 1, 1, 1, 1, 1, 1, 1, 1, 1),
(5, 'Npqap0xFEsMlT', 1, 1, 0, 0, 0, 1, 1, 1, 0, 1),
(23, '64a25c0fb64af', 0, 0, 1, 1, 0, 1, 0, 0, 0, 0),
(24, '64a25f3aa0205', 1, 1, 0, 1, 0, 0, 1, 1, 0, 0),
(25, '64a26224a3945', 1, 1, 1, 1, 0, 0, 1, 1, 1, 0),
(26, '64a27c37e0a61', 0, 0, 1, 1, 0, 0, 0, 0, 0, 1),
(28, '64aa96be7e74e', 1, 0, 1, 1, 0, 0, 0, 0, 0, 0),
(29, '64aae5739a8be', 1, 1, 0, 0, 0, 0, 0, 0, 0, 0);

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
(1, 'i1QjNuLJTMZIB', 'tyFmSQJWc9HwZ'),
(5, 'lABWe6poOzMcM', 'tyFmSQJWc9HwZ'),
(20, 'Npqap0xFEsMlT', 'qzPHvK8kHTy3i'),
(27, 'KKtGjBpTq9VAI', '649bb4d1e1b2c'),
(28, 'i1QjNuLJTMZIB', '649eabd1c976f'),
(30, 'lABWe6poOzMcM', '649eabd1c976f'),
(31, 'KKtGjBpTq9VAI', '649eabd1c976f'),
(32, 'i1QjNuLJTMZIB', '64a1bb8752f90'),
(33, 'KKtGjBpTq9VAI', '64a1bb8752f90'),
(34, 'lABWe6poOzMcM', '64a1bb8752f90'),
(36, 'Npqap0xFEsMlT', '649c42845fed0'),
(37, 'i1QjNuLJTMZIB', '649c42845fed0'),
(38, '64aa96be7e74e', '64aaeb0d37d85');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_chatrooms`
--

CREATE TABLE `tbl_chatrooms` (
  `id` int(11) NOT NULL,
  `unique_code` text NOT NULL,
  `chatroom_code` text NOT NULL,
  `to_user` varchar(13) NOT NULL,
  `from_user` varchar(13) NOT NULL,
  `pay_rent` int(11) DEFAULT 0,
  `time` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_chatrooms`
--

INSERT INTO `tbl_chatrooms` (`id`, `unique_code`, `chatroom_code`, `to_user`, `from_user`, `pay_rent`, `time`) VALUES
(1, 'i1QjNuLJTMZIB', '6498ebe454c61', '64943d0f7d484', 'qzPHvK8kHTy3i', 1, 1687743460),
(2, 'i1QjNuLJTMZIB', '6498ebe454c61', 'qzPHvK8kHTy3i', '64943d0f7d484', 1, 1687743460),
(3, 'Npqap0xFEsMlT', '64990acee5a85', 'qzPHvK8kHTy3i', 'LhVQ3FMv6d6lW', 0, 1687751374),
(4, 'Npqap0xFEsMlT', '64990acee5a85', 'LhVQ3FMv6d6lW', 'qzPHvK8kHTy3i', 0, 1687751374),
(5, 'i1QjNuLJTMZIB', '64994eca2e3df', '64994e86cdb49', 'qzPHvK8kHTy3i', 0, 1687768778),
(6, 'i1QjNuLJTMZIB', '64994eca2e3df', 'qzPHvK8kHTy3i', '64994e86cdb49', 0, 1687768778),
(7, 'KKtGjBpTq9VAI', '6499c50aacb1e', '64966ba98d0d1', 'LhVQ3FMv6d6lW', 0, 1687799050),
(8, 'KKtGjBpTq9VAI', '6499c50aacb1e', 'LhVQ3FMv6d6lW', '64966ba98d0d1', 0, 1687799050),
(9, 'lABWe6poOzMcM', '6499ca7deeb2e', '64966ba98d0d1', 'LhVQ3FMv6d6lW', 0, 1687800445),
(10, 'lABWe6poOzMcM', '6499ca7deeb2e', 'LhVQ3FMv6d6lW', '64966ba98d0d1', 0, 1687800446),
(11, 'Npqap0xFEsMlT', '6499ca8a3bdb6', '64966ba98d0d1', 'LhVQ3FMv6d6lW', 0, 1687800458),
(12, 'Npqap0xFEsMlT', '6499ca8a3bdb6', 'LhVQ3FMv6d6lW', '64966ba98d0d1', 0, 1687800458),
(13, 'Npqap0xFEsMlT', '649c42c53328a', '649c42845fed0', 'LhVQ3FMv6d6lW', 1, 1687962309),
(14, 'Npqap0xFEsMlT', '649c42c53328a', 'LhVQ3FMv6d6lW', '649c42845fed0', 1, 1687962309),
(15, 'i1QjNuLJTMZIB', '649d201b8e288', '649c4f24ac2ae', 'qzPHvK8kHTy3i', 0, 1688018971),
(16, 'i1QjNuLJTMZIB', '649d201b8e288', 'qzPHvK8kHTy3i', '649c4f24ac2ae', 0, 1688018971),
(17, 'i1QjNuLJTMZIB', '649ee6cc60d4b', '649eabd1c976f', 'qzPHvK8kHTy3i', 0, 1688135372),
(18, 'i1QjNuLJTMZIB', '649ee6cc60d4b', 'qzPHvK8kHTy3i', '649eabd1c976f', 0, 1688135372),
(19, 'i1QjNuLJTMZIB', '64a2587490337', '64a1bb8752f90', 'qzPHvK8kHTy3i', 0, 1688361076),
(20, 'i1QjNuLJTMZIB', '64a2587490337', 'qzPHvK8kHTy3i', '64a1bb8752f90', 0, 1688361076),
(21, 'KKtGjBpTq9VAI', '64a2ddaae6ed4', '64a1bb8752f90', 'qzPHvK8kHTy3i', 0, 1688395178),
(22, 'KKtGjBpTq9VAI', '64a2ddaae6ed4', 'qzPHvK8kHTy3i', '64a1bb8752f90', 0, 1688395178),
(23, '64a25c0fb64af', '64a8d450e39ba', '649c42845fed0', '64a24fe0dfdb9', 0, 1688786000),
(24, '64a25c0fb64af', '64a8d450e39ba', '64a24fe0dfdb9', '649c42845fed0', 0, 1688786000),
(25, 'i1QjNuLJTMZIB', '64aa2fadceff4', 'LhVQ3FMv6d6lW', 'qzPHvK8kHTy3i', 0, 1688874925),
(26, 'i1QjNuLJTMZIB', '64aa2fadceff4', 'qzPHvK8kHTy3i', 'LhVQ3FMv6d6lW', 0, 1688874925),
(27, 'Npqap0xFEsMlT', '64aa4d1483dd1', '64a1bb8752f90', 'LhVQ3FMv6d6lW', 0, 1688882452),
(28, 'Npqap0xFEsMlT', '64aa4d1483dd1', 'LhVQ3FMv6d6lW', '64a1bb8752f90', 0, 1688882452),
(29, '64aa96be7e74e', '64aaeb2c44316', '64aaeb0d37d85', '64a8ef03659ba', 0, 1688922924),
(30, '64aa96be7e74e', '64aaeb2c44316', '64a8ef03659ba', '64aaeb0d37d85', 0, 1688922924),
(31, 'i1QjNuLJTMZIB', '64ab83c5e0668', '64ab832907075', 'qzPHvK8kHTy3i', 1, 1688961989),
(32, 'i1QjNuLJTMZIB', '64ab83c5e0668', 'qzPHvK8kHTy3i', '64ab832907075', 1, 1688961989);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_chats`
--

CREATE TABLE `tbl_chats` (
  `id` int(11) NOT NULL,
  `itr` int(11) NOT NULL,
  `chatroom_code` text NOT NULL,
  `user_id` varchar(13) NOT NULL,
  `message` text NOT NULL,
  `image` text NOT NULL,
  `time` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_chats`
--

INSERT INTO `tbl_chats` (`id`, `itr`, `chatroom_code`, `user_id`, `message`, `image`, `time`) VALUES
(22, 1, '6498ebe454c61', '64943d0f7d484', 'I would like to inquire', '', 1687799783),
(23, 2, '6498ebe454c61', '64943d0f7d484', 'For 2 persons', '', 1687799892),
(24, 1, '6499ca7deeb2e', '64966ba98d0d1', 'hi', '', 1687800449),
(25, 1, '6499ca8a3bdb6', '64966ba98d0d1', 'Interested !', '', 1687800560),
(26, 3, '6498ebe454c61', '64943d0f7d484', 'Hello', '', 1687856715),
(27, 2, '6499ca8a3bdb6', '64966ba98d0d1', 'hello, i am interested! is this available for single occupancy?', '', 1687926262),
(29, 1, '649c42c53328a', '649c42845fed0', 'I am interested! Is there any unit open for single occupancy?', '', 1687962859),
(37, 1, '64a2587490337', '64a1bb8752f90', 'hi', '', 1688366673),
(42, 7, '6498ebe454c61', 'qzPHvK8kHTy3i', 'Sure!', '', 1688824353),
(47, 1, '64aaeb2c44316', '64aaeb0d37d85', 'Is there any available for single units?', '', 1688922938),
(48, 2, '649c42c53328a', 'LhVQ3FMv6d6lW', 'Yes. :)', '', 1688923159),
(50, 3, '649c42c53328a', 'LhVQ3FMv6d6lW', 'Still interested?', '', 1688923607),
(51, 1, '64a2ddaae6ed4', '64a1bb8752f90', 'Hello :)', '', 1688923773),
(52, 2, '64a2ddaae6ed4', '64a1bb8752f90', '', 'uploads/chatrooms/64a2ddaae6ed4/64aaee830bcba.jpeg', 1688923779),
(53, 4, '649c42c53328a', '649c42845fed0', 'Yes', '', 1688923915),
(54, 1, '64ab83c5e0668', '64ab832907075', 'Hello', '', 1688962000),
(55, 2, '64ab83c5e0668', 'qzPHvK8kHTy3i', 'Hi', '', 1688962110);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_documents`
--

CREATE TABLE `tbl_documents` (
  `id` int(11) NOT NULL,
  `doc_1` text NOT NULL,
  `doc1_status` int(11) NOT NULL,
  `user_id` varchar(13) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_documents`
--

INSERT INTO `tbl_documents` (`id`, `doc_1`, `doc1_status`, `user_id`) VALUES
(15, 'doc1_sample.pdf', 1, 'LhVQ3FMv6d6lW'),
(16, 'doc2_sample.pdf', 1, 'LhVQ3FMv6d6lW'),
(17, 'doc1_sample.pdf', 1, 'qzPHvK8kHTy3i'),
(18, 'doc2_sample.pdf', 1, 'qzPHvK8kHTy3i'),
(25, 'doc1_sample.pdf', 1, '64a2600cbd83c'),
(26, 'doc2_sample.pdf', 1, '64a2600cbd83c'),
(27, 'doc1_sample.pdf', 1, '64a2798aea1d3'),
(28, 'doc2_sample.pdf', 1, '64a2798aea1d3'),
(29, 'doc1_sample.pdf', 1, '64a8ef03659ba'),
(30, 'doc2_sample.pdf', 1, '64a8ef03659ba'),
(33, 'doc1_sample.pdf', 1, '64a24fe0dfdb9'),
(34, 'doc2_sample.pdf', 1, '64a24fe0dfdb9'),
(35, 'doc1_sample.pdf', 1, '64aa32a720270'),
(36, 'doc2_sample.pdf', 1, '64aa32a720270');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_dormreports`
--

CREATE TABLE `tbl_dormreports` (
  `id` int(11) NOT NULL,
  `dormref` varchar(13) DEFAULT NULL,
  `userref` varchar(13) DEFAULT NULL,
  `comment` text DEFAULT NULL,
  `createdAt` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_dormreports`
--

INSERT INTO `tbl_dormreports` (`id`, `dormref`, `userref`, `comment`, `createdAt`) VALUES
(17, 'KKtGjBpTq9VAI', '64a1bb8752f90', 'The information are misleading', '2023-07-09');

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
(2, 'LhVQ3FMv6d6lW', 'Npqap0xFEsMlT', 2, 'Fantastic dorm listing! Conveniently located near shops and public transportation. Clean rooms, friendly staff, and great amenities. Highly recommended!', '2022-04-05'),
(3, 'qzPHvK8kHTy3i', 'lABWe6poOzMcM', 5, 'This dorm listing exceeded my expectations. Comfortable beds, spacious rooms, and a warm atmosphere. The staff was welcoming and attentive. A wonderful experience overall!\n', '2022-05-25'),
(8, 'qzPHvK8kHTy3i', 'i1QjNuLJTMZIB', 4, 'Convenient location, clean rooms, and a friendly community. The management provided exceptional service. I would choose this place again!', '2022-12-06'),
(12, 'LhVQ3FMv6d6lW', 'i1QjNuLJTMZIB', 5, 'Very accomodating', '2023-06-25'),
(14, 'qzPHvK8kHTy3i', 'KKtGjBpTq9VAI', 5, 'Accomodating', '2023-06-26'),
(649932059, '64a1bb8752f90', 'i1QjNuLJTMZIB', 4, 'Peaceful :)', '2023-07-09'),
(649932060, '649c42845fed0', 'KKtGjBpTq9VAI', 2, 'Unresponsive owner', '2023-07-09'),
(649932061, '64ab832907075', 'i1QjNuLJTMZIB', 4, 'Nice', '2023-07-10');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_dorms`
--

CREATE TABLE `tbl_dorms` (
  `id` varchar(13) NOT NULL,
  `userref` varchar(13) NOT NULL,
  `name` varchar(255) NOT NULL,
  `address` varchar(255) NOT NULL,
  `longitude` float(9,6) NOT NULL,
  `latitude` float(9,6) NOT NULL,
  `price` varchar(100) NOT NULL,
  `slots` int(11) DEFAULT 0,
  `desc` text NOT NULL,
  `hei` text NOT NULL,
  `images` text NOT NULL,
  `visitors` int(11) NOT NULL DEFAULT 0,
  `pets` int(11) NOT NULL DEFAULT 0,
  `curfew` int(11) NOT NULL DEFAULT 0,
  `payment_duration` varchar(255) NOT NULL,
  `payment_policy` text NOT NULL,
  `adv_dep` varchar(255) NOT NULL,
  `sec_dep` varchar(255) NOT NULL,
  `util` varchar(255) NOT NULL,
  `min_stay` varchar(255) NOT NULL,
  `hide` int(11) NOT NULL DEFAULT 0,
  `who_own_this_dorm` varchar(13) DEFAULT NULL,
  `paid_time` timestamp NULL DEFAULT NULL,
  `end_time` timestamp NULL DEFAULT NULL,
  `createdAt` date DEFAULT current_timestamp(),
  `updatedAt` date DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_dorms`
--

INSERT INTO `tbl_dorms` (`id`, `userref`, `name`, `address`, `longitude`, `latitude`, `price`, `slots`, `desc`, `hei`, `images`, `visitors`, `pets`, `curfew`, `payment_duration`, `payment_policy`, `adv_dep`, `sec_dep`, `util`, `min_stay`, `hide`, `who_own_this_dorm`, `paid_time`, `end_time`, `createdAt`, `updatedAt`) VALUES
('64a25c0fb64af', '64a24fe0dfdb9', 'Bartolome Housing', '2048-C Hipodromo, Sta. Mesa, Manila', 121.008942, 14.600983, '4500', 2, '2-bedroom room available for rent', 'PUP', 'rn_image_picker_lib_temp_40941f45-fe73-4db6-a348-3d8c6b1fa09a.jpg', 1, 0, 0, 'monthly', 'First come First Reserve', '', '', '', '', 0, NULL, NULL, NULL, '2023-07-03', '2023-07-03'),
('64a25f3aa0205', '64a25d8c9576a', 'Maui Oasis', 'Anonas, Sta. Mesa, Manila', 121.003670, 14.598096, '4000', 10, 'Maui Oasis is your tropical island-inspired medium rise condominium community in Sta. Mesa, Manila.', 'PUP,FEU,NU,CEU', 'rn_image_picker_lib_temp_d4c2cada-344c-4c3e-9c0f-9e937ded55ef.jpg,rn_image_picker_lib_temp_9acf215f-6bd0-471a-9a7e-e37cfc37f4d3.jpg,rn_image_picker_lib_temp_dd52c87c-81ec-4671-a5cf-a5214f341d87.jpg,rn_image_picker_lib_temp_fe95ccf8-c991-4480-8e0b-d12b5df91928.jpg,rn_image_picker_lib_temp_0383869e-4636-44bb-a00d-fef5230e6c2d.jpg,rn_image_picker_lib_temp_62313278-c7de-4220-bee6-ff63cf8ca5d2.jpg', 0, 0, 0, 'monthly', 'Pay First', '', '', '', '', 0, NULL, NULL, NULL, '2023-07-03', '2023-07-03'),
('64a26224a3945', '64a2600cbd83c', 'RSG Sta. Mesa ResiDens', 'Ramon Magsaysay Ave, Sta. Mesa, Manila', 121.009010, 14.601927, '8000', 5, 'Price Range\n₱8,000 - ₱10,000\nWith C.R. inside unit\n\nProperty features:\n• Roof deck garden with trellis\n• Jacuzzi\n• Gym\n• Social Hall\n• Commercial Area\n\n#Livethesweeylife\n\nPls Like and Share our page\nhttps://www.facebook.com/RSGSMRD/', 'PUP,FEU,NU', 'rn_image_picker_lib_temp_e97b1062-f7b1-460b-a6e1-f663be613fed.jpg,rn_image_picker_lib_temp_ba1386e0-0601-4d7d-be32-5bd7447ce95b.jpg,rn_image_picker_lib_temp_0f485cb8-18a8-4f23-8528-ec5068d522cd.jpg,rn_image_picker_lib_temp_4d13a8cb-686d-468f-aee4-dac14f59ee37.jpg', 1, 1, 0, 'monthly', 'Pay First', '', '', '', '', 0, NULL, NULL, NULL, '2023-07-03', '2023-07-03'),
('64a27c37e0a61', '64a2798aea1d3', 'Kochijoji Rentals', 'Anonas, Sta. Mesa Manila', 121.003670, 14.598096, '4000', 1, 'Commercial Space Rentals, Condominiums Transient and Rentals, Dormitory Rentals', 'PUP', 'rn_image_picker_lib_temp_3f991c6b-dfde-4622-8406-96a8703f7656.jpg,rn_image_picker_lib_temp_ff6dd686-5315-4946-9e42-831aedc764dc.jpg,rn_image_picker_lib_temp_9680dfa7-83bc-4112-bd3a-c1fa71d7da9c.jpg,rn_image_picker_lib_temp_335fc5f9-99e2-4c07-8e93-6aab99c734a2.jpg', 0, 0, 0, 'semiannually', 'Pay First', '', '', '', '', 0, NULL, NULL, NULL, '2023-07-03', '2023-07-03'),
('64aa96be7e74e', '64a8ef03659ba', 'Noli Residences', '736 T. Alfonso, Sampaloc, Manila', 120.976868, 14.605229, '11,000', 6, 'A spacious dormitory with numerous rooms, designed to accommodate a large number of residents. The dormitory is equipped with modern amenities and facilities to ensure comfort and convenience for all residents, offering a comfortable and inclusive living space.', 'FEU,UST', 'rn_image_picker_lib_temp_06267dbe-2cd8-45b6-8f0e-05e553b1bbb9.jpg,rn_image_picker_lib_temp_771a7f1f-0b89-47db-a1da-f9259470a482.jpg,rn_image_picker_lib_temp_ce2f860a-2d0a-4958-8826-09410da21e1f.jpg,rn_image_picker_lib_temp_ca5f3136-6900-4513-a7ae-f558ac4ec224.jpg,rn_image_picker_lib_temp_8776aaea-f793-4848-92c2-7dab1e017b2d.jpg,rn_image_picker_lib_temp_c3ce2580-3e9e-4c62-827b-a094dc43920c.jpg,rn_image_picker_lib_temp_72cf5112-4e5d-4323-9121-c0dee6b86425.jpg', 1, 0, 0, 'quarterly', 'Pay First', '', '', '', '', 0, NULL, NULL, NULL, '2023-07-09', '2023-07-09'),
('64aae5739a8be', '64aa32a720270', 'Rosquin Bldg', '703-711 MV Delos Santos St.,\nManila', 0.000000, 0.000000, '2222', 6, 'The dorm is a cozy and vibrant living space designed to accommodate students during their academic years. It offers a communal environment that fosters social interaction, personal growth, and shared experiences.', 'FEU,UST', 'rn_image_picker_lib_temp_ff38c80d-7e9a-4f54-bb76-dcf964942506.jpg,rn_image_picker_lib_temp_89d7cd93-8f0e-409e-a7d6-07ccf01e5785.jpg,rn_image_picker_lib_temp_06490568-39e8-4485-b84b-d7f1899d888d.jpg', 1, 0, 1, 'monthly', 'Pay First', '', '', '', '', 0, NULL, NULL, NULL, '2023-07-09', '2023-07-09'),
('i1QjNuLJTMZIB', 'qzPHvK8kHTy3i', 'Quimba', '851B Dos Castillas St., Sampaloc, Manila', 120.964409, 14.602771, '1937.13', 10, 'Discover the ideal dorm near your school with modern amenities, spacious rooms, and a vibrant community. Experience convenience, comfort, and a memorable student life.', 'UST,FEU', 'daria-shevtsova-RP4mtXJM7es-unsplash.jpg,fred-kleber-gTbaxaVLvsg-unsplash.jpg,gabriel-beaudry-WuQME0I_oZA-unsplash.jpg,lissete-laverde-9XdCMuK8zlQ-unsplash.jpg,marcus-loke-WQJvWU_HZFo-unsplash.jpg', 0, 0, 1, 'annually', 'Advance and security included in first payment', '500', '500', '', '', 0, '64ab832907075', '2023-07-17 09:44:09', '2023-07-17 00:00:00', '2022-02-14', '2023-06-29'),
('KKtGjBpTq9VAI', 'qzPHvK8kHTy3i', 'Tagcat', '303 Recto Ave, Binondo, Manila', 120.976868, 14.605229, '3214.48', 17, 'Introducing our exceptional dormitory located conveniently near your school. Experience comfortable living with modern amenities, spacious rooms, and a vibrant community. With easy access to campus and a supportive environment, our dorm provides the perfe', 'PUP,CEU,DLSU', 'nguyen-dang-hoang-nhu-HHs_PrvxSQk-unsplash.jpg,rnaol-oKHHspCSWHQ-unsplash.jpg,samuel-regan-asante-CbptaPcrFCc-unsplash.jpg,shashi-chaturvedula-UlHN7wFhtvU-unsplash.jpg,shashi-chaturvedula-xdY1s1I6J8U-unsplash.jpg', 1, 1, 1, 'monthly', 'Advance included in first payment', '500', '', '', '', 0, NULL, NULL, NULL, '2022-06-14', '2023-06-27'),
('lABWe6poOzMcM', 'LhVQ3FMv6d6lW', 'Zoov', '261 2nd St, Port Area, Manila', 120.968193, 14.594378, '2628.09', 5, 'Escape the hustle and bustle of the city in this charming dormitory nestled in a peaceful neighborhood. The rooms are cozy and well-equipped, providing a comfortable living environment. With beautiful green spaces nearby, it\'s the perfect place to relax and unwind after a long day of classes.', 'UP, UST, NU, FEU, PUP', 'sigmund-CwTfKH5edSk-unsplash.jpg,spacejoy-808a4AWu8jE-unsplash.jpg,spacejoy-vOa-PSimwg4-unsplash.jpg,taiga-ishii-mukO8Po_LZ8-unsplash.jpg', 1, 1, 1, 'monthly', 'Pay First', '', '', '', '', 1, NULL, NULL, NULL, '2021-04-04', '2023-07-09'),
('Npqap0xFEsMlT', 'LhVQ3FMv6d6lW', 'Zoonder', '71 Wagas St, Tondo, Manila', 120.963966, 14.606765, '1381.52', 1, 'Experience a vibrant and lively atmosphere in this dormitory known for its sense of community. The common areas are designed to foster social interaction and collaboration, with game rooms, a movie theater, and outdoor spaces for group activities. The individual rooms are spacious and offer a private sanctuary for studying and relaxation.', 'PUP,UP,FEU,UST,NU', 'alen-rojnic-T1Yvmf4oleQ-unsplash.jpg,gabriel-beaudry-WuQME0I_oZA-unsplash.jpg,samuel-regan-asante-CbptaPcrFCc-unsplash.jpg,spacejoy-808a4AWu8jE-unsplash.jpg', 0, 1, 1, 'monthly', 'Advance included in first payment', '1500', '', '', '12', 1, NULL, NULL, NULL, '2022-03-19', '2023-07-09');

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
  `status` text NOT NULL DEFAULT 'unread'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_notifications`
--

INSERT INTO `tbl_notifications` (`id`, `user_ref`, `title`, `ndesc`, `created`, `scheduled`, `notif_uniqid`, `status`) VALUES
(74, 'LhVQ3FMv6d6lW', 'StudyHive', 'Your documents have been verified! You can now publish your Dorm.', '2023-07-08 03:20:32', '2023-06-25 16:42:31', '6497c57b96b64', 'read'),
(75, 'qzPHvK8kHTy3i', 'StudyHive', 'Your documents have been verified! You can now publish your Dorm.', '2023-07-07 10:32:56', '2023-06-25 16:42:37', '6497c5819f537', 'read'),
(156, 'qzPHvK8kHTy3i', 'StudyHive', 'Received payment from examplePaymentMethodToken for Quimba - Willis with amount: ₱2500', '2023-07-07 10:32:56', '2023-06-30 10:04:33', '649e37f51a812', 'read'),
(157, 'qzPHvK8kHTy3i', 'StudyHive', 'Received payment from examplePaymentMethodToken for Tagcat - Willis with amount: ₱3214.48', '2023-07-07 10:32:56', '2023-06-30 10:26:11', '649e3d07c6d11', 'read'),
(169, '64a24fe0dfdb9', 'StudyHive', 'Your documents have been verified! You can now publish your Dorm.', '2023-07-09 14:38:14', '2023-07-03 12:39:10', '1', 'read'),
(170, '64a25d8c9576a', 'StudyHive', 'Your documents have been verified! You can now publish your Dorm.', '2023-07-03 13:34:27', '2023-07-03 13:35:27', '1', 'unread'),
(171, '64a2600cbd83c', 'StudyHive', 'Your documents have been verified! You can now publish your Dorm.', '2023-07-03 13:44:29', '2023-07-03 13:45:29', '1', 'unread'),
(172, '64a2798aea1d3', 'StudyHive', 'Your documents have been verified! You can now publish your Dorm.', '2023-07-03 15:34:13', '2023-07-03 15:35:13', '1', 'unread'),
(173, '64a1bb8752f90', 'StudyHive', 'Your documents have been verified! You can now publish your housing.', '2023-07-07 14:46:04', '2023-07-04 09:34:46', '64a376fabbb2f', 'read'),
(174, '64a1bb8752f90', 'StudyHive', 'We would like to notify you that your listing named \'awe\' has been removed. If you believe this removal was a mistake, kindly reach out to our team for assistance.', '2023-07-07 14:46:04', '2023-07-04 09:35:17', '64a377193568f', 'read'),
(175, 'qzPHvK8kHTy3i', 'StudyHive', 'Received payment from examplePaymentMethodToken for Quimba - Willis with amount: ₱1937.13', '2023-07-07 10:32:56', '2023-07-05 17:34:15', '64a538dbc8753', 'read'),
(176, 'qzPHvK8kHTy3i', 'StudyHive', 'We would like to notify you that your listing named \'Quimba\', has been set to be hidden due to being inactive. If you believe this action was a mistake, kindly reach out to our team for assistance.', '2023-07-07 10:32:56', '2023-07-06 19:22:37', '64a6a3c1440a9', 'read'),
(177, 'qzPHvK8kHTy3i', 'StudyHive', 'We would like to notify you that your listing named \'Quimba\' has been set to be unhidden. As a result, your listing is now visible and available for viewing. If you did not request this action or believe it was a mistake, please don\'t hesitate to contact our team immediately. ', '2023-07-07 10:32:56', '2023-07-06 19:23:00', '64a6a3d802b97', 'read'),
(178, 'qzPHvK8kHTy3i', 'StudyHive', 'We would like to notify you that your listing named \'Quimba\', has been set to be hidden due to being inactive. If you believe this action was a mistake, kindly reach out to our team for assistance.', '2023-07-07 10:32:56', '2023-07-06 20:38:02', '64a6b56e82626', 'read'),
(179, 'qzPHvK8kHTy3i', 'StudyHive', 'We would like to notify you that your listing named \'Quimba\' has been set to be unhidden. As a result, your listing is now visible and available for viewing. If you did not request this action or believe it was a mistake, please don\'t hesitate to contact our team immediately. ', '2023-07-07 10:32:56', '2023-07-06 20:38:12', '64a6b578a5e27', 'read'),
(189, '64a24fe0dfdb9', 'StudyHive', 'Your documents have not been verified! Please upload a new document.', '2023-07-09 14:38:14', '2023-07-09 00:12:20', '1', 'read'),
(190, '64a25d8c9576a', 'StudyHive', 'Your documents have not been verified! Please upload a new document.', '2023-07-09 00:11:31', '2023-07-09 00:12:31', '1', 'unread'),
(191, '64a8ef03659ba', 'StudyHive', 'Your documents have been verified! You can now publish your Dorm.', '2023-07-09 19:08:34', '2023-07-09 19:09:34', '1', 'unread'),
(192, 'LhVQ3FMv6d6lW', 'StudyHive', 'This is a reminder to update your listing named \'Zoonder\', for the upcoming semester. Please ensure your listing is up to date. If your listing is already current, you may disregard this message.', '2023-07-09 16:57:45', '2023-07-09 22:13:24', '64aac048e9116', 'read'),
(193, 'LhVQ3FMv6d6lW', 'StudyHive', 'We would like to notify you that your listing named \'Zoonder\', has been set to be hidden due to being inactive. If you believe this action was a mistake, kindly reach out to our team for assistance.', '2023-07-09 16:57:45', '2023-07-09 22:13:34', '64aac0520f16b', 'read'),
(194, '64a1bb8752f90', 'StudyHive', 'The dorm listing you reported has been resolved. Thank you for your contribution!', '2023-07-09 17:28:18', '2023-07-09 22:14:14', '64aac07a68ad5', 'read'),
(195, '64a24fe0dfdb9', 'StudyHive', 'This is a reminder to update your listing named \'Bartolome Housing\', for the upcoming semester. Please ensure your listing is up to date. If your listing is already current, you may disregard this message.', '2023-07-09 14:38:14', '2023-07-09 22:20:16', '64aac1e485d1c', 'read'),
(196, '64a24fe0dfdb9', 'StudyHive', 'We would like to notify you that your listing named \'Bartolome Housing\', has been set to be hidden due to being inactive. If you believe this action was a mistake, kindly reach out to our team for assistance.', '2023-07-09 14:38:14', '2023-07-09 22:20:23', '64aac1ebbce0c', 'read'),
(197, '64a24fe0dfdb9', 'StudyHive', 'We would like to notify you that your listing named \'Bartolome Housing\' has been set to be unhidden. As a result, your listing is now visible and available for viewing. If you did not request this action or believe it was a mistake, please don\'t hesitate to contact our team immediately. ', '2023-07-09 14:38:14', '2023-07-09 22:20:27', '64aac1ef35c16', 'read'),
(198, '64ab832907075', 'StudyHive', 'The dorm listing you reported has been resolved. Thank you for your contribution!', '2023-07-17 09:47:37', '2023-07-09 22:20:37', '64aac1f90e022', 'read'),
(200, '64a24fe0dfdb9', 'StudyHive', 'This is a reminder to update your listing named \'Bartolome Housing\', for the upcoming semester. Please ensure your listing is up to date. If your listing is already current, you may disregard this message.', '2023-07-09 14:38:14', '2023-07-09 22:24:55', '64aac2fbce518', 'read'),
(201, '64a24fe0dfdb9', 'StudyHive', 'We would like to notify you that your listing named \'Bartolome Housing\', has been set to be hidden due to being inactive. If you believe this action was a mistake, kindly reach out to our team for assistance.', '2023-07-09 14:38:14', '2023-07-09 22:25:01', '64aac301b42da', 'read'),
(202, '64a24fe0dfdb9', 'StudyHive', 'We would like to notify you that your listing named \'Bartolome Housing\' has been set to be unhidden. As a result, your listing is now visible and available for viewing. If you did not request this action or believe it was a mistake, please don\'t hesitate to contact our team immediately. ', '2023-07-09 14:38:14', '2023-07-09 22:25:04', '64aac304d91e8', 'read'),
(203, '649c42845fed0', 'StudyHive', 'The dorm listing you reported has been resolved. Thank you for your contribution!', '2023-07-09 17:32:39', '2023-07-09 22:25:17', '64aac31177c78', 'read'),
(205, '64a24fe0dfdb9', 'StudyHive', 'This is a reminder to update your listing named \'Bartolome Housing\', for the upcoming semester. Please ensure your listing is up to date. If your listing is already current, you may disregard this message.', '2023-07-09 14:38:14', '2023-07-09 22:28:56', '64aac3ec48b03', 'read'),
(206, '64a24fe0dfdb9', 'StudyHive', 'We would like to notify you that your listing named \'Bartolome Housing\', has been set to be hidden due to being inactive. If you believe this action was a mistake, kindly reach out to our team for assistance.', '2023-07-09 14:38:14', '2023-07-09 22:29:02', '64aac3f2969a9', 'read'),
(210, '64a24fe0dfdb9', 'StudyHive', 'We would like to notify you that your listing named \'Bartolome Housing\' has been set to be unhidden. As a result, your listing is now visible and available for viewing. If you did not request this action or believe it was a mistake, please don\'t hesitate to contact our team immediately. ', '2023-07-09 22:41:16', '2023-07-09 22:42:16', '64aac70c0a3e0', 'unread'),
(214, '64a24fe0dfdb9', 'StudyHive', 'Your documents have been verified! You can now publish your Dorm.', '2023-07-10 00:41:55', '2023-07-10 00:42:55', '1', 'unread'),
(215, '64aa32a720270', 'StudyHive', 'Your documents have been verified! You can now publish your Dorm.', '2023-07-09 16:42:38', '2023-07-10 00:42:59', '1', 'read'),
(217, '64a24fe0dfdb9', 'StudyHive', 'Your documents have been verified! You can now publish your Dorm.', '2023-07-10 00:42:11', '2023-07-10 00:43:11', '1', 'unread'),
(233, 'qzPHvK8kHTy3i', 'StudyHive', 'Received payment from examplePaymentMethodToken for Quimba - Willis with amount: ₱8748', '2023-07-17 14:29:24', '2023-07-17 14:30:24', '64b4dfc490a2e', 'unread'),
(234, 'qzPHvK8kHTy3i', 'StudyHive', 'Received payment from examplePaymentMethodToken for Quimba - Willis with amount: ₱7748', '2023-07-17 14:37:02', '2023-07-17 14:38:02', '64b4e18eeb8f5', 'unread'),
(235, '64ab832907075', 'StudyHive', 'Please note that your rent is to be paid today. You are given until tomorrow for your convenience.', '2023-07-17 09:47:37', '2023-07-17 09:43:21', '64b50d3998e68', 'read'),
(236, '64ab832907075', 'StudyHive', 'Please note that your rent is to be paid today. You are given until tomorrow for your convenience.', '2023-07-17 09:47:37', '2023-07-17 09:43:23', '64b50d3b08204', 'read'),
(237, '64ab832907075', 'StudyHive', 'Please note that your rent is to be paid today. You are given until tomorrow for your convenience.', '2023-07-17 09:47:37', '2023-07-17 09:43:23', '64b50d3be4bee', 'read'),
(239, '64ab832907075', 'StudyHive', 'Please note that your rent is to be paid today. You are given until tomorrow for your convenience.', '2023-07-17 09:47:37', '2023-07-17 09:45:34', '64b50dbe68dfa', 'read'),
(240, '64ab832907075', 'StudyHive', 'Please note that your rent is to be paid today. You are given until tomorrow for your convenience.', '2023-07-17 09:47:37', '2023-07-17 09:47:36', '64b50e386300c', 'read'),
(241, '64ab832907075', 'StudyHive', 'Please note that your rent is to be paid today. You are given until tomorrow for your convenience.', '2023-07-17 09:56:00', '2023-07-17 09:52:47', '64b50f6fb2ac8', 'read'),
(242, '64ab832907075', 'StudyHive', 'Please note that your rent is to be paid today. You are given until tomorrow for your convenience.', '2023-07-17 09:56:00', '2023-07-17 09:52:49', '64b50f710bf66', 'read'),
(243, '64ab832907075', 'StudyHive', 'Please note that your rent is to be paid today. You are given until tomorrow for your convenience.', '2023-07-17 09:56:00', '2023-07-17 09:52:57', '64b50f793bbc2', 'read'),
(244, '64ab832907075', 'StudyHive', 'Please note that your rent is to be paid today. You are given until tomorrow for your convenience.', '2023-07-17 09:56:00', '2023-07-17 09:55:41', '64b5101d8d2cb', 'read'),
(245, '64ab832907075', 'StudyHive', 'Please note that your rent is to be paid today. You are given until tomorrow for your convenience.', '2023-07-17 09:56:00', '2023-07-17 09:55:42', '64b5101ee6d83', 'read'),
(246, '64ab832907075', 'StudyHive', 'Please note that your rent is to be paid today. You are given until tomorrow for your convenience.', '2023-07-17 10:21:22', '2023-07-17 09:56:18', '64b510424e873', 'read'),
(247, '64ab832907075', 'StudyHive', 'Please note that your rent is to be paid today. You are given until tomorrow for your convenience.', '2023-07-17 10:21:22', '2023-07-17 10:09:14', '64b5134adb5c2', 'read'),
(248, '64ab832907075', 'StudyHive', 'Please note that your rent is to be paid today. You are given until tomorrow for your convenience.', '2023-07-17 10:21:22', '2023-07-17 10:09:15', '64b5134bb179a', 'read'),
(249, '64ab832907075', 'StudyHive', 'Please note that your rent is to be paid today. You are given until tomorrow for your convenience.', '2023-07-17 10:21:22', '2023-07-17 10:10:07', '64b5137fad522', 'read'),
(250, '64ab832907075', 'StudyHive', 'Please note that your rent is to be paid today. You are given until tomorrow for your convenience.', '2023-07-17 10:21:22', '2023-07-17 10:10:53', '64b513adb170d', 'read'),
(251, '64ab832907075', 'StudyHive', 'Please note that your rent is to be paid today. You are given until tomorrow for your convenience.', '2023-07-17 10:21:22', '2023-07-17 10:10:54', '64b513ae7437b', 'read'),
(252, '64ab832907075', 'StudyHive', 'Please note that your rent is to be paid today. You are given until tomorrow for your convenience.', '2023-07-17 10:21:22', '2023-07-17 10:10:55', '64b513af53033', 'read'),
(253, '64ab832907075', 'StudyHive', 'Please note that your rent is to be paid today. You are given until tomorrow for your convenience.', '2023-07-17 10:21:22', '2023-07-17 10:10:56', '64b513b02097e', 'read'),
(254, '64ab832907075', 'StudyHive', 'Please note that your rent is to be paid today. You are given until tomorrow for your convenience.', '2023-07-17 10:21:22', '2023-07-17 10:10:56', '64b513b0e1ff1', 'read');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_notif_fcmkeys`
--

CREATE TABLE `tbl_notif_fcmkeys` (
  `id` int(11) NOT NULL,
  `user_ref` varchar(13) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `fcm_key` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tbl_notif_fcmkeys`
--

INSERT INTO `tbl_notif_fcmkeys` (`id`, `user_ref`, `fcm_key`, `created_at`) VALUES
(89, '64a8ef03659ba', 'f4gNxHm3S_mR1BhIS_RWIH:APA91bHI31FpXLdP7VmK5eldsUn-umUtGaFenjnVf9nAlam4su1uEZ0AlCzdkrDG5Bog8YeTjBsliB2ztCwUWw2LH1QHRGw3pMEfY3T1BUROIEoX5m7kW21xgp90J6cr4JOprzBI167W', '2023-07-09 19:05:42'),
(91, '64a24fe0dfdb9', 'dY2cRR8CShmPxoLNgvCuLs:APA91bGhhJUg4MoZ-xzzRNzyHT2yhlPfnOP5ydowF-vIBza-94hezyEuosA6WUOri3VAqBfXirLsJtFuzfbNHeUp84rt8Y3eYZwxyePevNBIggcrLHm_JRlMc1nzCDaWdtVA3kWkufy1', '2023-07-09 22:35:27'),
(102, '64aa32a720270', 'dWy_4yO7Rw69zKgV-QeaNm:APA91bF9a6N09nZrxIOyHW2MHWAmkt4z4aBuE5djS7rS1dl1Z9Rf55qqpNbo4AQjjUhhFTEtxq5lIa6a2vlGNpMPZGa-ticV4c-u6KSwgo7OpCG_piijObOh5VKxXbb-033Pgd2NqZmV', '2023-07-10 05:05:21'),
(117, '64ab832907075', 'dhnECoPhSlmCDvIOCoFaVZ:APA91bH0OHwJs5eAo-KvFwztNGPw9eTyEAUvHQKmW3cXYXmp3OBRUPvsIgo_DWloMZokeIqqEMTn8QTk-Z5LFA3laEIeTtbgeYu2sjXDCiF0Qa-Z8gddw5Ij-FGuOcIg0wLKXBqZ9g0Z', '2023-07-17 15:44:58');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_transactions`
--

CREATE TABLE `tbl_transactions` (
  `id` varchar(13) NOT NULL,
  `token` varchar(255) NOT NULL,
  `userref` varchar(13) NOT NULL,
  `ownerref` varchar(13) NOT NULL,
  `ownername` varchar(255) NOT NULL,
  `dormref` varchar(13) NOT NULL,
  `amount` decimal(6,2) NOT NULL,
  `has_reviewed` int(11) NOT NULL DEFAULT 0,
  `timestamp` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_transactions`
--

INSERT INTO `tbl_transactions` (`id`, `token`, `userref`, `ownerref`, `ownername`, `dormref`, `amount`, `has_reviewed`, `timestamp`) VALUES
('649e37f51a4cb', 'Visa', '64943d0f7d484', 'qzPHvK8kHTy3i', 'Quimba - Willis', 'i1QjNuLJTMZIB', '2500.00', 1, '2023-07-17 05:41:10'),
('649e3d07c6bd4', 'Mastercard', '649c42845fed0', 'qzPHvK8kHTy3i', 'Tagcat - Willis', 'KKtGjBpTq9VAI', '3214.48', 1, '2023-07-17 05:41:10'),
('64a538dbc84b9', 'Mastercard', '64a1bb8752f90', 'qzPHvK8kHTy3i', 'Quimba - Willis', 'i1QjNuLJTMZIB', '1937.13', 1, '2023-07-17 05:41:10'),
('64b4dfc4906a8', 'examplePaymentMethodToken', '64ab832907075', 'qzPHvK8kHTy3i', 'Quimba - Willis', 'i1QjNuLJTMZIB', '8748.00', 0, '2023-07-17 06:29:24'),
('64b4e18eeb727', 'examplePaymentMethodToken', '64ab832907075', 'qzPHvK8kHTy3i', 'Quimba - Willis', 'i1QjNuLJTMZIB', '7748.00', 0, '2023-07-17 06:37:02'),
('64b50d69695c2', 'External Payment', '64ab832907075', 'qzPHvK8kHTy3i', 'Quimba - Willis', 'i1QjNuLJTMZIB', '7748.00', 0, '2023-07-17 09:44:09');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_users`
--

CREATE TABLE `tbl_users` (
  `id` varchar(13) NOT NULL,
  `username` varchar(255) NOT NULL,
  `identifier` varchar(255) NOT NULL,
  `imageUrl` varchar(255) NOT NULL DEFAULT 'http://studyhive.social/uploads/userImages/default/default-user-img.jpg',
  `is_verified` int(11) NOT NULL DEFAULT 0 COMMENT '0 - none\r\n1 - verified',
  `password` varchar(255) NOT NULL,
  `is_online` int(1) NOT NULL DEFAULT 0,
  `is_forgot` int(11) NOT NULL DEFAULT 0,
  `unique_forgot` text DEFAULT '',
  `unique_verifykey` text DEFAULT NULL,
  `is_email_verified` int(11) NOT NULL DEFAULT 0,
  `created_at` date NOT NULL,
  `updated_at` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_users`
--

INSERT INTO `tbl_users` (`id`, `username`, `identifier`, `imageUrl`, `is_verified`, `password`, `is_online`, `is_forgot`, `unique_forgot`, `unique_verifykey`, `is_email_verified`, `created_at`, `updated_at`) VALUES
('649bb4d1e1b2c', 'Soidemer', 'emzcaberz42107@gmail..com', 'http://studyhive.social/uploads/userImages/default/default-user-img.jpg', 0, '123', 0, 0, NULL, '', 0, '2023-06-28', '2023-06-28'),
('649c42845fed0', 'triciacgilo1', 'gilotriciac@gmail.com', 'https://studyhive.social/uploads/userImages/649c42845fed0/rn_image_picker_lib_temp_8c22a1a2-afe9-4be5-b6e8-275d75ebe6fd.jpg', 0, 'newpassword', 0, 0, NULL, '', 0, '2023-06-28', '2023-07-09'),
('649c4f24ac2ae', 'harvs05', 'arboledaharvs@gmail.com', 'http://studyhive.social/uploads/userImages/default/default-user-img.jpg', 0, '123456', 0, 0, NULL, '', 0, '2023-06-28', '2023-07-09'),
('649eabd1c976f', 'Studyhive1', 'info.studyhive@gmail.com', 'https://lh3.googleusercontent.com/a/AAcHTteRzfhtXOBWCWJXsf7T_Ih1E8M9JPGQGmWXVQYAnzox-A', 0, '12345678', 0, 0, NULL, '', 0, '2023-06-30', '2023-07-09'),
('64a1bb8752f90', 'RV ', 'trashaccoun12345@gmail.com', 'http://studyhive.social/uploads/userImages/default/default-user-img.jpg', 0, '12345678', 0, 0, NULL, '', 0, '2023-07-02', '2023-07-02'),
('64a24fe0dfdb9', 'Owner1', 'owner1@gmail.com', 'http://studyhive.social/uploads/userImages/default/default-user-img.jpg', 1, '12345678', 0, 0, NULL, '', 0, '2023-07-03', '2023-07-03'),
('64a25d8c9576a', 'Owner2', 'owner2@gmail.com', 'http://studyhive.social/uploads/userImages/default/default-user-img.jpg', 0, '12345678', 0, 0, NULL, '', 0, '2023-07-03', '2023-07-03'),
('64a2600cbd83c', 'Owner3', 'owner3@gmail.com', 'http://studyhive.social/uploads/userImages/default/default-user-img.jpg', 1, '12345678', 0, 0, NULL, '', 0, '2023-07-03', '2023-07-03'),
('64a2798aea1d3', 'Kochijoji', 'owner4@gmail.com', 'http://studyhive.social/uploads/userImages/default/default-user-img.jpg', 1, '12345678', 0, 0, NULL, '', 0, '2023-07-03', '2023-07-03'),
('64a8ef03659ba', 'kuya_mar', 'owner5@gmail.com', 'http://studyhive.social/uploads/userImages/default/default-user-img.jpg', 1, 'rosquinbldg', 0, 0, NULL, '', 0, '2023-07-08', '2023-07-08'),
('64aa32a720270', 'rosquin', 'rosquin12@gmail.com', 'http://studyhive.social/uploads/userImages/default/default-user-img.jpg', 1, '12345678', 0, 0, NULL, '', 0, '2023-07-09', '2023-07-09'),
('64aaeb0d37d85', 'studyhive', 'info.studyhive@gmail.com', 'http://studyhive.social/uploads/userImages/default/default-user-img.jpg', 0, '12345678', 0, 0, '', '', 0, '2023-07-09', '2023-07-09'),
('64ab827a93f11', 'tricia123', 'gilotricia@gmail.com', 'http://studyhive.social/uploads/userImages/default/default-user-img.jpg', 0, 'tricia123', 0, 0, '', '', 0, '2023-07-10', '2023-07-10'),
('64ab82a3af3f7', 'tricia123', 'triciagilo03@gmail.com', 'http://studyhive.social/uploads/userImages/default/default-user-img.jpg', 0, 'tricia123', 0, 0, '', '', 0, '2023-07-10', '2023-07-10'),
('64ab832907075', 'Justin', 'justlara17@gmail.com', 'http://studyhive.social/uploads/userImages/default/default-user-img.jpg', 0, '12345678', 1, 0, NULL, NULL, 1, '2023-07-10', '2023-07-10'),
('64b4e9e43283b', 'Joanna', 'joannalara21@gmail.com', 'http://studyhive.social/uploads/userImages/default/default-user-img.jpg', 0, '12345678', 0, 0, '', NULL, 1, '2023-07-17', '2023-07-17'),
('LhVQ3FMv6d6lW', 'Juan Dela Cruz', 'juandlcruz@gmail.com', 'https://studyhive.social/uploads/userImages/LhVQ3FMv6d6lW/1.jpg', 1, 'newpassword', 0, 0, NULL, '', 0, '2022-04-01', '2023-07-09'),
('qzPHvK8kHTy3i', 'Willis', 'williStan@yahoo.com', 'http://studyhive.social/uploads/userImages/qzPHvK8kHTy3i/7.jpg', 1, 'abcdefghi', 0, 0, NULL, '', 0, '2022-03-02', '2023-06-26'),
('tyFmSQJWc9HwZ', 'Giacobo', 'gziemke2@tamu.edu', 'http://studyhive.social/uploads/userImages/tyFmSQJWc9HwZ/5.jpg', 0, 'asdfghjk', 0, 0, NULL, '', 0, '2022-04-29', '2023-10-30');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tbl_adminusers`
--
ALTER TABLE `tbl_adminusers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_amenities`
--
ALTER TABLE `tbl_amenities`
  ADD PRIMARY KEY (`id`),
  ADD KEY `dormref` (`dormref`);

--
-- Indexes for table `tbl_bookmarks`
--
ALTER TABLE `tbl_bookmarks`
  ADD PRIMARY KEY (`id`),
  ADD KEY `dormref` (`dormref`),
  ADD KEY `userref` (`userref`);

--
-- Indexes for table `tbl_chatrooms`
--
ALTER TABLE `tbl_chatrooms`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_chats`
--
ALTER TABLE `tbl_chats`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_documents`
--
ALTER TABLE `tbl_documents`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `tbl_dormreports`
--
ALTER TABLE `tbl_dormreports`
  ADD PRIMARY KEY (`id`),
  ADD KEY `dormref` (`dormref`),
  ADD KEY `userref` (`userref`);

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
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_ref` (`user_ref`);

--
-- Indexes for table `tbl_notif_fcmkeys`
--
ALTER TABLE `tbl_notif_fcmkeys`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_ref` (`user_ref`);

--
-- Indexes for table `tbl_transactions`
--
ALTER TABLE `tbl_transactions`
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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=55;

--
-- AUTO_INCREMENT for table `tbl_amenities`
--
ALTER TABLE `tbl_amenities`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT for table `tbl_bookmarks`
--
ALTER TABLE `tbl_bookmarks`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

--
-- AUTO_INCREMENT for table `tbl_chatrooms`
--
ALTER TABLE `tbl_chatrooms`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT for table `tbl_chats`
--
ALTER TABLE `tbl_chats`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=56;

--
-- AUTO_INCREMENT for table `tbl_documents`
--
ALTER TABLE `tbl_documents`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT for table `tbl_dormreports`
--
ALTER TABLE `tbl_dormreports`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `tbl_dormreviews`
--
ALTER TABLE `tbl_dormreviews`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=649932062;

--
-- AUTO_INCREMENT for table `tbl_notifications`
--
ALTER TABLE `tbl_notifications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=255;

--
-- AUTO_INCREMENT for table `tbl_notif_fcmkeys`
--
ALTER TABLE `tbl_notif_fcmkeys`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=118;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `tbl_amenities`
--
ALTER TABLE `tbl_amenities`
  ADD CONSTRAINT `tbl_amenities_ibfk_1` FOREIGN KEY (`dormref`) REFERENCES `tbl_dorms` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `tbl_bookmarks`
--
ALTER TABLE `tbl_bookmarks`
  ADD CONSTRAINT `tbl_bookmarks_ibfk_1` FOREIGN KEY (`dormref`) REFERENCES `tbl_dorms` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `tbl_bookmarks_ibfk_2` FOREIGN KEY (`userref`) REFERENCES `tbl_users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `tbl_documents`
--
ALTER TABLE `tbl_documents`
  ADD CONSTRAINT `tbl_documents_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `tbl_users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `tbl_dormreports`
--
ALTER TABLE `tbl_dormreports`
  ADD CONSTRAINT `tbl_dormreports_ibfk_1` FOREIGN KEY (`dormref`) REFERENCES `tbl_dorms` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `tbl_dormreports_ibfk_2` FOREIGN KEY (`userref`) REFERENCES `tbl_users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

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

--
-- Constraints for table `tbl_notifications`
--
ALTER TABLE `tbl_notifications`
  ADD CONSTRAINT `tbl_notifications_ibfk_1` FOREIGN KEY (`user_ref`) REFERENCES `tbl_users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `tbl_notif_fcmkeys`
--
ALTER TABLE `tbl_notif_fcmkeys`
  ADD CONSTRAINT `tbl_notif_fcmkeys_ibfk_1` FOREIGN KEY (`user_ref`) REFERENCES `tbl_users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
