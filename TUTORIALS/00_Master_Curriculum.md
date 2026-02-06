# Laravel Hospital Management System (HMS) - Master Reconstruction Guide

## Introduction
This curriculum is designed to guide you through rebuilding the entire Laravel HMS project from scratch. It contains **75+ meticulously structured classes** (lessons) covering every single component of the application.

Each class focuses on a specific aspect of the system, following a professional development lifecycle:
1.  **Theory & Architecture**: Why we are doing this.
2.  **Database Layer**: Migrations and Models.
3.  **Logic Layer**: Services, Traits, and Providers.
4.  **Control Layer**: Controllers, Requests, and Policies.
5.  **Presentation Layer**: Views and Routes.
6.  **Verification**: Testing and Debugging.

---

## Module 1: Environment & Foundation
*   **[Class 01] Project Initialization & Environment Setup**: Installing Laravel, configuring `.env`, setting up the database connection, and understanding the directory structure.
*   **[Class 02] Database Architecture & Schema Design**: Designing the ERD, understanding relationships, and setting up the core migration strategy.
*   **[Class 03] Service Provider Configuration**: configuring `AppServiceProvider`, setting up default string lengths, and understanding the boot lifecycle.
*   **[Class 04] Helper Functions & Support Classes**: Creating `app/Support` helpers for global usage.
*   **[Class 05] Base Model Architecture**: Creating abstract base models to enforce standards across the application.

## Module 2: Multi-Tenancy Architecture (The Core)
*   **[Class 06] The Multi-Tenancy Concept**: Understanding single-database multi-tenancy vs multi-database.
*   **[Class 07] The Clinic Model**: Creating the `Clinic` entity (the tenant) and its migration.
*   **[Class 08] TenantContext Support Class**: Implementing `app/Support/TenantContext.php` to manage the active clinic session.
*   **[Class 09] Global Scopes for Tenancy**: Implementing `BelongsToClinic` trait and global query scopes to automatically filter data.
*   **[Class 10] BaseTenantModel**: Creating the abstract model that all tenant-specific models will extend.
*   **[Class 11] Middleware for Tenant Context**: Creating `EnsureClinicContext` middleware to set the clinic ID on every request.
*   **[Class 12] Testing Multi-Tenancy**: Writing the first tests to prove data isolation between clinics.

## Module 3: Authentication & Authorization (RBAC)
*   **[Class 13] User Model & Migrations**: Customizing the default User model for the HMS context.
*   **[Class 14] Role-Based Access Control (RBAC) Design**: Designing `roles`, `permissions`, `role_permission`, and `user_role` tables.
*   **[Class 15] Role & Permission Models**: Implementing the models with many-to-many relationships.
*   **[Class 16] RBAC Seeding**: Creating `RolePermissionSeeder` to bootstrap the system with default roles (Super Admin, Doctor, etc.).
*   **[Class 17] Custom Auth Controllers**: Implementing `AuthenticatedSessionController` and `RegisteredUserController`.
*   **[Class 18] Authorization Policies**: Creating `UserPolicy` and `RolePolicy`.
*   **[Class 19] Middleware for Roles**: Implementing route-level protection based on roles.
*   **[Class 20] Auth Views & Components**: Building the login, register, and password reset views.

## Module 4: Core Entity Management
*   **[Class 21] Department Management**: Creating Departments (CRUD, Model, Controller, Views).
*   **[Class 22] Room & Ward Management**: Implementing physical location tracking (Rooms, Wards).
*   **[Class 23] Staff Management**: Creating the Staff entity and linking it to Users.
*   **[Class 24] Doctor Profile Architecture**: Designing the complex `Doctor` model with relationships to User and Department.
*   **[Class 25] Doctor Professional Details**: Implementing `DoctorEducation`, `DoctorCertification`, and `DoctorAward` models.
*   **[Class 26] Doctor CRUD Operations**: Building the controller and views for managing doctor profiles.
*   **[Class 27] Doctor Policy**: Securing doctor profile management.
*   **[Class 28] Image Handling**: Implementing `ClinicImage` and profile photo uploads.
*   **[Class 29] Dashboard Architecture**: Creating distinct dashboards for Super Admin, Doctor, and Receptionist.
*   **[Class 30] Core Entity Testing**: Writing feature tests for departments and doctors.

## Module 5: Patient Management System
*   **[Class 31] Patient Model Design**: Designing the `Patient` model with demographics and medical identifiers.
*   **[Class 32] Patient Global vs Clinic Scope**: Handling patients that might visit multiple clinics (Global Patient implementation).
*   **[Class 33] Patient Registration Workflow**: Building the registration form and controller logic.
*   **[Class 34] Patient Medical History**: Implementing `PatientMedicalHistory`, `PatientAllergy`, and `PatientSurgery` models.
*   **[Class 35] Vitals Tracking**: Creating `PatientVital` model and tracking logic.
*   **[Class 36] Patient Profile View**: Designing a comprehensive patient dashboard view.
*   **[Class 37] Patient Search & Filtering**: Implementing advanced search (by phone, NID, code) in `PatientSearchController`.
*   **[Class 38] Patient Portal Access**: Allowing patients to log in and view their own data.

