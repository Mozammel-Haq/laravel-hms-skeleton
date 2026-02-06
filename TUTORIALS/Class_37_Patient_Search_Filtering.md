# Class 37: Patient Search & Filtering

## Introduction
In a busy hospital, finding the right "John Smith" quickly is essential. We need a robust search API.

## 1. The Search Logic
We can use Laravel Scout (advanced) or simple Eloquent `where` clauses (sufficient for now).

Create `PatientSearchController.php`:

```php
public function search(Request $request)
{
    $query = $request->get('q');
    
    // We search across global patients but filter by what the user is allowed to see.
    // However, usually, a receptionist wants to find a patient who is ALREADY in the system
    // even if not registered at THIS clinic yet (to import them).
    
    $patients = Patient::withoutGlobalScope('clinic_access')
        ->where('first_name', 'like', "%{$query}%")
        ->orWhere('last_name', 'like', "%{$query}%")
        ->orWhere('phone', 'like', "%{$query}%")
        ->orWhere('nid', 'like', "%{$query}%")
        ->limit(20)
        ->get();
        
    // Format for frontend (e.g., Select2 or Autocomplete)
    return response()->json($patients->map(function($p) {
        return [
            'id' => $p->id,
            'text' => "{$p->name} ({$p->phone})",
            'dob' => $p->dob->format('Y-m-d')
        ];
    }));
}
```

## 2. Frontend Integration
Using a library like **Select2** or a custom AlpineJS component makes this seamless.

```html
<select id="patient-search" class="form-control"></select>

<script>
$('#patient-search').select2({
    ajax: {
        url: '/api/patients/search',
        dataType: 'json'
    }
});
</script>
```

## Summary
Fast search reduces waiting times at the reception desk. By indexing the `phone` and `nid` columns in our migration (Class 31), we ensured this query remains fast even with millions of records.
