-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Sep 19, 2026 at 05:21 AM
-- Server version: 11.8.9-MariaDB-log
-- PHP Version: 7.2.34

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `u598407524_live_club`
--

DELIMITER $$
--
-- Procedures
--
CREATE DEFINER=`u598407524_live_club`@`127.0.0.1` PROCEDURE `sp_get_dashboard_statistics` (IN `p_start_of_month` DATETIME, IN `p_start_of_last_month` DATETIME, IN `p_end_of_last_month` DATETIME, IN `p_today` DATE, IN `p_start_30d` DATE, IN `p_start_12m` DATE, IN `p_client_id` BIGINT, IN `p_filter_start` DATE, IN `p_filter_end` DATE)   BEGIN
                -- 1. Consolidated Core Metrics Row
                SELECT 
                    COUNT(b.id) AS total_bookings,
                    COALESCE(SUM(b.total_amount_incl_tax), 0) AS total_revenue,
                    COALESCE(SUM(b.guest_count), 0) AS total_guests,
                    COALESCE(AVG(b.total_amount_incl_tax), 0) AS avg_booking_value,
                    
                    SUM(CASE WHEN b.created_at >= p_start_of_month THEN 1 ELSE 0 END) AS this_month_bookings,
                    SUM(CASE WHEN b.created_at >= p_start_of_last_month AND b.created_at <= p_end_of_last_month THEN 1 ELSE 0 END) AS last_month_bookings,
                    
                    COALESCE(SUM(CASE WHEN b.created_at >= p_start_of_month THEN b.total_amount_incl_tax ELSE 0 END), 0) AS this_month_revenue,
                    COALESCE(SUM(CASE WHEN b.created_at >= p_start_of_last_month AND b.created_at <= p_end_of_last_month THEN b.total_amount_incl_tax ELSE 0 END), 0) AS last_month_revenue,
                    
                    SUM(CASE WHEN b.booking_date = p_today THEN 1 ELSE 0 END) AS today_bookings,
                    COALESCE(SUM(CASE WHEN b.booking_date = p_today THEN b.total_amount_incl_tax ELSE 0 END), 0) AS today_revenue,
                    
                    SUM(CASE WHEN b.status = 'pending' THEN 1 ELSE 0 END) AS pending_bookings,
                    SUM(CASE WHEN b.status = 'confirmed' THEN 1 ELSE 0 END) AS confirmed_bookings,
                    SUM(CASE WHEN b.status = 'checked_in' THEN 1 ELSE 0 END) AS checked_in_bookings,
                    SUM(CASE WHEN b.status = 'cancelled' THEN 1 ELSE 0 END) AS cancelled_bookings,

                    -- --- NEW FIELDS: TODAY'S DETAILED STATS ---
                    SUM(CASE WHEN b.booking_date = p_today THEN 1 ELSE 0 END) AS today_total_bookings,
                    SUM(CASE WHEN b.booking_date = p_today AND b.status = 'pending' THEN 1 ELSE 0 END) AS today_pending_bookings,
                    SUM(CASE WHEN b.booking_date = p_today AND b.status = 'confirmed' THEN 1 ELSE 0 END) AS today_confirmed_bookings,
                    SUM(CASE WHEN b.booking_date = p_today AND b.status = 'checked_in' THEN 1 ELSE 0 END) AS today_checked_in_bookings,
                    SUM(CASE WHEN b.booking_date = p_today AND b.status = 'cancelled' THEN 1 ELSE 0 END) AS today_cancelled_bookings,
                    COALESCE(SUM(CASE WHEN b.booking_date = p_today THEN b.due_amount ELSE 0 END), 0) AS today_due_amount,

                    -- --- NEW FIELDS: FILTERED TOTAL STATS ---
                    SUM(CASE WHEN 
                        (p_client_id IS NULL OR b.client_id = p_client_id) AND 
                        (p_filter_start IS NULL OR b.booking_date >= p_filter_start) AND 
                        (p_filter_end IS NULL OR b.booking_date <= p_filter_end)
                    THEN 1 ELSE 0 END) AS total_filtered_bookings,

                    SUM(CASE WHEN 
                        (p_client_id IS NULL OR b.client_id = p_client_id) AND 
                        (p_filter_start IS NULL OR b.booking_date >= p_filter_start) AND 
                        (p_filter_end IS NULL OR b.booking_date <= p_filter_end) AND
                        b.status = 'pending'
                    THEN 1 ELSE 0 END) AS total_filtered_pending,

                    SUM(CASE WHEN 
                        (p_client_id IS NULL OR b.client_id = p_client_id) AND 
                        (p_filter_start IS NULL OR b.booking_date >= p_filter_start) AND 
                        (p_filter_end IS NULL OR b.booking_date <= p_filter_end) AND
                        b.status = 'confirmed'
                    THEN 1 ELSE 0 END) AS total_filtered_confirmed,

                    SUM(CASE WHEN 
                        (p_client_id IS NULL OR b.client_id = p_client_id) AND 
                        (p_filter_start IS NULL OR b.booking_date >= p_filter_start) AND 
                        (p_filter_end IS NULL OR b.booking_date <= p_filter_end) AND
                        b.status = 'checked_in'
                    THEN 1 ELSE 0 END) AS total_filtered_checked_in,

                    SUM(CASE WHEN 
                        (p_client_id IS NULL OR b.client_id = p_client_id) AND 
                        (p_filter_start IS NULL OR b.booking_date >= p_filter_start) AND 
                        (p_filter_end IS NULL OR b.booking_date <= p_filter_end) AND
                        b.status = 'cancelled'
                    THEN 1 ELSE 0 END) AS total_filtered_cancelled,

                    COALESCE(SUM(CASE WHEN 
                        (p_client_id IS NULL OR b.client_id = p_client_id) AND 
                        (p_filter_start IS NULL OR b.booking_date >= p_filter_start) AND 
                        (p_filter_end IS NULL OR b.booking_date <= p_filter_end)
                    THEN b.due_amount ELSE 0 END), 0) AS total_filtered_due_amount,

                    (SELECT COUNT(*) FROM clients) AS total_clients,
                    (SELECT COUNT(*) FROM clients WHERE created_at >= p_start_of_month) AS this_month_clients,
                    (SELECT COUNT(*) FROM clients WHERE created_at >= p_start_of_last_month AND created_at <= p_end_of_last_month) AS last_month_clients,
                    (SELECT COUNT(*) FROM clients WHERE is_active = 1) AS active_clients,

                    (SELECT COUNT(*) FROM clubs) AS total_clubs,
                    (SELECT COUNT(*) FROM clubs WHERE is_active = 1) AS active_clubs,
                    (SELECT COALESCE(AVG(average_rating), 0) FROM clubs) AS avg_rating,

                    (SELECT COUNT(*) FROM tables) AS total_tables,
                    (SELECT COALESCE(SUM(capacity), 0) FROM tables) AS total_capacity,
                    (SELECT COUNT(*) FROM tables WHERE status = 'active') AS active_tables
                FROM bookings b;

                -- 2. Daily Trends for Charts (Last 30 Days)
                SELECT 
                    DATE(booking_date) AS `date`,
                    COUNT(id) AS bookings_count,
                    COALESCE(SUM(total_amount_incl_tax), 0) AS revenue
                FROM bookings
                WHERE booking_date >= p_start_30d
                  AND (p_client_id IS NULL OR client_id = p_client_id)
                GROUP BY DATE(booking_date)
                ORDER BY `date` ASC;

                -- 3. Monthly Trends for Charts (Last 12 Months)
                SELECT 
                    DATE_FORMAT(booking_date, '%Y-%m') AS ym,
                    COUNT(id) AS bookings_count,
                    COALESCE(SUM(total_amount_incl_tax), 0) AS revenue
                FROM bookings
                WHERE booking_date >= p_start_12m
                  AND (p_client_id IS NULL OR client_id = p_client_id)
                GROUP BY DATE_FORMAT(booking_date, '%Y-%m')
                ORDER BY ym ASC;

                -- 4. Top 5 Performing Clubs
                SELECT 
                    c.id,
                    c.name,
                    c.city,
                    c.average_rating,
                    c.logo,
                    c.is_active,
                    COUNT(b.id) AS total_bookings,
                    COALESCE(SUM(b.total_amount_incl_tax), 0) AS total_revenue
                FROM clubs c
                LEFT JOIN bookings b ON c.id = b.club_id AND (p_client_id IS NULL OR b.client_id = p_client_id)
                GROUP BY c.id, c.name, c.city, c.average_rating, c.logo, c.is_active
                ORDER BY total_revenue DESC
                LIMIT 5;

                -- 5. Payment Methods Breakdown
                SELECT 
                    COALESCE(NULLIF(payment_method, ''), 'Unspecified') AS method,
                    COUNT(*) AS `count`,
                    COALESCE(SUM(total_amount_incl_tax), 0) AS total_amount
                FROM bookings
                WHERE (p_client_id IS NULL OR client_id = p_client_id)
                  AND (p_filter_start IS NULL OR booking_date >= p_filter_start)
                  AND (p_filter_end IS NULL OR booking_date <= p_filter_end)
                GROUP BY method
                ORDER BY `count` DESC;

                -- 6. Role Distribution & User Percentage
                SELECT 
                    r.id,
                    r.name,
                    r.type,
                    COUNT(u.id) AS users_count,
                    (SELECT COUNT(*) FROM users) AS total_users
                FROM roles r
                LEFT JOIN users u ON r.id = u.role_id
                GROUP BY r.id, r.name, r.type
                ORDER BY users_count DESC;
            END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `audit_logs`
--

CREATE TABLE `audit_logs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `action` varchar(255) NOT NULL,
  `model_type` varchar(255) NOT NULL,
  `model_id` bigint(20) UNSIGNED DEFAULT NULL,
  `old_values` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`old_values`)),
  `new_values` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`new_values`)),
  `ip_address` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `bookings`
--

CREATE TABLE `bookings` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `client_id` bigint(20) UNSIGNED DEFAULT NULL,
  `table_id` bigint(20) UNSIGNED DEFAULT NULL,
  `event_id` bigint(20) UNSIGNED DEFAULT NULL,
  `club_id` bigint(20) UNSIGNED DEFAULT NULL,
  `client_name` varchar(255) DEFAULT NULL,
  `client_phone` varchar(255) DEFAULT NULL,
  `client_email` varchar(255) DEFAULT NULL,
  `club_name` varchar(255) DEFAULT NULL,
  `base_price` decimal(10,2) NOT NULL DEFAULT 0.00,
  `spend_amount` decimal(10,2) NOT NULL DEFAULT 0.00,
  `paid_amount` decimal(10,2) NOT NULL DEFAULT 0.00,
  `due_amount` decimal(10,2) NOT NULL DEFAULT 0.00,
  `personalised_event` varchar(255) DEFAULT NULL,
  `discount_type` enum('percentage','fixed') DEFAULT NULL,
  `discount_code` varchar(100) DEFAULT NULL,
  `discount_source` varchar(100) DEFAULT NULL,
  `discount_note` varchar(100) DEFAULT NULL,
  `discount_amount` decimal(10,2) NOT NULL DEFAULT 0.00,
  `max_discount_amount` decimal(10,2) DEFAULT NULL,
  `tax_rate` decimal(10,2) NOT NULL DEFAULT 0.00,
  `tax_amount` decimal(10,2) NOT NULL DEFAULT 0.00,
  `total_amount_excl_tax` decimal(10,2) NOT NULL DEFAULT 0.00,
  `total_amount_incl_tax` decimal(10,2) NOT NULL DEFAULT 0.00,
  `payment_status` varchar(100) DEFAULT NULL,
  `payment_gateway` varchar(100) DEFAULT NULL,
  `payment_method` varchar(100) DEFAULT NULL,
  `booking_date` date NOT NULL,
  `start_time` time DEFAULT NULL,
  `checked_out_date` date DEFAULT NULL,
  `end_time` time DEFAULT NULL,
  `guest_count` int(11) NOT NULL DEFAULT 0,
  `status` varchar(255) NOT NULL DEFAULT 'pending',
  `special_requests` text DEFAULT NULL,
  `qr_code` varchar(256) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `bookings`
--

INSERT INTO `bookings` (`id`, `client_id`, `table_id`, `event_id`, `club_id`, `client_name`, `client_phone`, `client_email`, `club_name`, `base_price`, `spend_amount`, `paid_amount`, `due_amount`, `personalised_event`, `discount_type`, `discount_code`, `discount_source`, `discount_note`, `discount_amount`, `max_discount_amount`, `tax_rate`, `tax_amount`, `total_amount_excl_tax`, `total_amount_incl_tax`, `payment_status`, `payment_gateway`, `payment_method`, `booking_date`, `start_time`, `checked_out_date`, `end_time`, `guest_count`, `status`, `special_requests`, `qr_code`, `created_at`, `updated_at`) VALUES
(1, 1, 1, NULL, 1, 'Tester', '9876543210', NULL, 'Mid Night Club', 0.00, 0.00, 0.00, 0.00, NULL, NULL, NULL, NULL, NULL, 0.00, NULL, 0.00, 0.00, 0.00, 0.00, 'pending', 'cash', 'cash', '2026-09-11', '23:00:00', NULL, '00:00:00', 0, 'checked_in', NULL, 'CLUB-6AA3C369A34AD-10', '2026-09-11 09:01:29', '2026-09-15 18:02:51'),
(2, 2, 1, NULL, 1, 'Boby', '9119001082', NULL, 'Mid Night Club', 0.00, 0.00, 0.00, 0.00, NULL, NULL, NULL, NULL, NULL, 0.00, NULL, 0.00, 0.00, 0.00, 0.00, 'pending', 'cash', 'cash', '2026-09-12', '23:00:00', NULL, '00:00:00', 0, 'pending', NULL, 'CLUB-6AA52391CA125-20', '2026-09-12 10:04:01', '2026-09-12 10:04:01'),
(3, 1, NULL, NULL, 1, 'Live Tester', '9876543210', NULL, 'Mid Night Club', 0.00, 0.00, 0.00, 0.00, NULL, NULL, NULL, NULL, NULL, 0.00, NULL, 0.00, 0.00, 0.00, 0.00, 'pending', 'cash', 'cash', '2026-09-16', '23:00:00', NULL, '00:00:00', 0, 'cancelled', NULL, 'CLUB-6AAA5E331B4D6-10', '2026-09-16 09:15:31', '2026-09-16 09:15:58'),
(4, 1, NULL, NULL, 1, 'Live Tester', '9876543210', NULL, 'Mid Night Club', 0.00, 0.00, 0.00, 0.00, NULL, NULL, NULL, NULL, NULL, 0.00, NULL, 0.00, 0.00, 0.00, 0.00, 'pending', 'cash', 'cash', '2026-09-16', '23:00:00', NULL, '00:00:00', 0, 'pending', NULL, 'CLUB-6AAA7E4E529EC-10', '2026-09-16 11:32:30', '2026-09-16 11:32:30');

-- --------------------------------------------------------

--
-- Table structure for table `booking_guests`
--

CREATE TABLE `booking_guests` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `booking_id` bigint(20) UNSIGNED NOT NULL,
  `client_id` bigint(20) UNSIGNED DEFAULT NULL,
  `guest_id` bigint(20) UNSIGNED DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `age` varchar(255) DEFAULT NULL,
  `gender` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `branches`
--

CREATE TABLE `branches` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `club_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `clients`
--

