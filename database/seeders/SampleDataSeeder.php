<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use App\Models\Clinic;
use App\Models\Department;
use App\Models\User;
use App\Models\Doctor;
use App\Models\Patient;
use App\Models\Medicine;
use App\Models\MedicineBatch;
use App\Models\Ward;
use App\Models\Room;
use App\Models\Bed;
use App\Models\Appointment;
use App\Models\Visit;
use App\Models\Consultation;
use App\Models\Prescription;
use App\Models\PrescriptionItem;
use App\Models\PharmacySale;
use App\Models\PharmacySaleItem;
use App\Models\Admission;
use App\Models\BedAssignment;
use App\Models\Invoice;
use App\Models\Payment;
use App\Models\LabTest;
use App\Models\LabTestOrder;
use App\Models\LabTestResult;

class SampleDataSeeder extends Seeder
{
    public function run(): void
    {
        $clinic = Clinic::firstOrCreate(
            ['code' => 'DEMO-001'],
            [
                'name' => 'Demo General Hospital',
                'address_line_1' => '123 Health Ave',
                'city' => 'Metropolis',
                'country' => 'USA',
                'timezone' => 'UTC',
                'currency' => 'USD',
                'status' => 'active',
            ]
        );

        $clinicId = $clinic->id;

        $departments = [
            'General Medicine',
            'Cardiology',
            'Pediatrics',
            'Orthopedics',
        ];
        foreach ($departments as $deptName) {
            Department::firstOrCreate(
                ['clinic_id' => $clinicId, 'name' => $deptName],
                ['description' => $deptName . ' Department']
            );
        }

        $doctorUser = User::firstOrCreate(
            ['email' => 'doctor@hospital.com'],
            [
                'name' => 'Dr. John Doe',
                'password' => bcrypt('password'),
                'clinic_id' => $clinicId,
                'email_verified_at' => now(),
            ]
        );
        $doctor = Doctor::firstOrCreate(
            ['user_id' => $doctorUser->id],
            [
                'primary_department_id' => Department::where('clinic_id', $clinicId)->where('name', 'General Medicine')->value('id'),
                'specialization' => 'Internist',
                'license_number' => 'MD-' . rand(10000, 99999),
                'status' => 'active',
            ]
        );
        $doctor->clinics()->syncWithoutDetaching([$clinicId]);

        // Patients
        for ($i = 1; $i <= 15; $i++) {
            $code = 'P-' . str_pad((string)$i, 4, '0', STR_PAD_LEFT);
            Patient::firstOrCreate(
                ['patient_code' => $code],
                [
                    'clinic_id' => $clinicId,
                    'name' => 'Patient ' . $i,
                    'email' => 'patient' . $i . '@example.com',
                    'gender' => ($i % 2 ? 'male' : 'female'),
                    'phone' => '017' . str_pad((string)$i, 8, '0', STR_PAD_LEFT),
                    'status' => 'active',
                ]
            );
        }

        // Medicines
        $medNames = ['Amoxicillin', 'Paracetamol', 'Ibuprofen', 'Azithromycin', 'Omeprazole', 'Cetirizine', 'Metformin', 'Amlodipine'];
        foreach ($medNames as $name) {
            Medicine::firstOrCreate(
                ['name' => $name],
                [
                    'generic_name' => $name,
                    'manufacturer' => 'PharmaCo',
                    'strength' => '500mg',
                    'dosage_form' => 'Tablet',
                    'price' => rand(5, 50),
                    'status' => 'active',
                ]
            );
        }

        // Medicine Batches
        $medIds = Medicine::pluck('id')->all();
        foreach ($medIds as $medId) {
            MedicineBatch::firstOrCreate(
                ['medicine_id' => $medId, 'batch_number' => 'BATCH-' . $medId],
                [
                    'clinic_id' => $clinicId,
                    'expiry_date' => now()->addMonths(rand(6, 18)),
                    'quantity_in_stock' => rand(50, 200),
                    'purchase_price' => rand(3, 20),
                ]
            );
        }

        // IPD: Wards, Rooms, Beds
        $wardA = Ward::firstOrCreate(['name' => 'General Ward', 'clinic_id' => $clinicId], ['type' => 'general', 'status' => 'active']);
        $wardB = Ward::firstOrCreate(['name' => 'ICU', 'clinic_id' => $clinicId], ['type' => 'icu', 'status' => 'active']);
        $wards = [$wardA, $wardB];

        foreach ($wards as $ward) {
            for ($r = 1; $r <= 3; $r++) {
                $room = Room::firstOrCreate(
                    ['ward_id' => $ward->id, 'room_number' => $ward->name[0] . $r],
                    [
                        'room_type' => 'standard',
                        'daily_rate' => rand(50, 200),
                        'status' => 'available',
                    ]
                );
                for ($b = 1; $b <= 4; $b++) {
                    Bed::firstOrCreate(
                        ['room_id' => $room->id, 'bed_number' => $room->room_number . '-' . $b],
                        ['status' => 'available', 'clinic_id' => $clinicId, 'position' => $b]
                    );
                }
            }
        }

        // Appointments
        $patients = Patient::where('clinic_id', $clinicId)->take(10)->get();
        $deptId = Department::where('clinic_id', $clinicId)->where('name', 'General Medicine')->value('id');
        $adminUser = User::firstOrCreate(
            ['email' => 'admin@hospital.com'],
            [
                'name' => 'Clinic Administrator',
                'password' => bcrypt('password'),
                'clinic_id' => $clinicId,
                'email_verified_at' => now(),
            ]
        );
        foreach ($patients as $p) {
            Appointment::firstOrCreate(
                [
                    'clinic_id' => $clinicId,
                    'patient_id' => $p->id,
                    'doctor_id' => $doctor->id,
                    'department_id' => $deptId,
                    'appointment_date' => now()->toDateString(),
                    'start_time' => '10:00',
                ],
                [
                    'end_time' => '10:15',
                    'appointment_type' => 'in_person',
                    'booking_source' => 'reception',
                    'reason_for_visit' => 'Routine checkup',
                    'status' => 'confirmed',
                    'created_by' => $adminUser->id,
                ]
            );
        }

        // Visits and Consultations
        $appointments = Appointment::where('clinic_id', $clinicId)->get();
        foreach ($appointments as $a) {
            $visit = Visit::firstOrCreate(
                ['appointment_id' => $a->id],
                [
                    'clinic_id' => $clinicId,
                    'check_in_time' => now()->subMinutes(30),
                    'check_out_time' => now(),
                    'visit_status' => 'completed',
                ]
            );
            $consult = Consultation::firstOrCreate(
                ['visit_id' => $visit->id],
                [
                    'doctor_notes' => 'Patient examined. Stable.',
                    'diagnosis' => 'General malaise',
                    'follow_up_required' => false,
                    'created_at' => now(),
                ]
            );
            // Prescriptions
            $pres = Prescription::firstOrCreate(
                ['consultation_id' => $consult->id],
                [
                    'clinic_id' => $clinicId,
                    'issued_at' => now(),
                    'notes' => 'Take medicines after meals',
                ]
            );
            foreach (array_slice($medIds, 0, 3) as $mid) {
                PrescriptionItem::firstOrCreate(
                    ['prescription_id' => $pres->id, 'medicine_id' => $mid],
                    [
                        'dosage' => '1-0-1',
                        'frequency' => 'BID',
                        'duration_days' => 5,
                        'instructions' => 'After food',
                        'clinic_id' => $clinicId,
                    ]
                );
            }
            // Pharmacy Sales
            $sale = PharmacySale::firstOrCreate(
                ['prescription_id' => $pres->id, 'patient_id' => $a->patient_id, 'sale_date' => now()->toDateString()],
                ['clinic_id' => $clinicId, 'total_amount' => rand(50, 200), 'created_at' => now()]
            );
            foreach (array_slice($medIds, 0, 2) as $mid) {
                PharmacySaleItem::firstOrCreate(
                    ['pharmacy_sale_id' => $sale->id, 'medicine_id' => $mid],
                    ['quantity' => rand(1, 3), 'unit_price' => rand(5, 20)]
                );
            }
        }

        // Laboratory: Tests, Orders, Results
        $labTests = [
            ['name' => 'Complete Blood Count', 'category' => 'Hematology', 'price' => 20.00],
            ['name' => 'Blood Glucose', 'category' => 'Biochemistry', 'price' => 15.00],
            ['name' => 'Liver Function Test', 'category' => 'Biochemistry', 'price' => 35.00],
        ];
        foreach ($labTests as $lt) {
            LabTest::firstOrCreate(
                ['name' => $lt['name']],
                [
                    'category' => $lt['category'],
                    'description' => null,
                    'normal_range' => null,
                    'price' => $lt['price'],
                    'status' => 'active',
                ]
            );
        }
        $labTech = User::firstOrCreate(
            ['email' => 'lab@hospital.com'],
            [
                'name' => 'Lab Tech Mike',
                'password' => bcrypt('password'),
                'clinic_id' => $clinicId,
                'email_verified_at' => now(),
            ]
        );
        $firstAppointment = Appointment::where('clinic_id', $clinicId)->first();
        if ($firstAppointment) {
            $order = LabTestOrder::firstOrCreate(
                [
                    'appointment_id' => $firstAppointment->id,
                    'doctor_id' => $doctor->id,
                    'patient_id' => $firstAppointment->patient_id,
                    'order_date' => now()->toDateString(),
                ],
                ['status' => 'pending', 'clinic_id' => $clinicId]
            );
            $cbc = LabTest::where('name', 'Complete Blood Count')->first();
            if ($cbc) {
                LabTestResult::firstOrCreate(
                    ['lab_test_order_id' => $order->id, 'lab_test_id' => $cbc->id],
                    [
                        'result_value' => 'Normal',
                        'unit' => '',
                        'reference_range' => '',
                        'remarks' => 'No abnormalities detected',
                        'reported_by' => $labTech->id,
                        'reported_at' => now(),
                        'clinic_id' => $clinicId,
                    ]
                );
            }
        }

        // Admissions and Bed Assignments
        $bed = Bed::where('status', 'available')->first();
        $patient = Patient::where('clinic_id', $clinicId)->first();
        if ($bed && $patient) {
            $admission = Admission::firstOrCreate(
                [
                    'clinic_id' => $clinicId,
                    'patient_id' => $patient->id,
                    'admitting_doctor_id' => $doctor->id,
                    'admission_date' => now()->subDay(),
                ],
                [
                    'admission_reason' => 'Observation',
                    'current_bed_id' => $bed->id,
                    'status' => 'admitted',
                ]
            );
            BedAssignment::firstOrCreate(
                ['bed_id' => $bed->id, 'admission_id' => $admission->id],
                ['clinic_id' => $clinicId, 'assigned_at' => now()]
            );
        }

        // Billing: Invoices and Payments
        $firstPatient = Patient::where('clinic_id', $clinicId)->first();
        $firstAppt = Appointment::where('clinic_id', $clinicId)->first();
        $invoice = Invoice::firstOrCreate(
            ['invoice_number' => 'INV-' . Str::upper(Str::random(6))],
            [
                'clinic_id' => $clinicId,
                'patient_id' => optional($firstPatient)->id,
                'appointment_id' => optional($firstAppt)->id,
                'subtotal' => 150.00,
                'discount' => 0.00,
                'tax' => 0.00,
                'total_amount' => 150.00,
                'status' => 'unpaid',
                'created_at' => now(),
            ]
        );
        $accountantUser = User::firstOrCreate(
            ['email' => 'accountant@hospital.com'],
            [
                'name' => 'Accountant Tom',
                'password' => bcrypt('password'),
                'clinic_id' => $clinicId,
                'email_verified_at' => now(),
            ]
        );
        Payment::firstOrCreate(
            ['invoice_id' => $invoice->id, 'amount' => 50.00, 'payment_method' => 'cash'],
            [
                'transaction_reference' => 'TX-' . Str::upper(Str::random(8)),
                'paid_at' => now(),
                'received_by' => $accountantUser->id,
                'clinic_id' => $clinicId,
            ]
        );
    }
}
