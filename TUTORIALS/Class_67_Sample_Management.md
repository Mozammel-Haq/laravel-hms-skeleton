# Class 67: Sample Management

## Introduction
Before a test can be run, a sample (blood, urine, etc.) must be collected.

## 1. The Workflow
1.  **Pending**: Request created.
2.  **Sample Collected**: Phlebotomist takes the sample.
3.  **Analyzed**: Lab technician enters results.
4.  **Approved**: Pathologist verifies results.

## 2. Sample Collection Action
In `LabRequestController`:

```php
public function collectSample(LabRequestItem $item)
{
    // Update status
    $item->update(['status' => 'collected']);
    
    // Optional: Generate barcode for the sample tube
    // $barcode = generateBarcode($item->id);
    
    return back()->with('success', 'Sample collected');
}
```

## 3. Bulk Collection
Often, one blood draw covers multiple tests.

```php
public function bulkCollect(LabRequest $labRequest)
{
    $labRequest->items()
               ->where('status', 'pending')
               ->update(['status' => 'collected']);
}
```

## Summary
Tracking sample collection status helps identify bottlenecks (e.g., patient hasn't gone to the lab yet).
