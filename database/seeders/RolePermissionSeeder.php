<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;
use App\Models\Permission;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Disable foreign key checks to allow truncation
        \Illuminate\Support\Facades\Schema::disableForeignKeyConstraints();

        \Illuminate\Support\Facades\DB::table('role_permission')->truncate();
        \Illuminate\Support\Facades\DB::table('user_role')->truncate();
        Permission::truncate();
        Role::truncate();

        \Illuminate\Support\Facades\Schema::enableForeignKeyConstraints();

        // 1. Define Permissions
        $permissions = [
            // Dashboard
            'view_dashboard',
            'view_admin_dashboard',
            'view_doctor_dashboard',
            'view_nurse_dashboard',
            'view_receptionist_dashboard',
            'view_lab_dashboard',
            'view_pharmacy_dashboard',
            'view_accountant_dashboard',

            // User Management
            'view_users',
            'create_users',
            'edit_users',
            'delete_users',
            'manage_roles',

            // Clinic Management (Settings)
            'manage_clinic_settings',

            // Patient Management
            'view_patients',
            'create_patients',
            'edit_patients',
            'delete_patients',

            // Doctor Management
            'view_doctors',
            'create_doctors',
            'edit_doctors',
            'delete_doctors',
            'manage_doctor_schedule',

            // Staff Management
            'view_staff',
            'create_staff',
            'edit_staff',
            'delete_staff',

            // Appointment Management
            'view_appointments',
            'create_appointments',
            'edit_appointments',
            'cancel_appointments',

            // OPD / Consultation
            'perform_consultation',
            'view_consultations',

            // IPD / Admissions
            'view_ipd',
            'view_admissions',
            'create_admissions',
            'discharge_patients',
            'manage_beds',
            'manage_wards',
            'view_nursing_notes',
            'create_nursing_notes',

            // Prescriptions
            'create_prescriptions',
            'view_prescriptions',

            // Lab
            'view_lab',
            'view_lab_orders',
            'create_lab_orders',
            'enter_lab_results',

            // Pharmacy
            'view_pharmacy',
            'view_pharmacy_inventory',
            'manage_pharmacy_inventory',
            'process_pharmacy_sales',

            // Billing
            'view_billing',
            'view_invoices',
            'create_invoices',
            'process_payments',

            // Reports
            'view_reports',
            'view_financial_reports',
        ];

        foreach ($permissions as $permissionName) {
            Permission::firstOrCreate(['name' => $permissionName]);
        }

        // 2. Define Roles and Assign Permissions

        // Super Admin (System Owner)
        $superAdmin = Role::firstOrCreate(['name' => 'Super Admin', 'description' => 'System Owner']);
        $superAdmin->permissions()->sync(Permission::all());

        // Clinic Admin
        $clinicAdmin = Role::firstOrCreate(['name' => 'Clinic Admin', 'description' => 'Administrator for the clinic']);
        $clinicAdmin->permissions()->sync(Permission::all());

        // Doctor
        $doctor = Role::firstOrCreate(['name' => 'Doctor', 'description' => 'Medical Doctor']);
        $doctor->permissions()->sync(Permission::whereIn('name', [
            'view_dashboard',
            'view_doctor_dashboard',
            'view_patients',
            'view_appointments',
            'perform_consultation',
            'view_consultations',
            'create_prescriptions',
            'view_prescriptions',
            'view_lab_orders',
            'create_lab_orders',
            'view_lab',
            'view_ipd',
            'view_admissions',
        ])->pluck('id'));

        // Nurse
        $nurse = Role::firstOrCreate(['name' => 'Nurse', 'description' => 'IPD Nurse']);
        $nurse->permissions()->sync(Permission::whereIn('name', [
            'view_dashboard',
            'view_nurse_dashboard',
            'view_patients',
            'view_ipd',
            'view_admissions',
            'view_nursing_notes',
            'create_nursing_notes',
            'manage_beds',
        ])->pluck('id'));

        // Receptionist
        $receptionist = Role::firstOrCreate(['name' => 'Receptionist', 'description' => 'Front Desk']);
        $receptionist->permissions()->sync(Permission::whereIn('name', [
            'view_dashboard',
            'view_receptionist_dashboard',
            'view_patients',
            'create_patients',
            'edit_patients',
            'view_appointments',
            'create_appointments',
            'edit_appointments',
            'cancel_appointments',
            'view_billing',
            'create_invoices', // Basic billing
        ])->pluck('id'));

        // Lab Technician
        $labTech = Role::firstOrCreate(['name' => 'Lab Technician', 'description' => 'Lab Staff']);
        $labTech->permissions()->sync(Permission::whereIn('name', [
            'view_dashboard',
            'view_lab_dashboard',
            'view_lab',
            'view_lab_orders',
            'enter_lab_results',
        ])->pluck('id'));

        // Pharmacist
        $pharmacist = Role::firstOrCreate(['name' => 'Pharmacist', 'description' => 'Pharmacy Staff']);
        $pharmacist->permissions()->sync(Permission::whereIn('name', [
            'view_dashboard',
            'view_pharmacy_dashboard',
            'view_pharmacy',
            'view_pharmacy_inventory',
            'manage_pharmacy_inventory',
            'process_pharmacy_sales',
            'view_prescriptions',
        ])->pluck('id'));

        // Accountant
        $accountant = Role::firstOrCreate(['name' => 'Accountant', 'description' => 'Finance Staff']);
        $accountant->permissions()->sync(Permission::whereIn('name', [
            'view_dashboard',
            'view_accountant_dashboard',
            'view_billing',
            'view_invoices',
            'create_invoices',
            'process_payments',
            'view_reports',
            'view_financial_reports',
        ])->pluck('id'));
    }
}
