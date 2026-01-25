-- Database Schema Dump
-- Generated: 2026-01-24 12:52:14
-- Database: project_hms

SET FOREIGN_KEY_CHECKS=0;

-- Table structure for table `lara_activity_logs`
DROP TABLE IF EXISTS `lara_activity_logs`;
CREATE TABLE `lara_activity_logs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned DEFAULT NULL,
  `clinic_id` bigint unsigned DEFAULT NULL,
  `action` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `entity_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `entity_id` bigint unsigned NOT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `lara_activity_logs_user_id_foreign` (`user_id`),
  KEY `lara_activity_logs_clinic_id_foreign` (`clinic_id`),
  KEY `lara_activity_logs_entity_type_entity_id_index` (`entity_type`,`entity_id`),
  KEY `lara_activity_logs_created_at_index` (`created_at`),
  CONSTRAINT `lara_activity_logs_clinic_id_foreign` FOREIGN KEY (`clinic_id`) REFERENCES `lara_clinics` (`id`) ON DELETE SET NULL,
  CONSTRAINT `lara_activity_logs_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `lara_users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=4774 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table structure for table `lara_admission_deposits`
DROP TABLE IF EXISTS `lara_admission_deposits`;
CREATE TABLE `lara_admission_deposits` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `clinic_id` bigint unsigned NOT NULL,
  `admission_id` bigint unsigned NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `payment_method` enum('cash','card','bank_transfer') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'cash',
  `transaction_reference` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `received_at` datetime DEFAULT NULL,
  `received_by` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `lara_admission_deposits_clinic_id_foreign` (`clinic_id`),
  KEY `lara_admission_deposits_admission_id_foreign` (`admission_id`),
  KEY `lara_admission_deposits_received_by_foreign` (`received_by`),
  CONSTRAINT `lara_admission_deposits_admission_id_foreign` FOREIGN KEY (`admission_id`) REFERENCES `lara_admissions` (`id`),
  CONSTRAINT `lara_admission_deposits_clinic_id_foreign` FOREIGN KEY (`clinic_id`) REFERENCES `lara_clinics` (`id`),
  CONSTRAINT `lara_admission_deposits_received_by_foreign` FOREIGN KEY (`received_by`) REFERENCES `lara_users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=32 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table structure for table `lara_admissions`
DROP TABLE IF EXISTS `lara_admissions`;
CREATE TABLE `lara_admissions` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `clinic_id` bigint unsigned NOT NULL,
  `patient_id` bigint unsigned NOT NULL,
  `admitting_doctor_id` bigint unsigned NOT NULL,
  `admission_date` datetime NOT NULL,
  `admission_reason` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `current_bed_id` bigint unsigned DEFAULT NULL,
  `status` enum('admitted','transferred','discharged') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'admitted',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `discharge_recommended` tinyint(1) NOT NULL DEFAULT '0',
  `discharge_recommended_by` bigint unsigned DEFAULT NULL,
  `discharged_by` bigint unsigned DEFAULT NULL,
  `discharge_date` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `lara_admissions_clinic_id_foreign` (`clinic_id`),
  KEY `lara_admissions_patient_id_foreign` (`patient_id`),
  KEY `lara_admissions_admitting_doctor_id_foreign` (`admitting_doctor_id`),
  KEY `lara_admissions_current_bed_id_foreign` (`current_bed_id`),
  KEY `lara_admissions_discharge_recommended_by_foreign` (`discharge_recommended_by`),
  KEY `lara_admissions_discharged_by_foreign` (`discharged_by`),
  CONSTRAINT `lara_admissions_admitting_doctor_id_foreign` FOREIGN KEY (`admitting_doctor_id`) REFERENCES `lara_doctors` (`id`),
  CONSTRAINT `lara_admissions_clinic_id_foreign` FOREIGN KEY (`clinic_id`) REFERENCES `lara_clinics` (`id`),
  CONSTRAINT `lara_admissions_current_bed_id_foreign` FOREIGN KEY (`current_bed_id`) REFERENCES `lara_beds` (`id`) ON DELETE SET NULL,
  CONSTRAINT `lara_admissions_discharge_recommended_by_foreign` FOREIGN KEY (`discharge_recommended_by`) REFERENCES `lara_users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `lara_admissions_discharged_by_foreign` FOREIGN KEY (`discharged_by`) REFERENCES `lara_users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `lara_admissions_patient_id_foreign` FOREIGN KEY (`patient_id`) REFERENCES `lara_patients` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=32 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table structure for table `lara_appointment_status_logs`
DROP TABLE IF EXISTS `lara_appointment_status_logs`;
CREATE TABLE `lara_appointment_status_logs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `appointment_id` bigint unsigned NOT NULL,
  `old_status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `new_status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `changed_by` bigint unsigned NOT NULL,
  `change_reason` text COLLATE utf8mb4_unicode_ci,
  `changed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `clinic_id` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `lara_appointment_status_logs_appointment_id_foreign` (`appointment_id`),
  KEY `lara_appointment_status_logs_changed_by_foreign` (`changed_by`),
  KEY `lara_appointment_status_logs_clinic_id_foreign` (`clinic_id`),
  CONSTRAINT `lara_appointment_status_logs_appointment_id_foreign` FOREIGN KEY (`appointment_id`) REFERENCES `lara_appointments` (`id`),
  CONSTRAINT `lara_appointment_status_logs_changed_by_foreign` FOREIGN KEY (`changed_by`) REFERENCES `lara_users` (`id`),
  CONSTRAINT `lara_appointment_status_logs_clinic_id_foreign` FOREIGN KEY (`clinic_id`) REFERENCES `lara_clinics` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=301 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table structure for table `lara_appointments`
DROP TABLE IF EXISTS `lara_appointments`;
CREATE TABLE `lara_appointments` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `clinic_id` bigint unsigned NOT NULL,
  `patient_id` bigint unsigned NOT NULL,
  `doctor_id` bigint unsigned NOT NULL,
  `department_id` bigint unsigned NOT NULL,
  `appointment_date` date NOT NULL,
  `start_time` time NOT NULL,
  `end_time` time NOT NULL,
  `appointment_type` enum('online','in_person') COLLATE utf8mb4_unicode_ci NOT NULL,
  `reason_for_visit` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `booking_source` enum('reception','patient_portal') COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `fee` decimal(10,2) DEFAULT NULL,
  `visit_type` enum('new','follow_up') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'new',
  `created_by` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `lara_appointments_patient_id_foreign` (`patient_id`),
  KEY `lara_appointments_doctor_id_foreign` (`doctor_id`),
  KEY `lara_appointments_department_id_foreign` (`department_id`),
  KEY `lara_appointments_created_by_foreign` (`created_by`),
  KEY `lara_appointments_clinic_id_appointment_date_index` (`clinic_id`,`appointment_date`),
  CONSTRAINT `lara_appointments_clinic_id_foreign` FOREIGN KEY (`clinic_id`) REFERENCES `lara_clinics` (`id`),
  CONSTRAINT `lara_appointments_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `lara_users` (`id`),
  CONSTRAINT `lara_appointments_department_id_foreign` FOREIGN KEY (`department_id`) REFERENCES `lara_departments` (`id`),
  CONSTRAINT `lara_appointments_doctor_id_foreign` FOREIGN KEY (`doctor_id`) REFERENCES `lara_doctors` (`id`),
  CONSTRAINT `lara_appointments_patient_id_foreign` FOREIGN KEY (`patient_id`) REFERENCES `lara_patients` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=307 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table structure for table `lara_bed_assignments`
