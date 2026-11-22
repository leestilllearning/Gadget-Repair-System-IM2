-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 22, 2026 at 09:25 AM
-- Server version: 10.11.16-MariaDB
-- PHP Version: 8.5.12

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
-- Table structure for table `customer`
--

CREATE TABLE `customer` (
  `customer_ID` varchar(20) NOT NULL,
  `first_name` varchar(250) NOT NULL,
  `last_name` varchar(250) NOT NULL,
  `middle_name` varchar(250) DEFAULT NULL,
  `contact_number` varchar(15) NOT NULL,
  `email` varchar(200) DEFAULT NULL,
  `date_registered` datetime NOT NULL,
  `barangay` varchar(100) NOT NULL,
  `city` varchar(100) NOT NULL,
  `province` varchar(100) NOT NULL,
  `zip_code` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `customer`
--

INSERT INTO `customer` (`customer_ID`, `first_name`, `last_name`, `middle_name`, `contact_number`, `email`, `date_registered`, `barangay`, `city`, `province`, `zip_code`) VALUES
('CUS-001', 'Miko', 'Ocfemia', 'Nidea', '09171112233', 'miko.ocfemia@example.com', '2026-10-01 08:14:00', 'SaGedli', 'Guinobatan', 'Albay', '4501'),
('CUS-002', 'John', 'Doe', 'Smith', '09172223344', 'john.doe@example.com', '2026-10-01 10:37:00', 'Bagumbayan', 'Daraga', 'Albay', '4501'),
('CUS-003', 'Jane', 'Smith', 'Doe', '09173334455', 'jane.smith@example.com', '2026-10-02 09:22:00', 'Binitayan', 'Daraga', 'Albay', '4501'),
('CUS-004', 'Robert', 'Johnson', 'Lee', '09174445566', 'robert.j@example.com', '2026-10-03 11:08:00', 'Kimantao', 'Daraga', 'Albay', '4501'),
('CUS-005', 'Emily', 'Davis', 'Brown', '09175556677', 'emily.d@example.com', '2026-10-04 08:53:00', 'Old Albay', 'Legazpi City', 'Albay', '4500'),
('CUS-006', 'Michael', 'Wilson', 'Taylor', '09176667788', 'mwilson@example.com', '2026-10-04 13:29:00', 'Rawis', 'Legazpi City', 'Albay', '4500'),
('CUS-007', 'Sarah', 'Taylor', 'Anderson', '09177778899', 'staylor@example.com', '2026-10-06 14:17:00', 'Bitano', 'Legazpi City', 'Albay', '4500'),
('CUS-008', 'David', 'Anderson', 'Thomas', '09178889900', 'danderson@example.com', '2026-10-07 09:41:00', 'Cabangan', 'Legazpi City', 'Albay', '4500'),
('CUS-009', 'Jessica', 'Thomas', 'Jackson', '09179990011', 'jthomas@example.com', '2026-10-08 10:05:00', 'Cruzada', 'Legazpi City', 'Albay', '4500'),
('CUS-010', 'Daniel', 'Jackson', 'White', '09170001122', 'djackson@example.com', '2026-10-08 10:46:00', 'Gogon', 'Legazpi City', 'Albay', '4500'),
('CUS-011', 'Amanda', 'White', 'Harris', '09181112233', 'awhite@example.com', '2026-10-10 08:32:00', 'San Roque', 'Legazpi City', 'Albay', '4500'),
('CUS-012', 'James', 'Harris', 'Martin', '09182223344', 'jharris@example.com', '2026-10-10 12:54:00', 'Tagas', 'Daraga', 'Albay', '4501'),
('CUS-013', 'Lisa', 'Martin', 'Thompson', '09183334455', 'lmartin@example.com', '2026-10-11 15:18:00', 'Busay', 'Daraga', 'Albay', '4501'),
('CUS-014', 'Brian', 'Thompson', 'Garcia', '09184445566', 'bthompson@example.com', '2026-10-12 09:07:00', 'Sipi', 'Daraga', 'Albay', '4501'),
('CUS-015', 'Karen', 'Garcia', 'Martinez', '09185556677', 'kgarcia@example.com', '2026-10-13 11:43:00', 'Banag', 'Daraga', 'Albay', '4501'),
('CUS-016', 'Andrea', 'Ramos', 'Villanueva', '09191112233', 'andrea.ramos@example.com', '2026-10-14 09:36:00', 'Anislag', 'Daraga', 'Albay', '4501'),
('CUS-017', 'Mark', 'Reyes', 'Santiago', '09192223344', 'mark.reyes@example.com', '2026-10-14 12:44:00', 'Rawis', 'Legazpi City', 'Albay', '4500'),
('CUS-018', 'Christine', 'Mendoza', 'Garcia', '09193334455', 'christine.mendoza@example.com', '2026-10-16 14:21:00', 'Bascaran', 'Daraga', 'Albay', '4501'),
('CUS-019', 'Joshua', 'Fernandez', 'Cruz', '09194445566', 'joshua.fernandez@example.com', '2026-10-17 10:12:00', 'Bitano', 'Legazpi City', 'Albay', '4500'),
('CUS-020', 'Angelica', 'Bautista', 'Lopez', '09195556677', 'angelica.bautista@example.com', '2026-10-18 09:34:00', 'Busay', 'Daraga', 'Albay', '4501'),
('CUS-021', 'Christian', 'Gonzales', 'Rivera', '09196667788', 'christian.gonzales@example.com', '2026-10-18 16:27:00', 'Cabangan', 'Legazpi City', 'Albay', '4500'),
('CUS-022', 'Maureen', 'Castillo', 'Santos', '09197778899', 'maureen.castillo@example.com', '2026-10-20 11:51:00', 'Binitayan', 'Daraga', 'Albay', '4501'),
('CUS-023', 'Kevin', 'Navarro', 'Martinez', '09198889900', 'kevin.navarro@example.com', '2026-10-21 08:19:00', 'Cruzada', 'Legazpi City', 'Albay', '4500'),
('CUS-024', 'Beatriz', 'Alvarez', 'Torres', '09199990011', 'beatriz.alvarez@example.com', '2026-10-22 13:42:00', 'Tagas', 'Daraga', 'Albay', '4501'),
('CUS-025', 'Patrick', 'Salazar', 'Dela Cruz', '09190001122', 'patrick.salazar@example.com', '2026-10-23 10:05:00', 'Gogon', 'Legazpi City', 'Albay', '4500'),
('CUS-026', 'Mariel', 'Domingo', 'Reyes', '09201112233', 'mariel.domingo@example.com', '2026-10-24 09:16:00', 'Bagumbayan', 'Daraga', 'Albay', '4501'),
('CUS-027', 'Francis', 'Aguilar', 'Mendoza', '09202223344', 'francis.aguilar@example.com', '2026-10-24 15:39:00', 'San Roque', 'Legazpi City', 'Albay', '4500'),
('CUS-028', 'Denise', 'Morales', 'Garcia', '09203334455', 'denise.morales@example.com', '2026-10-25 12:23:00', 'Sipi', 'Daraga', 'Albay', '4501'),
('CUS-031', 'Lambert', 'Knight', NULL, '09231231323', 'lknight@gmail.com', '2026-11-20 10:28:39', 'Sagpon', 'Legazpi', 'Albay', '4500');

--
-- Triggers `customer`
--
DELIMITER $$
CREATE TRIGGER `trg_customer_before_delete` BEFORE DELETE ON `customer` FOR EACH ROW BEGIN
  INSERT INTO `deleted_customer` (
    `original_customer_ID`, `first_name`, `last_name`, `middle_name`,
    `contact_number`, `email`, `barangay`, `city`, `province`, `zip_code`,
    `date_registered`, `deleted_by`
  ) VALUES (
    OLD.`customer_ID`, OLD.`first_name`, OLD.`last_name`, OLD.`middle_name`,
    OLD.`contact_number`, OLD.`email`, OLD.`barangay`, OLD.`city`, OLD.`province`, OLD.`zip_code`,
    OLD.`date_registered`, CURRENT_USER()
  );

  DELETE p FROM `payment` p
    JOIN `invoice` i ON p.`invoice_ID` = i.`invoice_ID`
    JOIN `repair_request` r ON i.`repair_request_ID` = r.`repair_request_ID`
    JOIN `gadget` g ON r.`gadget_ID` = g.`gadget_ID`
   WHERE g.`customer_ID` = OLD.`customer_ID`;

  DELETE i FROM `invoice` i
    JOIN `repair_request` r ON i.`repair_request_ID` = r.`repair_request_ID`
    JOIN `gadget` g ON r.`gadget_ID` = g.`gadget_ID`
   WHERE g.`customer_ID` = OLD.`customer_ID`;

  DELETE rs FROM `repair_service` rs
    JOIN `repair_request` r ON rs.`repair_request_ID` = r.`repair_request_ID`
    JOIN `gadget` g ON r.`gadget_ID` = g.`gadget_ID`
   WHERE g.`customer_ID` = OLD.`customer_ID`;

  DELETE sh FROM `status_history` sh
    JOIN `repair_request` r ON sh.`repair_request_ID` = r.`repair_request_ID`
    JOIN `gadget` g ON r.`gadget_ID` = g.`gadget_ID`
   WHERE g.`customer_ID` = OLD.`customer_ID`;

  DELETE r FROM `repair_request` r
    JOIN `gadget` g ON r.`gadget_ID` = g.`gadget_ID`
   WHERE g.`customer_ID` = OLD.`customer_ID`;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `deleted_customer`
--

CREATE TABLE `deleted_customer` (
  `deleted_id` int(11) NOT NULL,
  `original_customer_ID` varchar(20) NOT NULL,
  `first_name` varchar(250) NOT NULL,
  `last_name` varchar(250) NOT NULL,
  `middle_name` varchar(250) DEFAULT NULL,
  `contact_number` varchar(15) NOT NULL,
  `email` varchar(200) DEFAULT NULL,
  `barangay` varchar(100) NOT NULL,
  `city` varchar(100) NOT NULL,
  `province` varchar(100) NOT NULL,
  `zip_code` varchar(10) NOT NULL,
  `date_registered` datetime NOT NULL,
  `deleted_by` varchar(150) NOT NULL,
  `deleted_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `deleted_customer`
--

INSERT INTO `deleted_customer` (`deleted_id`, `original_customer_ID`, `first_name`, `last_name`, `middle_name`, `contact_number`, `email`, `barangay`, `city`, `province`, `zip_code`, `date_registered`, `deleted_by`, `deleted_at`) VALUES
(1, 'CUS-032', 'Jack', 'Loen', 'Hos', '09228381734', 'jhosleon@gmail.com', 'There', 'Somewhere', 'Sorsogon', '4600', '2026-11-20 10:46:47', 'Alex Eala', '2026-11-20 12:13:46'),
(2, 'CUS-030', 'Katrina', 'Abad', 'Santos', '09205556677', 'katrina.abad@example.com', 'Bañag', 'Daraga', 'Albay', '4501', '2026-10-27 14:58:00', 'Alex Eala', '2026-11-20 12:14:03'),
(3, 'CUS-029', 'Adrian', 'Flores', 'Ramos', '09204445566', 'adrian.flores@example.com', 'Arimbay', 'Legazpi City', 'Albay', '4500', '2026-10-26 08:44:00', 'root@localhost', '2026-11-20 12:21:11');

-- --------------------------------------------------------

--
-- Table structure for table `gadget`
--

CREATE TABLE `gadget` (
  `gadget_ID` varchar(20) NOT NULL,
  `customer_ID` varchar(20) NOT NULL,
  `category` varchar(100) NOT NULL,
  `brand` varchar(100) NOT NULL,
  `model` varchar(100) NOT NULL,
  `serial_number` varchar(100) NOT NULL,
  `date_registered` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `gadget`
--

INSERT INTO `gadget` (`gadget_ID`, `customer_ID`, `category`, `brand`, `model`, `serial_number`, `date_registered`) VALUES
('GAD-001', 'CUS-001', 'Smartphone', 'OnePlus', 'OnePlus 11', 'SN-1PL-1014', '2026-10-01 08:14:00'),
('GAD-002', 'CUS-002', 'Smartphone', 'Xiaomi', 'Redmi Note 12 Pro', 'SN-XIA-1007', '2026-10-01 10:37:00'),
('GAD-003', 'CUS-003', 'Desktop', 'Custom PC', 'Intel Core i5 Gaming PC', 'SN-CST-1038', '2026-10-02 09:22:00'),
('GAD-004', 'CUS-004', 'Tablet', 'Huawei', 'MatePad 11', 'SN-HUA-1027', '2026-10-03 11:08:00'),
('GAD-005', 'CUS-005', 'Smartphone', 'Samsung', 'Galaxy S22 Ultra', 'SN-SAM-1002', '2026-10-04 08:53:00'),
('GAD-006', 'CUS-006', 'Console', 'Microsoft', 'Xbox Series X', 'SN-MIC-1023', '2026-10-04 13:29:00'),
('GAD-007', 'CUS-007', 'Laptop', 'Lenovo', 'IdeaPad Slim 5', 'SN-LEN-1032', '2026-10-06 14:17:00'),
('GAD-008', 'CUS-008', 'Laptop', 'HP', 'Spectre x360 14', 'SN-HPP-1011', '2026-10-07 09:41:00'),
('GAD-009', 'CUS-009', 'Tablet', 'Lenovo', 'Tab P11 Pro Gen 2', 'SN-LEN-1018', '2026-10-08 10:05:00'),
('GAD-010', 'CUS-010', 'Laptop', 'Acer', 'Aspire 5 A515', 'SN-ACE-1036', '2026-10-08 10:46:00'),
('GAD-011', 'CUS-011', 'Laptop', 'Apple', 'MacBook Air M2', 'SN-APL-1021', '2026-10-10 08:32:00'),
('GAD-012', 'CUS-012', 'Laptop', 'Lenovo', 'ThinkPad X1 Carbon Gen 10', 'SN-LEN-1004', '2026-10-10 12:54:00'),
('GAD-013', 'CUS-013', 'Gaming Mouse', 'Razer', 'DeathAdder V2', 'SN-RAZ-1030', '2026-10-11 15:18:00'),
('GAD-014', 'CUS-014', 'Smartphone', 'Google', 'Pixel 7 Pro', 'SN-GGL-1009', '2026-10-12 09:07:00'),
('GAD-015', 'CUS-015', 'Smartphone', 'Infinix', 'Zero 30 5G', 'SN-INF-1040', '2026-10-13 11:43:00'),
('GAD-016', 'CUS-016', 'Smartphone', 'OPPO', 'Reno 8 5G', 'SN-OPP-1016', '2026-10-14 09:36:00'),
('GAD-017', 'CUS-017', 'Smartphone', 'Tecno', 'Camon 20 Pro', 'SN-TEC-1043', '2026-10-14 12:44:00'),
('GAD-018', 'CUS-018', 'Smartphone', 'Samsung', 'Galaxy A54 5G', 'SN-SAM-1025', '2026-10-16 14:21:00'),
('GAD-019', 'CUS-019', 'Desktop', 'Custom PC', 'Ryzen 7 5800X Rig', 'SN-CST-1006', '2026-10-17 10:12:00'),
('GAD-020', 'CUS-020', 'Smartphone', 'Google', 'Pixel 6a', 'SN-GGL-1034', '2026-10-18 09:34:00'),
('GAD-021', 'CUS-021', 'Laptop', 'Apple', 'MacBook Pro M1', 'SN-APL-1047', '2026-10-18 16:27:00'),
('GAD-022', 'CUS-022', 'Smartphone', 'Vivo', 'V27 5G', 'SN-VIV-1019', '2026-10-20 11:51:00'),
('GAD-023', 'CUS-023', 'Smartphone', 'Apple', 'iPhone 13 Pro', 'SN-APL-1001', '2026-10-21 08:19:00'),
('GAD-024', 'CUS-024', 'Smartphone', 'Samsung', 'Galaxy S21 FE', 'SN-SAM-1045', '2026-10-22 13:42:00'),
('GAD-025', 'CUS-025', 'Laptop', 'Dell', 'Inspiron 14 5420', 'SN-DEL-1029', '2026-10-23 10:05:00'),
('GAD-026', 'CUS-026', 'Tablet', 'Xiaomi', 'Pad 6', 'SN-XIA-1049', '2026-10-24 09:16:00'),
('GAD-027', 'CUS-027', 'Console', 'Sony', 'PlayStation 5 Slim', 'SN-SNY-1013', '2026-10-24 15:39:00'),
('GAD-028', 'CUS-028', 'Wireless Keyboard', 'Logitech', 'K380 Wireless Keyboard', 'SN-LOG-1041', '2026-10-25 12:23:00'),
('GAD-031', 'CUS-031', 'Laptop', 'Mac', 'Apple 29', '221332', '2026-11-20 10:28:39');

-- --------------------------------------------------------

--
-- Table structure for table `invoice`
--

CREATE TABLE `invoice` (
  `invoice_ID` varchar(20) NOT NULL,
  `repair_request_ID` varchar(20) NOT NULL,
  `date` datetime NOT NULL,
  `total` decimal(10,2) NOT NULL CHECK (`total` >= 0),
  `status` varchar(30) NOT NULL DEFAULT 'Unpaid'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `invoice`
--

INSERT INTO `invoice` (`invoice_ID`, `repair_request_ID`, `date`, `total`, `status`) VALUES
('INV-001', 'REQ-001', '2026-10-02 15:00:00', 850.00, 'Paid'),
('INV-002', 'REQ-002', '2026-10-03 11:00:00', 850.00, 'Paid'),
('INV-003', 'REQ-003', '2026-10-04 16:30:00', 1800.00, 'Paid'),
('INV-004', 'REQ-004', '2026-10-05 14:00:00', 1800.00, 'Paid'),
('INV-005', 'REQ-005', '2026-10-07 10:30:00', 1200.00, 'Partially Paid'),
('INV-006', 'REQ-006', '2026-10-06 16:00:00', 1600.00, 'Paid'),
('INV-007', 'REQ-007', '2026-10-08 15:00:00', 850.00, 'Paid'),
('INV-008', 'REQ-008', '2026-10-10 12:00:00', 1200.00, 'Unpaid'),
('INV-009', 'REQ-009', '2026-10-10 16:00:00', 1800.00, 'Paid'),
('INV-010', 'REQ-010', '2026-10-11 17:00:00', 950.00, 'Partially Paid'),
('INV-011', 'REQ-011', '2026-10-12 10:30:00', 1100.00, 'Paid'),
('INV-012', 'REQ-012', '2026-10-13 15:30:00', 1500.00, 'Paid'),
('INV-013', 'REQ-013', '2026-10-14 17:00:00', 650.00, 'Unpaid'),
('INV-014', 'REQ-014', '2026-10-14 11:00:00', 1300.00, 'Paid'),
('INV-015', 'REQ-015', '2026-10-16 14:30:00', 2800.00, 'Partially Paid'),
('INV-016', 'REQ-016', '2026-10-16 16:15:00', 750.00, 'Paid'),
('INV-017', 'REQ-017', '2026-10-17 15:00:00', 1200.00, 'Paid'),
('INV-018', 'REQ-018', '2026-10-19 16:30:00', 850.00, 'Pending'),
('INV-019', 'REQ-019', '2026-10-20 12:45:00', 2000.00, 'Paid'),
('INV-020', 'REQ-020', '2026-10-21 11:15:00', 950.00, 'Partially Paid'),
('INV-021', 'REQ-021', '2026-10-22 18:30:00', 1100.00, 'Paid'),
('INV-022', 'REQ-022', '2026-10-23 14:00:00', 1000.00, 'Paid'),
('INV-023', 'REQ-023', '2026-10-24 10:15:00', 2800.00, 'Paid'),
('INV-024', 'REQ-024', '2026-10-25 15:30:00', 2500.00, 'Paid'),
('INV-025', 'REQ-025', '2026-10-26 12:15:00', 1100.00, 'Unpaid'),
('INV-026', 'REQ-026', '2026-10-27 11:30:00', 1100.00, 'Paid'),
('INV-027', 'REQ-027', '2026-10-28 17:30:00', 2200.00, 'Paid'),
('INV-028', 'REQ-028', '2026-10-29 14:15:00', 750.00, 'Paid'),
('INV-031', 'REQ-031', '2026-11-20 10:28:39', 4300.00, 'Pending');

-- --------------------------------------------------------

--
-- Table structure for table `payment`
--

CREATE TABLE `payment` (
  `payment_ID` varchar(20) NOT NULL,
  `user_ID` varchar(20) NOT NULL,
  `invoice_ID` varchar(20) NOT NULL,
  `reference_number` varchar(50) NOT NULL,
  `payment_date` datetime NOT NULL,
  `amount_paid` decimal(10,2) NOT NULL CHECK (`amount_paid` > 0),
  `payment_status` varchar(30) NOT NULL CHECK (`payment_status` in ('Completed','Pending','Failed'))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `payment`
--

INSERT INTO `payment` (`payment_ID`, `user_ID`, `invoice_ID`, `reference_number`, `payment_date`, `amount_paid`, `payment_status`) VALUES
('PAY-001', 'USR-009', 'INV-001', 'REF-20261001-001', '2026-10-02 15:30:00', 850.00, 'Completed'),
('PAY-002', 'USR-009', 'INV-002', 'REF-20261001-002', '2026-10-04 12:30:00', 850.00, 'Completed'),
('PAY-003', 'USR-009', 'INV-003', 'REF-20261002-001', '2026-10-05 10:00:00', 1800.00, 'Completed'),
('PAY-004', 'USR-009', 'INV-004', 'REF-20261003-001', '2026-10-06 14:00:00', 1800.00, 'Completed'),
('PAY-005', 'USR-009', 'INV-005', 'REF-20261004-001', '2026-10-08 11:00:00', 700.00, 'Completed'),
('PAY-006', 'USR-009', 'INV-006', 'REF-20261004-002', '2026-10-06 16:30:00', 1600.00, 'Completed'),
('PAY-007', 'USR-009', 'INV-007', 'REF-20261006-001', '2026-10-09 15:30:00', 850.00, 'Completed'),
('PAY-008', 'USR-009', 'INV-008', 'REF-20261007-001', '2026-10-12 12:45:00', 1200.00, 'Pending'),
('PAY-009', 'USR-009', 'INV-009', 'REF-20261008-001', '2026-10-10 16:30:00', 1800.00, 'Completed'),
('PAY-010', 'USR-009', 'INV-010', 'REF-20261008-002', '2026-10-13 18:00:00', 500.00, 'Completed'),
('PAY-011', 'USR-009', 'INV-011', 'REF-20261010-001', '2026-10-12 10:45:00', 1100.00, 'Completed'),
('PAY-012', 'USR-009', 'INV-012', 'REF-20261010-002', '2026-10-14 15:45:00', 1500.00, 'Completed'),
('PAY-013', 'USR-009', 'INV-013', 'REF-20261011-001', '2026-10-16 17:15:00', 650.00, 'Failed'),
('PAY-014', 'USR-009', 'INV-014', 'REF-20261012-001', '2026-10-14 11:30:00', 1300.00, 'Completed'),
('PAY-015', 'USR-009', 'INV-015', 'REF-20261013-001', '2026-10-18 14:45:00', 1800.00, 'Completed'),
('PAY-016', 'USR-009', 'INV-016', 'REF-20261014-001', '2026-10-16 16:45:00', 750.00, 'Completed'),
('PAY-017', 'USR-009', 'INV-017', 'REF-20261014-002', '2026-10-19 15:15:00', 1200.00, 'Completed'),
('PAY-018', 'USR-009', 'INV-018', 'REF-20261016-001', '2026-10-22 16:45:00', 850.00, 'Pending'),
('PAY-019', 'USR-009', 'INV-019', 'REF-20261017-001', '2026-10-20 13:00:00', 2000.00, 'Completed'),
('PAY-020', 'USR-009', 'INV-020', 'REF-20261018-001', '2026-10-23 11:30:00', 500.00, 'Completed'),
('PAY-021', 'USR-009', 'INV-021', 'REF-20261018-002', '2026-10-22 19:00:00', 1100.00, 'Completed'),
('PAY-022', 'USR-009', 'INV-022', 'REF-20261020-001', '2026-10-24 14:15:00', 1000.00, 'Completed'),
('PAY-023', 'USR-009', 'INV-023', 'REF-20261021-001', '2026-10-24 11:30:00', 2800.00, 'Completed'),
('PAY-024', 'USR-009', 'INV-024', 'REF-20261022-001', '2026-10-25 16:00:00', 2500.00, 'Completed'),
('PAY-025', 'USR-009', 'INV-025', 'REF-20261023-001', '2026-10-30 12:30:00', 1100.00, 'Pending'),
('PAY-026', 'USR-009', 'INV-026', 'REF-20261024-001', '2026-10-27 12:00:00', 1100.00, 'Completed'),
('PAY-027', 'USR-009', 'INV-027', 'REF-20261024-002', '2026-10-29 17:45:00', 2200.00, 'Completed'),
('PAY-028', 'USR-009', 'INV-028', 'REF-20261025-001', '2026-10-30 14:30:00', 750.00, 'Completed'),
('PAY-031', 'USR-011', 'INV-003', 'Cash · GC-9921', '2026-11-20 04:56:54', 100.00, 'Pending');

-- --------------------------------------------------------

--
-- Table structure for table `repair_request`
--

CREATE TABLE `repair_request` (
  `repair_request_ID` varchar(20) NOT NULL,
  `gadget_ID` varchar(20) NOT NULL,
  `received_by` varchar(20) NOT NULL,
  `assigned_tech_ID` varchar(20) NOT NULL,
  `date_submitted` datetime NOT NULL,
  `reported_issue` text NOT NULL,
  `initial_condition` text NOT NULL,
  `accessories_included` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `repair_request`
--

INSERT INTO `repair_request` (`repair_request_ID`, `gadget_ID`, `received_by`, `assigned_tech_ID`, `date_submitted`, `reported_issue`, `initial_condition`, `accessories_included`) VALUES
('REQ-001', 'GAD-001', 'USR-005', 'USR-003', '2026-10-01 08:14:00', 'Phone charges intermittently; charging cable must be held at an angle', 'Screen intact and charging port loose.', 'Phone, USB-C cable'),
('REQ-002', 'GAD-002', 'USR-002', 'USR-003', '2026-10-01 10:37:00', 'Charging port does not consistently recognize the charger', 'Minor scratches on back cover, charging port damaged', 'Phone, charger'),
('REQ-003', 'GAD-003', 'USR-005', 'USR-004', '2026-10-02 09:22:00', 'Desktop randomly shuts down during gaming sessions', 'Case intact and system powers on but shuts down under load', 'CPU unit, power cable'),
('REQ-004', 'GAD-004', 'USR-005', 'USR-006', '2026-10-03 11:08:00', 'Tablet touchscreen has an unresponsive area', 'Display has visible cracks, touch response inconsistent', 'Tablet, protective case'),
('REQ-005', 'GAD-005', 'USR-002', 'USR-003', '2026-10-04 08:53:00', 'Battery drains quickly and phone shuts down unexpectedly', 'Screen intact, battery health degraded', 'Phone, charger'),
('REQ-006', 'GAD-006', 'USR-005', 'USR-008', '2026-10-04 13:29:00', 'HDMI port has no stable video output when connected to a television', 'Console powers on, HDMI connector appears loose', 'Console, HDMI cable, power cable'),
('REQ-007', 'GAD-007', 'USR-002', 'USR-004', '2026-10-06 14:17:00', 'Several laptop keys are unresponsive', 'Minor exterior scratches,keyboard keys worn', 'Laptop, charger'),
('REQ-008', 'GAD-008', 'USR-005', 'USR-004', '2026-10-07 09:41:00', 'Laptop fan makes loud grinding noise during operation', 'Display intact, fan produces abnormal noise', 'Laptop, charger'),
('REQ-009', 'GAD-009', 'USR-002', 'USR-006', '2026-10-08 10:05:00', 'Tablet touchscreen is cracked and does not respond in some areas', 'Front glass cracked,display still powers on', 'Tablet, protective case'),
('REQ-010', 'GAD-010', 'USR-002', 'USR-004', '2026-10-08 10:46:00', 'Laptop does not consistently charge due to a loose power jack', 'Display intact, charging connection unstable', 'Laptop, charger'),
('REQ-011', 'GAD-011', 'USR-005', 'USR-006', '2026-10-10 08:32:00', 'MacBook display flickers when the screen angle is adjusted', 'Display intact,intermittent flickering observed', 'MacBook, charger'),
('REQ-012', 'GAD-012', 'USR-002', 'USR-004', '2026-10-10 12:54:00', 'Laptop hinge is stiff and makes cracking sounds when opened', 'Exterior casing slightly separated near hinge', 'Laptop, charger'),
('REQ-013', 'GAD-013', 'USR-005', 'USR-008', '2026-10-11 15:18:00', 'Mouse sometimes registers double-clicks with a single press', 'Exterior intact,left mouse button inconsistent', 'Gaming mouse, USB receiver'),
('REQ-014', 'GAD-014', 'USR-002', 'USR-003', '2026-10-12 09:07:00', 'Rear camera produces blurry images and fails to focus', 'Screen intact, rear camera lens appears undamaged', 'Phone, charger'),
('REQ-015', 'GAD-015', 'USR-005', 'USR-007', '2026-10-13 11:43:00', 'Phone randomly restarts and fails to maintain power', 'Exterior intact, suspected motherboard power fault', 'Phone, charger'),
('REQ-016', 'GAD-016', 'USR-005', 'USR-003', '2026-10-14 09:36:00', 'Earpiece produces distorted sound during calls', 'Screen intact,earpiece audio is distorted', 'Phone, charger'),
('REQ-017', 'GAD-017', 'USR-002', 'USR-003', '2026-10-14 12:44:00', 'Battery drains unusually fast even when idle', 'Minor scratches,battery performance degraded', 'Phone, charger'),
('REQ-018', 'GAD-018', 'USR-005', 'USR-003', '2026-10-16 14:21:00', 'Phone has intermittent charging and overheating issues', 'Screen intact,charging port area heats up', 'Phone, charger'),
('REQ-019', 'GAD-019', 'USR-002', 'USR-007', '2026-10-17 10:12:00', 'Desktop experiences graphics artifacts and display crashes', 'Case intact and graphics output unstable under load', 'CPU unit, power cable'),
('REQ-020', 'GAD-020', 'USR-005', 'USR-003', '2026-10-18 09:34:00', 'Rear camera lens is scratched, resulting in blurry photos', 'Screen intact, camera lens visibly scratched', 'Phone, charger'),
('REQ-021', 'GAD-021', 'USR-002', 'USR-006', '2026-10-18 16:27:00', 'MacBook screen flickers intermittently when opened or moved', 'Exterior intact and display connection suspected faulty', 'MacBook, charger'),
('REQ-022', 'GAD-022', 'USR-002', 'USR-003', '2026-10-20 11:51:00', 'Mobile signal and Wi-Fi reception are weak', 'Screen intact and network connectivity inconsistent', 'Phone, charger'),
('REQ-023', 'GAD-023', 'USR-005', 'USR-007', '2026-10-21 08:19:00', 'Phone randomly loses power despite having battery charge', 'Exterior intact and suspected internal board fault', 'Phone, charger'),
('REQ-024', 'GAD-024', 'USR-002', 'USR-003', '2026-10-22 13:42:00', 'Front display is cracked and touch response is inconsistent', 'Cracked front panel,display partially functional', 'Phone, protective case'),
('REQ-025', 'GAD-025', 'USR-005', 'USR-004', '2026-10-23 10:05:00', 'Laptop cannot detect available Wi-Fi networks', 'Display intact, wireless connection unavailable', 'Laptop, charger'),
('REQ-026', 'GAD-026', 'USR-002', 'USR-006', '2026-10-24 09:16:00', 'Tablet charges intermittently due to damaged charging flex', 'Screen intact ,charging connection unstable', 'Tablet, charger'),
('REQ-027', 'GAD-027', 'USR-005', 'USR-008', '2026-10-24 15:39:00', 'Console fails to read game discs', 'Console powers on disc drive makes unusual noise', 'Console, power cable, controller'),
('REQ-028', 'GAD-028', 'USR-002', 'USR-008', '2026-10-25 12:23:00', 'Several wireless keyboard keys do not register when pressed', 'Keyboard casing intact selected keys unresponsive', 'Keyboard, USB receiver'),
('REQ-031', 'GAD-031', 'USR-011', 'USR-006', '2026-11-20 10:28:39', 'Broken LCD', 'not working', 'none');

-- --------------------------------------------------------

--
-- Table structure for table `repair_service`
--

CREATE TABLE `repair_service` (
  `repair_service_ID` varchar(20) NOT NULL,
  `repair_request_ID` varchar(20) NOT NULL,
  `service_ID` varchar(20) NOT NULL,
  `quantity` int(11) NOT NULL CHECK (`quantity` > 0),
  `actual_price` decimal(10,2) NOT NULL CHECK (`actual_price` > 0)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `repair_service`
--

INSERT INTO `repair_service` (`repair_service_ID`, `repair_request_ID`, `service_ID`, `quantity`, `actual_price`) VALUES
('RES-001', 'REQ-001', 'SER-003', 1, 850.00),
('RES-002', 'REQ-002', 'SER-003', 1, 850.00),
('RES-003', 'REQ-003', 'SER-013', 1, 1800.00),
('RES-004', 'REQ-004', 'SER-025', 1, 1800.00),
('RES-005', 'REQ-005', 'SER-002', 1, 1200.00),
('RES-006', 'REQ-006', 'SER-015', 1, 1600.00),
('RES-007', 'REQ-007', 'SER-045', 1, 850.00),
('RES-008', 'REQ-008', 'SER-023', 1, 1200.00),
('RES-009', 'REQ-009', 'SER-025', 1, 1800.00),
('RES-010', 'REQ-010', 'SER-024', 1, 950.00),
('RES-011', 'REQ-011', 'SER-021', 1, 1100.00),
('RES-012', 'REQ-012', 'SER-020', 1, 1500.00),
('RES-013', 'REQ-013', 'SER-037', 1, 650.00),
('RES-014', 'REQ-014', 'SER-012', 1, 1300.00),
('RES-015', 'REQ-015', 'SER-050', 1, 2800.00),
('RES-016', 'REQ-016', 'SER-041', 1, 750.00),
('RES-017', 'REQ-017', 'SER-002', 1, 1200.00),
('RES-018', 'REQ-018', 'SER-003', 1, 850.00),
('RES-019', 'REQ-019', 'SER-028', 1, 2000.00),
('RES-020', 'REQ-020', 'SER-017', 1, 950.00),
('RES-021', 'REQ-021', 'SER-021', 1, 1100.00),
('RES-022', 'REQ-022', 'SER-019', 1, 1000.00),
('RES-023', 'REQ-023', 'SER-050', 1, 2800.00),
('RES-024', 'REQ-024', 'SER-001', 1, 2500.00),
('RES-025', 'REQ-025', 'SER-040', 1, 1100.00),
('RES-026', 'REQ-026', 'SER-043', 1, 1100.00),
('RES-027', 'REQ-027', 'SER-031', 1, 2200.00),
('RES-028', 'REQ-028', 'SER-038', 1, 750.00),
('RES-034', 'REQ-031', 'SER-001', 1, 2500.00),
('RES-035', 'REQ-031', 'SER-016', 1, 1800.00);

-- --------------------------------------------------------

--
-- Table structure for table `service`
--

CREATE TABLE `service` (
  `service_ID` varchar(20) NOT NULL,
  `service_name` varchar(200) NOT NULL,
  `description` text NOT NULL,
  `price` decimal(10,2) NOT NULL CHECK (`price` >= 0)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `service`
--

INSERT INTO `service` (`service_ID`, `service_name`, `description`, `price`) VALUES
('SER-001', 'LCD Screen Replacement', 'Full display panel replacement for cracked or unresponsive screens.', 2500.00),
('SER-002', 'Battery Replacement', 'Installation of a new OEM-grade battery for degraded battery life.', 1200.00),
('SER-003', 'Charging Port Repair', 'Cleaning, resoldering, or replacing damaged charging ports.', 850.00),
('SER-004', 'OS Reinstallation & Cleanup', 'Fresh installation of operating system with driver setup and malware removal.', 800.00),
('SER-005', 'Thermal Repaste & Cleaning', 'Deep interior dusting and high-performance thermal paste application.', 650.00),
('SER-006', 'Water Damage Treatment', 'Ultrasonic board cleaning and chemical de-oxidation treatment.', 1500.00),
('SER-007', 'Keyboard Replacement', 'Full keyboard assembly replacement for laptops.', 1400.00),
('SER-008', 'SSD Storage Upgrade', 'Installation of high-speed NVMe SSD including OS cloning.', 2200.00),
('SER-009', 'RAM Upgrade', 'Memory expansion installation and stress testing.', 1100.00),
('SER-010', 'Micro-soldering Board Repair', 'Component-level motherboard repair for power lines or ICs.', 3500.00),
('SER-011', 'Speaker Module Repair', 'Replacement of distorted or non-functioning audio speakers.', 750.00),
('SER-012', 'Camera Module Replacement', 'Replacement of front or rear camera modules.', 1300.00),
('SER-013', 'Power Supply Unit Repair', 'Diagnostic and component replacement for desktop PSUs.', 1800.00),
('SER-014', 'Data Recovery Service', 'Advanced logical and physical data extraction from damaged drives.', 4000.00),
('SER-015', 'HDMI Port Replacement', 'Desoldering damaged HDMI port and replacing with new connector.', 1600.00),
('SER-016', 'Back Glass Replacement', 'Replacement of cracked or damaged rear glass panels on smartphones.', 1800.00),
('SER-017', 'Camera Lens Repair', 'Repair or replacement of damaged front and rear camera lenses.', 950.00),
('SER-018', 'Fingerprint Sensor Repair', 'Repair of malfunctioning fingerprint sensors and biometric components.', 1200.00),
('SER-019', 'Network Antenna Repair', 'Repair or replacement of damaged Wi-Fi and cellular antenna components.', 1000.00),
('SER-020', 'Laptop Hinge Repair', 'Replacement or reinforcement of broken laptop hinges and hinge mounts.', 1500.00),
('SER-021', 'Laptop Display Cable Repair', 'Repair or replacement of damaged laptop display cables causing flickering screens.', 1100.00),
('SER-022', 'Touchpad Replacement', 'Repair or replacement of faulty laptop touchpads and trackpad components.', 1300.00),
('SER-023', 'Laptop Fan Replacement', 'Replacement of noisy, damaged, or non-functioning laptop cooling fans.', 1200.00),
('SER-024', 'DC Jack Repair', 'Repair or replacement of damaged laptop DC power jacks and connectors.', 950.00),
('SER-025', 'Tablet Touchscreen Replacement', 'Replacement of cracked tablet touch panels and digitizers.', 1800.00),
('SER-026', 'Tablet Battery Replacement', 'Replacement of swollen or degraded tablet batteries.', 1600.00),
('SER-027', 'Tablet Button Repair', 'Repair of malfunctioning tablet power buttons and volume controls.', 750.00),
('SER-028', 'Graphics Card Repair', 'Cleaning and repair of desktop graphics cards with display or overheating issues.', 2000.00),
('SER-029', 'CPU Cooler Replacement', 'Replacement of faulty desktop CPU cooling fans and heatsinks.', 1300.00),
('SER-030', 'Desktop Boot Repair', 'Troubleshooting and repair of desktop operating systems that fail to boot.', 1000.00),
('SER-031', 'Console Disc Drive Repair', 'Repair or replacement of damaged game console disc drives and laser assemblies.', 2200.00),
('SER-032', 'Controller Joystick Repair', 'Repair of drifting or unresponsive analog sticks on gaming controllers.', 850.00),
('SER-033', 'Console Cooling System Repair', 'Cleaning and replacement of overheating console cooling fans.', 1600.00),
('SER-034', 'Console Storage Upgrade', 'Installation and configuration of larger SSD storage for gaming consoles.', 2500.00),
('SER-035', 'Headset Cable Replacement', 'Replacement of damaged gaming headset audio cables and connectors.', 700.00),
('SER-036', 'Headset Microphone Repair', 'Repair of malfunctioning headset microphones and microphone connectors.', 850.00),
('SER-037', 'Gaming Mouse Switch Repair', 'Replacement of faulty gaming mouse switches causing double-clicking or unresponsive buttons.', 650.00),
('SER-038', 'Keyboard Switch Replacement', 'Repair or replacement of damaged wireless keyboard mechanical switches and keys.', 750.00),
('SER-039', 'Keyboard Charging Port Repair', 'Repair of wireless keyboard charging ports and battery connections.', 650.00),
('SER-040', 'Wi-Fi Card Replacement', 'Replacement of faulty laptop wireless network cards and Wi-Fi modules.', 1100.00),
('SER-041', 'Smartphone Audio Repair', 'Repair or replacement of smartphone earpiece and loudspeaker components with distorted or no audio.', 750.00),
('SER-042', 'Smartphone Liquid Damage Repair', 'Cleaning and corrosion treatment for liquid-damaged smartphone internal components.', 1500.00),
('SER-043', 'Tablet Charging Flex Repair', 'Repair or replacement of damaged tablet charging flex cables and connectors.', 1100.00),
('SER-044', 'RAM Diagnostic Service', 'Diagnosis and testing of desktop RAM modules causing crashes or startup failures.', 500.00),
('SER-045', 'Laptop Keyboard Repair', 'Cleaning and repair of laptop keyboards with stuck, unresponsive, or malfunctioning keys.', 850.00),
('SER-046', 'Console Power Supply Repair', 'Diagnosis and repair of console power supply units causing startup or power failures.', 1900.00),
('SER-047', 'Data Transfer and Backup', 'Transfer and backup of personal files between storage devices.', 600.00),
('SER-048', 'Malware Removal Service', 'Detection and removal of malware, spyware, and unwanted software.', 750.00),
('SER-049', 'Comprehensive Device Diagnostics', 'Complete hardware and software inspection to identify device faults and repair requirements.', 350.00),
('SER-050', 'General Motherboard Repair', 'Component-level circuit board troubleshooting and repair for damaged electronic devices.', 2800.00);

-- --------------------------------------------------------

--
-- Table structure for table `status_history`
--

CREATE TABLE `status_history` (
  `status_history_ID` varchar(20) NOT NULL,
  `repair_request_ID` varchar(20) NOT NULL,
  `updated_by` varchar(20) NOT NULL,
  `status` varchar(30) NOT NULL,
  `date_update` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `status_history`
--

INSERT INTO `status_history` (`status_history_ID`, `repair_request_ID`, `updated_by`, `status`, `date_update`) VALUES
('SHS-001', 'REQ-001', 'USR-005', 'Pending', '2026-10-01 08:14:03'),
('SHS-002', 'REQ-001', 'USR-003', 'Under Diagnosis', '2026-10-01 09:15:00'),
('SHS-003', 'REQ-001', 'USR-003', 'In Progress', '2026-10-01 10:30:00'),
('SHS-004', 'REQ-001', 'USR-003', 'Completed', '2026-10-02 14:30:00'),
('SHS-005', 'REQ-002', 'USR-002', 'Pending', '2026-10-01 10:37:03'),
('SHS-006', 'REQ-002', 'USR-003', 'Under Diagnosis', '2026-10-01 12:00:00'),
('SHS-007', 'REQ-002', 'USR-003', 'In Progress', '2026-10-02 09:00:00'),
('SHS-008', 'REQ-002', 'USR-003', 'Completed', '2026-10-03 10:30:00'),
('SHS-009', 'REQ-003', 'USR-005', 'Pending', '2026-10-02 09:22:03'),
('SHS-010', 'REQ-003', 'USR-004', 'Under Diagnosis', '2026-10-02 11:00:00'),
('SHS-011', 'REQ-003', 'USR-004', 'In Progress', '2026-10-03 09:30:00'),
('SHS-012', 'REQ-003', 'USR-004', 'Completed', '2026-10-04 16:00:00'),
('SHS-013', 'REQ-004', 'USR-005', 'Pending', '2026-10-03 11:08:03'),
('SHS-014', 'REQ-004', 'USR-006', 'Under Diagnosis', '2026-10-03 13:30:00'),
('SHS-015', 'REQ-004', 'USR-006', 'In Progress', '2026-10-04 09:00:00'),
('SHS-016', 'REQ-004', 'USR-006', 'Completed', '2026-10-05 13:30:00'),
('SHS-017', 'REQ-005', 'USR-002', 'Pending', '2026-10-04 08:53:03'),
('SHS-018', 'REQ-005', 'USR-003', 'Under Diagnosis', '2026-10-04 11:00:00'),
('SHS-019', 'REQ-005', 'USR-003', 'In Progress', '2026-10-05 09:15:00'),
('SHS-020', 'REQ-005', 'USR-003', 'Completed', '2026-10-07 10:00:00'),
('SHS-021', 'REQ-006', 'USR-005', 'Pending', '2026-10-04 13:29:03'),
('SHS-022', 'REQ-006', 'USR-008', 'Under Diagnosis', '2026-10-04 15:15:00'),
('SHS-023', 'REQ-006', 'USR-008', 'In Progress', '2026-10-05 10:00:00'),
('SHS-024', 'REQ-006', 'USR-008', 'Completed', '2026-10-06 15:30:00'),
('SHS-025', 'REQ-007', 'USR-002', 'Pending', '2026-10-06 14:17:03'),
('SHS-026', 'REQ-007', 'USR-004', 'Under Diagnosis', '2026-10-06 16:00:00'),
('SHS-027', 'REQ-007', 'USR-004', 'In Progress', '2026-10-07 09:00:00'),
('SHS-028', 'REQ-007', 'USR-004', 'Completed', '2026-10-08 14:30:00'),
('SHS-029', 'REQ-008', 'USR-005', 'Pending', '2026-10-07 09:41:03'),
('SHS-030', 'REQ-008', 'USR-004', 'Under Diagnosis', '2026-10-07 12:15:00'),
('SHS-031', 'REQ-008', 'USR-004', 'In Progress', '2026-10-08 09:00:00'),
('SHS-032', 'REQ-008', 'USR-004', 'Completed', '2026-10-10 11:30:00'),
('SHS-033', 'REQ-009', 'USR-002', 'Pending', '2026-10-08 10:05:03'),
('SHS-034', 'REQ-009', 'USR-006', 'Under Diagnosis', '2026-10-08 12:00:00'),
('SHS-035', 'REQ-009', 'USR-006', 'In Progress', '2026-10-09 09:30:00'),
('SHS-036', 'REQ-009', 'USR-006', 'Completed', '2026-10-10 15:30:00'),
('SHS-037', 'REQ-010', 'USR-002', 'Pending', '2026-10-08 10:46:03'),
('SHS-038', 'REQ-010', 'USR-004', 'Under Diagnosis', '2026-10-08 17:30:00'),
('SHS-039', 'REQ-010', 'USR-004', 'In Progress', '2026-10-09 09:15:00'),
('SHS-040', 'REQ-010', 'USR-004', 'Completed', '2026-10-11 16:30:00'),
('SHS-041', 'REQ-011', 'USR-005', 'Pending', '2026-10-10 08:32:03'),
('SHS-042', 'REQ-011', 'USR-006', 'Under Diagnosis', '2026-10-10 10:15:00'),
('SHS-043', 'REQ-011', 'USR-006', 'In Progress', '2026-10-11 09:00:00'),
('SHS-044', 'REQ-011', 'USR-006', 'Completed', '2026-10-12 10:00:00'),
('SHS-045', 'REQ-012', 'USR-002', 'Pending', '2026-10-10 12:54:03'),
('SHS-046', 'REQ-012', 'USR-004', 'Under Diagnosis', '2026-10-10 15:00:00'),
('SHS-047', 'REQ-012', 'USR-004', 'In Progress', '2026-10-11 09:30:00'),
('SHS-048', 'REQ-012', 'USR-004', 'Completed', '2026-10-13 15:00:00'),
('SHS-049', 'REQ-013', 'USR-005', 'Pending', '2026-10-11 15:18:03'),
('SHS-050', 'REQ-013', 'USR-008', 'Under Diagnosis', '2026-10-11 16:45:00'),
('SHS-051', 'REQ-013', 'USR-008', 'In Progress', '2026-10-12 09:00:00'),
('SHS-052', 'REQ-013', 'USR-008', 'Completed', '2026-10-14 16:30:00'),
('SHS-053', 'REQ-014', 'USR-002', 'Pending', '2026-10-12 09:07:03'),
('SHS-054', 'REQ-014', 'USR-003', 'Under Diagnosis', '2026-10-12 10:45:00'),
('SHS-055', 'REQ-014', 'USR-003', 'In Progress', '2026-10-13 09:00:00'),
('SHS-056', 'REQ-014', 'USR-003', 'Completed', '2026-10-14 10:30:00'),
('SHS-057', 'REQ-015', 'USR-005', 'Pending', '2026-10-13 11:43:03'),
('SHS-058', 'REQ-015', 'USR-007', 'Under Diagnosis', '2026-10-13 14:00:00'),
('SHS-059', 'REQ-015', 'USR-007', 'In Progress', '2026-10-14 09:30:00'),
('SHS-060', 'REQ-015', 'USR-007', 'Completed', '2026-10-16 14:00:00'),
('SHS-061', 'REQ-016', 'USR-005', 'Pending', '2026-10-14 09:36:03'),
('SHS-062', 'REQ-016', 'USR-003', 'Under Diagnosis', '2026-10-14 11:00:00'),
('SHS-063', 'REQ-016', 'USR-003', 'In Progress', '2026-10-15 09:00:00'),
('SHS-064', 'REQ-016', 'USR-003', 'Completed', '2026-10-16 15:45:00'),
('SHS-065', 'REQ-017', 'USR-002', 'Pending', '2026-10-14 12:44:03'),
('SHS-066', 'REQ-017', 'USR-003', 'Under Diagnosis', '2026-10-14 14:45:00'),
('SHS-067', 'REQ-017', 'USR-003', 'In Progress', '2026-10-15 10:00:00'),
('SHS-068', 'REQ-017', 'USR-003', 'Completed', '2026-10-17 14:30:00'),
('SHS-069', 'REQ-018', 'USR-005', 'Pending', '2026-10-16 14:21:03'),
('SHS-070', 'REQ-018', 'USR-003', 'Under Diagnosis', '2026-10-16 16:00:00'),
('SHS-071', 'REQ-018', 'USR-003', 'In Progress', '2026-10-17 09:00:00'),
('SHS-072', 'REQ-018', 'USR-003', 'Completed', '2026-10-19 16:00:00'),
('SHS-073', 'REQ-019', 'USR-002', 'Pending', '2026-10-17 10:12:03'),
('SHS-074', 'REQ-019', 'USR-007', 'Under Diagnosis', '2026-10-17 12:15:00'),
('SHS-075', 'REQ-019', 'USR-007', 'In Progress', '2026-10-18 09:30:00'),
('SHS-076', 'REQ-019', 'USR-007', 'Completed', '2026-10-20 12:15:00'),
('SHS-077', 'REQ-020', 'USR-005', 'Pending', '2026-10-18 09:34:03'),
('SHS-078', 'REQ-020', 'USR-003', 'Under Diagnosis', '2026-10-18 11:00:00'),
('SHS-079', 'REQ-020', 'USR-003', 'In Progress', '2026-10-19 09:00:00'),
('SHS-080', 'REQ-020', 'USR-003', 'Completed', '2026-10-21 10:45:00'),
('SHS-081', 'REQ-021', 'USR-002', 'Pending', '2026-10-18 16:27:03'),
('SHS-082', 'REQ-021', 'USR-006', 'Under Diagnosis', '2026-10-19 09:00:00'),
('SHS-083', 'REQ-021', 'USR-006', 'In Progress', '2026-10-20 09:30:00'),
('SHS-084', 'REQ-021', 'USR-006', 'Completed', '2026-10-22 18:00:00'),
('SHS-085', 'REQ-022', 'USR-002', 'Pending', '2026-10-20 11:51:03'),
('SHS-086', 'REQ-022', 'USR-003', 'Under Diagnosis', '2026-10-20 13:45:00'),
('SHS-087', 'REQ-022', 'USR-003', 'In Progress', '2026-10-21 09:00:00'),
('SHS-088', 'REQ-022', 'USR-003', 'Completed', '2026-10-23 13:30:00'),
('SHS-089', 'REQ-023', 'USR-005', 'Pending', '2026-10-21 08:19:03'),
('SHS-090', 'REQ-023', 'USR-007', 'Under Diagnosis', '2026-10-21 10:00:00'),
('SHS-091', 'REQ-023', 'USR-007', 'In Progress', '2026-10-22 09:00:00'),
('SHS-092', 'REQ-023', 'USR-007', 'Completed', '2026-10-24 09:45:00'),
('SHS-093', 'REQ-024', 'USR-002', 'Pending', '2026-10-22 13:42:03'),
('SHS-094', 'REQ-024', 'USR-003', 'Under Diagnosis', '2026-10-22 15:15:00'),
('SHS-095', 'REQ-024', 'USR-003', 'In Progress', '2026-10-23 09:00:00'),
('SHS-096', 'REQ-024', 'USR-003', 'Completed', '2026-10-25 15:00:00'),
('SHS-097', 'REQ-025', 'USR-005', 'Pending', '2026-10-23 10:05:03'),
('SHS-098', 'REQ-025', 'USR-004', 'Under Diagnosis', '2026-10-23 12:00:00'),
('SHS-099', 'REQ-025', 'USR-004', 'In Progress', '2026-10-24 09:00:00'),
('SHS-100', 'REQ-025', 'USR-004', 'Completed', '2026-10-26 11:45:00'),
('SHS-101', 'REQ-026', 'USR-002', 'Pending', '2026-10-24 09:16:03'),
('SHS-102', 'REQ-026', 'USR-006', 'Under Diagnosis', '2026-10-24 11:00:00'),
('SHS-103', 'REQ-026', 'USR-006', 'In Progress', '2026-10-25 09:00:00'),
('SHS-104', 'REQ-026', 'USR-006', 'Completed', '2026-10-27 11:00:00'),
('SHS-105', 'REQ-027', 'USR-005', 'Pending', '2026-10-24 15:39:03'),
('SHS-106', 'REQ-027', 'USR-008', 'Under Diagnosis', '2026-10-24 17:00:00'),
('SHS-107', 'REQ-027', 'USR-008', 'In Progress', '2026-10-25 09:30:00'),
('SHS-108', 'REQ-027', 'USR-008', 'Completed', '2026-10-28 17:00:00'),
('SHS-109', 'REQ-028', 'USR-002', 'Pending', '2026-10-25 12:23:03'),
('SHS-110', 'REQ-028', 'USR-008', 'Under Diagnosis', '2026-10-25 14:00:00'),
('SHS-111', 'REQ-028', 'USR-008', 'In Progress', '2026-10-26 09:00:00'),
('SHS-112', 'REQ-028', 'USR-008', 'Completed', '2026-10-29 13:45:00'),
('SHS-121', 'REQ-001', 'USR-005', 'Ready for Release', '2026-10-02 15:05:00'),
('SHS-122', 'REQ-002', 'USR-002', 'Ready for Release', '2026-10-03 11:05:00'),
('SHS-123', 'REQ-003', 'USR-005', 'Ready for Release', '2026-10-04 16:35:00'),
('SHS-124', 'REQ-004', 'USR-005', 'Ready for Release', '2026-10-05 14:05:00'),
('SHS-125', 'REQ-005', 'USR-002', 'Ready for Release', '2026-10-07 10:35:00'),
('SHS-126', 'REQ-006', 'USR-005', 'Ready for Release', '2026-10-06 16:05:00'),
('SHS-127', 'REQ-007', 'USR-002', 'Ready for Release', '2026-10-08 15:05:00'),
('SHS-128', 'REQ-008', 'USR-005', 'Ready for Release', '2026-10-10 12:05:00'),
('SHS-129', 'REQ-009', 'USR-002', 'Ready for Release', '2026-10-10 16:05:00'),
('SHS-130', 'REQ-010', 'USR-002', 'Ready for Release', '2026-10-11 17:05:00'),
('SHS-131', 'REQ-011', 'USR-005', 'Ready for Release', '2026-10-12 10:35:00'),
('SHS-132', 'REQ-012', 'USR-002', 'Ready for Release', '2026-10-13 15:35:00'),
('SHS-133', 'REQ-013', 'USR-005', 'Ready for Release', '2026-10-14 17:05:00'),
('SHS-134', 'REQ-014', 'USR-002', 'Ready for Release', '2026-10-14 11:05:00'),
('SHS-135', 'REQ-015', 'USR-005', 'Ready for Release', '2026-10-16 14:35:00'),
('SHS-136', 'REQ-016', 'USR-005', 'Ready for Release', '2026-10-16 16:20:00'),
('SHS-137', 'REQ-017', 'USR-002', 'Ready for Release', '2026-10-17 15:05:00'),
('SHS-138', 'REQ-018', 'USR-005', 'Ready for Release', '2026-10-19 16:35:00'),
('SHS-139', 'REQ-019', 'USR-002', 'Ready for Release', '2026-10-20 12:50:00'),
('SHS-140', 'REQ-020', 'USR-005', 'Ready for Release', '2026-10-21 11:20:00'),
('SHS-141', 'REQ-021', 'USR-002', 'Ready for Release', '2026-10-22 18:35:00'),
('SHS-142', 'REQ-022', 'USR-002', 'Ready for Release', '2026-10-23 14:05:00'),
('SHS-143', 'REQ-023', 'USR-005', 'Ready for Release', '2026-10-24 10:20:00'),
('SHS-144', 'REQ-024', 'USR-002', 'Ready for Release', '2026-10-25 15:35:00'),
('SHS-145', 'REQ-025', 'USR-005', 'Ready for Release', '2026-10-26 12:20:00'),
('SHS-146', 'REQ-026', 'USR-002', 'Ready for Release', '2026-10-27 11:35:00'),
('SHS-147', 'REQ-027', 'USR-005', 'Ready for Release', '2026-10-28 17:35:00'),
('SHS-148', 'REQ-028', 'USR-002', 'Ready for Release', '2026-10-29 14:20:00'),
('SHS-151', 'REQ-001', 'USR-005', 'Picked Up', '2026-10-02 15:37:00'),
('SHS-152', 'REQ-002', 'USR-002', 'Picked Up', '2026-10-04 12:37:00'),
('SHS-153', 'REQ-003', 'USR-005', 'Picked Up', '2026-10-05 10:07:00'),
('SHS-154', 'REQ-004', 'USR-005', 'Picked Up', '2026-10-06 14:07:00'),
('SHS-155', 'REQ-006', 'USR-005', 'Picked Up', '2026-10-06 16:37:00'),
('SHS-156', 'REQ-007', 'USR-002', 'Picked Up', '2026-10-09 15:37:00'),
('SHS-157', 'REQ-009', 'USR-002', 'Picked Up', '2026-10-10 16:37:00'),
('SHS-158', 'REQ-011', 'USR-005', 'Picked Up', '2026-10-12 10:52:00'),
('SHS-159', 'REQ-012', 'USR-002', 'Picked Up', '2026-10-14 15:52:00'),
('SHS-160', 'REQ-014', 'USR-002', 'Picked Up', '2026-10-14 11:37:00'),
('SHS-161', 'REQ-016', 'USR-005', 'Picked Up', '2026-10-16 16:52:00'),
('SHS-162', 'REQ-017', 'USR-002', 'Picked Up', '2026-10-19 15:22:00'),
('SHS-163', 'REQ-019', 'USR-002', 'Picked Up', '2026-10-20 13:07:00'),
('SHS-164', 'REQ-021', 'USR-002', 'Picked Up', '2026-10-22 19:07:00'),
('SHS-165', 'REQ-022', 'USR-002', 'Picked Up', '2026-10-24 14:22:00'),
('SHS-166', 'REQ-023', 'USR-005', 'Picked Up', '2026-10-24 11:37:00'),
('SHS-167', 'REQ-024', 'USR-002', 'Picked Up', '2026-10-25 16:07:00'),
('SHS-168', 'REQ-026', 'USR-002', 'Picked Up', '2026-10-27 12:07:00'),
('SHS-169', 'REQ-027', 'USR-005', 'Picked Up', '2026-10-29 17:52:00'),
('SHS-170', 'REQ-028', 'USR-002', 'Picked Up', '2026-10-30 14:37:00'),
('SHS-201', 'REQ-001', 'USR-005', 'Received', '2026-11-18 09:00:00'),
('SHS-202', 'REQ-002', 'USR-002', 'Received', '2026-11-18 10:15:00'),
('SHS-203', 'REQ-003', 'USR-005', 'Received', '2026-11-18 11:30:00'),
('SHS-204', 'REQ-004', 'USR-005', 'Pending', '2026-11-18 13:00:00'),
('SHS-205', 'REQ-005', 'USR-002', 'Pending', '2026-11-18 14:10:00'),
('SHS-206', 'REQ-006', 'USR-005', 'Pending', '2026-11-18 15:20:00'),
('SHS-207', 'REQ-007', 'USR-004', 'Diagnosing', '2026-11-18 16:00:00'),
('SHS-208', 'REQ-008', 'USR-004', 'Diagnosing', '2026-11-19 08:45:00'),
('SHS-209', 'REQ-009', 'USR-006', 'Diagnosing', '2026-11-19 09:30:00'),
('SHS-210', 'REQ-010', 'USR-004', 'Diagnosing', '2026-11-19 10:15:00'),
('SHS-211', 'REQ-011', 'USR-006', 'Repairing', '2026-11-19 11:00:00'),
('SHS-212', 'REQ-012', 'USR-004', 'Repairing', '2026-11-19 11:45:00'),
('SHS-213', 'REQ-013', 'USR-008', 'Repairing', '2026-11-19 13:20:00'),
('SHS-214', 'REQ-014', 'USR-003', 'Repairing', '2026-11-19 14:10:00'),
('SHS-215', 'REQ-015', 'USR-007', 'Ready for pickup', '2026-11-19 15:00:00'),
('SHS-216', 'REQ-016', 'USR-003', 'Ready for pickup', '2026-11-19 15:30:00'),
('SHS-217', 'REQ-017', 'USR-003', 'Ready for pickup', '2026-11-19 16:00:00'),
('SHS-218', 'REQ-018', 'USR-003', 'Ready for pickup', '2026-11-19 16:30:00'),
('SHS-219', 'REQ-019', 'USR-002', 'Picked up', '2026-11-19 17:00:00'),
('SHS-220', 'REQ-020', 'USR-005', 'Picked up', '2026-11-19 17:15:00'),
('SHS-221', 'REQ-021', 'USR-002', 'Picked up', '2026-11-19 17:30:00'),
('SHS-222', 'REQ-022', 'USR-002', 'Picked up', '2026-11-19 17:45:00'),
('SHS-223', 'REQ-023', 'USR-009', 'Released', '2026-11-20 08:15:00'),
('SHS-224', 'REQ-024', 'USR-009', 'Released', '2026-11-20 08:40:00'),
('SHS-225', 'REQ-025', 'USR-009', 'Released', '2026-11-20 09:05:00'),
('SHS-226', 'REQ-026', 'USR-006', 'Completed', '2026-11-20 09:20:00'),
('SHS-227', 'REQ-027', 'USR-008', 'Completed', '2026-11-20 09:40:00'),
('SHS-228', 'REQ-028', 'USR-008', 'Completed', '2026-11-20 09:55:00'),
('SHS-231', 'REQ-031', 'USR-011', 'Received', '2026-11-20 10:28:39'),
('SHS-237', 'REQ-031', 'USR-011', 'Repairing', '2026-11-20 11:28:19');

-- --------------------------------------------------------

--
-- Table structure for table `system_user`
--

CREATE TABLE `system_user` (
  `user_id` varchar(20) NOT NULL,
  `first_name` varchar(250) NOT NULL,
  `last_name` varchar(250) NOT NULL,
  `middle_name` varchar(250) DEFAULT NULL,
  `username` varchar(100) NOT NULL,
  `password_hash` varchar(250) NOT NULL,
  `contact_number` varchar(15) NOT NULL,
  `role` varchar(100) NOT NULL,
  `specialization` varchar(100) DEFAULT NULL,
  `account_status` varchar(20) NOT NULL DEFAULT 'Active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `system_user`
--

INSERT INTO `system_user` (`user_id`, `first_name`, `last_name`, `middle_name`, `username`, `password_hash`, `contact_number`, `role`, `specialization`, `account_status`) VALUES
('USR-001', 'Alex', 'Reyes', 'Mendoza', 'admin_alex', 'hash_admin123', '09171020001', 'Admin', 'System Management', 'Active'),
('USR-002', 'Sarah', 'Gomez', 'Cruz', 'desk_sarah', 'hash_sarah123', '09171030002', 'Receptionist', 'Customer Intake', 'Active'),
('USR-003', 'Dave', 'Santos', 'Ramos', 'tech_dave', 'hash_dave123', '09171040003', 'Technician', 'Mobile & Micro-soldering', 'Active'),
('USR-004', 'Marco', 'Cruz', NULL, 'tech_marco', 'hash_marco123', '09171050004', 'Technician', 'Laptop & Desktop Hardware', 'Active'),
('USR-005', 'Elena', 'Navarro', 'Bautista', 'desk_elena', 'hash_elena123', '09171060005', 'Receptionist', 'Customer Intake & Billing', 'Active'),
('USR-006', 'Carlo', 'Mendoza', 'Aquino', 'tech_carlo', 'hash_carlo123', '09171070006', 'Technician', 'Apple & Tablet Devices', 'Active'),
('USR-007', 'Patricia', 'Villanueva', 'Torres', 'tech_pat', 'hash_pat123', '09171080007', 'Technician', 'Motherboard & IC Repair', 'Active'),
('USR-008', 'Jericho', 'Bautista', NULL, 'tech_jericho', 'hash_jericho123', '09171090008', 'Technician', 'Console & Peripherals', 'Active'),
('USR-009', 'Camille', 'Torres', 'Salazar', 'cashier_camille', 'hash_camille123', '09171100009', 'Cashier', 'Payments & Release', 'Active'),
('USR-010', 'Rafael', 'Aquino', 'Dela Rosa', 'admin_rafael', 'hash_rafael123', '09171200010', 'Admin', 'Operations Supervisor', 'Inactive'),
('USR-011', 'Lorraine', 'Bea', NULL, 'manager_bea', '$2y$12$ALDbUs3wk3ZZwq21M7hkbeutMhxVx/gYNesLENZ5Nuzt.QO6FrYbu', '09123153141', 'Manager', NULL, 'Active'),
('USR-012', 'Sarah', 'Labathi', NULL, 'desk_sara', '$2y$12$.iMbC4Zg66CNMc2O/O6/xuf0ahLRD4Kudy869JNCnomlGlL6NK31O', '09267313521', 'Receptionist', 'Customer Intake', 'Active'),
('USR-013', 'Carlos', 'NaIniwan', NULL, 'tech_carlos', '$2y$12$uBeiavTpKKb2fx5X9RxWLedJNWvYOlJ0e78PvZgDbNnDxDwoVfaTi', '09124645231', 'Technician', 'Apple & Tablet Devices', 'Active'),
('USR-014', 'Alex', 'Eala', NULL, 'tech_alex', '$2y$12$aT/gNwWT3nDLzT794L91Gufle8BMXhb1XBmWNQvivxCaye/iaaSTO', '09231432153', 'Admin', 'System Management', 'Active');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `customer`
--
ALTER TABLE `customer`
  ADD PRIMARY KEY (`customer_ID`);

--
-- Indexes for table `deleted_customer`
--
ALTER TABLE `deleted_customer`
  ADD PRIMARY KEY (`deleted_id`);

--
-- Indexes for table `gadget`
--
ALTER TABLE `gadget`
  ADD PRIMARY KEY (`gadget_ID`),
  ADD UNIQUE KEY `serial_number` (`serial_number`),
  ADD KEY `fk_gadget_customer` (`customer_ID`);

--
-- Indexes for table `invoice`
--
ALTER TABLE `invoice`
  ADD PRIMARY KEY (`invoice_ID`),
  ADD UNIQUE KEY `repair_request_ID` (`repair_request_ID`);

--
-- Indexes for table `payment`
--
ALTER TABLE `payment`
  ADD PRIMARY KEY (`payment_ID`),
  ADD UNIQUE KEY `reference_number` (`reference_number`),
  ADD KEY `fk_payment_user` (`user_ID`),
  ADD KEY `fk_payment_invoice` (`invoice_ID`);

--
-- Indexes for table `repair_request`
--
ALTER TABLE `repair_request`
  ADD PRIMARY KEY (`repair_request_ID`),
  ADD KEY `fk_request_gadget` (`gadget_ID`),
  ADD KEY `fk_request_received_by` (`received_by`),
  ADD KEY `fk_request_assigned_tech` (`assigned_tech_ID`);

--
-- Indexes for table `repair_service`
--
ALTER TABLE `repair_service`
  ADD PRIMARY KEY (`repair_service_ID`),
  ADD KEY `fk_repair_service_request` (`repair_request_ID`),
  ADD KEY `fk_repair_service_service` (`service_ID`);

--
-- Indexes for table `service`
--
ALTER TABLE `service`
  ADD PRIMARY KEY (`service_ID`),
  ADD UNIQUE KEY `service_name` (`service_name`);

--
-- Indexes for table `status_history`
--
ALTER TABLE `status_history`
  ADD PRIMARY KEY (`status_history_ID`),
  ADD KEY `fk_status_history_repair_request` (`repair_request_ID`),
  ADD KEY `fk_status_history_user` (`updated_by`);

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
-- AUTO_INCREMENT for table `deleted_customer`
--
ALTER TABLE `deleted_customer`
  MODIFY `deleted_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `gadget`
--
ALTER TABLE `gadget`
  ADD CONSTRAINT `fk_gadget_customer` FOREIGN KEY (`customer_ID`) REFERENCES `customer` (`customer_ID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `invoice`
--
ALTER TABLE `invoice`
  ADD CONSTRAINT `fk_invoice_repair_request` FOREIGN KEY (`repair_request_ID`) REFERENCES `repair_request` (`repair_request_ID`);

--
-- Constraints for table `payment`
--
ALTER TABLE `payment`
  ADD CONSTRAINT `fk_payment_invoice` FOREIGN KEY (`invoice_ID`) REFERENCES `invoice` (`invoice_ID`),
  ADD CONSTRAINT `fk_payment_user` FOREIGN KEY (`user_ID`) REFERENCES `system_user` (`user_id`);

--
-- Constraints for table `repair_request`
--
ALTER TABLE `repair_request`
  ADD CONSTRAINT `fk_request_assigned_tech` FOREIGN KEY (`assigned_tech_ID`) REFERENCES `system_user` (`user_id`),
  ADD CONSTRAINT `fk_request_gadget` FOREIGN KEY (`gadget_ID`) REFERENCES `gadget` (`gadget_ID`),
  ADD CONSTRAINT `fk_request_received_by` FOREIGN KEY (`received_by`) REFERENCES `system_user` (`user_id`);

--
-- Constraints for table `repair_service`
--
ALTER TABLE `repair_service`
  ADD CONSTRAINT `fk_repair_service_request` FOREIGN KEY (`repair_request_ID`) REFERENCES `repair_request` (`repair_request_ID`),
  ADD CONSTRAINT `fk_repair_service_service` FOREIGN KEY (`service_ID`) REFERENCES `service` (`service_ID`);

--
-- Constraints for table `status_history`
--
ALTER TABLE `status_history`
  ADD CONSTRAINT `fk_status_history_repair_request` FOREIGN KEY (`repair_request_ID`) REFERENCES `repair_request` (`repair_request_ID`),
  ADD CONSTRAINT `fk_status_history_user` FOREIGN KEY (`updated_by`) REFERENCES `system_user` (`user_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
