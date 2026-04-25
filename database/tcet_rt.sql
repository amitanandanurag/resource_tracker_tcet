-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 25, 2026 at 09:37 AM
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
-- Database: `tcet_rt`
--

-- --------------------------------------------------------

--
-- Table structure for table `st_audit_log`
--

CREATE TABLE `st_audit_log` (
  `audit_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `action_type` varchar(100) NOT NULL,
  `affected_table` varchar(100) DEFAULT NULL,
  `affected_record` int(11) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `performed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `st_class_master`
--

CREATE TABLE `st_class_master` (
  `class_id` int(11) NOT NULL,
  `class_name` varchar(50) NOT NULL,
  `date` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `st_class_master`
--

INSERT INTO `st_class_master` (`class_id`, `class_name`, `date`) VALUES
(1, 'FY', '2026-04-16 08:46:58'),
(2, 'SY', '2026-04-16 08:46:58'),
(3, 'TY', '2026-04-16 08:46:58'),
(4, 'FE', '2026-04-16 08:46:58'),
(5, 'SE', '2026-04-16 08:46:58'),
(6, 'TE', '2026-04-16 08:46:58'),
(7, 'BE', '2026-04-16 08:46:58');

-- --------------------------------------------------------

--
-- Table structure for table `st_department_master`
--

CREATE TABLE `st_department_master` (
  `department_id` int(11) NOT NULL,
  `department_name` varchar(150) NOT NULL,
  `date` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `st_department_master`
--

INSERT INTO `st_department_master` (`department_id`, `department_name`, `date`) VALUES
(1, 'MCA', '2026-04-22 10:19:07'),
(2, 'AIML', '2026-04-16 09:31:31'),
(3, 'CE', '2026-04-22 10:16:32'),
(4, 'IT', '2026-04-22 10:16:38'),
(5, 'EXTC', '2026-04-22 10:16:45'),
(6, 'ECS', '2026-04-22 10:18:15'),
(7, 'MECH', '2026-04-22 10:18:15'),
(8, 'CIVIL', '2026-04-22 10:18:15'),
(9, 'CSE-CS', '2026-04-22 10:18:15'),
(10, 'MME', '2026-04-22 10:18:15'),
(11, 'BCA', '2026-04-22 10:19:55'),
(12, 'AIDS', '2026-04-22 10:19:55'),
(13, 'IOT', '2026-04-22 10:19:55');

-- --------------------------------------------------------

--
-- Table structure for table `st_division_master`
--

CREATE TABLE `st_division_master` (
  `division_id` int(11) NOT NULL,
  `division_name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `st_division_master`
--

INSERT INTO `st_division_master` (`division_id`, `division_name`) VALUES
(1, 'A'),
(2, 'B'),
(3, 'C'),
(4, 'D'),
(5, 'E'),
(6, 'F');

-- --------------------------------------------------------

--
-- Table structure for table `st_login`
--

CREATE TABLE `st_login` (
  `login_id` int(11) NOT NULL,
  `username` varchar(200) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `st_login`
--

INSERT INTO `st_login` (`login_id`, `username`, `password`, `role_id`, `user_id`, `created_at`) VALUES
(1, 'superadmin@tcetmumbai.in', 'Amit@1234', 1, 1, '2026-04-23 09:44:44'),
(2, 'admin@tcetmumbai.in', 'Amit@1234', 2, 2, '2026-04-23 09:45:37'),
(3, 'coordinator@tcetmumbai.in', 'Amit@1234', 3, 3, '2026-04-23 09:48:36'),
(4, 'mentor@tcetmumbai.in', 'Amit@1234', 4, 4, '2026-04-23 09:48:36'),
(5, 'student@tcetmumbai.in', 'Amit@1234', 5, 5, '2026-04-23 09:50:00');

-- --------------------------------------------------------

--
-- Table structure for table `st_menu_allocation_master`
--

CREATE TABLE `st_menu_allocation_master` (
  `menu_allocation_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `role_id` int(11) NOT NULL,
  `menu_id` int(11) NOT NULL,
  `sub_menu_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `st_menu_allocation_master`
--

INSERT INTO `st_menu_allocation_master` (`menu_allocation_id`, `user_id`, `role_id`, `menu_id`, `sub_menu_id`) VALUES
(1, 0, 1, 1, NULL),
(2, 0, 1, 1, 1),
(3, 0, 1, 1, 2),
(6, 0, 1, 1, 5),
(7, 0, 1, 2, NULL),
(25, 0, 1, 3, NULL),
(32, 0, 1, 3, 7),
(36, 0, 1, 3, 8),
(47, 0, 1, 4, NULL),
(54, 0, 1, 4, 9),
(58, 0, 1, 4, 10),
(69, 0, 1, 5, NULL),
(76, 0, 1, 5, 11),
(80, 0, 1, 5, 12),
(93, 0, 1, 2, 13),
(147, 0, 1, 2, 20),
(148, 0, 1, 2, 21),
(149, 0, 1, 2, 22),
(150, 0, 1, 2, 23),
(151, 0, 1, 2, 24),
(152, 0, 1, 2, 25),
(153, 0, 1, 5, 13),
(154, 0, 1, 5, 20),
(155, 0, 1, 5, 21),
(156, 0, 1, 5, 23),
(157, 0, 1, 5, 24),
(158, 0, 1, 5, 25),
(159, 0, 1, 5, 46),
(240, 0, 5, 1, 1),
(241, 0, 5, 1, NULL),
(256, 0, 2, 1, 1),
(257, 0, 2, 1, NULL),
(258, 0, 2, 1, 2),
(259, 0, 2, 1, 5),
(260, 0, 2, 3, 9),
(261, 0, 2, 3, NULL),
(262, 0, 2, 3, 10),
(263, 0, 2, 4, 11),
(264, 0, 2, 4, NULL),
(265, 0, 2, 4, 12),
(266, 0, 2, 5, 13),
(267, 0, 2, 5, NULL),
(268, 0, 2, 5, 20),
(269, 0, 2, 5, 21),
(270, 0, 3, 1, 1),
(271, 0, 3, 1, NULL),
(272, 0, 3, 1, 2),
(273, 0, 3, 1, 5),
(274, 0, 3, 4, 11),
(275, 0, 3, 4, NULL),
(276, 0, 3, 4, 12),
(277, 0, 3, 5, 20),
(278, 0, 3, 5, NULL),
(279, 0, 3, 5, 21),
(280, 0, 4, 1, 2),
(281, 0, 4, 1, NULL),
(282, 0, 4, 1, 5),
(283, 0, 4, 5, 20),
(284, 0, 4, 5, NULL),
(285, 0, 4, 5, 21),
(286, 0, 1, 5, 47);

-- --------------------------------------------------------

--
-- Table structure for table `st_menu_master`
--

CREATE TABLE `st_menu_master` (
  `menu_id` int(11) NOT NULL,
  `menu_name` varchar(100) NOT NULL,
  `menu_icon` varchar(100) NOT NULL DEFAULT 'fa fa-folder'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `st_menu_master`
--

INSERT INTO `st_menu_master` (`menu_id`, `menu_name`, `menu_icon`) VALUES
(1, 'Students', 'fa fa-graduation-cap'),
(2, 'Admin', 'fa fa-cogs'),
(3, 'coordinator', 'fa fa-user-secret'),
(4, 'mentor', 'fa fa-users'),
(5, 'Settings', 'fa fa-user');

-- --------------------------------------------------------

--
-- Table structure for table `st_role_master`
--

CREATE TABLE `st_role_master` (
  `role_id` int(11) NOT NULL,
  `role_name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `st_role_master`
--

INSERT INTO `st_role_master` (`role_id`, `role_name`) VALUES
(1, 'SUPER ADMIN'),
(2, 'ADMIN'),
(3, 'COORDINATOR / HOD'),
(4, 'MENTOR'),
(5, 'STUDENT');

-- --------------------------------------------------------

--
-- Table structure for table `st_sub_menu_master`
--

CREATE TABLE `st_sub_menu_master` (
  `sub_menu_id` int(11) NOT NULL,
  `menu_id` int(11) NOT NULL,
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `sub_menu_name` varchar(100) NOT NULL,
  `sub_menu_icon` varchar(100) NOT NULL DEFAULT 'fa fa-angle-double-right',
  `sub_menu_route` varchar(255) NOT NULL DEFAULT '#'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `st_sub_menu_master`
--

INSERT INTO `st_sub_menu_master` (`sub_menu_id`, `menu_id`, `sort_order`, `sub_menu_name`, `sub_menu_icon`, `sub_menu_route`) VALUES
(1, 1, 1, 'Register Students', 'fa fa-plus', 'student_admission.php'),
(2, 1, 2, 'List of Students', 'fa fa-info-circle', 'student-info.php'),
(5, 1, 3, 'Concise Details', 'fa fa-info-circle', 'student_concise_details.php'),
(7, 2, 1, 'Register Admin', 'fa fa-plus', 'admin_register.php'),
(8, 2, 2, 'Admin Info', 'fa fa-info-circle', 'admin_info.php'),
(9, 3, 1, 'Register Coordinator', 'fa fa-plus', 'coordinator_register.php'),
(10, 3, 2, 'Coordinator Info', 'fa fa-info-circle', 'coordinator_info.php'),
(11, 4, 1, 'Register Mentor', 'fa fa-plus', 'mentor_register.php'),
(12, 4, 2, 'Mentor Info', 'fa fa-info-circle', 'mentor_info.php'),
(13, 5, 1, 'Masters', 'fa fa-cog', 'class_crud_new.php#section-list'),
(20, 5, 2, 'Profile', 'fa fa-user', 'profile.php'),
(21, 5, 3, 'Update Password', 'fa fa-folder', 'change_password.php'),
(23, 5, 5, 'Menu', 'fa fa-folder', 'class_crud_new.php?tab=menu-list'),
(24, 5, 6, 'Sub Menu', 'fa fa-folder', 'class_crud_new.php?tab=sub-menu-list'),
(25, 5, 7, 'Side Menu Allocation', 'fa fa-check-square-o', 'allocation_master.php'),
(46, 5, 4, 'Manage Section', 'fa fa-list-alt', 'class_crud_new.php?tab=section-list'),
(47, 5, 8, 'Offline Marks Entry', 'fa fa-pencil-square-o', 'offline_marks_entry.php');

-- --------------------------------------------------------

--
-- Table structure for table `st_user_log_master`
--

CREATE TABLE `st_user_log_master` (
  `user_log_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `st_user_master`
--

CREATE TABLE `st_user_master` (
  `user_id` int(11) NOT NULL,
  `user_name` varchar(150) NOT NULL,
  `email_id` varchar(200) NOT NULL,
  `phone_number` varchar(15) DEFAULT NULL,
  `department_id` int(11) NOT NULL,
  `role_id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `st_user_master`
--

INSERT INTO `st_user_master` (`user_id`, `user_name`, `email_id`, `phone_number`, `department_id`, `role_id`, `student_id`) VALUES
(1, 'Anurag Mishra', 'amit@tcetmumbai.in', '8080590516', 1, 1, 0),
(2, 'Amit Kumar', 'anurag@tcetmumbai.in', '8080590516', 1, 2, 0),
(3, 'Ashutosh Pandey', 'asdf@tcetmumbai.in', '234', 2, 2, 0);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `st_audit_log`
--
ALTER TABLE `st_audit_log`
  ADD PRIMARY KEY (`audit_id`);

--
-- Indexes for table `st_class_master`
--
ALTER TABLE `st_class_master`
  ADD PRIMARY KEY (`class_id`);

--
-- Indexes for table `st_department_master`
--
ALTER TABLE `st_department_master`
  ADD PRIMARY KEY (`department_id`);

--
-- Indexes for table `st_division_master`
--
ALTER TABLE `st_division_master`
  ADD PRIMARY KEY (`division_id`);

--
-- Indexes for table `st_login`
--
ALTER TABLE `st_login`
  ADD PRIMARY KEY (`login_id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `st_menu_allocation_master`
--
ALTER TABLE `st_menu_allocation_master`
  ADD PRIMARY KEY (`menu_allocation_id`);

--
-- Indexes for table `st_menu_master`
--
ALTER TABLE `st_menu_master`
  ADD PRIMARY KEY (`menu_id`);

--
-- Indexes for table `st_role_master`
--
ALTER TABLE `st_role_master`
  ADD PRIMARY KEY (`role_id`);

--
-- Indexes for table `st_sub_menu_master`
--
ALTER TABLE `st_sub_menu_master`
  ADD PRIMARY KEY (`sub_menu_id`);

--
-- Indexes for table `st_user_log_master`
--
ALTER TABLE `st_user_log_master`
  ADD PRIMARY KEY (`user_log_id`);

--
-- Indexes for table `st_user_master`
--
ALTER TABLE `st_user_master`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `email_id` (`email_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `st_audit_log`
--
ALTER TABLE `st_audit_log`
  MODIFY `audit_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `st_class_master`
--
ALTER TABLE `st_class_master`
  MODIFY `class_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `st_department_master`
--
ALTER TABLE `st_department_master`
  MODIFY `department_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `st_division_master`
--
ALTER TABLE `st_division_master`
  MODIFY `division_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `st_login`
--
ALTER TABLE `st_login`
  MODIFY `login_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `st_menu_allocation_master`
--
ALTER TABLE `st_menu_allocation_master`
  MODIFY `menu_allocation_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=287;

--
-- AUTO_INCREMENT for table `st_menu_master`
--
ALTER TABLE `st_menu_master`
  MODIFY `menu_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `st_role_master`
--
ALTER TABLE `st_role_master`
  MODIFY `role_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `st_sub_menu_master`
--
ALTER TABLE `st_sub_menu_master`
  MODIFY `sub_menu_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=48;

--
-- AUTO_INCREMENT for table `st_user_log_master`
--
ALTER TABLE `st_user_log_master`
  MODIFY `user_log_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `st_user_master`
--
ALTER TABLE `st_user_master`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