DROP TABLE IF EXISTS `lara_bed_assignments`;
CREATE TABLE `lara_bed_assignments` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `admission_id` bigint unsigned NOT NULL,
  `bed_id` bigint unsigned NOT NULL,
  `assigned_at` datetime NOT NULL,
  `released_at` datetime DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `clinic_id` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `lara_bed_assignments_admission_id_foreign` (`admission_id`),
  KEY `lara_bed_assignments_bed_id_foreign` (`bed_id`),
  KEY `lara_bed_assignments_clinic_id_foreign` (`clinic_id`),
  CONSTRAINT `lara_bed_assignments_admission_id_foreign` FOREIGN KEY (`admission_id`) REFERENCES `lara_admissions` (`id`),
  CONSTRAINT `lara_bed_assignments_bed_id_foreign` FOREIGN KEY (`bed_id`) REFERENCES `lara_beds` (`id`),
  CONSTRAINT `lara_bed_assignments_clinic_id_foreign` FOREIGN KEY (`clinic_id`) REFERENCES `lara_clinics` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=32 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table structure for table `lara_beds`
DROP TABLE IF EXISTS `lara_beds`;
CREATE TABLE `lara_beds` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `room_id` bigint unsigned NOT NULL,
  `clinic_id` bigint unsigned NOT NULL,
  `bed_number` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` enum('available','occupied','maintenance') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'available',
  `position` int NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `lara_beds_room_id_bed_number_unique` (`room_id`,`bed_number`),
  KEY `lara_beds_clinic_id_foreign` (`clinic_id`),
  CONSTRAINT `lara_beds_clinic_id_foreign` FOREIGN KEY (`clinic_id`) REFERENCES `lara_clinics` (`id`),
  CONSTRAINT `lara_beds_room_id_foreign` FOREIGN KEY (`room_id`) REFERENCES `lara_rooms` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=121 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table structure for table `lara_cache`
DROP TABLE IF EXISTS `lara_cache`;
CREATE TABLE `lara_cache` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table structure for table `lara_cache_locks`
DROP TABLE IF EXISTS `lara_cache_locks`;
CREATE TABLE `lara_cache_locks` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table structure for table `lara_clinic_images`
DROP TABLE IF EXISTS `lara_clinic_images`;
CREATE TABLE `lara_clinic_images` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `clinic_id` bigint unsigned NOT NULL,
  `image_path` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `sort_order` int NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `lara_clinic_images_clinic_id_foreign` (`clinic_id`),
  CONSTRAINT `lara_clinic_images_clinic_id_foreign` FOREIGN KEY (`clinic_id`) REFERENCES `lara_clinics` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table structure for table `lara_clinics`
DROP TABLE IF EXISTS `lara_clinics`;
CREATE TABLE `lara_clinics` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `registration_number` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address_line_1` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `address_line_2` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `city` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `state` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `country` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `postal_code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `website` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `logo_path` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `timezone` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `currency` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `opening_time` time DEFAULT NULL,
  `closing_time` time DEFAULT NULL,
  `status` enum('active','inactive','suspended') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `lara_clinics_code_unique` (`code`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table structure for table `lara_consultations`
DROP TABLE IF EXISTS `lara_consultations`;
CREATE TABLE `lara_consultations` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `visit_id` bigint unsigned NOT NULL,
  `doctor_notes` longtext COLLATE utf8mb4_unicode_ci,
  `diagnosis` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `follow_up_required` tinyint(1) NOT NULL DEFAULT '0',
  `follow_up_date` date DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `clinic_id` bigint unsigned DEFAULT NULL,
  `doctor_id` bigint unsigned DEFAULT NULL,
  `patient_id` bigint unsigned DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'in_progress',
  `deleted_at` timestamp NULL DEFAULT NULL,
  `symptoms` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin,
  PRIMARY KEY (`id`),
  KEY `lara_consultations_visit_id_foreign` (`visit_id`),
  KEY `lara_consultations_clinic_id_foreign` (`clinic_id`),
  KEY `lara_consultations_doctor_id_foreign` (`doctor_id`),
  KEY `lara_consultations_patient_id_foreign` (`patient_id`),
  CONSTRAINT `lara_consultations_clinic_id_foreign` FOREIGN KEY (`clinic_id`) REFERENCES `lara_clinics` (`id`),
  CONSTRAINT `lara_consultations_doctor_id_foreign` FOREIGN KEY (`doctor_id`) REFERENCES `lara_doctors` (`id`),
  CONSTRAINT `lara_consultations_patient_id_foreign` FOREIGN KEY (`patient_id`) REFERENCES `lara_patients` (`id`),
  CONSTRAINT `lara_consultations_visit_id_foreign` FOREIGN KEY (`visit_id`) REFERENCES `lara_visits` (`id`) ON DELETE CASCADE,
  CONSTRAINT `lara_consultations_chk_1` CHECK (json_valid(`symptoms`))
) ENGINE=InnoDB AUTO_INCREMENT=309 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table structure for table `lara_departments`
DROP TABLE IF EXISTS `lara_departments`;
CREATE TABLE `lara_departments` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `clinic_id` bigint unsigned NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `floor_number` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone_extension` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` enum('active','inactive') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `lara_departments_clinic_id_foreign` (`clinic_id`),
  CONSTRAINT `lara_departments_clinic_id_foreign` FOREIGN KEY (`clinic_id`) REFERENCES `lara_clinics` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table structure for table `lara_doctor_awards`
DROP TABLE IF EXISTS `lara_doctor_awards`;
CREATE TABLE `lara_doctor_awards` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `doctor_id` bigint unsigned NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `year` year DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `lara_doctor_awards_doctor_id_foreign` (`doctor_id`),
  CONSTRAINT `lara_doctor_awards_doctor_id_foreign` FOREIGN KEY (`doctor_id`) REFERENCES `lara_doctors` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table structure for table `lara_doctor_certifications`
DROP TABLE IF EXISTS `lara_doctor_certifications`;
CREATE TABLE `lara_doctor_certifications` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `doctor_id` bigint unsigned NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `issued_by` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `issued_date` date DEFAULT NULL,
  `expiry_date` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `lara_doctor_certifications_doctor_id_foreign` (`doctor_id`),
  CONSTRAINT `lara_doctor_certifications_doctor_id_foreign` FOREIGN KEY (`doctor_id`) REFERENCES `lara_doctors` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table structure for table `lara_doctor_clinic`
DROP TABLE IF EXISTS `lara_doctor_clinic`;
CREATE TABLE `lara_doctor_clinic` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `doctor_id` bigint unsigned NOT NULL,
  `clinic_id` bigint unsigned NOT NULL,
  `consultation_fee` decimal(10,2) DEFAULT NULL,
  `display_on_booking` tinyint(1) NOT NULL DEFAULT '1',
  `joining_date` date DEFAULT NULL,
  `status` enum('active','inactive') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `lara_doctor_clinic_doctor_id_foreign` (`doctor_id`),
  KEY `lara_doctor_clinic_clinic_id_foreign` (`clinic_id`),
  CONSTRAINT `lara_doctor_clinic_clinic_id_foreign` FOREIGN KEY (`clinic_id`) REFERENCES `lara_clinics` (`id`),
  CONSTRAINT `lara_doctor_clinic_doctor_id_foreign` FOREIGN KEY (`doctor_id`) REFERENCES `lara_doctors` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table structure for table `lara_doctor_education`
DROP TABLE IF EXISTS `lara_doctor_education`;
CREATE TABLE `lara_doctor_education` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `doctor_id` bigint unsigned NOT NULL,
  `degree` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `institution` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `country` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `start_year` year DEFAULT NULL,
  `end_year` year DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `lara_doctor_education_doctor_id_foreign` (`doctor_id`),
  CONSTRAINT `lara_doctor_education_doctor_id_foreign` FOREIGN KEY (`doctor_id`) REFERENCES `lara_doctors` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table structure for table `lara_doctor_schedule_exceptions`
DROP TABLE IF EXISTS `lara_doctor_schedule_exceptions`;
CREATE TABLE `lara_doctor_schedule_exceptions` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `doctor_id` bigint unsigned NOT NULL,
  `clinic_id` bigint unsigned NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `is_available` tinyint(1) NOT NULL DEFAULT '0',
  `start_time` time DEFAULT NULL,
  `end_time` time DEFAULT NULL,
  `reason` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` enum('pending','approved','rejected') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `lara_doctor_schedule_exceptions_doctor_id_foreign` (`doctor_id`),
  KEY `lara_doctor_schedule_exceptions_clinic_id_foreign` (`clinic_id`),
  CONSTRAINT `lara_doctor_schedule_exceptions_clinic_id_foreign` FOREIGN KEY (`clinic_id`) REFERENCES `lara_clinics` (`id`),
  CONSTRAINT `lara_doctor_schedule_exceptions_doctor_id_foreign` FOREIGN KEY (`doctor_id`) REFERENCES `lara_doctors` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table structure for table `lara_doctor_schedule_requests`
