SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS=0;
SET SQL_MODE='NO_AUTO_VALUE_ON_ZERO';

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
  `about` text COLLATE utf8mb4_unicode_ci,
  `services` json DEFAULT NULL,
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
  UNIQUE KEY `lara_clinics_code_unique` (`code`),
  UNIQUE KEY `lara_clinics_registration_number_unique` (`registration_number`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table `lara_clinics`
INSERT INTO `lara_clinics` VALUES (1, 'Dhanmondi CityCare', 'CC-HMS-1', '1210784863', 'Nizam\'s Shankar Plaza, 72 Satmasjid Road, Dhaka 1209', NULL, 'Dhaka', NULL, 'Bangladesh', '1209', '+8801711223344', 'dhcchms@citycare.com', 'citycare.com.bd', NULL, NULL, 'dh-cc.png', 'Asia/Dhaka', 'BDT', '08:00:00', '22:00:00', 'active', '2026-01-30 06:37:24', '2026-01-30 06:37:24', NULL);

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

-- Dumping data for table `lara_roles`
INSERT INTO `lara_roles` VALUES (1, 'Super Admin', 'System Owner', '2026-01-30 06:37:23', '2026-01-30 06:37:23'),
(2, 'Clinic Admin', 'Administrator for the clinic', '2026-01-30 06:37:24', '2026-01-30 06:37:24'),
(3, 'Doctor', 'Medical Doctor', '2026-01-30 06:37:24', '2026-01-30 06:37:24'),
(4, 'Nurse', 'IPD Nurse', '2026-01-30 06:37:24', '2026-01-30 06:37:24'),
(5, 'Receptionist', 'Front Desk', '2026-01-30 06:37:24', '2026-01-30 06:37:24'),
(6, 'Lab Technician', 'Lab Staff', '2026-01-30 06:37:24', '2026-01-30 06:37:24'),
(7, 'Pharmacist', 'Pharmacy Staff', '2026-01-30 06:37:24', '2026-01-30 06:37:24'),
(8, 'Accountant', 'Finance Staff', '2026-01-30 06:37:24', '2026-01-30 06:37:24');

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
) ENGINE=InnoDB AUTO_INCREMENT=59 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table `lara_permissions`
INSERT INTO `lara_permissions` VALUES (1, 'view_dashboard', NULL, '2026-01-30 06:37:23', '2026-01-30 06:37:23'),
(2, 'view_admin_dashboard', NULL, '2026-01-30 06:37:23', '2026-01-30 06:37:23'),
(3, 'view_doctor_dashboard', NULL, '2026-01-30 06:37:23', '2026-01-30 06:37:23'),
(4, 'view_nurse_dashboard', NULL, '2026-01-30 06:37:23', '2026-01-30 06:37:23'),
(5, 'view_receptionist_dashboard', NULL, '2026-01-30 06:37:23', '2026-01-30 06:37:23'),
(6, 'view_lab_dashboard', NULL, '2026-01-30 06:37:23', '2026-01-30 06:37:23'),
(7, 'view_pharmacy_dashboard', NULL, '2026-01-30 06:37:23', '2026-01-30 06:37:23'),
(8, 'view_accountant_dashboard', NULL, '2026-01-30 06:37:23', '2026-01-30 06:37:23'),
(9, 'view_users', NULL, '2026-01-30 06:37:23', '2026-01-30 06:37:23'),
(10, 'create_users', NULL, '2026-01-30 06:37:23', '2026-01-30 06:37:23'),
(11, 'edit_users', NULL, '2026-01-30 06:37:23', '2026-01-30 06:37:23'),
(12, 'delete_users', NULL, '2026-01-30 06:37:23', '2026-01-30 06:37:23'),
(13, 'manage_roles', NULL, '2026-01-30 06:37:23', '2026-01-30 06:37:23'),
(14, 'manage_clinic_settings', NULL, '2026-01-30 06:37:23', '2026-01-30 06:37:23'),
(15, 'view_patients', NULL, '2026-01-30 06:37:23', '2026-01-30 06:37:23'),
(16, 'create_patients', NULL, '2026-01-30 06:37:23', '2026-01-30 06:37:23'),
(17, 'edit_patients', NULL, '2026-01-30 06:37:23', '2026-01-30 06:37:23'),
(18, 'delete_patients', NULL, '2026-01-30 06:37:23', '2026-01-30 06:37:23'),
(19, 'view_doctors', NULL, '2026-01-30 06:37:23', '2026-01-30 06:37:23'),
(20, 'create_doctors', NULL, '2026-01-30 06:37:23', '2026-01-30 06:37:23'),
(21, 'edit_doctors', NULL, '2026-01-30 06:37:23', '2026-01-30 06:37:23'),
(22, 'delete_doctors', NULL, '2026-01-30 06:37:23', '2026-01-30 06:37:23'),
(23, 'manage_doctor_schedule', NULL, '2026-01-30 06:37:23', '2026-01-30 06:37:23'),
(24, 'manage_doctor_clinic_assignments', NULL, '2026-01-30 06:37:23', '2026-01-30 06:37:23'),
(25, 'view_staff', NULL, '2026-01-30 06:37:23', '2026-01-30 06:37:23'),
(26, 'create_staff', NULL, '2026-01-30 06:37:23', '2026-01-30 06:37:23'),
(27, 'edit_staff', NULL, '2026-01-30 06:37:23', '2026-01-30 06:37:23'),
(28, 'delete_staff', NULL, '2026-01-30 06:37:23', '2026-01-30 06:37:23'),
(29, 'view_appointments', NULL, '2026-01-30 06:37:23', '2026-01-30 06:37:23'),
(30, 'create_appointments', NULL, '2026-01-30 06:37:23', '2026-01-30 06:37:23'),
(31, 'edit_appointments', NULL, '2026-01-30 06:37:23', '2026-01-30 06:37:23'),
(32, 'cancel_appointments', NULL, '2026-01-30 06:37:23', '2026-01-30 06:37:23'),
(33, 'perform_consultation', NULL, '2026-01-30 06:37:23', '2026-01-30 06:37:23'),
(34, 'view_consultations', NULL, '2026-01-30 06:37:23', '2026-01-30 06:37:23'),
(35, 'view_ipd', NULL, '2026-01-30 06:37:23', '2026-01-30 06:37:23'),
(36, 'view_admissions', NULL, '2026-01-30 06:37:23', '2026-01-30 06:37:23'),
(37, 'create_admissions', NULL, '2026-01-30 06:37:23', '2026-01-30 06:37:23'),
(38, 'discharge_patients', NULL, '2026-01-30 06:37:23', '2026-01-30 06:37:23'),
(39, 'manage_beds', NULL, '2026-01-30 06:37:23', '2026-01-30 06:37:23'),
(40, 'manage_wards', NULL, '2026-01-30 06:37:23', '2026-01-30 06:37:23'),
(41, 'view_nursing_notes', NULL, '2026-01-30 06:37:23', '2026-01-30 06:37:23'),
(42, 'create_nursing_notes', NULL, '2026-01-30 06:37:23', '2026-01-30 06:37:23'),
(43, 'create_prescriptions', NULL, '2026-01-30 06:37:23', '2026-01-30 06:37:23'),
(44, 'view_prescriptions', NULL, '2026-01-30 06:37:23', '2026-01-30 06:37:23'),
(45, 'view_lab', NULL, '2026-01-30 06:37:23', '2026-01-30 06:37:23'),
(46, 'view_lab_orders', NULL, '2026-01-30 06:37:23', '2026-01-30 06:37:23'),
(47, 'create_lab_orders', NULL, '2026-01-30 06:37:23', '2026-01-30 06:37:23'),
(48, 'enter_lab_results', NULL, '2026-01-30 06:37:23', '2026-01-30 06:37:23'),
(49, 'view_pharmacy', NULL, '2026-01-30 06:37:23', '2026-01-30 06:37:23'),
(50, 'view_pharmacy_inventory', NULL, '2026-01-30 06:37:23', '2026-01-30 06:37:23'),
(51, 'manage_pharmacy_inventory', NULL, '2026-01-30 06:37:23', '2026-01-30 06:37:23'),
(52, 'process_pharmacy_sales', NULL, '2026-01-30 06:37:23', '2026-01-30 06:37:23'),
(53, 'view_billing', NULL, '2026-01-30 06:37:23', '2026-01-30 06:37:23'),
(54, 'view_invoices', NULL, '2026-01-30 06:37:23', '2026-01-30 06:37:23'),
(55, 'create_invoices', NULL, '2026-01-30 06:37:23', '2026-01-30 06:37:23'),
(56, 'process_payments', NULL, '2026-01-30 06:37:23', '2026-01-30 06:37:23'),
(57, 'view_reports', NULL, '2026-01-30 06:37:23', '2026-01-30 06:37:23'),
(58, 'view_financial_reports', NULL, '2026-01-30 06:37:23', '2026-01-30 06:37:23');

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
  CONSTRAINT `lara_users_clinic_id_foreign` FOREIGN KEY (`clinic_id`) REFERENCES `lara_clinics` (`id`) ON DELETE RESTRICT
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table `lara_users`
INSERT INTO `lara_users` VALUES (1, 1, 'superadmin@hospital.com', 'assets/img/profile/super-admin.jpg', 'Super Admin', '$2y$12$5NJjx4hrNC9z0HafbXLiXuBoBUmq9UEsymnVR/LHacVZZBCTyWeO6', NULL, NULL, '2026-01-30 06:37:25', NULL, 0, 'active', '2026-01-30 06:37:25', '2026-01-30 06:37:25', NULL),
(2, 1, 'admin@hospital.com', 'assets/img/profile/super-admin.jpg', 'Clinic Admin', '$2y$12$5NJjx4hrNC9z0HafbXLiXuBoBUmq9UEsymnVR/LHacVZZBCTyWeO6', NULL, NULL, '2026-01-30 06:37:25', NULL, 0, 'active', '2026-01-30 06:37:25', '2026-01-30 06:37:25', NULL),
(3, 1, 'doctor@hospital.com', 'assets/img/profile/dr-rahim-ahmed.jpg', 'Dr. Rahim Ahmed', '$2y$12$BUc6yEPXt5fU6L5dVUbzOunllQkVB4pxF3xt0P/5YN8GSZMtPUoby', NULL, NULL, '2026-01-30 06:37:25', NULL, 0, 'active', '2026-01-30 06:37:25', '2026-01-30 06:37:25', NULL),
(4, 1, 'nurse@hospital.com', 'assets/img/profile/fatima-begum.jpg', 'Fatima Begum', '$2y$12$EJzgAMfsSFhlO73X.pvqjeAGT3C.4VY.tvxHhHl8hVUqqnGxSjhRi', NULL, NULL, '2026-01-30 06:37:26', NULL, 0, 'active', '2026-01-30 06:37:26', '2026-01-30 06:37:26', NULL),
(5, 1, 'receptionist@hospital.com', 'assets/img/profile/sumaiya-akter.jpg', 'Sumaiya Akter', '$2y$12$UtlQCHap8jWuw9DvpdNsK.2QTvs8U/9MUiqj7q9rv1KqrRI0IKp0C', NULL, NULL, '2026-01-30 06:37:26', NULL, 0, 'active', '2026-01-30 06:37:26', '2026-01-30 06:37:26', NULL),
(6, 1, 'lab@hospital.com', 'assets/img/profile/abdul-malek.jpg', 'Abdul Malek', '$2y$12$qrxpqgWmStGcUslUpIHXDuTpBZOWK94CyZBdx.GdQkX4Lyat9BGza', NULL, NULL, '2026-01-30 06:37:27', NULL, 0, 'active', '2026-01-30 06:37:27', '2026-01-30 06:37:27', NULL),
(7, 1, 'pharmacist@hospital.com', 'assets/img/profile/hassan-mahmud.jpg', 'Hassan Mahmud', '$2y$12$0DBPVMAWpoA3NNE3QTCE7OegBgRYlBBLiKsZsxmMoJELhQzYW9VLq', NULL, NULL, '2026-01-30 06:37:27', NULL, 0, 'active', '2026-01-30 06:37:27', '2026-01-30 06:37:27', NULL),
(8, 1, 'accountant@hospital.com', 'assets/img/profile/rafiqul-islam.jpg', 'Rafiqul Islam', '$2y$12$wPHtvKatWYFbjZG4Ms.fkudd45Lhd6sl0mhTiCy6TjYj3I1s6JZbC', NULL, NULL, '2026-01-30 06:37:28', NULL, 0, 'active', '2026-01-30 06:37:28', '2026-01-30 06:37:28', NULL);

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
  CONSTRAINT `lara_departments_clinic_id_foreign` FOREIGN KEY (`clinic_id`) REFERENCES `lara_clinics` (`id`) ON DELETE RESTRICT
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table `lara_departments`
INSERT INTO `lara_departments` VALUES (1, 1, 'General Medicine', 'General Medicine Department', NULL, NULL, 'active', '2026-01-30 06:37:25', '2026-01-30 06:37:25', NULL);

