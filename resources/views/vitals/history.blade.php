<x-app-layout>
    <div class="container-fluid">

        <div class="card mt-2">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h3 class="page-title mb-0">
                        @if (isset($patient) && $patient)
                            Vitals History for {{ $patient->name }}
                        @else
                            Vitals History
                        @endif
                    </h3>
                    <a href="{{ route('vitals.record') }}" class="btn btn-outline-secondary">Record Vitals</a>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead>
                            <tr>
                                <th>Patient</th>
                                <th>Temperature</th>
                                <th>Pulse</th>
                                <th>BP</th>
                                <th>Resp Rate</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($vitals as $vital)
                                <tr>
                                    <td>{{ $vital->patient?->name ?? 'Patient' }}</td>
                                    <td>{{ $vital->temperature }}</td>
                                    <td>{{ $vital->heart_rate }}</td>
                                    <td>{{ $vital->blood_pressure }}</td>
                                    <td>{{ $vital->respiratory_rate }}</td>
                                    <td>{{ optional($vital->recorded_at)->format('d M Y H:i') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center text-muted">No vitals recorded.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="mt-3">
                    {{ $vitals->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
