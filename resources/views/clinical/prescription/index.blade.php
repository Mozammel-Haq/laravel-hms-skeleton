<x-app-layout>
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h3 class="page-title mb-0">Prescriptions</h3>
            <div class="btn-group">
                <a href="{{ route('clinical.prescriptions.index') }}"
                    class="btn btn-{{ request('status') !== 'trashed' ? 'primary' : 'outline-primary' }}">Active</a>
                <a href="{{ route('clinical.prescriptions.index', ['status' => 'trashed']) }}"
                    class="btn btn-{{ request('status') === 'trashed' ? 'primary' : 'outline-primary' }}">Trash</a>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Patient</th>
                                <th>Doctor</th>
                                <th>Issued</th>
                                <th>Status</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($prescriptions as $rx)
                                <tr>
                                    <td>{{ $rx->id }}</td>
                                    <td>{{ optional($rx->consultation?->patient)->full_name ?? (optional($rx->consultation?->patient)->name ?? 'Patient') }}
                                    </td>
                                    <td>{{ optional($rx->consultation?->doctor?->user)->name ?? 'Doctor' }}</td>
                                    <td>{{ isset($rx->issued_at) ? \Illuminate\Support\Carbon::parse($rx->issued_at)->format('Y-m-d') : $rx->created_at->format('Y-m-d') }}
                                    </td>
                                    <td><span class="badge bg-secondary">{{ $rx->status ?? 'active' }}</span></td>
                                    <td class="text-end">
                                        @if ($rx->trashed())
                                            <form action="{{ route('clinical.prescriptions.restore', $rx->id) }}"
                                                method="POST" class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-outline-success"
                                                    onclick="return confirm('Are you sure you want to restore this prescription?')">Restore</button>
                                            </form>
                                        @else
                                            <a href="{{ route('clinical.prescriptions.show', $rx) }}"
                                                class="btn btn-sm btn-outline-primary">Open</a>
                                            <form action="{{ route('clinical.prescriptions.destroy', $rx) }}"
                                                method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger"
                                                    onclick="return confirm('Are you sure you want to delete this prescription?')">Delete</button>
                                            </form>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center text-muted">No prescriptions</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="mt-3">
                    {{ $prescriptions->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
