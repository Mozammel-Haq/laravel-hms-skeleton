<x-app-layout>
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h3 class="page-title mb-0">Rounds Management</h3>
            <a href="{{ route('ipd.index') }}" class="btn btn-outline-secondary">IPD Dashboard</a>
        </div>
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Patient</th>
                                <th>Doctor</th>
                                <th>Admitted On</th>
                                <th class="text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($admissions as $admission)
                                <tr>
                                    <td>
                                        <div class="fw-semibold">{{ optional($admission->patient)->name ?? 'Patient' }}</div>
                                        <div class="small text-muted">{{ $admission->patient->patient_code ?? '' }}</div>
                                    </td>
                                    <td>{{ optional($admission->doctor)->user->name ?? 'Doctor' }}</td>
                                    <td>{{ $admission->created_at->format('d M, Y H:i') }}</td>
                                    <td class="text-end">
                                        <a href="{{ route('ipd.rounds.create', $admission->id) }}" class="btn btn-sm btn-success me-1">
                                            <i class="ti ti-plus"></i> Add Round
                                        </a>
                                        <a href="{{ route('ipd.show', $admission->id) }}" class="btn btn-sm btn-outline-primary">
                                            <i class="ti ti-eye"></i> Review
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center text-muted">No admitted patients found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="mt-3">
                    {{ $admissions->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