CREATE TABLE `clients` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `avatar` varchar(255) DEFAULT NULL,
  `age` varchar(255) DEFAULT NULL,
  `gender` varchar(255) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `role_id` bigint(20) UNSIGNED DEFAULT NULL,
  `google_id` varchar(255) DEFAULT NULL,
  `fcm_token` varchar(255) DEFAULT NULL,
  `is_email_verified` tinyint(1) NOT NULL DEFAULT 0,
  `email_verified_at` varchar(255) DEFAULT NULL,
  `is_phone_verified` tinyint(1) NOT NULL DEFAULT 0,
  `phone_verified_at` varchar(255) DEFAULT NULL,
  `login_at` varchar(255) DEFAULT NULL,
  `version` varchar(255) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `clients`
--

INSERT INTO `clients` (`id`, `name`, `email`, `phone`, `avatar`, `age`, `gender`, `password`, `role_id`, `google_id`, `fcm_token`, `is_email_verified`, `email_verified_at`, `is_phone_verified`, `phone_verified_at`, `login_at`, `version`, `is_active`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Live Tester', NULL, '9876543210', 'clients/K4EKiShJjqrKn89T6zjCbNEHRh1Wa9aOuEK6Xg2I.jpg', '25', 'male', '$2y$12$/YnakPnMRzZi41hxh8WDHu6ZL884SrJ8DmaHq/0sng.xz0aBF/vym', NULL, NULL, 'eyOCwY8UQjiR5hUlMn6O6j:APA91bHrTBbOU20l67ho39wPhilqUZGF1V_gQc-L7VdPObuEQihceZwQ3LP46y3FTn92rf8IcZwbDjvMVorXz_P4ggZJJuZ8N0EG4SxcB2TZTlrerY5nPzw', 0, NULL, 1, '2026-09-11 08:33:44', '2026-09-19 05:07:03', '1.0.4', 1, NULL, '2026-09-11 08:33:44', '2026-09-19 05:07:03'),
(2, 'Boby', NULL, '9119001082', NULL, NULL, NULL, '$2y$12$NIUrwYn3cN.ZcZaZ7aBbDO3qymtOVU43GA/nF6SD1HDJLsQf0IrIK', NULL, NULL, 'dJwzUVyoRqaWKa_2qacu8V:APA91bHKshdfESaJYVELXktGsfOjCdjKLyXYEHvRrpqEIkxQ6AMXxVAMwc4SSSQtbkXx3U7s4X8lbWIb0dEPE0HR49cepzmcRCUrBY8-Ew5Ddl49cUYBlOc', 0, NULL, 1, '2026-09-12 10:03:09', '2026-09-12 10:03:09', NULL, 1, NULL, '2026-09-12 10:03:09', '2026-09-12 10:03:16'),
(3, 'yes', NULL, '6393604028', NULL, NULL, NULL, '$2y$12$3FuqzkfobosLVdeCwT9HuO4pdE4tUpWRh53L3Syo/VimbeRgy9I/O', NULL, NULL, 'eAy2q2WDQBO6KL45QnmmOS:APA91bGbBgdjnFskmKKUO7bq_JO_gQT8bMCS2yLfJ6-x9bt4z-QZ_iMSHyUq3uD_FZx5ZFncDL9c40yQvhhLyh1zvlnc7uyMqha62V_1cOC_pMOEJSyLHs0', 0, NULL, 1, '2026-09-16 08:41:23', '2026-09-16 11:24:39', '1.0.4', 1, NULL, '2026-09-16 08:41:24', '2026-09-16 11:24:40'),
(4, 'shrey', NULL, '6387848896', NULL, NULL, NULL, '$2y$12$Eo3gF00fX2FiaJRF6WVV9etWinzIhHXbXm7sDhtWLX3bwmLOAkAzq', NULL, NULL, 'e3vtmjR3QEy4I9wfw_duaM:APA91bGIPruwFBzRUHfTbCnmVj_mWxuqaQl0DVgLpjzHKsKTs-CoQvia4mltZVxz8cFjpbGTMPv-zJWJIBBHRbWXalm0P0aQ36wIPzpFlaKjJHBrBbP6efE', 0, NULL, 1, '2026-09-16 11:21:36', '2026-09-16 11:21:36', '1.0.4', 1, NULL, '2026-09-16 11:21:36', '2026-09-16 11:21:43');

-- --------------------------------------------------------

--
-- Table structure for table `client_balances`
--

CREATE TABLE `client_balances` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `client_id` bigint(20) UNSIGNED DEFAULT NULL,
  `total_due` decimal(10,2) NOT NULL DEFAULT 0.00,
  `total_advance` decimal(10,2) NOT NULL DEFAULT 0.00,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `client_balances`
--

INSERT INTO `client_balances` (`id`, `client_id`, `total_due`, `total_advance`, `created_at`, `updated_at`) VALUES
(1, 1, 0.00, 0.00, '2026-09-11 09:03:37', '2026-09-11 09:03:37');

-- --------------------------------------------------------

--
-- Table structure for table `client_guests`
--

CREATE TABLE `client_guests` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `client_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `age` varchar(255) DEFAULT NULL,
  `gender` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `client_ledgers`
--

CREATE TABLE `client_ledgers` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `client_id` bigint(20) UNSIGNED NOT NULL,
  `booking_id` bigint(20) UNSIGNED DEFAULT NULL,
  `payment_id` bigint(20) UNSIGNED DEFAULT NULL,
  `due_type` varchar(255) NOT NULL,
  `advance_type` varchar(255) NOT NULL,
  `due_amount` decimal(10,2) NOT NULL,
  `advance_amount` decimal(10,2) NOT NULL,
  `advance_after` decimal(10,2) NOT NULL DEFAULT 0.00,
  `due_after` decimal(10,2) NOT NULL DEFAULT 0.00,
  `action_for` varchar(255) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `client_ledgers`
--

INSERT INTO `client_ledgers` (`id`, `client_id`, `booking_id`, `payment_id`, `due_type`, `advance_type`, `due_amount`, `advance_amount`, `advance_after`, `due_after`, `action_for`, `description`, `created_at`, `updated_at`) VALUES
(1, 1, 1, NULL, 'credit', 'credit', 0.00, 0.00, 0.00, 0.00, 'adjustment', 'Adjustment by admin #1', '2026-09-11 09:03:37', '2026-09-11 09:03:37'),
(2, 1, 1, NULL, 'credit', 'credit', 0.00, 0.00, 0.00, 0.00, 'adjustment', 'Adjustment by admin #1', '2026-09-11 09:04:27', '2026-09-11 09:04:27'),
(3, 1, 1, NULL, 'credit', 'credit', 0.00, 0.00, 0.00, 0.00, 'adjustment', 'Adjustment by admin #1', '2026-09-11 09:10:35', '2026-09-11 09:10:35'),
(4, 1, 1, NULL, 'credit', 'credit', 0.00, 0.00, 0.00, 0.00, 'adjustment', 'Adjustment by admin #1', '2026-09-15 18:02:51', '2026-09-15 18:02:51');

-- --------------------------------------------------------

--
-- Table structure for table `clubs`
--

CREATE TABLE `clubs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `city` varchar(255) DEFAULT NULL,
  `logo` varchar(255) DEFAULT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `whatsapp_no` varchar(255) DEFAULT NULL,
  `primary_business_whatsapp` varchar(255) DEFAULT NULL,
  `opening_time` time DEFAULT NULL,
  `close_time` time DEFAULT NULL,
  `disclaimer` varchar(255) DEFAULT NULL,
  `average_rating` decimal(3,1) NOT NULL DEFAULT 0.0,
  `review_count` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `rating_5_percent` decimal(5,2) NOT NULL DEFAULT 0.00,
  `rating_4_percent` decimal(5,2) NOT NULL DEFAULT 0.00,
  `rating_3_percent` decimal(5,2) NOT NULL DEFAULT 0.00,
  `rating_2_percent` decimal(5,2) NOT NULL DEFAULT 0.00,
  `rating_1_percent` decimal(5,2) NOT NULL DEFAULT 0.00,
  `file_type` varchar(255) DEFAULT NULL,
  `file_path` varchar(255) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `clubs`
--

INSERT INTO `clubs` (`id`, `name`, `description`, `address`, `city`, `logo`, `phone`, `whatsapp_no`, `primary_business_whatsapp`, `opening_time`, `close_time`, `disclaimer`, `average_rating`, `review_count`, `rating_5_percent`, `rating_4_percent`, `rating_3_percent`, `rating_2_percent`, `rating_1_percent`, `file_type`, `file_path`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'Mid Night Club', NULL, 'SCO 34-36, Sector 29', 'Gurugram', 'clubs/logos/5JB3dJhGJvhrq9o2PgGj8mT25FsbaPLyORV7o3fW.jpg', '9899281515', '9899281515', '9899281515', '23:00:22', '06:00:22', 'Pay in advance to enjoy an exclusive 15% to 20% savings on your dining experience.', 0.0, 0, 0.00, 0.00, 0.00, 0.00, 0.00, 'video', 'clubs/files/gVHaqi0jWZj0SStw6hNBx4d1JdONQXcmPZp8io8B.mp4', 1, '2026-08-03 10:53:19', '2026-09-11 08:59:05');

-- --------------------------------------------------------

--
-- Table structure for table `club_assets`
--

CREATE TABLE `club_assets` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `club_id` bigint(20) UNSIGNED NOT NULL,
  `batch_id` varchar(255) DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `file_name` varchar(255) NOT NULL,
  `original_name` varchar(255) DEFAULT NULL,
  `file_path` varchar(255) NOT NULL,
  `file_type` varchar(255) NOT NULL DEFAULT 'image',
  `mime_type` varchar(255) DEFAULT NULL,
  `file_size` bigint(20) UNSIGNED DEFAULT NULL,
  `width` int(10) UNSIGNED DEFAULT NULL,
  `height` int(10) UNSIGNED DEFAULT NULL,
  `duration` varchar(255) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_by` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `club_assets`
--

INSERT INTO `club_assets` (`id`, `club_id`, `batch_id`, `title`, `file_name`, `original_name`, `file_path`, `file_type`, `mime_type`, `file_size`, `width`, `height`, `duration`, `is_active`, `created_by`, `created_at`, `updated_at`) VALUES
(78, 1, 'a2739c63-cc69-41d7-955e-42325acd07ee', 'File Example MP4 640 3MG', 'FJf4YPs07wahfZqUhnxHJKYjT99lsv_1786174287.mp4', 'file_example_MP4_640_3MG.mp4', 'club_assets/1/videos/FJf4YPs07wahfZqUhnxHJKYjT99lsv_1786174287.mp4', 'video', 'video/mp4', 3114374, NULL, NULL, NULL, 1, 1, '2026-08-08 02:01:27', '2026-08-08 02:01:27'),
(79, 1, 'a2739c63-cc69-41d7-955e-42325acd07ee', 'Unnamed (3)', 'hZS7dI4fQ5mhAhT23rElZvDcExipf7_1786174289.webp', 'unnamed (3).webp', 'club_assets/1/images/hZS7dI4fQ5mhAhT23rElZvDcExipf7_1786174289.webp', 'image', 'image/webp', 70362, 680, 453, NULL, 1, 1, '2026-08-08 02:01:29', '2026-08-08 02:01:29'),
(80, 1, 'a2739c63-cc69-41d7-955e-42325acd07ee', 'Unnamed (4)', 'KRqYgVae007nkjJ6BTwjXNvFKUnYUR_1786174289.webp', 'unnamed (4).webp', 'club_assets/1/images/KRqYgVae007nkjJ6BTwjXNvFKUnYUR_1786174289.webp', 'image', 'image/webp', 40798, 382, 510, NULL, 1, 1, '2026-08-08 02:01:29', '2026-08-08 02:01:29'),
(81, 1, 'a2739c9b-eb78-4f2e-9db9-9b5425e8cb7c', 'Unnamed (5)', '72sey0KWxxp5uUSlPrQOHFvIxzcFKI_1786174326.webp', 'unnamed (5).webp', 'club_assets/1/images/72sey0KWxxp5uUSlPrQOHFvIxzcFKI_1786174326.webp', 'image', 'image/webp', 39938, 382, 510, NULL, 1, 1, '2026-08-08 02:02:06', '2026-08-08 02:02:06'),
(82, 1, 'a2739c9b-eb78-4f2e-9db9-9b5425e8cb7c', 'Unnamed (4)', 'Z6CewiuRsN53lwgWPfSFhNo0VbF8kS_1786174326.webp', 'unnamed (4).webp', 'club_assets/1/images/Z6CewiuRsN53lwgWPfSFhNo0VbF8kS_1786174326.webp', 'image', 'image/webp', 40798, 382, 510, NULL, 1, 1, '2026-08-08 02:02:06', '2026-08-08 02:02:06'),
(83, 1, 'a2739c9b-eb78-4f2e-9db9-9b5425e8cb7c', 'Unnamed (3)', 'SHGc18KpzvKZBDxEsoJaliaW8E7tNf_1786174326.webp', 'unnamed (3).webp', 'club_assets/1/images/SHGc18KpzvKZBDxEsoJaliaW8E7tNf_1786174326.webp', 'image', 'image/webp', 70362, 680, 453, NULL, 1, 1, '2026-08-08 02:02:06', '2026-08-08 02:02:06'),
(113, 1, NULL, 'External Media', 'premium_photo-1708589337907-a08302e6ec3c', 'https://plus.unsplash.com/premium_photo-1708589337907-a08302e6ec3c?q=80&w=870&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D', 'https://plus.unsplash.com/premium_photo-1708589337907-a08302e6ec3c?q=80&w=870&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D', 'image_url', NULL, 0, NULL, NULL, NULL, 1, NULL, '2026-08-08 14:58:39', '2026-08-08 14:58:39'),
(114, 1, NULL, 'External Media', 'premium_photo-1757507025808-cfd1ca06fa36', 'https://plus.unsplash.com/premium_photo-1757507025808-cfd1ca06fa36?q=80&w=1032&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D', 'https://plus.unsplash.com/premium_photo-1757507025808-cfd1ca06fa36?q=80&w=1032&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D', 'image_url', NULL, 0, NULL, NULL, NULL, 1, NULL, '2026-08-08 14:58:39', '2026-08-08 14:58:39'),
(115, 1, NULL, 'External Media', 'premium_photo-1661369901339-f6ac6d76541f', 'https://plus.unsplash.com/premium_photo-1661369901339-f6ac6d76541f?q=80&w=870&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D', 'https://plus.unsplash.com/premium_photo-1661369901339-f6ac6d76541f?q=80&w=870&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D', 'image_url', NULL, 0, NULL, NULL, NULL, 1, NULL, '2026-08-08 14:58:39', '2026-08-08 14:58:39'),
(116, 1, NULL, 'External Media', 'photo-1588083066783-8828e623bad7', 'https://images.unsplash.com/photo-1588083066783-8828e623bad7?q=80&w=870&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D', 'https://images.unsplash.com/photo-1588083066783-8828e623bad7?q=80&w=870&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D', 'image_url', NULL, 0, NULL, NULL, NULL, 1, NULL, '2026-08-08 14:58:39', '2026-08-08 14:58:39'),
(117, 1, NULL, 'External Media', 'photo-1485872299829-c673f5194813', 'https://images.unsplash.com/photo-1485872299829-c673f5194813?q=80&w=560&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D', 'https://images.unsplash.com/photo-1485872299829-c673f5194813?q=80&w=560&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D', 'image_url', NULL, 0, NULL, NULL, NULL, 1, NULL, '2026-08-08 14:58:39', '2026-08-08 14:58:39'),
(118, 1, NULL, 'External Media', 'photo-1720623784273-f9388b9aa535', 'https://images.unsplash.com/photo-1720623784273-f9388b9aa535?q=80&w=774&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D', 'https://images.unsplash.com/photo-1720623784273-f9388b9aa535?q=80&w=774&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D', 'image_url', NULL, 0, NULL, NULL, NULL, 1, NULL, '2026-08-08 14:58:39', '2026-08-08 14:58:39'),
(119, 1, NULL, 'External Media', 'photo-1632008650337-3b8befff9d75', 'https://images.unsplash.com/photo-1632008650337-3b8befff9d75?q=80&w=870&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D', 'https://images.unsplash.com/photo-1632008650337-3b8befff9d75?q=80&w=870&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D', 'image_url', NULL, 0, NULL, NULL, NULL, 1, NULL, '2026-08-08 14:58:39', '2026-08-08 14:58:39'),
(120, 1, NULL, 'External Media', 'photo-1645730826845-cd2ddec9984f', 'https://images.unsplash.com/photo-1645730826845-cd2ddec9984f?q=80&w=844&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D', 'https://images.unsplash.com/photo-1645730826845-cd2ddec9984f?q=80&w=844&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D', 'image_url', NULL, 0, NULL, NULL, NULL, 1, NULL, '2026-08-08 14:58:39', '2026-08-08 14:58:39'),
(121, 1, NULL, 'External Media', 'photo-1713450606272-0ee6c418dd5f', 'https://images.unsplash.com/photo-1713450606272-0ee6c418dd5f?q=80&w=870&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D', 'https://images.unsplash.com/photo-1713450606272-0ee6c418dd5f?q=80&w=870&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D', 'image_url', NULL, 0, NULL, NULL, NULL, 1, NULL, '2026-08-08 14:58:39', '2026-08-08 14:58:39'),
(122, 1, NULL, 'External Media', 'photo-1569315618680-3d673b5e1514', 'https://images.unsplash.com/photo-1569315618680-3d673b5e1514?q=80&w=870&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D', 'https://images.unsplash.com/photo-1569315618680-3d673b5e1514?q=80&w=870&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D', 'image_url', NULL, 0, NULL, NULL, NULL, 1, NULL, '2026-08-08 14:58:39', '2026-08-08 14:58:39');

-- --------------------------------------------------------

--
-- Table structure for table `club_staff`
--

CREATE TABLE `club_staff` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `club_id` bigint(20) UNSIGNED NOT NULL,
  `role` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `avatar` varchar(255) DEFAULT NULL,
  `social_accounts` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`social_accounts`)),
  `bio` text DEFAULT NULL,
  `contact_no` varchar(255) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `complaints`
--

CREATE TABLE `complaints` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `client_id` bigint(20) UNSIGNED NOT NULL,
  `club_id` bigint(20) UNSIGNED DEFAULT NULL,
  `booking_id` bigint(20) UNSIGNED DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `message` varchar(2000) NOT NULL,
  `remark` varchar(1000) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `datagrid_saved_filters`
--

CREATE TABLE `datagrid_saved_filters` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `src` varchar(255) NOT NULL,
  `applied` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`applied`)),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `events`
--

CREATE TABLE `events` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `club_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `event_date` date NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `image` varchar(255) DEFAULT NULL,
  `featured_image` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `events`
--

INSERT INTO `events` (`id`, `club_id`, `name`, `description`, `event_date`, `is_active`, `image`, `featured_image`, `created_at`, `updated_at`) VALUES
(1, 1, 'Bollywood Night ft. DJ Shadow', 'Experience the biggest Bollywood dance explosion in Gurugram featuring award-winning DJ Shadow spinning chart-topping remixes.', '2026-08-03', 1, 'events/9lybUDBoLMNFDFmSKXptmCkZJUxXzn8TZ0jaNHiM.webp', 'events/iIFZ7hy5wvW5HhyrjeouvptkZi5us2eHCjrbp5Zk.webp', '2026-08-03 12:17:42', '2026-08-03 12:17:42'),
(2, 1, 'Neon EDM Carnival & Glow Party', 'Ultraviolet lasers, glow paint artists, immersive bass drops, and headline electronic dance music producers.', '2026-08-03', 1, 'events/S4skwAqMUvMMbcAbpLp7LTLjfNvGnZL1XoRSi5UH.webp', 'events/Pi3A9F3cEbeozvsqItbLtrsRV3GBP0xEL6AdvjbE.webp', '2026-08-03 12:17:42', '2026-08-03 12:17:42'),
(3, 1, 'Midnight Saturday Extravaganza', 'Our signature weekend residency with celebrity guest bartenders, acrobatic aerialists, and resident DJ sets.', '2026-08-03', 1, 'events/q9jvDTCOoRErzDe0L95PFxfT2NI6scoCK7CXuwny.webp', 'events/saturday_party_feat.jpg', '2026-08-03 12:17:42', '2026-08-03 12:17:42'),
(4, 1, 'Techno Underground: Deep Minimal Sessions', 'A dedicated deep-tech journey with hypnotic lighting and German underground techno grooves till early sunrise.', '2026-08-03', 1, 'events/RWWCcuEfMAXCS1oA3Vo8oSFZWzopf1TW0qPbDfjy.webp', 'events/techno_underground_feat.jpg', '2026-08-03 12:17:42', '2026-08-03 12:17:42'),
(5, 1, 'Independence Eve Gala 2026', 'Patriotic laser choreography, luxury VIP table packages, and high-energy commercial hits all night long.', '2026-08-03', 1, 'events/m7ucvAR8rz1bzz5WMrHMg0dlJm0zasgbmcjLI21q.webp', 'events/independence_gala_feat.jpg', '2026-08-03 12:17:42', '2026-08-03 12:17:42'),
(6, 1, 'Ladies & Champagne Night', 'Complimentary welcome bubbles for ladies, artisanal tapas, and irresistible R&B, Hip-Hop, and Commercial jams.', '2026-08-03', 1, 'events/oWtgJOACgm2HwQkPgrETPGvreKjVXjvqzm1JrmY7.webp', 'events/ladies_night_feat.jpg', '2026-08-03 12:17:42', '2026-08-03 12:17:42'),
(7, 1, 'Retro 90s & 2000s Pop Blast', 'A nostalgic throwback celebration honoring the golden anthems of pop, disco, and rock classics.', '2026-08-03', 1, 'events/ZuTX3oxxhz4svGOjQSUc9r1ZlQgon9AERemAyKH0.jpg', 'events/retro_night_feat.jpg', '2026-08-03 12:17:42', '2026-08-03 12:17:42'),
(8, 1, 'Sunburn Club Showcase - Live in Gurugram', 'Official festival club takeover with state-of-the-art CO2 cannons, confetti blasts, and international festival DJs.', '2026-08-03', 1, 'events/PaV4Rb0dY6v43dnQ0L4xe4QviB1nOMT2lO7Tpk4H.webp', 'events/sunburn_showcase_feat.jpg', '2026-08-03 12:17:42', '2026-08-03 12:17:42');

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `failed_jobs`
--

INSERT INTO `failed_jobs` (`id`, `uuid`, `connection`, `queue`, `payload`, `exception`, `failed_at`) VALUES
(1, 'b8544d0b-7cec-4919-87ed-0be7452cc4b4', 'database', 'default', '{\"uuid\":\"b8544d0b-7cec-4919-87ed-0be7452cc4b4\",\"displayName\":\"App\\\\Jobs\\\\SendFirebaseNotificationJob\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":3,\"maxExceptions\":3,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\SendFirebaseNotificationJob\",\"command\":\"O:36:\\\"App\\\\Jobs\\\\SendFirebaseNotificationJob\\\":5:{s:4:\\\"type\\\";s:6:\\\"tokens\\\";s:6:\\\"target\\\";s:5:\\\"admin\\\";s:5:\\\"title\\\";s:24:\\\"Request Received! 📨#1\\\";s:4:\\\"body\\\";s:68:\\\"Your reservation has been received and is currently being processed.\\\";s:4:\\\"data\\\";a:4:{s:4:\\\"type\\\";s:14:\\\"booking_status\\\";s:10:\\\"created_by\\\";N;s:6:\\\"remark\\\";s:5:\\\"admin\\\";s:10:\\\"additional\\\";a:3:{s:6:\\\"screen\\\";s:7:\\\"booking\\\";s:10:\\\"booking_id\\\";i:1;s:9:\\\"client_id\\\";i:1;}}}\",\"batchId\":null},\"createdAt\":1789117289,\"delay\":null}', 'Error: Cannot modify readonly property App\\Jobs\\SendFirebaseNotificationJob::$target in /home/u598407524/domains/sunoyaar.com/public_html/midnightclub/app/Jobs/SendFirebaseNotificationJob.php:48\nStack trace:\n#0 /home/u598407524/domains/sunoyaar.com/public_html/midnightclub/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(36): App\\Jobs\\SendFirebaseNotificationJob->handle()\n#1 /home/u598407524/domains/sunoyaar.com/public_html/midnightclub/vendor/laravel/framework/src/Illuminate/Container/Util.php(43): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()\n#2 /home/u598407524/domains/sunoyaar.com/public_html/midnightclub/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(96): Illuminate\\Container\\Util::unwrapIfClosure()\n#3 /home/u598407524/domains/sunoyaar.com/public_html/midnightclub/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(35): Illuminate\\Container\\BoundMethod::callBoundMethod()\n#4 /home/u598407524/domains/sunoyaar.com/public_html/midnightclub/vendor/laravel/framework/src/Illuminate/Container/Container.php(799): Illuminate\\Container\\BoundMethod::call()\n#5 /home/u598407524/domains/sunoyaar.com/public_html/midnightclub/vendor/laravel/framework/src/Illuminate/Bus/Dispatcher.php(129): Illuminate\\Container\\Container->call()\n#6 /home/u598407524/domains/sunoyaar.com/public_html/midnightclub/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(180): Illuminate\\Bus\\Dispatcher->Illuminate\\Bus\\{closure}()\n#7 /home/u598407524/domains/sunoyaar.com/public_html/midnightclub/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(137): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}()\n#8 /home/u598407524/domains/sunoyaar.com/public_html/midnightclub/vendor/laravel/framework/src/Illuminate/Bus/Dispatcher.php(133): Illuminate\\Pipeline\\Pipeline->then()\n#9 /home/u598407524/domains/sunoyaar.com/public_html/midnightclub/vendor/laravel/framework/src/Illuminate/Queue/CallQueuedHandler.php(136): Illuminate\\Bus\\Dispatcher->dispatchNow()\n#10 /home/u598407524/domains/sunoyaar.com/public_html/midnightclub/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(180): Illuminate\\Queue\\CallQueuedHandler->Illuminate\\Queue\\{closure}()\n#11 /home/u598407524/domains/sunoyaar.com/public_html/midnightclub/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(137): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}()\n#12 /home/u598407524/domains/sunoyaar.com/public_html/midnightclub/vendor/laravel/framework/src/Illuminate/Queue/CallQueuedHandler.php(129): Illuminate\\Pipeline\\Pipeline->then()\n#13 /home/u598407524/domains/sunoyaar.com/public_html/midnightclub/vendor/laravel/framework/src/Illuminate/Queue/CallQueuedHandler.php(70): Illuminate\\Queue\\CallQueuedHandler->dispatchThroughMiddleware()\n#14 /home/u598407524/domains/sunoyaar.com/public_html/midnightclub/vendor/laravel/framework/src/Illuminate/Queue/Jobs/Job.php(102): Illuminate\\Queue\\CallQueuedHandler->call()\n#15 /home/u598407524/domains/sunoyaar.com/public_html/midnightclub/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(504): Illuminate\\Queue\\Jobs\\Job->fire()\n#16 /home/u598407524/domains/sunoyaar.com/public_html/midnightclub/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(454): Illuminate\\Queue\\Worker->process()\n#17 /home/u598407524/domains/sunoyaar.com/public_html/midnightclub/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(212): Illuminate\\Queue\\Worker->runJob()\n#18 /home/u598407524/domains/sunoyaar.com/public_html/midnightclub/vendor/laravel/framework/src/Illuminate/Queue/Console/WorkCommand.php(149): Illuminate\\Queue\\Worker->daemon()\n#19 /home/u598407524/domains/sunoyaar.com/public_html/midnightclub/vendor/laravel/framework/src/Illuminate/Queue/Console/WorkCommand.php(132): Illuminate\\Queue\\Console\\WorkCommand->runWorker()\n#20 /home/u598407524/domains/sunoyaar.com/public_html/midnightclub/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(36): Illuminate\\Queue\\Console\\WorkCommand->handle()\n#21 /home/u598407524/domains/sunoyaar.com/public_html/midnightclub/vendor/laravel/framework/src/Illuminate/Container/Util.php(43): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()\n#22 /home/u598407524/domains/sunoyaar.com/public_html/midnightclub/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(96): Illuminate\\Container\\Util::unwrapIfClosure()\n#23 /home/u598407524/domains/sunoyaar.com/public_html/midnightclub/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(35): Illuminate\\Container\\BoundMethod::callBoundMethod()\n#24 /home/u598407524/domains/sunoyaar.com/public_html/midnightclub/vendor/laravel/framework/src/Illuminate/Container/Container.php(799): Illuminate\\Container\\BoundMethod::call()\n#25 /home/u598407524/domains/sunoyaar.com/public_html/midnightclub/vendor/laravel/framework/src/Illuminate/Console/Command.php(211): Illuminate\\Container\\Container->call()\n#26 /home/u598407524/domains/sunoyaar.com/public_html/midnightclub/vendor/symfony/console/Command/Command.php(341): Illuminate\\Console\\Command->execute()\n#27 /home/u598407524/domains/sunoyaar.com/public_html/midnightclub/vendor/laravel/framework/src/Illuminate/Console/Command.php(180): Symfony\\Component\\Console\\Command\\Command->run()\n#28 /home/u598407524/domains/sunoyaar.com/public_html/midnightclub/vendor/symfony/console/Application.php(1117): Illuminate\\Console\\Command->run()\n#29 /home/u598407524/domains/sunoyaar.com/public_html/midnightclub/vendor/symfony/console/Application.php(356): Symfony\\Component\\Console\\Application->doRunCommand()\n#30 /home/u598407524/domains/sunoyaar.com/public_html/midnightclub/vendor/symfony/console/Application.php(195): Symfony\\Component\\Console\\Application->doRun()\n#31 /home/u598407524/domains/sunoyaar.com/public_html/midnightclub/vendor/laravel/framework/src/Illuminate/Foundation/Console/Kernel.php(198): Symfony\\Component\\Console\\Application->run()\n#32 /home/u598407524/domains/sunoyaar.com/public_html/midnightclub/vendor/laravel/framework/src/Illuminate/Foundation/Application.php(1235): Illuminate\\Foundation\\Console\\Kernel->handle()\n#33 /home/u598407524/domains/sunoyaar.com/public_html/midnightclub/artisan(16): Illuminate\\Foundation\\Application->handleCommand()\n#34 {main}', '2026-09-11 09:02:10'),
(2, 'a1a6efdc-e25b-4721-bcc7-98f26d86b140', 'database', 'default', '{\"uuid\":\"a1a6efdc-e25b-4721-bcc7-98f26d86b140\",\"displayName\":\"App\\\\Jobs\\\\SendFirebaseNotificationJob\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":3,\"maxExceptions\":3,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\SendFirebaseNotificationJob\",\"command\":\"O:36:\\\"App\\\\Jobs\\\\SendFirebaseNotificationJob\\\":5:{s:4:\\\"type\\\";s:6:\\\"tokens\\\";s:6:\\\"target\\\";s:5:\\\"admin\\\";s:5:\\\"title\\\";s:24:\\\"Request Received! 📨#2\\\";s:4:\\\"body\\\";s:68:\\\"Your reservation has been received and is currently being processed.\\\";s:4:\\\"data\\\";a:4:{s:4:\\\"type\\\";s:14:\\\"booking_status\\\";s:10:\\\"created_by\\\";N;s:6:\\\"remark\\\";s:5:\\\"admin\\\";s:10:\\\"additional\\\";a:3:{s:6:\\\"screen\\\";s:7:\\\"booking\\\";s:10:\\\"booking_id\\\";i:2;s:9:\\\"client_id\\\";i:2;}}}\",\"batchId\":null},\"createdAt\":1789207441,\"delay\":null}', 'Error: Cannot modify readonly property App\\Jobs\\SendFirebaseNotificationJob::$target in /home/u598407524/domains/sunoyaar.com/public_html/midnightclub/app/Jobs/SendFirebaseNotificationJob.php:48\nStack trace:\n#0 /home/u598407524/domains/sunoyaar.com/public_html/midnightclub/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(36): App\\Jobs\\SendFirebaseNotificationJob->handle()\n#1 /home/u598407524/domains/sunoyaar.com/public_html/midnightclub/vendor/laravel/framework/src/Illuminate/Container/Util.php(43): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()\n#2 /home/u598407524/domains/sunoyaar.com/public_html/midnightclub/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(96): Illuminate\\Container\\Util::unwrapIfClosure()\n#3 /home/u598407524/domains/sunoyaar.com/public_html/midnightclub/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(35): Illuminate\\Container\\BoundMethod::callBoundMethod()\n#4 /home/u598407524/domains/sunoyaar.com/public_html/midnightclub/vendor/laravel/framework/src/Illuminate/Container/Container.php(799): Illuminate\\Container\\BoundMethod::call()\n#5 /home/u598407524/domains/sunoyaar.com/public_html/midnightclub/vendor/laravel/framework/src/Illuminate/Bus/Dispatcher.php(129): Illuminate\\Container\\Container->call()\n#6 /home/u598407524/domains/sunoyaar.com/public_html/midnightclub/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(180): Illuminate\\Bus\\Dispatcher->Illuminate\\Bus\\{closure}()\n#7 /home/u598407524/domains/sunoyaar.com/public_html/midnightclub/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(137): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}()\n#8 /home/u598407524/domains/sunoyaar.com/public_html/midnightclub/vendor/laravel/framework/src/Illuminate/Bus/Dispatcher.php(133): Illuminate\\Pipeline\\Pipeline->then()\n#9 /home/u598407524/domains/sunoyaar.com/public_html/midnightclub/vendor/laravel/framework/src/Illuminate/Queue/CallQueuedHandler.php(136): Illuminate\\Bus\\Dispatcher->dispatchNow()\n#10 /home/u598407524/domains/sunoyaar.com/public_html/midnightclub/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(180): Illuminate\\Queue\\CallQueuedHandler->Illuminate\\Queue\\{closure}()\n#11 /home/u598407524/domains/sunoyaar.com/public_html/midnightclub/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(137): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}()\n#12 /home/u598407524/domains/sunoyaar.com/public_html/midnightclub/vendor/laravel/framework/src/Illuminate/Queue/CallQueuedHandler.php(129): Illuminate\\Pipeline\\Pipeline->then()\n#13 /home/u598407524/domains/sunoyaar.com/public_html/midnightclub/vendor/laravel/framework/src/Illuminate/Queue/CallQueuedHandler.php(70): Illuminate\\Queue\\CallQueuedHandler->dispatchThroughMiddleware()\n#14 /home/u598407524/domains/sunoyaar.com/public_html/midnightclub/vendor/laravel/framework/src/Illuminate/Queue/Jobs/Job.php(102): Illuminate\\Queue\\CallQueuedHandler->call()\n#15 /home/u598407524/domains/sunoyaar.com/public_html/midnightclub/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(504): Illuminate\\Queue\\Jobs\\Job->fire()\n#16 /home/u598407524/domains/sunoyaar.com/public_html/midnightclub/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(454): Illuminate\\Queue\\Worker->process()\n#17 /home/u598407524/domains/sunoyaar.com/public_html/midnightclub/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(212): Illuminate\\Queue\\Worker->runJob()\n#18 /home/u598407524/domains/sunoyaar.com/public_html/midnightclub/vendor/laravel/framework/src/Illuminate/Queue/Console/WorkCommand.php(149): Illuminate\\Queue\\Worker->daemon()\n#19 /home/u598407524/domains/sunoyaar.com/public_html/midnightclub/vendor/laravel/framework/src/Illuminate/Queue/Console/WorkCommand.php(132): Illuminate\\Queue\\Console\\WorkCommand->runWorker()\n#20 /home/u598407524/domains/sunoyaar.com/public_html/midnightclub/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(36): Illuminate\\Queue\\Console\\WorkCommand->handle()\n#21 /home/u598407524/domains/sunoyaar.com/public_html/midnightclub/vendor/laravel/framework/src/Illuminate/Container/Util.php(43): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()\n#22 /home/u598407524/domains/sunoyaar.com/public_html/midnightclub/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(96): Illuminate\\Container\\Util::unwrapIfClosure()\n#23 /home/u598407524/domains/sunoyaar.com/public_html/midnightclub/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(35): Illuminate\\Container\\BoundMethod::callBoundMethod()\n#24 /home/u598407524/domains/sunoyaar.com/public_html/midnightclub/vendor/laravel/framework/src/Illuminate/Container/Container.php(799): Illuminate\\Container\\BoundMethod::call()\n#25 /home/u598407524/domains/sunoyaar.com/public_html/midnightclub/vendor/laravel/framework/src/Illuminate/Console/Command.php(211): Illuminate\\Container\\Container->call()\n#26 /home/u598407524/domains/sunoyaar.com/public_html/midnightclub/vendor/symfony/console/Command/Command.php(341): Illuminate\\Console\\Command->execute()\n#27 /home/u598407524/domains/sunoyaar.com/public_html/midnightclub/vendor/laravel/framework/src/Illuminate/Console/Command.php(180): Symfony\\Component\\Console\\Command\\Command->run()\n#28 /home/u598407524/domains/sunoyaar.com/public_html/midnightclub/vendor/symfony/console/Application.php(1117): Illuminate\\Console\\Command->run()\n#29 /home/u598407524/domains/sunoyaar.com/public_html/midnightclub/vendor/symfony/console/Application.php(356): Symfony\\Component\\Console\\Application->doRunCommand()\n#30 /home/u598407524/domains/sunoyaar.com/public_html/midnightclub/vendor/symfony/console/Application.php(195): Symfony\\Component\\Console\\Application->doRun()\n#31 /home/u598407524/domains/sunoyaar.com/public_html/midnightclub/vendor/laravel/framework/src/Illuminate/Foundation/Console/Kernel.php(198): Symfony\\Component\\Console\\Application->run()\n#32 /home/u598407524/domains/sunoyaar.com/public_html/midnightclub/vendor/laravel/framework/src/Illuminate/Foundation/Application.php(1235): Illuminate\\Foundation\\Console\\Kernel->handle()\n#33 /home/u598407524/domains/sunoyaar.com/public_html/midnightclub/artisan(16): Illuminate\\Foundation\\Application->handleCommand()\n#34 {main}', '2026-09-12 10:04:06'),
(3, '43a5ebb2-aa65-4ff5-8d9b-dd35a61f0c53', 'database', 'default', '{\"uuid\":\"43a5ebb2-aa65-4ff5-8d9b-dd35a61f0c53\",\"displayName\":\"App\\\\Jobs\\\\SendFirebaseNotificationJob\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":3,\"maxExceptions\":3,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\SendFirebaseNotificationJob\",\"command\":\"O:36:\\\"App\\\\Jobs\\\\SendFirebaseNotificationJob\\\":5:{s:4:\\\"type\\\";s:6:\\\"tokens\\\";s:6:\\\"target\\\";s:5:\\\"admin\\\";s:5:\\\"title\\\";s:24:\\\"Request Received! 📨#3\\\";s:4:\\\"body\\\";s:68:\\\"Your reservation has been received and is currently being processed.\\\";s:4:\\\"data\\\";a:4:{s:4:\\\"type\\\";s:14:\\\"booking_status\\\";s:10:\\\"created_by\\\";N;s:6:\\\"remark\\\";s:5:\\\"admin\\\";s:10:\\\"additional\\\";a:3:{s:6:\\\"screen\\\";s:7:\\\"booking\\\";s:10:\\\"booking_id\\\";i:3;s:9:\\\"client_id\\\";i:1;}}}\",\"batchId\":null},\"createdAt\":1789550131,\"delay\":null}', 'Error: Cannot modify readonly property App\\Jobs\\SendFirebaseNotificationJob::$target in /home/u598407524/domains/mymidnight1515.com/public_html/app/Jobs/SendFirebaseNotificationJob.php:48\nStack trace:\n#0 /home/u598407524/domains/mymidnight1515.com/public_html/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(36): App\\Jobs\\SendFirebaseNotificationJob->handle()\n#1 /home/u598407524/domains/mymidnight1515.com/public_html/vendor/laravel/framework/src/Illuminate/Container/Util.php(43): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()\n#2 /home/u598407524/domains/mymidnight1515.com/public_html/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(96): Illuminate\\Container\\Util::unwrapIfClosure()\n#3 /home/u598407524/domains/mymidnight1515.com/public_html/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(35): Illuminate\\Container\\BoundMethod::callBoundMethod()\n#4 /home/u598407524/domains/mymidnight1515.com/public_html/vendor/laravel/framework/src/Illuminate/Container/Container.php(799): Illuminate\\Container\\BoundMethod::call()\n#5 /home/u598407524/domains/mymidnight1515.com/public_html/vendor/laravel/framework/src/Illuminate/Bus/Dispatcher.php(129): Illuminate\\Container\\Container->call()\n#6 /home/u598407524/domains/mymidnight1515.com/public_html/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(180): Illuminate\\Bus\\Dispatcher->Illuminate\\Bus\\{closure}()\n#7 /home/u598407524/domains/mymidnight1515.com/public_html/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(137): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}()\n#8 /home/u598407524/domains/mymidnight1515.com/public_html/vendor/laravel/framework/src/Illuminate/Bus/Dispatcher.php(133): Illuminate\\Pipeline\\Pipeline->then()\n#9 /home/u598407524/domains/mymidnight1515.com/public_html/vendor/laravel/framework/src/Illuminate/Queue/CallQueuedHandler.php(136): Illuminate\\Bus\\Dispatcher->dispatchNow()\n#10 /home/u598407524/domains/mymidnight1515.com/public_html/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(180): Illuminate\\Queue\\CallQueuedHandler->Illuminate\\Queue\\{closure}()\n#11 /home/u598407524/domains/mymidnight1515.com/public_html/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(137): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}()\n#12 /home/u598407524/domains/mymidnight1515.com/public_html/vendor/laravel/framework/src/Illuminate/Queue/CallQueuedHandler.php(129): Illuminate\\Pipeline\\Pipeline->then()\n#13 /home/u598407524/domains/mymidnight1515.com/public_html/vendor/laravel/framework/src/Illuminate/Queue/CallQueuedHandler.php(70): Illuminate\\Queue\\CallQueuedHandler->dispatchThroughMiddleware()\n#14 /home/u598407524/domains/mymidnight1515.com/public_html/vendor/laravel/framework/src/Illuminate/Queue/Jobs/Job.php(102): Illuminate\\Queue\\CallQueuedHandler->call()\n#15 /home/u598407524/domains/mymidnight1515.com/public_html/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(504): Illuminate\\Queue\\Jobs\\Job->fire()\n#16 /home/u598407524/domains/mymidnight1515.com/public_html/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(454): Illuminate\\Queue\\Worker->process()\n#17 /home/u598407524/domains/mymidnight1515.com/public_html/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(212): Illuminate\\Queue\\Worker->runJob()\n#18 /home/u598407524/domains/mymidnight1515.com/public_html/vendor/laravel/framework/src/Illuminate/Queue/Console/WorkCommand.php(149): Illuminate\\Queue\\Worker->daemon()\n#19 /home/u598407524/domains/mymidnight1515.com/public_html/vendor/laravel/framework/src/Illuminate/Queue/Console/WorkCommand.php(132): Illuminate\\Queue\\Console\\WorkCommand->runWorker()\n#20 /home/u598407524/domains/mymidnight1515.com/public_html/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(36): Illuminate\\Queue\\Console\\WorkCommand->handle()\n#21 /home/u598407524/domains/mymidnight1515.com/public_html/vendor/laravel/framework/src/Illuminate/Container/Util.php(43): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()\n#22 /home/u598407524/domains/mymidnight1515.com/public_html/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(96): Illuminate\\Container\\Util::unwrapIfClosure()\n#23 /home/u598407524/domains/mymidnight1515.com/public_html/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(35): Illuminate\\Container\\BoundMethod::callBoundMethod()\n#24 /home/u598407524/domains/mymidnight1515.com/public_html/vendor/laravel/framework/src/Illuminate/Container/Container.php(799): Illuminate\\Container\\BoundMethod::call()\n#25 /home/u598407524/domains/mymidnight1515.com/public_html/vendor/laravel/framework/src/Illuminate/Console/Command.php(211): Illuminate\\Container\\Container->call()\n#26 /home/u598407524/domains/mymidnight1515.com/public_html/vendor/symfony/console/Command/Command.php(341): Illuminate\\Console\\Command->execute()\n#27 /home/u598407524/domains/mymidnight1515.com/public_html/vendor/laravel/framework/src/Illuminate/Console/Command.php(180): Symfony\\Component\\Console\\Command\\Command->run()\n#28 /home/u598407524/domains/mymidnight1515.com/public_html/vendor/symfony/console/Application.php(1117): Illuminate\\Console\\Command->run()\n#29 /home/u598407524/domains/mymidnight1515.com/public_html/vendor/symfony/console/Application.php(356): Symfony\\Component\\Console\\Application->doRunCommand()\n#30 /home/u598407524/domains/mymidnight1515.com/public_html/vendor/symfony/console/Application.php(195): Symfony\\Component\\Console\\Application->doRun()\n#31 /home/u598407524/domains/mymidnight1515.com/public_html/vendor/laravel/framework/src/Illuminate/Foundation/Console/Kernel.php(198): Symfony\\Component\\Console\\Application->run()\n#32 /home/u598407524/domains/mymidnight1515.com/public_html/vendor/laravel/framework/src/Illuminate/Foundation/Application.php(1235): Illuminate\\Foundation\\Console\\Kernel->handle()\n#33 /home/u598407524/domains/mymidnight1515.com/public_html/artisan(16): Illuminate\\Foundation\\Application->handleCommand()\n#34 {main}', '2026-09-16 09:16:07'),
(4, '6404cfb9-83e8-488e-9ba8-7bb6c11880c5', 'database', 'default', '{\"uuid\":\"6404cfb9-83e8-488e-9ba8-7bb6c11880c5\",\"displayName\":\"App\\\\Jobs\\\\SendFirebaseNotificationJob\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":3,\"maxExceptions\":3,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\SendFirebaseNotificationJob\",\"command\":\"O:36:\\\"App\\\\Jobs\\\\SendFirebaseNotificationJob\\\":5:{s:4:\\\"type\\\";s:6:\\\"tokens\\\";s:6:\\\"target\\\";s:5:\\\"admin\\\";s:5:\\\"title\\\";s:24:\\\"Request Received! 📨#4\\\";s:4:\\\"body\\\";s:68:\\\"Your reservation has been received and is currently being processed.\\\";s:4:\\\"data\\\";a:4:{s:4:\\\"type\\\";s:14:\\\"booking_status\\\";s:10:\\\"created_by\\\";N;s:6:\\\"remark\\\";s:5:\\\"admin\\\";s:10:\\\"additional\\\";a:3:{s:6:\\\"screen\\\";s:7:\\\"booking\\\";s:10:\\\"booking_id\\\";i:4;s:9:\\\"client_id\\\";i:1;}}}\",\"batchId\":null},\"createdAt\":1789558350,\"delay\":null}', 'Error: Cannot modify readonly property App\\Jobs\\SendFirebaseNotificationJob::$target in /home/u598407524/domains/mymidnight1515.com/public_html/app/Jobs/SendFirebaseNotificationJob.php:48\nStack trace:\n#0 /home/u598407524/domains/mymidnight1515.com/public_html/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(36): App\\Jobs\\SendFirebaseNotificationJob->handle()\n#1 /home/u598407524/domains/mymidnight1515.com/public_html/vendor/laravel/framework/src/Illuminate/Container/Util.php(43): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()\n#2 /home/u598407524/domains/mymidnight1515.com/public_html/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(96): Illuminate\\Container\\Util::unwrapIfClosure()\n#3 /home/u598407524/domains/mymidnight1515.com/public_html/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(35): Illuminate\\Container\\BoundMethod::callBoundMethod()\n#4 /home/u598407524/domains/mymidnight1515.com/public_html/vendor/laravel/framework/src/Illuminate/Container/Container.php(799): Illuminate\\Container\\BoundMethod::call()\n#5 /home/u598407524/domains/mymidnight1515.com/public_html/vendor/laravel/framework/src/Illuminate/Bus/Dispatcher.php(129): Illuminate\\Container\\Container->call()\n#6 /home/u598407524/domains/mymidnight1515.com/public_html/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(180): Illuminate\\Bus\\Dispatcher->Illuminate\\Bus\\{closure}()\n#7 /home/u598407524/domains/mymidnight1515.com/public_html/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(137): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}()\n#8 /home/u598407524/domains/mymidnight1515.com/public_html/vendor/laravel/framework/src/Illuminate/Bus/Dispatcher.php(133): Illuminate\\Pipeline\\Pipeline->then()\n#9 /home/u598407524/domains/mymidnight1515.com/public_html/vendor/laravel/framework/src/Illuminate/Queue/CallQueuedHandler.php(136): Illuminate\\Bus\\Dispatcher->dispatchNow()\n#10 /home/u598407524/domains/mymidnight1515.com/public_html/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(180): Illuminate\\Queue\\CallQueuedHandler->Illuminate\\Queue\\{closure}()\n#11 /home/u598407524/domains/mymidnight1515.com/public_html/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(137): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}()\n#12 /home/u598407524/domains/mymidnight1515.com/public_html/vendor/laravel/framework/src/Illuminate/Queue/CallQueuedHandler.php(129): Illuminate\\Pipeline\\Pipeline->then()\n#13 /home/u598407524/domains/mymidnight1515.com/public_html/vendor/laravel/framework/src/Illuminate/Queue/CallQueuedHandler.php(70): Illuminate\\Queue\\CallQueuedHandler->dispatchThroughMiddleware()\n#14 /home/u598407524/domains/mymidnight1515.com/public_html/vendor/laravel/framework/src/Illuminate/Queue/Jobs/Job.php(102): Illuminate\\Queue\\CallQueuedHandler->call()\n#15 /home/u598407524/domains/mymidnight1515.com/public_html/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(504): Illuminate\\Queue\\Jobs\\Job->fire()\n#16 /home/u598407524/domains/mymidnight1515.com/public_html/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(454): Illuminate\\Queue\\Worker->process()\n#17 /home/u598407524/domains/mymidnight1515.com/public_html/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(212): Illuminate\\Queue\\Worker->runJob()\n#18 /home/u598407524/domains/mymidnight1515.com/public_html/vendor/laravel/framework/src/Illuminate/Queue/Console/WorkCommand.php(149): Illuminate\\Queue\\Worker->daemon()\n#19 /home/u598407524/domains/mymidnight1515.com/public_html/vendor/laravel/framework/src/Illuminate/Queue/Console/WorkCommand.php(132): Illuminate\\Queue\\Console\\WorkCommand->runWorker()\n#20 /home/u598407524/domains/mymidnight1515.com/public_html/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(36): Illuminate\\Queue\\Console\\WorkCommand->handle()\n#21 /home/u598407524/domains/mymidnight1515.com/public_html/vendor/laravel/framework/src/Illuminate/Container/Util.php(43): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()\n#22 /home/u598407524/domains/mymidnight1515.com/public_html/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(96): Illuminate\\Container\\Util::unwrapIfClosure()\n#23 /home/u598407524/domains/mymidnight1515.com/public_html/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(35): Illuminate\\Container\\BoundMethod::callBoundMethod()\n#24 /home/u598407524/domains/mymidnight1515.com/public_html/vendor/laravel/framework/src/Illuminate/Container/Container.php(799): Illuminate\\Container\\BoundMethod::call()\n#25 /home/u598407524/domains/mymidnight1515.com/public_html/vendor/laravel/framework/src/Illuminate/Console/Command.php(211): Illuminate\\Container\\Container->call()\n#26 /home/u598407524/domains/mymidnight1515.com/public_html/vendor/symfony/console/Command/Command.php(341): Illuminate\\Console\\Command->execute()\n#27 /home/u598407524/domains/mymidnight1515.com/public_html/vendor/laravel/framework/src/Illuminate/Console/Command.php(180): Symfony\\Component\\Console\\Command\\Command->run()\n#28 /home/u598407524/domains/mymidnight1515.com/public_html/vendor/symfony/console/Application.php(1117): Illuminate\\Console\\Command->run()\n#29 /home/u598407524/domains/mymidnight1515.com/public_html/vendor/symfony/console/Application.php(356): Symfony\\Component\\Console\\Application->doRunCommand()\n#30 /home/u598407524/domains/mymidnight1515.com/public_html/vendor/symfony/console/Application.php(195): Symfony\\Component\\Console\\Application->doRun()\n#31 /home/u598407524/domains/mymidnight1515.com/public_html/vendor/laravel/framework/src/Illuminate/Foundation/Console/Kernel.php(198): Symfony\\Component\\Console\\Application->run()\n#32 /home/u598407524/domains/mymidnight1515.com/public_html/vendor/laravel/framework/src/Illuminate/Foundation/Application.php(1235): Illuminate\\Foundation\\Console\\Kernel->handle()\n#33 /home/u598407524/domains/mymidnight1515.com/public_html/artisan(16): Illuminate\\Foundation\\Application->handleCommand()\n#34 {main}', '2026-09-16 11:33:10');

-- --------------------------------------------------------

--
-- Table structure for table `feature_requests`
--

CREATE TABLE `feature_requests` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `client_id` bigint(20) UNSIGNED DEFAULT NULL,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'pending',
  `priority` varchar(255) NOT NULL DEFAULT 'medium',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `floors`
--

CREATE TABLE `floors` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `branch_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `level` int(11) NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `flyers`
--

CREATE TABLE `flyers` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `file_type` varchar(255) NOT NULL DEFAULT 'image',
  `file_path` varchar(255) NOT NULL,
  `audio_path` varchar(255) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `flyers`
--

INSERT INTO `flyers` (`id`, `title`, `description`, `file_type`, `file_path`, `audio_path`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'Hip Hop Wednesday', 'Midweek just got a major upgrade at Midnight Club! Join us this Wednesday night for an explosive session featuring DJ DC live on the decks. Expect a high-octane blend of heavy-hitting beats, deep house classics, and chart-topping remixes designed to keep the dance floor moving all night long.\r\n\r\nBreak up your routine, sip on handcrafted drinks, and immerse yourself in an incredible atmosphere with world-class light shows and booming sound.', 'image', 'flyers/files/mXLwkqr2LROR3VOHzY7L0aOCLZjhEkXycxCkB6rM.jpg', 'flyers/audios/hNbh7BoJJWn8qQ2ops3Q6anyi77RlKqd4IFM5WZY.mp3', 1, '2026-08-09 01:45:23', '2026-08-19 14:04:09'),
(2, 'Tught Thursday', 'Get ready to elevate your night at Midnight Club this Thursday! We are turning up the energy with DJ DC taking over the decks to drop the ultimate mix of electrifying beats, house anthems, and high-energy tracks that will keep you dancing until dawn.\r\n\r\nGather your crew, head to the floor, and experience an unforgettable atmosphere powered by deep bass, stunning lighting, and signature cocktails. The weekend starts early right here.', 'image', 'flyers/files/iMQXLtKU9KW8cfL6QwcyE6Ju3eBHFtSp4IHLWX26.jpg', 'flyers/audios/Ch8Jl9K6wIK7C2ZsANG1efuiDJdqSIlYlMx7sBzY.mp3', 1, '2026-08-09 01:48:47', '2026-08-19 14:04:09'),
(3, 'Fun Friday', 'Enjoy the Best Party For Fun Friday with Best DJ DC', 'image', 'flyers/files/6y0ee9PVOiScTKGB4ffR9ekEe1YiaBjU9LTEuw4i.jpg', 'flyers/audios/WzoAjyI38EixfvJM9wz9JxC5PyKrVVPzFZ8lcYeZ.mp3', 1, '2026-08-09 01:51:27', '2026-08-19 14:04:09'),
(4, 'Sexy Saturday', 'Enjoy the party with best DJ  DC in Gururam.', 'image', 'flyers/files/tLP2PDfte22KIANIzxf9T8TLoNf5Idg0QHamndLU.jpg', 'flyers/audios/tz8PxFFEunyAOffA0rBs20fSCOfAxDVXxWbawNmV.mp3', 1, '2026-08-17 14:00:17', '2026-08-19 14:04:09');

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) UNSIGNED NOT NULL,
  `reserved_at` int(10) UNSIGNED DEFAULT NULL,
  `available_at` int(10) UNSIGNED NOT NULL,
  `created_at` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext DEFAULT NULL,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2024_05_10_152848_create_saved_filters_table', 1),
(5, '2026_01_01_000003_create_mobile_app_roles_table', 1),
(6, '2026_05_05_051655_create_personal_access_tokens_table', 1),
(7, '2026_05_06_044345_create_roles_table', 1),
(8, '2026_07_16_000001_create_clients_table', 1),
(9, '2026_07_16_000002_create_settings_table', 1),
(10, '2026_07_16_000004_create_clubs_table', 1),
(11, '2026_07_16_000005_create_branches_table', 1),
(12, '2026_07_16_000006_create_floors_table', 1),
(13, '2026_07_16_000007_create_tables_table', 1),
(14, '2026_07_16_000008_create_events_table', 1),
(15, '2026_07_16_000009_create_bookings_table', 1),
(16, '2026_07_16_000011_create_payments_table', 1),
(17, '2026_07_16_000012_create_transactions_table', 1),
(18, '2026_07_16_000013_create_client_balances_table', 1),
(19, '2026_07_16_000013_create_notifications_table', 1),
(20, '2026_07_16_000014_create_client_ledgers_table', 1),
(21, '2026_07_16_000015_create_reviews_table', 1),
(22, '2026_07_16_000016_create_audit_logs_table', 1),
(23, '2026_07_22_123054_create_feature_requests_table', 1),
(24, '2026_07_23_000015_create_complaints_table', 1),
(25, '2026_07_24_000010_create_client_guests_table', 1),
(26, '2026_07_25_000010_create_booking_guests_table', 1),
(27, '2026_07_26_000003_create_promo_codes_table', 1),
(28, '2026_08_02_100000_create_dashboard_stored_procedures', 1),
(29, '2026_08_06_000001_create_club_assets_table', 1),
(30, '2026_08_09_000000_create_flyers_table', 1),
(31, '2026_08_16_092046_create_club_staff_table', 1),
(32, '2026_08_16_125734_update_dashboard_stored_procedures_v2', 1);

-- --------------------------------------------------------

--
-- Table structure for table `mobile_app_roles`
--

CREATE TABLE `mobile_app_roles` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `type` varchar(255) NOT NULL,
  `permissions` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`permissions`)),
  `route_permissions` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`route_permissions`)),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `mobile_app_roles`
--

INSERT INTO `mobile_app_roles` (`id`, `name`, `description`, `type`, `permissions`, `route_permissions`, `created_at`, `updated_at`) VALUES
(1, 'Administrator', NULL, 'system', '[\"*\"]', NULL, '2026-09-11 08:27:06', '2026-09-11 08:27:06'),
(2, 'Staff', NULL, 'custom', '[\"can_qr_scan\",\"can_booking_check_in\"]', NULL, '2026-09-11 08:27:06', '2026-09-11 08:27:06');

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `client_id` bigint(20) UNSIGNED DEFAULT NULL,
  `title` varchar(255) NOT NULL,
  `body` text NOT NULL,
  `created_by` int(10) UNSIGNED DEFAULT NULL,
  `remark` varchar(255) DEFAULT NULL,
  `type` varchar(255) DEFAULT NULL,
  `additional` text NOT NULL DEFAULT '{}',
  `read_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `notifications`
--

INSERT INTO `notifications` (`id`, `client_id`, `title`, `body`, `created_by`, `remark`, `type`, `additional`, `read_at`, `created_at`, `updated_at`) VALUES
(1, 1, 'You\'re Confirmed! 🎉#1', 'Your reservation is successfully confirmed.', 1, 'admin', 'booking_status', '{\"screen\":\"booking\",\"booking_id\":1,\"client_id\":1}', '2026-09-11 09:05:00', '2026-09-11 09:04:05', '2026-09-11 09:05:00'),
(2, 1, 'Welcome In! ✨#1', 'Check-in is complete. Thank you for choosing our services.', 1, 'admin', 'booking_status', '{\"screen\":\"booking\",\"booking_id\":1,\"client_id\":1}', '2026-09-14 14:03:45', '2026-09-11 09:05:06', '2026-09-14 14:03:45'),
(3, 1, 'Booking Cancelled 😔#1', 'Your reservation has been cancelled as requested.', 1, 'admin', 'booking_status', '{\"screen\":\"booking\",\"booking_id\":1,\"client_id\":1}', '2026-09-14 14:03:45', '2026-09-11 09:11:05', '2026-09-14 14:03:45'),
(4, 1, 'Welcome In! ✨#1', 'Check-in is complete. Thank you for choosing our services.', 1, 'admin', 'booking_status', '{\"screen\":\"booking\",\"booking_id\":1,\"client_id\":1}', '2026-09-16 20:17:16', '2026-09-15 18:03:10', '2026-09-16 20:17:16');

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

CREATE TABLE `payments` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `client_id` bigint(20) UNSIGNED DEFAULT NULL,
  `booking_id` bigint(20) UNSIGNED DEFAULT NULL,
  `amount` decimal(10,2) NOT NULL,
  `payment_type` varchar(255) DEFAULT NULL,
  `payment_method` varchar(255) NOT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'pending',
  `transaction_reference` varchar(255) DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `recorded_by` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) NOT NULL,
  `tokenable_id` bigint(20) UNSIGNED NOT NULL,
  `name` text NOT NULL,
  `token` varchar(64) NOT NULL,
  `abilities` text DEFAULT NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `personal_access_tokens`
