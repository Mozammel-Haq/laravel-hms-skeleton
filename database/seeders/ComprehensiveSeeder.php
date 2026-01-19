<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Faker\Factory as Faker;
use App\Models\Clinic;
use App\Models\Department;
use App\Models\User;
use App\Models\Role;
use App\Models\Doctor;
use App\Models\DoctorEducation;
use App\Models\DoctorCertification;
use App\Models\DoctorAward;
use App\Models\DoctorSchedule;
use App\Models\DoctorScheduleException;
use App\Models\DoctorScheduleRequest;
use App\Models\Patient;
use App\Models\PatientAllergy;
use App\Models\PatientMedicalHistory;
use App\Models\PatientVital;
use App\Models\PatientComplaint;
use App\Models\Ward;
use App\Models\Room;
use App\Models\Bed;
use App\Models\Medicine;
use App\Models\LabTest;
use App\Models\Appointment;
use App\Models\AppointmentStatusLog;
use App\Models\Visit;
use App\Models\Consultation;
use App\Models\Prescription;
use App\Models\PrescriptionItem;
use App\Models\PharmacySale;
use App\Models\PharmacySaleItem;
use App\Models\LabTestOrder;
use App\Models\LabTestResult;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\Payment;
use App\Models\Admission;
use App\Models\AdmissionDeposit;
use App\Models\BedAssignment;
use App\Models\InpatientRound;
use App\Models\NursingNote;
use App\Models\InpatientService;
use App\Services\BillingService;

class ComprehensiveSeeder extends Seeder
{
    protected $faker;
    protected $billingService;

    public function run()
    {
        $this->faker = Faker::create();
        $this->billingService = new BillingService();

        // 1. Clinics
        $clinics = $this->seedClinics();

        foreach ($clinics as $clinic) {
            // 2. Departments
            $departments = $this->seedDepartments($clinic);

            // 3. Wards, Rooms, Beds
            $beds = $this->seedWardsAndBeds($clinic);

            // 4. Staff (Doctors, Nurses, Receptionists, Pharmacists, Lab Techs)
            $doctors = $this->seedStaff($clinic, $departments);

            // 5. Patients
            $patients = $this->seedPatients($clinic);

            // 6. Master Data (Medicines, Lab Tests, Complaints)
            $medicines = $this->seedMedicines($clinic);
            $labTests = $this->seedLabTests($clinic);
            $complaints = $this->seedPatientComplaints($clinic);

            // 7. Transactional Data (History over last 12 months)
            $this->seedTransactions($clinic, $doctors, $patients, $medicines, $labTests, $beds, $complaints);
        }
    }

    private function seedClinics()
    {
        $clinics = [];
        $names = ['CityCareLocation1', 'CityCareLocation2'];

        foreach ($names as $name) {
            $clinic = Clinic::firstOrCreate(
                ['code' => Str::slug($name)],
                [
                    'name' => $name,
                    'address_line_1' => $this->faker->address,
                    'city' => $this->faker->city,
                    'country' => 'Bangladesh',
                    'email' => strtolower($name) . '@citycare.com',
                    'phone' => $this->faker->phoneNumber,
                    'status' => 'active',
                    'timezone' => 'UTC +6',
                    'currency' => 'TK',
                    'opening_time' => '08:00:00',
                    'closing_time' => '22:00:00',
                ]
            );
            $clinics[] = $clinic;
        }

        // Ensure default clinic from DefaultClinicUserSeeder is also included if exists
        $defaultClinic = Clinic::where('email', 'dhcchms@citycare.com')->first();
        if ($defaultClinic && !collect($clinics)->contains('id', $defaultClinic->id)) {
            $clinics[] = $defaultClinic;
        }

        // Also include any other existing clinics (e.g. from UserSeeder)
        $existingClinics = Clinic::whereNotIn('id', collect($clinics)->pluck('id'))->get();
        foreach ($existingClinics as $existing) {
            $clinics[] = $existing;
        }

        return $clinics;
    }

