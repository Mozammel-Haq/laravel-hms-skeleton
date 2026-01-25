-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jan 25, 2026 at 02:03 AM
-- Server version: 8.4.3
-- PHP Version: 8.3.26

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `project_hms`
--

-- --------------------------------------------------------

--
-- Table structure for table `lara_activity_logs`
--

CREATE TABLE `lara_activity_logs` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `clinic_id` bigint UNSIGNED DEFAULT NULL,
  `action` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `entity_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `entity_id` bigint UNSIGNED NOT NULL,
  `ip_address` varchar(45) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `lara_activity_logs`
--

INSERT INTO `lara_activity_logs` (`id`, `user_id`, `clinic_id`, `action`, `entity_type`, `entity_id`, `ip_address`, `created_at`) VALUES
(4774, 6, 1, 'created', 'appointment', 29, '103.108.140.45', '2025-01-21 02:00:00'),
(4775, 6, 1, 'created', 'appointment', 30, '103.108.140.45', '2025-01-21 02:05:00'),
(4776, 5, 1, 'updated', 'patient_vitals', 17, '103.108.140.50', '2025-01-24 02:55:00'),
(4777, 2, 1, 'created', 'consultation', 29, '103.108.140.55', '2025-01-24 03:00:00'),
(4778, 6, 1, 'created', 'invoice', 29, '103.108.140.45', '2025-01-24 03:00:00'),
(4779, 1, 1, 'updated', 'inpatient_round', 9, '103.108.140.60', '2025-01-22 03:00:00'),
(4780, 5, 1, 'created', 'nursing_note', 9, '103.108.140.50', '2025-01-22 08:00:00'),
(4781, 8, 1, 'created', 'lab_test_result', 13, '103.108.140.65', '2025-01-24 04:00:00'),
(4782, 2, 1, 'updated', 'App\\Models\\User', 2, '127.0.0.1', '2026-01-24 16:09:53'),
(4783, 5, 1, 'updated', 'App\\Models\\User', 5, '127.0.0.1', '2026-01-24 16:12:16'),
(4784, 3, 1, 'updated', 'App\\Models\\User', 3, '127.0.0.1', '2026-01-24 16:12:54'),
(4785, 6, 1, 'updated', 'App\\Models\\User', 6, '127.0.0.1', '2026-01-24 16:13:32'),
(4786, 8, 1, 'updated', 'App\\Models\\User', 8, '127.0.0.1', '2026-01-24 16:14:14'),
(4787, 2, 1, 'created', 'App\\Models\\User', 11, '127.0.0.1', '2026-01-24 16:23:31'),
(4788, 2, 1, 'created', 'App\\Models\\Doctor', 4, '127.0.0.1', '2026-01-24 16:23:31'),
(4789, 2, 1, 'created', 'App\\Models\\DoctorSchedule', 12, '127.0.0.1', '2026-01-24 16:54:54'),
(4790, 2, 1, 'created', 'App\\Models\\DoctorSchedule', 13, '127.0.0.1', '2026-01-24 16:57:55'),
(4791, 2, 1, 'updated', 'App\\Models\\DoctorScheduleException', 5, '127.0.0.1', '2026-01-24 17:02:18'),
(4792, 5, 1, 'created', 'App\\Models\\Appointment', 336, '127.0.0.1', '2026-01-24 17:03:47'),
(4793, 5, 1, 'updated', 'App\\Models\\Appointment', 336, '127.0.0.1', '2026-01-24 17:09:02'),
(4794, 5, 1, 'created', 'App\\Models\\Visit', 336, '127.0.0.1', '2026-01-24 17:09:13'),
(4795, 5, 1, 'created', 'App\\Models\\Consultation', 326, '127.0.0.1', '2026-01-24 17:09:14'),
(4796, 5, 1, 'updated', 'App\\Models\\Visit', 336, '127.0.0.1', '2026-01-24 17:09:14'),
(4797, 5, 1, 'created', 'App\\Models\\Invoice', 584, '127.0.0.1', '2026-01-24 17:09:14'),
(4798, 5, 1, 'created', 'App\\Models\\InvoiceItem', 774, '127.0.0.1', '2026-01-24 17:09:14'),
(4799, 5, 1, 'created', 'App\\Models\\Invoice', 585, '127.0.0.1', '2026-01-24 17:10:54'),
(4800, 5, 1, 'created', 'App\\Models\\InvoiceItem', 775, '127.0.0.1', '2026-01-24 17:10:54'),
(4801, 5, 1, 'created', 'App\\Models\\Payment', 605, '127.0.0.1', '2026-01-24 17:11:34'),
(4802, 5, 1, 'updated', 'App\\Models\\Invoice', 585, '127.0.0.1', '2026-01-24 17:11:34'),
(4803, 5, 1, 'created', 'App\\Models\\Payment', 606, '127.0.0.1', '2026-01-24 17:12:42'),
(4804, 5, 1, 'updated', 'App\\Models\\Invoice', 584, '127.0.0.1', '2026-01-24 17:12:42'),
(4805, 5, 1, 'updated', 'App\\Models\\Appointment', 336, '127.0.0.1', '2026-01-24 17:12:42'),
(4806, 4, 1, 'updated', 'App\\Models\\User', 4, '127.0.0.1', '2026-01-24 17:15:02'),
(4807, NULL, 1, 'updated', 'App\\Models\\User', 11, '127.0.0.1', '2026-01-24 17:17:13'),
(4808, 3, 1, 'created', 'App\\Models\\LabTestOrder', 137, '127.0.0.1', '2026-01-24 17:25:03'),
(4809, 2, 1, 'created', 'App\\Models\\LabTestOrder', 138, '127.0.0.1', '2026-01-25 01:27:43'),
(4810, 2, 1, 'created', 'App\\Models\\LabTestOrder', 139, '127.0.0.1', '2026-01-25 01:28:59'),
(4811, 2, 1, 'created', 'App\\Models\\Invoice', 586, '127.0.0.1', '2026-01-25 01:30:39'),
(4812, 2, 1, 'created', 'App\\Models\\InvoiceItem', 776, '127.0.0.1', '2026-01-25 01:30:39'),
(4813, 2, 1, 'updated', 'App\\Models\\LabTestOrder', 137, '127.0.0.1', '2026-01-25 01:30:39'),
(4814, 2, 1, 'created', 'App\\Models\\Payment', 607, '127.0.0.1', '2026-01-25 01:30:54'),
(4815, 2, 1, 'updated', 'App\\Models\\Invoice', 586, '127.0.0.1', '2026-01-25 01:30:54'),
(4816, 2, 1, 'created', 'App\\Models\\LabTestResult', 128, '127.0.0.1', '2026-01-25 01:37:23'),
(4817, 2, 1, 'updated', 'App\\Models\\LabTestOrder', 137, '127.0.0.1', '2026-01-25 01:37:23');

-- --------------------------------------------------------

--
-- Table structure for table `lara_admissions`
--

CREATE TABLE `lara_admissions` (
  `id` bigint UNSIGNED NOT NULL,
  `clinic_id` bigint UNSIGNED NOT NULL,
  `patient_id` bigint UNSIGNED NOT NULL,
  `admitting_doctor_id` bigint UNSIGNED NOT NULL,
  `admission_date` datetime NOT NULL,
  `admission_reason` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `current_bed_id` bigint UNSIGNED DEFAULT NULL,
  `status` enum('admitted','transferred','discharged') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'admitted',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `discharge_recommended` tinyint(1) NOT NULL DEFAULT '0',
  `discharge_recommended_by` bigint UNSIGNED DEFAULT NULL,
  `discharged_by` bigint UNSIGNED DEFAULT NULL,
  `discharge_date` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `lara_admissions`
--

INSERT INTO `lara_admissions` (`id`, `clinic_id`, `patient_id`, `admitting_doctor_id`, `admission_date`, `admission_reason`, `current_bed_id`, `status`, `created_at`, `updated_at`, `deleted_at`, `discharge_recommended`, `discharge_recommended_by`, `discharged_by`, `discharge_date`) VALUES
(32, 1, 3, 1, '2024-12-15 10:30:00', 'Acute chest pain, requires cardiac monitoring', 5, 'admitted', '2024-12-15 04:30:00', '2025-01-20 04:00:00', NULL, 0, NULL, NULL, NULL),
(33, 1, 5, 1, '2025-01-10 14:20:00', 'Hypertensive crisis, ICU admission required', 8, 'admitted', '2025-01-10 08:20:00', '2025-01-20 04:00:00', NULL, 0, NULL, NULL, NULL),
(34, 1, 7, 1, '2025-01-18 09:15:00', 'Cardiac arrhythmia, monitoring needed', 10, 'admitted', '2025-01-18 03:15:00', '2025-01-20 04:00:00', NULL, 0, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `lara_admission_deposits`
--

CREATE TABLE `lara_admission_deposits` (
  `id` bigint UNSIGNED NOT NULL,
  `clinic_id` bigint UNSIGNED NOT NULL,
  `admission_id` bigint UNSIGNED NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `payment_method` enum('cash','card','bank_transfer') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'cash',
  `transaction_reference` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `received_at` datetime DEFAULT NULL,
  `received_by` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `lara_admission_deposits`
--

INSERT INTO `lara_admission_deposits` (`id`, `clinic_id`, `admission_id`, `amount`, `payment_method`, `transaction_reference`, `received_at`, `received_by`, `created_at`, `updated_at`) VALUES
(32, 1, 1, 15000.00, 'cash', NULL, '2024-12-15 10:45:00', 6, '2024-12-15 04:45:00', '2024-12-15 04:45:00'),
(33, 1, 2, 25000.00, 'card', 'TXN-ICU-2025-001', '2025-01-10 14:30:00', 6, '2025-01-10 08:30:00', '2025-01-10 08:30:00'),
(34, 1, 3, 20000.00, '', 'BKASH-ADM-2025-001', '2025-01-18 09:30:00', 6, '2025-01-18 03:30:00', '2025-01-18 03:30:00');

-- --------------------------------------------------------

--
-- Table structure for table `lara_appointments`
--

CREATE TABLE `lara_appointments` (
  `id` bigint UNSIGNED NOT NULL,
  `clinic_id` bigint UNSIGNED NOT NULL,
  `patient_id` bigint UNSIGNED NOT NULL,
  `doctor_id` bigint UNSIGNED NOT NULL,
  `department_id` bigint UNSIGNED NOT NULL,
  `appointment_date` date NOT NULL,
  `start_time` time NOT NULL,
  `end_time` time NOT NULL,
  `appointment_type` enum('online','in_person') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `reason_for_visit` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `booking_source` enum('reception','patient_portal') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `fee` decimal(10,2) DEFAULT NULL,
  `visit_type` enum('new','follow_up') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'new',
  `created_by` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `lara_appointments`
--

INSERT INTO `lara_appointments` (`id`, `clinic_id`, `patient_id`, `doctor_id`, `department_id`, `appointment_date`, `start_time`, `end_time`, `appointment_type`, `reason_for_visit`, `booking_source`, `status`, `fee`, `visit_type`, `created_by`, `created_at`, `updated_at`, `deleted_at`) VALUES
(307, 1, 1, 1, 1, '2024-01-08', '09:00:00', '09:20:00', 'in_person', 'Regular checkup', 'reception', 'completed', 1500.00, 'new', 6, '2024-01-05 02:00:00', '2024-01-08 04:00:00', NULL),
(308, 1, 2, 2, 6, '2024-01-10', '10:00:00', '10:15:00', 'in_person', 'Pregnancy checkup', 'reception', 'completed', 1200.00, 'new', 6, '2024-01-08 03:00:00', '2024-01-10 05:00:00', NULL),
(309, 1, 3, 1, 1, '2024-01-15', '09:20:00', '09:40:00', 'in_person', 'Chest pain', 'reception', 'completed', 1500.00, 'new', 6, '2024-01-12 04:00:00', '2024-01-15 04:30:00', NULL),
(310, 1, 4, 2, 6, '2024-01-17', '10:15:00', '10:30:00', 'online', 'Consultation', 'patient_portal', 'completed', 1200.00, 'follow_up', 6, '2024-01-15 05:00:00', '2024-01-17 05:00:00', NULL),
(311, 1, 5, 1, 1, '2024-01-22', '09:40:00', '10:00:00', 'in_person', 'High blood pressure', 'reception', 'completed', 1500.00, 'new', 6, '2024-01-19 02:30:00', '2024-01-22 05:00:00', NULL),
(312, 1, 6, 2, 6, '2024-01-24', '10:30:00', '10:45:00', 'in_person', 'Menstrual problems', 'reception', 'completed', 1200.00, 'new', 6, '2024-01-22 03:00:00', '2024-01-24 05:30:00', NULL),
(313, 1, 7, 1, 1, '2024-01-29', '10:00:00', '10:20:00', 'in_person', 'Follow-up', 'reception', 'completed', 800.00, 'follow_up', 6, '2024-01-26 04:00:00', '2024-01-29 05:00:00', NULL),
(314, 1, 8, 1, 1, '2024-02-05', '09:00:00', '09:20:00', 'in_person', 'Heart palpitations', 'reception', 'completed', 1500.00, 'new', 6, '2024-02-02 02:00:00', '2024-02-05 04:00:00', NULL),
(315, 1, 9, 1, 1, '2024-02-12', '09:20:00', '09:40:00', 'in_person', 'Checkup', 'reception', 'completed', 1500.00, 'new', 6, '2024-02-09 03:00:00', '2024-02-12 04:30:00', NULL),
(316, 1, 10, 2, 6, '2024-02-14', '10:00:00', '10:15:00', 'in_person', 'Prenatal care', 'reception', 'completed', 1200.00, 'new', 6, '2024-02-12 04:00:00', '2024-02-14 05:00:00', NULL),
(317, 1, 1, 1, 1, '2024-02-19', '09:40:00', '10:00:00', 'in_person', 'Follow-up checkup', 'reception', 'completed', 800.00, 'follow_up', 6, '2024-02-16 05:00:00', '2024-02-19 04:30:00', NULL),
(318, 1, 11, 2, 6, '2024-02-21', '10:15:00', '10:30:00', 'in_person', 'Consultation', 'reception', 'completed', 1200.00, 'new', 6, '2024-02-19 02:30:00', '2024-02-21 05:00:00', NULL),
(319, 1, 12, 1, 1, '2024-02-26', '10:00:00', '10:20:00', 'online', 'Online consultation', 'patient_portal', 'completed', 1500.00, 'new', 6, '2024-02-24 03:00:00', '2024-02-26 05:00:00', NULL),
(320, 1, 13, 1, 1, '2024-03-04', '09:00:00', '09:20:00', 'in_person', 'Diabetes consultation', 'reception', 'completed', 1500.00, 'new', 6, '2024-03-01 02:00:00', '2024-03-04 04:00:00', NULL),
(321, 1, 14, 2, 6, '2024-03-11', '10:00:00', '10:15:00', 'in_person', 'General checkup', 'reception', 'completed', 1200.00, 'new', 6, '2024-03-08 03:00:00', '2024-03-11 05:00:00', NULL),
(322, 1, 15, 1, 1, '2024-03-18', '09:20:00', '09:40:00', 'in_person', 'Blood pressure check', 'reception', 'completed', 1500.00, 'new', 6, '2024-03-15 04:00:00', '2024-03-18 04:30:00', NULL),
(323, 1, 2, 2, 6, '2024-03-25', '10:15:00', '10:30:00', 'in_person', 'Pregnancy follow-up', 'reception', 'completed', 600.00, 'follow_up', 6, '2024-03-22 05:00:00', '2024-03-25 05:00:00', NULL),
(324, 1, 1, 1, 1, '2024-04-08', '09:00:00', '09:20:00', 'in_person', 'Regular follow-up', 'reception', 'completed', 800.00, 'follow_up', 6, '2024-04-05 02:00:00', '2024-04-08 04:00:00', NULL),
(325, 1, 3, 1, 1, '2024-04-15', '09:20:00', '09:40:00', 'in_person', 'Medication review', 'reception', 'completed', 800.00, 'follow_up', 6, '2024-04-12 03:00:00', '2024-04-15 04:30:00', NULL),
(326, 1, 5, 1, 1, '2024-04-22', '09:40:00', '10:00:00', 'in_person', 'BP monitoring', 'reception', 'completed', 800.00, 'follow_up', 6, '2024-04-19 04:00:00', '2024-04-22 05:00:00', NULL),
(327, 1, 1, 1, 1, '2025-01-06', '09:00:00', '09:20:00', 'in_person', 'Annual checkup', 'reception', 'completed', 1500.00, 'new', 6, '2025-01-03 02:00:00', '2025-01-06 04:00:00', NULL),
(328, 1, 2, 2, 6, '2025-01-07', '10:00:00', '10:15:00', 'in_person', 'Prenatal checkup', 'reception', 'completed', 1200.00, 'follow_up', 6, '2025-01-04 03:00:00', '2025-01-07 05:00:00', NULL),
(329, 1, 3, 1, 1, '2025-01-13', '09:20:00', '09:40:00', 'in_person', 'Chest discomfort', 'reception', 'completed', 1500.00, 'new', 6, '2025-01-10 02:00:00', '2025-01-13 04:30:00', NULL),
(330, 1, 4, 2, 6, '2025-01-14', '10:15:00', '10:30:00', 'online', 'Follow-up consultation', 'patient_portal', 'completed', 600.00, 'follow_up', 6, '2025-01-11 03:00:00', '2025-01-14 05:00:00', NULL),
(331, 1, 5, 1, 1, '2025-01-20', '09:00:00', '09:20:00', 'in_person', 'Hypertension review', 'reception', 'completed', 800.00, 'follow_up', 6, '2025-01-17 02:00:00', '2025-01-20 04:00:00', NULL),
(332, 1, 6, 2, 6, '2025-01-21', '10:00:00', '10:15:00', 'in_person', 'Routine gynec check', 'reception', 'completed', 1200.00, 'new', 6, '2025-01-18 03:00:00', '2025-01-21 05:00:00', NULL),
(333, 1, 7, 1, 1, '2025-01-22', '09:20:00', '09:40:00', 'in_person', 'Medication adjustment', 'reception', 'completed', 800.00, 'follow_up', 6, '2025-01-19 04:00:00', '2025-01-22 04:30:00', NULL),
(334, 1, 8, 2, 6, '2025-01-23', '10:15:00', '10:30:00', 'in_person', 'Health consultation', 'reception', 'completed', 1200.00, 'new', 6, '2025-01-20 05:00:00', '2025-01-23 05:00:00', NULL),
(335, 1, 9, 1, 1, '2025-01-24', '09:00:00', '09:20:00', 'in_person', 'Heart screening', 'reception', 'in_progress', 1500.00, 'new', 6, '2025-01-21 02:00:00', '2025-01-24 03:00:00', NULL),
(336, 1, 8, 4, 6, '2026-01-26', '09:00:00', '10:00:00', 'in_person', 'Standard Appointment', 'reception', 'confirmed', 1200.00, 'new', 5, '2026-01-24 11:03:47', '2026-01-24 11:12:42', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `lara_appointment_status_logs`
--

CREATE TABLE `lara_appointment_status_logs` (
  `id` bigint UNSIGNED NOT NULL,
  `appointment_id` bigint UNSIGNED NOT NULL,
  `old_status` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `new_status` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `changed_by` bigint UNSIGNED NOT NULL,
  `change_reason` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `changed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `clinic_id` bigint UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `lara_appointment_status_logs`
--

INSERT INTO `lara_appointment_status_logs` (`id`, `appointment_id`, `old_status`, `new_status`, `changed_by`, `change_reason`, `changed_at`, `clinic_id`) VALUES
(301, 29, 'scheduled', 'confirmed', 6, 'Patient confirmed via phone', '2025-01-22 04:00:00', 1),
(302, 29, 'confirmed', 'in_progress', 2, 'Doctor started consultation', '2025-01-24 03:00:00', 1),
(303, 30, 'scheduled', 'confirmed', 6, 'Confirmed by receptionist', '2025-01-22 05:00:00', 1),
(304, 30, 'confirmed', 'waiting', 5, 'Patient checked in', '2025-01-24 03:55:00', 1),
(305, 1, 'scheduled', 'confirmed', 6, 'Appointment confirmed', '2024-01-06 03:00:00', 1),
(306, 1, 'confirmed', 'in_progress', 2, 'Consultation started', '2024-01-08 03:00:00', 1),
(307, 1, 'in_progress', 'completed', 2, 'Consultation completed', '2024-01-08 03:45:00', 1),
(308, 2, 'scheduled', 'confirmed', 6, 'Patient confirmed', '2024-01-09 04:00:00', 1),
(309, 2, 'confirmed', 'in_progress', 3, 'Started consultation', '2024-01-10 04:00:00', 1),
(310, 2, 'in_progress', 'completed', 3, 'Completed successfully', '2024-01-10 04:35:00', 1),
(311, 5, 'scheduled', 'confirmed', 6, 'Confirmed booking', '2024-01-20 03:00:00', 1),
(312, 5, 'confirmed', 'in_progress', 2, 'Patient in consultation', '2024-01-22 03:40:00', 1),
(313, 5, 'in_progress', 'completed', 2, 'Consultation finished', '2024-01-22 04:25:00', 1),
(314, 21, 'scheduled', 'confirmed', 6, 'Confirmed via phone call', '2025-01-04 04:00:00', 1),
(315, 21, 'confirmed', 'in_progress', 2, 'Consultation in progress', '2025-01-06 03:00:00', 1),
(316, 21, 'in_progress', 'completed', 2, 'Successfully completed', '2025-01-06 03:50:00', 1),
(317, 23, 'scheduled', 'confirmed', 6, 'Patient confirmed attendance', '2025-01-11 08:00:00', 1),
(318, 23, 'confirmed', 'in_progress', 2, 'Doctor began consultation', '2025-01-13 03:20:00', 1),
(319, 23, 'in_progress', 'completed', 2, 'Consultation completed', '2025-01-13 04:10:00', 1),
(320, 4, 'scheduled', 'cancelled', 6, 'Patient requested cancellation - rescheduled', '2024-01-16 08:00:00', 1);

-- --------------------------------------------------------

--
-- Table structure for table `lara_beds`
--

