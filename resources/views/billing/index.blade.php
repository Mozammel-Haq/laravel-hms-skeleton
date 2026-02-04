<x-app-layout>
    <div class="container-fluid mx-2 mt-2">
    <div class="d-flex justify-content-between align-items-center bg-primary-subtle text-primary px-4 py-2 rounded-top shadow-sm mb-0">
        <div>
            <h5 class="fw-bold mb-1 text-primary">Invoices</h5>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb breadcrumb-dots mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Invoices</li>
                </ol>
            </nav>
        </div>
        @can('create', \App\Models\Invoice::class)
            <a href="{{ route('billing.create') }}" class="btn btn-sm btn-primary">
                <i class="ti ti-plus me-1"></i> Create Invoice
            </a>
        @endcan
    </div>

    <div class="card shadow-sm border-0 rounded-bottom mt-0">
        <div class="card-body">

                <!-- Filters -->
                <form method="GET" action="{{ route('billing.index') }}" class="mb-4">
                    <div class="row g-2 align-items-end">
                        <div class="col-md-3">
                            <label class="form-label small text-muted">Search</label>
                            <input type="text" name="search" class="form-control form-control-sm"
                                placeholder="Search Invoice # or Patient" value="{{ request('search') }}">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label small fw-semibold text-muted">Status</label>
                            <select name="status" class="form-select form-select-sm">
                                <option value="all">All Status</option>
                                <option value="unpaid" {{ request('status') == 'unpaid' ? 'selected' : '' }}>Unpaid
                                </option>
                                <option value="paid" {{ request('status') == 'paid' ? 'selected' : '' }}>Paid</option>
                                <option value="partial" {{ request('status') == 'partial' ? 'selected' : '' }}>Partial
                                </option>
                                <option value="overdue" {{ request('status') == 'overdue' ? 'selected' : '' }}>Overdue
                                </option>
                                <option value="trashed" {{ request('status') == 'trashed' ? 'selected' : '' }}>Trash
                                </option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label small fw-semibold text-muted">From</label>
                            <input type="date" name="from" class="form-control form-control-sm" placeholder="From Date"
                                value="{{ request('from') }}">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label small fw-semibold text-muted">To</label>
                            <input type="date" name="to" class="form-control form-control-sm" placeholder="To Date"
                                value="{{ request('to') }}">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label small fw-semibold d-block">&nbsp;</label>
                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-primary btn-sm w-100">Filter</button>
                                <a href="{{ route('billing.index') }}" class="btn btn-light btn-sm w-100">Reset</a>
                            </div>
                        </div>
                    </div>
                </form>

                <hr>
                <div class="table">
                    <table class="table table-hover align-middle">
                        <thead>
                            <tr>
                                <th>Number</th>
                                <th>Patient</th>
                                <th>Status</th>
                                <th>Total</th>
                                <th>Issued</th>
                                <th class="text-end">Actions</th>
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
                                    <td>
                                        @php
                                            $status = $inv->status;
                                            $color = match ($status) {
                                                'paid' => 'success',
                                                'partial' => 'warning',
                                                'unpaid', 'overdue' => 'danger',
                                                default => 'primary',
                                            };
                                        @endphp
                                        <span class="badge bg-{{ $color }}">{{ ucfirst($status) }}</span>
                                    </td>
                                    <td>{{ number_format($inv->total_amount, 2) }}</td>
                                    <td>{{ \Carbon\Carbon::parse($inv->issued_at)->format('Y-m-d H:i') }}</td>
                                    <td class="text-end">
                                        <div class="dropdown">
                                            <button class="btn btn-sm btn-light btn-icon" type="button"
                                                data-bs-toggle="dropdown">
                                                <i class="ti ti-dots-vertical"></i>
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-end">
                                                @if ($inv->trashed())
                                                    <li>
                                                        <form action="{{ route('billing.restore', $inv->id) }}"
                                                            method="POST">
                                                            @csrf
                                                            <button type="submit" class="dropdown-item text-success"
                                                                onclick="return confirm('Are you sure you want to restore this invoice?')">
                                                                <i class="ti ti-refresh me-1"></i> Restore
                                                            </button>
                                                        </form>
                                                    </li>
                                                @else
                                                    <li>
                                                        <a class="dropdown-item"
                                                            href="{{ route('billing.show', $inv->id) }}">
                                                            <i class="ti ti-eye me-1"></i> View
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a class="dropdown-item"
                                                            href="{{ route('billing.show', ['invoice' => $inv->id, 'print' => 'true']) }}"
                                                            target="_blank">
                                                            <i class="ti ti-printer me-1"></i> Print
                                                        </a>
                                                    </li>
                                                    @can('delete', $inv)
                                                        <li>
                                                            <hr class="dropdown-divider">
                                                        </li>
                                                        <li>
                                                            <form action="{{ route('billing.destroy', $inv) }}"
                                                                method="POST">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="dropdown-item text-danger"
                                                                    onclick="return confirm('Are you sure you want to delete this invoice?')">
                                                                    <i class="ti ti-trash me-1"></i> Delete
                                                                </button>
                                                            </form>
                                                        </li>
                                                    @endcan
                                                @endif
                                            </ul>
                                        </div>
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
