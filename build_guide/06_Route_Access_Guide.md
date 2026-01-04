# Route Access & Roles Guide

## Overview
- Access is enforced at two layers:
  - Route middleware uses ability-based checks with "can:permission" across feature groups [routes/web.php](file:///c:/Users/Mozammel/Desktop/IsDB_BISEW/laravel/laravel-hms-skeleton/routes/web.php#L48-L154).
  - Controllers authorize against policies and gates (model policies for CRUD; non-model gates for reports).
- Tenancy is enforced per-request via EnsureClinicContext middleware [bootstrap/app.php](file:///c:/Users/Mozammel/Desktop/IsDB_BISEW/laravel/laravel-hms-skeleton/bootstrap/app.php#L12-L20), setting clinic context for the current user.
- Roles map to permissions via the seeder; permissions are the unit used in routes, gates, and policies [RolePermissionSeeder.php](file:///c:/Users/Mozammel/Desktop/IsDB_BISEW/laravel/laravel-hms-skeleton/database/seeders/RolePermissionSeeder.php#L29-L154).

## Do Any Roles Access All Routes?
- Super Admin: Yes. Gate::before grants all abilities, and the role is assigned all permissions [AuthServiceProvider.php](file:///c:/Users/Mozammel/Desktop/IsDB_BISEW/laravel/laravel-hms-skeleton/app/Providers/AuthServiceProvider.php#L76-L84), [RolePermissionSeeder.php](file:///c:/Users/Mozammel/Desktop/IsDB_BISEW/laravel/laravel-hms-skeleton/database/seeders/RolePermissionSeeder.php#L157-L164).
- Clinic Admin: Yes. The role is assigned all permissions in the seeder, so all "can:*" route middleware and policy checks pass [RolePermissionSeeder.php](file:///c:/Users/Mozammel/Desktop/IsDB_BISEW/laravel/laravel-hms-skeleton/database/seeders/RolePermissionSeeder.php#L166-L170).
- Other roles: No. Access is limited to the permissions defined for each role. Even if a route group permits entry via "can:view_X", controllers still call Gate::authorize with policies to enforce per-model rules (e.g., clinic ownership).

## Are We Using Route Restrictions?
- Yes. The project uses "can:*" route middleware broadly to restrict entry to feature route groups:
  - Patients: "can:view_patients" [routes/web.php](file:///c:/Users/Mozammel/Desktop/IsDB_BISEW/laravel/laravel-hms-skeleton/routes/web.php#L48-L50)
  - Appointments: "can:view_appointments" [routes/web.php](file:///c:/Users/Mozammel/Desktop/IsDB_BISEW/laravel/laravel-hms-skeleton/routes/web.php#L51-L53)
  - Clinical: "can:perform_consultation" and "can:view_consultations" [routes/web.php](file:///c:/Users/Mozammel/Desktop/IsDB_BISEW/laravel/laravel-hms-skeleton/routes/web.php#L56-L75)
  - Billing: "can:view_billing" [routes/web.php](file:///c:/Users/Mozammel/Desktop/IsDB_BISEW/laravel/laravel-hms-skeleton/routes/web.php#L77-L87)
  - Pharmacy: "can:view_pharmacy" [routes/web.php](file:///c:/Users/Mozammel/Desktop/IsDB_BISEW/laravel/laravel-hms-skeleton/routes/web.php#L89-L104)
  - IPD: "can:view_ipd" [routes/web.php](file:///c:/Users/Mozammel/Desktop/IsDB_BISEW/laravel/laravel-hms-skeleton/routes/web.php#L106-L120)
  - Lab: "can:view_lab" [routes/web.php](file:///c:/Users/Mozammel/Desktop/IsDB_BISEW/laravel/laravel-hms-skeleton/routes/web.php#L122-L132)
  - Reports: "can:view_reports" [routes/web.php](file:///c:/Users/Mozammel/Desktop/IsDB_BISEW/laravel/laravel-hms-skeleton/routes/web.php#L134-L140)
  - Doctors, Staff: "can:view_doctors", "can:view_staff" [routes/web.php](file:///c:/Users/Mozammel/Desktop/IsDB_BISEW/laravel/laravel-hms-skeleton/routes/web.php#L144-L151)
- Controllers still enforce fine-grained access through policies (e.g., view/update/delete per model) and non-model gates:
  - Example: ReportController uses Gate::authorize('view_reports') and 'view_financial_reports' [ReportController.php](file:///c:/Users/Mozammel/Desktop/IsDB_BISEW/laravel/laravel-hms-skeleton/app/Http/Controllers/ReportController.php#L15-L43), backed by gates [AuthServiceProvider.php](file:///c:/Users/Mozammel/Desktop/IsDB_BISEW/laravel/laravel-hms-skeleton/app/Providers/AuthServiceProvider.php#L85-L90).
  - Example: Patient listing uses "viewAny" policy [PatientController.php](file:///c:/Users/Mozammel/Desktop/IsDB_BISEW/laravel/laravel-hms-skeleton/app/Http/Controllers/PatientController.php#L16-L22) mapped in [AuthServiceProvider.php](file:///c:/Users/Mozammel/Desktop/IsDB_BISEW/laravel/laravel-hms-skeleton/app/Providers/AuthServiceProvider.php#L48-L66).

## Role-by-Role Access Summary

### Super Admin
- Access: All routes and abilities via Gate::before and full permission assignment.
- Special: Can switch clinic context [routes/web.php](file:///c:/Users/Mozammel/Desktop/IsDB_BISEW/laravel/laravel-hms-skeleton/routes/web.php#L33-L41).

### Clinic Admin
- Access: All routes and abilities (assigned all permissions).
- Typical: Full access to administration (Doctors, Staff), reports, billing, pharmacy, IPD, lab, patients, appointments.

### Doctor
- Key permissions: "view_patients", "view_appointments", "perform_consultation", "view_consultations", "create_prescriptions", "view_prescriptions", "view_lab", "view_lab_orders", "create_lab_orders", "view_ipd", "view_admissions" [RolePermissionSeeder.php](file:///c:/Users/Mozammel/Desktop/IsDB_BISEW/laravel/laravel-hms-skeleton/database/seeders/RolePermissionSeeder.php#L180-L197).
- Routes passed:
  - Patients group ("can:view_patients")
  - Appointments group ("can:view_appointments")
  - Clinical consultation routes ("can:perform_consultation"), consultation show ("can:view_consultations")
  - Prescriptions index/show ("can:view_prescriptions")
  - Lab group ("can:view_lab"); controller policies allow creating orders if clinic-bound [LabController.php](file:///c:/Users/Mozammel/Desktop/IsDB_BISEW/laravel/laravel-hms-skeleton/app/Http/Controllers/LabController.php#L21-L39), [LabTestOrderPolicy.php](file:///c:/Users/Mozammel/Desktop/IsDB_BISEW/laravel/laravel-hms-skeleton/app/Policies/LabTestOrderPolicy.php#L20-L27)
  - IPD group ("can:view_ipd")

### Nurse
- Key permissions: "view_patients", "view_ipd", "view_admissions", "view_nursing_notes", "create_nursing_notes", "manage_beds" [RolePermissionSeeder.php](file:///c:/Users/Mozammel/Desktop/IsDB_BISEW/laravel/laravel-hms-skeleton/database/seeders/RolePermissionSeeder.php#L199-L210).
- Routes passed:
  - Patients group ("can:view_patients")
  - IPD group ("can:view_ipd"); bed assignment and discharge operations use policies for clinic checks [IpdController.php](file:///c:/Users/Mozammel/Desktop/IsDB_BISEW/laravel/laravel-hms-skeleton/app/Http/Controllers/IpdController.php#L71-L93)
- Not passed:
  - Appointments, billing, pharmacy, lab routes (no corresponding view_* permissions).

### Receptionist
- Key permissions: "view_patients", "create_patients", "edit_patients", "view_appointments", "create_appointments", "edit_appointments", "cancel_appointments", "view_billing", "create_invoices" [RolePermissionSeeder.php](file:///c:/Users/Mozammel/Desktop/IsDB_BISEW/laravel/laravel-hms-skeleton/database/seeders/RolePermissionSeeder.php#L212-L230).
- Routes passed:
  - Patients group ("can:view_patients")
  - Appointments group ("can:view_appointments")
  - Billing group ("can:view_billing") with controller enforcement for create/store [BillingController.php](file:///c:/Users/Mozammel/Desktop/IsDB_BISEW/laravel/laravel-hms-skeleton/app/Http/Controllers/BillingController.php#L27-L37)
- Notes:
  - Route middleware grants entry; actual create/update actions are enforced by policies in controllers (e.g., Gate::authorize('create', Model::class)).

### Lab Technician
- Key permissions: "view_lab", "view_lab_orders", "enter_lab_results" [RolePermissionSeeder.php](file:///c:/Users/Mozammel/Desktop/IsDB_BISEW/laravel/laravel-hms-skeleton/database/seeders/RolePermissionSeeder.php#L175-L186).
- Routes passed:
  - Lab group ("can:view_lab") [routes/web.php](file:///c:/Users/Mozammel/Desktop/IsDB_BISEW/laravel/laravel-hms-skeleton/routes/web.php#L122-L132)
- Notes:
  - Creating lab orders is governed by policy "create" which checks clinic membership, not explicit create_lab_orders permission [LabTestOrderPolicy.php](file:///c:/Users/Mozammel/Desktop/IsDB_BISEW/laravel/laravel-hms-skeleton/app/Policies/LabTestOrderPolicy.php#L20-L23). As long as the role can access the lab routes and is clinic-bound, creation is permitted.
  - Results entry is enforced by controller authorization Gate::authorize('update', $order) [LabController.php](file:///c:/Users/Mozammel/Desktop/IsDB_BISEW/laravel/laravel-hms-skeleton/app/Http/Controllers/LabController.php#L61-L71).

### Pharmacist
- Key permissions: "view_pharmacy", "view_pharmacy_inventory", "manage_pharmacy_inventory", "process_pharmacy_sales", "view_prescriptions" [RolePermissionSeeder.php](file:///c:/Users/Mozammel/Desktop/IsDB_BISEW/laravel/laravel-hms-skeleton/database/seeders/RolePermissionSeeder.php#L188-L199).
- Routes passed:
  - Pharmacy group ("can:view_pharmacy") covering POS and sales [routes/web.php](file:///c:/Users/Mozammel/Desktop/IsDB_BISEW/laravel/laravel-hms-skeleton/routes/web.php#L89-L104)
  - Inventory routes; create/store enforced by MedicineBatchPolicy [InventoryController.php](file:///c:/Users/Mozammel/Desktop/IsDB_BISEW/laravel/laravel-hms-skeleton/app/Http/Controllers/InventoryController.php#L24-L47), [MedicineBatchPolicy.php](file:///c:/Users/Mozammel/Desktop/IsDB_BISEW/laravel/laravel-hms-skeleton/app/Policies/MedicineBatchPolicy.php#L20-L27)
  - Medicine catalog; actions enforced by MedicinePolicy [MedicineController.php](file:///c:/Users/Mozammel/Desktop/IsDB_BISEW/laravel/laravel-hms-skeleton/app/Http/Controllers/MedicineController.php#L18-L27), [MedicinePolicy.php](file:///c:/Users/Mozammel/Desktop/IsDB_BISEW/laravel/laravel-hms-skeleton/app/Policies/MedicinePolicy.php#L20-L33)

### Accountant
- Key permissions: "view_billing", "view_invoices", "create_invoices", "process_payments", "view_reports", "view_financial_reports" [RolePermissionSeeder.php](file:///c:/Users/Mozammel/Desktop/IsDB_BISEW/laravel/laravel-hms-skeleton/database/seeders/RolePermissionSeeder.php#L201-L214).
- Routes passed:
  - Billing group ("can:view_billing") including payments [routes/web.php](file:///c:/Users/Mozammel/Desktop/IsDB_BISEW/laravel/laravel-hms-skeleton/routes/web.php#L77-L87)
  - Reports group ("can:view_reports") with Finance requiring 'view_financial_reports' [ReportController.php](file:///c:/Users/Mozammel/Desktop/IsDB_BISEW/laravel/laravel-hms-skeleton/app/Http/Controllers/ReportController.php#L25-L43), [AuthServiceProvider.php](file:///c:/Users/Mozammel/Desktop/IsDB_BISEW/laravel/laravel-hms-skeleton/app/Providers/AuthServiceProvider.php#L85-L90)

## Notes on Enforcement
- Route middleware controls access to route entry points, improving UX by hiding entire sections if lacking base permissions.
- Controllers and policies enforce per-action and per-record rules:
  - Example: even if a user enters the patients routes via "can:view_patients", they must pass policy checks to view/update/delete specific patients [PatientPolicy.php](file:///c:/Users/Mozammel/Desktop/IsDB_BISEW/laravel/laravel-hms-skeleton/app/Policies/PatientPolicy.php#L10-L33).
- Non-model abilities (like reports) are implemented as explicit gates:
  - Defined in AuthServiceProvider [AuthServiceProvider.php](file:///c:/Users/Mozammel/Desktop/IsDB_BISEW/laravel/laravel-hms-skeleton/app/Providers/AuthServiceProvider.php#L85-L90) and invoked by controllers [ReportController.php](file:///c:/Users/Mozammel/Desktop/IsDB_BISEW/laravel/laravel-hms-skeleton/app/Http/Controllers/ReportController.php#L15-L43).