CREATE TABLE `lara_beds` (
  `id` bigint UNSIGNED NOT NULL,
  `room_id` bigint UNSIGNED NOT NULL,
  `clinic_id` bigint UNSIGNED NOT NULL,
  `bed_number` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` enum('available','occupied','maintenance') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'available',
  `position` int NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `lara_beds`
--

INSERT INTO `lara_beds` (`id`, `room_id`, `clinic_id`, `bed_number`, `status`, `position`, `created_at`, `updated_at`) VALUES
(121, 1, 1, 'B1', 'available', 1, '2023-01-15 00:00:00', '2025-01-20 04:00:00'),
(122, 1, 1, 'B2', 'available', 2, '2023-01-15 00:00:00', '2025-01-20 04:00:00'),
(123, 2, 1, 'B1', 'available', 1, '2023-01-15 00:00:00', '2025-01-20 04:00:00'),
(124, 2, 1, 'B2', 'available', 2, '2023-01-15 00:00:00', '2025-01-20 04:00:00'),
(125, 3, 1, 'B1', 'occupied', 1, '2023-01-15 00:00:00', '2025-01-20 04:00:00'),
(126, 3, 1, 'B2', 'occupied', 2, '2023-01-15 00:00:00', '2025-01-20 04:00:00'),
(127, 5, 1, 'ICU-B1', 'available', 1, '2023-01-15 00:00:00', '2025-01-20 04:00:00'),
(128, 6, 1, 'ICU-B1', 'occupied', 1, '2023-01-15 00:00:00', '2025-01-20 04:00:00'),
(129, 7, 1, 'CAB-B1', 'available', 1, '2023-01-15 00:00:00', '2025-01-20 04:00:00'),
(130, 9, 1, 'CAB-B1', 'occupied', 1, '2023-01-15 00:00:00', '2025-01-20 04:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `lara_bed_assignments`
--

CREATE TABLE `lara_bed_assignments` (
  `id` bigint UNSIGNED NOT NULL,
  `admission_id` bigint UNSIGNED NOT NULL,
  `bed_id` bigint UNSIGNED NOT NULL,
  `assigned_at` datetime NOT NULL,
  `released_at` datetime DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `clinic_id` bigint UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `lara_bed_assignments`
--

INSERT INTO `lara_bed_assignments` (`id`, `admission_id`, `bed_id`, `assigned_at`, `released_at`, `created_at`, `updated_at`, `clinic_id`) VALUES
(32, 1, 5, '2024-12-15 10:30:00', NULL, '2024-12-15 04:30:00', '2024-12-15 04:30:00', 1),
(33, 2, 8, '2025-01-10 14:20:00', NULL, '2025-01-10 08:20:00', '2025-01-10 08:20:00', 1),
(34, 3, 10, '2025-01-18 09:15:00', NULL, '2025-01-18 03:15:00', '2025-01-18 03:15:00', 1);

-- --------------------------------------------------------

--
-- Table structure for table `lara_cache`
--

CREATE TABLE `lara_cache` (
  `key` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `lara_cache_locks`
--

CREATE TABLE `lara_cache_locks` (
  `key` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `lara_clinics`
--

CREATE TABLE `lara_clinics` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `code` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `registration_number` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address_line_1` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `address_line_2` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `city` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `state` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `country` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `postal_code` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `website` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `logo_path` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `timezone` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `currency` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `opening_time` time DEFAULT NULL,
  `closing_time` time DEFAULT NULL,
  `status` enum('active','inactive','suspended') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `lara_clinics`
--

INSERT INTO `lara_clinics` (`id`, `name`, `code`, `registration_number`, `address_line_1`, `address_line_2`, `city`, `state`, `country`, `postal_code`, `phone`, `email`, `website`, `logo_path`, `timezone`, `currency`, `opening_time`, `closing_time`, `status`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'Dhaka Medical Center', 'DMC001', 'REG-2020-DMC-001', 'House 45, Road 12', 'Dhanmondi', 'Dhaka', 'Dhaka Division', 'Bangladesh', '1209', '+880-2-9876543', 'info@dhakamedical.com.bd', 'https://www.dhakamedical.com.bd', 'clinics/logos/1769270576_18246203_v987-18a.svg', 'Asia/Dhaka', 'BDT', '08:00:00', '22:00:00', 'active', '2023-01-15 00:00:00', '2026-01-24 10:02:56', NULL),
(2, 'Chittagong Healthcare Hospital', 'CHH002', 'REG-2021-CHH-002', '123 Agrabad C/A', 'GEC Circle', 'Chittagong', 'Chittagong Division', 'Bangladesh', '4100', '+880-31-654321', 'contact@chittagonghealth.com.bd', 'https://www.chittagonghealth.com.bd', 'clinics/logos/1769270737_47639047_7689030.svg', 'Asia/Dhaka', 'BDT', '07:00:00', '23:00:00', 'active', '2023-03-10 00:00:00', '2026-01-24 10:05:37', NULL),
(3, 'Sylhet Specialized Hospital', 'SSH003', 'REG-2022-SSH-003', 'Zindabazar Main Road', 'Sylhet Sadar', 'Sylhet', 'Sylhet Division', 'Bangladesh', '3100', '+880-821-717171', 'info@sylhetspecialized.com.bd', 'https://www.sylhetspecialized.com.bd', 'clinics/logos/1769270664_47639124_7802875.svg', 'Asia/Dhaka', 'BDT', '08:00:00', '20:00:00', 'active', '2023-06-01 00:00:00', '2026-01-24 10:04:24', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `lara_clinic_images`
--

CREATE TABLE `lara_clinic_images` (
  `id` bigint UNSIGNED NOT NULL,
  `clinic_id` bigint UNSIGNED NOT NULL,
  `image_path` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `sort_order` int NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `lara_consultations`
--

CREATE TABLE `lara_consultations` (
  `id` bigint UNSIGNED NOT NULL,
  `visit_id` bigint UNSIGNED NOT NULL,
  `doctor_notes` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `diagnosis` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `follow_up_required` tinyint(1) NOT NULL DEFAULT '0',
  `follow_up_date` date DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `clinic_id` bigint UNSIGNED DEFAULT NULL,
  `doctor_id` bigint UNSIGNED DEFAULT NULL,
  `patient_id` bigint UNSIGNED DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `status` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'in_progress',
  `deleted_at` timestamp NULL DEFAULT NULL,
  `symptoms` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin
) ;

--
-- Dumping data for table `lara_consultations`
--

INSERT INTO `lara_consultations` (`id`, `visit_id`, `doctor_notes`, `diagnosis`, `follow_up_required`, `follow_up_date`, `created_at`, `clinic_id`, `doctor_id`, `patient_id`, `updated_at`, `status`, `deleted_at`, `symptoms`) VALUES
(309, 1, 'Patient presents with stable cardiovascular status. BP: 130/85 mmHg. Regular medications continued.', 'Hypertension - controlled', 1, '2024-02-19', '2024-01-08 03:00:00', 1, 1, 1, '2024-01-08 03:45:00', 'completed', NULL, '[\"chest_tightness\", \"fatigue\"]'),
(310, 2, 'Prenatal checkup - 20 weeks. Fetal heartbeat normal. Mother vitals stable.', 'Normal pregnancy progression', 1, '2024-02-14', '2024-01-10 04:00:00', 1, 2, 2, '2024-01-10 04:35:00', 'completed', NULL, '[\"back_pain\", \"fatigue\"]'),
(311, 3, 'Acute chest pain investigation. ECG shows normal sinus rhythm. Likely muscular pain.', 'Musculoskeletal chest pain', 1, '2024-02-15', '2024-01-15 03:20:00', 1, 1, 3, '2024-01-15 04:10:00', 'completed', NULL, '[\"chest_pain\", \"shortness_of_breath\"]'),
(312, 4, 'Follow-up consultation via telemedicine. Patient doing well on current treatment.', 'Routine prenatal care', 1, '2024-03-25', '2024-01-17 04:15:00', 1, 2, 4, '2024-01-17 04:50:00', 'completed', NULL, '[\"nausea\", \"dizziness\"]'),
(313, 5, 'Blood pressure elevated at 150/95. Medication dosage adjusted. Diet and lifestyle counseling provided.', 'Hypertension - Stage 2', 1, '2024-02-22', '2024-01-22 03:40:00', 1, 1, 5, '2024-01-22 04:25:00', 'completed', NULL, '[\"headache\", \"dizziness\"]'),
(314, 6, 'Irregular menstrual cycle investigation. Hormonal profile ordered. Symptomatic treatment initiated.', 'Menstrual irregularity', 1, '2024-02-24', '2024-01-24 04:30:00', 1, 2, 6, '2024-01-24 05:05:00', 'completed', NULL, '[\"abdominal_pain\", \"irregular_periods\"]'),
(315, 7, 'Follow-up visit. BP well controlled at 125/80. Continue current medications.', 'Hypertension - controlled', 1, '2024-03-15', '2024-01-29 04:00:00', 1, 1, 7, '2024-01-29 04:30:00', 'completed', NULL, NULL),
(316, 8, 'Patient reports palpitations. 24hr Holter monitor recommended. Started on beta blocker.', 'Cardiac arrhythmia - suspected', 1, '2024-03-05', '2024-02-05 03:00:00', 1, 1, 8, '2024-02-05 03:50:00', 'completed', NULL, '[\"palpitations\", \"anxiety\"]'),
(317, 21, 'Annual cardiac assessment. All parameters within normal limits. Lipid profile excellent.', 'Routine health maintenance', 1, '2025-04-06', '2025-01-06 03:00:00', 1, 1, 1, '2025-01-06 03:50:00', 'completed', NULL, NULL),
(318, 22, 'Prenatal visit at 32 weeks. Baby position good. Preparing for delivery.', 'Normal pregnancy - third trimester', 1, '2025-02-04', '2025-01-07 04:00:00', 1, 2, 2, '2025-01-07 04:40:00', 'completed', NULL, '[\"back_pain\", \"swelling\"]'),
(319, 23, 'Chest discomfort with exertion. Stress test ordered. Started on antianginal therapy.', 'Angina pectoris - stable', 1, '2025-02-13', '2025-01-13 03:20:00', 1, 1, 3, '2025-01-13 04:10:00', 'completed', NULL, '[\"chest_discomfort\", \"fatigue\"]'),
(320, 24, 'Online follow-up. Pregnancy progressing well. No complications noted.', 'Routine prenatal care', 1, '2025-02-14', '2025-01-14 04:15:00', 1, 2, 4, '2025-01-14 04:50:00', 'completed', NULL, NULL),
(321, 25, 'BP monitoring visit. Excellent control at 120/78. Diet compliance good.', 'Hypertension - well controlled', 1, '2025-03-20', '2025-01-20 03:00:00', 1, 1, 5, '2025-01-20 03:45:00', 'completed', NULL, NULL),
(322, 26, 'Routine gynecological examination. Pap smear taken. Contraception counseling provided.', 'Routine gynec checkup', 0, NULL, '2025-01-21 04:00:00', 1, 2, 6, '2025-01-21 04:35:00', 'completed', NULL, NULL),
(323, 27, 'Medication review and adjustment. Improved symptom control achieved.', 'Hypertension management', 1, '2025-03-22', '2025-01-22 03:20:00', 1, 1, 7, '2025-01-22 04:00:00', 'completed', NULL, '[\"mild_headache\"]'),
(324, 28, 'New patient consultation. Comprehensive health assessment performed.', 'General health assessment', 1, '2025-02-23', '2025-01-23 04:15:00', 1, 2, 8, '2025-01-23 04:55:00', 'completed', NULL, '[\"fatigue\", \"weight_gain\"]'),
(325, 29, 'Cardiac screening in progress. Preliminary findings unremarkable.', 'Screening examination', 0, NULL, '2025-01-24 03:00:00', 1, 1, 9, '2025-01-24 03:00:00', 'in_progress', NULL, '[\"occasional_palpitations\"]'),
(326, 336, NULL, NULL, 0, NULL, '2026-01-24 11:09:14', 1, 4, 8, '2026-01-24 11:09:14', 'in_progress', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `lara_departments`
--

CREATE TABLE `lara_departments` (
  `id` bigint UNSIGNED NOT NULL,
  `clinic_id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `floor_number` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone_extension` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` enum('active','inactive') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `lara_departments`
--

INSERT INTO `lara_departments` (`id`, `clinic_id`, `name`, `description`, `floor_number`, `phone_extension`, `status`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 1, 'Cardiology', 'Heart and cardiovascular diseases treatment', '3', '301', 'active', '2023-01-20 00:00:00', '2025-01-20 04:00:00', NULL),
(2, 1, 'Neurology', 'Brain and nervous system disorders', '4', '401', 'active', '2023-01-20 00:00:00', '2025-01-20 04:00:00', NULL),
(3, 1, 'Orthopedics', 'Bone and joint treatments', '2', '201', 'active', '2023-01-20 00:00:00', '2025-01-20 04:00:00', NULL),
(4, 1, 'Pediatrics', 'Child healthcare and treatment', '1', '101', 'active', '2023-01-20 00:00:00', '2025-01-20 04:00:00', NULL),
(5, 1, 'General Medicine', 'General medical consultation', '1', '102', 'active', '2023-01-20 00:00:00', '2025-01-20 04:00:00', NULL),
(6, 1, 'Gynecology', 'Women health and maternity', '3', '302', 'active', '2023-01-20 00:00:00', '2025-01-20 04:00:00', NULL),
(7, 2, 'Cardiology', 'Heart disease treatment center', '2', '201', 'active', '2023-03-15 00:00:00', '2025-01-20 04:00:00', NULL),
(8, 2, 'General Surgery', 'Surgical procedures department', '3', '301', 'active', '2023-03-15 00:00:00', '2025-01-20 04:00:00', NULL),
(9, 2, 'Dermatology', 'Skin disease treatment', '1', '101', 'active', '2023-03-15 00:00:00', '2025-01-20 04:00:00', NULL),
(10, 2, 'ENT', 'Ear, Nose and Throat specialist', '2', '202', 'active', '2023-03-15 00:00:00', '2025-01-20 04:00:00', NULL),
(11, 3, 'Ophthalmology', 'Eye care and treatment', '1', '101', 'active', '2023-06-05 00:00:00', '2025-01-20 04:00:00', NULL),
(12, 3, 'Urology', 'Urinary system treatment', '2', '201', 'active', '2023-06-05 00:00:00', '2025-01-20 04:00:00', NULL),
(13, 3, 'Psychiatry', 'Mental health services', '3', '301', 'active', '2023-06-05 00:00:00', '2025-01-20 04:00:00', NULL),
(14, 1, 'Emergency', 'Emergency and trauma care', 'G', '001', 'active', '2023-01-20 00:00:00', '2025-01-20 04:00:00', NULL),
(15, 2, 'Radiology', 'Imaging and diagnostic services', '1', '103', 'active', '2023-03-15 00:00:00', '2025-01-20 04:00:00', NULL),
(16, 3, 'Pathology', 'Laboratory and diagnostic tests', 'B1', '001', 'active', '2023-06-05 00:00:00', '2025-01-20 04:00:00', NULL),
(17, 1, 'Oncology', 'Cancer treatment and care', '5', '501', 'active', '2023-01-20 00:00:00', '2025-01-20 04:00:00', NULL),
(18, 2, 'Nephrology', 'Kidney disease treatment', '4', '401', 'active', '2023-03-15 00:00:00', '2025-01-20 04:00:00', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `lara_doctors`
--

CREATE TABLE `lara_doctors` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `primary_department_id` bigint UNSIGNED NOT NULL,
  `registration_number` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `license_number` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `specialization` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `experience_years` int UNSIGNED NOT NULL DEFAULT '0',
  `gender` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `blood_group` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `date_of_birth` date DEFAULT NULL,
  `consultation_fee` decimal(10,2) DEFAULT NULL,
  `follow_up_fee` decimal(10,2) DEFAULT NULL,
  `biography` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `profile_photo` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_featured` tinyint(1) NOT NULL DEFAULT '0',
  `status` enum('active','inactive') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `consultation_room_number` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `consultation_floor` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `lara_doctors`
--

INSERT INTO `lara_doctors` (`id`, `user_id`, `primary_department_id`, `registration_number`, `license_number`, `specialization`, `experience_years`, `gender`, `blood_group`, `date_of_birth`, `consultation_fee`, `follow_up_fee`, `biography`, `profile_photo`, `is_featured`, `status`, `consultation_room_number`, `consultation_floor`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 1, 1, 'BMDC-A-12345', 'LIC-2015-001', 'Cardiologist', 15, 'Male', 'B+', '1980-05-15', 1500.00, 800.00, 'Senior Consultant Cardiologist with expertise in interventional cardiology and heart failure management. MBBS from Dhaka Medical College, MD from BSMMU.', 'assets/img/profiles/avatar-02.jpg', 1, 'active', '301', '3', '2023-01-20 00:00:00', '2025-01-20 04:00:00', NULL),
(2, 10, 6, 'BMDC-A-23456', 'LIC-2017-002', 'Gynecologist & Obstetrician', 10, 'Female', 'A+', '1985-08-20', 1200.00, 600.00, 'Specialist in high-risk pregnancy and gynecological surgeries. MBBS, FCPS (Gynecology & Obstetrics). Member of OGSB.', 'assets/img/profiles/avatar-03.jpg', 1, 'active', '302', '3', '2023-02-01 00:00:00', '2025-01-20 04:00:00', NULL),
(3, 9, 7, 'BMDC-A-34567', 'LIC-2016-003', 'Interventional Cardiologist', 12, 'Male', 'O+', '1982-11-10', 1800.00, 900.00, 'Expert in coronary angioplasty and cardiac catheterization. MBBS, MD (Cardiology), Fellowship from India.', 'assets/img/profiles/avatar-09.jpg', 1, 'active', '201', '2', '2023-03-15 00:00:00', '2025-01-20 04:00:00', NULL),
(4, 11, 6, '12345678', 'DMC-201800', 'specialized treatments, surgical treatments,hormonal treatments', 5, 'male', 'B+', '1995-01-01', 1200.00, 1000.00, 'Dr. Tanvir Jubayer is an experienced gynecologist, specialities include- focus on comprehensive female reproductive health, ranging from general care to specialized, surgical, and hormonal treatments. Key areas include maternal-fetal medicine, gynecologic oncology, reproductive endocrinology/infertility, and urogynecology, along with pediatric, adolescent, and menopause care.', 'assets/img/doctors/1769271810_avatar-05.jpg', 1, 'active', '201', '2', '2026-01-24 10:23:31', '2026-01-24 10:23:31', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `lara_doctor_awards`
--

CREATE TABLE `lara_doctor_awards` (
  `id` bigint UNSIGNED NOT NULL,
  `doctor_id` bigint UNSIGNED NOT NULL,
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `year` year DEFAULT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `lara_doctor_awards`
--

INSERT INTO `lara_doctor_awards` (`id`, `doctor_id`, `title`, `year`, `description`, `created_at`, `updated_at`) VALUES
(1, 1, 'Best Cardiologist Award', '2022', 'Awarded by Bangladesh Medical Association for outstanding contribution to cardiac care', '2023-01-20 00:00:00', '2023-01-20 00:00:00'),
(2, 1, 'Excellence in Patient Care', '2020', 'National Healthcare Excellence Awards', '2023-01-20 00:00:00', '2023-01-20 00:00:00'),
(3, 2, 'Young Gynecologist Award', '2021', 'Obstetrical and Gynaecological Society of Bangladesh', '2023-02-01 00:00:00', '2023-02-01 00:00:00'),
(4, 2, 'Women Healthcare Champion', '2023', 'Ministry of Health & Family Welfare', '2023-02-01 00:00:00', '2023-02-01 00:00:00'),
(5, 3, 'Excellence in Cardiac Intervention', '2019', 'Cardiac Society of Bangladesh', '2023-03-15 00:00:00', '2023-03-15 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `lara_doctor_certifications`
--

CREATE TABLE `lara_doctor_certifications` (
  `id` bigint UNSIGNED NOT NULL,
  `doctor_id` bigint UNSIGNED NOT NULL,
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `issued_by` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `issued_date` date DEFAULT NULL,
  `expiry_date` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `lara_doctor_certifications`
--

INSERT INTO `lara_doctor_certifications` (`id`, `doctor_id`, `title`, `issued_by`, `issued_date`, `expiry_date`, `created_at`, `updated_at`) VALUES
(1, 1, 'Basic Life Support (BLS)', 'American Heart Association', '2023-01-15', '2026-01-15', '2023-01-20 00:00:00', '2023-01-20 00:00:00'),
(2, 1, 'Advanced Cardiac Life Support (ACLS)', 'American Heart Association', '2023-02-20', '2026-02-20', '2023-02-20 00:00:00', '2023-02-20 00:00:00'),
(3, 1, 'Board Certification in Cardiology', 'Bangladesh Cardiac Society', '2010-12-01', NULL, '2023-01-20 00:00:00', '2023-01-20 00:00:00'),
(4, 2, 'Basic Life Support (BLS)', 'American Heart Association', '2022-06-10', '2025-06-10', '2023-02-01 00:00:00', '2023-02-01 00:00:00'),
(5, 2, 'Fetal Medicine Certification', 'Fetal Medicine Foundation', '2020-08-15', NULL, '2023-02-01 00:00:00', '2023-02-01 00:00:00'),
(6, 2, 'Laparoscopic Surgery Certificate', 'OGSB Bangladesh', '2018-11-20', NULL, '2023-02-01 00:00:00', '2023-02-01 00:00:00'),
(7, 3, 'Advanced Cardiac Life Support (ACLS)', 'American Heart Association', '2022-09-12', '2025-09-12', '2023-03-15 00:00:00', '2023-03-15 00:00:00'),
(8, 3, 'Interventional Cardiology Certificate', 'Cardiac Society of India', '2014-03-25', NULL, '2023-03-15 00:00:00', '2023-03-15 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `lara_doctor_clinic`
--

CREATE TABLE `lara_doctor_clinic` (
  `id` bigint UNSIGNED NOT NULL,
  `doctor_id` bigint UNSIGNED NOT NULL,
  `clinic_id` bigint UNSIGNED NOT NULL,
  `consultation_fee` decimal(10,2) DEFAULT NULL,
  `display_on_booking` tinyint(1) NOT NULL DEFAULT '1',
  `joining_date` date DEFAULT NULL,
  `status` enum('active','inactive') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `lara_doctor_clinic`
--

INSERT INTO `lara_doctor_clinic` (`id`, `doctor_id`, `clinic_id`, `consultation_fee`, `display_on_booking`, `joining_date`, `status`, `created_at`, `updated_at`) VALUES
(6, 1, 1, 1500.00, 1, '2023-01-20', 'active', '2023-01-20 00:00:00', '2025-01-20 04:00:00'),
(7, 2, 1, 1200.00, 1, '2023-02-01', 'active', '2023-02-01 00:00:00', '2025-01-20 04:00:00'),
(8, 3, 2, 1800.00, 1, '2023-03-15', 'active', '2023-03-15 00:00:00', '2025-01-20 04:00:00'),
(9, 4, 1, NULL, 1, NULL, 'active', NULL, NULL),
(10, 4, 2, NULL, 1, NULL, 'active', NULL, NULL),
(11, 4, 3, NULL, 1, NULL, 'active', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `lara_doctor_education`
--

CREATE TABLE `lara_doctor_education` (
  `id` bigint UNSIGNED NOT NULL,
  `doctor_id` bigint UNSIGNED NOT NULL,
  `degree` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `institution` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `country` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `start_year` year DEFAULT NULL,
  `end_year` year DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `lara_doctor_education`
--

INSERT INTO `lara_doctor_education` (`id`, `doctor_id`, `degree`, `institution`, `country`, `start_year`, `end_year`, `created_at`, `updated_at`) VALUES
(1, 1, 'MBBS', 'Dhaka Medical College', 'Bangladesh', '2000', '2005', '2023-01-20 00:00:00', '2023-01-20 00:00:00'),
(2, 1, 'MD (Cardiology)', 'Bangabandhu Sheikh Mujib Medical University (BSMMU)', 'Bangladesh', '2007', '2010', '2023-01-20 00:00:00', '2023-01-20 00:00:00'),
(3, 1, 'Fellowship in Interventional Cardiology', 'National Heart Foundation', 'Bangladesh', '2011', '2012', '2023-01-20 00:00:00', '2023-01-20 00:00:00'),
(4, 2, 'MBBS', 'Sir Salimullah Medical College', 'Bangladesh', '2005', '2010', '2023-02-01 00:00:00', '2023-02-01 00:00:00'),
(5, 2, 'FCPS (Gynecology & Obstetrics)', 'Bangladesh College of Physicians and Surgeons', 'Bangladesh', '2012', '2015', '2023-02-01 00:00:00', '2023-02-01 00:00:00'),
(6, 3, 'MBBS', 'Chittagong Medical College', 'Bangladesh', '2002', '2007', '2023-03-15 00:00:00', '2023-03-15 00:00:00'),
(7, 3, 'MD (Cardiology)', 'National Institute of Cardiovascular Diseases', 'Bangladesh', '2009', '2012', '2023-03-15 00:00:00', '2023-03-15 00:00:00'),
(8, 3, 'Fellowship in Interventional Cardiology', 'Escorts Heart Institute', 'India', '2013', '2014', '2023-03-15 00:00:00', '2023-03-15 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `lara_doctor_schedules`
--

CREATE TABLE `lara_doctor_schedules` (
  `id` bigint UNSIGNED NOT NULL,
  `doctor_id` bigint UNSIGNED NOT NULL,
  `clinic_id` bigint UNSIGNED NOT NULL,
  `department_id` bigint UNSIGNED NOT NULL,
  `day_of_week` tinyint UNSIGNED DEFAULT NULL,
  `schedule_date` date DEFAULT NULL,
  `start_time` time NOT NULL,
  `end_time` time NOT NULL,
  `slot_duration_minutes` smallint UNSIGNED NOT NULL,
  `max_patients` smallint UNSIGNED DEFAULT NULL,
  `status` enum('active','inactive') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `lara_doctor_schedules`
--

INSERT INTO `lara_doctor_schedules` (`id`, `doctor_id`, `clinic_id`, `department_id`, `day_of_week`, `schedule_date`, `start_time`, `end_time`, `slot_duration_minutes`, `max_patients`, `status`, `created_at`, `updated_at`) VALUES
(6, 1, 1, 1, 6, NULL, '09:00:00', '13:00:00', 20, 12, 'active', '2023-01-25 00:00:00', '2025-01-20 04:00:00'),
(7, 1, 1, 1, 1, NULL, '09:00:00', '13:00:00', 20, 12, 'active', '2023-01-25 00:00:00', '2025-01-20 04:00:00'),
(8, 1, 1, 1, 3, NULL, '09:00:00', '13:00:00', 20, 12, 'active', '2023-01-25 00:00:00', '2025-01-20 04:00:00'),
(9, 2, 1, 6, 0, NULL, '10:00:00', '14:00:00', 15, 16, 'active', '2023-02-05 00:00:00', '2025-01-20 04:00:00'),
(10, 2, 1, 6, 2, NULL, '10:00:00', '14:00:00', 15, 16, 'active', '2023-02-05 00:00:00', '2025-01-20 04:00:00'),
(11, 2, 1, 6, 4, NULL, '10:00:00', '14:00:00', 15, 16, 'active', '2023-02-05 00:00:00', '2025-01-20 04:00:00'),
(13, 4, 1, 6, 1, NULL, '09:00:00', '14:00:00', 60, NULL, 'active', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `lara_doctor_schedule_exceptions`
--

CREATE TABLE `lara_doctor_schedule_exceptions` (
  `id` bigint UNSIGNED NOT NULL,
  `doctor_id` bigint UNSIGNED NOT NULL,
  `clinic_id` bigint UNSIGNED NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `is_available` tinyint(1) NOT NULL DEFAULT '0',
  `start_time` time DEFAULT NULL,
  `end_time` time DEFAULT NULL,
  `reason` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` enum('pending','approved','rejected') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `lara_doctor_schedule_exceptions`
--

INSERT INTO `lara_doctor_schedule_exceptions` (`id`, `doctor_id`, `clinic_id`, `start_date`, `end_date`, `is_available`, `start_time`, `end_time`, `reason`, `status`, `created_at`, `updated_at`) VALUES
(1, 1, 1, '2025-02-10', '2025-02-12', 0, NULL, NULL, 'Attending National Cardiology Conference', 'approved', '2025-01-15 02:00:00', '2025-01-16 03:00:00'),
(2, 1, 1, '2025-01-25', '2025-01-25', 1, '14:00:00', '18:00:00', 'Emergency coverage - additional hours', 'approved', '2025-01-20 04:00:00', '2025-01-21 02:00:00'),
(3, 2, 1, '2025-02-05', '2025-02-07', 0, NULL, NULL, 'Personal family matter', 'approved', '2025-01-18 03:00:00', '2025-01-19 04:00:00'),
(4, 3, 2, '2025-01-28', '2025-01-28', 1, '09:00:00', '13:00:00', 'Additional clinic day due to patient demand', 'approved', '2025-01-22 05:00:00', '2025-01-23 02:00:00'),
(5, 1, 1, '2025-03-01', '2025-03-03', 0, NULL, NULL, 'Medical workshop attendance', 'approved', '2025-01-23 08:00:00', '2025-01-23 08:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `lara_doctor_schedule_requests`
--

CREATE TABLE `lara_doctor_schedule_requests` (
  `id` bigint UNSIGNED NOT NULL,
  `doctor_id` bigint UNSIGNED NOT NULL,
  `clinic_id` bigint UNSIGNED NOT NULL,
  `schedules` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL,
  `status` enum('pending','approved','rejected') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `requested_by` bigint UNSIGNED NOT NULL,
  `processed_by` bigint UNSIGNED DEFAULT NULL,
  `processed_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ;

--
-- Dumping data for table `lara_doctor_schedule_requests`
--

INSERT INTO `lara_doctor_schedule_requests` (`id`, `doctor_id`, `clinic_id`, `schedules`, `status`, `requested_by`, `processed_by`, `processed_at`, `created_at`, `updated_at`) VALUES
(1, 1, 1, '[{\"day_of_week\": 6, \"start_time\": \"09:00:00\", \"end_time\": \"13:00:00\", \"slot_duration_minutes\": 20, \"max_patients\": 12}, {\"day_of_week\": 1, \"start_time\": \"09:00:00\", \"end_time\": \"13:00:00\", \"slot_duration_minutes\": 20, \"max_patients\": 12}]', 'approved', 2, 1, '2023-01-22 04:00:00', '2023-01-21 08:00:00', '2023-01-22 04:00:00'),
(2, 2, 1, '[{\"day_of_week\": 0, \"start_time\": \"14:00:00\", \"end_time\": \"17:00:00\", \"slot_duration_minutes\": 15, \"max_patients\": 12}]', 'pending', 3, NULL, NULL, '2025-01-22 09:30:00', '2025-01-22 09:30:00'),
(3, 3, 2, '[{\"day_of_week\": 5, \"start_time\": \"08:00:00\", \"end_time\": \"12:00:00\", \"slot_duration_minutes\": 20, \"max_patients\": 12}]', 'rejected', 9, 4, '2024-12-15 05:00:00', '2024-12-14 10:00:00', '2024-12-15 05:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `lara_failed_jobs`
--

CREATE TABLE `lara_failed_jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `uuid` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `lara_inpatient_rounds`
--

CREATE TABLE `lara_inpatient_rounds` (
  `id` bigint UNSIGNED NOT NULL,
  `admission_id` bigint UNSIGNED NOT NULL,
  `doctor_id` bigint UNSIGNED NOT NULL,
  `notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `round_date` date NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `clinic_id` bigint UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `lara_inpatient_rounds`
--

INSERT INTO `lara_inpatient_rounds` (`id`, `admission_id`, `doctor_id`, `notes`, `round_date`, `created_at`, `updated_at`, `clinic_id`) VALUES
(31, 1, 1, 'Patient stable. Chest pain resolved. ECG monitoring continues.', '2024-12-16', '2024-12-16 03:00:00', '2024-12-16 03:00:00', 1),
(32, 1, 1, 'Vitals improving. Normal sinus rhythm maintained. Discharge planning.', '2024-12-20', '2024-12-20 03:00:00', '2024-12-20 03:00:00', 1),
(33, 1, 1, 'Patient doing well. Ready for discharge tomorrow.', '2025-01-05', '2025-01-05 03:00:00', '2025-01-05 03:00:00', 1),
(34, 2, 1, 'BP controlled on IV medications. Transferred from critical care.', '2025-01-11', '2025-01-11 04:00:00', '2025-01-11 04:00:00', 1),
(35, 2, 1, 'Good progress. Oral medications started. Continue ICU monitoring.', '2025-01-13', '2025-01-13 04:00:00', '2025-01-13 04:00:00', 1),
(36, 2, 1, 'BP stable. Plan to move to general ward in 2 days.', '2025-01-16', '2025-01-16 04:00:00', '2025-01-16 04:00:00', 1),
(37, 3, 1, 'Initial assessment. Cardiac markers normal. Continue monitoring.', '2025-01-19', '2025-01-19 03:00:00', '2025-01-19 03:00:00', 1),
(38, 3, 1, 'Patient stable. No arrhythmia episodes. Consider discharge soon.', '2025-01-20', '2025-01-20 03:00:00', '2025-01-20 03:00:00', 1),
(39, 3, 1, 'Excellent progress. Medications adjusted. Discharge tomorrow.', '2025-01-22', '2025-01-22 03:00:00', '2025-01-22 03:00:00', 1);

-- --------------------------------------------------------

--
-- Table structure for table `lara_inpatient_services`
--

CREATE TABLE `lara_inpatient_services` (
  `id` bigint UNSIGNED NOT NULL,
  `admission_id` bigint UNSIGNED NOT NULL,
  `service_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `service_date` date NOT NULL,
  `quantity` int NOT NULL,
  `unit_price` decimal(10,2) NOT NULL,
  `total_price` decimal(10,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `clinic_id` bigint UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `lara_inpatient_services`
--

INSERT INTO `lara_inpatient_services` (`id`, `admission_id`, `service_name`, `service_date`, `quantity`, `unit_price`, `total_price`, `created_at`, `updated_at`, `clinic_id`) VALUES
(16, 1, 'Daily room charge - General Ward', '2024-12-15', 1, 1500.00, 1500.00, '2024-12-15 17:59:00', '2024-12-15 17:59:00', 1),
(17, 1, 'Daily room charge - General Ward', '2024-12-16', 1, 1500.00, 1500.00, '2024-12-16 17:59:00', '2024-12-16 17:59:00', 1),
(18, 1, 'Nursing care', '2024-12-15', 2, 500.00, 1000.00, '2024-12-15 14:00:00', '2024-12-15 14:00:00', 1),
(19, 1, 'ECG monitoring', '2024-12-15', 1, 800.00, 800.00, '2024-12-15 09:00:00', '2024-12-15 09:00:00', 1),
(20, 2, 'Daily ICU charge', '2025-01-10', 1, 5000.00, 5000.00, '2025-01-10 17:59:00', '2025-01-10 17:59:00', 1),
(21, 2, 'Daily ICU charge', '2025-01-11', 1, 5000.00, 5000.00, '2025-01-11 17:59:00', '2025-01-11 17:59:00', 1),
(22, 2, 'ICU nursing care', '2025-01-10', 3, 800.00, 2400.00, '2025-01-10 16:00:00', '2025-01-10 16:00:00', 1),
(23, 2, 'IV medications', '2025-01-10', 1, 2500.00, 2500.00, '2025-01-10 12:00:00', '2025-01-10 12:00:00', 1),
(24, 3, 'Daily cabin charge', '2025-01-18', 1, 3500.00, 3500.00, '2025-01-18 17:59:00', '2025-01-18 17:59:00', 1),
(25, 3, 'Daily cabin charge', '2025-01-19', 1, 3500.00, 3500.00, '2025-01-19 17:59:00', '2025-01-19 17:59:00', 1),
(26, 3, 'Nursing care', '2025-01-18', 2, 600.00, 1200.00, '2025-01-18 14:00:00', '2025-01-18 14:00:00', 1),
(27, 3, 'Cardiac monitoring', '2025-01-18', 1, 1200.00, 1200.00, '2025-01-18 08:00:00', '2025-01-18 08:00:00', 1);

-- --------------------------------------------------------

--
-- Table structure for table `lara_invoices`
--

CREATE TABLE `lara_invoices` (
  `id` bigint UNSIGNED NOT NULL,
  `clinic_id` bigint UNSIGNED NOT NULL,
  `patient_id` bigint UNSIGNED NOT NULL,
  `appointment_id` bigint UNSIGNED DEFAULT NULL,
  `visit_id` bigint UNSIGNED DEFAULT NULL,
  `admission_id` bigint UNSIGNED DEFAULT NULL,
  `invoice_number` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `invoice_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `subtotal` decimal(10,2) NOT NULL,
  `discount` decimal(10,2) NOT NULL DEFAULT '0.00',
  `tax` decimal(10,2) NOT NULL DEFAULT '0.00',
  `total_amount` decimal(10,2) NOT NULL,
  `status` enum('unpaid','partial','paid','cancelled') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'unpaid',
  `state` enum('draft','finalized') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'draft',
  `issued_at` datetime DEFAULT NULL,
  `finalized_at` datetime DEFAULT NULL,
  `finalized_by` bigint UNSIGNED DEFAULT NULL,
  `created_by` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `lara_invoices`
--

INSERT INTO `lara_invoices` (`id`, `clinic_id`, `patient_id`, `appointment_id`, `visit_id`, `admission_id`, `invoice_number`, `invoice_type`, `subtotal`, `discount`, `tax`, `total_amount`, `status`, `state`, `issued_at`, `finalized_at`, `finalized_by`, `created_by`, `created_at`, `updated_at`, `deleted_at`) VALUES
(584, 1, 8, 336, 336, NULL, 'INV-20260124-VYPQRA', 'consultation', 1200.00, 0.00, 0.00, 1200.00, 'paid', 'finalized', '2026-01-24 17:09:14', '2026-01-24 17:09:14', 5, 5, NULL, NULL, NULL),
(585, 1, 8, 336, 336, NULL, 'INV-20260124-VQIZQL', 'procedure', 300.00, 50.00, 5.00, 262.50, 'paid', 'finalized', '2026-01-24 17:10:54', '2026-01-24 17:10:54', 5, 5, NULL, NULL, NULL),
(586, 1, 8, NULL, NULL, NULL, 'INV-20260125-4A8EQ5', 'lab', 600.00, 0.00, 0.00, 600.00, 'paid', 'finalized', '2026-01-25 01:30:39', '2026-01-25 01:30:39', 2, 2, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `lara_invoice_items`
--

CREATE TABLE `lara_invoice_items` (
  `id` bigint UNSIGNED NOT NULL,
  `invoice_id` bigint UNSIGNED NOT NULL,
  `item_type` enum('consultation','lab','medicine','bed','service') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `reference_id` bigint UNSIGNED DEFAULT NULL,
  `description` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `quantity` int NOT NULL,
  `unit_price` decimal(10,2) NOT NULL,
  `total_price` decimal(10,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `clinic_id` bigint UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `lara_invoice_items`
--

INSERT INTO `lara_invoice_items` (`id`, `invoice_id`, `item_type`, `reference_id`, `description`, `quantity`, `unit_price`, `total_price`, `created_at`, `updated_at`, `clinic_id`) VALUES
(774, 584, 'consultation', 326, 'Consultation Fee (Initial)', 1, 1200.00, 1200.00, NULL, NULL, 1),
(775, 585, 'service', NULL, 'Medical Assistant Consultation', 1, 300.00, 300.00, NULL, NULL, 1),
(776, 586, 'lab', 7, 'Complete Blood Count (CBC)', 1, 600.00, 600.00, NULL, NULL, 1);

-- --------------------------------------------------------

--
-- Table structure for table `lara_jobs`
--

CREATE TABLE `lara_jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `queue` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` tinyint UNSIGNED NOT NULL,
  `reserved_at` int UNSIGNED DEFAULT NULL,
  `available_at` int UNSIGNED NOT NULL,
  `created_at` int UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `lara_job_batches`
--

CREATE TABLE `lara_job_batches` (
  `id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `cancelled_at` int DEFAULT NULL,
  `created_at` int NOT NULL,
  `finished_at` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `lara_lab_tests`
--

CREATE TABLE `lara_lab_tests` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `category` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `normal_range` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `status` enum('active','inactive') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `lara_lab_tests`
--

INSERT INTO `lara_lab_tests` (`id`, `name`, `category`, `description`, `normal_range`, `price`, `status`, `created_at`, `updated_at`) VALUES
(7, 'Complete Blood Count (CBC)', 'Hematology', 'Blood cell count and analysis', 'Varies by parameter', 600.00, 'active', '2023-01-10 00:00:00', '2025-01-20 04:00:00'),
(8, 'Lipid Profile', 'Biochemistry', 'Cholesterol and triglycerides test', 'Total Cholesterol < 200 mg/dL', 800.00, 'active', '2023-01-10 00:00:00', '2025-01-20 04:00:00'),
(9, 'Blood Sugar (Fasting)', 'Biochemistry', 'Fasting blood glucose level', '70-100 mg/dL', 250.00, 'active', '2023-01-10 00:00:00', '2025-01-20 04:00:00'),
(10, 'Liver Function Test (LFT)', 'Biochemistry', 'Liver enzyme assessment', 'Varies by parameter', 1200.00, 'active', '2023-01-10 00:00:00', '2025-01-20 04:00:00'),
(11, 'Kidney Function Test', 'Biochemistry', 'Creatinine and urea test', 'Creatinine: 0.7-1.3 mg/dL', 900.00, 'active', '2023-01-10 00:00:00', '2025-01-20 04:00:00'),
(12, 'Thyroid Profile (T3, T4, TSH)', 'Endocrinology', 'Thyroid hormone levels', 'TSH: 0.4-4.0 mIU/L', 1500.00, 'active', '2023-01-10 00:00:00', '2025-01-20 04:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `lara_lab_test_orders`
--

CREATE TABLE `lara_lab_test_orders` (
  `id` bigint UNSIGNED NOT NULL,
  `appointment_id` bigint UNSIGNED DEFAULT NULL,
  `doctor_id` bigint UNSIGNED DEFAULT NULL,
  `patient_id` bigint UNSIGNED NOT NULL,
  `lab_test_id` bigint UNSIGNED DEFAULT NULL,
  `order_date` date NOT NULL,
  `status` enum('pending','completed','cancelled') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `invoice_id` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `clinic_id` bigint UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `lara_lab_test_orders`
--

INSERT INTO `lara_lab_test_orders` (`id`, `appointment_id`, `doctor_id`, `patient_id`, `lab_test_id`, `order_date`, `status`, `invoice_id`, `created_at`, `updated_at`, `deleted_at`, `clinic_id`) VALUES
(129, 1, 1, 1, 2, '2024-01-08', 'completed', NULL, '2024-01-08 03:45:00', '2024-01-09 08:00:00', NULL, 1),
(130, 1, 1, 1, 3, '2024-01-08', 'completed', NULL, '2024-01-08 03:45:00', '2024-01-09 08:00:00', NULL, 1),
(131, 3, 1, 3, 1, '2024-01-15', 'completed', NULL, '2024-01-15 04:10:00', '2024-01-16 05:00:00', NULL, 1),
(132, 5, 1, 5, 5, '2024-01-22', 'completed', NULL, '2024-01-22 04:25:00', '2024-01-23 09:00:00', NULL, 1),
(133, 5, 1, 5, 3, '2024-01-22', 'completed', NULL, '2024-01-22 04:25:00', '2024-01-23 09:00:00', NULL, 1),
(134, 8, 1, 8, 1, '2024-02-05', 'completed', NULL, '2024-02-05 03:50:00', '2024-02-06 07:00:00', NULL, 1),
(135, 8, 1, 8, 6, '2024-02-05', 'completed', NULL, '2024-02-05 03:50:00', '2024-02-07 10:00:00', NULL, 1),
(136, 21, 1, 1, 2, '2025-01-06', 'completed', NULL, '2025-01-06 03:50:00', '2025-01-07 08:00:00', NULL, 1),
(137, NULL, NULL, 8, 7, '2026-01-24', 'completed', 586, NULL, NULL, NULL, 1),
(138, 334, 4, 8, 7, '2026-01-25', 'pending', NULL, NULL, NULL, NULL, 1),
(139, NULL, 4, 7, 8, '2026-01-25', 'pending', NULL, NULL, NULL, NULL, 1);

-- --------------------------------------------------------

--
-- Table structure for table `lara_lab_test_results`
--

CREATE TABLE `lara_lab_test_results` (
  `id` bigint UNSIGNED NOT NULL,
  `lab_test_order_id` bigint UNSIGNED NOT NULL,
  `lab_test_id` bigint UNSIGNED NOT NULL,
  `result_value` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `unit` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `reference_range` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `remarks` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `pdf_path` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `reported_by` bigint UNSIGNED NOT NULL,
  `reported_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `clinic_id` bigint UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `lara_lab_test_results`
--

INSERT INTO `lara_lab_test_results` (`id`, `lab_test_order_id`, `lab_test_id`, `result_value`, `unit`, `reference_range`, `remarks`, `pdf_path`, `reported_by`, `reported_at`, `created_at`, `updated_at`, `clinic_id`) VALUES
(128, 137, 7, 'Normal', NULL, NULL, 'Normal values', 'lab_results/Sgrcd7ZEBAmvrjW9PAyBssI6IvzNHNRh8OyD41QA.pdf', 2, '2026-01-24 19:37:23', NULL, NULL, 1);

-- --------------------------------------------------------

--
-- Table structure for table `lara_medicines`
--

CREATE TABLE `lara_medicines` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `generic_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `manufacturer` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `strength` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `dosage_form` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `status` enum('active','inactive') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `lara_medicines`
--

INSERT INTO `lara_medicines` (`id`, `name`, `generic_name`, `manufacturer`, `strength`, `dosage_form`, `price`, `status`, `created_at`, `updated_at`) VALUES
(8, 'Napa', 'Paracetamol', 'Beximco Pharmaceuticals', '500mg', 'Tablet', 1.50, 'active', '2023-01-10 00:00:00', '2025-01-20 04:00:00'),
(9, 'Amodis', 'Amlodipine', 'Square Pharmaceuticals', '5mg', 'Tablet', 6.00, 'active', '2023-01-10 00:00:00', '2025-01-20 04:00:00'),
(10, 'Atorva', 'Atorvastatin', 'Incepta Pharmaceuticals', '10mg', 'Tablet', 12.00, 'active', '2023-01-10 00:00:00', '2025-01-20 04:00:00'),
(11, 'Losectil', 'Omeprazole', 'Square Pharmaceuticals', '20mg', 'Capsule', 4.00, 'active', '2023-01-10 00:00:00', '2025-01-20 04:00:00'),
(12, 'Monas', 'Montelukast', 'Beximco Pharmaceuticals', '10mg', 'Tablet', 15.00, 'active', '2023-01-10 00:00:00', '2025-01-20 04:00:00'),
(13, 'Seclo', 'Esomeprazole', 'Square Pharmaceuticals', '40mg', 'Capsule', 8.00, 'active', '2023-01-10 00:00:00', '2025-01-20 04:00:00'),
(14, 'Zimax', 'Azithromycin', 'Incepta Pharmaceuticals', '500mg', 'Tablet', 25.00, 'active', '2023-01-10 00:00:00', '2025-01-20 04:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `lara_medicine_batches`
--

CREATE TABLE `lara_medicine_batches` (
  `id` bigint UNSIGNED NOT NULL,
  `clinic_id` bigint UNSIGNED NOT NULL,
  `medicine_id` bigint UNSIGNED NOT NULL,
  `batch_number` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiry_date` date NOT NULL,
  `quantity_in_stock` int NOT NULL,
  `purchase_price` decimal(10,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `lara_medicine_batches`
--

INSERT INTO `lara_medicine_batches` (`id`, `clinic_id`, `medicine_id`, `batch_number`, `expiry_date`, `quantity_in_stock`, `purchase_price`, `created_at`, `updated_at`) VALUES
(22, 1, 1, 'NAPA-2024-001', '2026-12-31', 5000, 1.20, '2024-01-05 00:00:00', '2025-01-20 04:00:00'),
(23, 1, 2, 'AMOD-2024-001', '2026-06-30', 2000, 5.00, '2024-01-05 00:00:00', '2025-01-20 04:00:00'),
(24, 1, 3, 'ATOR-2024-001', '2026-09-30', 1500, 10.00, '2024-01-05 00:00:00', '2025-01-20 04:00:00'),
(25, 1, 4, 'LOSE-2024-001', '2026-11-30', 3000, 3.50, '2024-01-05 00:00:00', '2025-01-20 04:00:00'),
(26, 1, 5, 'MONA-2024-001', '2026-08-31', 1000, 13.00, '2024-01-05 00:00:00', '2025-01-20 04:00:00'),
(27, 1, 6, 'SECL-2024-001', '2026-10-31', 2500, 7.00, '2024-01-05 00:00:00', '2025-01-20 04:00:00'),
(28, 1, 7, 'ZIMA-2024-001', '2026-07-31', 800, 22.00, '2024-01-05 00:00:00', '2025-01-20 04:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `lara_migrations`
--

CREATE TABLE `lara_migrations` (
  `id` int UNSIGNED NOT NULL,
  `migration` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `lara_notifications`
--

CREATE TABLE `lara_notifications` (
  `id` char(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `notifiable_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `notifiable_id` bigint UNSIGNED NOT NULL,
  `data` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `read_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `lara_notifications`
--

INSERT INTO `lara_notifications` (`id`, `type`, `notifiable_type`, `notifiable_id`, `data`, `read_at`, `created_at`, `updated_at`) VALUES
('375f56ae-bf1b-4b8d-aba5-998534cf932b', 'App\\Notifications\\NewLabOrderNotification', 'App\\Models\\User', 6, '{\"title\":\"New Lab Order\",\"message\":\"New lab order for Farida Yasmin (Test: Complete Blood Count (CBC))\",\"link\":\"http:\\/\\/127.0.0.1:8000\\/lab\\/order\\/137\",\"type\":\"info\"}', NULL, '2026-01-24 11:25:03', '2026-01-24 11:25:03'),
('5644a1a6-0182-41ab-abbb-bdc596ca6228', 'App\\Notifications\\NewLabOrderNotification', 'App\\Models\\User', 6, '{\"title\":\"New Lab Order\",\"message\":\"New lab order for Habibur Rahman (Test: Lipid Profile)\",\"link\":\"http:\\/\\/127.0.0.1:8000\\/lab\\/order\\/139\",\"type\":\"info\"}', NULL, '2026-01-24 19:28:59', '2026-01-24 19:28:59'),
('629c3278-4d25-43a9-bbb6-be17d103c7d3', 'App\\Notifications\\AppointmentBookedNotification', 'App\\Models\\User', 11, '{\"title\":\"New Appointment Booked\",\"message\":\"Appointment #336 scheduled for Jan 26, 2026 00:00\",\"link\":\"http:\\/\\/127.0.0.1:8000\\/appointments\\/336\",\"type\":\"success\"}', NULL, '2026-01-24 11:03:48', '2026-01-24 11:03:48'),
('b4fce7ca-12d5-4a1f-a307-3513fb6f7214', 'App\\Notifications\\NewLabOrderNotification', 'App\\Models\\User', 6, '{\"title\":\"New Lab Order\",\"message\":\"New lab order for Farida Yasmin (Test: Complete Blood Count (CBC))\",\"link\":\"http:\\/\\/127.0.0.1:8000\\/lab\\/order\\/138\",\"type\":\"info\"}', NULL, '2026-01-24 19:27:43', '2026-01-24 19:27:43');

-- --------------------------------------------------------

--
-- Table structure for table `lara_nursing_notes`
--

CREATE TABLE `lara_nursing_notes` (
  `id` bigint UNSIGNED NOT NULL,
  `admission_id` bigint UNSIGNED NOT NULL,
  `nurse_id` bigint UNSIGNED NOT NULL,
  `notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `recorded_at` datetime NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `clinic_id` bigint UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `lara_nursing_notes`
--

INSERT INTO `lara_nursing_notes` (`id`, `admission_id`, `nurse_id`, `notes`, `recorded_at`, `created_at`, `updated_at`, `clinic_id`) VALUES
(31, 1, 5, 'Patient admitted. Resting comfortably. Vital signs stable.', '2024-12-15 14:00:00', '2024-12-15 08:00:00', '2024-12-15 08:00:00', 1),
(32, 1, 5, 'Night shift - patient slept well. BP 125/80. HR 72 bpm.', '2024-12-16 06:00:00', '2024-12-16 00:00:00', '2024-12-16 00:00:00', 1),
(33, 1, 5, 'Medications administered as per schedule. No complaints.', '2024-12-18 10:00:00', '2024-12-18 04:00:00', '2024-12-18 04:00:00', 1),
(34, 2, 5, 'ICU admission. Continuous BP monitoring. IV lines secured.', '2025-01-10 15:00:00', '2025-01-10 09:00:00', '2025-01-10 09:00:00', 1),
(35, 2, 5, 'BP medications titrated. Patient alert and oriented.', '2025-01-10 20:00:00', '2025-01-10 14:00:00', '2025-01-10 14:00:00', 1),
(36, 2, 5, 'Stable night. BP readings improving. Family counseled.', '2025-01-11 07:00:00', '2025-01-11 01:00:00', '2025-01-11 01:00:00', 1),
(37, 3, 5, 'Patient admitted to private cabin. Comfortable. Family present.', '2025-01-18 10:00:00', '2025-01-18 04:00:00', '2025-01-18 04:00:00', 1),
(38, 3, 5, 'All medications given. Patient ambulating well. No issues.', '2025-01-19 08:00:00', '2025-01-19 02:00:00', '2025-01-19 02:00:00', 1),
(39, 3, 5, 'Patient ready for discharge. Discharge instructions given.', '2025-01-22 14:00:00', '2025-01-22 08:00:00', '2025-01-22 08:00:00', 1);

-- --------------------------------------------------------

--
-- Table structure for table `lara_password_reset_tokens`
--

CREATE TABLE `lara_password_reset_tokens` (
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `lara_patients`
--

CREATE TABLE `lara_patients` (
  `id` bigint UNSIGNED NOT NULL,
  `clinic_id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `patient_code` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `date_of_birth` date DEFAULT NULL,
  `age` int UNSIGNED DEFAULT NULL,
  `gender` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `blood_group` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `profile_photo` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `emergency_contact_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `emergency_contact_phone` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` enum('active','inactive') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `lara_patients`
--

INSERT INTO `lara_patients` (`id`, `clinic_id`, `user_id`, `patient_code`, `name`, `date_of_birth`, `age`, `gender`, `blood_group`, `phone`, `email`, `profile_photo`, `address`, `emergency_contact_name`, `emergency_contact_phone`, `status`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 1, NULL, 'PAT-2024-0001', 'Md. Kamal Hossain', '1975-03-12', 50, 'Male', 'A+', '+8801711-111111', 'kamal.hossain@email.com', 'assets/img/profiles/avatar-01.jpg', 'House 12, Road 5, Mirpur-10, Dhaka-1216', 'Rahima Begum', '+8801711-111112', 'active', '2024-01-05 02:00:00', '2025-01-20 04:00:00', NULL),
(2, 1, NULL, 'PAT-2024-0002', 'Shamima Akter', '1988-07-22', 36, 'Female', 'B+', '+8801811-222222', 'shamima.akter@email.com', 'assets/img/profiles/avatar-02.jpg', 'Flat 3B, Uttara Sector 4, Dhaka-1230', 'Abdur Rahman', '+8801811-222223', 'active', '2024-01-10 03:00:00', '2025-01-20 04:00:00', NULL),
(3, 1, NULL, 'PAT-2024-0003', 'Rahim Uddin', '1960-11-05', 64, 'Male', 'O+', '+8801911-333333', NULL, 'assets/img/profiles/avatar-03.jpg', 'Mohammadpur Housing, Dhaka-1207', 'Salma Khatun', '+8801911-333334', 'active', '2024-01-15 04:00:00', '2025-01-20 04:00:00', NULL),
(4, 1, NULL, 'PAT-2024-0004', 'Nasrin Jahan', '1992-04-18', 32, 'Female', 'AB+', '+8801611-444444', 'nasrin.jahan@email.com', 'assets/img/profiles/avatar-04.jpg', 'Banani DOHS, Road 11, Dhaka-1213', 'Md. Jamal', '+8801611-444445', 'active', '2024-02-01 02:30:00', '2025-01-20 04:00:00', NULL),
(5, 1, NULL, 'PAT-2024-0005', 'Abdul Jabbar', '1955-09-30', 69, 'Male', 'B-', '+8801511-555555', NULL, 'assets/img/profiles/avatar-05.jpg', 'Lalmatia Block C, Dhaka-1207', 'Anwara Begum', '+8801511-555556', 'active', '2024-02-10 05:00:00', '2025-01-20 04:00:00', NULL),
(6, 1, NULL, 'PAT-2024-0006', 'Rozina Khatun', '1998-12-25', 26, 'Female', 'A-', '+8801411-666666', 'rozina.k@email.com', 'assets/img/profiles/avatar-06.jpg', 'Azimpur Colony, Dhaka-1205', 'Faruk Ahmed', '+8801411-666667', 'active', '2024-03-05 03:30:00', '2025-01-20 04:00:00', NULL),
(7, 1, NULL, 'PAT-2024-0007', 'Habibur Rahman', '1970-06-14', 54, 'Male', 'O-', '+8801311-777777', NULL, 'assets/img/profiles/avatar-07.jpg', 'Rampura, Dhaka-1219', 'Halima Begum', '+8801311-777778', 'active', '2024-03-20 04:15:00', '2025-01-20 04:00:00', NULL),
(8, 1, NULL, 'PAT-2024-0008', 'Farida Yasmin', '1985-02-08', 40, 'Female', 'B+', '+8801211-888888', 'farida.y@email.com', 'assets/img/profiles/avatar-08.jpg', 'Gulshan-2, Dhaka-1212', 'Kamrul Hassan', '+8801211-888889', 'active', '2024-04-01 02:45:00', '2025-01-20 04:00:00', NULL),
(9, 2, NULL, 'PAT-2024-0009', 'Azizul Haque', '1978-08-19', 46, 'Male', 'A+', '+8801811-999999', NULL, 'assets/img/profiles/avatar-09.jpg', 'Agrabad, Chittagong-4100', 'Razia Sultana', '+8801811-999990', 'active', '2024-04-15 03:00:00', '2025-01-20 04:00:00', NULL),
(10, 2, NULL, 'PAT-2024-0010', 'Sabina Ahmed', '1995-05-03', 29, 'Female', 'AB-', '+8801711-101010', 'sabina.a@email.com', 'assets/img/profiles/avatar-10.jpg', 'GEC Circle, Chittagong-4000', 'Jalal Ahmed', '+8801711-101011', 'active', '2024-05-01 04:30:00', '2025-01-20 04:00:00', NULL),
(151, 1, NULL, 'PAT-2024-0011', 'Monir Hossain', '1982-10-12', 42, 'Male', 'O+', '+8801611-111222', NULL, 'assets/img/profiles/avatar-01.jpg', 'Shyamoli, Dhaka-1207', 'Hasina Begum', '+8801611-111223', 'active', '2024-06-10 03:00:00', '2025-01-20 04:00:00', NULL),
(152, 1, NULL, 'PAT-2024-0012', 'Tahera Khatun', '1990-01-25', 35, 'Female', 'A+', '+8801711-222333', 'tahera@email.com', 'assets/img/profiles/avatar-02.jpg', 'Bashundhara R/A, Dhaka-1229', 'Shahin Ahmed', '+8801711-222334', 'active', '2024-07-05 02:30:00', '2025-01-20 04:00:00', NULL),
(153, 1, NULL, 'PAT-2024-0013', 'Hafiz Uddin', '1968-05-30', 56, 'Male', 'B+', '+8801811-333444', NULL, 'assets/img/profiles/avatar-03.jpg', 'Motijheel, Dhaka-1000', 'Amina Khatun', '+8801811-333445', 'active', '2024-08-12 04:00:00', '2025-01-20 04:00:00', NULL),
(154, 1, NULL, 'PAT-2024-0014', 'Rupa Begum', '2000-03-15', 24, 'Female', 'AB+', '+8801911-444555', 'rupa.b@email.com', 'assets/img/profiles/avatar-04.jpg', 'Badda, Dhaka-1212', 'Sumon Khan', '+8801911-444556', 'active', '2024-09-20 05:30:00', '2025-01-20 04:00:00', NULL),
(155, 1, NULL, 'PAT-2024-0015', 'Karim Sheikh', '1958-11-08', 66, 'Male', 'A-', '+8801511-555666', NULL, 'assets/img/profiles/avatar-05.jpg', 'Tejgaon, Dhaka-1215', 'Begum Ara', '+8801511-555667', 'active', '2024-10-05 03:45:00', '2025-01-20 04:00:00', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `lara_patient_allergies`
--

CREATE TABLE `lara_patient_allergies` (
  `id` bigint UNSIGNED NOT NULL,
  `patient_id` bigint UNSIGNED NOT NULL,
  `allergy_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `severity` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `lara_patient_allergies`
--

INSERT INTO `lara_patient_allergies` (`id`, `patient_id`, `allergy_name`, `severity`, `notes`, `created_at`, `updated_at`) VALUES
(38, 1, 'Penicillin', 'Severe', 'Anaphylactic reaction reported in 2015', '2024-01-05 02:00:00', '2024-01-05 02:00:00'),
(39, 3, 'Aspirin', 'Moderate', 'Causes stomach upset and skin rash', '2024-01-15 03:15:00', '2024-01-15 03:15:00'),
(40, 5, 'Sulfa drugs', 'Severe', 'Severe allergic reaction with breathing difficulty', '2024-02-10 05:00:00', '2024-02-10 05:00:00'),
(41, 8, 'Iodine contrast', 'Moderate', 'Reaction during CT scan in 2022', '2024-04-01 02:45:00', '2024-04-01 02:45:00'),
(42, 12, 'Latex', 'Mild', 'Skin irritation with latex gloves', '2024-07-05 02:30:00', '2024-07-05 02:30:00');

-- --------------------------------------------------------

--
-- Table structure for table `lara_patient_complaints`
--

CREATE TABLE `lara_patient_complaints` (
  `id` bigint UNSIGNED NOT NULL,
  `clinic_id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `lara_patient_complaints`
--

INSERT INTO `lara_patient_complaints` (`id`, `clinic_id`, `name`, `created_at`, `updated_at`) VALUES
(21, 1, 'Fever', '2023-01-10 00:00:00', '2025-01-20 04:00:00'),
(22, 1, 'Headache', '2023-01-10 00:00:00', '2025-01-20 04:00:00'),
(23, 1, 'Cough', '2023-01-10 00:00:00', '2025-01-20 04:00:00'),
(24, 1, 'Chest Pain', '2023-01-10 00:00:00', '2025-01-20 04:00:00'),
(25, 1, 'Abdominal Pain', '2023-01-10 00:00:00', '2025-01-20 04:00:00'),
(26, 1, 'Dizziness', '2023-01-10 00:00:00', '2025-01-20 04:00:00'),
(27, 1, 'Fatigue', '2023-01-10 00:00:00', '2025-01-20 04:00:00'),
(28, 1, 'Nausea', '2023-01-10 00:00:00', '2025-01-20 04:00:00'),
(29, 1, 'Joint Pain', '2023-01-10 00:00:00', '2025-01-20 04:00:00'),
(30, 1, 'Back Pain', '2023-01-10 00:00:00', '2025-01-20 04:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `lara_patient_medical_history`
--

CREATE TABLE `lara_patient_medical_history` (
  `id` bigint UNSIGNED NOT NULL,
  `patient_id` bigint UNSIGNED NOT NULL,
  `condition_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `diagnosed_date` date DEFAULT NULL,
  `status` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `lara_patient_medical_history`
--

INSERT INTO `lara_patient_medical_history` (`id`, `patient_id`, `condition_name`, `diagnosed_date`, `status`, `notes`, `created_at`, `updated_at`) VALUES
(151, 1, 'Hypertension', '2018-03-15', 'Ongoing', 'Currently on medication, well controlled', '2024-01-05 02:00:00', '2024-01-05 02:00:00'),
(152, 1, 'Type 2 Diabetes Mellitus', '2020-07-22', 'Ongoing', 'Diet and medication controlled, HbA1c in target', '2024-01-05 02:00:00', '2024-01-05 02:00:00'),
(153, 3, 'Coronary Artery Disease', '2022-11-10', 'Ongoing', 'Post angioplasty, on dual antiplatelet therapy', '2024-01-15 03:15:00', '2024-01-15 03:15:00'),
(154, 5, 'Chronic Kidney Disease Stage 2', '2021-05-18', 'Ongoing', 'Regular monitoring, creatinine stable', '2024-02-10 05:00:00', '2024-02-10 05:00:00'),
(155, 7, 'Bronchial Asthma', '2015-08-30', 'Ongoing', 'Well controlled with inhalers', '2024-03-20 04:15:00', '2024-03-20 04:15:00'),
(156, 8, 'Hypothyroidism', '2019-12-05', 'Ongoing', 'On thyroxine replacement, TSH normal', '2024-04-01 02:45:00', '2024-04-01 02:45:00');

-- --------------------------------------------------------

--
-- Table structure for table `lara_patient_vitals`
--

CREATE TABLE `lara_patient_vitals` (
  `id` bigint UNSIGNED NOT NULL,
  `patient_id` bigint UNSIGNED NOT NULL,
  `visit_id` bigint UNSIGNED NOT NULL,
  `admission_id` bigint UNSIGNED DEFAULT NULL,
  `inpatient_round_id` bigint UNSIGNED DEFAULT NULL,
  `blood_pressure` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `heart_rate` int DEFAULT NULL,
  `temperature` decimal(4,1) DEFAULT NULL,
  `spo2` decimal(5,2) DEFAULT NULL,
  `respiratory_rate` int DEFAULT NULL,
  `weight` decimal(5,2) DEFAULT NULL,
  `height` decimal(5,2) DEFAULT NULL,
  `bmi` decimal(5,2) DEFAULT NULL,
  `recorded_by` bigint UNSIGNED NOT NULL,
  `recorded_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `lara_patient_vitals`
--

INSERT INTO `lara_patient_vitals` (`id`, `patient_id`, `visit_id`, `admission_id`, `inpatient_round_id`, `blood_pressure`, `heart_rate`, `temperature`, `spo2`, `respiratory_rate`, `weight`, `height`, `bmi`, `recorded_by`, `recorded_at`, `created_at`, `updated_at`) VALUES
(303, 1, 1, NULL, NULL, '130/85', 78, 98.4, 98.00, 16, 75.50, 170.00, 26.13, 5, '2024-01-08 02:55:00', '2024-01-08 02:55:00', '2024-01-08 02:55:00'),
(304, 2, 2, NULL, NULL, '115/75', 82, 98.2, 99.00, 18, 68.00, 162.00, 25.91, 5, '2024-01-10 03:50:00', '2024-01-10 03:50:00', '2024-01-10 03:50:00'),
(305, 3, 3, NULL, NULL, '125/80', 88, 98.6, 97.00, 20, 82.00, 175.00, 26.78, 5, '2024-01-15 03:15:00', '2024-01-15 03:15:00', '2024-01-15 03:15:00'),
(306, 4, 4, NULL, NULL, '110/70', 76, 98.3, 98.50, 16, 62.00, 158.00, 24.84, 5, '2024-01-17 04:10:00', '2024-01-17 04:10:00', '2024-01-17 04:10:00'),
(307, 5, 5, NULL, NULL, '150/95', 85, 98.5, 96.00, 18, 88.50, 168.00, 31.38, 5, '2024-01-22 03:35:00', '2024-01-22 03:35:00', '2024-01-22 03:35:00'),
(308, 6, 6, NULL, NULL, '118/76', 74, 98.1, 99.00, 16, 58.00, 160.00, 22.66, 5, '2024-01-24 04:25:00', '2024-01-24 04:25:00', '2024-01-24 04:25:00'),
(309, 7, 7, NULL, NULL, '125/80', 72, 98.4, 98.00, 16, 76.00, 172.00, 25.69, 5, '2024-01-29 03:55:00', '2024-01-29 03:55:00', '2024-01-29 03:55:00'),
(310, 8, 8, NULL, NULL, '128/82', 92, 98.6, 97.50, 18, 70.00, 165.00, 25.71, 5, '2024-02-05 02:55:00', '2024-02-05 02:55:00', '2024-02-05 02:55:00'),
(311, 1, 21, NULL, NULL, '122/78', 75, 98.3, 98.50, 16, 74.00, 170.00, 25.61, 5, '2025-01-06 02:55:00', '2025-01-06 02:55:00', '2025-01-06 02:55:00'),
(312, 2, 22, NULL, NULL, '120/75', 80, 98.4, 99.00, 18, 72.00, 162.00, 27.43, 5, '2025-01-07 03:55:00', '2025-01-07 03:55:00', '2025-01-07 03:55:00'),
(313, 3, 23, NULL, NULL, '132/84', 86, 98.5, 97.00, 18, 83.00, 175.00, 27.10, 5, '2025-01-13 03:15:00', '2025-01-13 03:15:00', '2025-01-13 03:15:00'),
(314, 4, 24, NULL, NULL, '112/72', 78, 98.2, 98.00, 16, 63.50, 158.00, 25.44, 5, '2025-01-14 04:10:00', '2025-01-14 04:10:00', '2025-01-14 04:10:00'),
(315, 5, 25, NULL, NULL, '120/78', 74, 98.4, 98.50, 16, 87.00, 168.00, 30.83, 5, '2025-01-20 02:55:00', '2025-01-20 02:55:00', '2025-01-20 02:55:00'),
(316, 6, 26, NULL, NULL, '115/74', 72, 98.1, 99.00, 16, 59.00, 160.00, 23.05, 5, '2025-01-21 03:55:00', '2025-01-21 03:55:00', '2025-01-21 03:55:00'),
(317, 7, 27, NULL, NULL, '124/79', 76, 98.3, 98.00, 16, 75.50, 172.00, 25.52, 5, '2025-01-22 03:15:00', '2025-01-22 03:15:00', '2025-01-22 03:15:00'),
(318, 8, 28, NULL, NULL, '122/80', 78, 98.2, 98.50, 16, 71.00, 165.00, 26.08, 5, '2025-01-23 04:10:00', '2025-01-23 04:10:00', '2025-01-23 04:10:00'),
(319, 9, 29, NULL, NULL, '118/76', 74, 98.4, 99.00, 16, 78.00, 173.00, 26.07, 5, '2025-01-24 02:55:00', '2025-01-24 02:55:00', '2025-01-24 02:55:00'),
(320, 8, 336, NULL, NULL, '115/70', 65, 36.6, 78.00, 17, 65.00, 165.00, 23.90, 4, '2026-01-24 11:14:28', '2026-01-24 11:14:28', '2026-01-24 11:14:28');

-- --------------------------------------------------------

--
-- Table structure for table `lara_payments`
--

CREATE TABLE `lara_payments` (
  `id` bigint UNSIGNED NOT NULL,
  `invoice_id` bigint UNSIGNED NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `payment_method` enum('cash','card','mobile_banking','bank_transfer') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `transaction_reference` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `paid_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `received_by` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `clinic_id` bigint UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `lara_payments`
--

INSERT INTO `lara_payments` (`id`, `invoice_id`, `amount`, `payment_method`, `transaction_reference`, `paid_at`, `received_by`, `created_at`, `updated_at`, `deleted_at`, `clinic_id`) VALUES
(577, 1, 2900.00, 'cash', NULL, '2024-01-08 04:15:00', 6, '2024-01-08 04:15:00', '2024-01-08 04:15:00', NULL, 1),
(578, 2, 1200.00, 'card', 'TXN-2024-001', '2024-01-10 05:05:00', 6, '2024-01-10 05:05:00', '2024-01-10 05:05:00', NULL, 1),
(579, 3, 2275.00, 'mobile_banking', 'BKASH-2024-001', '2024-01-15 04:45:00', 6, '2024-01-15 04:45:00', '2024-01-15 04:45:00', NULL, 1),
(580, 4, 1200.00, 'card', 'TXN-2024-002', '2024-01-17 05:20:00', 6, '2024-01-17 05:20:00', '2024-01-17 05:20:00', NULL, 1),
(581, 5, 2900.00, 'cash', NULL, '2024-01-22 05:00:00', 6, '2024-01-22 05:00:00', '2024-01-22 05:00:00', NULL, 1),
(582, 6, 1200.00, 'mobile_banking', 'NAGAD-2024-001', '2024-01-24 05:35:00', 6, '2024-01-24 05:35:00', '2024-01-24 05:35:00', NULL, 1),
(583, 7, 800.00, 'cash', NULL, '2024-01-29 05:00:00', 6, '2024-01-29 05:00:00', '2024-01-29 05:00:00', NULL, 1),
(584, 8, 3200.00, 'bank_transfer', 'BNK-2024-001', '2024-02-05 04:25:00', 6, '2024-02-05 04:25:00', '2024-02-05 04:25:00', NULL, 1),
(585, 9, 1500.00, 'card', 'TXN-2024-003', '2024-02-12 04:40:00', 6, '2024-02-12 04:40:00', '2024-02-12 04:40:00', NULL, 1),
(586, 10, 1200.00, 'cash', NULL, '2024-02-14 05:15:00', 6, '2024-02-14 05:15:00', '2024-02-14 05:15:00', NULL, 1),
(587, 11, 800.00, 'cash', NULL, '2024-02-19 04:45:00', 6, '2024-02-19 04:45:00', '2024-02-19 04:45:00', NULL, 1),
(588, 12, 1200.00, 'mobile_banking', 'BKASH-2024-002', '2024-02-21 05:30:00', 6, '2024-02-21 05:30:00', '2024-02-21 05:30:00', NULL, 1),
(589, 13, 1500.00, 'card', 'TXN-2024-004', '2024-02-26 05:20:00', 6, '2024-02-26 05:20:00', '2024-02-26 05:20:00', NULL, 1),
(590, 14, 1500.00, 'cash', NULL, '2024-03-04 04:20:00', 6, '2024-03-04 04:20:00', '2024-03-04 04:20:00', NULL, 1),
(591, 15, 1200.00, 'cash', NULL, '2024-03-11 05:10:00', 6, '2024-03-11 05:10:00', '2024-03-11 05:10:00', NULL, 1),
(592, 16, 1500.00, 'card', 'TXN-2024-005', '2024-03-18 04:40:00', 6, '2024-03-18 04:40:00', '2024-03-18 04:40:00', NULL, 1),
(593, 17, 600.00, 'mobile_banking', 'ROCKET-2024-001', '2024-03-25 05:25:00', 6, '2024-03-25 05:25:00', '2024-03-25 05:25:00', NULL, 1),
(594, 18, 800.00, 'cash', NULL, '2024-04-08 04:15:00', 6, '2024-04-08 04:15:00', '2024-04-08 04:15:00', NULL, 1),
(595, 19, 800.00, 'cash', NULL, '2024-04-15 04:30:00', 6, '2024-04-15 04:30:00', '2024-04-15 04:30:00', NULL, 1),
(596, 20, 800.00, 'card', 'TXN-2024-006', '2024-04-22 04:55:00', 6, '2024-04-22 04:55:00', '2024-04-22 04:55:00', NULL, 1),
(597, 21, 2300.00, 'cash', NULL, '2025-01-06 04:25:00', 6, '2025-01-06 04:25:00', '2025-01-06 04:25:00', NULL, 1),
(598, 22, 1200.00, 'mobile_banking', 'BKASH-2025-001', '2025-01-07 05:15:00', 6, '2025-01-07 05:15:00', '2025-01-07 05:15:00', NULL, 1),
(599, 23, 2800.00, 'card', 'TXN-2025-001', '2025-01-13 04:50:00', 6, '2025-01-13 04:50:00', '2025-01-13 04:50:00', NULL, 1),
(600, 24, 600.00, 'cash', NULL, '2025-01-14 05:25:00', 6, '2025-01-14 05:25:00', '2025-01-14 05:25:00', NULL, 1),
(601, 25, 1700.00, 'card', 'TXN-2025-002', '2025-01-20 04:25:00', 6, '2025-01-20 04:25:00', '2025-01-20 04:25:00', NULL, 1),
(602, 26, 1200.00, 'cash', NULL, '2025-01-21 05:10:00', 6, '2025-01-21 05:10:00', '2025-01-21 05:10:00', NULL, 1),
(603, 27, 800.00, 'mobile_banking', 'NAGAD-2025-001', '2025-01-22 04:35:00', 6, '2025-01-22 04:35:00', '2025-01-22 04:35:00', NULL, 1),
(604, 28, 2700.00, 'cash', NULL, '2025-01-23 05:30:00', 6, '2025-01-23 05:30:00', '2025-01-23 05:30:00', NULL, 1),
(605, 585, 262.50, 'cash', NULL, '2026-01-24 11:11:34', 5, NULL, NULL, NULL, 1),
(606, 584, 1200.00, 'cash', NULL, '2026-01-24 11:12:42', 5, NULL, NULL, NULL, 1),
(607, 586, 600.00, 'cash', NULL, '2026-01-24 19:30:54', 2, NULL, NULL, NULL, 1);

-- --------------------------------------------------------

--
-- Table structure for table `lara_permissions`
--

CREATE TABLE `lara_permissions` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `lara_permissions`
--

INSERT INTO `lara_permissions` (`id`, `name`, `description`, `created_at`, `updated_at`) VALUES
(1, 'view_dashboard', NULL, '2026-01-19 07:07:41', '2026-01-19 07:07:41'),
(2, 'view_admin_dashboard', NULL, '2026-01-19 07:07:41', '2026-01-19 07:07:41'),
(3, 'view_doctor_dashboard', NULL, '2026-01-19 07:07:41', '2026-01-19 07:07:41'),
(4, 'view_nurse_dashboard', NULL, '2026-01-19 07:07:41', '2026-01-19 07:07:41'),
(5, 'view_receptionist_dashboard', NULL, '2026-01-19 07:07:41', '2026-01-19 07:07:41'),
(6, 'view_lab_dashboard', NULL, '2026-01-19 07:07:41', '2026-01-19 07:07:41'),
(7, 'view_pharmacy_dashboard', NULL, '2026-01-19 07:07:41', '2026-01-19 07:07:41'),
(8, 'view_accountant_dashboard', NULL, '2026-01-19 07:07:41', '2026-01-19 07:07:41'),
(9, 'view_users', NULL, '2026-01-19 07:07:41', '2026-01-19 07:07:41'),
(10, 'create_users', NULL, '2026-01-19 07:07:41', '2026-01-19 07:07:41'),
(11, 'edit_users', NULL, '2026-01-19 07:07:41', '2026-01-19 07:07:41'),
(12, 'delete_users', NULL, '2026-01-19 07:07:41', '2026-01-19 07:07:41'),
(13, 'manage_roles', NULL, '2026-01-19 07:07:41', '2026-01-19 07:07:41'),
(14, 'manage_clinic_settings', NULL, '2026-01-19 07:07:41', '2026-01-19 07:07:41'),
(15, 'view_patients', NULL, '2026-01-19 07:07:41', '2026-01-19 07:07:41'),
(16, 'create_patients', NULL, '2026-01-19 07:07:41', '2026-01-19 07:07:41'),
(17, 'edit_patients', NULL, '2026-01-19 07:07:41', '2026-01-19 07:07:41'),
(18, 'delete_patients', NULL, '2026-01-19 07:07:41', '2026-01-19 07:07:41'),
(19, 'view_doctors', NULL, '2026-01-19 07:07:41', '2026-01-19 07:07:41'),
(20, 'create_doctors', NULL, '2026-01-19 07:07:41', '2026-01-19 07:07:41'),
(21, 'edit_doctors', NULL, '2026-01-19 07:07:41', '2026-01-19 07:07:41'),
(22, 'delete_doctors', NULL, '2026-01-19 07:07:41', '2026-01-19 07:07:41'),
(23, 'manage_doctor_schedule', NULL, '2026-01-19 07:07:41', '2026-01-19 07:07:41'),
(24, 'view_staff', NULL, '2026-01-19 07:07:41', '2026-01-19 07:07:41'),
(25, 'create_staff', NULL, '2026-01-19 07:07:41', '2026-01-19 07:07:41'),
(26, 'edit_staff', NULL, '2026-01-19 07:07:41', '2026-01-19 07:07:41'),
(27, 'delete_staff', NULL, '2026-01-19 07:07:41', '2026-01-19 07:07:41'),
(28, 'view_appointments', NULL, '2026-01-19 07:07:41', '2026-01-19 07:07:41'),
(29, 'create_appointments', NULL, '2026-01-19 07:07:41', '2026-01-19 07:07:41'),
(30, 'edit_appointments', NULL, '2026-01-19 07:07:41', '2026-01-19 07:07:41'),
(31, 'cancel_appointments', NULL, '2026-01-19 07:07:41', '2026-01-19 07:07:41'),
(32, 'perform_consultation', NULL, '2026-01-19 07:07:41', '2026-01-19 07:07:41'),
(33, 'view_consultations', NULL, '2026-01-19 07:07:41', '2026-01-19 07:07:41'),
(34, 'view_ipd', NULL, '2026-01-19 07:07:41', '2026-01-19 07:07:41'),
(35, 'view_admissions', NULL, '2026-01-19 07:07:41', '2026-01-19 07:07:41'),
(36, 'create_admissions', NULL, '2026-01-19 07:07:41', '2026-01-19 07:07:41'),
(37, 'discharge_patients', NULL, '2026-01-19 07:07:41', '2026-01-19 07:07:41'),
(38, 'manage_beds', NULL, '2026-01-19 07:07:41', '2026-01-19 07:07:41'),
(39, 'manage_wards', NULL, '2026-01-19 07:07:41', '2026-01-19 07:07:41'),
(40, 'view_nursing_notes', NULL, '2026-01-19 07:07:41', '2026-01-19 07:07:41'),
(41, 'create_nursing_notes', NULL, '2026-01-19 07:07:41', '2026-01-19 07:07:41'),
(42, 'create_prescriptions', NULL, '2026-01-19 07:07:41', '2026-01-19 07:07:41'),
(43, 'view_prescriptions', NULL, '2026-01-19 07:07:41', '2026-01-19 07:07:41'),
(44, 'view_lab', NULL, '2026-01-19 07:07:41', '2026-01-19 07:07:41'),
(45, 'view_lab_orders', NULL, '2026-01-19 07:07:41', '2026-01-19 07:07:41'),
(46, 'create_lab_orders', NULL, '2026-01-19 07:07:41', '2026-01-19 07:07:41'),
(47, 'enter_lab_results', NULL, '2026-01-19 07:07:41', '2026-01-19 07:07:41'),
(48, 'view_pharmacy', NULL, '2026-01-19 07:07:41', '2026-01-19 07:07:41'),
(49, 'view_pharmacy_inventory', NULL, '2026-01-19 07:07:41', '2026-01-19 07:07:41'),
(50, 'manage_pharmacy_inventory', NULL, '2026-01-19 07:07:41', '2026-01-19 07:07:41'),
(51, 'process_pharmacy_sales', NULL, '2026-01-19 07:07:41', '2026-01-19 07:07:41'),
(52, 'view_billing', NULL, '2026-01-19 07:07:41', '2026-01-19 07:07:41'),
(53, 'view_invoices', NULL, '2026-01-19 07:07:41', '2026-01-19 07:07:41'),
(54, 'create_invoices', NULL, '2026-01-19 07:07:41', '2026-01-19 07:07:41'),
(55, 'process_payments', NULL, '2026-01-19 07:07:41', '2026-01-19 07:07:41'),
(56, 'view_reports', NULL, '2026-01-19 07:07:41', '2026-01-19 07:07:41'),
(57, 'view_financial_reports', NULL, '2026-01-19 07:07:41', '2026-01-19 07:07:41'),
(58, 'manage_doctor_clinic_assignments', 'Manage Doctor Clinic Assignments', '2026-01-24 10:34:28', '2026-01-24 10:34:28');

-- --------------------------------------------------------

--
-- Table structure for table `lara_personal_access_tokens`
--

CREATE TABLE `lara_personal_access_tokens` (
  `id` bigint UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint UNSIGNED NOT NULL,
  `name` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `lara_pharmacy_sales`
--

CREATE TABLE `lara_pharmacy_sales` (
  `id` bigint UNSIGNED NOT NULL,
  `prescription_id` bigint UNSIGNED NOT NULL,
  `patient_id` bigint UNSIGNED NOT NULL,
  `sale_date` date NOT NULL,
  `total_amount` decimal(10,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `clinic_id` bigint UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `lara_pharmacy_sales`
--

INSERT INTO `lara_pharmacy_sales` (`id`, `prescription_id`, `patient_id`, `sale_date`, `total_amount`, `created_at`, `updated_at`, `deleted_at`, `clinic_id`) VALUES
(152, 1, 1, '2024-01-08', 390.00, '2024-01-08 05:00:00', '2024-01-08 05:00:00', NULL, 1),
(153, 3, 3, '2024-01-15', 235.00, '2024-01-15 05:30:00', '2024-01-15 05:30:00', NULL, 1),
(154, 5, 5, '2024-01-22', 560.00, '2024-01-22 05:45:00', '2024-01-22 05:45:00', NULL, 1),
(155, 9, 1, '2025-01-06', 300.00, '2025-01-06 05:00:00', '2025-01-06 05:00:00', NULL, 1),
(156, 11, 3, '2025-01-13', 330.00, '2025-01-13 05:30:00', '2025-01-13 05:30:00', NULL, 1),
(157, 13, 5, '2025-01-20', 420.00, '2025-01-20 04:50:00', '2025-01-20 04:50:00', NULL, 1),
(158, 15, 7, '2025-01-22', 440.00, '2025-01-22 05:15:00', '2025-01-22 05:15:00', NULL, 1);

-- --------------------------------------------------------

--
-- Table structure for table `lara_pharmacy_sale_items`
--

CREATE TABLE `lara_pharmacy_sale_items` (
  `id` bigint UNSIGNED NOT NULL,
  `pharmacy_sale_id` bigint UNSIGNED NOT NULL,
  `medicine_id` bigint UNSIGNED NOT NULL,
  `quantity` int NOT NULL,
  `unit_price` decimal(10,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `lara_pharmacy_sale_items`
--

INSERT INTO `lara_pharmacy_sale_items` (`id`, `pharmacy_sale_id`, `medicine_id`, `quantity`, `unit_price`, `created_at`, `updated_at`) VALUES
(292, 1, 2, 30, 6.00, '2024-01-08 05:00:00', '2024-01-08 05:00:00'),
(293, 1, 3, 30, 12.00, '2024-01-08 05:00:00', '2024-01-08 05:00:00'),
(294, 2, 1, 20, 1.50, '2024-01-15 05:30:00', '2024-01-15 05:30:00'),
(295, 2, 5, 14, 15.00, '2024-01-15 05:30:00', '2024-01-15 05:30:00'),
(296, 3, 2, 30, 6.00, '2024-01-22 05:45:00', '2024-01-22 05:45:00'),
(297, 3, 3, 30, 12.00, '2024-01-22 05:45:00', '2024-01-22 05:45:00'),
(298, 3, 4, 30, 4.00, '2024-01-22 05:45:00', '2024-01-22 05:45:00'),
(299, 4, 3, 30, 12.00, '2025-01-06 05:00:00', '2025-01-06 05:00:00'),
(300, 5, 2, 30, 6.00, '2025-01-13 05:30:00', '2025-01-13 05:30:00'),
(301, 5, 1, 30, 1.50, '2025-01-13 05:30:00', '2025-01-13 05:30:00'),
(302, 6, 2, 30, 6.00, '2025-01-20 04:50:00', '2025-01-20 04:50:00'),
(303, 6, 3, 30, 12.00, '2025-01-20 04:50:00', '2025-01-20 04:50:00'),
(304, 7, 2, 30, 10.00, '2025-01-22 05:15:00', '2025-01-22 05:15:00'),
(305, 7, 4, 30, 4.00, '2025-01-22 05:15:00', '2025-01-22 05:15:00');

-- --------------------------------------------------------

--
-- Table structure for table `lara_prescriptions`
--

CREATE TABLE `lara_prescriptions` (
  `id` bigint UNSIGNED NOT NULL,
  `consultation_id` bigint UNSIGNED NOT NULL,
  `issued_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `clinic_id` bigint UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `lara_prescriptions`
--

INSERT INTO `lara_prescriptions` (`id`, `consultation_id`, `issued_at`, `notes`, `created_at`, `updated_at`, `deleted_at`, `clinic_id`) VALUES
(153, 1, '2024-01-08 03:45:00', 'Continue current antihypertensive regimen. Monitor BP daily.', '2024-01-08 03:45:00', '2024-01-08 03:45:00', NULL, 1),
(154, 2, '2024-01-10 04:35:00', 'Prenatal vitamins and calcium supplementation.', '2024-01-10 04:35:00', '2024-01-10 04:35:00', NULL, 1),
(155, 3, '2024-01-15 04:10:00', 'Pain management and muscle relaxant. Avoid strenuous activity.', '2024-01-15 04:10:00', '2024-01-15 04:10:00', NULL, 1),
(156, 4, '2024-01-17 04:50:00', 'Continue prenatal care regimen.', '2024-01-17 04:50:00', '2024-01-17 04:50:00', NULL, 1),
(157, 5, '2024-01-22 04:25:00', 'Increased antihypertensive dose. Low salt diet advised.', '2024-01-22 04:25:00', '2024-01-22 04:25:00', NULL, 1),
(158, 6, '2024-01-24 05:05:00', 'Hormonal regulation therapy started.', '2024-01-24 05:05:00', '2024-01-24 05:05:00', NULL, 1),
(159, 7, '2024-01-29 04:30:00', 'Continue existing medications.', '2024-01-29 04:30:00', '2024-01-29 04:30:00', NULL, 1),
(160, 8, '2024-02-05 03:50:00', 'Beta blocker initiated for heart rate control.', '2024-02-05 03:50:00', '2024-02-05 03:50:00', NULL, 1),
(161, 9, '2025-01-06 03:50:00', 'Maintain healthy lifestyle. Continue monitoring.', '2025-01-06 03:50:00', '2025-01-06 03:50:00', NULL, 1),
(162, 10, '2025-01-07 04:40:00', 'Prenatal vitamins, iron supplementation.', '2025-01-07 04:40:00', '2025-01-07 04:40:00', NULL, 1),
(163, 11, '2025-01-13 04:10:00', 'Antianginal therapy initiated. Risk factor modification.', '2025-01-13 04:10:00', '2025-01-13 04:10:00', NULL, 1),
(164, 12, '2025-01-14 04:50:00', 'Continue current regimen.', '2025-01-14 04:50:00', '2025-01-14 04:50:00', NULL, 1),
(165, 13, '2025-01-20 03:45:00', 'Excellent compliance. Continue medications.', '2025-01-20 03:45:00', '2025-01-20 03:45:00', NULL, 1),
(166, 14, '2025-01-21 04:35:00', 'Routine care, no medications needed.', '2025-01-21 04:35:00', '2025-01-21 04:35:00', NULL, 1),
(167, 15, '2025-01-22 04:00:00', 'Adjusted medication dosage for better control.', '2025-01-22 04:00:00', '2025-01-22 04:00:00', NULL, 1),
(168, 16, '2025-01-23 04:55:00', 'Initiated treatment for identified conditions.', '2025-01-23 04:55:00', '2025-01-23 04:55:00', NULL, 1);

-- --------------------------------------------------------

--
-- Table structure for table `lara_prescription_complaint`
--

CREATE TABLE `lara_prescription_complaint` (
  `id` bigint UNSIGNED NOT NULL,
  `prescription_id` bigint UNSIGNED NOT NULL,
  `complaint_id` bigint UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `lara_prescription_complaint`
--

INSERT INTO `lara_prescription_complaint` (`id`, `prescription_id`, `complaint_id`) VALUES
(224, 1, 4),
(225, 1, 7),
(227, 3, 2),
(226, 3, 4),
(228, 5, 2),
(229, 5, 6),
(230, 6, 5),
(231, 6, 8),
(232, 8, 4),
(233, 11, 4),
(234, 11, 7),
(235, 13, 2);

-- --------------------------------------------------------

--
-- Table structure for table `lara_prescription_items`
--

CREATE TABLE `lara_prescription_items` (
  `id` bigint UNSIGNED NOT NULL,
  `prescription_id` bigint UNSIGNED NOT NULL,
  `medicine_id` bigint UNSIGNED NOT NULL,
  `dosage` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `frequency` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `duration_days` int NOT NULL,
  `instructions` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `clinic_id` bigint UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `lara_prescription_items`
--

INSERT INTO `lara_prescription_items` (`id`, `prescription_id`, `medicine_id`, `dosage`, `frequency`, `duration_days`, `instructions`, `created_at`, `updated_at`, `clinic_id`) VALUES
(293, 1, 2, '5mg', 'Once daily', 30, 'Take in the morning with water', '2024-01-08 03:45:00', '2024-01-08 03:45:00', 1),
(294, 1, 3, '10mg', 'Once daily at night', 30, 'Take after dinner', '2024-01-08 03:45:00', '2024-01-08 03:45:00', 1),
(295, 2, 1, '500mg', 'As needed', 10, 'For pain relief, max 3 times daily', '2024-01-10 04:35:00', '2024-01-10 04:35:00', 1),
(296, 3, 1, '500mg', 'Three times daily', 7, 'After meals', '2024-01-15 04:10:00', '2024-01-15 04:10:00', 1),
(297, 3, 5, '10mg', 'Once daily at night', 7, 'Before sleep', '2024-01-15 04:10:00', '2024-01-15 04:10:00', 1),
(298, 5, 2, '10mg', 'Once daily', 30, 'Take in the morning', '2024-01-22 04:25:00', '2024-01-22 04:25:00', 1),
(299, 5, 3, '20mg', 'Once daily at night', 30, 'After dinner', '2024-01-22 04:25:00', '2024-01-22 04:25:00', 1),
(300, 5, 4, '20mg', 'Once daily', 30, 'Before breakfast', '2024-01-22 04:25:00', '2024-01-22 04:25:00', 1),
(301, 8, 2, '5mg', 'Twice daily', 30, 'Morning and evening', '2024-02-05 03:50:00', '2024-02-05 03:50:00', 1),
(302, 9, 3, '10mg', 'Once daily', 30, 'Take at night', '2025-01-06 03:50:00', '2025-01-06 03:50:00', 1),
(303, 11, 2, '5mg', 'Once daily', 30, 'Morning dose', '2025-01-13 04:10:00', '2025-01-13 04:10:00', 1),
(304, 11, 1, '500mg', 'As needed', 30, 'For chest discomfort', '2025-01-13 04:10:00', '2025-01-13 04:10:00', 1),
(305, 13, 2, '5mg', 'Once daily', 30, 'Continue current dose', '2025-01-20 03:45:00', '2025-01-20 03:45:00', 1),
(306, 13, 3, '10mg', 'Once daily', 30, 'At bedtime', '2025-01-20 03:45:00', '2025-01-20 03:45:00', 1),
(307, 15, 2, '10mg', 'Once daily', 30, 'Increased dose', '2025-01-22 04:00:00', '2025-01-22 04:00:00', 1),
(308, 15, 4, '20mg', 'Once daily', 30, 'Before breakfast', '2025-01-22 04:00:00', '2025-01-22 04:00:00', 1);

-- --------------------------------------------------------

--
-- Table structure for table `lara_roles`
--

CREATE TABLE `lara_roles` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `lara_roles`
--

INSERT INTO `lara_roles` (`id`, `name`, `description`, `created_at`, `updated_at`) VALUES
(1, 'Super Admin', 'System Owner', '2026-01-19 07:07:41', '2026-01-19 07:07:41'),
(2, 'Clinic Admin', 'Administrator for the clinic', '2026-01-19 07:07:41', '2026-01-19 07:07:41'),
(3, 'Doctor', 'Medical Doctor', '2026-01-19 07:07:42', '2026-01-19 07:07:42'),
(4, 'Nurse', 'IPD Nurse', '2026-01-19 07:07:42', '2026-01-19 07:07:42'),
(5, 'Receptionist', 'Front Desk', '2026-01-19 07:07:42', '2026-01-19 07:07:42'),
(6, 'Lab Technician', 'Lab Staff', '2026-01-19 07:07:42', '2026-01-19 07:07:42'),
(7, 'Pharmacist', 'Pharmacy Staff', '2026-01-19 07:07:42', '2026-01-19 07:07:42'),
(8, 'Accountant', 'Finance Staff', '2026-01-19 07:07:42', '2026-01-19 07:07:42');

-- --------------------------------------------------------

--
-- Table structure for table `lara_role_permission`
--

CREATE TABLE `lara_role_permission` (
  `id` bigint UNSIGNED NOT NULL,
  `role_id` bigint UNSIGNED NOT NULL,
  `permission_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `lara_role_permission`
--

INSERT INTO `lara_role_permission` (`id`, `role_id`, `permission_id`, `created_at`, `updated_at`) VALUES
(1, 1, 1, NULL, NULL),
(2, 1, 2, NULL, NULL),
(3, 1, 3, NULL, NULL),
(4, 1, 4, NULL, NULL),
(5, 1, 5, NULL, NULL),
(6, 1, 6, NULL, NULL),
(7, 1, 7, NULL, NULL),
(8, 1, 8, NULL, NULL),
(9, 1, 9, NULL, NULL),
(10, 1, 10, NULL, NULL),
(11, 1, 11, NULL, NULL),
(12, 1, 12, NULL, NULL),
(13, 1, 13, NULL, NULL),
(14, 1, 14, NULL, NULL),
(15, 1, 15, NULL, NULL),
(16, 1, 16, NULL, NULL),
(17, 1, 17, NULL, NULL),
(18, 1, 18, NULL, NULL),
(19, 1, 19, NULL, NULL),
(20, 1, 20, NULL, NULL),
(21, 1, 21, NULL, NULL),
(22, 1, 22, NULL, NULL),
(23, 1, 23, NULL, NULL),
(24, 1, 24, NULL, NULL),
(25, 1, 25, NULL, NULL),
(26, 1, 26, NULL, NULL),
(27, 1, 27, NULL, NULL),
(28, 1, 28, NULL, NULL),
(29, 1, 29, NULL, NULL),
(30, 1, 30, NULL, NULL),
(31, 1, 31, NULL, NULL),
(32, 1, 32, NULL, NULL),
(33, 1, 33, NULL, NULL),
(34, 1, 34, NULL, NULL),
(35, 1, 35, NULL, NULL),
(36, 1, 36, NULL, NULL),
(37, 1, 37, NULL, NULL),
(38, 1, 38, NULL, NULL),
(39, 1, 39, NULL, NULL),
(40, 1, 40, NULL, NULL),
(41, 1, 41, NULL, NULL),
(42, 1, 42, NULL, NULL),
(43, 1, 43, NULL, NULL),
(44, 1, 44, NULL, NULL),
(45, 1, 45, NULL, NULL),
(46, 1, 46, NULL, NULL),
(47, 1, 47, NULL, NULL),
(48, 1, 48, NULL, NULL),
(49, 1, 49, NULL, NULL),
(50, 1, 50, NULL, NULL),
(51, 1, 51, NULL, NULL),
(52, 1, 52, NULL, NULL),
(53, 1, 53, NULL, NULL),
(54, 1, 54, NULL, NULL),
(55, 1, 55, NULL, NULL),
(56, 1, 56, NULL, NULL),
(57, 1, 57, NULL, NULL),
(58, 2, 1, NULL, NULL),
(59, 2, 2, NULL, NULL),
(60, 2, 3, NULL, NULL),
(61, 2, 4, NULL, NULL),
(62, 2, 5, NULL, NULL),
(63, 2, 6, NULL, NULL),
(64, 2, 7, NULL, NULL),
(65, 2, 8, NULL, NULL),
(66, 2, 9, NULL, NULL),
(67, 2, 10, NULL, NULL),
(68, 2, 11, NULL, NULL),
(69, 2, 12, NULL, NULL),
(70, 2, 13, NULL, NULL),
(71, 2, 14, NULL, NULL),
(72, 2, 15, NULL, NULL),
(73, 2, 16, NULL, NULL),
(74, 2, 17, NULL, NULL),
(75, 2, 18, NULL, NULL),
(76, 2, 19, NULL, NULL),
(77, 2, 20, NULL, NULL),
(78, 2, 21, NULL, NULL),
(79, 2, 22, NULL, NULL),
(80, 2, 23, NULL, NULL),
(81, 2, 24, NULL, NULL),
(82, 2, 25, NULL, NULL),
(83, 2, 26, NULL, NULL),
(84, 2, 27, NULL, NULL),
(85, 2, 28, NULL, NULL),
(86, 2, 29, NULL, NULL),
(87, 2, 30, NULL, NULL),
(88, 2, 31, NULL, NULL),
(89, 2, 32, NULL, NULL),
(90, 2, 33, NULL, NULL),
(91, 2, 34, NULL, NULL),
(92, 2, 35, NULL, NULL),
(93, 2, 36, NULL, NULL),
(94, 2, 37, NULL, NULL),
(95, 2, 38, NULL, NULL),
(96, 2, 39, NULL, NULL),
(97, 2, 40, NULL, NULL),
(98, 2, 41, NULL, NULL),
(99, 2, 42, NULL, NULL),
(100, 2, 43, NULL, NULL),
(101, 2, 44, NULL, NULL),
(102, 2, 45, NULL, NULL),
(103, 2, 46, NULL, NULL),
(104, 2, 47, NULL, NULL),
(105, 2, 48, NULL, NULL),
(106, 2, 49, NULL, NULL),
(107, 2, 50, NULL, NULL),
(108, 2, 51, NULL, NULL),
(109, 2, 52, NULL, NULL),
(110, 2, 53, NULL, NULL),
(111, 2, 54, NULL, NULL),
(112, 2, 55, NULL, NULL),
(113, 2, 56, NULL, NULL),
(114, 2, 57, NULL, NULL),
(115, 3, 46, NULL, NULL),
(116, 3, 42, NULL, NULL),
(117, 3, 32, NULL, NULL),
(118, 3, 35, NULL, NULL),
(119, 3, 28, NULL, NULL),
(120, 3, 33, NULL, NULL),
(121, 3, 1, NULL, NULL),
(122, 3, 3, NULL, NULL),
(123, 3, 34, NULL, NULL),
(124, 3, 44, NULL, NULL),
(125, 3, 45, NULL, NULL),
(126, 3, 15, NULL, NULL),
(127, 3, 43, NULL, NULL),
(128, 4, 41, NULL, NULL),
(129, 4, 38, NULL, NULL),
(130, 4, 35, NULL, NULL),
(131, 4, 1, NULL, NULL),
(132, 4, 34, NULL, NULL),
(133, 4, 4, NULL, NULL),
(134, 4, 40, NULL, NULL),
(135, 4, 15, NULL, NULL),
(136, 5, 31, NULL, NULL),
(137, 5, 29, NULL, NULL),
(138, 5, 54, NULL, NULL),
(139, 5, 16, NULL, NULL),
(140, 5, 30, NULL, NULL),
(141, 5, 17, NULL, NULL),
(142, 5, 28, NULL, NULL),
(143, 5, 52, NULL, NULL),
(144, 5, 1, NULL, NULL),
(145, 5, 15, NULL, NULL),
(146, 5, 5, NULL, NULL),
(147, 6, 47, NULL, NULL),
(148, 6, 1, NULL, NULL),
(149, 6, 44, NULL, NULL),
(150, 6, 6, NULL, NULL),
(151, 6, 45, NULL, NULL),
(152, 7, 54, NULL, NULL),
(153, 7, 50, NULL, NULL),
(154, 7, 55, NULL, NULL),
(155, 7, 51, NULL, NULL),
(156, 7, 52, NULL, NULL),
(157, 7, 1, NULL, NULL),
(158, 7, 53, NULL, NULL),
(159, 7, 48, NULL, NULL),
(160, 7, 7, NULL, NULL),
(161, 7, 49, NULL, NULL),
(162, 7, 43, NULL, NULL),
(163, 8, 54, NULL, NULL),
(164, 8, 55, NULL, NULL),
(165, 8, 8, NULL, NULL),
(166, 8, 52, NULL, NULL),
(167, 8, 1, NULL, NULL),
(168, 8, 57, NULL, NULL),
(169, 8, 53, NULL, NULL),
(170, 8, 56, NULL, NULL),
(171, 8, 15, NULL, NULL),
(172, 8, 19, NULL, NULL),
(173, 5, 3, NULL, NULL),
(174, 5, 19, NULL, NULL),
(175, 5, 43, NULL, NULL),
(176, 5, 53, NULL, NULL),
(177, 5, 55, NULL, NULL),
(178, 7, 5, NULL, NULL),
(179, 7, 15, NULL, NULL),
(180, 7, 19, NULL, NULL),
(181, 7, 28, NULL, NULL),
(182, 7, 33, NULL, NULL),
(183, 6, 15, NULL, NULL),
(184, 6, 19, NULL, NULL),
(185, 6, 34, NULL, NULL),
(186, 6, 53, NULL, NULL),
(187, 6, 54, NULL, NULL),
(188, 6, 55, NULL, NULL),
(189, 8, 49, NULL, NULL),
(190, 4, 19, NULL, NULL),
(191, 4, 28, NULL, NULL),
(192, 4, 33, NULL, NULL),
(193, 2, 58, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `lara_rooms`
--

CREATE TABLE `lara_rooms` (
  `id` bigint UNSIGNED NOT NULL,
  `ward_id` bigint UNSIGNED NOT NULL,
  `clinic_id` bigint UNSIGNED DEFAULT NULL,
  `room_number` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `room_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `daily_rate` decimal(10,2) NOT NULL,
  `status` enum('available','occupied','maintenance') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'available',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `lara_rooms`
--

INSERT INTO `lara_rooms` (`id`, `ward_id`, `clinic_id`, `room_number`, `room_type`, `daily_rate`, `status`, `created_at`, `updated_at`) VALUES
(61, 1, 1, 'G-101', 'General', 1500.00, 'available', '2023-01-15 00:00:00', '2025-01-20 04:00:00'),
(62, 1, 1, 'G-102', 'General', 1500.00, 'available', '2023-01-15 00:00:00', '2025-01-20 04:00:00'),
(63, 1, 1, 'G-103', 'General', 1500.00, 'occupied', '2023-01-15 00:00:00', '2025-01-20 04:00:00'),
(64, 2, 1, 'G-201', 'General', 1500.00, 'available', '2023-01-15 00:00:00', '2025-01-20 04:00:00'),
(65, 3, 1, 'ICU-301', 'ICU', 5000.00, 'available', '2023-01-15 00:00:00', '2025-01-20 04:00:00'),
(66, 3, 1, 'ICU-302', 'ICU', 5000.00, 'occupied', '2023-01-15 00:00:00', '2025-01-20 04:00:00'),
(67, 4, 1, 'CAB-401', 'Private Cabin', 3500.00, 'available', '2023-01-15 00:00:00', '2025-01-20 04:00:00'),
(68, 4, 1, 'CAB-402', 'Private Cabin', 3500.00, 'available', '2023-01-15 00:00:00', '2025-01-20 04:00:00'),
(69, 4, 1, 'CAB-403', 'Private Cabin', 3500.00, 'occupied', '2023-01-15 00:00:00', '2025-01-20 04:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `lara_sessions`
--

CREATE TABLE `lara_sessions` (
  `id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `payload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `lara_sessions`
--

INSERT INTO `lara_sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('mNV9p0TBAi7DsKUhXP3j86GupcZncFY7NwR3RN6i', 3, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/124.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiSWkwSGhWOVM0aW9pQUV0SjZqMnU1ZEZTU25TeUVOblRlNkpYUFVjRiI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzE6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9sYWIvb3JkZXIiO3M6NToicm91dGUiO3M6MTA6ImxhYi5jcmVhdGUiO31zOjUwOiJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aTozO30=', 1769280088),
('NKgFGr7YIl9dT4DNPEA4PGkESwyHAZRekc07Maj4', 2, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/124.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiYmwzdVowMFI2QmdTalJtdmd3VkQzSWNTaXpTbHFOWTZlWDVHR3dCZCI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzM6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9iaWxsaW5nLzU4NCI7czo1OiJyb3V0ZSI7czoxMjoiYmlsbGluZy5zaG93Ijt9czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6Mjt9', 1769305762);

-- --------------------------------------------------------

--
-- Table structure for table `lara_users`
--

CREATE TABLE `lara_users` (
  `id` bigint UNSIGNED NOT NULL,
  `clinic_id` bigint UNSIGNED NOT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `profile_photo_path` varchar(2048) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `last_login_at` timestamp NULL DEFAULT NULL,
  `is_two_factor_enabled` tinyint(1) NOT NULL DEFAULT '0',
  `status` enum('active','inactive','blocked') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `lara_users`
--

INSERT INTO `lara_users` (`id`, `clinic_id`, `email`, `profile_photo_path`, `name`, `password`, `remember_token`, `phone`, `email_verified_at`, `last_login_at`, `is_two_factor_enabled`, `status`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 1, 'superadmin@hospital.com', 'profile-photos/1769257377_PhotoF.jpg', 'Super Admin', '$2y$12$g6uf4LjWRl/8RJ3PqjfPCe96jrbo/nwdiSBRqrl76Fiul.jG77M.C', NULL, NULL, '2026-01-19 07:07:43', NULL, 0, 'active', NULL, NULL, NULL),
(2, 1, 'admin@hospital.com', 'profile-photos/1769270993_profile.png', 'Clinic Admin', '$2y$12$wo6XAZQtz1lEEKx67uBfjOyURWHe7.Rrom9CW/mxB9nIqkW0odJ1W', NULL, NULL, '2026-01-19 07:07:43', NULL, 0, 'active', NULL, NULL, NULL),
(3, 1, 'doctor@hospital.com', 'profile-photos/1769271174_avatar-01.jpg', 'John Doe', '$2y$12$/WOCz7X41fEADYpql5GAH.4nk0upoC8BglT4kyq7YwWKEo6qS.uqe', NULL, NULL, '2026-01-19 07:07:44', NULL, 0, 'active', NULL, NULL, NULL),
(4, 1, 'nurse@hospital.com', 'profile-photos/1769274902_avatar-44.jpg', 'Sarah Nurse', '$2y$12$/e205P5k1s1FdKDAr9gxg.jmBgZLe38SbHXpEI5Rt7rfIIOAoW7Oa', NULL, NULL, '2026-01-19 07:07:44', NULL, 0, 'active', NULL, NULL, NULL),
(5, 1, 'receptionist@hospital.com', 'profile-photos/1769271136_avatar-09.jpg', 'Rachel Receptionist', '$2y$12$4EOWLMWez6GItGg2iglDh.ZsAT6c2jIWZCbxKuaEq9RGOZPs6Nghy', NULL, NULL, '2026-01-19 07:07:45', NULL, 0, 'active', NULL, NULL, NULL),
(6, 1, 'lab@hospital.com', 'profile-photos/1769271212_avatar-03.jpg', 'Tom LabTech', '$2y$12$EGcvVvVlAyu0hJx518ZMQOy7uIyL5F1g30TP3cTur48xyT0Ou.9Fy', NULL, NULL, '2026-01-19 07:07:45', NULL, 0, 'active', NULL, NULL, NULL),
(7, 1, 'pharmacist@hospital.com', 'assets/img/profiles/avatar-07.jpg', 'Peter Pharmacist', '$2y$12$g0D2.DBQ3uN.LPeBCxhT4e/gV9DmMYT3C.XWVLSAj1QDrqUsDRyAi', NULL, NULL, '2026-01-19 07:07:45', NULL, 0, 'active', NULL, NULL, NULL),
(8, 1, 'accountant@hospital.com', 'profile-photos/1769271254_avatar-04.jpg', 'Alice Accountant', '$2y$12$V/WKpmUygf4DpoyP/eWbx.6A7xuu4QD6m2f1lds4GRBGGLJ6L/WQO', NULL, NULL, '2026-01-19 07:07:46', NULL, 0, 'active', NULL, NULL, NULL),
(9, 1, 'harun@gmail.com', NULL, 'Harun PhD', '$2y$12$9pkumwhF84k67R.TPEuuteM15jZTl8aMWYXDVwQjn0/w6D7KGvmF.', NULL, '01794249827', '2026-01-19 16:43:25', NULL, 0, 'active', NULL, NULL, NULL),
(10, 1, 'rashed@gmail.com', NULL, 'Rashedul', '$2y$12$0oFwMYdqrI8szrB5fexiLu.eKAEYv.Wvgx/nAVKBl.Srv3r3n4Osq', NULL, '01792347242', '2026-01-19 16:47:00', NULL, 0, 'active', NULL, NULL, NULL),
(11, 1, 'tnvirjubaer96@gmail.com', NULL, 'Tanvir Jubayer', '$2y$12$WDGoDIE/X92FBHVj5SqbueHssSmFetHiy4IEdsD4RrSO57.yHUoEu', NULL, '0179234642', '2026-01-24 10:23:31', NULL, 0, 'active', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `lara_user_role`
--

CREATE TABLE `lara_user_role` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `role_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `lara_user_role`
--

INSERT INTO `lara_user_role` (`id`, `user_id`, `role_id`, `created_at`, `updated_at`) VALUES
(1, 1, 1, NULL, NULL),
(2, 2, 2, NULL, NULL),
(3, 3, 3, NULL, NULL),
(4, 4, 4, NULL, NULL),
(5, 5, 5, NULL, NULL),
(6, 6, 6, NULL, NULL),
(7, 7, 7, NULL, NULL),
(8, 8, 8, NULL, NULL),
(9, 9, 3, NULL, NULL),
(10, 10, 3, NULL, NULL),
(11, 11, 3, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `lara_visits`
--

CREATE TABLE `lara_visits` (
  `id` bigint UNSIGNED NOT NULL,
  `appointment_id` bigint UNSIGNED NOT NULL,
  `check_in_time` timestamp NULL DEFAULT NULL,
  `check_out_time` timestamp NULL DEFAULT NULL,
  `visit_status` enum('waiting','in_progress','completed','cancelled') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `clinic_id` bigint UNSIGNED DEFAULT NULL,
  `consultation_id` bigint UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `lara_visits`
--

INSERT INTO `lara_visits` (`id`, `appointment_id`, `check_in_time`, `check_out_time`, `visit_status`, `created_at`, `updated_at`, `deleted_at`, `clinic_id`, `consultation_id`) VALUES
(306, 1, '2024-01-08 02:55:00', '2024-01-08 03:45:00', 'completed', '2024-01-08 02:55:00', '2024-01-08 03:45:00', NULL, 1, NULL),
(307, 2, '2024-01-10 03:50:00', '2024-01-10 04:35:00', 'completed', '2024-01-10 03:50:00', '2024-01-10 04:35:00', NULL, 1, NULL),
(308, 3, '2024-01-15 03:15:00', '2024-01-15 04:10:00', 'completed', '2024-01-15 03:15:00', '2024-01-15 04:10:00', NULL, 1, NULL),
(309, 4, '2024-01-17 04:10:00', '2024-01-17 04:50:00', 'completed', '2024-01-17 04:10:00', '2024-01-17 04:50:00', NULL, 1, NULL),
(310, 5, '2024-01-22 03:35:00', '2024-01-22 04:25:00', 'completed', '2024-01-22 03:35:00', '2024-01-22 04:25:00', NULL, 1, NULL),
(311, 6, '2024-01-24 04:25:00', '2024-01-24 05:05:00', 'completed', '2024-01-24 04:25:00', '2024-01-24 05:05:00', NULL, 1, NULL),
(312, 7, '2024-01-29 03:55:00', '2024-01-29 04:30:00', 'completed', '2024-01-29 03:55:00', '2024-01-29 04:30:00', NULL, 1, NULL),
(313, 8, '2024-02-05 02:55:00', '2024-02-05 03:50:00', 'completed', '2024-02-05 02:55:00', '2024-02-05 03:50:00', NULL, 1, NULL),
(314, 9, '2024-02-12 03:15:00', '2024-02-12 04:05:00', 'completed', '2024-02-12 03:15:00', '2024-02-12 04:05:00', NULL, 1, NULL),
(315, 10, '2024-02-14 03:55:00', '2024-02-14 04:40:00', 'completed', '2024-02-14 03:55:00', '2024-02-14 04:40:00', NULL, 1, NULL),
(316, 11, '2024-02-19 03:35:00', '2024-02-19 04:15:00', 'completed', '2024-02-19 03:35:00', '2024-02-19 04:15:00', NULL, 1, NULL),
(317, 12, '2024-02-21 04:10:00', '2024-02-21 04:55:00', 'completed', '2024-02-21 04:10:00', '2024-02-21 04:55:00', NULL, 1, NULL),
(318, 13, '2024-02-26 03:55:00', '2024-02-26 04:45:00', 'completed', '2024-02-26 03:55:00', '2024-02-26 04:45:00', NULL, 1, NULL),
(319, 14, '2024-03-04 02:55:00', '2024-03-04 03:45:00', 'completed', '2024-03-04 02:55:00', '2024-03-04 03:45:00', NULL, 1, NULL),
(320, 15, '2024-03-11 03:55:00', '2024-03-11 04:35:00', 'completed', '2024-03-11 03:55:00', '2024-03-11 04:35:00', NULL, 1, NULL),
(321, 16, '2024-03-18 03:15:00', '2024-03-18 04:05:00', 'completed', '2024-03-18 03:15:00', '2024-03-18 04:05:00', NULL, 1, NULL),
(322, 17, '2024-03-25 04:10:00', '2024-03-25 04:50:00', 'completed', '2024-03-25 04:10:00', '2024-03-25 04:50:00', NULL, 1, NULL),
(323, 18, '2024-04-08 02:55:00', '2024-04-08 03:40:00', 'completed', '2024-04-08 02:55:00', '2024-04-08 03:40:00', NULL, 1, NULL),
(324, 19, '2024-04-15 03:15:00', '2024-04-15 03:55:00', 'completed', '2024-04-15 03:15:00', '2024-04-15 03:55:00', NULL, 1, NULL),
(325, 20, '2024-04-22 03:35:00', '2024-04-22 04:20:00', 'completed', '2024-04-22 03:35:00', '2024-04-22 04:20:00', NULL, 1, NULL),
(326, 21, '2025-01-06 02:55:00', '2025-01-06 03:50:00', 'completed', '2025-01-06 02:55:00', '2025-01-06 03:50:00', NULL, 1, NULL),
(327, 22, '2025-01-07 03:55:00', '2025-01-07 04:40:00', 'completed', '2025-01-07 03:55:00', '2025-01-07 04:40:00', NULL, 1, NULL),
(328, 23, '2025-01-13 03:15:00', '2025-01-13 04:10:00', 'completed', '2025-01-13 03:15:00', '2025-01-13 04:10:00', NULL, 1, NULL),
(329, 24, '2025-01-14 04:10:00', '2025-01-14 04:50:00', 'completed', '2025-01-14 04:10:00', '2025-01-14 04:50:00', NULL, 1, NULL),
(330, 25, '2025-01-20 02:55:00', '2025-01-20 03:45:00', 'completed', '2025-01-20 02:55:00', '2025-01-20 03:45:00', NULL, 1, NULL),
(331, 26, '2025-01-21 03:55:00', '2025-01-21 04:35:00', 'completed', '2025-01-21 03:55:00', '2025-01-21 04:35:00', NULL, 1, NULL),
(332, 27, '2025-01-22 03:15:00', '2025-01-22 04:00:00', 'completed', '2025-01-22 03:15:00', '2025-01-22 04:00:00', NULL, 1, NULL),
(333, 28, '2025-01-23 04:10:00', '2025-01-23 04:55:00', 'completed', '2025-01-23 04:10:00', '2025-01-23 04:55:00', NULL, 1, NULL),
(334, 29, '2025-01-24 02:55:00', NULL, 'in_progress', '2025-01-24 02:55:00', '2025-01-24 02:55:00', NULL, 1, NULL),
(335, 30, '2025-01-24 03:55:00', NULL, 'waiting', '2025-01-24 03:55:00', '2025-01-24 03:55:00', NULL, 1, NULL),
(336, 336, '2026-01-24 11:09:13', NULL, 'in_progress', NULL, NULL, NULL, 1, 326);

-- --------------------------------------------------------

--
-- Table structure for table `lara_wards`
--

CREATE TABLE `lara_wards` (
  `id` bigint UNSIGNED NOT NULL,
  `clinic_id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` enum('general','icu','cabin') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `floor` int DEFAULT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `status` enum('active','inactive') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `lara_wards`
--

INSERT INTO `lara_wards` (`id`, `clinic_id`, `name`, `type`, `floor`, `description`, `status`, `created_at`, `updated_at`) VALUES
(13, 1, 'General Ward A', 'general', 1, 'General patient care ward', 'active', '2023-01-15 00:00:00', '2025-01-20 04:00:00'),
(14, 1, 'General Ward B', 'general', 1, 'General patient care ward', 'active', '2023-01-15 00:00:00', '2025-01-20 04:00:00'),
(15, 1, 'ICU Ward', 'icu', 2, 'Intensive Care Unit', 'active', '2023-01-15 00:00:00', '2025-01-20 04:00:00'),
(16, 1, 'Private Cabin Block', 'cabin', 3, 'Private cabin rooms', 'active', '2023-01-15 00:00:00', '2025-01-20 04:00:00'),
(17, 2, 'General Ward', 'general', 1, 'General patient ward', 'active', '2023-03-10 00:00:00', '2025-01-20 04:00:00'),
(18, 2, 'CCU', 'icu', 2, 'Cardiac Care Unit', 'active', '2023-03-10 00:00:00', '2025-01-20 04:00:00');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `lara_activity_logs`
--
ALTER TABLE `lara_activity_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `lara_activity_logs_user_id_foreign` (`user_id`),
  ADD KEY `lara_activity_logs_clinic_id_foreign` (`clinic_id`),
  ADD KEY `lara_activity_logs_entity_type_entity_id_index` (`entity_type`,`entity_id`),
  ADD KEY `lara_activity_logs_created_at_index` (`created_at`);

--
-- Indexes for table `lara_admissions`
--
ALTER TABLE `lara_admissions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `lara_admissions_clinic_id_foreign` (`clinic_id`),
  ADD KEY `lara_admissions_patient_id_foreign` (`patient_id`),
  ADD KEY `lara_admissions_admitting_doctor_id_foreign` (`admitting_doctor_id`),
  ADD KEY `lara_admissions_current_bed_id_foreign` (`current_bed_id`),
  ADD KEY `lara_admissions_discharge_recommended_by_foreign` (`discharge_recommended_by`),
  ADD KEY `lara_admissions_discharged_by_foreign` (`discharged_by`);

--
-- Indexes for table `lara_admission_deposits`
--
ALTER TABLE `lara_admission_deposits`
  ADD PRIMARY KEY (`id`),
  ADD KEY `lara_admission_deposits_clinic_id_foreign` (`clinic_id`),
  ADD KEY `lara_admission_deposits_admission_id_foreign` (`admission_id`),
  ADD KEY `lara_admission_deposits_received_by_foreign` (`received_by`);

--
-- Indexes for table `lara_appointments`
--
ALTER TABLE `lara_appointments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `lara_appointments_patient_id_foreign` (`patient_id`),
  ADD KEY `lara_appointments_doctor_id_foreign` (`doctor_id`),
  ADD KEY `lara_appointments_department_id_foreign` (`department_id`),
  ADD KEY `lara_appointments_created_by_foreign` (`created_by`),
  ADD KEY `lara_appointments_clinic_id_appointment_date_index` (`clinic_id`,`appointment_date`);

--
-- Indexes for table `lara_appointment_status_logs`
--
ALTER TABLE `lara_appointment_status_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `lara_appointment_status_logs_appointment_id_foreign` (`appointment_id`),
  ADD KEY `lara_appointment_status_logs_changed_by_foreign` (`changed_by`),
  ADD KEY `lara_appointment_status_logs_clinic_id_foreign` (`clinic_id`);

--
-- Indexes for table `lara_beds`
--
ALTER TABLE `lara_beds`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `lara_beds_room_id_bed_number_unique` (`room_id`,`bed_number`),
  ADD KEY `lara_beds_clinic_id_foreign` (`clinic_id`);

--
-- Indexes for table `lara_bed_assignments`
--
ALTER TABLE `lara_bed_assignments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `lara_bed_assignments_admission_id_foreign` (`admission_id`),
  ADD KEY `lara_bed_assignments_bed_id_foreign` (`bed_id`),
  ADD KEY `lara_bed_assignments_clinic_id_foreign` (`clinic_id`);

--
-- Indexes for table `lara_cache`
--
ALTER TABLE `lara_cache`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `lara_cache_locks`
--
ALTER TABLE `lara_cache_locks`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `lara_clinics`
--
ALTER TABLE `lara_clinics`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `lara_clinics_code_unique` (`code`);

--
-- Indexes for table `lara_clinic_images`
--
ALTER TABLE `lara_clinic_images`
  ADD PRIMARY KEY (`id`),
  ADD KEY `lara_clinic_images_clinic_id_foreign` (`clinic_id`);

--
-- Indexes for table `lara_consultations`
--
ALTER TABLE `lara_consultations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `lara_consultations_visit_id_foreign` (`visit_id`),
  ADD KEY `lara_consultations_clinic_id_foreign` (`clinic_id`),
  ADD KEY `lara_consultations_doctor_id_foreign` (`doctor_id`),
  ADD KEY `lara_consultations_patient_id_foreign` (`patient_id`);

--
-- Indexes for table `lara_departments`
--
ALTER TABLE `lara_departments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `lara_departments_clinic_id_foreign` (`clinic_id`);

--
-- Indexes for table `lara_doctors`
--
ALTER TABLE `lara_doctors`
  ADD PRIMARY KEY (`id`),
  ADD KEY `lara_doctors_user_id_foreign` (`user_id`),
  ADD KEY `lara_doctors_primary_department_id_foreign` (`primary_department_id`);

--
-- Indexes for table `lara_doctor_awards`
--
ALTER TABLE `lara_doctor_awards`
  ADD PRIMARY KEY (`id`),
  ADD KEY `lara_doctor_awards_doctor_id_foreign` (`doctor_id`);

--
-- Indexes for table `lara_doctor_certifications`
--
ALTER TABLE `lara_doctor_certifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `lara_doctor_certifications_doctor_id_foreign` (`doctor_id`);

--
-- Indexes for table `lara_doctor_clinic`
--
ALTER TABLE `lara_doctor_clinic`
  ADD PRIMARY KEY (`id`),
  ADD KEY `lara_doctor_clinic_doctor_id_foreign` (`doctor_id`),
  ADD KEY `lara_doctor_clinic_clinic_id_foreign` (`clinic_id`);

--
-- Indexes for table `lara_doctor_education`
--
ALTER TABLE `lara_doctor_education`
  ADD PRIMARY KEY (`id`),
  ADD KEY `lara_doctor_education_doctor_id_foreign` (`doctor_id`);

--
-- Indexes for table `lara_doctor_schedules`
--
ALTER TABLE `lara_doctor_schedules`
  ADD PRIMARY KEY (`id`),
  ADD KEY `lara_doctor_schedules_doctor_id_foreign` (`doctor_id`),
  ADD KEY `lara_doctor_schedules_clinic_id_foreign` (`clinic_id`),
  ADD KEY `lara_doctor_schedules_department_id_foreign` (`department_id`),
  ADD KEY `lara_doctor_schedules_schedule_date_index` (`schedule_date`);

--
-- Indexes for table `lara_doctor_schedule_exceptions`
--
ALTER TABLE `lara_doctor_schedule_exceptions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `lara_doctor_schedule_exceptions_doctor_id_foreign` (`doctor_id`),
  ADD KEY `lara_doctor_schedule_exceptions_clinic_id_foreign` (`clinic_id`);

--
-- Indexes for table `lara_doctor_schedule_requests`
--
ALTER TABLE `lara_doctor_schedule_requests`
  ADD PRIMARY KEY (`id`),
  ADD KEY `lara_doctor_schedule_requests_doctor_id_foreign` (`doctor_id`),
  ADD KEY `lara_doctor_schedule_requests_clinic_id_foreign` (`clinic_id`),
  ADD KEY `lara_doctor_schedule_requests_requested_by_foreign` (`requested_by`),
  ADD KEY `lara_doctor_schedule_requests_processed_by_foreign` (`processed_by`);

--
-- Indexes for table `lara_failed_jobs`
--
ALTER TABLE `lara_failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `lara_failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `lara_inpatient_rounds`
--
ALTER TABLE `lara_inpatient_rounds`
  ADD PRIMARY KEY (`id`),
  ADD KEY `lara_inpatient_rounds_admission_id_foreign` (`admission_id`),
  ADD KEY `lara_inpatient_rounds_doctor_id_foreign` (`doctor_id`),
  ADD KEY `lara_inpatient_rounds_clinic_id_foreign` (`clinic_id`);

--
-- Indexes for table `lara_inpatient_services`
--
ALTER TABLE `lara_inpatient_services`
  ADD PRIMARY KEY (`id`),
  ADD KEY `lara_inpatient_services_admission_id_foreign` (`admission_id`),
  ADD KEY `lara_inpatient_services_clinic_id_foreign` (`clinic_id`);

--
-- Indexes for table `lara_invoices`
--
ALTER TABLE `lara_invoices`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `lara_invoices_invoice_number_unique` (`invoice_number`),
  ADD KEY `lara_invoices_clinic_id_foreign` (`clinic_id`),
  ADD KEY `lara_invoices_patient_id_foreign` (`patient_id`),
  ADD KEY `lara_invoices_appointment_id_foreign` (`appointment_id`),
  ADD KEY `lara_invoices_admission_id_foreign` (`admission_id`),
  ADD KEY `lara_invoices_visit_id_foreign` (`visit_id`),
  ADD KEY `lara_invoices_finalized_by_foreign` (`finalized_by`),
  ADD KEY `lara_invoices_created_by_foreign` (`created_by`);

--
-- Indexes for table `lara_invoice_items`
--
ALTER TABLE `lara_invoice_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `lara_invoice_items_invoice_id_foreign` (`invoice_id`),
  ADD KEY `lara_invoice_items_clinic_id_foreign` (`clinic_id`);

--
-- Indexes for table `lara_jobs`
--
ALTER TABLE `lara_jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `lara_jobs_queue_index` (`queue`);

--
-- Indexes for table `lara_job_batches`
--
ALTER TABLE `lara_job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `lara_lab_tests`
--
ALTER TABLE `lara_lab_tests`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `lara_lab_test_orders`
--
ALTER TABLE `lara_lab_test_orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `lara_lab_test_orders_appointment_id_foreign` (`appointment_id`),
  ADD KEY `lara_lab_test_orders_doctor_id_foreign` (`doctor_id`),
  ADD KEY `lara_lab_test_orders_patient_id_foreign` (`patient_id`),
  ADD KEY `lara_lab_test_orders_clinic_id_foreign` (`clinic_id`),
  ADD KEY `lara_lab_test_orders_lab_test_id_foreign` (`lab_test_id`),
  ADD KEY `lara_lab_test_orders_invoice_id_foreign` (`invoice_id`);

--
-- Indexes for table `lara_lab_test_results`
--
ALTER TABLE `lara_lab_test_results`
  ADD PRIMARY KEY (`id`),
  ADD KEY `lara_lab_test_results_lab_test_order_id_foreign` (`lab_test_order_id`),
  ADD KEY `lara_lab_test_results_lab_test_id_foreign` (`lab_test_id`),
  ADD KEY `lara_lab_test_results_reported_by_foreign` (`reported_by`),
  ADD KEY `lara_lab_test_results_clinic_id_foreign` (`clinic_id`);

--
-- Indexes for table `lara_medicines`
--
ALTER TABLE `lara_medicines`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `lara_medicine_batches`
--
ALTER TABLE `lara_medicine_batches`
  ADD PRIMARY KEY (`id`),
  ADD KEY `lara_medicine_batches_medicine_id_foreign` (`medicine_id`),
  ADD KEY `lara_medicine_batches_clinic_id_foreign` (`clinic_id`);

--
-- Indexes for table `lara_migrations`
--
ALTER TABLE `lara_migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `lara_notifications`
--
ALTER TABLE `lara_notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `lara_notifications_notifiable_type_notifiable_id_index` (`notifiable_type`,`notifiable_id`);

--
-- Indexes for table `lara_nursing_notes`
--
ALTER TABLE `lara_nursing_notes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `lara_nursing_notes_admission_id_foreign` (`admission_id`),
  ADD KEY `lara_nursing_notes_nurse_id_foreign` (`nurse_id`),
  ADD KEY `lara_nursing_notes_clinic_id_foreign` (`clinic_id`);

--
-- Indexes for table `lara_password_reset_tokens`
--
ALTER TABLE `lara_password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `lara_patients`
--
ALTER TABLE `lara_patients`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `lara_patients_patient_code_unique` (`patient_code`),
  ADD KEY `lara_patients_clinic_id_foreign` (`clinic_id`),
  ADD KEY `lara_patients_user_id_foreign` (`user_id`);

--
-- Indexes for table `lara_patient_allergies`
--
ALTER TABLE `lara_patient_allergies`
  ADD PRIMARY KEY (`id`),
  ADD KEY `lara_patient_allergies_patient_id_foreign` (`patient_id`);

--
-- Indexes for table `lara_patient_complaints`
--
ALTER TABLE `lara_patient_complaints`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `lara_patient_complaints_name_unique` (`name`),
  ADD KEY `lara_patient_complaints_clinic_id_foreign` (`clinic_id`);

--
-- Indexes for table `lara_patient_medical_history`
--
ALTER TABLE `lara_patient_medical_history`
  ADD PRIMARY KEY (`id`),
  ADD KEY `lara_patient_medical_history_patient_id_foreign` (`patient_id`);

--
-- Indexes for table `lara_patient_vitals`
--
ALTER TABLE `lara_patient_vitals`
  ADD PRIMARY KEY (`id`),
  ADD KEY `lara_patient_vitals_patient_id_foreign` (`patient_id`),
  ADD KEY `lara_patient_vitals_visit_id_foreign` (`visit_id`),
  ADD KEY `lara_patient_vitals_recorded_by_foreign` (`recorded_by`),
  ADD KEY `lara_patient_vitals_admission_id_foreign` (`admission_id`),
  ADD KEY `lara_patient_vitals_inpatient_round_id_foreign` (`inpatient_round_id`);

--
-- Indexes for table `lara_payments`
--
ALTER TABLE `lara_payments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `lara_payments_invoice_id_foreign` (`invoice_id`),
  ADD KEY `lara_payments_received_by_foreign` (`received_by`),
  ADD KEY `lara_payments_clinic_id_foreign` (`clinic_id`);

--
-- Indexes for table `lara_permissions`
--
ALTER TABLE `lara_permissions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `lara_permissions_name_unique` (`name`);

--
-- Indexes for table `lara_personal_access_tokens`
--
ALTER TABLE `lara_personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `lara_personal_access_tokens_token_unique` (`token`),
  ADD KEY `lara_personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`),
  ADD KEY `lara_personal_access_tokens_expires_at_index` (`expires_at`);

--
-- Indexes for table `lara_pharmacy_sales`
--
ALTER TABLE `lara_pharmacy_sales`
  ADD PRIMARY KEY (`id`),
  ADD KEY `lara_pharmacy_sales_prescription_id_foreign` (`prescription_id`),
  ADD KEY `lara_pharmacy_sales_patient_id_foreign` (`patient_id`),
  ADD KEY `lara_pharmacy_sales_clinic_id_foreign` (`clinic_id`);

--
-- Indexes for table `lara_pharmacy_sale_items`
--
ALTER TABLE `lara_pharmacy_sale_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `lara_pharmacy_sale_items_pharmacy_sale_id_foreign` (`pharmacy_sale_id`),
  ADD KEY `lara_pharmacy_sale_items_medicine_id_foreign` (`medicine_id`);

--
-- Indexes for table `lara_prescriptions`
--
ALTER TABLE `lara_prescriptions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `lara_prescriptions_consultation_id_foreign` (`consultation_id`),
  ADD KEY `lara_prescriptions_clinic_id_foreign` (`clinic_id`);

--
-- Indexes for table `lara_prescription_complaint`
--
ALTER TABLE `lara_prescription_complaint`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `lara_prescription_complaint_prescription_id_complaint_id_unique` (`prescription_id`,`complaint_id`),
  ADD KEY `lara_prescription_complaint_complaint_id_foreign` (`complaint_id`);

--
-- Indexes for table `lara_prescription_items`
--
ALTER TABLE `lara_prescription_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `lara_prescription_items_prescription_id_foreign` (`prescription_id`),
  ADD KEY `lara_prescription_items_medicine_id_foreign` (`medicine_id`),
  ADD KEY `lara_prescription_items_clinic_id_foreign` (`clinic_id`);

--
-- Indexes for table `lara_roles`
--
ALTER TABLE `lara_roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `lara_roles_name_unique` (`name`);

--
-- Indexes for table `lara_role_permission`
--
ALTER TABLE `lara_role_permission`
  ADD PRIMARY KEY (`id`),
  ADD KEY `lara_role_permission_role_id_foreign` (`role_id`),
  ADD KEY `lara_role_permission_permission_id_foreign` (`permission_id`);

--
-- Indexes for table `lara_rooms`
--
ALTER TABLE `lara_rooms`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `lara_rooms_ward_id_room_number_unique` (`ward_id`,`room_number`),
  ADD KEY `lara_rooms_clinic_id_foreign` (`clinic_id`);

--
-- Indexes for table `lara_sessions`
--
ALTER TABLE `lara_sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `lara_sessions_user_id_index` (`user_id`),
  ADD KEY `lara_sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `lara_users`
--
ALTER TABLE `lara_users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `lara_users_email_unique` (`email`),
  ADD KEY `lara_users_clinic_id_foreign` (`clinic_id`);

--
-- Indexes for table `lara_user_role`
--
ALTER TABLE `lara_user_role`
  ADD PRIMARY KEY (`id`),
  ADD KEY `lara_user_role_user_id_foreign` (`user_id`),
  ADD KEY `lara_user_role_role_id_foreign` (`role_id`);

--
-- Indexes for table `lara_visits`
--
ALTER TABLE `lara_visits`
  ADD PRIMARY KEY (`id`),
  ADD KEY `lara_visits_appointment_id_foreign` (`appointment_id`),
  ADD KEY `lara_visits_clinic_id_foreign` (`clinic_id`),
  ADD KEY `lara_visits_consultation_id_foreign` (`consultation_id`);

--
-- Indexes for table `lara_wards`
--
ALTER TABLE `lara_wards`
  ADD PRIMARY KEY (`id`),
  ADD KEY `lara_wards_clinic_id_foreign` (`clinic_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `lara_activity_logs`
--
ALTER TABLE `lara_activity_logs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4818;

--
-- AUTO_INCREMENT for table `lara_admissions`
--
ALTER TABLE `lara_admissions`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT for table `lara_admission_deposits`
--
ALTER TABLE `lara_admission_deposits`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT for table `lara_appointments`
--
ALTER TABLE `lara_appointments`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=337;

--
-- AUTO_INCREMENT for table `lara_appointment_status_logs`
--
ALTER TABLE `lara_appointment_status_logs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=321;

--
-- AUTO_INCREMENT for table `lara_beds`
--
ALTER TABLE `lara_beds`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=131;

--
-- AUTO_INCREMENT for table `lara_bed_assignments`
--
ALTER TABLE `lara_bed_assignments`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT for table `lara_clinics`
--
ALTER TABLE `lara_clinics`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `lara_clinic_images`
--
ALTER TABLE `lara_clinic_images`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `lara_consultations`
--
ALTER TABLE `lara_consultations`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `lara_departments`
--
ALTER TABLE `lara_departments`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `lara_doctors`
--
ALTER TABLE `lara_doctors`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `lara_doctor_awards`
--
ALTER TABLE `lara_doctor_awards`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `lara_doctor_certifications`
--
ALTER TABLE `lara_doctor_certifications`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `lara_doctor_clinic`
--
ALTER TABLE `lara_doctor_clinic`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `lara_doctor_education`
--
ALTER TABLE `lara_doctor_education`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `lara_doctor_schedules`
--
ALTER TABLE `lara_doctor_schedules`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `lara_doctor_schedule_exceptions`
--
ALTER TABLE `lara_doctor_schedule_exceptions`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `lara_doctor_schedule_requests`
--
ALTER TABLE `lara_doctor_schedule_requests`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `lara_failed_jobs`
--
ALTER TABLE `lara_failed_jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `lara_inpatient_rounds`
--
ALTER TABLE `lara_inpatient_rounds`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

--
-- AUTO_INCREMENT for table `lara_inpatient_services`
--
ALTER TABLE `lara_inpatient_services`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT for table `lara_invoices`
--
ALTER TABLE `lara_invoices`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=587;

--
-- AUTO_INCREMENT for table `lara_invoice_items`
--
ALTER TABLE `lara_invoice_items`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=777;

--
-- AUTO_INCREMENT for table `lara_jobs`
--
ALTER TABLE `lara_jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `lara_lab_tests`
--
ALTER TABLE `lara_lab_tests`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `lara_lab_test_orders`
--
ALTER TABLE `lara_lab_test_orders`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=140;

--
-- AUTO_INCREMENT for table `lara_lab_test_results`
--
ALTER TABLE `lara_lab_test_results`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=129;

--
-- AUTO_INCREMENT for table `lara_medicines`
--
ALTER TABLE `lara_medicines`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `lara_medicine_batches`
--
ALTER TABLE `lara_medicine_batches`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT for table `lara_migrations`
--
ALTER TABLE `lara_migrations`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=88;

--
-- AUTO_INCREMENT for table `lara_nursing_notes`
--
ALTER TABLE `lara_nursing_notes`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

--
-- AUTO_INCREMENT for table `lara_patients`
--
ALTER TABLE `lara_patients`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=156;

--
-- AUTO_INCREMENT for table `lara_patient_allergies`
--
ALTER TABLE `lara_patient_allergies`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=43;

--
-- AUTO_INCREMENT for table `lara_patient_complaints`
--
ALTER TABLE `lara_patient_complaints`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `lara_patient_medical_history`
--
ALTER TABLE `lara_patient_medical_history`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=157;

--
-- AUTO_INCREMENT for table `lara_patient_vitals`
--
ALTER TABLE `lara_patient_vitals`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=321;

--
-- AUTO_INCREMENT for table `lara_payments`
--
ALTER TABLE `lara_payments`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=608;

--
-- AUTO_INCREMENT for table `lara_permissions`
--
ALTER TABLE `lara_permissions`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=59;

--
-- AUTO_INCREMENT for table `lara_personal_access_tokens`
--
ALTER TABLE `lara_personal_access_tokens`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `lara_pharmacy_sales`
--
ALTER TABLE `lara_pharmacy_sales`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=159;

--
-- AUTO_INCREMENT for table `lara_pharmacy_sale_items`
--
ALTER TABLE `lara_pharmacy_sale_items`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=306;

--
-- AUTO_INCREMENT for table `lara_prescriptions`
--
ALTER TABLE `lara_prescriptions`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=169;

--
-- AUTO_INCREMENT for table `lara_prescription_complaint`
--
ALTER TABLE `lara_prescription_complaint`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=236;

--
-- AUTO_INCREMENT for table `lara_prescription_items`
--
ALTER TABLE `lara_prescription_items`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=309;

--
-- AUTO_INCREMENT for table `lara_roles`
--
ALTER TABLE `lara_roles`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `lara_role_permission`
--
ALTER TABLE `lara_role_permission`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=194;

--
-- AUTO_INCREMENT for table `lara_rooms`
--
ALTER TABLE `lara_rooms`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=70;

--
-- AUTO_INCREMENT for table `lara_users`
--
ALTER TABLE `lara_users`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `lara_user_role`
--
ALTER TABLE `lara_user_role`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `lara_visits`
--
ALTER TABLE `lara_visits`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=337;

--
-- AUTO_INCREMENT for table `lara_wards`
--
ALTER TABLE `lara_wards`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `lara_activity_logs`
--
ALTER TABLE `lara_activity_logs`
  ADD CONSTRAINT `lara_activity_logs_clinic_id_foreign` FOREIGN KEY (`clinic_id`) REFERENCES `lara_clinics` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `lara_activity_logs_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `lara_users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `lara_admissions`
--
ALTER TABLE `lara_admissions`
  ADD CONSTRAINT `lara_admissions_admitting_doctor_id_foreign` FOREIGN KEY (`admitting_doctor_id`) REFERENCES `lara_doctors` (`id`),
  ADD CONSTRAINT `lara_admissions_clinic_id_foreign` FOREIGN KEY (`clinic_id`) REFERENCES `lara_clinics` (`id`),
  ADD CONSTRAINT `lara_admissions_current_bed_id_foreign` FOREIGN KEY (`current_bed_id`) REFERENCES `lara_beds` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `lara_admissions_discharge_recommended_by_foreign` FOREIGN KEY (`discharge_recommended_by`) REFERENCES `lara_users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `lara_admissions_discharged_by_foreign` FOREIGN KEY (`discharged_by`) REFERENCES `lara_users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `lara_admissions_patient_id_foreign` FOREIGN KEY (`patient_id`) REFERENCES `lara_patients` (`id`);

--
-- Constraints for table `lara_admission_deposits`
--
ALTER TABLE `lara_admission_deposits`
  ADD CONSTRAINT `lara_admission_deposits_admission_id_foreign` FOREIGN KEY (`admission_id`) REFERENCES `lara_admissions` (`id`),
  ADD CONSTRAINT `lara_admission_deposits_clinic_id_foreign` FOREIGN KEY (`clinic_id`) REFERENCES `lara_clinics` (`id`),
  ADD CONSTRAINT `lara_admission_deposits_received_by_foreign` FOREIGN KEY (`received_by`) REFERENCES `lara_users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `lara_appointments`
--
ALTER TABLE `lara_appointments`
  ADD CONSTRAINT `lara_appointments_clinic_id_foreign` FOREIGN KEY (`clinic_id`) REFERENCES `lara_clinics` (`id`),
  ADD CONSTRAINT `lara_appointments_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `lara_users` (`id`),
  ADD CONSTRAINT `lara_appointments_department_id_foreign` FOREIGN KEY (`department_id`) REFERENCES `lara_departments` (`id`),
  ADD CONSTRAINT `lara_appointments_doctor_id_foreign` FOREIGN KEY (`doctor_id`) REFERENCES `lara_doctors` (`id`),
  ADD CONSTRAINT `lara_appointments_patient_id_foreign` FOREIGN KEY (`patient_id`) REFERENCES `lara_patients` (`id`);

--
-- Constraints for table `lara_appointment_status_logs`
--
ALTER TABLE `lara_appointment_status_logs`
  ADD CONSTRAINT `lara_appointment_status_logs_appointment_id_foreign` FOREIGN KEY (`appointment_id`) REFERENCES `lara_appointments` (`id`),
  ADD CONSTRAINT `lara_appointment_status_logs_changed_by_foreign` FOREIGN KEY (`changed_by`) REFERENCES `lara_users` (`id`),
  ADD CONSTRAINT `lara_appointment_status_logs_clinic_id_foreign` FOREIGN KEY (`clinic_id`) REFERENCES `lara_clinics` (`id`);

--
-- Constraints for table `lara_beds`
--
ALTER TABLE `lara_beds`
  ADD CONSTRAINT `lara_beds_clinic_id_foreign` FOREIGN KEY (`clinic_id`) REFERENCES `lara_clinics` (`id`),
  ADD CONSTRAINT `lara_beds_room_id_foreign` FOREIGN KEY (`room_id`) REFERENCES `lara_rooms` (`id`);

--
-- Constraints for table `lara_bed_assignments`
--
ALTER TABLE `lara_bed_assignments`
  ADD CONSTRAINT `lara_bed_assignments_admission_id_foreign` FOREIGN KEY (`admission_id`) REFERENCES `lara_admissions` (`id`),
  ADD CONSTRAINT `lara_bed_assignments_bed_id_foreign` FOREIGN KEY (`bed_id`) REFERENCES `lara_beds` (`id`),
  ADD CONSTRAINT `lara_bed_assignments_clinic_id_foreign` FOREIGN KEY (`clinic_id`) REFERENCES `lara_clinics` (`id`);

--
-- Constraints for table `lara_clinic_images`
--
ALTER TABLE `lara_clinic_images`
  ADD CONSTRAINT `lara_clinic_images_clinic_id_foreign` FOREIGN KEY (`clinic_id`) REFERENCES `lara_clinics` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `lara_consultations`
--
ALTER TABLE `lara_consultations`
  ADD CONSTRAINT `lara_consultations_clinic_id_foreign` FOREIGN KEY (`clinic_id`) REFERENCES `lara_clinics` (`id`),
  ADD CONSTRAINT `lara_consultations_doctor_id_foreign` FOREIGN KEY (`doctor_id`) REFERENCES `lara_doctors` (`id`),
  ADD CONSTRAINT `lara_consultations_patient_id_foreign` FOREIGN KEY (`patient_id`) REFERENCES `lara_patients` (`id`),
  ADD CONSTRAINT `lara_consultations_visit_id_foreign` FOREIGN KEY (`visit_id`) REFERENCES `lara_visits` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `lara_departments`
--
ALTER TABLE `lara_departments`
  ADD CONSTRAINT `lara_departments_clinic_id_foreign` FOREIGN KEY (`clinic_id`) REFERENCES `lara_clinics` (`id`);

--
-- Constraints for table `lara_doctors`
--
ALTER TABLE `lara_doctors`
  ADD CONSTRAINT `lara_doctors_primary_department_id_foreign` FOREIGN KEY (`primary_department_id`) REFERENCES `lara_departments` (`id`),
  ADD CONSTRAINT `lara_doctors_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `lara_users` (`id`);

--
-- Constraints for table `lara_doctor_awards`
--
ALTER TABLE `lara_doctor_awards`
  ADD CONSTRAINT `lara_doctor_awards_doctor_id_foreign` FOREIGN KEY (`doctor_id`) REFERENCES `lara_doctors` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `lara_doctor_certifications`
--
ALTER TABLE `lara_doctor_certifications`
  ADD CONSTRAINT `lara_doctor_certifications_doctor_id_foreign` FOREIGN KEY (`doctor_id`) REFERENCES `lara_doctors` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `lara_doctor_clinic`
--
ALTER TABLE `lara_doctor_clinic`
  ADD CONSTRAINT `lara_doctor_clinic_clinic_id_foreign` FOREIGN KEY (`clinic_id`) REFERENCES `lara_clinics` (`id`),
  ADD CONSTRAINT `lara_doctor_clinic_doctor_id_foreign` FOREIGN KEY (`doctor_id`) REFERENCES `lara_doctors` (`id`);

--
-- Constraints for table `lara_doctor_education`
--
ALTER TABLE `lara_doctor_education`
  ADD CONSTRAINT `lara_doctor_education_doctor_id_foreign` FOREIGN KEY (`doctor_id`) REFERENCES `lara_doctors` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `lara_doctor_schedules`
--
ALTER TABLE `lara_doctor_schedules`
  ADD CONSTRAINT `lara_doctor_schedules_clinic_id_foreign` FOREIGN KEY (`clinic_id`) REFERENCES `lara_clinics` (`id`),
  ADD CONSTRAINT `lara_doctor_schedules_department_id_foreign` FOREIGN KEY (`department_id`) REFERENCES `lara_departments` (`id`),
  ADD CONSTRAINT `lara_doctor_schedules_doctor_id_foreign` FOREIGN KEY (`doctor_id`) REFERENCES `lara_doctors` (`id`);

--
-- Constraints for table `lara_doctor_schedule_exceptions`
--
ALTER TABLE `lara_doctor_schedule_exceptions`
  ADD CONSTRAINT `lara_doctor_schedule_exceptions_clinic_id_foreign` FOREIGN KEY (`clinic_id`) REFERENCES `lara_clinics` (`id`),
  ADD CONSTRAINT `lara_doctor_schedule_exceptions_doctor_id_foreign` FOREIGN KEY (`doctor_id`) REFERENCES `lara_doctors` (`id`);

--
-- Constraints for table `lara_doctor_schedule_requests`
--
ALTER TABLE `lara_doctor_schedule_requests`
  ADD CONSTRAINT `lara_doctor_schedule_requests_clinic_id_foreign` FOREIGN KEY (`clinic_id`) REFERENCES `lara_clinics` (`id`),
  ADD CONSTRAINT `lara_doctor_schedule_requests_doctor_id_foreign` FOREIGN KEY (`doctor_id`) REFERENCES `lara_doctors` (`id`),
  ADD CONSTRAINT `lara_doctor_schedule_requests_processed_by_foreign` FOREIGN KEY (`processed_by`) REFERENCES `lara_users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `lara_doctor_schedule_requests_requested_by_foreign` FOREIGN KEY (`requested_by`) REFERENCES `lara_users` (`id`);

--
-- Constraints for table `lara_inpatient_rounds`
--
ALTER TABLE `lara_inpatient_rounds`
  ADD CONSTRAINT `lara_inpatient_rounds_admission_id_foreign` FOREIGN KEY (`admission_id`) REFERENCES `lara_admissions` (`id`),
  ADD CONSTRAINT `lara_inpatient_rounds_clinic_id_foreign` FOREIGN KEY (`clinic_id`) REFERENCES `lara_clinics` (`id`),
  ADD CONSTRAINT `lara_inpatient_rounds_doctor_id_foreign` FOREIGN KEY (`doctor_id`) REFERENCES `lara_doctors` (`id`);

--
-- Constraints for table `lara_inpatient_services`
--
ALTER TABLE `lara_inpatient_services`
  ADD CONSTRAINT `lara_inpatient_services_admission_id_foreign` FOREIGN KEY (`admission_id`) REFERENCES `lara_admissions` (`id`),
  ADD CONSTRAINT `lara_inpatient_services_clinic_id_foreign` FOREIGN KEY (`clinic_id`) REFERENCES `lara_clinics` (`id`);

--
-- Constraints for table `lara_invoices`
--
ALTER TABLE `lara_invoices`
  ADD CONSTRAINT `lara_invoices_admission_id_foreign` FOREIGN KEY (`admission_id`) REFERENCES `lara_admissions` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `lara_invoices_appointment_id_foreign` FOREIGN KEY (`appointment_id`) REFERENCES `lara_appointments` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `lara_invoices_clinic_id_foreign` FOREIGN KEY (`clinic_id`) REFERENCES `lara_clinics` (`id`),
  ADD CONSTRAINT `lara_invoices_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `lara_users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `lara_invoices_finalized_by_foreign` FOREIGN KEY (`finalized_by`) REFERENCES `lara_users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `lara_invoices_patient_id_foreign` FOREIGN KEY (`patient_id`) REFERENCES `lara_patients` (`id`),
  ADD CONSTRAINT `lara_invoices_visit_id_foreign` FOREIGN KEY (`visit_id`) REFERENCES `lara_visits` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `lara_invoice_items`
--
ALTER TABLE `lara_invoice_items`
  ADD CONSTRAINT `lara_invoice_items_clinic_id_foreign` FOREIGN KEY (`clinic_id`) REFERENCES `lara_clinics` (`id`),
  ADD CONSTRAINT `lara_invoice_items_invoice_id_foreign` FOREIGN KEY (`invoice_id`) REFERENCES `lara_invoices` (`id`);

--
-- Constraints for table `lara_lab_test_orders`
--
ALTER TABLE `lara_lab_test_orders`
  ADD CONSTRAINT `lara_lab_test_orders_appointment_id_foreign` FOREIGN KEY (`appointment_id`) REFERENCES `lara_appointments` (`id`),
  ADD CONSTRAINT `lara_lab_test_orders_clinic_id_foreign` FOREIGN KEY (`clinic_id`) REFERENCES `lara_clinics` (`id`),
  ADD CONSTRAINT `lara_lab_test_orders_doctor_id_foreign` FOREIGN KEY (`doctor_id`) REFERENCES `lara_doctors` (`id`),
  ADD CONSTRAINT `lara_lab_test_orders_invoice_id_foreign` FOREIGN KEY (`invoice_id`) REFERENCES `lara_invoices` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `lara_lab_test_orders_lab_test_id_foreign` FOREIGN KEY (`lab_test_id`) REFERENCES `lara_lab_tests` (`id`),
  ADD CONSTRAINT `lara_lab_test_orders_patient_id_foreign` FOREIGN KEY (`patient_id`) REFERENCES `lara_patients` (`id`);

--
-- Constraints for table `lara_lab_test_results`
--
ALTER TABLE `lara_lab_test_results`
  ADD CONSTRAINT `lara_lab_test_results_clinic_id_foreign` FOREIGN KEY (`clinic_id`) REFERENCES `lara_clinics` (`id`),
  ADD CONSTRAINT `lara_lab_test_results_lab_test_id_foreign` FOREIGN KEY (`lab_test_id`) REFERENCES `lara_lab_tests` (`id`),
  ADD CONSTRAINT `lara_lab_test_results_lab_test_order_id_foreign` FOREIGN KEY (`lab_test_order_id`) REFERENCES `lara_lab_test_orders` (`id`),
  ADD CONSTRAINT `lara_lab_test_results_reported_by_foreign` FOREIGN KEY (`reported_by`) REFERENCES `lara_users` (`id`);

--
-- Constraints for table `lara_medicine_batches`
--
ALTER TABLE `lara_medicine_batches`
  ADD CONSTRAINT `lara_medicine_batches_clinic_id_foreign` FOREIGN KEY (`clinic_id`) REFERENCES `lara_clinics` (`id`),
  ADD CONSTRAINT `lara_medicine_batches_medicine_id_foreign` FOREIGN KEY (`medicine_id`) REFERENCES `lara_medicines` (`id`);

--
-- Constraints for table `lara_nursing_notes`
--
ALTER TABLE `lara_nursing_notes`
  ADD CONSTRAINT `lara_nursing_notes_admission_id_foreign` FOREIGN KEY (`admission_id`) REFERENCES `lara_admissions` (`id`),
  ADD CONSTRAINT `lara_nursing_notes_clinic_id_foreign` FOREIGN KEY (`clinic_id`) REFERENCES `lara_clinics` (`id`),
  ADD CONSTRAINT `lara_nursing_notes_nurse_id_foreign` FOREIGN KEY (`nurse_id`) REFERENCES `lara_users` (`id`);

--
-- Constraints for table `lara_patients`
--
ALTER TABLE `lara_patients`
  ADD CONSTRAINT `lara_patients_clinic_id_foreign` FOREIGN KEY (`clinic_id`) REFERENCES `lara_clinics` (`id`),
  ADD CONSTRAINT `lara_patients_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `lara_users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `lara_patient_allergies`
--
ALTER TABLE `lara_patient_allergies`
  ADD CONSTRAINT `lara_patient_allergies_patient_id_foreign` FOREIGN KEY (`patient_id`) REFERENCES `lara_patients` (`id`);

--
-- Constraints for table `lara_patient_complaints`
--
ALTER TABLE `lara_patient_complaints`
  ADD CONSTRAINT `lara_patient_complaints_clinic_id_foreign` FOREIGN KEY (`clinic_id`) REFERENCES `lara_clinics` (`id`);

--
-- Constraints for table `lara_patient_medical_history`
--
ALTER TABLE `lara_patient_medical_history`
  ADD CONSTRAINT `lara_patient_medical_history_patient_id_foreign` FOREIGN KEY (`patient_id`) REFERENCES `lara_patients` (`id`);

--
-- Constraints for table `lara_patient_vitals`
--
ALTER TABLE `lara_patient_vitals`
  ADD CONSTRAINT `lara_patient_vitals_admission_id_foreign` FOREIGN KEY (`admission_id`) REFERENCES `lara_admissions` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `lara_patient_vitals_inpatient_round_id_foreign` FOREIGN KEY (`inpatient_round_id`) REFERENCES `lara_inpatient_rounds` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `lara_patient_vitals_patient_id_foreign` FOREIGN KEY (`patient_id`) REFERENCES `lara_patients` (`id`),
  ADD CONSTRAINT `lara_patient_vitals_recorded_by_foreign` FOREIGN KEY (`recorded_by`) REFERENCES `lara_users` (`id`),
  ADD CONSTRAINT `lara_patient_vitals_visit_id_foreign` FOREIGN KEY (`visit_id`) REFERENCES `lara_visits` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `lara_payments`
--
ALTER TABLE `lara_payments`
  ADD CONSTRAINT `lara_payments_clinic_id_foreign` FOREIGN KEY (`clinic_id`) REFERENCES `lara_clinics` (`id`),
  ADD CONSTRAINT `lara_payments_invoice_id_foreign` FOREIGN KEY (`invoice_id`) REFERENCES `lara_invoices` (`id`),
  ADD CONSTRAINT `lara_payments_received_by_foreign` FOREIGN KEY (`received_by`) REFERENCES `lara_users` (`id`);

--
-- Constraints for table `lara_pharmacy_sales`
--
ALTER TABLE `lara_pharmacy_sales`
  ADD CONSTRAINT `lara_pharmacy_sales_clinic_id_foreign` FOREIGN KEY (`clinic_id`) REFERENCES `lara_clinics` (`id`),
  ADD CONSTRAINT `lara_pharmacy_sales_patient_id_foreign` FOREIGN KEY (`patient_id`) REFERENCES `lara_patients` (`id`),
  ADD CONSTRAINT `lara_pharmacy_sales_prescription_id_foreign` FOREIGN KEY (`prescription_id`) REFERENCES `lara_prescriptions` (`id`);

--
-- Constraints for table `lara_pharmacy_sale_items`
--
ALTER TABLE `lara_pharmacy_sale_items`
  ADD CONSTRAINT `lara_pharmacy_sale_items_medicine_id_foreign` FOREIGN KEY (`medicine_id`) REFERENCES `lara_medicines` (`id`),
  ADD CONSTRAINT `lara_pharmacy_sale_items_pharmacy_sale_id_foreign` FOREIGN KEY (`pharmacy_sale_id`) REFERENCES `lara_pharmacy_sales` (`id`);

--
-- Constraints for table `lara_prescriptions`
--
ALTER TABLE `lara_prescriptions`
  ADD CONSTRAINT `lara_prescriptions_clinic_id_foreign` FOREIGN KEY (`clinic_id`) REFERENCES `lara_clinics` (`id`),
  ADD CONSTRAINT `lara_prescriptions_consultation_id_foreign` FOREIGN KEY (`consultation_id`) REFERENCES `lara_consultations` (`id`);

--
-- Constraints for table `lara_prescription_complaint`
--
ALTER TABLE `lara_prescription_complaint`
  ADD CONSTRAINT `lara_prescription_complaint_complaint_id_foreign` FOREIGN KEY (`complaint_id`) REFERENCES `lara_patient_complaints` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `lara_prescription_complaint_prescription_id_foreign` FOREIGN KEY (`prescription_id`) REFERENCES `lara_prescriptions` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `lara_prescription_items`
--
ALTER TABLE `lara_prescription_items`
  ADD CONSTRAINT `lara_prescription_items_clinic_id_foreign` FOREIGN KEY (`clinic_id`) REFERENCES `lara_clinics` (`id`),
  ADD CONSTRAINT `lara_prescription_items_medicine_id_foreign` FOREIGN KEY (`medicine_id`) REFERENCES `lara_medicines` (`id`),
  ADD CONSTRAINT `lara_prescription_items_prescription_id_foreign` FOREIGN KEY (`prescription_id`) REFERENCES `lara_prescriptions` (`id`);

--
-- Constraints for table `lara_role_permission`
--
ALTER TABLE `lara_role_permission`
  ADD CONSTRAINT `lara_role_permission_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `lara_permissions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `lara_role_permission_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `lara_roles` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `lara_rooms`
--
ALTER TABLE `lara_rooms`
  ADD CONSTRAINT `lara_rooms_clinic_id_foreign` FOREIGN KEY (`clinic_id`) REFERENCES `lara_clinics` (`id`),
  ADD CONSTRAINT `lara_rooms_ward_id_foreign` FOREIGN KEY (`ward_id`) REFERENCES `lara_wards` (`id`);

--
-- Constraints for table `lara_users`
--
ALTER TABLE `lara_users`
  ADD CONSTRAINT `lara_users_clinic_id_foreign` FOREIGN KEY (`clinic_id`) REFERENCES `lara_clinics` (`id`);

--
-- Constraints for table `lara_user_role`
--
ALTER TABLE `lara_user_role`
  ADD CONSTRAINT `lara_user_role_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `lara_roles` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `lara_user_role_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `lara_users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `lara_visits`
--
ALTER TABLE `lara_visits`
  ADD CONSTRAINT `lara_visits_appointment_id_foreign` FOREIGN KEY (`appointment_id`) REFERENCES `lara_appointments` (`id`),
  ADD CONSTRAINT `lara_visits_clinic_id_foreign` FOREIGN KEY (`clinic_id`) REFERENCES `lara_clinics` (`id`),
  ADD CONSTRAINT `lara_visits_consultation_id_foreign` FOREIGN KEY (`consultation_id`) REFERENCES `lara_consultations` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `lara_wards`
--
ALTER TABLE `lara_wards`
  ADD CONSTRAINT `lara_wards_clinic_id_foreign` FOREIGN KEY (`clinic_id`) REFERENCES `lara_clinics` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