DROP TABLE IF EXISTS `lara_doctor_schedule_requests`;
CREATE TABLE `lara_doctor_schedule_requests` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `doctor_id` bigint unsigned NOT NULL,
  `clinic_id` bigint unsigned NOT NULL,
  `schedules` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL,
  `status` enum('pending','approved','rejected') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `requested_by` bigint unsigned NOT NULL,
  `processed_by` bigint unsigned DEFAULT NULL,
  `processed_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `lara_doctor_schedule_requests_doctor_id_foreign` (`doctor_id`),
  KEY `lara_doctor_schedule_requests_clinic_id_foreign` (`clinic_id`),
  KEY `lara_doctor_schedule_requests_requested_by_foreign` (`requested_by`),
  KEY `lara_doctor_schedule_requests_processed_by_foreign` (`processed_by`),
  CONSTRAINT `lara_doctor_schedule_requests_clinic_id_foreign` FOREIGN KEY (`clinic_id`) REFERENCES `lara_clinics` (`id`),
  CONSTRAINT `lara_doctor_schedule_requests_doctor_id_foreign` FOREIGN KEY (`doctor_id`) REFERENCES `lara_doctors` (`id`),
  CONSTRAINT `lara_doctor_schedule_requests_processed_by_foreign` FOREIGN KEY (`processed_by`) REFERENCES `lara_users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `lara_doctor_schedule_requests_requested_by_foreign` FOREIGN KEY (`requested_by`) REFERENCES `lara_users` (`id`),
  CONSTRAINT `lara_doctor_schedule_requests_chk_1` CHECK (json_valid(`schedules`))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table structure for table `lara_doctor_schedules`
