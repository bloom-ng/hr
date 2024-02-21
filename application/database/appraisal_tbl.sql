-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Feb 17, 2024 at 04:36 PM
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
-- Database: `bloom_employee`
--

-- --------------------------------------------------------

--
-- Table structure for table `appraisal_tbl`
--

CREATE TABLE `appraisal_tbl` (
  `id` int(11) NOT NULL,
  `staff_id` int(11) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `job_title` varchar(255) DEFAULT NULL,
  `department_id` int(11) DEFAULT NULL,
  `department_name` varchar(255) DEFAULT NULL, 
  `date` varchar(255) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `performance` int(11) DEFAULT NULL,
  `performance_comment` text DEFAULT NULL,
  `skills` int(11) DEFAULT NULL,
  `skills_comment` text DEFAULT NULL,
  `quality` int(11) DEFAULT NULL,
  `quality_comment` text DEFAULT NULL,
  `communication` int(11) DEFAULT NULL,
  `communication_comment` text DEFAULT NULL,
  `teamwork` int(11) DEFAULT NULL,
  `teamwork_comment` text DEFAULT NULL,
  `goals` int(11) DEFAULT NULL,
  `goals_comment` text DEFAULT NULL,
  `projects_assigned` int(11) DEFAULT NULL,
  `projects_completed` int(11) DEFAULT NULL,
  `overall_performance` tinyint(1) DEFAULT NULL,
  `overall_performance_comment` text DEFAULT NULL,
  `job_knowledge` tinyint(1) DEFAULT NULL,
  `job_knowledge_comment` text DEFAULT NULL,
  `quality_of_work` tinyint(1) DEFAULT NULL,
  `quality_of_work_comment` text DEFAULT NULL,
  `communication_skills` tinyint(1) DEFAULT NULL,
  `communication_skills_comment` text DEFAULT NULL,
  `teamwork_collaboration` tinyint(1) DEFAULT NULL,
  `teamwork_collaboration_comment` text DEFAULT NULL,
  `achievement_of_goals` int(11) DEFAULT NULL,
  `achievement_of_goals_reason` text DEFAULT NULL,
  `assigned_projects_count` int(11) DEFAULT NULL,
  `completed_projects_count` int(11) DEFAULT NULL,
  `completion_of_projects_outcome` int(11) DEFAULT NULL,
  `completion_of_projects_reason` text DEFAULT NULL,
  `outstanding_job_knowledge` tinyint(1) DEFAULT NULL,
  `effective_communication` tinyint(1) DEFAULT NULL,
  `strong_team_player` tinyint(1) DEFAULT NULL,
  `innovative_thinking` tinyint(1) DEFAULT NULL,
  `adaptable_to_change` tinyint(1) DEFAULT NULL,
  `time_management` tinyint(1) DEFAULT NULL,
  `conflict_resolution` tinyint(1) DEFAULT NULL,
  `technical_skills_enhancement` tinyint(1) DEFAULT NULL,
  `goal_setting_and_achievement` tinyint(1) DEFAULT NULL,
  `communication_with_team_members` tinyint(1) DEFAULT NULL,
  `leadership_training` tinyint(1) DEFAULT NULL,
  `technical_skills_training` tinyint(1) DEFAULT NULL,
  `communication_skills_workshop` tinyint(1) DEFAULT NULL,
  `project_management_training` tinyint(1) DEFAULT NULL,
  `additional_comments` text DEFAULT NULL,
  `employee_self_assessment` text DEFAULT NULL,
  `manager_comments` text DEFAULT NULL,
  `action_plan_for_improvement` text DEFAULT NULL,
  `follow_up_meeting_schedule` date DEFAULT NULL,
  `status` varchar(50) DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `appraisal_tbl`
--
ALTER TABLE `appraisal_tbl`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `appraisal_tbl`
--
ALTER TABLE `appraisal_tbl`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