--

INSERT INTO `personal_access_tokens` (`id`, `tokenable_type`, `tokenable_id`, `name`, `token`, `abilities`, `last_used_at`, `expires_at`, `created_at`, `updated_at`) VALUES
(2, 'App\\Model\\Client', 2, 'auth_token', '4af3be56f5097532de9005937d5a2ab34f092dfa426db1378c376fe3a87da3d2', '[\"*\"]', '2026-09-18 01:47:30', NULL, '2026-09-12 10:03:09', '2026-09-18 01:47:30'),
(13, 'App\\Model\\Client', 4, 'auth_token', '9e89b6e1fdc7fde2194e6f1000e7168f1dce3144c4de7353949acd7e8f198ecc', '[\"*\"]', '2026-09-16 11:22:09', NULL, '2026-09-16 11:21:36', '2026-09-16 11:22:09'),
(14, 'App\\Model\\Client', 3, 'auth_token', '85ad3c38782828ea10b12ca54dd1d750dd1a93f1eb027f0d8f3238a8af1e3a4c', '[\"*\"]', '2026-09-16 11:24:40', NULL, '2026-09-16 11:24:39', '2026-09-16 11:24:40'),
(23, 'App\\Model\\Client', 1, 'auth_token', '0b28c1e9cc9639e52345fb664b2b3a32adef02668e5050f9a2cab43c1fe30446', '[\"*\"]', '2026-09-19 05:18:17', NULL, '2026-09-19 05:07:03', '2026-09-19 05:18:17');

