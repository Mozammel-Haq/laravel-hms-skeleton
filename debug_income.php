<?php
$clinicId = 1;
echo "Total Invoices for Clinic 2: " . \App\Models\Invoice::where('clinic_id', $clinicId)->count() . "\n";
echo "Invoices with Appointment: " . \App\Models\Invoice::where('clinic_id', $clinicId)->whereNotNull('appointment_id')->count() . "\n";
echo "Invoices with Admission: " . \App\Models\Invoice::where('clinic_id', $clinicId)->whereNotNull('admission_id')->count() . "\n";

$invoice = \App\Models\Invoice::where('clinic_id', $clinicId)->whereNotNull('appointment_id')->first();
if ($invoice) {
    echo "Sample Invoice with Appointment ID: " . $invoice->id . "\n";
    $appointment = $invoice->appointment;
    if ($appointment) {
        echo "Linked Appointment ID: " . $appointment->id . "\n";
        $dept = $appointment->department;
        echo "Appointment Department: " . ($dept ? $dept->name : 'NULL') . "\n";
    } else {
        echo "Linked Appointment is NULL\n";
    }
}

$invoiceAdm = \App\Models\Invoice::where('clinic_id', $clinicId)->whereNotNull('admission_id')->first();
if ($invoiceAdm) {
    echo "Sample Invoice with Admission ID: " . $invoiceAdm->id . "\n";
    $admission = $invoiceAdm->admission;
    if ($admission) {
        echo "Linked Admission ID: " . $admission->id . "\n";
        $doctor = $admission->doctor;
        if ($doctor) {
            echo "Admitting Doctor ID: " . $doctor->id . "\n";
            $dept = $doctor->department; // Uses primary_department_id via model relation
            echo "Doctor Department: " . ($dept ? $dept->name : 'NULL') . "\n";
        } else {
             // Try manual lookup
             $docId = $admission->admitting_doctor_id;
             echo "Admitting Doctor ID (Manual): " . $docId . "\n";
             $doc = \App\Models\Doctor::find($docId);
             echo "Doctor Found: " . ($doc ? 'Yes' : 'No') . "\n";
        }
    } else {
        echo "Linked Admission is NULL\n";
    }
}
