<x-app-layout>
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h3 class="page-title mb-0">Rounds</h3>
            <a href="{{ route('ipd.index') }}" class="btn btn-outline-secondary">IPD Dashboard</a>
        </div>
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead>
                            <tr>
                                <th>Patient</th>
                                <th>Doctor</th>
                                <th>Admitted On</th>
                                <th>Notes</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($admissions as $admission)
                                <tr>
                                    <td>{{ optional($admission->patient)->full_name ?? 'Patient' }}</td>
                                    <td>{{ optional($admission->doctor)->user->name ?? 'Doctor' }}</td>
                                    <td>{{ $admission->created_at->format('Y-m-d H:i') }}</td>
                                    <td>
                                        <input type="text" class="form-control" placeholder="Round note">
                                    </td>
                                    <td>
                                        <a href="{{ route('ipd.show', $admission->id) }}"
                                            class="btn btn-sm btn-outline-primary">Review</a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5">No admitted patients.</td>
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
