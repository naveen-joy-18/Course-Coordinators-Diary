-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 25, 2024 at 05:21 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.1.25

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `students_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `eighth_semester`
--

CREATE TABLE `eighth_semester` (
  `id` int(11) NOT NULL,
  `usn` varchar(255) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `branch` varchar(255) DEFAULT NULL,
  `batch` varchar(255) DEFAULT NULL,
  `semester` varchar(255) DEFAULT NULL,
  `gender` varchar(50) DEFAULT NULL,
  `dateofbirth` date DEFAULT NULL,
  `gmail` varchar(255) DEFAULT NULL,
  `phonenumber` varchar(50) DEFAULT NULL,
  `address` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `fifth_semester`
--

CREATE TABLE `fifth_semester` (
  `id` int(11) NOT NULL,
  `usn` varchar(20) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `branch` varchar(50) DEFAULT NULL,
  `batch` varchar(50) DEFAULT NULL,
  `semester` int(11) DEFAULT NULL,
  `gender` varchar(10) DEFAULT NULL,
  `dateofbirth` date DEFAULT NULL,
  `gmail` varchar(255) DEFAULT NULL,
  `phonenumber` varchar(20) DEFAULT NULL,
  `address` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `first_semester`
--

CREATE TABLE `first_semester` (
  `id` int(11) NOT NULL,
  `usn` varchar(255) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `branch` varchar(255) DEFAULT NULL,
  `batch` varchar(255) DEFAULT NULL,
  `semester` varchar(255) DEFAULT NULL,
  `gender` varchar(50) DEFAULT NULL,
  `dateofbirth` date DEFAULT NULL,
  `gmail` varchar(255) DEFAULT NULL,
  `phonenumber` varchar(50) DEFAULT NULL,
  `address` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `fourth_semester`
--

CREATE TABLE `fourth_semester` (
  `id` int(11) NOT NULL,
  `usn` varchar(255) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `branch` varchar(255) DEFAULT NULL,
  `batch` varchar(255) DEFAULT NULL,
  `semester` varchar(255) DEFAULT NULL,
  `gender` varchar(50) DEFAULT NULL,
  `dateofbirth` date DEFAULT NULL,
  `gmail` varchar(255) DEFAULT NULL,
  `phonenumber` varchar(50) DEFAULT NULL,
  `address` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `qp_split_up`
--

CREATE TABLE `qp_split_up` (
  `id` int(11) NOT NULL,
  `semester` int(11) DEFAULT NULL,
  `sub_code` varchar(20) DEFAULT NULL,
  `sub_name` varchar(100) DEFAULT NULL,
  `which_ia` int(11) DEFAULT NULL,
  `total_marks` int(11) DEFAULT NULL,
  `co_data` text DEFAULT NULL,
  `co_totals` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`co_totals`)),
  `questions_data` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `second_semester`
--

CREATE TABLE `second_semester` (
  `id` int(11) NOT NULL,
  `usn` varchar(255) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `branch` varchar(255) DEFAULT NULL,
  `batch` varchar(255) DEFAULT NULL,
  `semester` varchar(255) DEFAULT NULL,
  `gender` varchar(50) DEFAULT NULL,
  `dateofbirth` date DEFAULT NULL,
  `gmail` varchar(255) DEFAULT NULL,
  `phonenumber` varchar(50) DEFAULT NULL,
  `address` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `seventh_semester`
--

CREATE TABLE `seventh_semester` (
  `id` int(11) NOT NULL,
  `usn` varchar(20) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `branch` varchar(50) DEFAULT NULL,
  `batch` varchar(50) DEFAULT NULL,
  `semester` int(11) DEFAULT NULL,
  `gender` varchar(10) DEFAULT NULL,
  `dateofbirth` date DEFAULT NULL,
  `gmail` varchar(255) DEFAULT NULL,
  `phonenumber` varchar(20) DEFAULT NULL,
  `address` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sixth_semester`
