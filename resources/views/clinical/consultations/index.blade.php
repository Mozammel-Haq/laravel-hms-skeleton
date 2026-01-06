<x-app-layout>
    @php
        $consultations = \App\Models\Consultation::with(['patient', 'doctor'])
            ->latest()
            ->take(50)
            ->get();
    @endphp
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h3 class="page-title mb-0">Consultations</h3>
            <a href="{{ route('appointments.index') }}" class="btn btn-outline-secondary">Appointments</a>
        </div>
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead>
                            <tr>
                                <th>Patient</th>
                                <th>Doctor</th>
                                <th>Date</th>
                                <th>Type</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($consultations as $c)
                                <tr>
                                    <td>{{ optional($c->patient)->full_name ?? 'Patient' }}</td>
                                    <td>{{ optional($c->doctor)->user->name ?? 'Doctor' }}</td>
                                    <td>{{ $c->created_at->format('Y-m-d H:i') }}</td>
                                    <td>{{ $c->type ?? 'OPD' }}</td>
                                    <td>
                                        <a href="{{ route('clinical.consultations.show', $c->id) }}"
                                            class="btn btn-sm btn-outline-primary">View</a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5">No consultations found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
