<?php

namespace Database\Seeders;

use App\Models\Clinic;
use App\Models\Service;
use Illuminate\Database\Seeder;

class ServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $services = [
            ['name' => 'Nursing Charge (Daily)', 'price' => 500, 'description' => 'Daily nursing care charge'],
            ['name' => 'Oxygen Supply (Hourly)', 'price' => 200, 'description' => 'Oxygen cylinder or central line usage'],
            ['name' => 'Nebulization', 'price' => 150, 'description' => 'Per session'],
            ['name' => 'ECG', 'price' => 800, 'description' => 'Electrocardiogram test'],
            ['name' => 'Dressing Charge (Minor)', 'price' => 300, 'description' => 'Minor wound dressing'],
            ['name' => 'Dressing Charge (Major)', 'price' => 1000, 'description' => 'Major wound dressing / post-op'],
            ['name' => 'OT Charge (General)', 'price' => 5000, 'description' => 'Operation Theatre usage'],
            ['name' => 'Anesthesia Charge', 'price' => 3000, 'description' => 'Anesthesiologist fee'],
            ['name' => 'Physiotherapy Session', 'price' => 800, 'description' => 'Per session'],
        ];

        $clinics = Clinic::all();

        foreach ($clinics as $clinic) {
            foreach ($services as $service) {
                Service::firstOrCreate(
                    [
                        'clinic_id' => $clinic->id,
                        'name' => $service['name'],
                    ],
                    [
                        'price' => $service['price'],
                        'description' => $service['description'],
                        'status' => 'active',
                    ]
                );
            }
        }
    }
}
