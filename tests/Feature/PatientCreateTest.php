<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Clinic;
use App\Models\Patient;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class PatientCreateTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        // Seed permissions if needed or just bypass
        $this->seed(); 
    }

    public function test_patient_can_be_created_with_profile_photo()
    {
        // Create a clinic
        $clinic = Clinic::create(['name' => 'Test Clinic', 'code' => 'TC', 'address' => 'Test Address']);
        
        // Create a user with permission
        $user = User::factory()->create([
            'clinic_id' => $clinic->id,
            'status' => 'active'
        ]);
        
        // Assign role/permissions (assuming Spatie)
        // Ensure user has 'create_patients' or similar permission if policy checks it
        // For now, let's assume Super Admin or Doctor role has access
        // Or mock Gate
        
        // Let's create a role 'Super Admin' which usually has all access
        $role = Role::firstOrCreate(['name' => 'Super Admin', 'guard_name' => 'web']);
        $user->assignRole($role);

        $this->actingAs($user);

        Storage::fake('public'); // This fakes the 'public' disk. 
        // NOTE: My controller uses public_path() and move(), which bypasses Storage facade.
        // So Storage::fake() might not help catch the file if I use move().
        // However, UploadedFile::fake()->image(...) creates a temp file.
        
        $file = UploadedFile::fake()->image('avatar.jpg');

        $response = $this->post(route('patients.store'), [
            'name' => 'Test Patient',
            'date_of_birth' => '1990-01-01',
            'gender' => 'male',
            'phone' => '1234567890',
            'address' => '123 Test St',
            'profile_photo' => $file,
        ]);

        $response->assertRedirect();
        
        // Check DB
        $patient = Patient::latest()->first();
        $this->assertNotNull($patient);
        $this->assertNotNull($patient->profile_photo);
        $this->assertStringContainsString('assets/img/patients', $patient->profile_photo);
    }
}