-- --------------------------------------------------------

--
-- Table structure for table `promo_codes`
--

CREATE TABLE `promo_codes` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `event_id` bigint(20) UNSIGNED DEFAULT NULL,
  `code` varchar(255) NOT NULL,
  `label` varchar(200) DEFAULT NULL,
  `description` varchar(500) DEFAULT NULL,
  `visibility` varchar(255) NOT NULL DEFAULT 'public',
  `type` varchar(255) NOT NULL,
  `value` decimal(10,2) NOT NULL,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `min_spend` decimal(10,2) NOT NULL DEFAULT 0.00,
  `max_discount` decimal(10,2) NOT NULL DEFAULT 0.00,
  `usage_limit` int(11) DEFAULT NULL,
  `used_count` int(11) NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `reviews`
--

CREATE TABLE `reviews` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `client_id` bigint(20) UNSIGNED NOT NULL,
  `club_id` bigint(20) UNSIGNED DEFAULT NULL,
  `booking_id` bigint(20) UNSIGNED DEFAULT NULL,
  `rating` int(11) NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `is_anonymous` tinyint(1) NOT NULL DEFAULT 0,
  `comment` varchar(2000) DEFAULT NULL,
  `remark` varchar(1000) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `type` varchar(255) NOT NULL,
  `permissions` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`permissions`)),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `name`, `description`, `type`, `permissions`, `created_at`, `updated_at`) VALUES
