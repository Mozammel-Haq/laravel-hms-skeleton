<x-app-layout>
    <div class="container-fluid">

        <div class="card mt-3 mx-2">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h3 class="page-title mb-0">Invoices</h3>
                    <div class="d-flex gap-2">
                        <div class="btn-group">
                            <a href="{{ route('billing.index') }}"
                                class="btn btn-{{ request('status') !== 'trashed' ? 'primary' : 'outline-primary' }}">Active</a>
                            <a href="{{ route('billing.index', ['status' => 'trashed']) }}"
                                class="btn btn-{{ request('status') === 'trashed' ? 'primary' : 'outline-primary' }}">Trash</a>
                        </div>
                        @can('create', \App\Models\Invoice::class)
                            <a href="{{ route('billing.create') }}" class="btn btn-primary">Create Invoice</a>
                        @endcan
                    </div>
                </div>
                <hr>
                <div class="table-responsive">
                    <table class="table table-hover align-middle datatable">
                        <thead>
                            <tr>
                                <th>Number</th>
                                <th>Patient</th>
                                <th>Status</th>
                                <th>Total</th>
                                <th>Issued</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($invoices as $inv)
                                <tr>
                                    <td>
                                        <a href="{{ route('billing.show', $inv->id) }}"
                                            class="text-decoration-none text-body fw-bold">
                                            {{ $inv->invoice_number }}
                                        </a>
                                    </td>
                                    <td>
                                        @if ($inv->patient)
                                            <a href="{{ route('patients.show', $inv->patient) }}"
                                                class="d-flex align-items-center text-decoration-none text-body">
                                                <div class="avatar avatar-sm me-2">
                                                    @if ($inv->patient->profile_photo)
                                                        <img src="{{ asset($inv->patient->profile_photo) }}"
                                                            alt="{{ $inv->patient->name }}"
                                                            class="rounded-circle w-100 h-100 object-fit-cover">
                                                    @else
                                                        <span
                                                            class="avatar-title rounded-circle bg-primary-subtle text-primary fs-6">
                                                            {{ substr($inv->patient->name, 0, 1) }}
                                                        </span>
                                                    @endif
                                                </div>
                                                <div>
                                                    {{ $inv->patient->name }}
                                                </div>
                                            </a>
                                        @else
                                            Patient
                                        @endif
                                    </td>
                                    <td><span
                                            class="badge bg-{{ $inv->status === 'paid' ? 'success' : ($inv->status === 'partial' ? 'warning' : 'secondary') }}">{{ ucfirst($inv->status) }}</span>
                                    </td>
                                    <td>{{ number_format($inv->total_amount, 2) }}</td>
                                    <td>{{ \Carbon\Carbon::parse($inv->issued_at)->format('Y-m-d H:i') }}</td>
                                    <td>
                                        @if ($inv->trashed())
                                            <form action="{{ route('billing.restore', $inv->id) }}" method="POST"
                                                class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-outline-success"
                                                    onclick="return confirm('Are you sure you want to restore this invoice?')">
                                                    Restore
                                                </button>
                                            </form>
                                        @else
                                            <a href="{{ route('billing.show', ['invoice' => $inv->id, 'print' => 'true']) }}"
                                                target="_blank" class="btn btn-sm btn-outline-secondary" title="Print">
                                                <i class="ti ti-printer"></i>
                                            </a>
                                            <a href="{{ route('billing.show', $inv->id) }}"
                                                class="btn btn-sm btn-outline-primary">View</a>
                                            @can('delete', $inv)
                                                <form action="{{ route('billing.destroy', $inv) }}" method="POST"
                                                    class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-outline-danger"
                                                        onclick="return confirm('Are you sure you want to delete this invoice?')">
                                                        Delete
                                                    </button>
                                                </form>
                                            @endcan
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center text-muted py-4">
                                        No invoices found.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="mt-3">
                    {{ $invoices->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
