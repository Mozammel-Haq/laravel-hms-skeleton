<x-app-layout>
    <div class="container-fluid py-3 mx-2">
        <div class="row g-4 mb-4">
            <!-- Revenue Today KPI Card -->
            <div class="col-md-4">
                <div class="position-relative overflow-hidden rounded-4 h-100 kpi-card kpi-success"
                    data-bs-theme="light,dark">
                    <div class="position-absolute top-0 end-0 w-100 h-100 opacity-25 pattern-bg">
                        <svg width="100%" height="100%" xmlns="http://www.w3.org/2000/svg">
                            <defs>
                                <pattern id="pattern-grid-acc-today" x="0" y="0" width="40" height="40"
                                    patternUnits="userSpaceOnUse">
                                    <rect x="0" y="0" width="2" height="2" fill="var(--primary-color)"
                                        fill-opacity="0.2" />
                                </pattern>
                            </defs>
                            <rect width="100%" height="100%" fill="url(#pattern-grid-acc-today)" />
                        </svg>
                    </div>
                    <div class="position-absolute top-0 end-0 w-25 h-25 decorative-shape"
                        style="background: radial-gradient(circle at top right, var(--primary-color) 0%, transparent 70%); opacity: 0.15;">
                    </div>
                    <div class="card-body position-relative z-1 p-4">
                        <div class="d-flex align-items-start justify-content-between mb-3">
                            <div>
                                <h6 class="card-title fw-medium mb-1 kpi-label" style="letter-spacing: 0.5px;">REVENUE
                                    TODAY</h6>
                                <h2 class="fw-bold kpi-value mb-0">$ {{ number_format($cards['revenue_today'], 2) }}
                                </h2>
                                </h2>
                            </div>
                            <div class="rounded-3 p-2 kpi-icon-container">
                                <svg width="28" height="28" viewBox="0 0 24 24" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M12 6V18M12 6C14.2091 6 16 7.79086 16 10C16 12.2091 14.2091 14 12 14M12 6C9.79086 6 8 7.79086 8 10C8 12.2091 9.79086 14 12 14M12 18C9.79086 18 8 16.2091 8 14M12 18C14.2091 18 16 16.2091 16 14"
                                        stroke="var(--primary-color)" stroke-width="1.5" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                </svg>
                            </div>
                        </div>
                        <div class="border-top pt-3 mt-3 kpi-divider">
                            <div class="d-flex align-items-center">
                                <div class="rounded-circle bg-body-secondary p-1 me-2 border kpi-small-icon">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path d="M12 5V19M5 12H19" stroke="var(--primary-color)" stroke-width="1.5"
                                            stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                </div>
                                <p class="text-muted kpi-footer">Today's Total</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Revenue This Month KPI Card -->
            <div class="col-md-4">
                <div class="position-relative overflow-hidden rounded-4 h-100 kpi-card kpi-primary"
                    data-bs-theme="light,dark">
                    <div class="position-absolute top-0 end-0 w-100 h-100 opacity-25 pattern-bg">
                        <svg width="100%" height="100%" xmlns="http://www.w3.org/2000/svg">
                            <defs>
                                <pattern id="pattern-grid-acc-3" x="0" y="0" width="40" height="40"
                                    patternUnits="userSpaceOnUse">
                                    <rect x="0" y="0" width="2" height="2" fill="var(--primary-color)"
                                        fill-opacity="0.2" />
                                </pattern>
                            </defs>
                            <rect width="100%" height="100%" fill="url(#pattern-grid-acc-3)" />
                        </svg>
                    </div>
                    <div class="position-absolute top-0 end-0 w-25 h-25 decorative-shape"
                        style="background: radial-gradient(circle at top right, var(--primary-color) 0%, transparent 70%); opacity: 0.15;">
                    </div>
                    <div class="card-body position-relative z-1 p-4">
                        <div class="d-flex align-items-start justify-content-between mb-3">
                            <div>
                                <h6 class="card-title fw-medium mb-1 kpi-label" style="letter-spacing: 0.5px;">REVENUE
                                    THIS MONTH</h6>
                                <h2 class="fw-bold kpi-value mb-0">{{ number_format($cards['revenue_month'], 2) }}</h2>
                            </div>
                            <div class="rounded-3 p-2 kpi-icon-container">
                                <svg width="28" height="28" viewBox="0 0 24 24" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M12 6V18M12 6C14.2091 6 16 7.79086 16 10C16 12.2091 14.2091 14 12 14M12 6C9.79086 6 8 7.79086 8 10C8 12.2091 9.79086 14 12 14M12 18C9.79086 18 8 16.2091 8 14M12 18C14.2091 18 16 16.2091 16 14"
                                        stroke="var(--primary-color)" stroke-width="1.5" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                </svg>
                            </div>
                        </div>
                        <div class="border-top pt-3 mt-3 kpi-divider">
                            <div class="d-flex align-items-center">
                                <div class="rounded-circle bg-body-secondary p-1 me-2 border kpi-small-icon">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path d="M13 7H7V5H13V7Z" stroke="var(--primary-color)" stroke-width="1.5"
                                            stroke-linecap="round" stroke-linejoin="round" />
                                        <path d="M13 11H7V9H13V11Z" stroke="var(--primary-color)" stroke-width="1.5"
                                            stroke-linecap="round" stroke-linejoin="round" />
                                        <path d="M13 15H7V13H13V15Z" stroke="var(--primary-color)" stroke-width="1.5"
                                            stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                </div>
                                <p class="text-muted kpi-footer">Current Month</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Revenue Total KPI Card -->
            <div class="col-md-4">
                <div class="position-relative overflow-hidden rounded-4 h-100 kpi-card kpi-info"
                    data-bs-theme="light,dark">
                    <div class="position-absolute top-0 end-0 w-100 h-100 opacity-25 pattern-bg">
                        <svg width="100%" height="100%" xmlns="http://www.w3.org/2000/svg">
                            <defs>
                                <pattern id="pattern-grid-acc-total" x="0" y="0" width="40" height="40"
                                    patternUnits="userSpaceOnUse">
                                    <rect x="0" y="0" width="2" height="2" fill="var(--primary-color)"
                                        fill-opacity="0.2" />
                                </pattern>
                            </defs>
                            <rect width="100%" height="100%" fill="url(#pattern-grid-acc-total)" />
                        </svg>
                    </div>
                    <div class="position-absolute top-0 end-0 w-25 h-25 decorative-shape"
                        style="background: radial-gradient(circle at top right, var(--primary-color) 0%, transparent 70%); opacity: 0.15;">
                    </div>
                    <div class="card-body position-relative z-1 p-4">
                        <div class="d-flex align-items-start justify-content-between mb-3">
                            <div>
                                <h6 class="card-title fw-medium mb-1 kpi-label" style="letter-spacing: 0.5px;">TOTAL
                                    REVENUE</h6>
                                <h2 class="fw-bold kpi-value mb-0">{{ number_format($cards['revenue_total'], 2) }}</h2>
                            </div>
                            <div class="rounded-3 p-2 kpi-icon-container">
                                <svg width="28" height="28" viewBox="0 0 24 24" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path d="M12 1V23" stroke="var(--primary-color)" stroke-width="1.5"
                                        stroke-linecap="round" stroke-linejoin="round" />
                                    <path
                                        d="M17 5H9.5C8.57174 5 7.6815 5.36875 7.02513 6.02513C6.36875 6.6815 6 7.57174 6 8.5C6 9.42826 6.36875 10.3185 7.02513 10.9749C7.6815 11.6313 8.57174 12 9.5 12H14.5C15.4283 12 16.3185 12.3687 16.9749 13.0251C17.6313 13.6815 18 14.5717 18 15.5C18 16.4283 17.6313 17.3185 16.9749 17.9749C16.3185 18.6313 15.4283 19 14.5 19H6"
                                        stroke="var(--primary-color)" stroke-width="1.5" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                </svg>
                            </div>
                        </div>
                        <div class="border-top pt-3 mt-3 kpi-divider">
                            <div class="d-flex align-items-center">
                                <div class="rounded-circle bg-body-secondary p-1 me-2 border kpi-small-icon">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path d="M12 8V16M8 12H16" stroke="var(--primary-color)" stroke-width="1.5"
                                            stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                </div>
                                <p class="text-muted kpi-footer">All Time</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-4 mb-4">
            <!-- Unpaid Invoices KPI Card -->
            <div class="col-md-6">
                <div class="position-relative overflow-hidden rounded-4 h-100 kpi-card kpi-primary"
                    data-bs-theme="light,dark">
                    <div class="position-absolute top-0 end-0 w-100 h-100 opacity-25 pattern-bg">
                        <svg width="100%" height="100%" xmlns="http://www.w3.org/2000/svg">
                            <defs>
                                <pattern id="pattern-grid-acc-1" x="0" y="0" width="40" height="40"
                                    patternUnits="userSpaceOnUse">
                                    <rect x="0" y="0" width="2" height="2" fill="var(--primary-color)"
                                        fill-opacity="0.2" />
                                </pattern>
                            </defs>
                            <rect width="100%" height="100%" fill="url(#pattern-grid-acc-1)" />
                        </svg>
                    </div>
                    <div class="position-absolute top-0 end-0 w-25 h-25 decorative-shape"
                        style="background: radial-gradient(circle at top right, var(--primary-color) 0%, transparent 70%); opacity: 0.15;">
                    </div>
                    <div class="card-body position-relative z-1 p-4">
                        <div class="d-flex align-items-start justify-content-between mb-3">
                            <div>
                                <h6 class="card-title fw-medium mb-1 kpi-label" style="letter-spacing: 0.5px;">UNPAID
                                    INVOICES</h6>
                                <h2 class="fw-bold kpi-value mb-0">{{ $cards['invoices_unpaid'] }}</h2>
                            </div>
                            <div class="rounded-3 p-2 kpi-icon-container">
                                <svg width="28" height="28" viewBox="0 0 24 24" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M9 14H15M9 10H15M9 6H15M19 21V5C19 3.89543 18.1046 3 17 3H7C5.89543 3 5 3.89543 5 5V21L12 18L19 21Z"
                                        stroke="var(--primary-color)" stroke-width="1.5" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                </svg>
                            </div>
                        </div>
                        <div class="border-top pt-3 mt-3 kpi-divider">
                            <div class="d-flex align-items-center">
                                <div class="rounded-circle bg-body-secondary p-1 me-2 border kpi-small-icon">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path d="M12 8V16M8 12H16" stroke="var(--primary-color)" stroke-width="1.5"
                                            stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                </div>
                                <p class="text-muted kpi-footer">Pending Payment</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Paid Invoices KPI Card -->
            <div class="col-md-6">
                <div class="position-relative overflow-hidden rounded-4 h-100 kpi-card kpi-success"
                    data-bs-theme="light,dark">
                    <div class="position-absolute top-0 end-0 w-100 h-100 opacity-25 pattern-bg">
                        <svg width="100%" height="100%" xmlns="http://www.w3.org/2000/svg">
                            <defs>
                                <pattern id="pattern-circle-acc-2" x="0" y="0" width="40" height="40"
                                    patternUnits="userSpaceOnUse">
                                    <circle cx="20" cy="20" r="2" fill="var(--primary-color)"
                                        fill-opacity="0.2" />
                                </pattern>
                            </defs>
                            <rect width="100%" height="100%" fill="url(#pattern-circle-acc-2)" />
                        </svg>
                    </div>
                    <div class="position-absolute top-0 end-0 w-25 h-25 decorative-shape"
                        style="background: radial-gradient(circle at top right, var(--primary-color) 0%, transparent 70%); opacity: 0.15;">
                    </div>
                    <div class="card-body position-relative z-1 p-4">
                        <div class="d-flex align-items-start justify-content-between mb-3">
                            <div>
                                <h6 class="card-title fw-medium mb-1 kpi-label" style="letter-spacing: 0.5px;">PAID
                                    INVOICES</h6>
                                <h2 class="fw-bold kpi-value mb-0">{{ $cards['invoices_paid'] }}</h2>
                            </div>
                            <div class="rounded-3 p-2 kpi-icon-container">
                                <svg width="28" height="28" viewBox="0 0 24 24" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M9 12L11 14L15 10M21 12C21 16.9706 16.9706 21 12 21C7.02944 21 3 16.9706 3 12C3 7.02944 7.02944 3 12 3C16.9706 3 21 7.02944 21 12Z"
                                        stroke="var(--primary-color)" stroke-width="1.5" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                </svg>
                            </div>
                        </div>
                        <div class="border-top pt-3 mt-3 kpi-divider">
                            <div class="d-flex align-items-center">
                                <div class="rounded-circle bg-body-secondary p-1 me-2 border kpi-small-icon">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path d="M5 13L9 17L19 7" stroke="var(--primary-color)" stroke-width="1.5"
                                            stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                </div>
                                <p class="text-muted kpi-footer">Successfully Processed</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card border-0 shadow-sm mt-3">
            <div class="card-header bg-white">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <h5 class="mb-0">Invoices</h5>
                    <a href="{{ route('billing.index') }}" class="btn btn-sm btn-outline-primary">Manage Billing</a>
                </div>
                <form action="{{ route('dashboard') }}" method="GET" class="row g-2">
                    <div class="col-md-3">
                        <input type="text" name="search" class="form-control form-control-sm"
                            placeholder="Search Invoice #" value="{{ request('search') }}">
                    </div>
                    <div class="col-md-2">
                        <select name="status" class="form-select form-select-sm">
                            <option value="">All Statuses</option>
                            <option value="paid" {{ request('status') == 'paid' ? 'selected' : '' }}>Paid</option>
                            <option value="unpaid" {{ request('status') == 'unpaid' ? 'selected' : '' }}>Unpaid
                            </option>
                            <option value="partial" {{ request('status') == 'partial' ? 'selected' : '' }}>Partial
                            </option>
                            <option value="overdue" {{ request('status') == 'overdue' ? 'selected' : '' }}>Overdue
                            </option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <input type="date" name="date_from" class="form-control form-control-sm"
                            value="{{ request('date_from') }}" placeholder="From">
                    </div>
                    <div class="col-md-2">
                        <input type="date" name="date_to" class="form-control form-control-sm"
                            value="{{ request('date_to') }}" placeholder="To">
                    </div>
                    <div class="col-md-3 d-flex gap-2">
                        <button type="submit" class="btn btn-sm btn-primary flex-grow-1">Filter</button>
                        <a href="{{ route('dashboard') }}" class="btn btn-sm btn-outline-secondary">Clear</a>
                    </div>
                </form>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Invoice</th>
                                <th>Total</th>
                                <th>Status</th>
                                <th>Issued</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($invoices as $inv)
                                <tr>
                                    <td>
                                        <a href="{{ route('billing.show', $inv) }}"
                                            class="text-decoration-none text-body fw-bold">
                                            {{ $inv->invoice_number ?? $inv->id }}
                                        </a>
                                    </td>
                                    <td>{{ number_format($inv->total_amount, 2) }}</td>
                                    <td>
                                        @php
                                            $invStatus = $inv->status;
                                            $invColor = match ($invStatus) {
                                                'paid' => 'success',
                                                'unpaid', 'pending' => 'warning',
                                                'overdue', 'cancelled' => 'danger',
                                                default => 'secondary',
                                            };
                                        @endphp
                                        <span class="badge bg-{{ $invColor }}">{{ ucfirst($invStatus) }}</span>
                                    </td>
                                    <td>{{ $inv->created_at }}</td>
                                    <td class="text-end">
                                        <div class="dropdown">
                                            <button class="btn btn-sm btn-light btn-icon" type="button"
                                                data-bs-toggle="dropdown" aria-expanded="false">
                                                <i class="ti ti-dots-vertical"></i>
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-end">
                                                <li>
                                                    <a class="dropdown-item"
                                                        href="{{ route('billing.show', $inv) }}">
                                                        <i class="ti ti-eye me-1"></i> View
                                                    </a>
                                                </li>
                                            </ul>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center text-muted py-4">No invoices found matching
                                        filters.</td>
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