(1, 'Administrator', NULL, 'system', '[\"*\"]', '2026-09-11 08:27:32', '2026-09-11 08:27:32');

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('7JYjMrrFBv05mgzV1AGxcUpPGJbf8z57Bf9znrje', NULL, '2a02:4780:11:c0de::e', 'Go-http-client/2.0', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoibGttMkxON2UxdXNpc1FUdmtFMG5xcXFYdWp5Tk5lZXRoNkJyVGRUZSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mzk6Imh0dHBzOi8vYmVzdGNsdWJuaWdodHBhcnR5aW5ndXJ1cmFtLmNvbSI7czo1OiJyb3V0ZSI7czoyNzoiZ2VuZXJhdGVkOjo0Q1l1V2dVUDlHeG9nSWpuIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1789779483),
('CKvuvGLXlV6x7inPpapl06z96YmbCi44BFDXSzFZ', NULL, '2402:3a80:4687:7109:b154:d59c:1f3e:efb6', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoicDFIQ3NCekx3QlhMUjdic2V3ZklncG1nOTBKaEtyM3h6TGUwUjRYUCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NTQ6Imh0dHBzOi8vYmVzdGNsdWJuaWdodHBhcnR5aW5ndXJ1cmFtLmNvbS9hZG1pbi9wYXltZW50cyI7czo1OiJyb3V0ZSI7czoyMDoiYWRtaW4ucGF5bWVudHMuaW5kZXgiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX1zOjUyOiJsb2dpbl9hZG1pbl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtpOjE7fQ==', 1789795122),
('HODNk6EdiXF7mDAUqeBpuFBMrdqHUyNZadBRjc5y', NULL, '205.169.39.68', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/79.0.3945.79 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiRjFUNU02enNWeUlOU2NMT29PTHpJcXpwajBCcDhOVjg0OTlVMlJYdyI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MjY6Imh0dHBzOi8vbXltaWRuaWdodDE1MTUuY29tIjtzOjU6InJvdXRlIjtzOjI3OiJnZW5lcmF0ZWQ6OjRDWXVXZ1VQOUd4b2dJam4iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1789784378),
('KqDAb4i32zm7mq0fdS247ihGyMQRfdZWCkD1dEPd', NULL, '172.71.147.58', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/125.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoieVZOSVB3cDJtaUltelZXQXYwd2hUUnJZM0xyYURIS0pObXNPS2J6NSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mzk6Imh0dHBzOi8vYmVzdGNsdWJuaWdodHBhcnR5aW5ndXJ1cmFtLmNvbSI7czo1OiJyb3V0ZSI7czoyNzoiZ2VuZXJhdGVkOjo0Q1l1V2dVUDlHeG9nSWpuIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1789789713),
('lA4ssYftkwByGSw4zJm3Hh8FP3QKOQTjG3uW3PSh', NULL, '44.212.54.151', 'axios/1.16.1', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiSlZUdDlXNThvNERNMFllcXFmSTZHNWxWSTBPUldzZDhGQ0lYV0RFRSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mzk6Imh0dHBzOi8vYmVzdGNsdWJuaWdodHBhcnR5aW5ndXJ1cmFtLmNvbSI7czo1OiJyb3V0ZSI7czoyNzoiZ2VuZXJhdGVkOjo0Q1l1V2dVUDlHeG9nSWpuIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1789787011),
('lQ0sQPLQkut3NBdkSdtVj47N8ZmD3PVLagMnyR8H', NULL, '44.212.54.151', 'axios/1.16.1', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiUXczMFp6ZEZaZk91YWk4RVhpbUZJeXhMc2dRajU0cklBZUp6ck9ZeCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mzk6Imh0dHBzOi8vYmVzdGNsdWJuaWdodHBhcnR5aW5ndXJ1cmFtLmNvbSI7czo1OiJyb3V0ZSI7czoyNzoiZ2VuZXJhdGVkOjo0Q1l1V2dVUDlHeG9nSWpuIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1789787009),
('m6lUhREs0vkqBkWR0CF977QrcZkuaX8mfy2oqNNA', NULL, '205.169.39.68', 'Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/83.0.4103.61 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoidHJCSndzQ1ZFTTdyZ0pYNzZrVllGNHB0NFE3YzZjdnNta1hHQkxwMSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MjY6Imh0dHBzOi8vbXltaWRuaWdodDE1MTUuY29tIjtzOjU6InJvdXRlIjtzOjI3OiJnZW5lcmF0ZWQ6OjRDWXVXZ1VQOUd4b2dJam4iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1789784375),
('muHwxFaPj67oGZFDLZ7S5xgokhLSt2mvmbqzwcPL', NULL, '18.212.62.160', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7_8 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.0 Mobile/15E148 Safari/604.1', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiZmFIMG9qRnBoaUpEODk0Z1dsUEhxNDhWR1B0T3VVaElaWEx0UVZ0SSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mzk6Imh0dHBzOi8vYmVzdGNsdWJuaWdodHBhcnR5aW5ndXJ1cmFtLmNvbSI7czo1OiJyb3V0ZSI7czoyNzoiZ2VuZXJhdGVkOjo0Q1l1V2dVUDlHeG9nSWpuIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1789785752),
('nJmPjjDV3vbyGVJgxdJXKNcyzep7oob1JtFsi9OW', NULL, '198.145.60.84', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiV0pWaFBWUWRrVGxuc0l3bTlnV0tFYjI2bGxwS1Y3b0ttaGQ0bHg2QSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mzk6Imh0dHBzOi8vYmVzdGNsdWJuaWdodHBhcnR5aW5ndXJ1cmFtLmNvbSI7czo1OiJyb3V0ZSI7czoyNzoiZ2VuZXJhdGVkOjo0Q1l1V2dVUDlHeG9nSWpuIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1789790474),
('PizR0Ap0FSst6iNzjhRdIcJdD4J5OgmyPsZincBY', NULL, '52.89.112.45', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:109.0) Gecko/20100101 Firefox/109.0', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiTzBCNnVSb0NNUjFYUG5wcG1vTnl0UDFIazg5NUw3bEpoWnVYS1JIQSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mzk6Imh0dHBzOi8vYmVzdGNsdWJuaWdodHBhcnR5aW5ndXJ1cmFtLmNvbSI7czo1OiJyb3V0ZSI7czoyNzoiZ2VuZXJhdGVkOjo0Q1l1V2dVUDlHeG9nSWpuIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1789777889),
('VIy69nU7F0DoCkyRh4eaoVmPpZvYXLciPXE6bOd6', NULL, '44.212.54.151', 'axios/1.16.1', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiRVdxalFpUGZ1MG1uN1FNTk5WU0d2Ujl5ZktIT1ZyelFxVVNSUGh2NSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mzk6Imh0dHBzOi8vYmVzdGNsdWJuaWdodHBhcnR5aW5ndXJ1cmFtLmNvbSI7czo1OiJyb3V0ZSI7czoyNzoiZ2VuZXJhdGVkOjo0Q1l1V2dVUDlHeG9nSWpuIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1789787010),
('wjlCAwc0tDRXMTzUcLEoOgqwsNda2wR3ly4uYcME', NULL, '35.225.82.182', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/102.0.5005.197 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiMzBLZzVhN1d2cGdRTVNVcE53Nm5hRTRLYjlTSzRPQkRPRnB6cHhZSCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mzk6Imh0dHBzOi8vYmVzdGNsdWJuaWdodHBhcnR5aW5ndXJ1cmFtLmNvbSI7czo1OiJyb3V0ZSI7czoyNzoiZ2VuZXJhdGVkOjo0Q1l1V2dVUDlHeG9nSWpuIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1789786194),
('wOVRMPD8uPTRtiKbbQjIW1zpJyECN0BvOO38yMyj', NULL, '198.145.60.84', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiVlMyR2p5QjlhMk9Ya3VacndGMTdkdW1FSElvdW1kdzdoSDdYMW9vTyI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mzk6Imh0dHBzOi8vYmVzdGNsdWJuaWdodHBhcnR5aW5ndXJ1cmFtLmNvbSI7czo1OiJyb3V0ZSI7czoyNzoiZ2VuZXJhdGVkOjo0Q1l1V2dVUDlHeG9nSWpuIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1789790480),
('ZBDeXIoR3lK9i8HeEIFrH8PDwQu7DQOAKAT414F6', NULL, '194.14.29.2', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:123.0) Gecko/20100101 Firefox/123', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiTG5ZSGhYcTNFMUVEZTV4c25GRmpoU004Z2dZakRyWHRpUTY4WWhmeiI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NDM6Imh0dHBzOi8vd3d3LmJlc3RjbHVibmlnaHRwYXJ0eWluZ3VydXJhbS5jb20iO3M6NToicm91dGUiO3M6Mjc6ImdlbmVyYXRlZDo6NENZdVdnVVA5R3hvZ0lqbiI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1789785070);

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE `settings` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `key` varchar(255) NOT NULL,
  `value` text DEFAULT NULL,
  `type` varchar(255) NOT NULL DEFAULT 'string',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tables`
--

CREATE TABLE `tables` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `club_id` bigint(20) UNSIGNED NOT NULL,
  `total_tables` int(11) NOT NULL DEFAULT 0,
  `name` varchar(255) NOT NULL,
  `label` varchar(255) NOT NULL,
  `type` varchar(255) DEFAULT NULL,
  `price` decimal(10,2) NOT NULL DEFAULT 0.00,
  `cover_charge` decimal(10,2) NOT NULL DEFAULT 0.00,
  `late_cover_charge` decimal(10,2) NOT NULL DEFAULT 0.00,
  `capacity` int(11) NOT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'active',
  `image` varchar(255) DEFAULT NULL,
  `disclaimer` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tables`
--

INSERT INTO `tables` (`id`, `club_id`, `total_tables`, `name`, `label`, `type`, `price`, `cover_charge`, `late_cover_charge`, `capacity`, `status`, `image`, `disclaimer`, `created_at`, `updated_at`) VALUES
(1, 1, 3, 'VIP table', '40k+ above spend', 'vip', 40000.00, 500.00, 1000.00, 6, 'active', 'tables/a3da41A5j9VK0VNJu0kKjH5uzxAcXmjMDI3jHMcj.jpg', 'Entry fee is 500/person but if you reach late then 1000/person will apply but adjust with your final bill.', '2026-07-20 14:02:06', '2026-08-19 14:11:47'),
(4, 1, 6, 'Standing Table', '20k+ above spend', 'standing', 20000.00, 500.00, 1000.00, 5, 'active', 'tables/8OLjKhS1PJoxw6uibLNN8TnAZYKEgvLoY4vpWNpX.webp', 'Entry fee is 500/person but if you reach late then 1000/person will apply but adjust with your final bill.', '2026-07-21 17:59:21', '2026-08-19 14:11:52'),
(5, 1, 4, 'Normal Table', '60k+ above spend', 'normal', 60000.00, 500.00, 1000.00, 5, 'active', 'tables/v1riUt2uWUzKs1yJ0Z74ECrnxaelc2vzqNwbhUeC.png', 'Entry fee is 500/person but if you reach late then 1000/person will apply but adjust with your final bill.', '2026-07-21 18:21:05', '2026-08-19 14:11:58'),
(6, 1, 10, 'Personalized Table', '50K+ above spend', NULL, 50000.00, 500.00, 600.00, 5, 'active', 'tables/zRD5Rc2veyiRneMElTtuaZNewpSehxUm99LRJI4e.webp', 'Entry fee is 500/person but if you reach late then 1000/person will apply but adjust with your final bill.', '2026-08-10 04:12:00', '2026-08-19 14:12:04');

-- --------------------------------------------------------

--
-- Table structure for table `transactions`
--

CREATE TABLE `transactions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `payment_id` bigint(20) UNSIGNED DEFAULT NULL,
  `booking_id` bigint(20) UNSIGNED DEFAULT NULL,
  `amount` decimal(10,2) NOT NULL,
  `type` varchar(255) NOT NULL,
  `payment_method` varchar(255) DEFAULT NULL,
  `status` varchar(255) NOT NULL,
  `reference` varchar(255) DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `recorded_by` bigint(20) UNSIGNED DEFAULT NULL,
  `response_payload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`response_payload`)),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `avatar` varchar(255) DEFAULT NULL,
  `role_id` varchar(255) DEFAULT NULL,
  `user_type` varchar(255) NOT NULL DEFAULT 'admin',
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `last_login_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `phone`, `avatar`, `role_id`, `user_type`, `is_active`, `last_login_at`, `deleted_at`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Super Admin', 'admin@admin.com', '9876543210', 'avatars/t7XIBTWMrYnWkOAoVyfIwZgHzdwgrUoe0X5S2HP4.png', '1', 'admin', 0, NULL, NULL, NULL, '$2y$12$k0.MtzbeHI1aymt.aKcXaefU3qRWuTVhN5CK4.6jsLmBG9RJM8NEW', 'BzZ95iqacfIyjnGHV5qFqQshnrvx03sTcvIsN7lt0Yk3LTHqNrkPcjuCoUlU', '2026-09-11 08:26:02', '2026-09-19 04:58:11');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `audit_logs`
--
ALTER TABLE `audit_logs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `bookings`
--
ALTER TABLE `bookings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `bookings_qr_code_unique` (`qr_code`),
  ADD KEY `bookings_client_id_foreign` (`client_id`),
  ADD KEY `bookings_table_id_foreign` (`table_id`),
  ADD KEY `bookings_event_id_foreign` (`event_id`),
  ADD KEY `bookings_booking_date_idx` (`booking_date`),
  ADD KEY `bookings_status_idx` (`status`),
  ADD KEY `bookings_created_at_idx` (`created_at`),
  ADD KEY `bookings_club_id_idx` (`club_id`);

--
-- Indexes for table `booking_guests`
--
ALTER TABLE `booking_guests`
  ADD PRIMARY KEY (`id`),
  ADD KEY `booking_guests_booking_id_foreign` (`booking_id`),
  ADD KEY `booking_guests_client_id_foreign` (`client_id`),
  ADD KEY `booking_guests_guest_id_foreign` (`guest_id`);

--
-- Indexes for table `branches`
--
ALTER TABLE `branches`
  ADD PRIMARY KEY (`id`),
  ADD KEY `branches_club_id_foreign` (`club_id`);

--
-- Indexes for table `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_expiration_index` (`expiration`);

--
-- Indexes for table `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_locks_expiration_index` (`expiration`);

--
-- Indexes for table `clients`
--
ALTER TABLE `clients`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `clients_email_unique` (`email`),
  ADD UNIQUE KEY `clients_phone_unique` (`phone`),
  ADD UNIQUE KEY `clients_google_id_unique` (`google_id`),
  ADD KEY `clients_role_id_foreign` (`role_id`);

--
-- Indexes for table `client_balances`
--
ALTER TABLE `client_balances`
  ADD PRIMARY KEY (`id`),
  ADD KEY `client_balances_client_id_foreign` (`client_id`);

--
-- Indexes for table `client_guests`
--
ALTER TABLE `client_guests`
  ADD PRIMARY KEY (`id`),
  ADD KEY `client_guests_client_id_foreign` (`client_id`);

--
-- Indexes for table `client_ledgers`
--
ALTER TABLE `client_ledgers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `client_ledgers_client_id_foreign` (`client_id`),
  ADD KEY `client_ledgers_booking_id_foreign` (`booking_id`),
  ADD KEY `client_ledgers_payment_id_foreign` (`payment_id`);

--
-- Indexes for table `clubs`
--
ALTER TABLE `clubs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `club_assets`
--
ALTER TABLE `club_assets`
  ADD PRIMARY KEY (`id`),
  ADD KEY `club_assets_club_id_file_type_is_active_index` (`club_id`,`file_type`,`is_active`),
  ADD KEY `club_assets_batch_id_index` (`batch_id`),
  ADD KEY `club_assets_file_type_index` (`file_type`);

--
-- Indexes for table `club_staff`
--
ALTER TABLE `club_staff`
  ADD PRIMARY KEY (`id`),
  ADD KEY `club_staff_club_id_foreign` (`club_id`);

--
-- Indexes for table `complaints`
--
ALTER TABLE `complaints`
  ADD PRIMARY KEY (`id`),
  ADD KEY `complaints_client_id_foreign` (`client_id`),
  ADD KEY `complaints_club_id_foreign` (`club_id`),
  ADD KEY `complaints_booking_id_foreign` (`booking_id`);

--
-- Indexes for table `datagrid_saved_filters`
--
ALTER TABLE `datagrid_saved_filters`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `datagrid_saved_filters_user_id_name_src_unique` (`user_id`,`name`,`src`);

--
-- Indexes for table `events`
--
ALTER TABLE `events`
  ADD PRIMARY KEY (`id`),
  ADD KEY `events_club_id_foreign` (`club_id`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `feature_requests`
--
ALTER TABLE `feature_requests`
  ADD PRIMARY KEY (`id`),
  ADD KEY `feature_requests_client_id_foreign` (`client_id`);

--
-- Indexes for table `floors`
--
ALTER TABLE `floors`
  ADD PRIMARY KEY (`id`),
  ADD KEY `floors_branch_id_foreign` (`branch_id`);

--
-- Indexes for table `flyers`
--
ALTER TABLE `flyers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `flyers_file_type_index` (`file_type`),
  ADD KEY `flyers_is_active_index` (`is_active`);

--
-- Indexes for table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Indexes for table `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `mobile_app_roles`
--
ALTER TABLE `mobile_app_roles`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `notifications_client_id_foreign` (`client_id`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `payments_client_id_foreign` (`client_id`),
  ADD KEY `payments_booking_id_foreign` (`booking_id`),
  ADD KEY `payments_recorded_by_foreign` (`recorded_by`);

--
-- Indexes for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`),
  ADD KEY `personal_access_tokens_expires_at_index` (`expires_at`);