-- Table structure for table `lara_patients`
DROP TABLE IF EXISTS `lara_patients`;
CREATE TABLE `lara_patients` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `clinic_id` bigint unsigned DEFAULT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `patient_code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `date_of_birth` date DEFAULT NULL,
  `age` int unsigned DEFAULT NULL,
  `gender` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `blood_group` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `must_change_password` tinyint(1) NOT NULL DEFAULT '1',
  `password_changed_at` timestamp NULL DEFAULT NULL,
  `last_login_at` timestamp NULL DEFAULT NULL,
  `nid_number` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `birth_certificate_number` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `passport_number` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
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
  UNIQUE KEY `lara_patients_clinic_id_passport_number_unique` (`clinic_id`,`passport_number`),
  KEY `lara_patients_user_id_foreign` (`user_id`),
  CONSTRAINT `lara_patients_clinic_id_foreign` FOREIGN KEY (`clinic_id`) REFERENCES `lara_clinics` (`id`) ON DELETE RESTRICT,
  CONSTRAINT `lara_patients_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `lara_users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table `lara_patients`
-- Table structure for table `lara_doctors`
DROP TABLE IF EXISTS `lara_doctors`;

CREATE TABLE `lara_doctors` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,

  `user_id` bigint(20) UNSIGNED NOT NULL,
  `clinic_id` bigint(20) UNSIGNED DEFAULT NULL,
  `primary_department_id` bigint(20) UNSIGNED NOT NULL,

  `registration_number` varchar(255) DEFAULT NULL,
  `license_number` varchar(255) DEFAULT NULL,

  `specialization` json DEFAULT NULL,

  `experience_years` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `gender` varchar(255) DEFAULT NULL,
  `blood_group` varchar(255) DEFAULT NULL,
  `date_of_birth` date DEFAULT NULL,

  `consultation_fee` decimal(10,2) DEFAULT NULL,
  `follow_up_fee` decimal(10,2) DEFAULT NULL,

  `consultation_room_number` varchar(255) DEFAULT NULL,
  `consultation_floor` varchar(255) DEFAULT NULL,

  `location` varchar(255) DEFAULT NULL,

  `biography` text DEFAULT NULL,
  `profile_photo` varchar(255) DEFAULT NULL,

  `is_featured` tinyint(1) NOT NULL DEFAULT 0,
  `status` enum('active','inactive') NOT NULL DEFAULT 'active',

  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,

  PRIMARY KEY (`id`),

  UNIQUE KEY `lara_doctors_license_number_unique` (`license_number`),
  UNIQUE KEY `lara_doctors_registration_number_unique` (`registration_number`),

  KEY `lara_doctors_user_id_foreign` (`user_id`),
  KEY `lara_doctors_clinic_id_foreign` (`clinic_id`),
  KEY `lara_doctors_primary_department_id_foreign` (`primary_department_id`),

  CONSTRAINT `lara_doctors_user_id_foreign`
    FOREIGN KEY (`user_id`) REFERENCES `lara_users` (`id`)
    ON DELETE RESTRICT,

  CONSTRAINT `lara_doctors_primary_department_id_foreign`
    FOREIGN KEY (`primary_department_id`) REFERENCES `lara_departments` (`id`)
    ON DELETE RESTRICT,

  CONSTRAINT `lara_doctors_clinic_id_foreign`
    FOREIGN KEY (`clinic_id`) REFERENCES `lara_clinics` (`id`)
    ON DELETE RESTRICT

) ENGINE=InnoDB
DEFAULT CHARSET=utf8mb4
COLLATE=utf8mb4_unicode_ci;

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
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table `lara_user_role`
INSERT INTO `lara_user_role` VALUES (1, 1, 1, NULL, NULL),
(2, 2, 2, NULL, NULL),
(3, 3, 3, NULL, NULL),
(4, 4, 4, NULL, NULL),
(5, 5, 5, NULL, NULL),
(6, 6, 6, NULL, NULL),
(7, 7, 7, NULL, NULL),
(8, 8, 8, NULL, NULL);

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
) ENGINE=InnoDB AUTO_INCREMENT=174 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table `lara_role_permission`
INSERT INTO `lara_role_permission` VALUES (1, 1, 1, NULL, NULL),
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
(58, 1, 58, NULL, NULL),
(59, 2, 1, NULL, NULL),
(60, 2, 2, NULL, NULL),
(61, 2, 3, NULL, NULL),
(62, 2, 4, NULL, NULL),
(63, 2, 5, NULL, NULL),
(64, 2, 6, NULL, NULL),
(65, 2, 7, NULL, NULL),
(66, 2, 8, NULL, NULL),
(67, 2, 9, NULL, NULL),
(68, 2, 10, NULL, NULL),
(69, 2, 11, NULL, NULL),
(70, 2, 12, NULL, NULL),
(71, 2, 13, NULL, NULL),
(72, 2, 14, NULL, NULL),
(73, 2, 15, NULL, NULL),
(74, 2, 16, NULL, NULL),
(75, 2, 17, NULL, NULL),
(76, 2, 18, NULL, NULL),
(77, 2, 19, NULL, NULL),
(78, 2, 20, NULL, NULL),
(79, 2, 21, NULL, NULL),
(80, 2, 22, NULL, NULL),
(81, 2, 23, NULL, NULL),
(82, 2, 24, NULL, NULL),
(83, 2, 25, NULL, NULL),
(84, 2, 26, NULL, NULL),
(85, 2, 27, NULL, NULL),
(86, 2, 28, NULL, NULL),
(87, 2, 29, NULL, NULL),
(88, 2, 30, NULL, NULL),
(89, 2, 31, NULL, NULL),
(90, 2, 32, NULL, NULL),
(91, 2, 33, NULL, NULL),
(92, 2, 34, NULL, NULL),
(93, 2, 35, NULL, NULL),
(94, 2, 36, NULL, NULL),
(95, 2, 37, NULL, NULL),
(96, 2, 38, NULL, NULL),
(97, 2, 39, NULL, NULL),
(98, 2, 40, NULL, NULL),
(99, 2, 41, NULL, NULL),
(100, 2, 42, NULL, NULL),
(101, 2, 43, NULL, NULL),
(102, 2, 44, NULL, NULL),
(103, 2, 45, NULL, NULL),
(104, 2, 46, NULL, NULL),
(105, 2, 47, NULL, NULL),
(106, 2, 48, NULL, NULL),
(107, 2, 49, NULL, NULL),
(108, 2, 50, NULL, NULL),
(109, 2, 51, NULL, NULL),
(110, 2, 52, NULL, NULL),
(111, 2, 53, NULL, NULL),
(112, 2, 54, NULL, NULL),
(113, 2, 55, NULL, NULL),
(114, 2, 56, NULL, NULL),
(115, 2, 57, NULL, NULL),
(116, 2, 58, NULL, NULL),
(117, 3, 47, NULL, NULL),
(118, 3, 43, NULL, NULL),
(119, 3, 17, NULL, NULL),
(120, 3, 33, NULL, NULL),
(121, 3, 36, NULL, NULL),
(122, 3, 29, NULL, NULL),
(123, 3, 34, NULL, NULL),
(124, 3, 1, NULL, NULL),
(125, 3, 3, NULL, NULL),
(126, 3, 35, NULL, NULL),
(127, 3, 45, NULL, NULL),
(128, 3, 46, NULL, NULL),
(129, 3, 15, NULL, NULL),
(130, 3, 44, NULL, NULL),
(131, 4, 42, NULL, NULL),
(132, 4, 39, NULL, NULL),
(133, 4, 36, NULL, NULL),
(134, 4, 1, NULL, NULL),
(135, 4, 35, NULL, NULL),
(136, 4, 4, NULL, NULL),
(137, 4, 41, NULL, NULL),
(138, 4, 15, NULL, NULL),
(139, 5, 32, NULL, NULL),
(140, 5, 30, NULL, NULL),
(141, 5, 55, NULL, NULL),
(142, 5, 16, NULL, NULL),
(143, 5, 31, NULL, NULL),
(144, 5, 17, NULL, NULL),
(145, 5, 29, NULL, NULL),
(146, 5, 53, NULL, NULL),
(147, 5, 1, NULL, NULL),
(148, 5, 15, NULL, NULL),
(149, 5, 5, NULL, NULL),
(150, 6, 48, NULL, NULL),
(151, 6, 1, NULL, NULL),
(152, 6, 45, NULL, NULL),
(153, 6, 6, NULL, NULL),
(154, 6, 46, NULL, NULL),
(155, 7, 55, NULL, NULL),
(156, 7, 51, NULL, NULL),
(157, 7, 56, NULL, NULL),
(158, 7, 52, NULL, NULL),
(159, 7, 53, NULL, NULL),
(160, 7, 1, NULL, NULL),
(161, 7, 54, NULL, NULL),
(162, 7, 49, NULL, NULL),
(163, 7, 7, NULL, NULL),
(164, 7, 50, NULL, NULL),
(165, 7, 44, NULL, NULL),
(166, 8, 55, NULL, NULL),
(167, 8, 56, NULL, NULL),
(168, 8, 8, NULL, NULL),
(169, 8, 53, NULL, NULL),
(170, 8, 1, NULL, NULL),
(171, 8, 58, NULL, NULL),
(172, 8, 54, NULL, NULL),
(173, 8, 57, NULL, NULL);

-- Table structure for table `lara_clinic_patient`
DROP TABLE IF EXISTS `lara_clinic_patient`;
CREATE TABLE `lara_clinic_patient` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `clinic_id` bigint unsigned NOT NULL,
  `patient_id` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `lara_clinic_patient_clinic_id_patient_id_unique` (`clinic_id`,`patient_id`),
  KEY `lara_clinic_patient_patient_id_foreign` (`patient_id`),
  CONSTRAINT `lara_clinic_patient_clinic_id_foreign` FOREIGN KEY (`clinic_id`) REFERENCES `lara_clinics` (`id`) ON DELETE CASCADE,
  CONSTRAINT `lara_clinic_patient_patient_id_foreign` FOREIGN KEY (`patient_id`) REFERENCES `lara_patients` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table `lara_clinic_patient`
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
  CONSTRAINT `lara_doctor_clinic_clinic_id_foreign` FOREIGN KEY (`clinic_id`) REFERENCES `lara_clinics` (`id`) ON DELETE RESTRICT,
  CONSTRAINT `lara_doctor_clinic_doctor_id_foreign` FOREIGN KEY (`doctor_id`) REFERENCES `lara_doctors` (`id`) ON DELETE RESTRICT
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table `lara_doctor_clinic`
INSERT INTO `lara_doctor_clinic` VALUES (1, 1, 1, NULL, 1, NULL, 'active', NULL, NULL);

-- Table structure for table `lara_activity_logs`
DROP TABLE IF EXISTS `lara_activity_logs`;
CREATE TABLE `lara_activity_logs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned DEFAULT NULL,
  `clinic_id` bigint unsigned DEFAULT NULL,
  `action` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
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
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table `lara_activity_logs`
INSERT INTO `lara_activity_logs` VALUES (1, NULL, NULL, 'created', NULL, 'App\\Models\\Role', 1, '127.0.0.1', '2026-01-30 06:37:09'),
(2, NULL, NULL, 'created', 'Created role Super Admin', 'App\\Models\\Role', 1, '127.0.0.1', '2026-01-30 06:37:23'),
(3, NULL, NULL, 'created', 'Created role Clinic Admin', 'App\\Models\\Role', 2, '127.0.0.1', '2026-01-30 06:37:24'),
(4, NULL, NULL, 'created', 'Created role Doctor', 'App\\Models\\Role', 3, '127.0.0.1', '2026-01-30 06:37:24'),
(5, NULL, NULL, 'created', 'Created role Nurse', 'App\\Models\\Role', 4, '127.0.0.1', '2026-01-30 06:37:24'),
(6, NULL, NULL, 'created', 'Created role Receptionist', 'App\\Models\\Role', 5, '127.0.0.1', '2026-01-30 06:37:24'),
(7, NULL, NULL, 'created', 'Created role Lab Technician', 'App\\Models\\Role', 6, '127.0.0.1', '2026-01-30 06:37:24'),
(8, NULL, NULL, 'created', 'Created role Pharmacist', 'App\\Models\\Role', 7, '127.0.0.1', '2026-01-30 06:37:24'),
(9, NULL, NULL, 'created', 'Created role Accountant', 'App\\Models\\Role', 8, '127.0.0.1', '2026-01-30 06:37:24'),
(10, NULL, 1, 'created', 'Created user Super Admin (No Role)', 'App\\Models\\User', 1, '127.0.0.1', '2026-01-30 06:37:25'),
(11, NULL, 1, 'created', 'Created user Dr. Rahim Ahmed (No Role)', 'App\\Models\\User', 2, '127.0.0.1', '2026-01-30 06:37:25'),
(12, NULL, 1, 'created', 'Created department General Medicine', 'App\\Models\\Department', 1, '127.0.0.1', '2026-01-30 06:37:25'),
(13, NULL, 1, 'created', 'Created doctor profile for Dr. Rahim Ahmed', 'App\\Models\\Doctor', 1, '127.0.0.1', '2026-01-30 06:37:25'),
(14, NULL, 1, 'created', 'Created user Fatima Begum (No Role)', 'App\\Models\\User', 3, '127.0.0.1', '2026-01-30 06:37:26'),
(15, NULL, 1, 'created', 'Created user Sumaiya Akter (No Role)', 'App\\Models\\User', 4, '127.0.0.1', '2026-01-30 06:37:26'),
(16, NULL, 1, 'created', 'Created user Abdul Malek (No Role)', 'App\\Models\\User', 5, '127.0.0.1', '2026-01-30 06:37:27'),
(17, NULL, 1, 'created', 'Created user Hassan Mahmud (No Role)', 'App\\Models\\User', 6, '127.0.0.1', '2026-01-30 06:37:27'),
(18, NULL, 1, 'created', 'Created user Rafiqul Islam (No Role)', 'App\\Models\\User', 7, '127.0.0.1', '2026-01-30 06:37:28'),
(19, 1, 1, 'login', 'User logged in', 'App\\Models\\User', 1, '127.0.0.1', '2026-01-30 07:05:30');

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
  CONSTRAINT `lara_admission_deposits_admission_id_foreign` FOREIGN KEY (`admission_id`) REFERENCES `lara_admissions` (`id`) ON DELETE RESTRICT,
  CONSTRAINT `lara_admission_deposits_clinic_id_foreign` FOREIGN KEY (`clinic_id`) REFERENCES `lara_clinics` (`id`) ON DELETE RESTRICT,
  CONSTRAINT `lara_admission_deposits_received_by_foreign` FOREIGN KEY (`received_by`) REFERENCES `lara_users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table `lara_admission_deposits`
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
  CONSTRAINT `lara_admissions_admitting_doctor_id_foreign` FOREIGN KEY (`admitting_doctor_id`) REFERENCES `lara_doctors` (`id`) ON DELETE RESTRICT,
  CONSTRAINT `lara_admissions_clinic_id_foreign` FOREIGN KEY (`clinic_id`) REFERENCES `lara_clinics` (`id`) ON DELETE RESTRICT,
  CONSTRAINT `lara_admissions_current_bed_id_foreign` FOREIGN KEY (`current_bed_id`) REFERENCES `lara_beds` (`id`) ON DELETE SET NULL,
  CONSTRAINT `lara_admissions_discharge_recommended_by_foreign` FOREIGN KEY (`discharge_recommended_by`) REFERENCES `lara_users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `lara_admissions_discharged_by_foreign` FOREIGN KEY (`discharged_by`) REFERENCES `lara_users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `lara_admissions_patient_id_foreign` FOREIGN KEY (`patient_id`) REFERENCES `lara_patients` (`id`) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table `lara_admissions`
-- Table structure for table `lara_appointment_requests`
DROP TABLE IF EXISTS `lara_appointment_requests`;
CREATE TABLE `lara_appointment_requests` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `appointment_id` bigint unsigned NOT NULL,
  `clinic_id` bigint unsigned NOT NULL,
  `type` enum('cancel','reschedule') COLLATE utf8mb4_unicode_ci NOT NULL,
  `reason` text COLLATE utf8mb4_unicode_ci,
  `desired_date` date DEFAULT NULL,
  `desired_time` time DEFAULT NULL,
  `status` enum('pending','approved','rejected') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `admin_notes` text COLLATE utf8mb4_unicode_ci,
  `processed_by` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `lara_appointment_requests_appointment_id_foreign` (`appointment_id`),
  KEY `lara_appointment_requests_clinic_id_foreign` (`clinic_id`),
  KEY `lara_appointment_requests_processed_by_foreign` (`processed_by`),
  CONSTRAINT `lara_appointment_requests_appointment_id_foreign` FOREIGN KEY (`appointment_id`) REFERENCES `lara_appointments` (`id`) ON DELETE CASCADE,
  CONSTRAINT `lara_appointment_requests_clinic_id_foreign` FOREIGN KEY (`clinic_id`) REFERENCES `lara_clinics` (`id`) ON DELETE CASCADE,
  CONSTRAINT `lara_appointment_requests_processed_by_foreign` FOREIGN KEY (`processed_by`) REFERENCES `lara_users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table `lara_appointment_requests`
