<x-app-layout>
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h3 class="page-title mb-0">Consultation</h3>
            <a href="{{ route('clinical.prescriptions.index') }}" class="btn btn-outline-secondary">Prescriptions</a>
        </div>
        <div class="row g-3">
            <div class="col-lg-4">
                <div class="card h-100">
                    <div class="card-body">
                        <div class="fw-semibold mb-2">Patient</div>
                        <div class="d-flex align-items-center mb-3">
                            <span class="avatar avatar-lg rounded-circle me-3">
                                <img src="{{ asset('assets') }}/img/users/user-02.jpg" alt="img" class="rounded-circle">
                            </span>
                            <div>
                                <div class="h5 mb-0">{{ optional($consultation->patient)->full_name ?? optional($consultation->patient)->name ?? 'Patient' }}</div>
                                <div class="text-muted">#{{ optional($consultation->patient)->id }}</div>
                            </div>
                        </div>
                        <div class="fw-semibold mb-2">Doctor</div>
                        <div>{{ optional($consultation->doctor?->user)->name ?? 'Doctor' }}</div>
                    </div>
                </div>
            </div>
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-header bg-white fw-semibold">Details</div>
                    <div class="card-body">
                        <div class="row g-3 mb-3">
                            <div class="col-md-4">
                                <div class="text-muted">Symptoms</div>
                                <div class="fw-semibold">{{ $consultation->symptoms ?? 'N/A' }}</div>
                            </div>
                            <div class="col-md-4">
                                <div class="text-muted">Diagnosis</div>
                                <div class="fw-semibold">{{ $consultation->diagnosis ?? 'N/A' }}</div>
                            </div>
                            <div class="col-md-4">
                                <div class="text-muted">Notes</div>
                                <div class="fw-semibold">{{ $consultation->notes ?? 'N/A' }}</div>
                            </div>
                        </div>
                        <div class="fw-semibold mb-2">Prescription Items</div>
                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th>Medicine</th>
                                        <th>Dosage</th>
                                        <th>Duration</th>
                                        <th>Instruction</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse (optional($consultation->prescription)->items ?? [] as $item)
                                        <tr>
                                            <td>{{ optional($item->medicine)->name ?? 'Medicine' }}</td>
                                            <td>{{ $item->dosage ?? 'N/A' }}</td>
                                            <td>{{ $item->duration ?? 'N/A' }}</td>
                                            <td>{{ $item->instruction ?? 'N/A' }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="text-center text-muted">No items</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