--
-- Indexes for table `promo_codes`
--
ALTER TABLE `promo_codes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `promo_codes_code_unique` (`code`),
  ADD KEY `promo_codes_event_id_foreign` (`event_id`);

--
-- Indexes for table `reviews`
--
ALTER TABLE `reviews`
  ADD PRIMARY KEY (`id`),
  ADD KEY `reviews_client_id_foreign` (`client_id`),
  ADD KEY `reviews_club_id_foreign` (`club_id`),
  ADD KEY `reviews_booking_id_foreign` (`booking_id`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `settings_key_unique` (`key`);

--
-- Indexes for table `tables`
--
ALTER TABLE `tables`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tables_club_id_foreign` (`club_id`);

--
-- Indexes for table `transactions`
--
ALTER TABLE `transactions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `transactions_payment_id_foreign` (`payment_id`),
  ADD KEY `transactions_booking_id_foreign` (`booking_id`),
  ADD KEY `transactions_recorded_by_foreign` (`recorded_by`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `audit_logs`
--
ALTER TABLE `audit_logs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `bookings`
--
ALTER TABLE `bookings`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `booking_guests`
--
ALTER TABLE `booking_guests`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `branches`
--
ALTER TABLE `branches`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `clients`
--
ALTER TABLE `clients`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `client_balances`
--
ALTER TABLE `client_balances`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `client_guests`
--
ALTER TABLE `client_guests`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `client_ledgers`
--
ALTER TABLE `client_ledgers`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `clubs`
--
ALTER TABLE `clubs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `club_assets`
--
ALTER TABLE `club_assets`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=123;

--
-- AUTO_INCREMENT for table `club_staff`
--
ALTER TABLE `club_staff`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `complaints`
--
ALTER TABLE `complaints`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `datagrid_saved_filters`
--
ALTER TABLE `datagrid_saved_filters`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `events`
--
ALTER TABLE `events`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `feature_requests`
--
ALTER TABLE `feature_requests`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `floors`
--
ALTER TABLE `floors`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `flyers`
--
ALTER TABLE `flyers`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT for table `mobile_app_roles`
--
ALTER TABLE `mobile_app_roles`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `promo_codes`
--
ALTER TABLE `promo_codes`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `reviews`
--
ALTER TABLE `reviews`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `settings`
--
ALTER TABLE `settings`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tables`
--
ALTER TABLE `tables`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `transactions`
--
ALTER TABLE `transactions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `bookings`
--
ALTER TABLE `bookings`
  ADD CONSTRAINT `bookings_client_id_foreign` FOREIGN KEY (`client_id`) REFERENCES `clients` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `bookings_club_id_foreign` FOREIGN KEY (`club_id`) REFERENCES `clubs` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `bookings_event_id_foreign` FOREIGN KEY (`event_id`) REFERENCES `events` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `bookings_table_id_foreign` FOREIGN KEY (`table_id`) REFERENCES `tables` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `booking_guests`
--
ALTER TABLE `booking_guests`
  ADD CONSTRAINT `booking_guests_booking_id_foreign` FOREIGN KEY (`booking_id`) REFERENCES `bookings` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `booking_guests_client_id_foreign` FOREIGN KEY (`client_id`) REFERENCES `clients` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `booking_guests_guest_id_foreign` FOREIGN KEY (`guest_id`) REFERENCES `client_guests` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `branches`
--
ALTER TABLE `branches`
  ADD CONSTRAINT `branches_club_id_foreign` FOREIGN KEY (`club_id`) REFERENCES `clubs` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `clients`
--
ALTER TABLE `clients`
  ADD CONSTRAINT `clients_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `mobile_app_roles` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `client_balances`