    private function seedDepartments($clinic)
    {
        $names = ['Cardiology', 'Orthopedics', 'Pediatrics', 'Neurology', 'General Medicine', 'Dermatology'];
        $depts = [];
        foreach ($names as $name) {
            $depts[] = Department::firstOrCreate(
                ['name' => $name, 'clinic_id' => $clinic->id],
                ['description' => $name . ' Department', 'status' => 'active']
            );
        }
        return $depts;
    }

    private function seedWardsAndBeds($clinic)
    {
        $wards = [];
        $wardNames = ['General Ward', 'ICU', 'Private Ward', 'Emergency'];

        foreach ($wardNames as $name) {
            $ward = Ward::create([
                'clinic_id' => $clinic->id,
                'name' => $name,
                'type' => $name === 'ICU' ? 'icu' : 'general',
                'description' => $name . ' for patients',
                'status' => 'active'
            ]);
            $wards[] = $ward;

            // Rooms
            for ($i = 1; $i <= 5; $i++) {
                $room = Room::create([
                    'ward_id' => $ward->id,
                    'clinic_id' => $clinic->id,
                    'room_number' => strtoupper(substr($name, 0, 1)) . '-' . $i,
                    'room_type' => $name === 'ICU' ? 'ICU' : ($name === 'Private Ward' ? 'Private' : 'Shared'),
                    'daily_rate' => $name === 'ICU' ? 5000 : ($name === 'Private Ward' ? 2500 : 1000),
                    'status' => 'available'
                ]);

                // Beds
                for ($j = 1; $j <= 2; $j++) {
                    Bed::create([
                        'room_id' => $room->id,
                        'clinic_id' => $clinic->id,
                        'bed_number' => $room->room_number . '-' . $j,
                        'status' => 'available'
                    ]);
                }
            }
        }

        // Return available beds for later assignment
        return Bed::join('rooms', 'beds.room_id', '=', 'rooms.id')
            ->join('wards', 'rooms.ward_id', '=', 'wards.id')
            ->where('wards.clinic_id', $clinic->id)
            ->select('beds.*')
            ->get();
    }

    private function seedStaff($clinic, $departments)
    {
        // Use existing doctors instead of creating new ones
        $doctors = Doctor::with('user')->get();

        if ($doctors->isEmpty()) {
            // Fallback if no doctors exist (should not happen if UserSeeder runs first)
            return [];
        }

        // Attach existing doctors to this clinic so they can operate here
        foreach ($doctors as $doctor) {
            if (!$doctor->clinics()->where('clinic_id', $clinic->id)->exists()) {
                $doctor->clinics()->attach($clinic->id);
            }
        }

        return $doctors;
    }

    private function seedDoctorProfile($doctor, $clinic)
    {
        // Education
        DoctorEducation::create([
            'doctor_id' => $doctor->id,
            'degree' => 'MBBS',
            'institution' => 'Dhaka Medical College',
            'start_year' => 2010,
            'end_year' => 2015,
        ]);

        // Certifications
        if ($this->faker->boolean(60)) {
            DoctorCertification::create([
                'doctor_id' => $doctor->id,
                'title' => 'Board Certified',
                'issued_by' => 'BMDC',
                'issued_date' => Carbon::now()->subYears(5),
                'expiry_date' => Carbon::now()->addYears(5),
            ]);
        }

        // Awards
        if ($this->faker->boolean(30)) {
            DoctorAward::create([
                'doctor_id' => $doctor->id,
                'title' => 'Best Doctor of the Year',
                'year' => Carbon::now()->subYears(rand(1, 3))->year,
                'description' => 'Awarded by Medical Association'
            ]);
        }

        // Schedules (Mon-Fri)
        $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'];
        foreach ($days as $index => $day) {
            DoctorSchedule::create([
                'doctor_id' => $doctor->id,
                'clinic_id' => $clinic->id,
                'department_id' => $doctor->primary_department_id ?? 1,
                'day_of_week' => $index + 1, // 1=Monday
                'start_time' => '09:00:00',
                'end_time' => '17:00:00',
                'slot_duration_minutes' => 20,
                'status' => 'active'
            ]);
        }

        // Schedule Exceptions (Leave)
        if ($this->faker->boolean(20)) {
            $startDate = Carbon::now()->addDays(rand(10, 30));
            DoctorScheduleException::create([
                'doctor_id' => $doctor->id,
                'clinic_id' => $clinic->id,
                'start_date' => $startDate,
                'end_date' => $startDate->copy()->addDays(rand(0, 2)),
                'reason' => 'Personal Leave',
                'is_available' => false
            ]);
        }

        // Schedule Request
        if ($this->faker->boolean(10)) {
            DoctorScheduleRequest::create([
                'doctor_id' => $doctor->id,
                'clinic_id' => $clinic->id,
                'requested_by' => $doctor->user_id,
                'schedules' => json_encode([
                    [
                        'day_of_week' => 1,
                        'start_time' => '10:00',
                        'end_time' => '14:00'
                    ]
                ]),
                'status' => 'pending'
            ]);
        }
    }

