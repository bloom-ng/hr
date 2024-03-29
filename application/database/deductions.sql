-- phpMyAdmin SQL Dump
-- version 4.9.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 05, 2024 at 01:08 PM
-- Server version: 5.7.33
-- PHP Version: 7.4.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


CREATE TABLE `deductions` (
  `id` int(11) NOT NULL,
  `staff_id` int(11) NOT NULL,
  `amount` bigint(20) NOT NULL DEFAULT '0',
  `date` date NOT NULL,
  `reason` longtext,
  `status` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;



ALTER TABLE `deductions`
  ADD PRIMARY KEY (`id`);


ALTER TABLE `deductions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
COMMIT;