--
ALTER TABLE `client_balances`
  ADD CONSTRAINT `client_balances_client_id_foreign` FOREIGN KEY (`client_id`) REFERENCES `clients` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `client_guests`
--
ALTER TABLE `client_guests`
  ADD CONSTRAINT `client_guests_client_id_foreign` FOREIGN KEY (`client_id`) REFERENCES `clients` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `client_ledgers`
--
ALTER TABLE `client_ledgers`
  ADD CONSTRAINT `client_ledgers_booking_id_foreign` FOREIGN KEY (`booking_id`) REFERENCES `bookings` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `client_ledgers_client_id_foreign` FOREIGN KEY (`client_id`) REFERENCES `clients` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `client_ledgers_payment_id_foreign` FOREIGN KEY (`payment_id`) REFERENCES `payments` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `club_assets`
--
ALTER TABLE `club_assets`
  ADD CONSTRAINT `club_assets_club_id_foreign` FOREIGN KEY (`club_id`) REFERENCES `clubs` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `club_staff`
--
ALTER TABLE `club_staff`
  ADD CONSTRAINT `club_staff_club_id_foreign` FOREIGN KEY (`club_id`) REFERENCES `clubs` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `complaints`
--
ALTER TABLE `complaints`
  ADD CONSTRAINT `complaints_booking_id_foreign` FOREIGN KEY (`booking_id`) REFERENCES `bookings` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `complaints_client_id_foreign` FOREIGN KEY (`client_id`) REFERENCES `clients` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `complaints_club_id_foreign` FOREIGN KEY (`club_id`) REFERENCES `clubs` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `events`