    private function seedPatients($clinic)
    {
        $patients = [];
        for ($i = 0; $i < 50; $i++) {
            $name = $this->faker->name;
            $patient = Patient::create([
                'clinic_id' => $clinic->id,
                'name' => $name,
                'patient_code' => 'P-' . strtoupper(Str::random(6)),
                'date_of_birth' => $this->faker->date('Y-m-d', '-10 years'),
                'gender' => $this->faker->randomElement(['male', 'female']),
                'blood_group' => $this->faker->randomElement(['A+', 'B+', 'O+', 'AB+', 'A-', 'B-', 'O-', 'AB-']),
                'phone' => $this->faker->phoneNumber,
                'address' => $this->faker->address,
                'emergency_contact_name' => $this->faker->name,
                'emergency_contact_phone' => $this->faker->phoneNumber,
                'profile_photo' => 'assets/img/profile/' . Str::slug($name) . '.jpg',
            ]);
            $patients[] = $patient;
            $this->seedPatientHealthProfile($patient);
        }
        return $patients;
    }

    private function seedPatientHealthProfile($patient)
    {
        // Medical History
        PatientMedicalHistory::create([
            'patient_id' => $patient->id,
            'condition_name' => 'None',
            'diagnosed_date' => now(),
            'status' => 'Active',
            'notes' => 'Healthy'
        ]);

        // Allergy
        if ($this->faker->boolean(20)) {
            PatientAllergy::create([
                'patient_id' => $patient->id,
                'allergy_name' => 'Dust',
                'severity' => 'Mild',
                'notes' => 'Sneezing'
            ]);
        }
    }

    private function seedMedicines($clinic)
    {
        $medicines = [];
        $medNames = ['Paracetamol', 'Amoxicillin', 'Omeprazole', 'Metformin', 'Ibuprofen', 'Cetirizine', 'Azithromycin'];

        foreach ($medNames as $name) {
            $medicine = Medicine::firstOrCreate(
                ['name' => $name],
                [
                    'generic_name' => $name . ' Generic',
                    'manufacturer' => 'Square Pharma',
                    'dosage_form' => 'Tablet',
                    'strength' => '500mg',
                    'price' => rand(5, 50),
                    'status' => 'active'
                ]
            );

            // Add batch
            $medicine->batches()->create([
                'clinic_id' => $clinic->id,
                'batch_number' => 'B-' . strtoupper(Str::random(5)),
                'expiry_date' => Carbon::now()->addYear(),
                'quantity_in_stock' => 1000,
                'purchase_price' => $medicine->price * 0.8,
            ]);

            $medicines[] = $medicine;
        }
        return $medicines;
    }

    private function seedLabTests($clinic)
    {
        $tests = [];
        $testNames = ['CBC', 'Lipid Profile', 'Blood Sugar', 'X-Ray Chest', 'ECG', 'Liver Function Test'];

        foreach ($testNames as $name) {
            $tests[] = LabTest::firstOrCreate(
                ['name' => $name],
                [
                    'category' => 'Pathology',
                    'description' => 'Standard ' . $name,
                    'normal_range' => 'N/A',
                    'price' => rand(200, 2000),
                    'status' => 'active'
                ]
            );
        }
        return $tests;
    }

