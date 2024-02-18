-- phpMyAdmin SQL Dump
-- version 4.9.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 18, 2024 at 12:41 AM
-- Server version: 5.7.33
-- PHP Version: 7.4.1


SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `bloom_employee`
--

-- --------------------------------------------------------

--
-- Table structure for table `staff_tbl`
--

CREATE TABLE `staff_tbl` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `staff_name` varchar(150) NOT NULL,
  `gender` varchar(10) NOT NULL,
  `email` varchar(150) NOT NULL,
  `mobile` bigint(20) NOT NULL,
  `dob` date NOT NULL,
  `doj` date NOT NULL,
  `address` text,
  `city` varchar(100) NOT NULL,
  `state` varchar(100) NOT NULL,
  `country` varchar(50) NOT NULL,
  `department_id` int(11) NOT NULL,
  `pic` varchar(150) NOT NULL DEFAULT 'default-pic.jpg',
  `added_by` int(11) NOT NULL,
  `updated_on` date NOT NULL,
  `added_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `staff_tbl`
--

INSERT INTO `staff_tbl` (`id`, `user_id`, `staff_name`, `gender`, `email`, `mobile`, `dob`, `doj`, `address`, `city`, `state`, `country`, `department_id`, `pic`, `added_by`, `updated_on`, `added_on`) VALUES
(2, NULL, 'Steven Askew', 'Male', 'steven@gmail.com', 7444440001, '1990-02-18', '2020-11-27', '3721  Hill Croft Farm Road', 'BURLINGTON', 'MI', 'United States', 1, 'smportrait.jpg', 0, '0000-00-00', '2021-05-27 15:37:03');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `staff_tbl`
--
ALTER TABLE `staff_tbl`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `staff_tbl`
--
ALTER TABLE `staff_tbl`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

