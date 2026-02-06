<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Department;
use App\Models\Medicine;
use App\Models\Room;
use App\Models\Bed;
use App\Models\Ward;
use App\Models\Clinic;
use App\Support\TenantContext;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Role;
use App\Models\Permission;
use App\Models\Patient;
use App\Models\Doctor;
use App\Models\Appointment;
use App\Models\LabTest;
use App\Models\Admission;
use Illuminate\Support\Facades\Gate;
use App\Policies\MedicinePolicy;

class CrudGapAnalysisTest extends TestCase
{
    use RefreshDatabase;

    protected $clinic;
    protected $admin;

    protected function setUp(): void
    {
        parent::setUp();

        // Explicitly register policy to ensure it's found
        Gate::policy(Medicine::class, MedicinePolicy::class);

        $this->clinic = Clinic::create([
            'name' => 'Test Clinic',
            'code' => 'TC',
            'registration_number' => 'REG123',
            'address_line_1' => '123 Test St',
            'city' => 'Test City',
            'country' => 'Test Country',
            'timezone' => 'UTC',
            'currency' => 'USD',
            'status' => 'active'
        ]);
        TenantContext::setClinicId($this->clinic->id);

        $this->admin = User::factory()->create([
            'clinic_id' => $this->clinic->id,
            'name' => 'Admin User',
            'email' => 'admin@example.com',
        ]);

        // Assign necessary permissions
        // Use 'Clinic Admin' role name as expected by some controllers (e.g. IpdController)
        $role = Role::firstOrCreate(['name' => 'Clinic Admin']);

        // Ensure all permissions exist
        $permissions = [
            'view_departments', 'create_departments', 'edit_departments', 'delete_departments',
            'view_medicines', 'create_medicines', 'edit_medicines', 'delete_medicines',
            'view_pharmacy', 'manage_pharmacy_inventory', 'view_pharmacy_inventory',
            'view_ipd', 'create_ipd', 'edit_ipd', 'delete_ipd', 'create_admissions', 'view_admissions', 'discharge_patients', 'manage_beds', // Added IPD specific permissions
            'view_staff', 'create_staff', 'edit_staff', 'delete_staff',
            'view_patients', 'create_patients', 'edit_patients', 'delete_patients',
            'view_doctors', 'create_doctors', 'edit_doctors', 'delete_doctors',
            'view_appointments', 'create_appointments', 'edit_appointments', 'delete_appointments',
            'view_lab', 'create_lab', 'edit_lab', 'delete_lab'
        ];

        $permissionIds = [];
        foreach ($permissions as $perm) {
            // Check if permission exists first (global scope)
            $p = Permission::where('name', $perm)->first();
            if (!$p) {
                $p = Permission::create(['name' => $perm]);
            }
            $permissionIds[] = $p->id;
        }

        $role->permissions()->sync($permissionIds);
        $this->admin->assignRole($role);

        // Refresh the user to ensure roles and permissions are loaded
        $this->admin->refresh();
    }

    /** @test */
    public function department_crud_works()
    {
        $this->actingAs($this->admin);

        // Create
        $response = $this->post(route('departments.store'), [
            'name' => 'Cardiology',
            'description' => 'Heart stuff',
            'status' => 'active'
        ]);
        $response->assertRedirect();
        $this->assertDatabaseHas('departments', ['name' => 'Cardiology']);

        $dept = Department::where('name', 'Cardiology')->first();

        // Read
        $response = $this->get(route('departments.index'));
        $response->assertStatus(200);
        $response->assertSee('Cardiology');

        // Update
        $response = $this->put(route('departments.update', $dept), [
            'name' => 'Cardiology Updated',
            'description' => 'Heart stuff updated',
            'status' => 'inactive'
        ]);
        $response->assertRedirect();
        $this->assertDatabaseHas('departments', ['name' => 'Cardiology Updated']);

        // Delete
        $response = $this->delete(route('departments.destroy', $dept));
        $response->assertRedirect();
        $this->assertSoftDeleted('departments', ['id' => $dept->id]);
    }

