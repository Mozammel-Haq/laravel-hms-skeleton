<x-app-layout>
    <h5 class="mb-3">Visits</h5>
    <div class="card">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                <tr>
                    <th>Appointment</th>
                    <th>Patient</th>
                    <th>Status</th>
                    <th></th>
                </tr>
                </thead>
                <tbody>
                @foreach($visits as $visit)
                    <tr>
                        <td>#{{ $visit->appointment_id }}</td>
                        <td>{{ optional($visit->appointment->patient)->name }}</td>
                        <td>{{ $visit->visit_status }}</td>
                        <td class="text-end">
                            <a href="{{ route('visits.show', $visit) }}" class="btn btn-sm btn-outline-primary">View</a>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
        <div class="card-footer">
            {{ $visits->links() }}
        </div>
    </div>
</x-app-layout>
