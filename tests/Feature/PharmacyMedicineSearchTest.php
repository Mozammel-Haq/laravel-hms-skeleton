<?php

namespace Tests\Feature;

use App\Models\Clinic;
use App\Models\Medicine;
use App\Models\MedicineBatch;
use App\Models\User;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PharmacyMedicineSearchTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        \Illuminate\Database\Eloquent\Model::unguard();
    }

    public function test_pharmacist_can_search_active_medicines_with_stock()
    {
        $this->seed(RolePermissionSeeder::class);

        $clinic = Clinic::create([
            'name' => 'Main Clinic',
            'code' => 'MC001',
            'address_line_1' => '123 Main St',
            'city' => 'Test City',
            'country' => 'Testland',
            'timezone' => 'UTC',
            'currency' => 'USD',
            'phone' => '1234567890',
            'email' => 'clinic@test.com'
        ]);

        $user = User::factory()->create([
            'clinic_id' => $clinic->id,
        ]);
        $user->assignRole('Pharmacist');

        $med1 = Medicine::create([
            'name' => 'Amoxicillin',
            'generic_name' => 'Amoxicillin',
            'manufacturer' => 'PharmaCo',
            'strength' => '500mg',
            'dosage_form' => 'Capsule',
            'price' => 5.50,
            'status' => 'active',
        ]);
        $med2 = Medicine::create([
            'name' => 'Ibuprofen',
            'generic_name' => 'Ibuprofen',
            'manufacturer' => 'HealthCorp',
            'strength' => '200mg',
            'dosage_form' => 'Tablet',
            'price' => 2.00,
            'status' => 'active',
        ]);
        $inactive = Medicine::create([
            'name' => 'Old Drug',
            'generic_name' => 'Legacy',
            'manufacturer' => 'OldInc',
            'strength' => '10mg',
            'dosage_form' => 'Tablet',
            'price' => 1.00,
            'status' => 'inactive',
        ]);

        MedicineBatch::create([
            'clinic_id' => $clinic->id,
            'medicine_id' => $med1->id,
            'batch_number' => 'A1',
            'expiry_date' => now()->addMonths(6)->toDateString(),
            'quantity_in_stock' => 100,
            'purchase_price' => 3.00,
        ]);
        MedicineBatch::create([
            'clinic_id' => $clinic->id,
            'medicine_id' => $med2->id,
            'batch_number' => 'B1',
            'expiry_date' => now()->addMonths(3)->toDateString(),
            'quantity_in_stock' => 0, // out of stock
            'purchase_price' => 1.00,
        ]);
        MedicineBatch::create([
            'clinic_id' => $clinic->id,
            'medicine_id' => $inactive->id,
            'batch_number' => 'C1',
            'expiry_date' => now()->addMonths(12)->toDateString(),
            'quantity_in_stock' => 50,
            'purchase_price' => 0.50,
        ]);

        $response = $this->actingAs($user)->get(route('pharmacy.medicines.search', ['term' => 'amo']));
        $response->assertStatus(200);
        $json = $response->json();

        $this->assertArrayHasKey('results', $json);
        $names = collect($json['results'])->pluck('text')->implode(' | ');

        $this->assertStringContainsString('Amoxicillin', $names);
        $this->assertStringNotContainsString('Ibuprofen', $names); // out of stock filtered out
        $this->assertStringNotContainsString('Old Drug', $names); // inactive filtered out
    }
}

