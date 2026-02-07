<x-app-layout>
    <div class="container-fluid p-4">

        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-body p-3">
                        <div class="d-flex justify-content-between align-items-center mb-2 bg-primary-subtle text-primary px-4 py-3 pt-3">
            <div>
                <h4 class="mb-1 text-dark fw-bold">
                    @if (isset($patient) && $patient)
                        Vitals History: {{ $patient->name }}
                    @else
                        Vitals History
                    @endif
                </h4>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-decoration-none">Dashboard</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Vitals History</li>
                    </ol>
                </nav>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('vitals.record') }}" class="btn btn-primary">
                    <i class="bi bi-plus-circle me-2"></i>Record Vitals
                </a>
            </div>
        </div>
        <hr>
                <!-- Filter Form (Optional - assuming there might be search later, keeping it simple for now or adding if route supports it) -->
                <!-- Assuming standard search might be available or just displaying the list -->

                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th class="border-0 rounded-start-2 ps-4">Patient</th>
                                <th class="border-0 text-center">Temp (°C)</th>
                                <th class="border-0 text-center">Pulse (bpm)</th>
                                <th class="border-0 text-center">BP (mmHg)</th>
                                <th class="border-0 text-center">Resp (bpm)</th>
                                <th class="border-0 text-center">Weight (kg)</th>
                                <th class="border-0 text-center">Height (cm)</th>
                                <th class="border-0 text-center">BMI</th>
                                <th class="border-0 text-center">SpO2 (%)</th>
                                <th class="border-0 text-end rounded-end-2 pe-4">Date Recorded</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($vitals as $vital)
                                <tr>
                                    <td class="ps-4">
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-sm rounded-circle bg-primary-subtle text-primary d-flex align-items-center justify-content-center me-3">
                                                <i class="bi bi-person-fill"></i>
                                            </div>
                                            <div>
                                                <h6 class="mb-0 fw-semibold text-dark">{{ $vital->patient?->name ?? 'Unknown' }}</h6>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-center">{{ $vital->temperature ?? '-' }}</td>
                                    <td class="text-center">{{ $vital->heart_rate ?? '-' }}</td>
                                    <td class="text-center">{{ $vital->blood_pressure ?? '-' }}</td>
                                    <td class="text-center">{{ $vital->respiratory_rate ?? '-' }}</td>
                                    <td class="text-center">{{ $vital->weight ?? '-' }}</td>
                                    <td class="text-center">{{ $vital->height ?? '-' }}</td>
                                    <td class="text-center">
                                        @if($vital->bmi)
                                            <span class="badge bg-info-subtle text-info border border-info-subtle rounded-pill">
                                                {{ $vital->bmi }}
                                            </span>
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td class="text-center">{{ $vital->spo2 ?? '-' }}</td>
                                    <td class="text-end pe-4">
                                        <div class="d-flex flex-column align-items-end">
                                            <span class="fw-medium text-dark">{{ optional($vital->recorded_at)->format('M d, Y') }}</span>
                                            <small class="text-muted">{{ optional($vital->recorded_at)->format('h:i A') }}</small>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="10" class="text-center py-5">
                                        <div class="d-flex flex-column align-items-center justify-content-center text-muted">
                                            <div class="bg-light rounded-circle p-4 mb-3">
                                                <i class="bi bi-clipboard2-pulse display-6 text-secondary"></i>
                                            </div>
                                            <h5 class="fw-semibold text-dark">No Vitals Recorded</h5>
                                            <p class="mb-0">Start by recording vitals for a patient.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-4">
                    {{ $vitals->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