    private function seedPatientComplaints($clinic)
    {
        $complaints = [];
        $names = ['Fever', 'Headache', 'Stomach Pain', 'Cough', 'Cold', 'Dizziness'];

        foreach ($names as $name) {
            // Append clinic ID to name to ensure uniqueness if name is unique globally
            $uniqueName = $name . ' - ' . $clinic->name;
            $complaints[] = PatientComplaint::firstOrCreate(
                ['name' => $uniqueName],
                ['clinic_id' => $clinic->id]
            );
        }
        return $complaints;
    }

    private function seedTransactions($clinic, $doctors, $patients, $medicines, $labTests, $beds, $complaints)
    {
        // Generate data for the last 365 days
        for ($i = 0; $i < 100; $i++) {
            $date = Carbon::instance($this->faker->dateTimeBetween('-1 year', 'now'));
            $patient = $this->faker->randomElement($patients);
            $doctor = $this->faker->randomElement($doctors);

            // 1. Appointment
            $appointment = Appointment::create([
                'clinic_id' => $clinic->id,
                'patient_id' => $patient->id,
                'doctor_id' => $doctor->id,
                'department_id' => $doctor->primary_department_id ?? 1,
                'appointment_date' => $date->format('Y-m-d'),
                'start_time' => $date->format('H:i:s'),
                'end_time' => $date->copy()->addMinutes(20)->format('H:i:s'),
                'status' => 'completed',
                'appointment_type' => 'in_person',
                'reason_for_visit' => 'Regular Checkup',
                'booking_source' => 'reception',
                'fee' => $doctor->consultation_fee,
                'visit_type' => 'new',
                'created_by' => $doctor->user_id,
                'created_at' => $date,
                'updated_at' => $date
            ]);

            AppointmentStatusLog::create([
                'appointment_id' => $appointment->id,
                'old_status' => 'pending',
                'new_status' => 'completed',
                'changed_by' => 1, // System/Admin
                'changed_at' => $date
            ]);

            // 2. Visit
            $visit = Visit::create([
                'clinic_id' => $clinic->id,
                'appointment_id' => $appointment->id,
                'check_in_time' => Carbon::instance($date),
                'check_out_time' => Carbon::instance($date)->addMinutes(30),
                'visit_status' => 'completed',
                'created_at' => $date,
                'updated_at' => $date
            ]);

            // Vitals
            PatientVital::create([
                'visit_id' => $visit->id,
                'patient_id' => $patient->id,
                'recorded_at' => $date,
                'blood_pressure' => rand(110, 130) . '/' . rand(70, 90),
                'heart_rate' => rand(60, 100),
                'temperature' => 98.6,
                'weight' => rand(50, 90),
                'height' => 170,
                'bmi' => 22.5,
                'recorded_by' => 1
            ]);

            // 3. Consultation
            $consultation = Consultation::create([
                'clinic_id' => $clinic->id,
                'visit_id' => $visit->id,
                'doctor_id' => $doctor->id,
                'patient_id' => $patient->id,
                'symptoms' => 'Fever, Cough',
                'diagnosis' => 'Viral Flu',
                'doctor_notes' => 'Rest and fluids',
                'created_at' => $date,
                'updated_at' => $date
            ]);

            // 4. Consultation Invoice
            $invoice = $this->createInvoice(
                $clinic,
                $patient,
                $date,
                [['description' => 'Consultation Fee', 'amount' => $doctor->consultation_fee, 'type' => 'consultation', 'ref' => $consultation->id]],
                'consultation',
                $visit->id,
                $appointment->id
            );

            // 5. Prescription & Pharmacy Sale (50% chance)
            if ($this->faker->boolean(50)) {
                $prescription = Prescription::create([
                    'clinic_id' => $clinic->id,
                    'consultation_id' => $consultation->id,
                    'notes' => 'Take after meals',
                    'issued_at' => $date,
                    'created_at' => $date,
                    'updated_at' => $date
                ]);

                // Attach complaints
                $randomComplaints = $this->faker->randomElements($complaints, rand(1, 2));
                foreach ($randomComplaints as $complaint) {
                    $prescription->complaints()->attach($complaint->id);
                }

                $medItems = [];
                $saleItems = [];
                foreach ($this->faker->randomElements($medicines, rand(1, 3)) as $med) {
                    $qty = rand(5, 15);
                    PrescriptionItem::create([
                        'clinic_id' => $clinic->id,
                        'prescription_id' => $prescription->id,
                        'medicine_id' => $med->id,
                        'dosage' => '1 Tablet',
                        'frequency' => '1-0-1',
                        'duration_days' => 5,
                        'instructions' => 'After food'
                    ]);

                    $medItems[] = [
                        'description' => $med->name,
                        'amount' => $med->price * $qty,
                        'type' => 'medicine',
                        'ref' => $med->id,
                        'quantity' => $qty,
                        'unit_price' => $med->price
                    ];

                    $saleItems[] = [
                        'medicine_id' => $med->id,
                        'quantity' => $qty,
                        'price' => $med->price,
                        'total' => $med->price * $qty
                    ];
                }

                // Pharmacy Sale
                $totalSale = collect($saleItems)->sum('total');
                $sale = PharmacySale::create([
                    'clinic_id' => $clinic->id,
                    'prescription_id' => $prescription->id,
                    'patient_id' => $patient->id,
                    'sale_date' => $date,
                    'total_amount' => $totalSale,
                    'created_at' => $date,
                    'updated_at' => $date
                ]);

                foreach ($saleItems as $item) {
                    PharmacySaleItem::create([
                        'pharmacy_sale_id' => $sale->id,
                        'medicine_id' => $item['medicine_id'],
                        'quantity' => $item['quantity'],
                        'unit_price' => $item['price']
                    ]);
                }

                // Pharmacy Invoice
                $this->createInvoice(
                    $clinic,
                    $patient,
                    $date,
                    $medItems,
                    'pharmacy',
                    $visit->id,
                    $appointment->id
                );
            }

            // 6. Lab Tests (30% chance)
            if ($this->faker->boolean(30)) {
                $testItems = [];
                foreach ($this->faker->randomElements($labTests, rand(1, 2)) as $test) {
                    $order = LabTestOrder::create([
                        'clinic_id' => $clinic->id,
                        'appointment_id' => $appointment->id,
                        'doctor_id' => $doctor->id,
                        'patient_id' => $patient->id,
                        'order_date' => $date,
                        'status' => 'completed',
                        'created_at' => $date,
                        'updated_at' => $date
                    ]);

                    // Lab Result
                    LabTestResult::create([
                        'clinic_id' => $clinic->id,
                        'lab_test_order_id' => $order->id,
                        'lab_test_id' => $test->id,
                        'result_value' => 'Normal',
                        'unit' => 'N/A',
                        'reference_range' => 'N/A',
                        'remarks' => 'Result within normal range',
                        'reported_by' => 1, // Admin/Tech
                        'reported_at' => $date
                    ]);

                    $testItems[] = [
                        'description' => $test->name,
                        'amount' => $test->price,
                        'type' => 'lab',
                        'ref' => $order->id,
                        'quantity' => 1,
                        'unit_price' => $test->price
                    ];
                }

                // Lab Invoice
                $this->createInvoice(
                    $clinic,
                    $patient,
                    $date,
                    $testItems,
                    'lab',
                    $visit->id,
                    $appointment->id
                );
            }
        }

        // 7. IPD Admissions (Smaller set)
        for ($k = 0; $k < 10; $k++) {
            $admitDate = $this->faker->dateTimeBetween('-6 months', '-1 month');
            $patient = $this->faker->randomElement($patients);
            $doctor = $this->faker->randomElement($doctors);

            $admission = Admission::create([
                'clinic_id' => $clinic->id,
                'patient_id' => $patient->id,
                'admitting_doctor_id' => $doctor->id,
                'admission_date' => $admitDate,
                'status' => 'discharged',
                'admission_reason' => 'Severe condition',
                'created_at' => $admitDate,
                'updated_at' => $admitDate
            ]);

            // Deposit
            AdmissionDeposit::create([
                'clinic_id' => $clinic->id,
                'admission_id' => $admission->id,
                'amount' => 5000,
                'payment_method' => 'cash',
                'received_at' => $admitDate,
                'received_by' => 1
            ]);

            // Bed Assignment
            $bed = $beds->random();
            $assignment = BedAssignment::create([
                'clinic_id' => $clinic->id,
                'admission_id' => $admission->id,
                'bed_id' => $bed->id,
                'assigned_at' => $admitDate,
                'released_at' => Carbon::instance($admitDate)->addDays(rand(2, 7))
            ]);

            // Rounds
            InpatientRound::create([
                'clinic_id' => $clinic->id,
                'admission_id' => $admission->id,
                'doctor_id' => $doctor->id,
                'notes' => 'Improving',
                'round_date' => Carbon::instance($admitDate)->addDay()->format('Y-m-d'),
            ]);

            // Nursing Notes
            NursingNote::create([
                'clinic_id' => $clinic->id,
                'admission_id' => $admission->id,
                'nurse_id' => 1, // Fallback to admin/user
                'notes' => 'Vitals stable',
                'recorded_at' => Carbon::instance($admitDate)->addHours(4)
            ]);

            // Inpatient Services
            $services = [];
            if ($this->faker->boolean(50)) {
                $serviceDate = Carbon::instance($admitDate)->addDay();
                $service = InpatientService::create([
                    'admission_id' => $admission->id,
                    'service_name' => 'Oxygen Supply',
                    'service_date' => $serviceDate,
                    'quantity' => 2,
                    'unit_price' => 500,
                    'total_price' => 1000,
                ]);

                $services[] = [
                    'description' => 'Oxygen Supply',
                    'amount' => 1000,
                    'type' => 'service',
                    'ref' => $service->id,
                    'quantity' => 2,
                    'unit_price' => 500
                ];
            }

            // Discharge Invoice
            $days = Carbon::parse($assignment->assigned_at)->diffInDays($assignment->released_at);
            $dailyRate = $bed->room->daily_rate ?? 1000;
            $bedCharge = $days * $dailyRate;

            $invoiceItems = array_merge([
                [
                    'description' => 'Bed Charge (' . $days . ' days)',
                    'amount' => $bedCharge,
                    'type' => 'bed',
                    'ref' => $assignment->id,
                    'quantity' => $days,
                    'unit_price' => $dailyRate
                ]
            ], $services);

            $this->createInvoice(
                $clinic,
                $patient,
                Carbon::instance($admitDate)->addDays($days),
                $invoiceItems,
                'ipd_discharge',
                null,
                null,
                $admission->id
            );
        }
    }

