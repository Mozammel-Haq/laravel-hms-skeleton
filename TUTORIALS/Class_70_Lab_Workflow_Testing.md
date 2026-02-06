# Class 70: Lab Workflow Testing

## Introduction
Let's test the entire lifecycle: Request -> Sample -> Result -> Print.

## 1. The Test
`tests/Feature/Lab/LabFlowTest.php`.

```php
public function test_complete_lab_cycle()
{
    // 1. Setup Data
    $test = LabTest::factory()->create(['price' => 500]);
    $param = LabTestParameter::factory()->create(['lab_test_id' => $test->id]);
    $patient = Patient::factory()->create();
    
    // 2. Create Request
    $response = $this->post('/lab/requests', [
        'patient_id' => $patient->id,
        'tests' => [$test->id]
    ]);
    
    $item = LabRequestItem::first();
    $this->assertEquals('pending', $item->status);
    
    // 3. Collect Sample
    $this->post("/lab/items/{$item->id}/collect");
    $this->assertEquals('collected', $item->fresh()->status);
    
    // 4. Enter Result
    $this->post("/lab/items/{$item->id}/results", [
        'results' => [
            $param->id => '15.5'
        ]
    ]);
    $this->assertEquals('analyzed', $item->fresh()->status);
    $this->assertDatabaseHas('lab_results', ['result_value' => '15.5']);
    
    // 5. Approve
    $this->post("/lab/items/{$item->id}/approve");
    $this->assertEquals('approved', $item->fresh()->status);
}
```

## Summary
This test guarantees the medical diagnostic pipeline works correctly.

## Module 10 Completion
You have built a flexible **Laboratory Information System (LIS)**.

Next: **Module 11: Billing & Finance**.
