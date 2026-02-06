-- MySQL dump 10.13  Distrib 8.4.3, for Win64 (x86_64)
--
-- Host: 127.0.0.1    Database: project_hms
-- ------------------------------------------------------
-- Server version	8.4.3

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `lara_activity_logs`
--

DROP TABLE IF EXISTS `lara_activity_logs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
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
) ENGINE=InnoDB AUTO_INCREMENT=77 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `lara_activity_logs`
--

LOCK TABLES `lara_activity_logs` WRITE;
/*!40000 ALTER TABLE `lara_activity_logs` DISABLE KEYS */;
INSERT INTO `lara_activity_logs` VALUES (1,NULL,NULL,'created',NULL,'App\\Models\\Role',1,'127.0.0.1','2026-02-06 00:35:03'),(2,NULL,NULL,'created','Created role Super Admin','App\\Models\\Role',1,'127.0.0.1','2026-02-06 00:35:11'),(3,NULL,NULL,'created','Created role Clinic Admin','App\\Models\\Role',2,'127.0.0.1','2026-02-06 00:35:12'),(4,NULL,NULL,'created','Created role Admin','App\\Models\\Role',3,'127.0.0.1','2026-02-06 00:35:12'),(5,NULL,NULL,'created','Created role Doctor','App\\Models\\Role',4,'127.0.0.1','2026-02-06 00:35:12'),(6,NULL,NULL,'created','Created role Nurse','App\\Models\\Role',5,'127.0.0.1','2026-02-06 00:35:12'),(7,NULL,NULL,'created','Created role Receptionist','App\\Models\\Role',6,'127.0.0.1','2026-02-06 00:35:12'),(8,NULL,NULL,'created','Created role Lab Technician','App\\Models\\Role',7,'127.0.0.1','2026-02-06 00:35:12'),(9,NULL,NULL,'created','Created role Pharmacist','App\\Models\\Role',8,'127.0.0.1','2026-02-06 00:35:12'),(10,NULL,NULL,'created','Created role Accountant','App\\Models\\Role',9,'127.0.0.1','2026-02-06 00:35:12'),(11,NULL,1,'created','Created department Cardiology','App\\Models\\Department',1,'127.0.0.1','2026-02-06 00:35:12'),(12,NULL,1,'created','Created department Orthopedics','App\\Models\\Department',2,'127.0.0.1','2026-02-06 00:35:12'),(13,NULL,1,'created','Created department Pediatrics','App\\Models\\Department',3,'127.0.0.1','2026-02-06 00:35:12'),(14,NULL,1,'created','Created department General Surgery','App\\Models\\Department',4,'127.0.0.1','2026-02-06 00:35:12'),(15,NULL,1,'created','Created department Internal Medicine','App\\Models\\Department',5,'127.0.0.1','2026-02-06 00:35:12'),(16,NULL,1,'created','Created department Neurology','App\\Models\\Department',6,'127.0.0.1','2026-02-06 00:35:12'),(17,NULL,1,'created','Created department Gynecology','App\\Models\\Department',7,'127.0.0.1','2026-02-06 00:35:12'),(18,NULL,1,'created','Created department Dermatology','App\\Models\\Department',8,'127.0.0.1','2026-02-06 00:35:12'),(19,NULL,1,'created','Created Ward','App\\Models\\Ward',1,'127.0.0.1','2026-02-06 00:35:12'),(20,NULL,1,'created','Created Room','App\\Models\\Room',1,'127.0.0.1','2026-02-06 00:35:12'),(21,NULL,1,'created','Created bed GEN-201-A in room GEN-201','App\\Models\\Bed',1,'127.0.0.1','2026-02-06 00:35:12'),(22,NULL,1,'created','Created bed GEN-201-B in room GEN-201','App\\Models\\Bed',2,'127.0.0.1','2026-02-06 00:35:12'),(23,NULL,1,'created','Created Room','App\\Models\\Room',2,'127.0.0.1','2026-02-06 00:35:12'),(24,NULL,1,'created','Created bed GEN-202-A in room GEN-202','App\\Models\\Bed',3,'127.0.0.1','2026-02-06 00:35:12'),(25,NULL,1,'created','Created bed GEN-202-B in room GEN-202','App\\Models\\Bed',4,'127.0.0.1','2026-02-06 00:35:12'),(26,NULL,1,'created','Created Ward','App\\Models\\Ward',2,'127.0.0.1','2026-02-06 00:35:12'),(27,NULL,1,'created','Created Room','App\\Models\\Room',3,'127.0.0.1','2026-02-06 00:35:12'),(28,NULL,1,'created','Created bed GEN-201-A in room GEN-201','App\\Models\\Bed',5,'127.0.0.1','2026-02-06 00:35:12'),(29,NULL,1,'created','Created bed GEN-201-B in room GEN-201','App\\Models\\Bed',6,'127.0.0.1','2026-02-06 00:35:12'),(30,NULL,1,'created','Created Room','App\\Models\\Room',4,'127.0.0.1','2026-02-06 00:35:12'),(31,NULL,1,'created','Created bed GEN-202-A in room GEN-202','App\\Models\\Bed',7,'127.0.0.1','2026-02-06 00:35:12'),(32,NULL,1,'created','Created bed GEN-202-B in room GEN-202','App\\Models\\Bed',8,'127.0.0.1','2026-02-06 00:35:12'),(33,NULL,1,'created','Created Ward','App\\Models\\Ward',3,'127.0.0.1','2026-02-06 00:35:12'),(34,NULL,1,'created','Created Room','App\\Models\\Room',5,'127.0.0.1','2026-02-06 00:35:12'),(35,NULL,1,'created','Created bed ICU-301-A in room ICU-301','App\\Models\\Bed',9,'127.0.0.1','2026-02-06 00:35:12'),(36,NULL,1,'created','Created bed ICU-301-B in room ICU-301','App\\Models\\Bed',10,'127.0.0.1','2026-02-06 00:35:12'),(37,NULL,1,'created','Created Room','App\\Models\\Room',6,'127.0.0.1','2026-02-06 00:35:12'),(38,NULL,1,'created','Created bed ICU-302-A in room ICU-302','App\\Models\\Bed',11,'127.0.0.1','2026-02-06 00:35:12'),(39,NULL,1,'created','Created bed ICU-302-B in room ICU-302','App\\Models\\Bed',12,'127.0.0.1','2026-02-06 00:35:12'),(40,NULL,1,'created','Created Ward','App\\Models\\Ward',4,'127.0.0.1','2026-02-06 00:35:12'),(41,NULL,1,'created','Created Room','App\\Models\\Room',7,'127.0.0.1','2026-02-06 00:35:12'),(42,NULL,1,'created','Created bed VIP-401-A in room VIP-401','App\\Models\\Bed',13,'127.0.0.1','2026-02-06 00:35:12'),(43,NULL,1,'created','Created bed VIP-401-B in room VIP-401','App\\Models\\Bed',14,'127.0.0.1','2026-02-06 00:35:12'),(44,NULL,1,'created','Created Room','App\\Models\\Room',8,'127.0.0.1','2026-02-06 00:35:12'),(45,NULL,1,'created','Created bed VIP-402-A in room VIP-402','App\\Models\\Bed',15,'127.0.0.1','2026-02-06 00:35:12'),(46,NULL,1,'created','Created bed VIP-402-B in room VIP-402','App\\Models\\Bed',16,'127.0.0.1','2026-02-06 00:35:12'),(47,NULL,NULL,'created','Created medicine Napa Extra (Paracetamol)','App\\Models\\Medicine',1,'127.0.0.1','2026-02-06 00:35:12'),(48,NULL,NULL,'created','Created medicine Seclo (Omeprazole)','App\\Models\\Medicine',2,'127.0.0.1','2026-02-06 00:35:12'),(49,NULL,NULL,'created','Created medicine Maxpro (Esomeprazole)','App\\Models\\Medicine',3,'127.0.0.1','2026-02-06 00:35:12'),(50,NULL,NULL,'created','Created medicine Cef-3 (Cefixime)','App\\Models\\Medicine',4,'127.0.0.1','2026-02-06 00:35:12'),(51,NULL,NULL,'created','Created medicine Tylace (Aceclofenac)','App\\Models\\Medicine',5,'127.0.0.1','2026-02-06 00:35:12'),(52,NULL,NULL,'created','Created medicine Alarid (Cetirizine)','App\\Models\\Medicine',6,'127.0.0.1','2026-02-06 00:35:12'),(53,NULL,NULL,'created','Created medicine Flagyl (Metronidazole)','App\\Models\\Medicine',7,'127.0.0.1','2026-02-06 00:35:12'),(54,NULL,NULL,'created','Created medicine Azithrocin (Azithromycin)','App\\Models\\Medicine',8,'127.0.0.1','2026-02-06 00:35:12'),(55,NULL,NULL,'created','Created medicine Monas (Montelukast)','App\\Models\\Medicine',9,'127.0.0.1','2026-02-06 00:35:12'),(56,NULL,NULL,'created','Created medicine Panthonix (Pantoprazole)','App\\Models\\Medicine',10,'127.0.0.1','2026-02-06 00:35:12'),(57,NULL,NULL,'created','Created lab test CBC (Complete Blood Count)','App\\Models\\LabTest',1,'127.0.0.1','2026-02-06 00:35:12'),(58,NULL,NULL,'created','Created lab test Lipid Profile','App\\Models\\LabTest',2,'127.0.0.1','2026-02-06 00:35:12'),(59,NULL,NULL,'created','Created lab test Liver Function Test','App\\Models\\LabTest',3,'127.0.0.1','2026-02-06 00:35:12'),(60,NULL,NULL,'created','Created lab test Kidney Function Test','App\\Models\\LabTest',4,'127.0.0.1','2026-02-06 00:35:12'),(61,NULL,NULL,'created','Created lab test Blood Sugar (Fasting)','App\\Models\\LabTest',5,'127.0.0.1','2026-02-06 00:35:12'),(62,NULL,NULL,'created','Created lab test Blood Sugar (2hPP)','App\\Models\\LabTest',6,'127.0.0.1','2026-02-06 00:35:12'),(63,NULL,NULL,'created','Created lab test X-Ray Chest PA View','App\\Models\\LabTest',7,'127.0.0.1','2026-02-06 00:35:12'),(64,NULL,NULL,'created','Created lab test ECG','App\\Models\\LabTest',8,'127.0.0.1','2026-02-06 00:35:12'),(65,NULL,NULL,'created','Created lab test Urine R/E','App\\Models\\LabTest',9,'127.0.0.1','2026-02-06 00:35:12'),(66,NULL,NULL,'created','Created lab test USG Whole Abdomen','App\\Models\\LabTest',10,'127.0.0.1','2026-02-06 00:35:12'),(67,NULL,1,'created','Created user Super Admin (No Role)','App\\Models\\User',1,'127.0.0.1','2026-02-06 00:35:13'),(68,NULL,1,'created','Created user Clinic Admin (No Role)','App\\Models\\User',2,'127.0.0.1','2026-02-06 00:35:13'),(69,NULL,1,'created','Created user Default Doctor (No Role)','App\\Models\\User',3,'127.0.0.1','2026-02-06 00:35:14'),(70,NULL,1,'created','Created doctor profile for Default Doctor','App\\Models\\Doctor',1,'127.0.0.1','2026-02-06 00:35:14'),(71,NULL,1,'created','Created user Default Nurse (No Role)','App\\Models\\User',4,'127.0.0.1','2026-02-06 00:35:14'),(72,NULL,1,'created','Created user Default Receptionist (No Role)','App\\Models\\User',5,'127.0.0.1','2026-02-06 00:35:15'),(73,NULL,1,'created','Created user Default Lab Technician (No Role)','App\\Models\\User',6,'127.0.0.1','2026-02-06 00:35:15'),(74,NULL,1,'created','Created user Default Pharmacist (No Role)','App\\Models\\User',7,'127.0.0.1','2026-02-06 00:35:15'),(75,NULL,1,'created','Created user Default Accountant (No Role)','App\\Models\\User',8,'127.0.0.1','2026-02-06 00:35:16'),(76,NULL,1,'created','Created patient Mr. Patient','App\\Models\\Patient',1,'127.0.0.1','2026-02-06 00:35:16');
/*!40000 ALTER TABLE `lara_activity_logs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `lara_admission_deposits`
--

