-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 24, 2025 at 04:49 AM
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
-- Database: `barangay_systemjanpol`
--

-- --------------------------------------------------------

--
-- Table structure for table `audit_log`
--

CREATE TABLE `audit_log` (
  `log_id` varchar(15) NOT NULL,
  `user_id` varchar(15) DEFAULT NULL,
  `action` text DEFAULT NULL,
  `action_date` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `audit_log`
--

INSERT INTO `audit_log` (`log_id`, `user_id`, `action`, `action_date`) VALUES
('LOG68d358617d52', 'ADM0001', 'User logged in successfully. Role: superadmin', '2025-09-24 10:33:05');

-- --------------------------------------------------------

--
-- Table structure for table `barangay_officials`
--

CREATE TABLE `barangay_officials` (
  `barangay_id` varchar(15) NOT NULL,
  `user_id` varchar(15) DEFAULT NULL,
  `full_name` varchar(100) DEFAULT NULL,
  `position` varchar(50) DEFAULT NULL,
  `contact_number` varchar(25) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `certificate_issuance`
--

CREATE TABLE `certificate_issuance` (
  `issuance_no` varchar(15) NOT NULL,
  `certificate_id` varchar(15) DEFAULT NULL,
  `date_issued` date DEFAULT NULL,
  `signatory_note` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `certificate_request`
--

CREATE TABLE `certificate_request` (
  `certificate_id` varchar(15) NOT NULL,
  `barangay_id` varchar(15) DEFAULT NULL,
  `resident_id` varchar(15) DEFAULT NULL,
  `cert_type_id` varchar(15) DEFAULT NULL,
  `request_date` date DEFAULT NULL,
  `purpose` text DEFAULT NULL,
  `status` varchar(50) DEFAULT NULL,
  `release_date` date DEFAULT NULL,
  `remarks` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `certificate_type`
--

CREATE TABLE `certificate_type` (
  `cert_type_id` varchar(15) NOT NULL,
  `cert_name` varchar(100) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `requirements` text DEFAULT NULL,
  `certificate_template` varchar(255) DEFAULT NULL,
  `fee` decimal(10,2) DEFAULT 0.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `events`
--

CREATE TABLE `events` (
  `event_id` int(11) NOT NULL,
  `title` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `event_date` date NOT NULL,
  `event_time` time DEFAULT NULL,
  `location` varchar(150) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `master_list`
--

CREATE TABLE `master_list` (
  `id` int(11) NOT NULL,
  `first_name` varchar(50) NOT NULL,
  `mi` varchar(5) DEFAULT NULL,
  `last_name` varchar(50) NOT NULL,
  `birth_date` date DEFAULT NULL,
  `cedula_number` varchar(50) DEFAULT NULL,
  `barangay` varchar(50) DEFAULT NULL,
  `municipality` varchar(50) DEFAULT NULL,
  `province` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `medical_history`
--

CREATE TABLE `medical_history` (
  `med_id` int(11) NOT NULL,
  `resident_id` varchar(15) NOT NULL,
  `medical_condition` varchar(255) NOT NULL,
  `diagnosis_date` varchar(100) DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `prescribed_by` varchar(100) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `medical_history`
--

INSERT INTO `medical_history` (`med_id`, `resident_id`, `medical_condition`, `diagnosis_date`, `notes`, `prescribed_by`, `created_at`) VALUES
(5, 'RES0002', 'paralyze', '2025-09-25', 'pasmo', NULL, '2025-09-20 18:04:26'),
(24, 'RES0002', 'paralyze', '2025-09-04', 'fdsf', 'jury gwapo', '2025-09-22 11:03:33'),
(27, 'RES68d29c4a2bab', 'buang', '2003-09-09', 'arayyyko', 'Dr. jury gwapo', '2025-09-24 04:12:20');

-- --------------------------------------------------------

--
-- Table structure for table `notification`
--

CREATE TABLE `notification` (
  `notification_id` varchar(15) NOT NULL,
  `certificate_id` varchar(15) DEFAULT NULL,
  `message` text DEFAULT NULL,
  `date_created` datetime DEFAULT NULL,
  `status` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

CREATE TABLE `payments` (
  `payment_id` int(11) NOT NULL,
  `resident_id` varchar(15) NOT NULL,
  `payment_type` varchar(100) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `status` enum('paid','unpaid','pending') DEFAULT 'pending',
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `prescribed_medicine`
--

CREATE TABLE `prescribed_medicine` (
  `prescription_id` int(11) NOT NULL,
  `resident_id` varchar(15) DEFAULT NULL,
  `med_id` int(11) NOT NULL,
  `medicine_name` varchar(255) NOT NULL,
  `dosage` varchar(100) DEFAULT NULL,
  `frequency` varchar(100) DEFAULT NULL,
  `duration` varchar(100) DEFAULT NULL,
  `prescribed_by` varchar(100) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `prescribed_medicine`
--

INSERT INTO `prescribed_medicine` (`prescription_id`, `resident_id`, `med_id`, `medicine_name`, `dosage`, `frequency`, `duration`, `prescribed_by`, `created_at`) VALUES
(4, 'RES0002', 24, 'fdfdf', '500mg', '2x', '7', 'jury gwapo', '2025-09-22 11:03:47'),
(5, 'RES0002', 24, 'fdfdf', '500mg', '2x', '7', 'jury gwapo', '2025-09-22 11:04:36'),
(10, 'RES68d29c4a2bab', 27, 'biogesic', '500mg', '2x', '2 weeks', 'Dr. jury gwapo', '2025-09-24 04:12:57');

-- --------------------------------------------------------

--
-- Table structure for table `report`
--

CREATE TABLE `report` (
  `report_id` varchar(15) NOT NULL,
  `barangay_id` varchar(15) DEFAULT NULL,
  `report_type` varchar(50) DEFAULT NULL,
  `date_generated` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `resident`
--

CREATE TABLE `resident` (
  `resident_id` varchar(15) NOT NULL,
  `user_id` varchar(15) DEFAULT NULL,
  `first_name` varchar(50) DEFAULT NULL,
  `mi` varchar(10) DEFAULT NULL,
  `last_name` varchar(50) DEFAULT NULL,
  `suffix` varchar(10) DEFAULT NULL,
  `birth_date` date DEFAULT NULL,
  `gender` varchar(10) DEFAULT NULL,
  `email` varchar(50) DEFAULT NULL,
  `contact_number` varchar(25) DEFAULT NULL,
  `civil_status` varchar(20) DEFAULT NULL,
  `age` int(11) DEFAULT NULL,
  `nationality` varchar(100) DEFAULT NULL,
  `purok` varchar(50) DEFAULT NULL,
  `barangay` varchar(50) DEFAULT NULL,
  `municipality` varchar(50) DEFAULT NULL,
  `province` varchar(100) DEFAULT NULL,
  `photo` varchar(255) DEFAULT NULL,
  `household_no` int(11) DEFAULT NULL,
  `date_registered` date DEFAULT NULL,
  `approved_by` varchar(50) DEFAULT NULL,
  `status` enum('Pending','Pre-Verified','Verified','Rejected') DEFAULT 'Pending',
  `proof_of_residency` varchar(255) DEFAULT NULL,
  `cedula_number` varchar(50) DEFAULT NULL,
  `verified_at` datetime DEFAULT NULL,
  `remarks` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `resident`
--

INSERT INTO `resident` (`resident_id`, `user_id`, `first_name`, `mi`, `last_name`, `suffix`, `birth_date`, `gender`, `email`, `contact_number`, `civil_status`, `age`, `nationality`, `purok`, `barangay`, `municipality`, `province`, `photo`, `household_no`, `date_registered`, `approved_by`, `status`, `proof_of_residency`, `cedula_number`, `verified_at`, `remarks`) VALUES
('RES0002', NULL, 'John', NULL, 'Lumacang', NULL, '2004-07-29', 'Male', NULL, '09778464733', NULL, NULL, NULL, 'camia', 'Poblacion', 'Sagay', 'Camiguin', NULL, NULL, NULL, NULL, 'Pending', NULL, NULL, NULL, NULL),
('RES68d0fef3432d', 'USR68d0fec2e531', 'Juryy gwapo', 'l.', 'gwapo oy', NULL, '2003-09-09', 'Male', NULL, '0999887836', 'single', NULL, NULL, 'hytryteds', 'tytws', 'ytytytuyuwws', 'iuiuws', '', NULL, '2025-09-22', NULL, '', 'RES68d0fef3432d_1758527219.jpg', '', NULL, NULL),
('RES68d29c4a2bab', 'USR68d29bf428c1', 'Diza', 'L.', 'Sumalpong', NULL, '2005-09-09', 'Female', NULL, '09567728733', 'single', NULL, NULL, 'waling-waling', 'Tupsan', 'mambajao', 'Camiguin', NULL, NULL, '2025-09-23', NULL, '', 'RES68d29c4a2bab_1758633034.jpg', '', NULL, NULL),
('RES68d356186ed2', 'USR68d355e9bd6d', 'erewrewrere', 'rewewe', 'fdf', NULL, '2005-09-09', 'Female', NULL, '0909887876', 'single', NULL, NULL, 'waling-waling2', 'Tupsan2', 'mambajao', 'Camiguin', NULL, NULL, '2025-09-24', NULL, 'Pending', 'RES68d356186ed2_1758680600.jpg', '', NULL, NULL),
('RES68d3575988ce', 'USR68d355e9bd6d', 'erewrewrerew', 'rewewew', 'fdfw', NULL, '2005-09-09', 'Female', NULL, '09098878764', 'single', NULL, NULL, 'waling-waling22', 'Tupsan23', 'mambajao', 'Camiguin', NULL, NULL, '2025-09-24', NULL, 'Pending', 'RES68d3575988ce_1758680921.jpg', '', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` varchar(15) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` varchar(20) NOT NULL,
  `full_name` varchar(100) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `reset_token` varchar(255) NOT NULL,
  `reset_expiry` datetime NOT NULL,
  `status` varchar(20) DEFAULT 'pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `username`, `email`, `password`, `role`, `full_name`, `created_at`, `reset_token`, `reset_expiry`, `status`) VALUES
('ADM0001', 'superadmin', 'superadmin@example.com', '$2y$10$XsNtmrc.k5Hm6yfKEmD4q.db4h.N0UTVVs2axytI3lZt7ETDlb8b.', 'superadmin', 'Super Admin', '2025-09-16 23:21:42', '', '0000-00-00 00:00:00', 'Approved'),
('USR68d0fec2e531', 'jury', 'jury1@gmail.com', '$2y$10$4TaDs7Z77JyPvmrHjNVRPuYByY4DGRIbqsqIUHZiMMA4lQv5eZIDa', 'health_worker', 'jury gwapo', '2025-09-22 15:46:10', '', '0000-00-00 00:00:00', 'Approved'),
('USR68d29bf428c1', 'dizang', 'dizasumalpong@gmail.com', '$2y$10$dstKcRk9jzi3JSkK62/Um.wlxv7s2NLyt1WKKIv/aHYWTKcj6Vn..', 'resident', 'Diza Sumalpong', '2025-09-23 21:09:08', '', '0000-00-00 00:00:00', 'Approved'),
('USR68d355e9bd6d', 'jan', 'diza1222@gmail.com', '$2y$10$6ciNQD13iXxGRKz4wUTU.upvtqt1yA9cN7TpWHtttcozwewBx3DZ2', 'resident', 'dfgdgfdgfdghf', '2025-09-24 10:22:33', '', '0000-00-00 00:00:00', 'pending');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `audit_log`
--
ALTER TABLE `audit_log`
  ADD PRIMARY KEY (`log_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `barangay_officials`
--
ALTER TABLE `barangay_officials`
  ADD PRIMARY KEY (`barangay_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `certificate_issuance`
--
ALTER TABLE `certificate_issuance`
  ADD PRIMARY KEY (`issuance_no`),
  ADD KEY `certificate_id` (`certificate_id`);

--
-- Indexes for table `certificate_request`
--
ALTER TABLE `certificate_request`
  ADD PRIMARY KEY (`certificate_id`),
  ADD KEY `resident_id` (`resident_id`),
  ADD KEY `cert_type_id` (`cert_type_id`);

--
-- Indexes for table `certificate_type`
--
ALTER TABLE `certificate_type`
  ADD PRIMARY KEY (`cert_type_id`);

--
-- Indexes for table `events`
--
ALTER TABLE `events`
  ADD PRIMARY KEY (`event_id`);

--
-- Indexes for table `master_list`
--
ALTER TABLE `master_list`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `medical_history`
--
ALTER TABLE `medical_history`
  ADD PRIMARY KEY (`med_id`),
  ADD KEY `resident_id` (`resident_id`);

--
-- Indexes for table `notification`
--
ALTER TABLE `notification`
  ADD PRIMARY KEY (`notification_id`),
  ADD KEY `certificate_id` (`certificate_id`);

--
-- Indexes for table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`payment_id`),
  ADD KEY `resident_id` (`resident_id`);

--
-- Indexes for table `prescribed_medicine`
--
ALTER TABLE `prescribed_medicine`
  ADD PRIMARY KEY (`prescription_id`),
  ADD KEY `med_id` (`med_id`),
  ADD KEY `fk_prescribed_resident` (`resident_id`);

--
-- Indexes for table `report`
--
ALTER TABLE `report`
  ADD PRIMARY KEY (`report_id`),
  ADD KEY `barangay_id` (`barangay_id`);

--
-- Indexes for table `resident`
--
ALTER TABLE `resident`
  ADD PRIMARY KEY (`resident_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `events`
--
ALTER TABLE `events`
  MODIFY `event_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `master_list`
--
ALTER TABLE `master_list`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `medical_history`
--
ALTER TABLE `medical_history`
  MODIFY `med_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `payment_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `prescribed_medicine`
--
ALTER TABLE `prescribed_medicine`
  MODIFY `prescription_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `audit_log`
--
ALTER TABLE `audit_log`
  ADD CONSTRAINT `audit_log_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE SET NULL;

--
-- Constraints for table `barangay_officials`
--
ALTER TABLE `barangay_officials`
  ADD CONSTRAINT `barangay_officials_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `certificate_issuance`
--
ALTER TABLE `certificate_issuance`
  ADD CONSTRAINT `certificate_issuance_ibfk_1` FOREIGN KEY (`certificate_id`) REFERENCES `certificate_request` (`certificate_id`) ON DELETE CASCADE;

--
-- Constraints for table `certificate_request`
--
ALTER TABLE `certificate_request`
  ADD CONSTRAINT `certificate_request_ibfk_1` FOREIGN KEY (`resident_id`) REFERENCES `resident` (`resident_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `certificate_request_ibfk_2` FOREIGN KEY (`cert_type_id`) REFERENCES `certificate_type` (`cert_type_id`) ON DELETE SET NULL;

--
-- Constraints for table `medical_history`
--
ALTER TABLE `medical_history`
  ADD CONSTRAINT `medical_history_ibfk_1` FOREIGN KEY (`resident_id`) REFERENCES `resident` (`resident_id`) ON DELETE CASCADE;

--
-- Constraints for table `notification`
--
ALTER TABLE `notification`
  ADD CONSTRAINT `notification_ibfk_1` FOREIGN KEY (`certificate_id`) REFERENCES `certificate_request` (`certificate_id`) ON DELETE CASCADE;

--
-- Constraints for table `payments`
--
ALTER TABLE `payments`
  ADD CONSTRAINT `payments_ibfk_1` FOREIGN KEY (`resident_id`) REFERENCES `resident` (`resident_id`) ON DELETE CASCADE;

--
-- Constraints for table `prescribed_medicine`
--
ALTER TABLE `prescribed_medicine`
  ADD CONSTRAINT `fk_prescribed_resident` FOREIGN KEY (`resident_id`) REFERENCES `resident` (`resident_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `prescribed_medicine_ibfk_1` FOREIGN KEY (`med_id`) REFERENCES `medical_history` (`med_id`) ON DELETE CASCADE;

--
-- Constraints for table `report`
--
ALTER TABLE `report`
  ADD CONSTRAINT `report_ibfk_1` FOREIGN KEY (`barangay_id`) REFERENCES `barangay_officials` (`barangay_id`) ON DELETE CASCADE;

--
-- Constraints for table `resident`
--
ALTER TABLE `resident`
  ADD CONSTRAINT `resident_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