-- Table structure for table `lara_appointment_status_logs`
DROP TABLE IF EXISTS `lara_appointment_status_logs`;
CREATE TABLE `lara_appointment_status_logs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `appointment_id` bigint unsigned NOT NULL,
  `old_status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `new_status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `changed_by` bigint unsigned NOT NULL,
  `change_reason` text COLLATE utf8mb4_unicode_ci,
  `changed_at` timestamp NOT NULL,
  `clinic_id` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `lara_appointment_status_logs_appointment_id_foreign` (`appointment_id`),
  KEY `lara_appointment_status_logs_changed_by_foreign` (`changed_by`),
  KEY `lara_appointment_status_logs_clinic_id_foreign` (`clinic_id`),
  CONSTRAINT `lara_appointment_status_logs_appointment_id_foreign` FOREIGN KEY (`appointment_id`) REFERENCES `lara_appointments` (`id`) ON DELETE RESTRICT,
  CONSTRAINT `lara_appointment_status_logs_changed_by_foreign` FOREIGN KEY (`changed_by`) REFERENCES `lara_users` (`id`) ON DELETE RESTRICT,
  CONSTRAINT `lara_appointment_status_logs_clinic_id_foreign` FOREIGN KEY (`clinic_id`) REFERENCES `lara_clinics` (`id`) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table `lara_appointment_status_logs`
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
  `booking_source` enum('reception','patient_portal','online','in_person') COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` enum('pending','arrived','confirmed','completed','cancelled','noshow') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `fee` decimal(10,2) DEFAULT NULL,
  `visit_type` enum('new','follow_up') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'new',
  `created_by` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `lara_appointments_patient_id_foreign` (`patient_id`),
  KEY `lara_appointments_doctor_id_foreign` (`doctor_id`),
  KEY `lara_appointments_department_id_foreign` (`department_id`),
  KEY `lara_appointments_created_by_foreign` (`created_by`),
  KEY `lara_appointments_clinic_id_appointment_date_index` (`clinic_id`,`appointment_date`),
  CONSTRAINT `lara_appointments_clinic_id_foreign` FOREIGN KEY (`clinic_id`) REFERENCES `lara_clinics` (`id`) ON DELETE RESTRICT,
  CONSTRAINT `lara_appointments_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `lara_users` (`id`) ON DELETE RESTRICT,
  CONSTRAINT `lara_appointments_department_id_foreign` FOREIGN KEY (`department_id`) REFERENCES `lara_departments` (`id`) ON DELETE RESTRICT,
  CONSTRAINT `lara_appointments_doctor_id_foreign` FOREIGN KEY (`doctor_id`) REFERENCES `lara_doctors` (`id`) ON DELETE RESTRICT,
  CONSTRAINT `lara_appointments_patient_id_foreign` FOREIGN KEY (`patient_id`) REFERENCES `lara_patients` (`id`) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table `lara_appointments`
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
  CONSTRAINT `lara_bed_assignments_admission_id_foreign` FOREIGN KEY (`admission_id`) REFERENCES `lara_admissions` (`id`) ON DELETE RESTRICT,
  CONSTRAINT `lara_bed_assignments_bed_id_foreign` FOREIGN KEY (`bed_id`) REFERENCES `lara_beds` (`id`) ON DELETE RESTRICT,
  CONSTRAINT `lara_bed_assignments_clinic_id_foreign` FOREIGN KEY (`clinic_id`) REFERENCES `lara_clinics` (`id`) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table `lara_bed_assignments`
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
  CONSTRAINT `lara_beds_clinic_id_foreign` FOREIGN KEY (`clinic_id`) REFERENCES `lara_clinics` (`id`) ON DELETE RESTRICT,
  CONSTRAINT `lara_beds_room_id_foreign` FOREIGN KEY (`room_id`) REFERENCES `lara_rooms` (`id`) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table `lara_beds`
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table `lara_clinic_images`
-- Table structure for table `lara_consultations`
DROP TABLE IF EXISTS `lara_consultations`;
CREATE TABLE `lara_consultations` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `visit_id` bigint unsigned NOT NULL,
  `doctor_notes` longtext COLLATE utf8mb4_unicode_ci,
  `diagnosis` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `follow_up_required` tinyint(1) NOT NULL DEFAULT '0',
  `follow_up_date` date DEFAULT NULL,
  `created_at` timestamp NOT NULL,
  `clinic_id` bigint unsigned DEFAULT NULL,
  `doctor_id` bigint unsigned DEFAULT NULL,
  `patient_id` bigint unsigned DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'in_progress',
  `deleted_at` timestamp NULL DEFAULT NULL,
  `symptoms` json DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `lara_consultations_visit_id_foreign` (`visit_id`),
  KEY `lara_consultations_clinic_id_foreign` (`clinic_id`),
  KEY `lara_consultations_doctor_id_foreign` (`doctor_id`),
  KEY `lara_consultations_patient_id_foreign` (`patient_id`),
  CONSTRAINT `lara_consultations_clinic_id_foreign` FOREIGN KEY (`clinic_id`) REFERENCES `lara_clinics` (`id`) ON DELETE RESTRICT,
  CONSTRAINT `lara_consultations_doctor_id_foreign` FOREIGN KEY (`doctor_id`) REFERENCES `lara_doctors` (`id`) ON DELETE RESTRICT,
  CONSTRAINT `lara_consultations_patient_id_foreign` FOREIGN KEY (`patient_id`) REFERENCES `lara_patients` (`id`) ON DELETE RESTRICT,
  CONSTRAINT `lara_consultations_visit_id_foreign` FOREIGN KEY (`visit_id`) REFERENCES `lara_visits` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table `lara_consultations`
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

-- Dumping data for table `lara_doctor_awards`
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

-- Dumping data for table `lara_doctor_certifications`
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

-- Dumping data for table `lara_doctor_education`
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
  CONSTRAINT `lara_doctor_schedule_exceptions_clinic_id_foreign` FOREIGN KEY (`clinic_id`) REFERENCES `lara_clinics` (`id`) ON DELETE RESTRICT,
  CONSTRAINT `lara_doctor_schedule_exceptions_doctor_id_foreign` FOREIGN KEY (`doctor_id`) REFERENCES `lara_doctors` (`id`) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table `lara_doctor_schedule_exceptions`
-- Table structure for table `lara_doctor_schedule_requests`
DROP TABLE IF EXISTS `lara_doctor_schedule_requests`;
CREATE TABLE `lara_doctor_schedule_requests` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `doctor_id` bigint unsigned NOT NULL,
  `clinic_id` bigint unsigned NOT NULL,
  `schedules` json NOT NULL,
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
  CONSTRAINT `lara_doctor_schedule_requests_clinic_id_foreign` FOREIGN KEY (`clinic_id`) REFERENCES `lara_clinics` (`id`) ON DELETE RESTRICT,
  CONSTRAINT `lara_doctor_schedule_requests_doctor_id_foreign` FOREIGN KEY (`doctor_id`) REFERENCES `lara_doctors` (`id`) ON DELETE RESTRICT,
  CONSTRAINT `lara_doctor_schedule_requests_processed_by_foreign` FOREIGN KEY (`processed_by`) REFERENCES `lara_users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `lara_doctor_schedule_requests_requested_by_foreign` FOREIGN KEY (`requested_by`) REFERENCES `lara_users` (`id`) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table `lara_doctor_schedule_requests`
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
  CONSTRAINT `lara_doctor_schedules_clinic_id_foreign` FOREIGN KEY (`clinic_id`) REFERENCES `lara_clinics` (`id`) ON DELETE RESTRICT,
  CONSTRAINT `lara_doctor_schedules_department_id_foreign` FOREIGN KEY (`department_id`) REFERENCES `lara_departments` (`id`) ON DELETE RESTRICT,
  CONSTRAINT `lara_doctor_schedules_doctor_id_foreign` FOREIGN KEY (`doctor_id`) REFERENCES `lara_doctors` (`id`) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table `lara_doctor_schedules`
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
  CONSTRAINT `lara_inpatient_rounds_admission_id_foreign` FOREIGN KEY (`admission_id`) REFERENCES `lara_admissions` (`id`) ON DELETE RESTRICT,
  CONSTRAINT `lara_inpatient_rounds_clinic_id_foreign` FOREIGN KEY (`clinic_id`) REFERENCES `lara_clinics` (`id`) ON DELETE RESTRICT,
  CONSTRAINT `lara_inpatient_rounds_doctor_id_foreign` FOREIGN KEY (`doctor_id`) REFERENCES `lara_doctors` (`id`) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table `lara_inpatient_rounds`
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
  CONSTRAINT `lara_inpatient_services_admission_id_foreign` FOREIGN KEY (`admission_id`) REFERENCES `lara_admissions` (`id`) ON DELETE RESTRICT,
  CONSTRAINT `lara_inpatient_services_clinic_id_foreign` FOREIGN KEY (`clinic_id`) REFERENCES `lara_clinics` (`id`) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table `lara_inpatient_services`
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
  CONSTRAINT `lara_invoice_items_clinic_id_foreign` FOREIGN KEY (`clinic_id`) REFERENCES `lara_clinics` (`id`) ON DELETE RESTRICT,
  CONSTRAINT `lara_invoice_items_invoice_id_foreign` FOREIGN KEY (`invoice_id`) REFERENCES `lara_invoices` (`id`) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table `lara_invoice_items`
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
  CONSTRAINT `lara_invoices_clinic_id_foreign` FOREIGN KEY (`clinic_id`) REFERENCES `lara_clinics` (`id`) ON DELETE RESTRICT,
  CONSTRAINT `lara_invoices_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `lara_users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `lara_invoices_finalized_by_foreign` FOREIGN KEY (`finalized_by`) REFERENCES `lara_users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `lara_invoices_patient_id_foreign` FOREIGN KEY (`patient_id`) REFERENCES `lara_patients` (`id`) ON DELETE RESTRICT,
  CONSTRAINT `lara_invoices_visit_id_foreign` FOREIGN KEY (`visit_id`) REFERENCES `lara_visits` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table `lara_invoices`
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
  CONSTRAINT `lara_lab_test_orders_appointment_id_foreign` FOREIGN KEY (`appointment_id`) REFERENCES `lara_appointments` (`id`) ON DELETE RESTRICT,
  CONSTRAINT `lara_lab_test_orders_clinic_id_foreign` FOREIGN KEY (`clinic_id`) REFERENCES `lara_clinics` (`id`) ON DELETE RESTRICT,
  CONSTRAINT `lara_lab_test_orders_doctor_id_foreign` FOREIGN KEY (`doctor_id`) REFERENCES `lara_doctors` (`id`) ON DELETE RESTRICT,
  CONSTRAINT `lara_lab_test_orders_invoice_id_foreign` FOREIGN KEY (`invoice_id`) REFERENCES `lara_invoices` (`id`) ON DELETE SET NULL,
  CONSTRAINT `lara_lab_test_orders_lab_test_id_foreign` FOREIGN KEY (`lab_test_id`) REFERENCES `lara_lab_tests` (`id`) ON DELETE RESTRICT,
  CONSTRAINT `lara_lab_test_orders_patient_id_foreign` FOREIGN KEY (`patient_id`) REFERENCES `lara_patients` (`id`) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table `lara_lab_test_orders`
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
  `reported_at` timestamp NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `clinic_id` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `lara_lab_test_results_lab_test_order_id_foreign` (`lab_test_order_id`),
  KEY `lara_lab_test_results_lab_test_id_foreign` (`lab_test_id`),
  KEY `lara_lab_test_results_reported_by_foreign` (`reported_by`),
  KEY `lara_lab_test_results_clinic_id_foreign` (`clinic_id`),
  CONSTRAINT `lara_lab_test_results_clinic_id_foreign` FOREIGN KEY (`clinic_id`) REFERENCES `lara_clinics` (`id`) ON DELETE RESTRICT,
  CONSTRAINT `lara_lab_test_results_lab_test_id_foreign` FOREIGN KEY (`lab_test_id`) REFERENCES `lara_lab_tests` (`id`) ON DELETE RESTRICT,
  CONSTRAINT `lara_lab_test_results_lab_test_order_id_foreign` FOREIGN KEY (`lab_test_order_id`) REFERENCES `lara_lab_test_orders` (`id`) ON DELETE RESTRICT,
  CONSTRAINT `lara_lab_test_results_reported_by_foreign` FOREIGN KEY (`reported_by`) REFERENCES `lara_users` (`id`) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table `lara_lab_test_results`
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table `lara_lab_tests`
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
  CONSTRAINT `lara_medicine_batches_clinic_id_foreign` FOREIGN KEY (`clinic_id`) REFERENCES `lara_clinics` (`id`) ON DELETE RESTRICT,
  CONSTRAINT `lara_medicine_batches_medicine_id_foreign` FOREIGN KEY (`medicine_id`) REFERENCES `lara_medicines` (`id`) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table `lara_medicine_batches`
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table `lara_medicines`
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

-- Dumping data for table `lara_notifications`
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
  CONSTRAINT `lara_nursing_notes_admission_id_foreign` FOREIGN KEY (`admission_id`) REFERENCES `lara_admissions` (`id`) ON DELETE RESTRICT,
  CONSTRAINT `lara_nursing_notes_clinic_id_foreign` FOREIGN KEY (`clinic_id`) REFERENCES `lara_clinics` (`id`) ON DELETE RESTRICT,
  CONSTRAINT `lara_nursing_notes_nurse_id_foreign` FOREIGN KEY (`nurse_id`) REFERENCES `lara_users` (`id`) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table `lara_nursing_notes`
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
  CONSTRAINT `lara_patient_allergies_patient_id_foreign` FOREIGN KEY (`patient_id`) REFERENCES `lara_patients` (`id`) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table `lara_patient_allergies`
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
  CONSTRAINT `lara_patient_complaints_clinic_id_foreign` FOREIGN KEY (`clinic_id`) REFERENCES `lara_clinics` (`id`) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table `lara_patient_complaints`
-- Table structure for table `lara_patient_immunizations`
DROP TABLE IF EXISTS `lara_patient_immunizations`;
CREATE TABLE `lara_patient_immunizations` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `patient_id` bigint unsigned NOT NULL,
  `vaccine_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `immunization_date` date DEFAULT NULL,
  `provider_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `lara_patient_immunizations_patient_id_foreign` (`patient_id`),
  CONSTRAINT `lara_patient_immunizations_patient_id_foreign` FOREIGN KEY (`patient_id`) REFERENCES `lara_patients` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table `lara_patient_immunizations`
-- Table structure for table `lara_patient_medical_history`
DROP TABLE IF EXISTS `lara_patient_medical_history`;
CREATE TABLE `lara_patient_medical_history` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `patient_id` bigint unsigned NOT NULL,
  `condition_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `diagnosed_date` date DEFAULT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `doctor_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `lara_patient_medical_history_patient_id_foreign` (`patient_id`),
  CONSTRAINT `lara_patient_medical_history_patient_id_foreign` FOREIGN KEY (`patient_id`) REFERENCES `lara_patients` (`id`) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table `lara_patient_medical_history`
-- Table structure for table `lara_patient_surgeries`
DROP TABLE IF EXISTS `lara_patient_surgeries`;
CREATE TABLE `lara_patient_surgeries` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `patient_id` bigint unsigned NOT NULL,
  `surgery_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `surgery_date` date DEFAULT NULL,
  `hospital_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `surgeon_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `lara_patient_surgeries_patient_id_foreign` (`patient_id`),
  CONSTRAINT `lara_patient_surgeries_patient_id_foreign` FOREIGN KEY (`patient_id`) REFERENCES `lara_patients` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table `lara_patient_surgeries`