DROP TABLE IF EXISTS `lara_admission_deposits`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
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
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `lara_admission_deposits`
--

LOCK TABLES `lara_admission_deposits` WRITE;
/*!40000 ALTER TABLE `lara_admission_deposits` DISABLE KEYS */;
/*!40000 ALTER TABLE `lara_admission_deposits` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `lara_admissions`
--

DROP TABLE IF EXISTS `lara_admissions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
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
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `lara_admissions`
--

LOCK TABLES `lara_admissions` WRITE;
/*!40000 ALTER TABLE `lara_admissions` DISABLE KEYS */;
/*!40000 ALTER TABLE `lara_admissions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `lara_appointment_requests`
--

DROP TABLE IF EXISTS `lara_appointment_requests`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
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
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `lara_appointment_requests`
--

LOCK TABLES `lara_appointment_requests` WRITE;
/*!40000 ALTER TABLE `lara_appointment_requests` DISABLE KEYS */;
/*!40000 ALTER TABLE `lara_appointment_requests` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `lara_appointment_status_logs`
--

DROP TABLE IF EXISTS `lara_appointment_status_logs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
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
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `lara_appointment_status_logs`
--

LOCK TABLES `lara_appointment_status_logs` WRITE;
/*!40000 ALTER TABLE `lara_appointment_status_logs` DISABLE KEYS */;
/*!40000 ALTER TABLE `lara_appointment_status_logs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `lara_appointments`
--

DROP TABLE IF EXISTS `lara_appointments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
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
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `lara_appointments`
--

LOCK TABLES `lara_appointments` WRITE;
/*!40000 ALTER TABLE `lara_appointments` DISABLE KEYS */;
/*!40000 ALTER TABLE `lara_appointments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `lara_bed_assignments`
--

DROP TABLE IF EXISTS `lara_bed_assignments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
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
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `lara_bed_assignments`
--

LOCK TABLES `lara_bed_assignments` WRITE;
/*!40000 ALTER TABLE `lara_bed_assignments` DISABLE KEYS */;
/*!40000 ALTER TABLE `lara_bed_assignments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `lara_beds`
--

DROP TABLE IF EXISTS `lara_beds`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
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
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `lara_beds`
--

LOCK TABLES `lara_beds` WRITE;
/*!40000 ALTER TABLE `lara_beds` DISABLE KEYS */;
INSERT INTO `lara_beds` VALUES (1,1,1,'GEN-201-A','available',0,'2026-02-06 00:35:12','2026-02-06 00:35:12'),(2,1,1,'GEN-201-B','available',0,'2026-02-06 00:35:12','2026-02-06 00:35:12'),(3,2,1,'GEN-202-A','available',0,'2026-02-06 00:35:12','2026-02-06 00:35:12'),(4,2,1,'GEN-202-B','available',0,'2026-02-06 00:35:12','2026-02-06 00:35:12'),(5,3,1,'GEN-201-A','available',0,'2026-02-06 00:35:12','2026-02-06 00:35:12'),(6,3,1,'GEN-201-B','available',0,'2026-02-06 00:35:12','2026-02-06 00:35:12'),(7,4,1,'GEN-202-A','available',0,'2026-02-06 00:35:12','2026-02-06 00:35:12'),(8,4,1,'GEN-202-B','available',0,'2026-02-06 00:35:12','2026-02-06 00:35:12'),(9,5,1,'ICU-301-A','available',0,'2026-02-06 00:35:12','2026-02-06 00:35:12'),(10,5,1,'ICU-301-B','available',0,'2026-02-06 00:35:12','2026-02-06 00:35:12'),(11,6,1,'ICU-302-A','available',0,'2026-02-06 00:35:12','2026-02-06 00:35:12'),(12,6,1,'ICU-302-B','available',0,'2026-02-06 00:35:12','2026-02-06 00:35:12'),(13,7,1,'VIP-401-A','available',0,'2026-02-06 00:35:12','2026-02-06 00:35:12'),(14,7,1,'VIP-401-B','available',0,'2026-02-06 00:35:12','2026-02-06 00:35:12'),(15,8,1,'VIP-402-A','available',0,'2026-02-06 00:35:12','2026-02-06 00:35:12'),(16,8,1,'VIP-402-B','available',0,'2026-02-06 00:35:12','2026-02-06 00:35:12');
/*!40000 ALTER TABLE `lara_beds` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `lara_cache`
--

DROP TABLE IF EXISTS `lara_cache`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `lara_cache` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `lara_cache`
--

LOCK TABLES `lara_cache` WRITE;
/*!40000 ALTER TABLE `lara_cache` DISABLE KEYS */;
/*!40000 ALTER TABLE `lara_cache` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `lara_cache_locks`
--

DROP TABLE IF EXISTS `lara_cache_locks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `lara_cache_locks` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `lara_cache_locks`
--

LOCK TABLES `lara_cache_locks` WRITE;
/*!40000 ALTER TABLE `lara_cache_locks` DISABLE KEYS */;
/*!40000 ALTER TABLE `lara_cache_locks` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `lara_clinic_images`
--

DROP TABLE IF EXISTS `lara_clinic_images`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
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
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `lara_clinic_images`
--

LOCK TABLES `lara_clinic_images` WRITE;
/*!40000 ALTER TABLE `lara_clinic_images` DISABLE KEYS */;
/*!40000 ALTER TABLE `lara_clinic_images` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `lara_clinic_patient`
--

DROP TABLE IF EXISTS `lara_clinic_patient`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
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
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `lara_clinic_patient`
--

LOCK TABLES `lara_clinic_patient` WRITE;
/*!40000 ALTER TABLE `lara_clinic_patient` DISABLE KEYS */;
/*!40000 ALTER TABLE `lara_clinic_patient` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `lara_clinics`
--

DROP TABLE IF EXISTS `lara_clinics`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
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
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `lara_clinics`
--

LOCK TABLES `lara_clinics` WRITE;
/*!40000 ALTER TABLE `lara_clinics` DISABLE KEYS */;
INSERT INTO `lara_clinics` VALUES (1,'Dhaka Medical Center','DMC-001',NULL,'123 Hospital Road',NULL,'Dhaka',NULL,'Bangladesh',NULL,NULL,NULL,NULL,NULL,NULL,NULL,'Asia/Dhaka','BDT',NULL,NULL,'active','2026-02-06 00:35:12','2026-02-06 00:35:12',NULL);
/*!40000 ALTER TABLE `lara_clinics` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `lara_consultations`
--

DROP TABLE IF EXISTS `lara_consultations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
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
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `lara_consultations`
--

LOCK TABLES `lara_consultations` WRITE;
/*!40000 ALTER TABLE `lara_consultations` DISABLE KEYS */;
/*!40000 ALTER TABLE `lara_consultations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `lara_departments`
--

DROP TABLE IF EXISTS `lara_departments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
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
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `lara_departments`
--

LOCK TABLES `lara_departments` WRITE;
/*!40000 ALTER TABLE `lara_departments` DISABLE KEYS */;
INSERT INTO `lara_departments` VALUES (1,1,'Cardiology','Heart and cardiovascular system',NULL,NULL,'active','2026-02-06 00:35:12','2026-02-06 00:35:12',NULL),(2,1,'Orthopedics','Bones, joints, ligaments, tendons, and muscles',NULL,NULL,'active','2026-02-06 00:35:12','2026-02-06 00:35:12',NULL),(3,1,'Pediatrics','Medical care of infants, children, and adolescents',NULL,NULL,'active','2026-02-06 00:35:12','2026-02-06 00:35:12',NULL),(4,1,'General Surgery','Surgical treatment of abdominal contents',NULL,NULL,'active','2026-02-06 00:35:12','2026-02-06 00:35:12',NULL),(5,1,'Internal Medicine','Prevention, diagnosis, and treatment of adult diseases',NULL,NULL,'active','2026-02-06 00:35:12','2026-02-06 00:35:12',NULL),(6,1,'Neurology','Disorders of the nervous system',NULL,NULL,'active','2026-02-06 00:35:12','2026-02-06 00:35:12',NULL),(7,1,'Gynecology','Female reproductive system',NULL,NULL,'active','2026-02-06 00:35:12','2026-02-06 00:35:12',NULL),(8,1,'Dermatology','Skin, hair, and nail conditions',NULL,NULL,'active','2026-02-06 00:35:12','2026-02-06 00:35:12',NULL);
/*!40000 ALTER TABLE `lara_departments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `lara_doctor_awards`
--

DROP TABLE IF EXISTS `lara_doctor_awards`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
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
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `lara_doctor_awards`
--

LOCK TABLES `lara_doctor_awards` WRITE;
/*!40000 ALTER TABLE `lara_doctor_awards` DISABLE KEYS */;
/*!40000 ALTER TABLE `lara_doctor_awards` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `lara_doctor_certifications`
--

DROP TABLE IF EXISTS `lara_doctor_certifications`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
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
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `lara_doctor_certifications`
--

LOCK TABLES `lara_doctor_certifications` WRITE;
/*!40000 ALTER TABLE `lara_doctor_certifications` DISABLE KEYS */;
/*!40000 ALTER TABLE `lara_doctor_certifications` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `lara_doctor_clinic`
--

DROP TABLE IF EXISTS `lara_doctor_clinic`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
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
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `lara_doctor_clinic`
--

LOCK TABLES `lara_doctor_clinic` WRITE;
/*!40000 ALTER TABLE `lara_doctor_clinic` DISABLE KEYS */;
INSERT INTO `lara_doctor_clinic` VALUES (1,1,1,NULL,1,NULL,'active',NULL,NULL);
/*!40000 ALTER TABLE `lara_doctor_clinic` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `lara_doctor_education`
--

DROP TABLE IF EXISTS `lara_doctor_education`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
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
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `lara_doctor_education`
--

LOCK TABLES `lara_doctor_education` WRITE;
/*!40000 ALTER TABLE `lara_doctor_education` DISABLE KEYS */;
/*!40000 ALTER TABLE `lara_doctor_education` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `lara_doctor_schedule_exceptions`
--

DROP TABLE IF EXISTS `lara_doctor_schedule_exceptions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
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
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `lara_doctor_schedule_exceptions`
--

LOCK TABLES `lara_doctor_schedule_exceptions` WRITE;
/*!40000 ALTER TABLE `lara_doctor_schedule_exceptions` DISABLE KEYS */;
/*!40000 ALTER TABLE `lara_doctor_schedule_exceptions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `lara_doctor_schedule_requests`
--

DROP TABLE IF EXISTS `lara_doctor_schedule_requests`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
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
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `lara_doctor_schedule_requests`
--

LOCK TABLES `lara_doctor_schedule_requests` WRITE;
/*!40000 ALTER TABLE `lara_doctor_schedule_requests` DISABLE KEYS */;
/*!40000 ALTER TABLE `lara_doctor_schedule_requests` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `lara_doctor_schedules`
--

DROP TABLE IF EXISTS `lara_doctor_schedules`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
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
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `lara_doctor_schedules`
--

LOCK TABLES `lara_doctor_schedules` WRITE;
/*!40000 ALTER TABLE `lara_doctor_schedules` DISABLE KEYS */;
/*!40000 ALTER TABLE `lara_doctor_schedules` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `lara_doctors`
--

DROP TABLE IF EXISTS `lara_doctors`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `lara_doctors` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `clinic_id` bigint unsigned NOT NULL,
  `primary_department_id` bigint unsigned NOT NULL,
  `registration_number` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `license_number` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `specialization` json NOT NULL,
  `experience_years` int unsigned NOT NULL DEFAULT '0',
  `gender` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `blood_group` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `date_of_birth` date DEFAULT NULL,
  `consultation_fee` decimal(10,2) DEFAULT NULL,
  `follow_up_fee` decimal(10,2) DEFAULT NULL,
  `location` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `biography` text COLLATE utf8mb4_unicode_ci,
  `profile_photo` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `consultation_room_number` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `consultation_floor` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_featured` tinyint(1) NOT NULL DEFAULT '0',
  `status` enum('active','inactive') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `lara_doctors_license_number_unique` (`license_number`),
  UNIQUE KEY `lara_doctors_registration_number_unique` (`registration_number`),
  KEY `lara_doctors_user_id_foreign` (`user_id`),
  KEY `lara_doctors_clinic_id_foreign` (`clinic_id`),
  KEY `lara_doctors_primary_department_id_foreign` (`primary_department_id`),
  CONSTRAINT `lara_doctors_clinic_id_foreign` FOREIGN KEY (`clinic_id`) REFERENCES `lara_clinics` (`id`) ON DELETE RESTRICT,
  CONSTRAINT `lara_doctors_primary_department_id_foreign` FOREIGN KEY (`primary_department_id`) REFERENCES `lara_departments` (`id`) ON DELETE RESTRICT,
  CONSTRAINT `lara_doctors_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `lara_users` (`id`) ON DELETE RESTRICT
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `lara_doctors`
--

LOCK TABLES `lara_doctors` WRITE;
/*!40000 ALTER TABLE `lara_doctors` DISABLE KEYS */;
INSERT INTO `lara_doctors` VALUES (1,3,1,4,NULL,'BMDC-90801','[\"General Physician\"]',10,NULL,NULL,NULL,1000.00,NULL,NULL,NULL,NULL,NULL,NULL,0,'active','2026-02-06 00:35:14','2026-02-06 00:35:14',NULL);
/*!40000 ALTER TABLE `lara_doctors` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `lara_failed_jobs`
--

DROP TABLE IF EXISTS `lara_failed_jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
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
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `lara_failed_jobs`
--

LOCK TABLES `lara_failed_jobs` WRITE;
/*!40000 ALTER TABLE `lara_failed_jobs` DISABLE KEYS */;
/*!40000 ALTER TABLE `lara_failed_jobs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `lara_inpatient_rounds`
--

DROP TABLE IF EXISTS `lara_inpatient_rounds`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
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
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `lara_inpatient_rounds`
--

LOCK TABLES `lara_inpatient_rounds` WRITE;
/*!40000 ALTER TABLE `lara_inpatient_rounds` DISABLE KEYS */;
/*!40000 ALTER TABLE `lara_inpatient_rounds` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `lara_inpatient_services`
--

DROP TABLE IF EXISTS `lara_inpatient_services`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
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
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `lara_inpatient_services`
--

LOCK TABLES `lara_inpatient_services` WRITE;
/*!40000 ALTER TABLE `lara_inpatient_services` DISABLE KEYS */;
/*!40000 ALTER TABLE `lara_inpatient_services` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `lara_invoice_items`
--

DROP TABLE IF EXISTS `lara_invoice_items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
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
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `lara_invoice_items`
--

LOCK TABLES `lara_invoice_items` WRITE;
/*!40000 ALTER TABLE `lara_invoice_items` DISABLE KEYS */;
/*!40000 ALTER TABLE `lara_invoice_items` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `lara_invoices`
--

DROP TABLE IF EXISTS `lara_invoices`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
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
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `lara_invoices`
--

LOCK TABLES `lara_invoices` WRITE;
/*!40000 ALTER TABLE `lara_invoices` DISABLE KEYS */;
/*!40000 ALTER TABLE `lara_invoices` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `lara_job_batches`
--

DROP TABLE IF EXISTS `lara_job_batches`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
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
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `lara_job_batches`
--

LOCK TABLES `lara_job_batches` WRITE;
/*!40000 ALTER TABLE `lara_job_batches` DISABLE KEYS */;
/*!40000 ALTER TABLE `lara_job_batches` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `lara_jobs`
--

DROP TABLE IF EXISTS `lara_jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
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
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `lara_jobs`
--

LOCK TABLES `lara_jobs` WRITE;
/*!40000 ALTER TABLE `lara_jobs` DISABLE KEYS */;
/*!40000 ALTER TABLE `lara_jobs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `lara_lab_test_orders`
--

DROP TABLE IF EXISTS `lara_lab_test_orders`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
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
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `lara_lab_test_orders`
--

LOCK TABLES `lara_lab_test_orders` WRITE;
/*!40000 ALTER TABLE `lara_lab_test_orders` DISABLE KEYS */;
/*!40000 ALTER TABLE `lara_lab_test_orders` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `lara_lab_test_results`
--

DROP TABLE IF EXISTS `lara_lab_test_results`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
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
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `lara_lab_test_results`
--

LOCK TABLES `lara_lab_test_results` WRITE;
/*!40000 ALTER TABLE `lara_lab_test_results` DISABLE KEYS */;
/*!40000 ALTER TABLE `lara_lab_test_results` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `lara_lab_tests`
--

DROP TABLE IF EXISTS `lara_lab_tests`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
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
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `lara_lab_tests`
--

LOCK TABLES `lara_lab_tests` WRITE;
/*!40000 ALTER TABLE `lara_lab_tests` DISABLE KEYS */;
INSERT INTO `lara_lab_tests` VALUES (1,'CBC (Complete Blood Count)','Hematology','CBC (Complete Blood Count)',NULL,400.00,'active',NULL,NULL),(2,'Lipid Profile','Biochemistry','Lipid Profile',NULL,1200.00,'active',NULL,NULL),(3,'Liver Function Test','Biochemistry','Liver Function Test',NULL,1000.00,'active',NULL,NULL),(4,'Kidney Function Test','Biochemistry','Kidney Function Test',NULL,1000.00,'active',NULL,NULL),(5,'Blood Sugar (Fasting)','Biochemistry','Blood Sugar (Fasting)',NULL,150.00,'active',NULL,NULL),(6,'Blood Sugar (2hPP)','Biochemistry','Blood Sugar (2hPP)',NULL,150.00,'active',NULL,NULL),(7,'X-Ray Chest PA View','Radiology','X-Ray Chest PA View',NULL,600.00,'active',NULL,NULL),(8,'ECG','Cardiology','ECG',NULL,500.00,'active',NULL,NULL),(9,'Urine R/E','Pathology','Urine R/E',NULL,250.00,'active',NULL,NULL),(10,'USG Whole Abdomen','Radiology','USG Whole Abdomen',NULL,1500.00,'active',NULL,NULL);
/*!40000 ALTER TABLE `lara_lab_tests` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `lara_medicine_batches`
--

DROP TABLE IF EXISTS `lara_medicine_batches`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
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
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `lara_medicine_batches`
--

LOCK TABLES `lara_medicine_batches` WRITE;
/*!40000 ALTER TABLE `lara_medicine_batches` DISABLE KEYS */;
/*!40000 ALTER TABLE `lara_medicine_batches` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `lara_medicines`
--

DROP TABLE IF EXISTS `lara_medicines`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
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
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `lara_medicines`
--

LOCK TABLES `lara_medicines` WRITE;
/*!40000 ALTER TABLE `lara_medicines` DISABLE KEYS */;
INSERT INTO `lara_medicines` VALUES (1,'Napa Extra','Paracetamol','Beximco/Square','500mg','tablet',2.50,'active',NULL,NULL),(2,'Seclo','Omeprazole','Beximco/Square','20mg','capsule',5.00,'active',NULL,NULL),(3,'Maxpro','Esomeprazole','Beximco/Square','20mg','capsule',7.00,'active',NULL,NULL),(4,'Cef-3','Cefixime','Beximco/Square','200mg','capsule',35.00,'active',NULL,NULL),(5,'Tylace','Aceclofenac','Beximco/Square','100mg','tablet',4.00,'active',NULL,NULL),(6,'Alarid','Cetirizine','Beximco/Square','10mg','tablet',3.00,'active',NULL,NULL),(7,'Flagyl','Metronidazole','Beximco/Square','400mg','tablet',2.00,'active',NULL,NULL),(8,'Azithrocin','Azithromycin','Beximco/Square','500mg','tablet',30.00,'active',NULL,NULL),(9,'Monas','Montelukast','Beximco/Square','10mg','tablet',15.00,'active',NULL,NULL),(10,'Panthonix','Pantoprazole','Beximco/Square','20mg','tablet',6.00,'active',NULL,NULL);
/*!40000 ALTER TABLE `lara_medicines` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `lara_migrations`
--

DROP TABLE IF EXISTS `lara_migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `lara_migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=98 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `lara_migrations`
--

LOCK TABLES `lara_migrations` WRITE;
/*!40000 ALTER TABLE `lara_migrations` DISABLE KEYS */;
INSERT INTO `lara_migrations` VALUES (1,'0001_01_01_000001_create_cache_table',1),(2,'0001_01_01_000002_create_jobs_table',1),(3,'2025_12_30_145205_create_clinics_table',1),(4,'2025_12_30_145318_create_departments_table',1),(5,'2025_12_30_145430_create_users_table',1),(6,'2025_12_30_151045_create_roles_table',1),(7,'2025_12_30_151120_create_permissions_table',1),(8,'2025_12_30_151246_create_role_permission_table',1),(9,'2025_12_30_151416_create_user_role_table',1),(10,'2025_12_30_151814_create_doctors_table',1),(11,'2025_12_30_152722_create_doctor_clinic_table',1),(12,'2025_12_30_152757_create_doctor_education_table',1),(13,'2025_12_30_152859_create_doctor_certifications_table',1),(14,'2025_12_30_153000_create_doctor_awards_table',1),(15,'2025_12_30_153133_create_doctor_schedules_table',1),(16,'2025_12_30_153510_create_doctor_schedule_exceptions_table',1),(17,'2025_12_30_154138_create_patients_table',1),(18,'2025_12_30_154237_create_patient_allergies_table',1),(19,'2025_12_30_154327_create_patient_medical_history_table',1),(20,'2025_12_30_154457_create_appointments_table',1),(21,'2025_12_30_165417_create_appointment_status_logs_table',1),(22,'2025_12_30_165541_create_visits_table',1),(23,'2025_12_30_165632_create_vitals_table',1),(24,'2025_12_30_165703_create_consultations_table',1),(25,'2025_12_30_165737_create_prescriptions_table',1),(26,'2025_12_30_170043_create_lab_tests_table',1),(27,'2025_12_30_170118_create_lab_test_orders_table',1),(28,'2025_12_30_170142_create_lab_test_results_table',1),(29,'2025_12_30_170213_create_medicines_table',1),(30,'2025_12_30_170250_create_medicine_batches_table',1),(31,'2025_12_30_170331_create_pharmacy_sales_table',1),(32,'2025_12_30_170401_create_pharmacy_sale_items_table',1),(33,'2025_12_30_172133_create_prescription_items_table',1),(34,'2025_12_30_172334_create_invoices_table',1),(35,'2025_12_30_172425_create_invoice_items_table',1),(36,'2025_12_30_172454_create_payments_table',1),(37,'2026_01_01_064510_create_wards_table',1),(38,'2026_01_01_064842_create_rooms_table',1),(39,'2026_01_01_064915_create_beds_table',1),(40,'2026_01_01_065247_create_admissions_table',1),(41,'2026_01_01_065322_create_bed_assignments_table',1),(42,'2026_01_01_065442_create_inpatient_rounds_table',1),(43,'2026_01_01_065514_create_nursing_notes_table',1),(44,'2026_01_01_065549_create_inpatient_services_table',1),(45,'2026_01_01_065711_create_activity_logs_table',1),(46,'2026_01_01_065808_create_notifications_table',1),(47,'2026_01_03_141446_add_soft_deletes_to_transactional_tables',1),(48,'2026_01_03_142745_add_clinic_id_to_medicine_batches_table',1),(49,'2026_01_03_144437_add_remember_token_to_users_table',1),(50,'2026_01_03_145733_add_clinic_id_to_transactional_tables',1),(51,'2026_01_04_033811_add_missing_columns_to_tables',1),(52,'2026_01_05_100000_grant_clinic_admin_permissions',1),(53,'2026_01_05_120000_add_clinic_id_to_missing_tables',1),(54,'2026_01_08_120000_add_consultation_id_to_visits_table',1),(55,'2026_01_08_121000_add_doctor_patient_to_consultations_table',1),(56,'2026_01_08_122000_create_patient_complaints_tables',1),(57,'2026_01_10_180355_add_column_timstamps_onconsultations_table',1),(58,'2026_01_10_180855_add_column_timstamps_onpatient_complaints_table',1),(59,'2026_01_10_191034_add_status_to_consultations_table',1),(60,'2026_01_11_133158_add_details_to_doctor_schedule_exceptions_table',1),(61,'2026_01_12_000001_add_pdf_path_to_lab_test_results',1),(62,'2026_01_12_050703_enhance_doctor_scheduling_tables',1),(63,'2026_01_16_021606_add_admission_id_to_invoices_table',1),(64,'2026_01_16_021739_add_clinic_id_to_ipd_tables',1),(65,'2026_01_16_063433_add_deleted_at_to_consultations_table',1),(66,'2026_01_16_070000_add_deleted_at_to_departments_table',1),(67,'2026_01_16_090500_add_clinic_id_to_rooms_table',1),(68,'2026_01_16_100000_add_ipd_columns_to_patient_vitals_table',1),(69,'2026_01_16_120000_add_soft_deletes_to_users_table',1),(70,'2026_01_16_120000_update_invoices_with_visit_and_states',1),(71,'2026_01_16_121000_create_admission_deposits_table',1),(72,'2026_01_18_000000_create_doctor_schedule_requests_table',1),(73,'2026_01_18_000001_add_position_to_beds_table',1),(74,'2026_01_18_000500_add_symptoms_to_consultations_table',1),(75,'2026_01_19_063845_add_profile_photo_to_patients_table',1),(76,'2026_01_19_111722_add_discharge_fields_to_admissions_table',1),(77,'2026_01_19_120000_fix_notifications_table',1),(78,'2026_01_19_125818_add_profile_photo_path_to_users_table',1),(79,'2026_01_20_000000_fix_lab_test_orders_schema',1),(80,'2026_01_20_000001_add_invoice_id_to_lab_test_orders',1),(81,'2026_01_24_043010_create_personal_access_tokens_table',1),(82,'2026_01_24_113008_create_clinic_images_table',1),(83,'2026_01_24_122712_add_deleted_at_to_clinics_table',1),(84,'2026_01_25_000002_add_about_services_to_clinics_table',1),(85,'2026_01_25_120000_add_identification_to_patients_table',1),(86,'2026_01_25_152307_add_unique_constraints_to_doctors_and_patients',1),(87,'2026_01_25_153350_add_unique_constraint_to_clinic_registration_number',1),(88,'2026_01_26_000100_add_auth_fields_to_patients_table',1),(89,'2026_01_26_120901_modify_patient_unique_constraints_to_be_clinic_scoped',1),(90,'2026_01_28_050000_refactor_patients_to_global_scope',1),(91,'2026_01_29_064443_create_appointment_requests_table',1),(92,'2026_01_29_140632_create_patient_surgeries_table',1),(93,'2026_01_29_140642_create_patient_immunizations_table',1),(94,'2026_01_29_144502_add_doctor_name_to_patient_medical_history_table',1),(95,'2026_01_30_050017_add_description_to_activity_logs_table',1),(96,'2026_02_05_161125_add_unit_cost_to_pharmacy_sale_items_table',1),(97,'2026_02_06_032431_add_consultation_details_to_doctors_table',1);
/*!40000 ALTER TABLE `lara_migrations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `lara_notifications`
--

DROP TABLE IF EXISTS `lara_notifications`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
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
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `lara_notifications`
--

LOCK TABLES `lara_notifications` WRITE;
/*!40000 ALTER TABLE `lara_notifications` DISABLE KEYS */;
/*!40000 ALTER TABLE `lara_notifications` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `lara_nursing_notes`
--

DROP TABLE IF EXISTS `lara_nursing_notes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
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
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `lara_nursing_notes`
--

LOCK TABLES `lara_nursing_notes` WRITE;
/*!40000 ALTER TABLE `lara_nursing_notes` DISABLE KEYS */;
/*!40000 ALTER TABLE `lara_nursing_notes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `lara_password_reset_tokens`
--

DROP TABLE IF EXISTS `lara_password_reset_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `lara_password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `lara_password_reset_tokens`
--

LOCK TABLES `lara_password_reset_tokens` WRITE;
/*!40000 ALTER TABLE `lara_password_reset_tokens` DISABLE KEYS */;
/*!40000 ALTER TABLE `lara_password_reset_tokens` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `lara_patient_allergies`
--

DROP TABLE IF EXISTS `lara_patient_allergies`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
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
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `lara_patient_allergies`
--

LOCK TABLES `lara_patient_allergies` WRITE;
/*!40000 ALTER TABLE `lara_patient_allergies` DISABLE KEYS */;
/*!40000 ALTER TABLE `lara_patient_allergies` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `lara_patient_complaints`
--

DROP TABLE IF EXISTS `lara_patient_complaints`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
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
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `lara_patient_complaints`
--

LOCK TABLES `lara_patient_complaints` WRITE;
/*!40000 ALTER TABLE `lara_patient_complaints` DISABLE KEYS */;
/*!40000 ALTER TABLE `lara_patient_complaints` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `lara_patient_immunizations`
--

DROP TABLE IF EXISTS `lara_patient_immunizations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
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
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `lara_patient_immunizations`
--

LOCK TABLES `lara_patient_immunizations` WRITE;
/*!40000 ALTER TABLE `lara_patient_immunizations` DISABLE KEYS */;
/*!40000 ALTER TABLE `lara_patient_immunizations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `lara_patient_medical_history`
--

DROP TABLE IF EXISTS `lara_patient_medical_history`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
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
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `lara_patient_medical_history`
--

LOCK TABLES `lara_patient_medical_history` WRITE;
/*!40000 ALTER TABLE `lara_patient_medical_history` DISABLE KEYS */;
/*!40000 ALTER TABLE `lara_patient_medical_history` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `lara_patient_surgeries`
--

DROP TABLE IF EXISTS `lara_patient_surgeries`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
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
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `lara_patient_surgeries`
--

LOCK TABLES `lara_patient_surgeries` WRITE;
/*!40000 ALTER TABLE `lara_patient_surgeries` DISABLE KEYS */;
/*!40000 ALTER TABLE `lara_patient_surgeries` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `lara_patient_vitals`
--

DROP TABLE IF EXISTS `lara_patient_vitals`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
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
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `lara_patient_vitals`
--

LOCK TABLES `lara_patient_vitals` WRITE;
/*!40000 ALTER TABLE `lara_patient_vitals` DISABLE KEYS */;
/*!40000 ALTER TABLE `lara_patient_vitals` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `lara_patients`
--

DROP TABLE IF EXISTS `lara_patients`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
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
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `lara_patients`
--

LOCK TABLES `lara_patients` WRITE;
/*!40000 ALTER TABLE `lara_patients` DISABLE KEYS */;
INSERT INTO `lara_patients` VALUES (1,1,NULL,'PAT-001','Mr. Patient','1990-01-01',NULL,'male','B+','01700000000','patient@example.com','$2y$12$JxpAAp6y0zNx1I5qj/KtouHdP0sFMkUYSZAA1PlxCjvb7DCO/daX6',NULL,1,NULL,NULL,NULL,NULL,NULL,NULL,'Dhaka, Bangladesh',NULL,NULL,'active','2026-02-06 00:35:16','2026-02-06 00:35:16',NULL);
/*!40000 ALTER TABLE `lara_patients` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `lara_payments`
--

DROP TABLE IF EXISTS `lara_payments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
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
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `lara_payments`
--

LOCK TABLES `lara_payments` WRITE;
/*!40000 ALTER TABLE `lara_payments` DISABLE KEYS */;
/*!40000 ALTER TABLE `lara_payments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `lara_permissions`
--

DROP TABLE IF EXISTS `lara_permissions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `lara_permissions` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `lara_permissions_name_unique` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=71 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `lara_permissions`
--

LOCK TABLES `lara_permissions` WRITE;
/*!40000 ALTER TABLE `lara_permissions` DISABLE KEYS */;
INSERT INTO `lara_permissions` VALUES (1,'view_dashboard',NULL,'2026-02-06 00:35:11','2026-02-06 00:35:11'),(2,'view_admin_dashboard',NULL,'2026-02-06 00:35:11','2026-02-06 00:35:11'),(3,'view_doctor_dashboard',NULL,'2026-02-06 00:35:11','2026-02-06 00:35:11'),(4,'view_nurse_dashboard',NULL,'2026-02-06 00:35:11','2026-02-06 00:35:11'),(5,'view_receptionist_dashboard',NULL,'2026-02-06 00:35:11','2026-02-06 00:35:11'),(6,'view_lab_dashboard',NULL,'2026-02-06 00:35:11','2026-02-06 00:35:11'),(7,'view_pharmacy_dashboard',NULL,'2026-02-06 00:35:11','2026-02-06 00:35:11'),(8,'view_accountant_dashboard',NULL,'2026-02-06 00:35:11','2026-02-06 00:35:11'),(9,'view_users',NULL,'2026-02-06 00:35:11','2026-02-06 00:35:11'),(10,'create_users',NULL,'2026-02-06 00:35:11','2026-02-06 00:35:11'),(11,'edit_users',NULL,'2026-02-06 00:35:11','2026-02-06 00:35:11'),(12,'delete_users',NULL,'2026-02-06 00:35:11','2026-02-06 00:35:11'),(13,'manage_roles',NULL,'2026-02-06 00:35:11','2026-02-06 00:35:11'),(14,'view_departments',NULL,'2026-02-06 00:35:11','2026-02-06 00:35:11'),(15,'create_departments',NULL,'2026-02-06 00:35:11','2026-02-06 00:35:11'),(16,'edit_departments',NULL,'2026-02-06 00:35:11','2026-02-06 00:35:11'),(17,'delete_departments',NULL,'2026-02-06 00:35:11','2026-02-06 00:35:11'),(18,'manage_clinic_settings',NULL,'2026-02-06 00:35:11','2026-02-06 00:35:11'),(19,'view_patients',NULL,'2026-02-06 00:35:11','2026-02-06 00:35:11'),(20,'create_patients',NULL,'2026-02-06 00:35:11','2026-02-06 00:35:11'),(21,'edit_patients',NULL,'2026-02-06 00:35:11','2026-02-06 00:35:11'),(22,'delete_patients',NULL,'2026-02-06 00:35:11','2026-02-06 00:35:11'),(23,'view_doctors',NULL,'2026-02-06 00:35:11','2026-02-06 00:35:11'),(24,'create_doctors',NULL,'2026-02-06 00:35:11','2026-02-06 00:35:11'),(25,'edit_doctors',NULL,'2026-02-06 00:35:11','2026-02-06 00:35:11'),(26,'delete_doctors',NULL,'2026-02-06 00:35:11','2026-02-06 00:35:11'),(27,'manage_doctor_schedule',NULL,'2026-02-06 00:35:11','2026-02-06 00:35:11'),(28,'manage_doctor_clinic_assignments',NULL,'2026-02-06 00:35:11','2026-02-06 00:35:11'),(29,'view_staff',NULL,'2026-02-06 00:35:11','2026-02-06 00:35:11'),(30,'create_staff',NULL,'2026-02-06 00:35:11','2026-02-06 00:35:11'),(31,'edit_staff',NULL,'2026-02-06 00:35:11','2026-02-06 00:35:11'),(32,'delete_staff',NULL,'2026-02-06 00:35:11','2026-02-06 00:35:11'),(33,'view_appointments',NULL,'2026-02-06 00:35:11','2026-02-06 00:35:11'),(34,'create_appointments',NULL,'2026-02-06 00:35:11','2026-02-06 00:35:11'),(35,'edit_appointments',NULL,'2026-02-06 00:35:11','2026-02-06 00:35:11'),(36,'cancel_appointments',NULL,'2026-02-06 00:35:11','2026-02-06 00:35:11'),(37,'perform_consultation',NULL,'2026-02-06 00:35:11','2026-02-06 00:35:11'),(38,'view_consultations',NULL,'2026-02-06 00:35:11','2026-02-06 00:35:11'),(39,'view_ipd',NULL,'2026-02-06 00:35:11','2026-02-06 00:35:11'),(40,'view_admissions',NULL,'2026-02-06 00:35:11','2026-02-06 00:35:11'),(41,'create_admissions',NULL,'2026-02-06 00:35:11','2026-02-06 00:35:11'),(42,'discharge_patients',NULL,'2026-02-06 00:35:11','2026-02-06 00:35:11'),(43,'manage_beds',NULL,'2026-02-06 00:35:11','2026-02-06 00:35:11'),(44,'manage_wards',NULL,'2026-02-06 00:35:11','2026-02-06 00:35:11'),(45,'view_nursing_notes',NULL,'2026-02-06 00:35:11','2026-02-06 00:35:11'),(46,'create_nursing_notes',NULL,'2026-02-06 00:35:11','2026-02-06 00:35:11'),(47,'create_prescriptions',NULL,'2026-02-06 00:35:11','2026-02-06 00:35:11'),(48,'view_prescriptions',NULL,'2026-02-06 00:35:11','2026-02-06 00:35:11'),(49,'view_pharmacy',NULL,'2026-02-06 00:35:11','2026-02-06 00:35:11'),(50,'view_pharmacy_inventory',NULL,'2026-02-06 00:35:11','2026-02-06 00:35:11'),(51,'manage_pharmacy_inventory',NULL,'2026-02-06 00:35:11','2026-02-06 00:35:11'),(52,'process_pharmacy_sales',NULL,'2026-02-06 00:35:11','2026-02-06 00:35:11'),(53,'view_medicines',NULL,'2026-02-06 00:35:11','2026-02-06 00:35:11'),(54,'create_medicines',NULL,'2026-02-06 00:35:11','2026-02-06 00:35:11'),(55,'edit_medicines',NULL,'2026-02-06 00:35:11','2026-02-06 00:35:11'),(56,'delete_medicines',NULL,'2026-02-06 00:35:11','2026-02-06 00:35:11'),(57,'view_lab',NULL,'2026-02-06 00:35:11','2026-02-06 00:35:11'),(58,'view_lab_orders',NULL,'2026-02-06 00:35:11','2026-02-06 00:35:11'),(59,'create_lab_orders',NULL,'2026-02-06 00:35:11','2026-02-06 00:35:11'),(60,'enter_lab_results',NULL,'2026-02-06 00:35:11','2026-02-06 00:35:11'),(61,'view_lab_tests',NULL,'2026-02-06 00:35:11','2026-02-06 00:35:11'),(62,'create_lab_tests',NULL,'2026-02-06 00:35:11','2026-02-06 00:35:11'),(63,'edit_lab_tests',NULL,'2026-02-06 00:35:11','2026-02-06 00:35:11'),(64,'delete_lab_tests',NULL,'2026-02-06 00:35:11','2026-02-06 00:35:11'),(65,'view_billing',NULL,'2026-02-06 00:35:11','2026-02-06 00:35:11'),(66,'view_invoices',NULL,'2026-02-06 00:35:11','2026-02-06 00:35:11'),(67,'create_invoices',NULL,'2026-02-06 00:35:11','2026-02-06 00:35:11'),(68,'process_payments',NULL,'2026-02-06 00:35:11','2026-02-06 00:35:11'),(69,'view_reports',NULL,'2026-02-06 00:35:11','2026-02-06 00:35:11'),(70,'view_financial_reports',NULL,'2026-02-06 00:35:11','2026-02-06 00:35:11');
/*!40000 ALTER TABLE `lara_permissions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `lara_personal_access_tokens`
--

DROP TABLE IF EXISTS `lara_personal_access_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
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
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `lara_personal_access_tokens`
--

LOCK TABLES `lara_personal_access_tokens` WRITE;
/*!40000 ALTER TABLE `lara_personal_access_tokens` DISABLE KEYS */;
/*!40000 ALTER TABLE `lara_personal_access_tokens` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `lara_pharmacy_sale_items`
--

DROP TABLE IF EXISTS `lara_pharmacy_sale_items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `lara_pharmacy_sale_items` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `pharmacy_sale_id` bigint unsigned NOT NULL,
  `medicine_id` bigint unsigned NOT NULL,
  `quantity` int NOT NULL,
  `unit_price` decimal(10,2) NOT NULL,
  `unit_cost` decimal(10,2) NOT NULL DEFAULT '0.00',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `lara_pharmacy_sale_items_pharmacy_sale_id_foreign` (`pharmacy_sale_id`),
  KEY `lara_pharmacy_sale_items_medicine_id_foreign` (`medicine_id`),
  CONSTRAINT `lara_pharmacy_sale_items_medicine_id_foreign` FOREIGN KEY (`medicine_id`) REFERENCES `lara_medicines` (`id`) ON DELETE RESTRICT,
  CONSTRAINT `lara_pharmacy_sale_items_pharmacy_sale_id_foreign` FOREIGN KEY (`pharmacy_sale_id`) REFERENCES `lara_pharmacy_sales` (`id`) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `lara_pharmacy_sale_items`
--

LOCK TABLES `lara_pharmacy_sale_items` WRITE;
/*!40000 ALTER TABLE `lara_pharmacy_sale_items` DISABLE KEYS */;
/*!40000 ALTER TABLE `lara_pharmacy_sale_items` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `lara_pharmacy_sales`
--

DROP TABLE IF EXISTS `lara_pharmacy_sales`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
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
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `lara_pharmacy_sales`
--

LOCK TABLES `lara_pharmacy_sales` WRITE;
/*!40000 ALTER TABLE `lara_pharmacy_sales` DISABLE KEYS */;
/*!40000 ALTER TABLE `lara_pharmacy_sales` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `lara_prescription_complaint`
--

DROP TABLE IF EXISTS `lara_prescription_complaint`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
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
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `lara_prescription_complaint`
--

LOCK TABLES `lara_prescription_complaint` WRITE;
/*!40000 ALTER TABLE `lara_prescription_complaint` DISABLE KEYS */;
/*!40000 ALTER TABLE `lara_prescription_complaint` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `lara_prescription_items`
--

DROP TABLE IF EXISTS `lara_prescription_items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
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
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `lara_prescription_items`
--

LOCK TABLES `lara_prescription_items` WRITE;
/*!40000 ALTER TABLE `lara_prescription_items` DISABLE KEYS */;
/*!40000 ALTER TABLE `lara_prescription_items` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `lara_prescriptions`
--

DROP TABLE IF EXISTS `lara_prescriptions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
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
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `lara_prescriptions`
--

LOCK TABLES `lara_prescriptions` WRITE;
/*!40000 ALTER TABLE `lara_prescriptions` DISABLE KEYS */;
/*!40000 ALTER TABLE `lara_prescriptions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `lara_role_permission`
--

DROP TABLE IF EXISTS `lara_role_permission`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
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
) ENGINE=InnoDB AUTO_INCREMENT=275 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `lara_role_permission`
--

LOCK TABLES `lara_role_permission` WRITE;
/*!40000 ALTER TABLE `lara_role_permission` DISABLE KEYS */;
INSERT INTO `lara_role_permission` VALUES (1,1,1,NULL,NULL),(2,1,2,NULL,NULL),(3,1,3,NULL,NULL),(4,1,4,NULL,NULL),(5,1,5,NULL,NULL),(6,1,6,NULL,NULL),(7,1,7,NULL,NULL),(8,1,8,NULL,NULL),(9,1,9,NULL,NULL),(10,1,10,NULL,NULL),(11,1,11,NULL,NULL),(12,1,12,NULL,NULL),(13,1,13,NULL,NULL),(14,1,14,NULL,NULL),(15,1,15,NULL,NULL),(16,1,16,NULL,NULL),(17,1,17,NULL,NULL),(18,1,18,NULL,NULL),(19,1,19,NULL,NULL),(20,1,20,NULL,NULL),(21,1,21,NULL,NULL),(22,1,22,NULL,NULL),(23,1,23,NULL,NULL),(24,1,24,NULL,NULL),(25,1,25,NULL,NULL),(26,1,26,NULL,NULL),(27,1,27,NULL,NULL),(28,1,28,NULL,NULL),(29,1,29,NULL,NULL),(30,1,30,NULL,NULL),(31,1,31,NULL,NULL),(32,1,32,NULL,NULL),(33,1,33,NULL,NULL),(34,1,34,NULL,NULL),(35,1,35,NULL,NULL),(36,1,36,NULL,NULL),(37,1,37,NULL,NULL),(38,1,38,NULL,NULL),(39,1,39,NULL,NULL),(40,1,40,NULL,NULL),(41,1,41,NULL,NULL),(42,1,42,NULL,NULL),(43,1,43,NULL,NULL),(44,1,44,NULL,NULL),(45,1,45,NULL,NULL),(46,1,46,NULL,NULL),(47,1,47,NULL,NULL),(48,1,48,NULL,NULL),(49,1,49,NULL,NULL),(50,1,50,NULL,NULL),(51,1,51,NULL,NULL),(52,1,52,NULL,NULL),(53,1,53,NULL,NULL),(54,1,54,NULL,NULL),(55,1,55,NULL,NULL),(56,1,56,NULL,NULL),(57,1,57,NULL,NULL),(58,1,58,NULL,NULL),(59,1,59,NULL,NULL),(60,1,60,NULL,NULL),(61,1,61,NULL,NULL),(62,1,62,NULL,NULL),(63,1,63,NULL,NULL),(64,1,64,NULL,NULL),(65,1,65,NULL,NULL),(66,1,66,NULL,NULL),(67,1,67,NULL,NULL),(68,1,68,NULL,NULL),(69,1,69,NULL,NULL),(70,1,70,NULL,NULL),(71,2,1,NULL,NULL),(72,2,2,NULL,NULL),(73,2,3,NULL,NULL),(74,2,4,NULL,NULL),(75,2,5,NULL,NULL),(76,2,6,NULL,NULL),(77,2,7,NULL,NULL),(78,2,8,NULL,NULL),(79,2,9,NULL,NULL),(80,2,10,NULL,NULL),(81,2,11,NULL,NULL),(82,2,12,NULL,NULL),(83,2,13,NULL,NULL),(84,2,14,NULL,NULL),(85,2,15,NULL,NULL),(86,2,16,NULL,NULL),(87,2,17,NULL,NULL),(88,2,18,NULL,NULL),(89,2,19,NULL,NULL),(90,2,20,NULL,NULL),(91,2,21,NULL,NULL),(92,2,22,NULL,NULL),(93,2,23,NULL,NULL),(94,2,24,NULL,NULL),(95,2,25,NULL,NULL),(96,2,26,NULL,NULL),(97,2,27,NULL,NULL),(98,2,28,NULL,NULL),(99,2,29,NULL,NULL),(100,2,30,NULL,NULL),(101,2,31,NULL,NULL),(102,2,32,NULL,NULL),(103,2,33,NULL,NULL),(104,2,34,NULL,NULL),(105,2,35,NULL,NULL),(106,2,36,NULL,NULL),(107,2,37,NULL,NULL),(108,2,38,NULL,NULL),(109,2,39,NULL,NULL),(110,2,40,NULL,NULL),(111,2,41,NULL,NULL),(112,2,42,NULL,NULL),(113,2,43,NULL,NULL),(114,2,44,NULL,NULL),(115,2,45,NULL,NULL),(116,2,46,NULL,NULL),(117,2,47,NULL,NULL),(118,2,48,NULL,NULL),(119,2,49,NULL,NULL),(120,2,50,NULL,NULL),(121,2,51,NULL,NULL),(122,2,52,NULL,NULL),(123,2,53,NULL,NULL),(124,2,54,NULL,NULL),(125,2,55,NULL,NULL),(126,2,56,NULL,NULL),(127,2,57,NULL,NULL),(128,2,58,NULL,NULL),(129,2,59,NULL,NULL),(130,2,60,NULL,NULL),(131,2,61,NULL,NULL),(132,2,62,NULL,NULL),(133,2,63,NULL,NULL),(134,2,64,NULL,NULL),(135,2,65,NULL,NULL),(136,2,66,NULL,NULL),(137,2,67,NULL,NULL),(138,2,68,NULL,NULL),(139,2,69,NULL,NULL),(140,2,70,NULL,NULL),(141,3,1,NULL,NULL),(142,3,2,NULL,NULL),(143,3,3,NULL,NULL),(144,3,4,NULL,NULL),(145,3,5,NULL,NULL),(146,3,6,NULL,NULL),(147,3,7,NULL,NULL),(148,3,8,NULL,NULL),(149,3,9,NULL,NULL),(150,3,10,NULL,NULL),(151,3,11,NULL,NULL),(152,3,12,NULL,NULL),(153,3,13,NULL,NULL),(154,3,14,NULL,NULL),(155,3,15,NULL,NULL),(156,3,16,NULL,NULL),(157,3,17,NULL,NULL),(158,3,18,NULL,NULL),(159,3,19,NULL,NULL),(160,3,20,NULL,NULL),(161,3,21,NULL,NULL),(162,3,22,NULL,NULL),(163,3,23,NULL,NULL),(164,3,24,NULL,NULL),(165,3,25,NULL,NULL),(166,3,26,NULL,NULL),(167,3,27,NULL,NULL),(168,3,28,NULL,NULL),(169,3,29,NULL,NULL),(170,3,30,NULL,NULL),(171,3,31,NULL,NULL),(172,3,32,NULL,NULL),(173,3,33,NULL,NULL),(174,3,34,NULL,NULL),(175,3,35,NULL,NULL),(176,3,36,NULL,NULL),(177,3,37,NULL,NULL),(178,3,38,NULL,NULL),(179,3,39,NULL,NULL),(180,3,40,NULL,NULL),(181,3,41,NULL,NULL),(182,3,42,NULL,NULL),(183,3,43,NULL,NULL),(184,3,44,NULL,NULL),(185,3,45,NULL,NULL),(186,3,46,NULL,NULL),(187,3,47,NULL,NULL),(188,3,48,NULL,NULL),(189,3,49,NULL,NULL),(190,3,50,NULL,NULL),(191,3,51,NULL,NULL),(192,3,52,NULL,NULL),(193,3,53,NULL,NULL),(194,3,54,NULL,NULL),(195,3,55,NULL,NULL),(196,3,56,NULL,NULL),(197,3,57,NULL,NULL),(198,3,58,NULL,NULL),(199,3,59,NULL,NULL),(200,3,60,NULL,NULL),(201,3,61,NULL,NULL),(202,3,62,NULL,NULL),(203,3,63,NULL,NULL),(204,3,64,NULL,NULL),(205,3,65,NULL,NULL),(206,3,66,NULL,NULL),(207,3,67,NULL,NULL),(208,3,68,NULL,NULL),(209,3,69,NULL,NULL),(210,3,70,NULL,NULL),(211,4,59,NULL,NULL),(212,4,47,NULL,NULL),(213,4,21,NULL,NULL),(214,4,37,NULL,NULL),(215,4,40,NULL,NULL),(216,4,33,NULL,NULL),(217,4,38,NULL,NULL),(218,4,1,NULL,NULL),(219,4,3,NULL,NULL),(220,4,39,NULL,NULL),(221,4,57,NULL,NULL),(222,4,58,NULL,NULL),(223,4,19,NULL,NULL),(224,4,48,NULL,NULL),(225,5,46,NULL,NULL),(226,5,43,NULL,NULL),(227,5,40,NULL,NULL),(228,5,1,NULL,NULL),(229,5,39,NULL,NULL),(230,5,4,NULL,NULL),(231,5,45,NULL,NULL),(232,5,19,NULL,NULL),(233,6,36,NULL,NULL),(234,6,41,NULL,NULL),(235,6,34,NULL,NULL),(236,6,67,NULL,NULL),(237,6,20,NULL,NULL),(238,6,42,NULL,NULL),(239,6,35,NULL,NULL),(240,6,21,NULL,NULL),(241,6,43,NULL,NULL),(242,6,40,NULL,NULL),(243,6,33,NULL,NULL),(244,6,65,NULL,NULL),(245,6,1,NULL,NULL),(246,6,39,NULL,NULL),(247,6,19,NULL,NULL),(248,6,5,NULL,NULL),(249,7,60,NULL,NULL),(250,7,1,NULL,NULL),(251,7,57,NULL,NULL),(252,7,6,NULL,NULL),(253,7,58,NULL,NULL),(254,7,61,NULL,NULL),(255,8,67,NULL,NULL),(256,8,51,NULL,NULL),(257,8,68,NULL,NULL),(258,8,52,NULL,NULL),(259,8,65,NULL,NULL),(260,8,1,NULL,NULL),(261,8,66,NULL,NULL),(262,8,53,NULL,NULL),(263,8,49,NULL,NULL),(264,8,7,NULL,NULL),(265,8,50,NULL,NULL),(266,8,48,NULL,NULL),(267,9,67,NULL,NULL),(268,9,68,NULL,NULL),(269,9,8,NULL,NULL),(270,9,65,NULL,NULL),(271,9,1,NULL,NULL),(272,9,70,NULL,NULL),(273,9,66,NULL,NULL),(274,9,69,NULL,NULL);
/*!40000 ALTER TABLE `lara_role_permission` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `lara_roles`
--

DROP TABLE IF EXISTS `lara_roles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `lara_roles` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `lara_roles_name_unique` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `lara_roles`
--

LOCK TABLES `lara_roles` WRITE;
/*!40000 ALTER TABLE `lara_roles` DISABLE KEYS */;
INSERT INTO `lara_roles` VALUES (1,'Super Admin','System Owner','2026-02-06 00:35:11','2026-02-06 00:35:11'),(2,'Clinic Admin','Administrator for the clinic','2026-02-06 00:35:12','2026-02-06 00:35:12'),(3,'Admin','Administrator','2026-02-06 00:35:12','2026-02-06 00:35:12'),(4,'Doctor','Medical Doctor','2026-02-06 00:35:12','2026-02-06 00:35:12'),(5,'Nurse','IPD Nurse','2026-02-06 00:35:12','2026-02-06 00:35:12'),(6,'Receptionist','Front Desk','2026-02-06 00:35:12','2026-02-06 00:35:12'),(7,'Lab Technician','Lab Staff','2026-02-06 00:35:12','2026-02-06 00:35:12'),(8,'Pharmacist','Pharmacy Staff','2026-02-06 00:35:12','2026-02-06 00:35:12'),(9,'Accountant','Finance Staff','2026-02-06 00:35:12','2026-02-06 00:35:12');
/*!40000 ALTER TABLE `lara_roles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `lara_rooms`
--

DROP TABLE IF EXISTS `lara_rooms`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
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
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `lara_rooms`
--

LOCK TABLES `lara_rooms` WRITE;
/*!40000 ALTER TABLE `lara_rooms` DISABLE KEYS */;
INSERT INTO `lara_rooms` VALUES (1,1,1,'GEN-201','general',1000.00,'available','2026-02-06 00:35:12','2026-02-06 00:35:12'),(2,1,1,'GEN-202','general',1000.00,'available','2026-02-06 00:35:12','2026-02-06 00:35:12'),(3,2,1,'GEN-201','general',1000.00,'available','2026-02-06 00:35:12','2026-02-06 00:35:12'),(4,2,1,'GEN-202','general',1000.00,'available','2026-02-06 00:35:12','2026-02-06 00:35:12'),(5,3,1,'ICU-301','icu',5000.00,'available','2026-02-06 00:35:12','2026-02-06 00:35:12'),(6,3,1,'ICU-302','icu',5000.00,'available','2026-02-06 00:35:12','2026-02-06 00:35:12'),(7,4,1,'VIP-401','cabin',3000.00,'available','2026-02-06 00:35:12','2026-02-06 00:35:12'),(8,4,1,'VIP-402','cabin',3000.00,'available','2026-02-06 00:35:12','2026-02-06 00:35:12');
/*!40000 ALTER TABLE `lara_rooms` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `lara_sessions`
--

DROP TABLE IF EXISTS `lara_sessions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
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
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `lara_sessions`
--

LOCK TABLES `lara_sessions` WRITE;
/*!40000 ALTER TABLE `lara_sessions` DISABLE KEYS */;
/*!40000 ALTER TABLE `lara_sessions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `lara_user_role`
--

DROP TABLE IF EXISTS `lara_user_role`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
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
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `lara_user_role`
--

LOCK TABLES `lara_user_role` WRITE;
/*!40000 ALTER TABLE `lara_user_role` DISABLE KEYS */;
INSERT INTO `lara_user_role` VALUES (1,1,1,NULL,NULL),(2,2,2,NULL,NULL),(3,3,4,NULL,NULL),(4,4,5,NULL,NULL),(5,5,6,NULL,NULL),(6,6,7,NULL,NULL),(7,7,8,NULL,NULL),(8,8,9,NULL,NULL);
/*!40000 ALTER TABLE `lara_user_role` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `lara_users`
--

DROP TABLE IF EXISTS `lara_users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
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
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `lara_users`
--

LOCK TABLES `lara_users` WRITE;
/*!40000 ALTER TABLE `lara_users` DISABLE KEYS */;
INSERT INTO `lara_users` VALUES (1,1,'superadmin@hospital.com','assets/img/profile/super-admin.jpg','Super Admin','$2y$12$iFhkM3E8QcP9WtrdDcnHgOsVtr2DUj.N5fnQB8wqs4UK3NRUwsHlC',NULL,NULL,'2026-02-06 00:35:13',NULL,0,'active','2026-02-06 00:35:13','2026-02-06 00:35:13',NULL),(2,1,'admin@hospital.com','assets/img/profile/clinic-admin.jpg','Clinic Admin','$2y$12$HnrcPTEfJhUwRFxXEj5P0.9oQAe4cQECpsq3jKJ9iu1IByEHZi38O',NULL,NULL,'2026-02-06 00:35:13',NULL,0,'active','2026-02-06 00:35:13','2026-02-06 00:35:13',NULL),(3,1,'doctor@hospital.com','assets/img/profile/default-doctor.jpg','Default Doctor','$2y$12$vu/hdUYTWAooLi.w1V.NN.ybt1P1O9NvIZmBUa9VyJiKmpxGWyjhG',NULL,NULL,'2026-02-06 00:35:14',NULL,0,'active','2026-02-06 00:35:14','2026-02-06 00:35:14',NULL),(4,1,'nurse@hospital.com','assets/img/profile/default-nurse.jpg','Default Nurse','$2y$12$u2R203Qu7D8AxI4KXqpSGej/gu0RTEOwMXpzA0M6jWybvML6CEk2e',NULL,NULL,'2026-02-06 00:35:14',NULL,0,'active','2026-02-06 00:35:14','2026-02-06 00:35:14',NULL),(5,1,'receptionist@hospital.com','assets/img/profile/default-receptionist.jpg','Default Receptionist','$2y$12$bUzDe2.WYbEW59TeyjdH4OqMmD9uH7rhtcKbhgC3HNYwCkGfCskZG',NULL,NULL,'2026-02-06 00:35:15',NULL,0,'active','2026-02-06 00:35:15','2026-02-06 00:35:15',NULL),(6,1,'lab@hospital.com','assets/img/profile/default-lab-technician.jpg','Default Lab Technician','$2y$12$1BeWhT.FOqG6XUTXFb.Hl.tvfDj9/4vxzOA.rxxRdJVIcekGAghTO',NULL,NULL,'2026-02-06 00:35:15',NULL,0,'active','2026-02-06 00:35:15','2026-02-06 00:35:15',NULL),(7,1,'pharmacist@hospital.com','assets/img/profile/default-pharmacist.jpg','Default Pharmacist','$2y$12$YeI1T20GPvtvyY44b33NXeUrUn3snTlWWVOwUU.qSwlSeQr/DrNT2',NULL,NULL,'2026-02-06 00:35:15',NULL,0,'active','2026-02-06 00:35:15','2026-02-06 00:35:15',NULL),(8,1,'accountant@hospital.com','assets/img/profile/default-accountant.jpg','Default Accountant','$2y$12$1zaewVZkFQRova9JKrScXObqm01GfWFVXD8vfNBD2gDN5a22gL75i',NULL,NULL,'2026-02-06 00:35:16',NULL,0,'active','2026-02-06 00:35:16','2026-02-06 00:35:16',NULL);
/*!40000 ALTER TABLE `lara_users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `lara_visits`
--

DROP TABLE IF EXISTS `lara_visits`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
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
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `lara_visits`
--

LOCK TABLES `lara_visits` WRITE;
/*!40000 ALTER TABLE `lara_visits` DISABLE KEYS */;
/*!40000 ALTER TABLE `lara_visits` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `lara_wards`
--

DROP TABLE IF EXISTS `lara_wards`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
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
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `lara_wards`
--

LOCK TABLES `lara_wards` WRITE;
/*!40000 ALTER TABLE `lara_wards` DISABLE KEYS */;
INSERT INTO `lara_wards` VALUES (1,1,'General Ward (Male)','general',2,NULL,'active','2026-02-06 00:35:12','2026-02-06 00:35:12'),(2,1,'General Ward (Female)','general',2,NULL,'active','2026-02-06 00:35:12','2026-02-06 00:35:12'),(3,1,'ICU','icu',3,NULL,'active','2026-02-06 00:35:12','2026-02-06 00:35:12'),(4,1,'VIP Cabin','cabin',4,NULL,'active','2026-02-06 00:35:12','2026-02-06 00:35:12');
/*!40000 ALTER TABLE `lara_wards` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-02-06 12:35:23
