<x-app-layout>
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h3 class="page-title mb-0">Prescription #{{ $prescription->id }}</h3>
            <a href="{{ route('clinical.prescriptions.print', $prescription) }}" class="btn btn-outline-primary">Print</a>
        </div>
        <div class="row g-3">
            <div class="col-lg-4">
                <div class="card h-100">
                    <div class="card-body">
                        <div class="fw-semibold mb-2">Patient</div>
                        <div>{{ optional($prescription->consultation->patient)->full_name ?? optional($prescription->consultation->patient)->name ?? 'Patient' }}</div>
                        <div class="fw-semibold mt-3 mb-2">Doctor</div>
                        <div>{{ optional($prescription->consultation->doctor?->user)->name ?? 'Doctor' }}</div>
                        <div class="fw-semibold mt-3 mb-2">Issued</div>
                        <div>{{ isset($prescription->issued_date) ? \Illuminate\Support\Carbon::parse($prescription->issued_date)->format('Y-m-d') : $prescription->created_at->format('Y-m-d') }}</div>
                        <div class="fw-semibold mt-3 mb-2">Status</div>
                        <span class="badge bg-secondary">{{ $prescription->status ?? 'active' }}</span>
                    </div>
                </div>
            </div>
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-header bg-white fw-semibold">Items</div>
                    <div class="card-body">
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
                                    @forelse ($prescription->items as $item)
                                        <tr>
                                            <td>{{ optional($item->medicine)->name ?? 'Medicine' }}</td>
                                            <td>{{ $item->dosage ?? 'N/A' }}</td>
                                            <td>{{ $item->duration ?? 'N/A' }}</td>
                                            <td>{{ $item->instruction ?? '—' }}</td>
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
