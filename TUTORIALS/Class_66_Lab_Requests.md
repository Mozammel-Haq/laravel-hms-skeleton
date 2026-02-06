# Class 66: Lab Requests

## Introduction
Doctors order tests. This creates a `LabRequest`.

## 1. Migration
```php
Schema::create('lab_requests', function (Blueprint $table) {
    $table->id();
    $table->foreignId('clinic_id')->constrained();
    $table->foreignId('patient_id')->constrained();
    $table->foreignId('doctor_id')->nullable()->constrained(); // Who ordered it
    
    // Optional: Link to OPD or IPD
    $table->nullableMorphs('visit'); // appointment_id or admission_id
    
    $table->date('request_date');
    $table->string('status')->default('pending'); // pending, partial, completed
    $table->decimal('total_amount', 10, 2);
    
    $table->timestamps();
});

Schema::create('lab_request_items', function (Blueprint $table) {
    $table->id();
    $table->foreignId('lab_request_id')->constrained()->onDelete('cascade');
    $table->foreignId('lab_test_id')->constrained();
    
    $table->decimal('price', 10, 2);
    $table->string('status')->default('pending'); // pending, collected, analyzed, approved
    
    $table->timestamps();
});
```

## 2. Controller Logic
When a doctor orders tests (via Prescription or directly):

```php
public function store(Request $request)
{
    $labRequest = LabRequest::create([
        'patient_id' => $request->patient_id,
        'doctor_id' => auth()->id(), // Assuming doctor is logged in
        'request_date' => now(),
        'total_amount' => 0 // Calculated below
    ]);

    $total = 0;
    foreach ($request->tests as $testId) {
        $test = LabTest::find($testId);
        $labRequest->items()->create([
            'lab_test_id' => $test->id,
            'price' => $test->price
        ]);
        $total += $test->price;
    }

    $labRequest->update(['total_amount' => $total]);
}
```

## Summary
The request is the "Order". The items are the individual "Jobs".