--

CREATE TABLE `sixth_semester` (
  `id` int(11) NOT NULL,
  `usn` varchar(255) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `branch` varchar(255) DEFAULT NULL,
  `batch` varchar(255) DEFAULT NULL,
  `semester` varchar(255) DEFAULT NULL,
  `gender` varchar(50) DEFAULT NULL,
  `dateofbirth` date DEFAULT NULL,
  `gmail` varchar(255) DEFAULT NULL,
  `phonenumber` varchar(50) DEFAULT NULL,
  `address` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `subjects`
--

CREATE TABLE `subjects` (
  `id` int(11) NOT NULL,
  `semester` int(11) DEFAULT NULL,
  `sub_code` varchar(20) DEFAULT NULL,
  `sub_name` varchar(100) DEFAULT NULL,
  `total_marks` int(11) DEFAULT NULL,
  `num_internals` int(11) DEFAULT NULL,
  `max_marks_each_internal` int(11) DEFAULT NULL,
  `theory_ia_marks` int(11) DEFAULT NULL,
  `mini_project_marks` int(11) DEFAULT NULL,
  `avg` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `third_semester`
--

CREATE TABLE `third_semester` (
  `id` int(11) NOT NULL,
  `usn` varchar(20) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `branch` varchar(50) DEFAULT NULL,
  `batch` varchar(50) DEFAULT NULL,
  `semester` int(11) DEFAULT NULL,
  `gender` varchar(10) DEFAULT NULL,
  `dateofbirth` date DEFAULT NULL,
  `gmail` varchar(255) DEFAULT NULL,
  `phonenumber` varchar(20) DEFAULT NULL,
  `address` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `eighth_semester`
--
ALTER TABLE `eighth_semester`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `fifth_semester`
--
ALTER TABLE `fifth_semester`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `usn` (`usn`);

--
-- Indexes for table `first_semester`
--
ALTER TABLE `first_semester`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `fourth_semester`
--
ALTER TABLE `fourth_semester`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `qp_split_up`
--
ALTER TABLE `qp_split_up`
  ADD PRIMARY KEY (`id`),
  ADD KEY `semester` (`semester`,`sub_code`,`sub_name`);

--
-- Indexes for table `second_semester`
--
ALTER TABLE `second_semester`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `seventh_semester`
--
ALTER TABLE `seventh_semester`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `usn` (`usn`);

--
-- Indexes for table `sixth_semester`
--
ALTER TABLE `sixth_semester`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `subjects`
--
ALTER TABLE `subjects`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `semester` (`semester`,`sub_code`,`sub_name`);

--
-- Indexes for table `third_semester`
--
ALTER TABLE `third_semester`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `usn` (`usn`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `eighth_semester`
--
ALTER TABLE `eighth_semester`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `fifth_semester`
--
ALTER TABLE `fifth_semester`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=72;

--
-- AUTO_INCREMENT for table `first_semester`
--
ALTER TABLE `first_semester`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `fourth_semester`
--
ALTER TABLE `fourth_semester`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `qp_split_up`
--
ALTER TABLE `qp_split_up`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- AUTO_INCREMENT for table `second_semester`
--
ALTER TABLE `second_semester`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `seventh_semester`
--
ALTER TABLE `seventh_semester`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=50;

--
-- AUTO_INCREMENT for table `sixth_semester`
--
ALTER TABLE `sixth_semester`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `subjects`
--
ALTER TABLE `subjects`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=56;

--
-- AUTO_INCREMENT for table `third_semester`
--
ALTER TABLE `third_semester`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=78;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `qp_split_up`
--
ALTER TABLE `qp_split_up`
  ADD CONSTRAINT `qp_split_up_ibfk_1` FOREIGN KEY (`semester`,`sub_code`,`sub_name`) REFERENCES `subjects` (`semester`, `sub_code`, `sub_name`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