    private function createInvoice($clinic, $patient, $date, $items, $type, $visitId = null, $appointmentId = null, $admissionId = null)
    {
        $subtotal = collect($items)->sum('amount');
        $discount = rand(0, 1) ? rand(10, 50) : 0;
        $taxPercent = 5;
        $taxAmount = ($subtotal - $discount) * ($taxPercent / 100);
        $total = $subtotal - $discount + $taxAmount;

        $invoice = Invoice::create([
            'clinic_id' => $clinic->id,
            'patient_id' => $patient->id,
            'visit_id' => $visitId,
            'appointment_id' => $appointmentId,
            'admission_id' => $admissionId,
            'invoice_number' => 'INV-' . strtoupper(Str::random(8)),
            'invoice_type' => $type,
            'subtotal' => $subtotal,
            'discount' => $discount,
            'tax' => $taxPercent,
            // 'tax_amount' => $taxAmount, // Ensure schema supports this if adding
            'total_amount' => $total,
            'status' => 'paid',
            'issued_at' => $date,
            'created_at' => $date,
            'updated_at' => $date,
        ]);

        foreach ($items as $item) {
            InvoiceItem::create([
                'invoice_id' => $invoice->id,
                'item_type' => $item['type'],
                'reference_id' => $item['ref'],
                'description' => $item['description'],
                'quantity' => $item['quantity'] ?? 1,
                'unit_price' => $item['unit_price'] ?? $item['amount'],
                'total_price' => $item['amount'],
                'created_at' => $date,
                'updated_at' => $date
            ]);
        }

        // Create Payment
        Payment::create([
            'invoice_id' => $invoice->id,
            'amount' => $total,
            'payment_method' => 'cash',
            'paid_at' => $date,
            'received_by' => 1, // Default admin
            'created_at' => $date,
            'updated_at' => $date
        ]);

        return $invoice;
    }
}