DROP TABLE IF EXISTS `lara_doctor_schedules`;
CREATE TABLE `lara_doctor_schedules` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `doctor_id` bigint unsigned NOT NULL,
  `clinic_id` bigint unsigned NOT NULL,
  `department_id` bigint unsigned NOT NULL,
  `day_of_week` tinyint unsigned DEFAULT NULL,
  `schedule_date` date DEFAULT NULL,
  `start_time` time NOT NULL,
  `end_time` time NOT NULL,
  `slot_duration_minutes` smallint unsigned NOT NULL,
  `max_patients` smallint unsigned DEFAULT NULL,
  `status` enum('active','inactive') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `lara_doctor_schedules_doctor_id_foreign` (`doctor_id`),
  KEY `lara_doctor_schedules_clinic_id_foreign` (`clinic_id`),
  KEY `lara_doctor_schedules_department_id_foreign` (`department_id`),
  KEY `lara_doctor_schedules_schedule_date_index` (`schedule_date`),
  CONSTRAINT `lara_doctor_schedules_clinic_id_foreign` FOREIGN KEY (`clinic_id`) REFERENCES `lara_clinics` (`id`),
  CONSTRAINT `lara_doctor_schedules_department_id_foreign` FOREIGN KEY (`department_id`) REFERENCES `lara_departments` (`id`),
  CONSTRAINT `lara_doctor_schedules_doctor_id_foreign` FOREIGN KEY (`doctor_id`) REFERENCES `lara_doctors` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table structure for table `lara_doctors`
DROP TABLE IF EXISTS `lara_doctors`;
CREATE TABLE `lara_doctors` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `primary_department_id` bigint unsigned NOT NULL,
  `registration_number` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `license_number` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `specialization` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `experience_years` int unsigned NOT NULL DEFAULT '0',
  `gender` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `blood_group` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `date_of_birth` date DEFAULT NULL,
  `consultation_fee` decimal(10,2) DEFAULT NULL,
  `follow_up_fee` decimal(10,2) DEFAULT NULL,
  `biography` text COLLATE utf8mb4_unicode_ci,
  `profile_photo` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_featured` tinyint(1) NOT NULL DEFAULT '0',
  `status` enum('active','inactive') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `consultation_room_number` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `consultation_floor` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `lara_doctors_user_id_foreign` (`user_id`),
  KEY `lara_doctors_primary_department_id_foreign` (`primary_department_id`),
  CONSTRAINT `lara_doctors_primary_department_id_foreign` FOREIGN KEY (`primary_department_id`) REFERENCES `lara_departments` (`id`),
  CONSTRAINT `lara_doctors_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `lara_users` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table structure for table `lara_failed_jobs`
DROP TABLE IF EXISTS `lara_failed_jobs`;
CREATE TABLE `lara_failed_jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `lara_failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table structure for table `lara_inpatient_rounds`
DROP TABLE IF EXISTS `lara_inpatient_rounds`;
CREATE TABLE `lara_inpatient_rounds` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `admission_id` bigint unsigned NOT NULL,
  `doctor_id` bigint unsigned NOT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `round_date` date NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `clinic_id` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `lara_inpatient_rounds_admission_id_foreign` (`admission_id`),
  KEY `lara_inpatient_rounds_doctor_id_foreign` (`doctor_id`),
  KEY `lara_inpatient_rounds_clinic_id_foreign` (`clinic_id`),
  CONSTRAINT `lara_inpatient_rounds_admission_id_foreign` FOREIGN KEY (`admission_id`) REFERENCES `lara_admissions` (`id`),
  CONSTRAINT `lara_inpatient_rounds_clinic_id_foreign` FOREIGN KEY (`clinic_id`) REFERENCES `lara_clinics` (`id`),
  CONSTRAINT `lara_inpatient_rounds_doctor_id_foreign` FOREIGN KEY (`doctor_id`) REFERENCES `lara_doctors` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=31 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table structure for table `lara_inpatient_services`
DROP TABLE IF EXISTS `lara_inpatient_services`;
CREATE TABLE `lara_inpatient_services` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `admission_id` bigint unsigned NOT NULL,
  `service_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `service_date` date NOT NULL,
  `quantity` int NOT NULL,
  `unit_price` decimal(10,2) NOT NULL,
  `total_price` decimal(10,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `clinic_id` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `lara_inpatient_services_admission_id_foreign` (`admission_id`),
  KEY `lara_inpatient_services_clinic_id_foreign` (`clinic_id`),
  CONSTRAINT `lara_inpatient_services_admission_id_foreign` FOREIGN KEY (`admission_id`) REFERENCES `lara_admissions` (`id`),
  CONSTRAINT `lara_inpatient_services_clinic_id_foreign` FOREIGN KEY (`clinic_id`) REFERENCES `lara_clinics` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table structure for table `lara_invoice_items`
DROP TABLE IF EXISTS `lara_invoice_items`;
CREATE TABLE `lara_invoice_items` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `invoice_id` bigint unsigned NOT NULL,
  `item_type` enum('consultation','lab','medicine','bed','service') COLLATE utf8mb4_unicode_ci NOT NULL,
  `reference_id` bigint unsigned DEFAULT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `quantity` int NOT NULL,
  `unit_price` decimal(10,2) NOT NULL,
  `total_price` decimal(10,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `clinic_id` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `lara_invoice_items_invoice_id_foreign` (`invoice_id`),
  KEY `lara_invoice_items_clinic_id_foreign` (`clinic_id`),
  CONSTRAINT `lara_invoice_items_clinic_id_foreign` FOREIGN KEY (`clinic_id`) REFERENCES `lara_clinics` (`id`),
  CONSTRAINT `lara_invoice_items_invoice_id_foreign` FOREIGN KEY (`invoice_id`) REFERENCES `lara_invoices` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=774 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table structure for table `lara_invoices`
DROP TABLE IF EXISTS `lara_invoices`;
CREATE TABLE `lara_invoices` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `clinic_id` bigint unsigned NOT NULL,
  `patient_id` bigint unsigned NOT NULL,
  `appointment_id` bigint unsigned DEFAULT NULL,
  `visit_id` bigint unsigned DEFAULT NULL,
  `admission_id` bigint unsigned DEFAULT NULL,
  `invoice_number` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `invoice_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `subtotal` decimal(10,2) NOT NULL,
  `discount` decimal(10,2) NOT NULL DEFAULT '0.00',
  `tax` decimal(10,2) NOT NULL DEFAULT '0.00',
  `total_amount` decimal(10,2) NOT NULL,
  `status` enum('unpaid','partial','paid','cancelled') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'unpaid',
  `state` enum('draft','finalized') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'draft',
  `issued_at` datetime DEFAULT NULL,
  `finalized_at` datetime DEFAULT NULL,
  `finalized_by` bigint unsigned DEFAULT NULL,
  `created_by` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `lara_invoices_invoice_number_unique` (`invoice_number`),
  KEY `lara_invoices_clinic_id_foreign` (`clinic_id`),
  KEY `lara_invoices_patient_id_foreign` (`patient_id`),
  KEY `lara_invoices_appointment_id_foreign` (`appointment_id`),
  KEY `lara_invoices_admission_id_foreign` (`admission_id`),
  KEY `lara_invoices_visit_id_foreign` (`visit_id`),
  KEY `lara_invoices_finalized_by_foreign` (`finalized_by`),
  KEY `lara_invoices_created_by_foreign` (`created_by`),
  CONSTRAINT `lara_invoices_admission_id_foreign` FOREIGN KEY (`admission_id`) REFERENCES `lara_admissions` (`id`) ON DELETE SET NULL,
  CONSTRAINT `lara_invoices_appointment_id_foreign` FOREIGN KEY (`appointment_id`) REFERENCES `lara_appointments` (`id`) ON DELETE SET NULL,
  CONSTRAINT `lara_invoices_clinic_id_foreign` FOREIGN KEY (`clinic_id`) REFERENCES `lara_clinics` (`id`),
  CONSTRAINT `lara_invoices_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `lara_users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `lara_invoices_finalized_by_foreign` FOREIGN KEY (`finalized_by`) REFERENCES `lara_users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `lara_invoices_patient_id_foreign` FOREIGN KEY (`patient_id`) REFERENCES `lara_patients` (`id`),
  CONSTRAINT `lara_invoices_visit_id_foreign` FOREIGN KEY (`visit_id`) REFERENCES `lara_visits` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=584 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table structure for table `lara_job_batches`
DROP TABLE IF EXISTS `lara_job_batches`;
CREATE TABLE `lara_job_batches` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` mediumtext COLLATE utf8mb4_unicode_ci,
  `cancelled_at` int DEFAULT NULL,
  `created_at` int NOT NULL,
  `finished_at` int DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table structure for table `lara_jobs`