## Module 6: Doctor Scheduling System
*   **[Class 39] Schedule Architecture**: Designing `DoctorSchedule` (weekly) and `DoctorScheduleException` (overrides).
*   **[Class 40] Schedule Management UI**: Building the interface for doctors to set their availability.
*   **[Class 41] Schedule Validation Logic**: Preventing overlapping schedules and invalid times.
*   **[Class 42] Slot Generation Service**: Creating the `AppointmentService` to calculate available time slots dynamically.
*   **[Class 43] Schedule Exceptions**: Handling leave requests and holidays.
*   **[Class 44] Doctor Assignment**: Linking doctors to specific clinics and departments.
*   **[Class 45] Testing Scheduling Logic**: Verifying slot generation and conflict detection.

## Module 7: Clinical Operations (OPD)
*   **[Class 46] Appointment Architecture**: Designing the `Appointment` model and status workflow.
*   **[Class 47] Appointment Booking Flow**: Building the booking form for receptionists.
*   **[Class 48] Double Booking Prevention**: Implementing logic to prevent concurrent appointments.
*   **[Class 49] Consultation Model**: Designing the `Consultation` model linking Doctor, Patient, and Appointment.
*   **[Class 50] Prescription System**: Creating `Prescription` and `PrescriptionItem` models.
*   **[Class 51] Diagnosis & Notes**: Implementing clinical notes and ICD-10 coding support.
*   **[Class 52] OPD Workflow Testing**: End-to-end testing of the Appointment -> Consultation -> Prescription flow.

## Module 8: In-Patient Department (IPD)
*   **[Class 53] IPD Architecture**: Designing `Admission`, `Bed`, and `BedAssignment` models.
*   **[Class 54] Admission Workflow**: Creating the admission form and controller logic.
*   **[Class 55] Bed Management Logic**: Implementing bed availability checking and assignment.
*   **[Class 56] Doctor Rounds**: creating `InpatientRound` for daily doctor visits.
*   **[Class 57] Nursing Notes**: Implementing `NursingNote` for continuous patient monitoring.
*   **[Class 58] Discharge Process**: Handling patient discharge, billing calculation, and bed release.

## Module 9: Pharmacy Management
*   **[Class 59] Medicine Inventory**: Designing `Medicine` and `MedicineBatch` models.
*   **[Class 60] Stock Management**: Implementing FIFO (First-In-First-Out) logic for medicine batches.
*   **[Class 61] Pharmacy Sales Architecture**: Designing `PharmacySale` and `PharmacySaleItem` models.
*   **[Class 62] Point of Sale (POS) System**: Building the interface for selling medicines.
*   **[Class 63] Inventory Alerts**: Implementing `LowStockNotification`.
*   **[Class 64] Pharmacy Reports**: Generating sales and profit reports.

## Module 10: Laboratory & Diagnostics
*   **[Class 65] Lab Test Catalog**: Designing `LabTest` model (types, prices, normal ranges).
*   **[Class 66] Lab Order System**: Creating `LabTestOrder` and linking it to Visits/Admissions.
*   **[Class 67] Sample Collection**: Managing sample status and barcodes.
*   **[Class 68] Result Entry**: Designing `LabTestResult` and the result entry interface.
*   **[Class 69] Report Generation**: Generating PDF lab reports.
*   **[Class 70] Lab Integration Testing**: Verifying the Order -> Result -> Report flow.

## Module 11: Billing & Finance
*   **[Class 71] Invoice Architecture**: Designing `Invoice` and `InvoiceItem` models.
*   **[Class 72] Automated Billing**: Integrating billing with OPD, IPD, Lab, and Pharmacy events.
*   **[Class 73] Payment Processing**: Designing `Payment` model and handling partial payments.
*   **[Class 74] Financial Reporting**: Creating daily/monthly income reports.
*   **[Class 75] Billing Policy & Security**: Ensuring only authorized staff can modify invoices.

## Module 12: Advanced Features & Deployment
*   **[Class 76] Activity Logging**: Implementing `ActivityLog` and the `LogsActivity` trait.
*   **[Class 77] Notification System**: Designing database notifications for system events.
*   **[Class 78] Excel Exports**: Implementing `Maatwebsite/Excel` exports for all reports.
*   **[Class 79] Mail System**: Configuring SMTP and creating email templates.
*   **[Class 80] Deployment Guide**: Setting up production environment, queues, and scheduling.
