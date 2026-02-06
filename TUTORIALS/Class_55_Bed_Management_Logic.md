# Class 55: Bed Management Logic

## Introduction
We need to efficiently find available beds and handle transfers.

## 1. Finding Beds
```php
// Room Scope
public function scopeAvailable($query)
{
    return $query->where('status', 'available');
}

// Controller
$wards = Ward::with(['rooms' => function($q) {
    $q->available();
}])->get();
```

## 2. Bed Transfer
When moving a patient from ICU to General Ward:
```php
public function transferBed(Admission $admission, $newBedId)
{
    DB::transaction(function() use ($admission, $newBedId) {
        // 1. Release old bed
        $currentAssignment = $admission->bedAssignments()->whereNull('released_at')->first();
        $currentAssignment->update(['released_at' => now()]);
        
        Room::where('id', $currentAssignment->bed_id)->update(['status' => 'available']);
        
        // 2. Assign new bed
        BedAssignment::create([
            'admission_id' => $admission->id,
            'bed_id' => $newBedId,
            'assigned_at' => now()
        ]);
        
        Room::where('id', $newBedId)->update(['status' => 'occupied']);
    });
}
```

## Summary
Correctly managing bed status is crucial for hospital capacity planning.
