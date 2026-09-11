-- ================================================================
-- Quetta Services Hub — Final Database Export
-- ================================================================
-- Database:    quettaserviceshub_db
-- Server:     localhost (XAMPP / MariaDB)
-- Generated:  Final project export
--
-- IMPORT INSTRUCTIONS (phpMyAdmin):
--   1. Open phpMyAdmin → http://localhost/phpmyadmin
--   2. Click the "Import" tab (or create the database first, then import)
--   3. Choose this SQL file and click "Go"
--
-- TABLES INCLUDED:
--   1. admins            — Admin login credentials
--   2. services          — Home service listings
--   3. bookings          — Customer booking records
--   4. contact_messages  — Contact form submissions
--
-- FOREIGN KEY:
--   bookings.service_id  →  services.id  (ON DELETE CASCADE)
--
-- SECURITY NOTE:
--   The admins table contains a DEMO / LOCAL-ONLY credential
--   (username: admin, password: 1234). This is NOT a production
--   password. For any live deployment, replace it with a properly
--   hashed password using PHP password_hash().
-- ================================================================

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

-- ================================================================
-- Create Database
-- ================================================================
CREATE DATABASE IF NOT EXISTS `quettaserviceshub_db`
  DEFAULT CHARACTER SET utf8mb4
  COLLATE utf8mb4_general_ci;

USE `quettaserviceshub_db`;

-- ================================================================
-- Table: services
-- Must be created BEFORE bookings (foreign key dependency).
-- Used by: index.php, book.php, edit_service.php,
--          admin_dashboard.php, api/services.php
-- ================================================================

CREATE TABLE `services` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `image` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Demo service records for the project
INSERT INTO `services` (`id`, `name`, `description`, `price`, `image`) VALUES
(1, 'House Cleaning', 'Professional home cleaning service.', 2500.00, 'cleaning.jpg'),
(2, 'Plumbing', 'Expert plumbing repairs and installation.', 1800.00, 'plumbing.jpg'),
(3, 'Electrical Repair', 'Electrical wiring and appliance repair.', 2200.00, 'electrical.jpg'),
(4, 'AC Repair', 'Air conditioner repair and maintenance.', 3000.00, 'ac.jpg'),
(5, 'Painting', 'Interior and exterior house painting.', 5000.00, 'painting.jpg'),
(6, 'Carpentry', 'Furniture repair and woodwork services.', 2800.00, 'carpentry.jpg');

-- ================================================================
-- Table: admins
-- Stores admin login credentials.
-- Used by: admin_login.php
--
-- ** DEMO / LOCAL-ONLY CREDENTIAL **
--   Username: admin
--   Password: 1234   (plain text — for local XAMPP use only)
-- ================================================================

CREATE TABLE `admins` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `admins` (`id`, `username`, `password`) VALUES
(1, 'admin', '1234');

-- ================================================================
-- Table: bookings
-- Stores customer service bookings.
-- Used by: book.php, admin_dashboard.php
--
-- Foreign Key: service_id → services.id (ON DELETE CASCADE)
-- ================================================================

CREATE TABLE `bookings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `service_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `address` text NOT NULL,
  `booking_date` date NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `service_id` (`service_id`),
  CONSTRAINT `bookings_ibfk_1` FOREIGN KEY (`service_id`) REFERENCES `services` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `bookings` (`id`, `service_id`, `name`, `phone`, `address`, `booking_date`, `created_at`) VALUES
(1, 4, 'mahi mahi', '03337869110', 'mahi the house quetta', '2026-08-25', '2026-08-08 06:27:39');

-- ================================================================
-- Table: contact_messages
-- Stores messages submitted via the Contact Us form.
-- Used by: contact.php, admin_dashboard.php
-- ================================================================

CREATE TABLE `contact_messages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `first_name` varchar(100) NOT NULL,
  `last_name` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `phone` varchar(30) NOT NULL,
  `subject` varchar(200) NOT NULL,
  `message` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `contact_messages` (`id`, `first_name`, `last_name`, `email`, `phone`, `subject`, `message`, `created_at`) VALUES
(1, 'majho', 'jhags', 'masi@gmail.com', '00000000000', 'late service', 'service is too late mene khud hi paint kar lia', '2026-08-19 20:10:15');

-- ================================================================
-- AUTO_INCREMENT values are set inline in each CREATE TABLE above.
-- No separate ALTER statements are needed.
-- ================================================================

COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
