# Class 52: OPD Workflow Testing

## Introduction
We need to test the full lifecycle: Book -> Visit -> Prescribe.

## 1. Feature Test
`tests/Feature/OpdFlowTest.php`

```php
public function test_full_opd_flow()
{
    // 1. Setup Data (Clinic, Doctor, Patient)
    // 2. Book Appointment
    $response = $this->post(route('appointments.store'), [
        'doctor_id' => $doctor->id,
        'date' => '2023-10-24',
        'start_time' => '09:00'
    ]);
    
    // 3. Start Consultation
    $appt = Appointment::first();
    $this->post(route('consultations.store', $appt), [
        'diagnosis' => 'Fever'
    ]);
    
    // 4. Create Prescription
    $consultation = Consultation::first();
    $this->post(route('prescriptions.store', $consultation), [
        'items' => [['medicine_name' => 'Napa']]
    ]);
    
    // 5. Assertions
    $this->assertEquals('completed', $appt->fresh()->status);
    $this->assertDatabaseHas('prescription_items', ['medicine_name' => 'Napa']);
}
```

## Summary
This test confirms that our modules (Scheduling, Patient, Clinical) work together harmoniously.

## Module 7 Completion
Congratulations! You have completed Module 7. You have built:
-   **Appointments**: Booking engine.
-   **Consultations**: Medical record creation.
-   **Prescriptions**: Digital RX.

In Module 8, we will build the **In-Patient Department (IPD)** system (Admissions, Beds, Discharge).
