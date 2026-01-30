<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Prescription #{{ $prescription->id }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: white;
            font-family: sans-serif;
            color: #333;
            -webkit-print-color-adjust: exact;
        }

        .invoice-logo img {
            max-height: 60px;
        }

        @media print {
            .no-print {
                display: none !important;
            }

            .card {
                border: none !important;
                box-shadow: none !important;
            }
        }
    </style>
</head>

<body onload="window.print()">
    <div class="container py-4">
        <div class="no-print mb-3 text-end">
            <button onclick="window.print()" class="btn btn-primary">Print Prescription</button>
        </div>

        <div class="card border-0">
            <div class="card-body p-0">
                <!-- Header -->
                <div class="d-flex align-items-center justify-content-between border-bottom pb-3 mb-4">
                    <div class="invoice-logo">
                        @if (data_get($prescription, 'clinic.logo_path'))
                            <img src="{{ Storage::url($prescription->clinic->logo_path) }}" alt="Logo">
                        @else
                            <h3>{{ data_get($prescription, 'clinic.name', 'Clinic') }}</h3>
                        @endif
                    </div>
                    <div class="text-end">
                        <h4 class="text-success mb-1">Prescription</h4>
                        <small class="text-muted">#{{ $prescription->id }}</small>
                    </div>
                </div>

                <!-- Clinic & Doctor Info -->
                <div class="row mb-4">
                    <div class="col-6">
                        <h5 class="fw-bold">{{ data_get($prescription, 'clinic.name', 'Clinic Name') }}</h5>
                        <p class="mb-0 text-muted">{{ data_get($prescription, 'clinic.address', 'N/A') }}</p>
                        <p class="mb-0 text-muted">{{ data_get($prescription, 'clinic.phone', '') }}</p>
                    </div>
                    <div class="col-6 text-end">
                        <h5 class="fw-bold">Dr. {{ data_get($prescription, 'consultation.doctor.user.name', 'Doctor') }}
                        </h5>
                        <p class="mb-0 text-muted">
                            {{ data_get($prescription, 'consultation.doctor.department.name', 'Department') }}</p>
                        <p class="mb-0 text-muted">Date: {{ optional($prescription->issued_at)->format('d M Y') }}</p>
                    </div>
                </div>

                <!-- Patient Info -->
                <div class="bg-light p-3 rounded mb-4">
                    <div class="row">
                        <div class="col-md-6">
                            <span class="fw-bold">Patient Name:</span>
                            {{ data_get($prescription, 'consultation.patient.name', 'Patient') }}
                        </div>
                        <div class="col-md-3">
                            <span class="fw-bold">Age:</span>
                            {{ data_get($prescription, 'consultation.patient.age', '-') }}
                        </div>
                        <div class="col-md-3">
                            <span class="fw-bold">Gender:</span>
                            {{ ucfirst(data_get($prescription, 'consultation.patient.gender', '-')) }}
                        </div>
                    </div>
                </div>

                <!-- Vitals -->
                @if (isset($vitalsHistory) && $vitalsHistory->isNotEmpty())
                    <div class="mb-4">
                        <h6 class="fw-bold border-bottom pb-2">Vitals</h6>
                        <div class="table-responsive">
                            <table class="table table-sm table-bordered">
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Temp</th>
                                        <th>Pulse</th>
                                        <th>BP</th>
                                        <th>Resp</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($vitalsHistory as $v)
                                        <tr>
                                            <td>{{ $v->recorded_at?->format('d M Y H:i') }}</td>
                                            <td>{{ $v->temperature }}</td>
                                            <td>{{ $v->heart_rate }}</td>
                                            <td>{{ $v->blood_pressure }}</td>
                                            <td>{{ $v->respiratory_rate }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endif

                <!-- Complaints -->
                @if ($prescription->complaints->isNotEmpty())
                    <div class="mb-4">
                        <h6 class="fw-bold border-bottom pb-2">Complaints</h6>
                        <ul>
                            @foreach ($prescription->complaints as $complaint)
                                <li>{{ $complaint->name }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <!-- Medicines -->
                <div class="mb-4">
                    <h6 class="fw-bold border-bottom pb-2">Rx (Medicines)</h6>
                    <table class="table table-bordered">
                        <thead class="table-light">
                            <tr>
                                <th>Medicine</th>
                                <th>Dosage</th>
                                <th>Frequency</th>
                                <th>Duration</th>
                                <th>Instructions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($prescription->items as $item)
                                <tr>
                                    <td class="fw-bold">{{ data_get($item, 'medicine.name', '-') }}</td>
                                    <td>{{ $item->dosage }}</td>
                                    <td>{{ $item->frequency }}</td>
                                    <td>{{ $item->duration_days }} days</td>
                                    <td>{{ $item->instructions }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center">No medicines prescribed</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Notes & Advice -->
                @if ($prescription->notes)
                    <div class="mb-4">
                        <h6 class="fw-bold border-bottom pb-2">Advice / Notes</h6>
                        <p>{{ $prescription->notes }}</p>
                    </div>
                @endif

                <!-- Footer -->
                <div class="row mt-5 pt-4">
                    <div class="col-6">
                        <p class="text-muted small">Generated on {{ now()->format('d M Y H:i') }}</p>
                    </div>
                    <div class="col-6 text-end">
                        <div class="border-top border-dark d-inline-block pt-2" style="width: 200px;">
                            Signature
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</body>

</html>