--
ALTER TABLE `events`
  ADD CONSTRAINT `events_club_id_foreign` FOREIGN KEY (`club_id`) REFERENCES `clubs` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `feature_requests`
--
ALTER TABLE `feature_requests`
  ADD CONSTRAINT `feature_requests_client_id_foreign` FOREIGN KEY (`client_id`) REFERENCES `clients` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `floors`
--
ALTER TABLE `floors`
  ADD CONSTRAINT `floors_branch_id_foreign` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `notifications`
--
ALTER TABLE `notifications`
  ADD CONSTRAINT `notifications_client_id_foreign` FOREIGN KEY (`client_id`) REFERENCES `clients` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `payments`
--
ALTER TABLE `payments`
  ADD CONSTRAINT `payments_booking_id_foreign` FOREIGN KEY (`booking_id`) REFERENCES `bookings` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `payments_client_id_foreign` FOREIGN KEY (`client_id`) REFERENCES `clients` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `payments_recorded_by_foreign` FOREIGN KEY (`recorded_by`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `promo_codes`
--
ALTER TABLE `promo_codes`
  ADD CONSTRAINT `promo_codes_event_id_foreign` FOREIGN KEY (`event_id`) REFERENCES `events` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `reviews`
--
ALTER TABLE `reviews`
  ADD CONSTRAINT `reviews_booking_id_foreign` FOREIGN KEY (`booking_id`) REFERENCES `bookings` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `reviews_client_id_foreign` FOREIGN KEY (`client_id`) REFERENCES `clients` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `reviews_club_id_foreign` FOREIGN KEY (`club_id`) REFERENCES `clubs` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `tables`
--
ALTER TABLE `tables`
  ADD CONSTRAINT `tables_club_id_foreign` FOREIGN KEY (`club_id`) REFERENCES `clubs` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `transactions`
--
ALTER TABLE `transactions`
  ADD CONSTRAINT `transactions_booking_id_foreign` FOREIGN KEY (`booking_id`) REFERENCES `bookings` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `transactions_payment_id_foreign` FOREIGN KEY (`payment_id`) REFERENCES `payments` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `transactions_recorded_by_foreign` FOREIGN KEY (`recorded_by`) REFERENCES `users` (`id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
