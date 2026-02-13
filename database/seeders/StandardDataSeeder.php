<?php

namespace Database\Seeders;

use App\Models\Bed;
use App\Models\Clinic;
use App\Models\Department;
use App\Models\LabTest;
use App\Models\Medicine;
use App\Models\Room;
use App\Models\Ward;
use Illuminate\Database\Seeder;

class StandardDataSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Ensure a Clinic Exists
        $clinic = Clinic::firstOrCreate(
            ['code' => 'DMC-001'],
            [
                'name' => 'Dhaka Medical Center',
                'address_line_1' => '123 Hospital Road',
                'city' => 'Dhaka',
                'country' => 'Bangladesh',
                'timezone' => 'Asia/Dhaka',
                'currency' => 'BDT',
                'status' => 'active',
            ]
        );

        // 2. Create Standard Departments
        $departments = [
            ['name' => 'Cardiology', 'description' => 'Heart and cardiovascular system'],
            ['name' => 'Orthopedics', 'description' => 'Bones, joints, ligaments, tendons, and muscles'],
            ['name' => 'Pediatrics', 'description' => 'Medical care of infants, children, and adolescents'],
            ['name' => 'General Surgery', 'description' => 'Surgical treatment of abdominal contents'],
            ['name' => 'Internal Medicine', 'description' => 'Prevention, diagnosis, and treatment of adult diseases'],
            ['name' => 'Neurology', 'description' => 'Disorders of the nervous system'],
            ['name' => 'Gynecology', 'description' => 'Female reproductive system'],
            ['name' => 'Dermatology', 'description' => 'Skin, hair, and nail conditions'],
        ];

        foreach ($departments as $deptData) {
            Department::firstOrCreate(
                ['name' => $deptData['name'], 'clinic_id' => $clinic->id],
                $deptData
            );
        }

        // 3. Create Wards, Rooms, and Beds
        $wards = [
            ['name' => 'General Ward (Male)', 'type' => 'general', 'floor' => 2],
            ['name' => 'General Ward (Female)', 'type' => 'general', 'floor' => 2],
            ['name' => 'ICU', 'type' => 'icu', 'floor' => 3],
            ['name' => 'VIP Cabin', 'type' => 'cabin', 'floor' => 4],
        ];

        foreach ($wards as $wardData) {
            $ward = Ward::firstOrCreate(
                ['name' => $wardData['name'], 'clinic_id' => $clinic->id],
                $wardData
            );

            // Create 2 Rooms per Ward
            for ($i = 1; $i <= 2; $i++) {
                $room = Room::create([
                    'clinic_id' => $clinic->id,
                    'ward_id' => $ward->id,
                    'room_number' => strtoupper(substr($ward->name, 0, 3)).'-'.$ward->floor.'0'.$i,
                    'room_type' => $ward->type, // Correct column name
                    'status' => 'available',
                    'daily_rate' => $ward->type === 'icu' ? 5000 : ($ward->type === 'cabin' ? 3000 : 1000), // Correct column name
                ]);

                // Create 2 Beds per Room
                for ($j = 1; $j <= 2; $j++) {
                    Bed::create([
                        'clinic_id' => $clinic->id,
                        'room_id' => $room->id,
                        'bed_number' => $room->room_number.'-'.chr(64 + $j), // e.g., GEN-201-A
                        'status' => 'available',
                    ]);
                }
            }
        }

        // 4. Create Medicines
        $medicines = [
            ['name' => 'Napa Extra', 'generic_name' => 'Paracetamol', 'dosage_form' => 'tablet', 'strength' => '500mg', 'price' => 2.50],
            ['name' => 'Seclo', 'generic_name' => 'Omeprazole', 'dosage_form' => 'capsule', 'strength' => '20mg', 'price' => 5.00],
            ['name' => 'Maxpro', 'generic_name' => 'Esomeprazole', 'dosage_form' => 'capsule', 'strength' => '20mg', 'price' => 7.00],
            ['name' => 'Cef-3', 'generic_name' => 'Cefixime', 'dosage_form' => 'capsule', 'strength' => '200mg', 'price' => 35.00],
            ['name' => 'Tylace', 'generic_name' => 'Aceclofenac', 'dosage_form' => 'tablet', 'strength' => '100mg', 'price' => 4.00],
            ['name' => 'Alarid', 'generic_name' => 'Cetirizine', 'dosage_form' => 'tablet', 'strength' => '10mg', 'price' => 3.00],
            ['name' => 'Flagyl', 'generic_name' => 'Metronidazole', 'dosage_form' => 'tablet', 'strength' => '400mg', 'price' => 2.00],
            ['name' => 'Azithrocin', 'generic_name' => 'Azithromycin', 'dosage_form' => 'tablet', 'strength' => '500mg', 'price' => 30.00],
            ['name' => 'Monas', 'generic_name' => 'Montelukast', 'dosage_form' => 'tablet', 'strength' => '10mg', 'price' => 15.00],
            ['name' => 'Panthonix', 'generic_name' => 'Pantoprazole', 'dosage_form' => 'tablet', 'strength' => '20mg', 'price' => 6.00],
        ];

        foreach ($medicines as $med) {
            Medicine::firstOrCreate(
                ['name' => $med['name']],
                array_merge($med, ['manufacturer' => 'Beximco/Square', 'status' => 'active'])
            );
        }

        // 5. Create Lab Tests
        $labTests = [
            ['name' => 'CBC (Complete Blood Count)', 'category' => 'Hematology', 'price' => 400],
            ['name' => 'Lipid Profile', 'category' => 'Biochemistry', 'price' => 1200],
            ['name' => 'Liver Function Test', 'category' => 'Biochemistry', 'price' => 1000],
            ['name' => 'Kidney Function Test', 'category' => 'Biochemistry', 'price' => 1000],
            ['name' => 'Blood Sugar (Fasting)', 'category' => 'Biochemistry', 'price' => 150],
            ['name' => 'Blood Sugar (2hPP)', 'category' => 'Biochemistry', 'price' => 150],
            ['name' => 'X-Ray Chest PA View', 'category' => 'Radiology', 'price' => 600],
            ['name' => 'ECG', 'category' => 'Cardiology', 'price' => 500],
            ['name' => 'Urine R/E', 'category' => 'Pathology', 'price' => 250],
            ['name' => 'USG Whole Abdomen', 'category' => 'Radiology', 'price' => 1500],
        ];

        foreach ($labTests as $test) {
            LabTest::firstOrCreate(
                ['name' => $test['name']],
                array_merge($test, ['description' => $test['name'], 'status' => 'active'])
            );
        }

        // 6. Create Users & Staff (Moved to UserSeeder)
        // Users are now handled in UserSeeder.php
    }
}
