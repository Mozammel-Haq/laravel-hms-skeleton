<x-app-layout>
    @push('styles')
        <style>
            @media print {

                .navbar-header,
                .sidebar,
                .footer {
                    display: none !important;
                }

                .page-wrapper {
                    margin-left: 0 !important;
                    padding: 0 !important;
                    width: 100% !important;
                }

                .main-wrapper {
                    padding: 0 !important;
                }

                .card {
                    border: none !important;
                    box-shadow: none !important;
                }

                .content {
                    padding: 0 !important;
                }

                body {
                    background-color: white !important;
                }
            }
        </style>
    @endpush

    <div class="content">

        <!-- start row -->
        <div class="row m-auto justify-content-center">
            <div class="col-lg-10">

                <!-- Page Header -->
                <div class="d-flex align-items-center justify-content-between mb-3 d-print-none">
                    <h6 class="fw-bold mb-0 d-flex align-items-center">
                        <a href="{{ route('clinical.prescriptions.index') }}">
                            <i class="ti ti-chevron-left me-1 fs-14"></i> Prescription
                        </a>
                    </h6>

                    <button onclick="window.print()" class="btn btn-sm btn-outline-primary d-print-none">
                        <i class="ti ti-printer me-1"></i> Print
                    </button>
                </div>

                <div class="card">
                    <div class="card-body">

                        <!-- Invoice Header -->
                        <div class="d-flex align-items-center justify-content-between border-bottom pb-3 mb-3">
                            <div class="invoice-logo">
                                <img src="{{ asset('assets/img/logo.svg') }}" class="logo-white" alt="logo">
                                <img src="{{ asset('assets/img/logo-white.svg') }}" class="logo-dark" alt="logo">
                            </div>
                            <span class="badge bg-success">Issued Prescription</span>
                        </div>

                        <!-- Clinic & Doctor -->
                        <div
                            class="d-flex align-items-center justify-content-between border-bottom pb-3 mb-3 flex-wrap gap-3">
                            <div class="d-flex gap-3">
                                <div class="avatar avatar-xxl rounded bg-light border p-2">
                                    <img src="{{ asset('assets/img/icons/trust-care.svg') }}" class="img-fluid">
                                </div>

                                <div>
                                    <h6 class="fw-semibold mb-1">
                                        {{ data_get($prescription, 'clinic.name', 'Clinic') }}
                                    </h6>

                                    <p class="mb-1">
                                        Dr. {{ data_get($prescription, 'doctor.user.name', 'Doctor') }}
                                    </p>

                                    <p class="mb-0">
                                        {{ data_get($prescription, 'doctor.department.name', 'Department') }}
                                    </p>
                                </div>
                            </div>

                            <div class="text-lg-end">
                                <p class="mb-1">
                                    Prescribed on :
                                    <span class="text-body">
                                        {{ optional($prescription->issued_at)->format('d M Y') ?? 'N/A' }}
                                    </span>
                                </p>

                                <p class="mb-0">
                                    Consultation :
                                    <span class="text-body">
                                        #{{ data_get($prescription, 'consultation_id', 'N/A') }}
                                    </span>
                                </p>
                            </div>
                        </div>

                        <!-- Patient Details -->
                        <div class="mb-4">
                            <h6 class="fs-14 fw-medium mb-2">Patient Details</h6>

                            <div class="px-3 py-2 bg-light rounded d-flex justify-content-between flex-wrap gap-2">
                                <div class="fw-semibold">
                                    {{ data_get($prescription, 'consultation.patient.name', 'Patient') }}
                                </div>

                                <div class="d-flex gap-3">
                                    <p class="mb-0">
                                        Age :
                                        {{ data_get($prescription, 'consultation.patient.age', 'N/A') }} yrs
                                    </p>
                                    <p class="mb-0">
                                        Blood :
                                        {{ data_get($prescription, 'consultation.patient.blood_group', 'N/A') }}
                                    </p>
                                </div>

                                <div>
                                    <p class="mb-0">
                                        Patient ID :
                                        #P-00{{ data_get($prescription, 'consultation.patient.id', 'N/A') }}
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="mb-4">
                            <h6 class="mb-2 fs-14 fw-medium">Vitals History (This Visit)</h6>
                            <div class="border rounded p-2">
                                @php
                                    $vitals = isset($vitalsHistory) ? $vitalsHistory : collect();
                                @endphp
                                @if ($vitals->isNotEmpty())
                                    <div class="table-responsive">
                                        <table class="table table-sm table-hover mb-0">
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
                                                @foreach ($vitals as $v)
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
                                @else
                                    <div class="text-muted">No vitals recorded for this visit.</div>
                                @endif
                            </div>
                        </div>

                        <!-- Complaints -->
                        <div class="mb-4">
                            <h6 class="fs-16 fw-bold mb-3">Patient Complaints</h6>

                            <ul class="ps-3">
                                @forelse($prescription->complaints as $complaint)
                                    <li>{{ $complaint->name }}</li>
                                @empty
                                    <li class="text-muted">No complaints recorded</li>
                                @endforelse
                            </ul>
                        </div>

                        <!-- Medicines -->
                        <div class="mb-4">
                            <h6 class="fs-16 fw-bold mb-3">Prescribed Medicines</h6>

                            <div class="table-responsive border bg-white">
                                <table class="table table-nowrap">
                                    <thead class="table-light">
                                        <tr>
                                            <th>#</th>
                                            <th>Medicine</th>
                                            <th>Dosage</th>
                                            <th>Frequency</th>
                                            <th>Duration</th>
                                            <th>Instructions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($prescription->items as $index => $item)
                                            <tr>
                                                <td>{{ $index + 1 }}</td>
                                                <td>{{ data_get($item, 'medicine.name', '-') }}</td>
                                                <td>{{ $item->dosage }}</td>
                                                <td>{{ $item->frequency }}</td>
                                                <td>{{ $item->duration_days }} days</td>
                                                <td>{{ $item->instructions ?? '-' }}</td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="6" class="text-center text-muted">
                                                    No medicines prescribed
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Follow-up & Notes -->
                        <div class="row border-bottom pb-3 mb-3">
                            <div class="col-lg-4">
                                <h6 class="fs-16 fw-bold mb-2">Follow Up</h6>
                                <p>
                                    {{ data_get($prescription, 'consultation.follow_up_required') ? 'Required' : 'Not Required' }}
                                </p>

                                <p>
                                    <strong>Diagnosis:</strong>
                                    {{ data_get($prescription, 'consultation.diagnosis', 'N/A') }}
                                </p>
                            </div>

                            <div class="col-lg-8">
                                <h6 class="fs-16 fw-bold mb-2">Notes / Advice</h6>
                                <p>{{ $prescription->notes ?? 'N/A' }}</p>
                            </div>
                        </div>

                        <!-- Signature -->
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="fs-14 fw-semibold">Terms and Conditions</h6>
                                <p class="mb-0">Medicines must be taken exactly as prescribed.</p>
                            </div>

                            <div class="text-end">
                                <img src="{{ asset('assets/img/icons/signature-img.svg') }}" class="img-fluid mb-1">
                                <h6 class="fs-14 fw-semibold mb-0">
                                    Dr. {{ data_get($prescription, 'consultation.doctor.user.name', 'Doctor') }}
                                </h6>
                                <p class="fs-13 mb-0">Authorized Physician</p>
                            </div>
                        </div>

                    </div>
                </div>

            </div>
        </div>
        <!-- end row -->

    </div>
</x-app-layout>