    /** @test */
    public function medicine_crud_works()
    {
        $this->actingAs($this->admin);

        // Create
        $response = $this->post(route('pharmacy.medicines.store'), [
            'name' => 'Paracetamol',
            'generic_name' => 'Acetaminophen',
            'description' => 'Pain killer',
            'price' => 10.50,
            'stock_quantity' => 100,
            'sku' => 'PARA123',
            'manufacturer' => 'PharmaCorp',
            'status' => 'active'
        ]);
        $response->assertRedirect();
        $this->assertDatabaseHas('medicines', ['name' => 'Paracetamol']);

        $medicine = Medicine::where('name', 'Paracetamol')->first();

        // Read
        $response = $this->get(route('pharmacy.medicines.index'));
        $response->assertStatus(200);
        $response->assertSee('Paracetamol');

        // Update
        $response = $this->put(route('pharmacy.medicines.update', $medicine), [
            'name' => 'Paracetamol Extra',
            'generic_name' => 'Acetaminophen',
            'description' => 'Strong pain killer',
            'price' => 12.00,
            'stock_quantity' => 150,
            'sku' => 'PARA123',
            'manufacturer' => 'PharmaCorp',
            'status' => 'active'
        ]);
        $response->assertRedirect();
        $this->assertDatabaseHas('medicines', ['name' => 'Paracetamol Extra']);

        // Delete
        $response = $this->delete(route('pharmacy.medicines.destroy', $medicine));
        $response->assertRedirect();
        $this->assertDatabaseMissing('medicines', ['id' => $medicine->id]);
    }

    /** @test */
    public function room_crud_works()
    {
        $this->actingAs($this->admin);

        $ward = Ward::create([
            'clinic_id' => $this->clinic->id,
            'name' => 'General Ward',
            'type' => 'general',
            'status' => 'active'
        ]);

        // Create
        $response = $this->post(route('ipd.rooms.store'), [
            'ward_id' => $ward->id,
            'room_number' => '101',
            'room_type' => 'Private',
            'daily_rate' => 500,
            'status' => 'available'
        ]);
        $response->assertRedirect();
        $this->assertDatabaseHas('rooms', ['room_number' => '101']);

        $room = Room::where('room_number', '101')->first();

        // Read
        $response = $this->get(route('ipd.rooms.index'));
        $response->assertStatus(200);
        $response->assertSee('101');

        // Update
        $response = $this->put(route('ipd.rooms.update', $room), [
            'ward_id' => $ward->id,
            'room_number' => '101-B',
            'room_type' => 'Private Deluxe',
            'daily_rate' => 600,
            'status' => 'occupied'
        ]);
        $response->assertRedirect();
        $this->assertDatabaseHas('rooms', ['room_number' => '101-B']);

        // Delete
        $response = $this->delete(route('ipd.rooms.destroy', $room));
        $response->assertRedirect();
        $this->assertDatabaseMissing('rooms', ['id' => $room->id]);
    }

    /** @test */
    public function staff_crud_works()
    {
        $this->actingAs($this->admin);

        $nurseRole = Role::firstOrCreate(['name' => 'nurse']);

        // Create
        $response = $this->post(route('staff.store'), [
            'name' => 'Nurse Joy',
            'email' => 'joy@example.com',
            'role_id' => $nurseRole->id,
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'status' => 'active'
        ]);
        $response->assertRedirect();
        $this->assertDatabaseHas('users', ['email' => 'joy@example.com']);

        $staff = User::where('email', 'joy@example.com')->first();

        // Read
        $response = $this->get(route('staff.index'));
        $response->assertStatus(200);
        $response->assertSee('Nurse Joy');

        // Update
        $response = $this->put(route('staff.update', $staff), [
            'name' => 'Nurse Joy Updated',
            'role_id' => $nurseRole->id,
            'profile_photo' => null
        ]);
        $response->assertRedirect();
        $this->assertDatabaseHas('users', ['name' => 'Nurse Joy Updated']);

        // Delete
        $response = $this->delete(route('staff.destroy', $staff));
        $response->assertRedirect();
        $this->assertSoftDeleted('users', ['id' => $staff->id]);
    }