-- Table structure for table `lara_patient_vitals`
DROP TABLE IF EXISTS `lara_patient_vitals`;
CREATE TABLE `lara_patient_vitals` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `patient_id` bigint unsigned NOT NULL,
  `visit_id` bigint unsigned DEFAULT NULL,
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
  `recorded_at` timestamp NOT NULL,
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
  CONSTRAINT `lara_patient_vitals_patient_id_foreign` FOREIGN KEY (`patient_id`) REFERENCES `lara_patients` (`id`) ON DELETE RESTRICT,
  CONSTRAINT `lara_patient_vitals_recorded_by_foreign` FOREIGN KEY (`recorded_by`) REFERENCES `lara_users` (`id`) ON DELETE RESTRICT,
  CONSTRAINT `lara_patient_vitals_visit_id_foreign` FOREIGN KEY (`visit_id`) REFERENCES `lara_visits` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table `lara_patient_vitals`
-- Table structure for table `lara_payments`
DROP TABLE IF EXISTS `lara_payments`;
CREATE TABLE `lara_payments` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `invoice_id` bigint unsigned NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `payment_method` enum('cash','card','mobile_banking','bank_transfer') COLLATE utf8mb4_unicode_ci NOT NULL,
  `transaction_reference` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `paid_at` timestamp NOT NULL,
  `received_by` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `clinic_id` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `lara_payments_invoice_id_foreign` (`invoice_id`),
  KEY `lara_payments_received_by_foreign` (`received_by`),
  KEY `lara_payments_clinic_id_foreign` (`clinic_id`),
  CONSTRAINT `lara_payments_clinic_id_foreign` FOREIGN KEY (`clinic_id`) REFERENCES `lara_clinics` (`id`) ON DELETE RESTRICT,
  CONSTRAINT `lara_payments_invoice_id_foreign` FOREIGN KEY (`invoice_id`) REFERENCES `lara_invoices` (`id`) ON DELETE RESTRICT,
  CONSTRAINT `lara_payments_received_by_foreign` FOREIGN KEY (`received_by`) REFERENCES `lara_users` (`id`) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table `lara_payments`
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

