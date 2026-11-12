-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 12, 2026 at 04:11 PM
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
-- Database: `grsms_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `system_user`
--

CREATE TABLE `system_user` (
  `user_id` int(11) NOT NULL,
  `first_name` varchar(250) NOT NULL,
  `last_name` varchar(250) NOT NULL,
  `middle_name` varchar(250) DEFAULT NULL,
  `username` varchar(100) NOT NULL,
  `password_hash` varchar(250) NOT NULL,
  `contact_number` varchar(12) NOT NULL,
  `role` varchar(100) NOT NULL,
  `specialization` varchar(100) DEFAULT NULL,
  `account_status` varchar(20) NOT NULL DEFAULT 'Active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `system_user`
--

INSERT INTO `system_user` (`user_id`, `first_name`, `last_name`, `middle_name`, `username`, `password_hash`, `contact_number`, `role`, `specialization`, `account_status`) VALUES
(1, 'Alex', 'Reyes', 'Mendoza', 'admin_alex', 'hash_admin123', '09171000001', 'Admin', 'System Management', 'Active'),
(2, 'Sarah', 'Gomez', 'Cruz', 'desk_sarah', 'hash_sarah123', '09171000002', 'Receptionist', 'Customer Intake', 'Active'),
(3, 'Dave', 'Santos', 'Ramos', 'tech_dave', 'hash_dave123', '09171000003', 'Technician', 'Mobile & Micro-soldering', 'Active'),
(4, 'Marco', 'Cruz', NULL, 'tech_marco', 'hash_marco123', '09171000004', 'Technician', 'Laptop & Desktop Hardware', 'Active'),
(5, 'Elena', 'Navarro', 'Bautista', 'desk_elena', 'hash_elena123', '09171000005', 'Receptionist', 'Customer Intake & Billing', 'Active'),
(6, 'Carlo', 'Mendoza', 'Aquino', 'tech_carlo', 'hash_carlo123', '09171000006', 'Technician', 'Apple & Tablet Devices', 'Active'),
(7, 'Patricia', 'Villanueva', 'Torres', 'tech_pat', 'hash_pat123', '09171000007', 'Technician', 'Motherboard & IC Repair', 'Active'),
(8, 'Jericho', 'Bautista', NULL, 'tech_jericho', 'hash_jericho123', '09171000008', 'Technician', 'Console & Peripherals', 'Active'),
(9, 'Camille', 'Torres', 'Salazar', 'cashier_camille', 'hash_camille123', '09171000009', 'Receptionist', 'Payments & Release', 'Active'),
(10, 'Rafael', 'Aquino', 'Dela Rosa', 'admin_rafael', 'hash_rafael123', '09171000010', 'Admin', 'Operations Supervisor', 'Inactive');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `system_user`
--
ALTER TABLE `system_user`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `system_user`
--
ALTER TABLE `system_user`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