    /** @test */
    public function patient_crud_works()
    {
        $this->actingAs($this->admin);

        // Create
        $response = $this->post(route('patients.store'), [
            'name' => 'John Doe',
            'date_of_birth' => '1990-01-01',
            'gender' => 'male',
            'phone' => '1234567890',
            'address' => '123 Main St',
            'nid_number' => '1234567890123',
            'status' => 'active'
        ]);
        $response->assertRedirect();
        $this->assertDatabaseHas('patients', ['name' => 'John Doe']);

        $patient = Patient::where('name', 'John Doe')->first();

        // Read
        $response = $this->get(route('patients.index'));
        $response->assertStatus(200);
        $response->assertSee('John Doe');

        // Update
        $response = $this->put(route('patients.update', $patient), [
            'name' => 'John Doe Updated',
            'date_of_birth' => '1990-01-01',
            'gender' => 'male',
            'phone' => '1234567890',
            'address' => '123 Main St Updated',
            'nid_number' => '1234567890123',
            'status' => 'active'
        ]);
        $response->assertRedirect();
        $this->assertDatabaseHas('patients', ['name' => 'John Doe Updated']);

        // Delete
        $response = $this->delete(route('patients.destroy', $patient));
        $response->assertRedirect();
        $this->assertSoftDeleted('patients', ['id' => $patient->id]);
    }

    /** @test */
    public function doctor_crud_works()
    {
        $this->actingAs($this->admin);

        $dept = Department::create(['clinic_id' => $this->clinic->id, 'name' => 'Neurology', 'status' => 'active']);
        $doctorRole = Role::firstOrCreate(['name' => 'Doctor']);

        // Create
        $response = $this->post(route('doctors.store'), [
            'name' => 'Dr. Strange',
            'email' => 'strange@example.com',
            'phone' => '9876543210',
            'password' => 'password123',
            'primary_department_id' => $dept->id,
            'specialization' => ['Neurosurgeon'],
            'license_number' => 'DOC12345',
            'status' => 'active'
        ]);
        $response->assertRedirect();
        $this->assertDatabaseHas('users', ['email' => 'strange@example.com']);
        // Store saves json_encoded array for specialization
        // $this->assertDatabaseHas('doctors', ['specialization' => '["Neurosurgeon"]']);
        // Or just check doctor exists
        $doctor = Doctor::where('license_number', 'DOC12345')->first();
        $this->assertNotNull($doctor);

        // Read
        $response = $this->get(route('doctors.index'));
        $response->assertStatus(200);
        $response->assertSee('Dr. Strange');

        // Update
        $response = $this->put(route('doctors.update', $doctor), [
            'name' => 'Dr. Strange Updated',
            'email' => 'strange@example.com',
            'phone' => '9876543210',
            'primary_department_id' => $dept->id,
            'specialization' => ['Master of Mystic Arts'],
            'license_number' => 'DOC12345',
            'status' => 'active'
        ]);
        $response->assertRedirect();
        $this->assertDatabaseHas('doctors', ['license_number' => 'DOC12345']);

        // Delete
        $response = $this->delete(route('doctors.destroy', $doctor));
        $response->assertRedirect();
        $this->assertSoftDeleted('doctors', ['id' => $doctor->id]);
    }