-- Dumping data for table `lara_personal_access_tokens`
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
  CONSTRAINT `lara_pharmacy_sale_items_medicine_id_foreign` FOREIGN KEY (`medicine_id`) REFERENCES `lara_medicines` (`id`) ON DELETE RESTRICT,
  CONSTRAINT `lara_pharmacy_sale_items_pharmacy_sale_id_foreign` FOREIGN KEY (`pharmacy_sale_id`) REFERENCES `lara_pharmacy_sales` (`id`) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table `lara_pharmacy_sale_items`
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
  CONSTRAINT `lara_pharmacy_sales_clinic_id_foreign` FOREIGN KEY (`clinic_id`) REFERENCES `lara_clinics` (`id`) ON DELETE RESTRICT,
  CONSTRAINT `lara_pharmacy_sales_patient_id_foreign` FOREIGN KEY (`patient_id`) REFERENCES `lara_patients` (`id`) ON DELETE RESTRICT,
  CONSTRAINT `lara_pharmacy_sales_prescription_id_foreign` FOREIGN KEY (`prescription_id`) REFERENCES `lara_prescriptions` (`id`) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table `lara_pharmacy_sales`
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table `lara_prescription_complaint`
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
  CONSTRAINT `lara_prescription_items_clinic_id_foreign` FOREIGN KEY (`clinic_id`) REFERENCES `lara_clinics` (`id`) ON DELETE RESTRICT,
  CONSTRAINT `lara_prescription_items_medicine_id_foreign` FOREIGN KEY (`medicine_id`) REFERENCES `lara_medicines` (`id`) ON DELETE RESTRICT,
  CONSTRAINT `lara_prescription_items_prescription_id_foreign` FOREIGN KEY (`prescription_id`) REFERENCES `lara_prescriptions` (`id`) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table `lara_prescription_items`
