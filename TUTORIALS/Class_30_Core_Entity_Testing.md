# Class 30: Core Entity Testing

## Introduction
We built Departments, Wards, Staff, and Doctors. Let's test them.

## 1. Feature Test
Run: `php artisan make:test CoreEntityTest`

```php
class CoreEntityTest extends TestCase
{
    use RefreshDatabase;

    public function test_doctor_can_be_created()
    {
        // 1. Setup Tenant
        $clinic = Clinic::factory()->create();
        TenantContext::setClinicId($clinic->id);

        // 2. Create Dept
        $dept = Department::create(['name' => 'Cardiology', 'status' => 'active']);

        // 3. Create Doctor via Controller (Simulate Request)
        // Or directly via Model for unit testing
        
        $user = User::factory()->create(['clinic_id' => $clinic->id]);
        
        $doctor = Doctor::create([
            'user_id' => $user->id,
            'department_id' => $dept->id,
            'specialization' => 'Heart',
            'consultation_fee' => 500
        ]);

        // 4. Assert
        $this->assertDatabaseHas('doctors', [
            'specialization' => 'Heart',
            'clinic_id' => $clinic->id // Should be auto-set
        ]);
    }

    public function test_tenant_isolation_for_doctors()
    {
        // Create Clinic A and Doctor A
        $clinicA = Clinic::factory()->create();
        TenantContext::setClinicId($clinicA->id);
        $userA = User::factory()->create(['clinic_id' => $clinicA->id]);
        Doctor::create(['user_id' => $userA->id, 'specialization' => 'Doc A']);

        // Create Clinic B and Doctor B
        $clinicB = Clinic::factory()->create();
        TenantContext::setClinicId($clinicB->id);
        $userB = User::factory()->create(['clinic_id' => $clinicB->id]);
        Doctor::create(['user_id' => $userB->id, 'specialization' => 'Doc B']);

        // Switch back to A
        TenantContext::setClinicId($clinicA->id);
        
        // Assert we only see Doc A
        $this->assertEquals(1, Doctor::count());
        $this->assertEquals('Doc A', Doctor::first()->specialization);
    }
}
```

## Summary
We verified that our core entities respect the Multi-Tenancy rules we established in Module 2.

## Module 4 Completion
Congratulations! You have completed Module 4. You now have:
-   **Departments**: Organizational units.
-   **Wards/Rooms**: Physical locations.
-   **Doctors**: Complex profiles with related data.
-   **Dashboards**: Role-specific views.

In Module 5, we will build the **Patient Management System**.