DROP TABLE IF EXISTS `lara_jobs`;
CREATE TABLE `lara_jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` tinyint unsigned NOT NULL,
  `reserved_at` int unsigned DEFAULT NULL,
  `available_at` int unsigned NOT NULL,
  `created_at` int unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `lara_jobs_queue_index` (`queue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table structure for table `lara_lab_test_orders`
DROP TABLE IF EXISTS `lara_lab_test_orders`;
CREATE TABLE `lara_lab_test_orders` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `appointment_id` bigint unsigned DEFAULT NULL,
  `doctor_id` bigint unsigned DEFAULT NULL,
  `patient_id` bigint unsigned NOT NULL,
  `lab_test_id` bigint unsigned DEFAULT NULL,
  `order_date` date NOT NULL,
  `status` enum('pending','completed','cancelled') COLLATE utf8mb4_unicode_ci NOT NULL,
  `invoice_id` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `clinic_id` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `lara_lab_test_orders_appointment_id_foreign` (`appointment_id`),
  KEY `lara_lab_test_orders_doctor_id_foreign` (`doctor_id`),
  KEY `lara_lab_test_orders_patient_id_foreign` (`patient_id`),
  KEY `lara_lab_test_orders_clinic_id_foreign` (`clinic_id`),
  KEY `lara_lab_test_orders_lab_test_id_foreign` (`lab_test_id`),
  KEY `lara_lab_test_orders_invoice_id_foreign` (`invoice_id`),
  CONSTRAINT `lara_lab_test_orders_appointment_id_foreign` FOREIGN KEY (`appointment_id`) REFERENCES `lara_appointments` (`id`),
  CONSTRAINT `lara_lab_test_orders_clinic_id_foreign` FOREIGN KEY (`clinic_id`) REFERENCES `lara_clinics` (`id`),
  CONSTRAINT `lara_lab_test_orders_doctor_id_foreign` FOREIGN KEY (`doctor_id`) REFERENCES `lara_doctors` (`id`),
  CONSTRAINT `lara_lab_test_orders_invoice_id_foreign` FOREIGN KEY (`invoice_id`) REFERENCES `lara_invoices` (`id`) ON DELETE SET NULL,
  CONSTRAINT `lara_lab_test_orders_lab_test_id_foreign` FOREIGN KEY (`lab_test_id`) REFERENCES `lara_lab_tests` (`id`),
  CONSTRAINT `lara_lab_test_orders_patient_id_foreign` FOREIGN KEY (`patient_id`) REFERENCES `lara_patients` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=129 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table structure for table `lara_lab_test_results`
DROP TABLE IF EXISTS `lara_lab_test_results`;
CREATE TABLE `lara_lab_test_results` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `lab_test_order_id` bigint unsigned NOT NULL,
  `lab_test_id` bigint unsigned NOT NULL,
  `result_value` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `unit` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `reference_range` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `remarks` text COLLATE utf8mb4_unicode_ci,
  `pdf_path` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `reported_by` bigint unsigned NOT NULL,
  `reported_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `clinic_id` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `lara_lab_test_results_lab_test_order_id_foreign` (`lab_test_order_id`),
  KEY `lara_lab_test_results_lab_test_id_foreign` (`lab_test_id`),
  KEY `lara_lab_test_results_reported_by_foreign` (`reported_by`),
  KEY `lara_lab_test_results_clinic_id_foreign` (`clinic_id`),
  CONSTRAINT `lara_lab_test_results_clinic_id_foreign` FOREIGN KEY (`clinic_id`) REFERENCES `lara_clinics` (`id`),
  CONSTRAINT `lara_lab_test_results_lab_test_id_foreign` FOREIGN KEY (`lab_test_id`) REFERENCES `lara_lab_tests` (`id`),
  CONSTRAINT `lara_lab_test_results_lab_test_order_id_foreign` FOREIGN KEY (`lab_test_order_id`) REFERENCES `lara_lab_test_orders` (`id`),
  CONSTRAINT `lara_lab_test_results_reported_by_foreign` FOREIGN KEY (`reported_by`) REFERENCES `lara_users` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=128 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table structure for table `lara_lab_tests`
DROP TABLE IF EXISTS `lara_lab_tests`;
CREATE TABLE `lara_lab_tests` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `category` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `normal_range` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `status` enum('active','inactive') COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table structure for table `lara_medicine_batches`
DROP TABLE IF EXISTS `lara_medicine_batches`;
CREATE TABLE `lara_medicine_batches` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `clinic_id` bigint unsigned NOT NULL,
  `medicine_id` bigint unsigned NOT NULL,
  `batch_number` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiry_date` date NOT NULL,
  `quantity_in_stock` int NOT NULL,
  `purchase_price` decimal(10,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `lara_medicine_batches_medicine_id_foreign` (`medicine_id`),
  KEY `lara_medicine_batches_clinic_id_foreign` (`clinic_id`),
  CONSTRAINT `lara_medicine_batches_clinic_id_foreign` FOREIGN KEY (`clinic_id`) REFERENCES `lara_clinics` (`id`),
  CONSTRAINT `lara_medicine_batches_medicine_id_foreign` FOREIGN KEY (`medicine_id`) REFERENCES `lara_medicines` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table structure for table `lara_medicines`
DROP TABLE IF EXISTS `lara_medicines`;
CREATE TABLE `lara_medicines` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `generic_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `manufacturer` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `strength` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `dosage_form` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `status` enum('active','inactive') COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table structure for table `lara_migrations`
DROP TABLE IF EXISTS `lara_migrations`;
CREATE TABLE `lara_migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=88 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table structure for table `lara_notifications`
DROP TABLE IF EXISTS `lara_notifications`;
CREATE TABLE `lara_notifications` (
  `id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `notifiable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `notifiable_id` bigint unsigned NOT NULL,
  `data` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `read_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `lara_notifications_notifiable_type_notifiable_id_index` (`notifiable_type`,`notifiable_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table structure for table `lara_nursing_notes`
DROP TABLE IF EXISTS `lara_nursing_notes`;
CREATE TABLE `lara_nursing_notes` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `admission_id` bigint unsigned NOT NULL,
  `nurse_id` bigint unsigned NOT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `recorded_at` datetime NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `clinic_id` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `lara_nursing_notes_admission_id_foreign` (`admission_id`),
  KEY `lara_nursing_notes_nurse_id_foreign` (`nurse_id`),
  KEY `lara_nursing_notes_clinic_id_foreign` (`clinic_id`),
  CONSTRAINT `lara_nursing_notes_admission_id_foreign` FOREIGN KEY (`admission_id`) REFERENCES `lara_admissions` (`id`),
  CONSTRAINT `lara_nursing_notes_clinic_id_foreign` FOREIGN KEY (`clinic_id`) REFERENCES `lara_clinics` (`id`),
  CONSTRAINT `lara_nursing_notes_nurse_id_foreign` FOREIGN KEY (`nurse_id`) REFERENCES `lara_users` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=31 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table structure for table `lara_password_reset_tokens`
DROP TABLE IF EXISTS `lara_password_reset_tokens`;
CREATE TABLE `lara_password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table structure for table `lara_patient_allergies`
DROP TABLE IF EXISTS `lara_patient_allergies`;
CREATE TABLE `lara_patient_allergies` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `patient_id` bigint unsigned NOT NULL,
  `allergy_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `severity` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `lara_patient_allergies_patient_id_foreign` (`patient_id`),
  CONSTRAINT `lara_patient_allergies_patient_id_foreign` FOREIGN KEY (`patient_id`) REFERENCES `lara_patients` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=38 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table structure for table `lara_patient_complaints`
DROP TABLE IF EXISTS `lara_patient_complaints`;
CREATE TABLE `lara_patient_complaints` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `clinic_id` bigint unsigned NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `lara_patient_complaints_name_unique` (`name`),
  KEY `lara_patient_complaints_clinic_id_foreign` (`clinic_id`),
  CONSTRAINT `lara_patient_complaints_clinic_id_foreign` FOREIGN KEY (`clinic_id`) REFERENCES `lara_clinics` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table structure for table `lara_patient_medical_history`
DROP TABLE IF EXISTS `lara_patient_medical_history`;
CREATE TABLE `lara_patient_medical_history` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `patient_id` bigint unsigned NOT NULL,
  `condition_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `diagnosed_date` date DEFAULT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `lara_patient_medical_history_patient_id_foreign` (`patient_id`),
  CONSTRAINT `lara_patient_medical_history_patient_id_foreign` FOREIGN KEY (`patient_id`) REFERENCES `lara_patients` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=151 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table structure for table `lara_patient_vitals`
DROP TABLE IF EXISTS `lara_patient_vitals`;
CREATE TABLE `lara_patient_vitals` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `patient_id` bigint unsigned NOT NULL,
  `visit_id` bigint unsigned NOT NULL,
  `admission_id` bigint unsigned DEFAULT NULL,
  `inpatient_round_id` bigint unsigned DEFAULT NULL,
  `blood_pressure` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `heart_rate` int DEFAULT NULL,
  `temperature` decimal(4,1) DEFAULT NULL,
  `spo2` decimal(5,2) DEFAULT NULL,
  `respiratory_rate` int DEFAULT NULL,
  `weight` decimal(5,2) DEFAULT NULL,
  `height` decimal(5,2) DEFAULT NULL,
  `bmi` decimal(5,2) DEFAULT NULL,
  `recorded_by` bigint unsigned NOT NULL,
  `recorded_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `lara_patient_vitals_patient_id_foreign` (`patient_id`),
  KEY `lara_patient_vitals_visit_id_foreign` (`visit_id`),
  KEY `lara_patient_vitals_recorded_by_foreign` (`recorded_by`),
  KEY `lara_patient_vitals_admission_id_foreign` (`admission_id`),
  KEY `lara_patient_vitals_inpatient_round_id_foreign` (`inpatient_round_id`),
  CONSTRAINT `lara_patient_vitals_admission_id_foreign` FOREIGN KEY (`admission_id`) REFERENCES `lara_admissions` (`id`) ON DELETE SET NULL,
  CONSTRAINT `lara_patient_vitals_inpatient_round_id_foreign` FOREIGN KEY (`inpatient_round_id`) REFERENCES `lara_inpatient_rounds` (`id`) ON DELETE SET NULL,
  CONSTRAINT `lara_patient_vitals_patient_id_foreign` FOREIGN KEY (`patient_id`) REFERENCES `lara_patients` (`id`),
  CONSTRAINT `lara_patient_vitals_recorded_by_foreign` FOREIGN KEY (`recorded_by`) REFERENCES `lara_users` (`id`),
  CONSTRAINT `lara_patient_vitals_visit_id_foreign` FOREIGN KEY (`visit_id`) REFERENCES `lara_visits` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=303 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table structure for table `lara_patients`
DROP TABLE IF EXISTS `lara_patients`;
CREATE TABLE `lara_patients` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `clinic_id` bigint unsigned NOT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `patient_code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `date_of_birth` date DEFAULT NULL,
  `age` int unsigned DEFAULT NULL,
  `gender` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `blood_group` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `profile_photo` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` text COLLATE utf8mb4_unicode_ci,
  `emergency_contact_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `emergency_contact_phone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` enum('active','inactive') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `lara_patients_patient_code_unique` (`patient_code`),
  KEY `lara_patients_clinic_id_foreign` (`clinic_id`),
  KEY `lara_patients_user_id_foreign` (`user_id`),
  CONSTRAINT `lara_patients_clinic_id_foreign` FOREIGN KEY (`clinic_id`) REFERENCES `lara_clinics` (`id`),
  CONSTRAINT `lara_patients_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `lara_users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=151 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table structure for table `lara_payments`
DROP TABLE IF EXISTS `lara_payments`;
CREATE TABLE `lara_payments` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `invoice_id` bigint unsigned NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `payment_method` enum('cash','card','mobile_banking','bank_transfer') COLLATE utf8mb4_unicode_ci NOT NULL,
  `transaction_reference` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `paid_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `received_by` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `clinic_id` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `lara_payments_invoice_id_foreign` (`invoice_id`),
  KEY `lara_payments_received_by_foreign` (`received_by`),
  KEY `lara_payments_clinic_id_foreign` (`clinic_id`),
  CONSTRAINT `lara_payments_clinic_id_foreign` FOREIGN KEY (`clinic_id`) REFERENCES `lara_clinics` (`id`),
  CONSTRAINT `lara_payments_invoice_id_foreign` FOREIGN KEY (`invoice_id`) REFERENCES `lara_invoices` (`id`),
  CONSTRAINT `lara_payments_received_by_foreign` FOREIGN KEY (`received_by`) REFERENCES `lara_users` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=577 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table structure for table `lara_permissions`
DROP TABLE IF EXISTS `lara_permissions`;
CREATE TABLE `lara_permissions` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `lara_permissions_name_unique` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=58 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table structure for table `lara_personal_access_tokens`
DROP TABLE IF EXISTS `lara_personal_access_tokens`;
CREATE TABLE `lara_personal_access_tokens` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `tokenable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint unsigned NOT NULL,
  `name` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text COLLATE utf8mb4_unicode_ci,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `lara_personal_access_tokens_token_unique` (`token`),
  KEY `lara_personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`),
  KEY `lara_personal_access_tokens_expires_at_index` (`expires_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table structure for table `lara_pharmacy_sale_items`
DROP TABLE IF EXISTS `lara_pharmacy_sale_items`;
CREATE TABLE `lara_pharmacy_sale_items` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `pharmacy_sale_id` bigint unsigned NOT NULL,
  `medicine_id` bigint unsigned NOT NULL,
  `quantity` int NOT NULL,
  `unit_price` decimal(10,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `lara_pharmacy_sale_items_pharmacy_sale_id_foreign` (`pharmacy_sale_id`),
  KEY `lara_pharmacy_sale_items_medicine_id_foreign` (`medicine_id`),
  CONSTRAINT `lara_pharmacy_sale_items_medicine_id_foreign` FOREIGN KEY (`medicine_id`) REFERENCES `lara_medicines` (`id`),
  CONSTRAINT `lara_pharmacy_sale_items_pharmacy_sale_id_foreign` FOREIGN KEY (`pharmacy_sale_id`) REFERENCES `lara_pharmacy_sales` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=292 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table structure for table `lara_pharmacy_sales`
DROP TABLE IF EXISTS `lara_pharmacy_sales`;
CREATE TABLE `lara_pharmacy_sales` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `prescription_id` bigint unsigned NOT NULL,
  `patient_id` bigint unsigned NOT NULL,
  `sale_date` date NOT NULL,
  `total_amount` decimal(10,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `clinic_id` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `lara_pharmacy_sales_prescription_id_foreign` (`prescription_id`),
  KEY `lara_pharmacy_sales_patient_id_foreign` (`patient_id`),
  KEY `lara_pharmacy_sales_clinic_id_foreign` (`clinic_id`),
  CONSTRAINT `lara_pharmacy_sales_clinic_id_foreign` FOREIGN KEY (`clinic_id`) REFERENCES `lara_clinics` (`id`),
  CONSTRAINT `lara_pharmacy_sales_patient_id_foreign` FOREIGN KEY (`patient_id`) REFERENCES `lara_patients` (`id`),
  CONSTRAINT `lara_pharmacy_sales_prescription_id_foreign` FOREIGN KEY (`prescription_id`) REFERENCES `lara_prescriptions` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=152 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table structure for table `lara_prescription_complaint`
DROP TABLE IF EXISTS `lara_prescription_complaint`;
CREATE TABLE `lara_prescription_complaint` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `prescription_id` bigint unsigned NOT NULL,
  `complaint_id` bigint unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `lara_prescription_complaint_prescription_id_complaint_id_unique` (`prescription_id`,`complaint_id`),
  KEY `lara_prescription_complaint_complaint_id_foreign` (`complaint_id`),
  CONSTRAINT `lara_prescription_complaint_complaint_id_foreign` FOREIGN KEY (`complaint_id`) REFERENCES `lara_patient_complaints` (`id`) ON DELETE CASCADE,
  CONSTRAINT `lara_prescription_complaint_prescription_id_foreign` FOREIGN KEY (`prescription_id`) REFERENCES `lara_prescriptions` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=224 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table structure for table `lara_prescription_items`
DROP TABLE IF EXISTS `lara_prescription_items`;
CREATE TABLE `lara_prescription_items` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `prescription_id` bigint unsigned NOT NULL,
  `medicine_id` bigint unsigned NOT NULL,
  `dosage` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `frequency` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `duration_days` int NOT NULL,
  `instructions` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `clinic_id` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `lara_prescription_items_prescription_id_foreign` (`prescription_id`),
  KEY `lara_prescription_items_medicine_id_foreign` (`medicine_id`),
  KEY `lara_prescription_items_clinic_id_foreign` (`clinic_id`),
  CONSTRAINT `lara_prescription_items_clinic_id_foreign` FOREIGN KEY (`clinic_id`) REFERENCES `lara_clinics` (`id`),
  CONSTRAINT `lara_prescription_items_medicine_id_foreign` FOREIGN KEY (`medicine_id`) REFERENCES `lara_medicines` (`id`),
  CONSTRAINT `lara_prescription_items_prescription_id_foreign` FOREIGN KEY (`prescription_id`) REFERENCES `lara_prescriptions` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=293 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table structure for table `lara_prescriptions`
DROP TABLE IF EXISTS `lara_prescriptions`;
CREATE TABLE `lara_prescriptions` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `consultation_id` bigint unsigned NOT NULL,
  `issued_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `clinic_id` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `lara_prescriptions_consultation_id_foreign` (`consultation_id`),
  KEY `lara_prescriptions_clinic_id_foreign` (`clinic_id`),
  CONSTRAINT `lara_prescriptions_clinic_id_foreign` FOREIGN KEY (`clinic_id`) REFERENCES `lara_clinics` (`id`),
  CONSTRAINT `lara_prescriptions_consultation_id_foreign` FOREIGN KEY (`consultation_id`) REFERENCES `lara_consultations` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=153 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table structure for table `lara_role_permission`
DROP TABLE IF EXISTS `lara_role_permission`;
CREATE TABLE `lara_role_permission` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `role_id` bigint unsigned NOT NULL,
  `permission_id` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `lara_role_permission_role_id_foreign` (`role_id`),
  KEY `lara_role_permission_permission_id_foreign` (`permission_id`),
  CONSTRAINT `lara_role_permission_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `lara_permissions` (`id`) ON DELETE CASCADE,
  CONSTRAINT `lara_role_permission_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `lara_roles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=193 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table structure for table `lara_roles`
DROP TABLE IF EXISTS `lara_roles`;
CREATE TABLE `lara_roles` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `lara_roles_name_unique` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table structure for table `lara_rooms`
DROP TABLE IF EXISTS `lara_rooms`;
CREATE TABLE `lara_rooms` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `ward_id` bigint unsigned NOT NULL,
  `clinic_id` bigint unsigned DEFAULT NULL,
  `room_number` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `room_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `daily_rate` decimal(10,2) NOT NULL,
  `status` enum('available','occupied','maintenance') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'available',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `lara_rooms_ward_id_room_number_unique` (`ward_id`,`room_number`),
  KEY `lara_rooms_clinic_id_foreign` (`clinic_id`),
  CONSTRAINT `lara_rooms_clinic_id_foreign` FOREIGN KEY (`clinic_id`) REFERENCES `lara_clinics` (`id`),
  CONSTRAINT `lara_rooms_ward_id_foreign` FOREIGN KEY (`ward_id`) REFERENCES `lara_wards` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=61 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table structure for table `lara_sessions`
DROP TABLE IF EXISTS `lara_sessions`;
CREATE TABLE `lara_sessions` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `lara_sessions_user_id_index` (`user_id`),
  KEY `lara_sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table structure for table `lara_user_role`
DROP TABLE IF EXISTS `lara_user_role`;
CREATE TABLE `lara_user_role` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `role_id` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `lara_user_role_user_id_foreign` (`user_id`),
  KEY `lara_user_role_role_id_foreign` (`role_id`),
  CONSTRAINT `lara_user_role_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `lara_roles` (`id`) ON DELETE CASCADE,
  CONSTRAINT `lara_user_role_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `lara_users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table structure for table `lara_users`
DROP TABLE IF EXISTS `lara_users`;
CREATE TABLE `lara_users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `clinic_id` bigint unsigned NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `profile_photo_path` varchar(2048) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `last_login_at` timestamp NULL DEFAULT NULL,
  `is_two_factor_enabled` tinyint(1) NOT NULL DEFAULT '0',
  `status` enum('active','inactive','blocked') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `lara_users_email_unique` (`email`),
  KEY `lara_users_clinic_id_foreign` (`clinic_id`),
  CONSTRAINT `lara_users_clinic_id_foreign` FOREIGN KEY (`clinic_id`) REFERENCES `lara_clinics` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table structure for table `lara_visits`
DROP TABLE IF EXISTS `lara_visits`;
CREATE TABLE `lara_visits` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `appointment_id` bigint unsigned NOT NULL,
  `check_in_time` timestamp NULL DEFAULT NULL,
  `check_out_time` timestamp NULL DEFAULT NULL,
  `visit_status` enum('waiting','in_progress','completed','cancelled') COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `clinic_id` bigint unsigned DEFAULT NULL,
  `consultation_id` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `lara_visits_appointment_id_foreign` (`appointment_id`),
  KEY `lara_visits_clinic_id_foreign` (`clinic_id`),
  KEY `lara_visits_consultation_id_foreign` (`consultation_id`),
  CONSTRAINT `lara_visits_appointment_id_foreign` FOREIGN KEY (`appointment_id`) REFERENCES `lara_appointments` (`id`),
  CONSTRAINT `lara_visits_clinic_id_foreign` FOREIGN KEY (`clinic_id`) REFERENCES `lara_clinics` (`id`),
  CONSTRAINT `lara_visits_consultation_id_foreign` FOREIGN KEY (`consultation_id`) REFERENCES `lara_consultations` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=306 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table structure for table `lara_wards`
DROP TABLE IF EXISTS `lara_wards`;
CREATE TABLE `lara_wards` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `clinic_id` bigint unsigned NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` enum('general','icu','cabin') COLLATE utf8mb4_unicode_ci NOT NULL,
  `floor` int DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `status` enum('active','inactive') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `lara_wards_clinic_id_foreign` (`clinic_id`),
  CONSTRAINT `lara_wards_clinic_id_foreign` FOREIGN KEY (`clinic_id`) REFERENCES `lara_clinics` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

SET FOREIGN_KEY_CHECKS=1;
