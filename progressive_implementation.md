# Progressive Implementation Plan: Hospital Management System (HMS) Expansion

## 1. Phased Implementation Roadmap (Step-by-Step)

This roadmap outlines a sequential approach to building the three separate Vue.js 3 dashboards while maintaining core Laravel production stability.

### Phase 1: Pilot Study & Scaffolding [COMPLETED]
*Goal: Create the "Shell" of the new dashboards and establish the API v2 pipeline.*

1.  **Line-by-Line Analysis:** Identification of `BaseTenantModel`, `BelongsToClinic`, and `EnsureTokenClinicContext` for safe multi-tenant expansion.
2.  **Environment Prep:** Setup of `dashboards/` directory with 3 Vue.js 3 projects (HRM, CRM, Asset).
3.  **API V2 Scaffolding:** Implementation of `api/v2` namespace, `BaseV2Controller`, and initial routes for Staff, Inquiries, and Procurement.
4.  **Auth Layer:** Setup of Laravel Sanctum for cross-origin authentication and Pinia for state management in Vue apps.
5.  **Database Expansion:** Creation of `Inquiry`, `ProcurementOrder`, and `ProcurementItem` models and migrations.

### Phase 2: UI Replication & CRM/HRM Pilot [COMPLETED]
*Goal: Clone the "CityCare" look-and-feel and implement first-slice features.*

1.  **UI Cloning:** Replicated CityCare's sidebar, header, and layout using independent Bootstrap 5 and Tabler Icons (no Laravel asset linking).
2.  **HRM Pilot:** Staff Directory view with real-time API integration and multi-tenant isolation.
3.  **CRM Pilot:** Inquiry Log system allowing staff to capture and track patient leads.
4.  **Asset Management Pilot:** Inventory levels view and initial Procurement lifecycle (Purchase Orders).

### Phase 3: Comprehensive Asset Management [IN PROGRESS]
*Goal: Full procurement workflow, equipment maintenance, and stock control.*

1.  **Procurement Lifecycle:** Full PO -> GRN -> Inventory Update workflow.
2.  **Biomedical Equipment Management:** Asset Tagging (QR/Barcode) and Maintenance Scheduling.
3.  **Stock Synchronization:** Real-time stock deduction using FEFO (First Expired, First Out) logic.

### Phase 4: Comprehensive HRM Dashboard [PENDING]
*Goal: Full lifecycle management of hospital staff and payroll.*

1.  **Recruitment & Onboarding:** Job Posting Engine and Digital Onboarding.
2.  **Attendance & Roster Management:** Biometric Integration API and Shift Scheduling.
3.  **Payroll Engine:** Dynamic Salary Calculation and automated Expense logging.

### Phase 5: Comprehensive CRM Dashboard [PENDING]
*Goal: Patient loyalty, lead management, and hospital marketing.*

1.  **Patient Engagement:** Post-Visit Surveys and Loyalty Points.
2.  **Marketing Hub:** SMS/Email Campaign Manager for health packages.
3.  **Support & Helpdesk:** Complaint Ticketing and Resolution Workflow.

---

## 2. Detailed Feature Breakdown (Enterprise Grade)

### 2.1 HRM Dashboard (Human Resource Management)
- **Staff Directory:** Advanced filters (department, role, shift, clinic branch).
- **Digital Onboarding:** Document upload (NID, Degree, Certifications) and contract signing.
- **Biometric Sync:** Real-time dashboard for thumb/face recognition attendance logs.
- **Payroll Processor:** One-click monthly payroll generation with automated Expense entry.

### 2.2 CRM Dashboard (Customer Relationship Management)
- **Lead Center:** Capture inquiries from landing page, Facebook, and WhatsApp.
- **NPS & Surveys:** Automated post-discharge feedback collection.
- **Loyalty Points:** Point accumulation for every bill paid, redeemable for pharmacy or consultation.
- **Ticket Management:** Tracking non-clinical complaints with SLA timers.

### 2.3 Asset & Inventory Management Dashboard
- **Procurement Lifecycle:** Purchase Requisitions -> PO -> GRN.
- **Asset Registry:** Detailed log of MRI, X-Ray, Ventilators, and Ward Beds.
- **Maintenance Calendar:** Automated alerts for preventive maintenance (PPM).
- **Expiry Watch:** Advanced warning system for consumable shelf-life (FEFO logic).

---

## 3. The "CityCare" UI Clone Strategy (Independent Vue.js)

To ensure the new dashboards look and feel like the original project while remaining technically independent:

1.  **Independent Styling:** Use **Bootstrap 5** installed via NPM in each Vue project. Avoid linking to Laravel's compiled assets.
2.  **Visual Parity:** Match colors, spacing, and typography by defining shared CSS variables in `AppLayout.vue`.
3.  **Component Architecture:** Rebuild `Sidebar.vue` and `Header.vue` using Vue-native reactivity (`ref`, `v-if`) instead of jQuery.
4.  **Asset Independence:** Use NPM versions of **Tabler Icons** and **Font Awesome**.

---

## 4. Technical Integration & Safety

### 4.1 Tenant Isolation Engine
- All new models extend `BaseTenantModel` or use the `BelongsToClinic` trait.
- Every clinical model automatically injects `WHERE clinic_id = ?` into every query.
- API requests use `EnsureTokenClinicContext` middleware to scope data to the authenticated user's clinic.

### 4.2 Non-Destructive Strategy
- **Namespace:** All new routes are under `api/v2/`.
- **Controllers:** New controllers are in `App\Http\Controllers\Api\V2`.
- **Frontend:** Vue apps are isolated in the `dashboards/` directory, leaving the core Laravel app and React Patient Portal untouched.

---

## 5. Cross-Module Synchronization Logic

- **HRM ↔ Finance:** Payroll finalization generates `Expense` entries in the core Laravel system.
- **Asset ↔ Pharmacy:** Procurement updates the central stock visible in the clinical Pharmacy module.
- **CRM ↔ Clinical:** Leads convert to `Patient` records; clinical billing triggers Loyalty Points accumulation.
- **Unified Reporting:** The Super Admin gets a "True Financial Picture" by combining data from all modules (Revenue - Expenses = Profit).
