# Class 68: Result Entry

## Introduction
The core function: storing the medical data.

## 1. Migration: Lab Results
We need to store values for each parameter.

```php
Schema::create('lab_results', function (Blueprint $table) {
    $table->id();
    $table->foreignId('lab_request_item_id')->constrained()->onDelete('cascade');
    $table->foreignId('lab_test_parameter_id')->constrained();
    
    $table->string('result_value'); // "14.5", "Positive", "High"
    $table->text('remarks')->nullable();
    
    $table->timestamps();
});
```

## 2. Entering Results
The UI should show a form with inputs for each parameter defined in `LabTestParameter`.

```php
public function storeResults(Request $request, LabRequestItem $item)
{
    foreach ($request->results as $parameterId => $value) {
        LabResult::updateOrCreate(
            [
                'lab_request_item_id' => $item->id,
                'lab_test_parameter_id' => $parameterId
            ],
            ['result_value' => $value]
        );
    }
    
    $item->update(['status' => 'analyzed']);
}
```

## 3. Approval
A senior doctor/pathologist must approve the result before printing.

```php
public function approve(LabRequestItem $item)
{
    $item->update(['status' => 'approved']);
}
```

## Summary
We use an **EAV (Entity-Attribute-Value)** like structure here (`lab_results` table) because different tests have different fields.