-- Table structure for table `lara_prescriptions`
DROP TABLE IF EXISTS `lara_prescriptions`;
CREATE TABLE `lara_prescriptions` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `consultation_id` bigint unsigned NOT NULL,
  `issued_at` timestamp NOT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `clinic_id` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `lara_prescriptions_consultation_id_foreign` (`consultation_id`),
  KEY `lara_prescriptions_clinic_id_foreign` (`clinic_id`),
  CONSTRAINT `lara_prescriptions_clinic_id_foreign` FOREIGN KEY (`clinic_id`) REFERENCES `lara_clinics` (`id`) ON DELETE RESTRICT,
  CONSTRAINT `lara_prescriptions_consultation_id_foreign` FOREIGN KEY (`consultation_id`) REFERENCES `lara_consultations` (`id`) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table `lara_prescriptions`
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
  CONSTRAINT `lara_rooms_clinic_id_foreign` FOREIGN KEY (`clinic_id`) REFERENCES `lara_clinics` (`id`) ON DELETE RESTRICT,
  CONSTRAINT `lara_rooms_ward_id_foreign` FOREIGN KEY (`ward_id`) REFERENCES `lara_wards` (`id`) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table `lara_rooms`
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
  CONSTRAINT `lara_visits_appointment_id_foreign` FOREIGN KEY (`appointment_id`) REFERENCES `lara_appointments` (`id`) ON DELETE RESTRICT,
  CONSTRAINT `lara_visits_clinic_id_foreign` FOREIGN KEY (`clinic_id`) REFERENCES `lara_clinics` (`id`) ON DELETE RESTRICT,
  CONSTRAINT `lara_visits_consultation_id_foreign` FOREIGN KEY (`consultation_id`) REFERENCES `lara_consultations` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table `lara_visits`
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
  CONSTRAINT `lara_wards_clinic_id_foreign` FOREIGN KEY (`clinic_id`) REFERENCES `lara_clinics` (`id`) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table `lara_wards`
SET FOREIGN_KEY_CHECKS=1;
