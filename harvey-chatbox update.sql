-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 19, 2023 at 06:04 PM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

--
-- Database: `db_dormfinder`
--

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
  `time` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
-- Indexes for dumped tables
--

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
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tbl_chatrooms`
--
ALTER TABLE `tbl_chatrooms`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_chats`
--
ALTER TABLE `tbl_chats`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;
