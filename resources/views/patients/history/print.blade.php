<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Medical History - {{ $patient->name }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: white; font-family: sans-serif; color: #333; -webkit-print-color-adjust: exact; }
        .invoice-logo img { max-height: 60px; }
        @media print { .no-print { display: none !important; } .card { border: none !important; box-shadow: none !important; } }
    </style>
</head>
<body onload="window.print()">
    <div class="container py-4">
        <div class="no-print mb-3 text-end">
            <button onclick="window.print()" class="btn btn-primary">Print Medical History</button>
        </div>

        <div class="card border-0">
            <div class="card-body p-0">
                <!-- Header -->
                <div class="d-flex align-items-center justify-content-between border-bottom pb-3 mb-4">
                    <div class="invoice-logo">
                        <h3>Medical History Record</h3>
                    </div>
                    <div class="text-end">
                        <h4 class="text-primary mb-1">{{ $patient->name }}</h4>
                        <small class="text-muted">ID: {{ $patient->id }}</small>
                    </div>
                </div>

                <!-- Patient Info -->
                <div class="bg-light p-3 rounded mb-4">
                    <div class="row">
                        <div class="col-md-4"><span class="fw-bold">Age:</span> {{ $patient->age }}</div>
                        <div class="col-md-4"><span class="fw-bold">Gender:</span> {{ ucfirst($patient->gender) }}</div>
                        <div class="col-md-4"><span class="fw-bold">Blood Group:</span> {{ $patient->blood_group ?? 'N/A' }}</div>
                    </div>
                </div>

                <!-- Conditions -->
                @if($patient->medicalHistory->isNotEmpty())
                <div class="mb-4">
                    <h5 class="fw-bold border-bottom pb-2">Medical Conditions</h5>
                    <table class="table table-bordered table-striped">
                        <thead><tr><th>Condition</th><th>Diagnosed Date</th><th>Status</th><th>Doctor</th></tr></thead>
                        <tbody>
                            @foreach($patient->medicalHistory as $item)
                            <tr>
                                <td>{{ $item->condition_name }}</td>
                                <td>{{ $item->diagnosed_date ? $item->diagnosed_date->format('d M Y') : '-' }}</td>
                                <td>{{ $item->status }}</td>
                                <td>{{ $item->doctor_name }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @endif

                <!-- Allergies -->
                @if($patient->allergies->isNotEmpty())
                <div class="mb-4">
                    <h5 class="fw-bold border-bottom pb-2">Allergies</h5>
                    <table class="table table-bordered table-striped">
                        <thead><tr><th>Allergen</th><th>Severity</th><th>Notes</th></tr></thead>
                        <tbody>
                            @foreach($patient->allergies as $item)
                            <tr>
                                <td>{{ $item->allergy_name }}</td>
                                <td>{{ $item->severity }}</td>
                                <td>{{ $item->notes }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @endif

                <!-- Surgeries -->
                @if($patient->surgeries->isNotEmpty())
                <div class="mb-4">
                    <h5 class="fw-bold border-bottom pb-2">Surgeries</h5>
                    <table class="table table-bordered table-striped">
                        <thead><tr><th>Surgery</th><th>Date</th><th>Hospital</th><th>Surgeon</th></tr></thead>
                        <tbody>
                            @foreach($patient->surgeries as $item)
                            <tr>
                                <td>{{ $item->surgery_name }}</td>
                                <td>{{ $item->surgery_date ? $item->surgery_date->format('d M Y') : '-' }}</td>
                                <td>{{ $item->hospital_name }}</td>
                                <td>{{ $item->surgeon_name }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @endif

                <!-- Immunizations -->
                @if($patient->immunizations->isNotEmpty())
                <div class="mb-4">
                    <h5 class="fw-bold border-bottom pb-2">Immunizations</h5>
                    <table class="table table-bordered table-striped">
                        <thead><tr><th>Vaccine</th><th>Date</th><th>Provider</th></tr></thead>
                        <tbody>
                            @foreach($patient->immunizations as $item)
                            <tr>
                                <td>{{ $item->vaccine_name }}</td>
                                <td>{{ $item->immunization_date ? $item->immunization_date->format('d M Y') : '-' }}</td>
                                <td>{{ $item->provider_name }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @endif

                <div class="mt-5 text-center text-muted small">
                    <p>Generated on {{ now()->format('d M Y h:i A') }}</p>
                </div>
            </div>
        </div>
    </div>
</body>
</html>