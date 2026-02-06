# Class 69: Report Generation

## Introduction
Patients pay for the printed report.

## 1. PDF Generation
We use `barryvdh/laravel-dompdf`.

```bash
composer require barryvdh/laravel-dompdf
```

## 2. The Controller
```php
public function print(LabRequestItem $item)
{
    if ($item->status !== 'approved') {
        abort(403, 'Result not approved yet');
    }

    $results = $item->results()->with('parameter')->get();
    
    $pdf = Pdf::loadView('lab.reports.default', [
        'item' => $item,
        'patient' => $item->labRequest->patient,
        'results' => $results
    ]);
    
    return $pdf->stream('report.pdf');
}
```

## 3. The View
Standard letterhead layout.

```html
<!-- lab/reports/default.blade.php -->
<h1>{{ $item->labTest->name }}</h1>

<table>
    <thead>
        <tr>
            <th>Parameter</th>
            <th>Result</th>
            <th>Unit</th>
            <th>Ref. Range</th>
        </tr>
    </thead>
    <tbody>
        @foreach($results as $res)
        <tr>
            <td>{{ $res->parameter->name }}</td>
            <td>{{ $res->result_value }}</td>
            <td>{{ $res->parameter->unit }}</td>
            <td>{{ $res->parameter->reference_range }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
```

## Summary
The PDF is the final deliverable.