    /** @test */
    public function appointment_crud_works()
    {
        $this->actingAs($this->admin);

        $dept = Department::create(['clinic_id' => $this->clinic->id, 'name' => 'Ortho', 'status' => 'active']);
        $doctorUser = User::factory()->create(['clinic_id' => $this->clinic->id]);
        $doctor = Doctor::create([
            'user_id' => $doctorUser->id,
            'clinic_id' => $this->clinic->id,
            'primary_department_id' => $dept->id,
            'specialization' => 'Ortho'
        ]);

        $patient = Patient::create([
            'clinic_id' => $this->clinic->id,
            'name' => 'Jane Doe',
            'date_of_birth' => '1995-05-05',
            'gender' => 'female',
            'phone' => '5555555555',
            'address' => '456 Lane',
            'nid_number' => '9999999999999',
            'patient_code' => 'P-12345'
        ]);

        // Create
        $response = $this->post(route('appointments.store'), [
            'patient_id' => $patient->id,
            'doctor_id' => $doctor->id,
            'appointment_date' => now()->addDay()->format('Y-m-d'),
            'start_time' => '10:00',
            'type' => 'consultation'
        ]);
        $response->assertRedirect();
        $this->assertDatabaseHas('appointments', ['patient_id' => $patient->id]);

        $appointment = Appointment::where('patient_id', $patient->id)->first();

        // Read
        $response = $this->get(route('appointments.index'));
        $response->assertStatus(200);

        // Update
        $response = $this->put(route('appointments.update', $appointment), [
            'doctor_id' => $doctor->id,
            'appointment_date' => now()->addDays(2)->format('Y-m-d'),
            'start_time' => '11:00',
            'status' => 'pending',
            'type' => 'follow_up'
        ]);
        $response->assertRedirect();
        $this->assertDatabaseHas('appointments', ['start_time' => '11:00']);

        // Delete
        $response = $this->delete(route('appointments.destroy', $appointment));
        $response->assertRedirect();
        $this->assertSoftDeleted('appointments', ['id' => $appointment->id]);
    }

    /** @test */
    public function lab_test_crud_works()
    {
        $this->actingAs($this->admin);

        // Create
        $response = $this->post(route('lab.catalog.store'), [
            'name' => 'CBC',
            'category' => 'Hematology',
            'price' => 500,
            'status' => 'active',
            'description' => 'Complete Blood Count'
        ]);
        $response->assertRedirect();
        $this->assertDatabaseHas('lab_tests', ['name' => 'CBC']);

        $test = LabTest::where('name', 'CBC')->first();

        // Read
        $response = $this->get(route('lab.catalog.index'));
        $response->assertStatus(200);
        $response->assertSee('CBC');

        // Update
        $response = $this->put(route('lab.catalog.update', $test), [
            'name' => 'CBC Updated',
            'category' => 'Hematology',
            'price' => 550,
            'status' => 'inactive',
            'description' => 'Complete Blood Count Updated'
        ]);
        $response->assertRedirect();
        $this->assertDatabaseHas('lab_tests', ['name' => 'CBC Updated']);

        // Delete
        $response = $this->delete(route('lab.catalog.destroy', $test));
        $response->assertRedirect();
        $this->assertDatabaseMissing('lab_tests', ['id' => $test->id]);
    }

    /** @test */
    public function ipd_admission_crud_works()
    {
        $this->actingAs($this->admin);

        $doctor = Doctor::factory()->create([
            'clinic_id' => $this->clinic->id,
            'user_id' => User::factory()->create(['clinic_id' => $this->clinic->id])->id,
            'status' => 'active'
        ]);

        $patient = Patient::factory()->create(['clinic_id' => $this->clinic->id]);

        $ward = Ward::create([
            'clinic_id' => $this->clinic->id,
            'name' => 'General Ward',
            'type' => 'general',
            'status' => 'active'
        ]);

        $room = Room::create([
                'ward_id' => $ward->id,
                'room_number' => '101',
                'room_type' => 'general',
                'status' => 'available',
                'daily_rate' => 100
            ]);

            $bed = Bed::create([
                'room_id' => $room->id,
                'bed_number' => '101-A',
                'status' => 'available',
            ]);

        // Create Admission
        $response = $this->post(route('ipd.store'), [
            'patient_id' => $patient->id,
            'doctor_id' => $doctor->id,
            'admission_date' => now()->format('Y-m-d'),
            'admission_reason' => 'Severe fever',
            'bed_id' => $bed->id,
            'admission_fee' => 500,
            'deposit_amount' => 1000
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('admissions', ['patient_id' => $patient->id, 'status' => 'admitted']);

        // Check Bed Status
        $this->assertDatabaseHas('beds', ['id' => $bed->id, 'status' => 'occupied']);

        $admission = Admission::where('patient_id', $patient->id)->first();

        // View (Show)
        $response = $this->get(route('ipd.show', $admission));
        $response->assertStatus(200);

        // Delete (Soft Delete)
        $response = $this->delete(route('ipd.destroy', $admission));
        $response->assertRedirect();
        $this->assertSoftDeleted('admissions', ['id' => $admission->id]);
    }
}
