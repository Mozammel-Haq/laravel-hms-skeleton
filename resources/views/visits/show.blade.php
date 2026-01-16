<x-app-layout>
    <h5 class="mb-3">Visit #{{ $visit->id }}</h5>
    <div class="row g-3">
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <div class="mb-2">Appointment: #{{ $visit->appointment_id }}</div>
                    <div class="mb-2">Patient: {{ optional($visit->appointment->patient)->name }}</div>
                    <div class="mb-2">Status: {{ $visit->visit_status }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <div class="mb-2">Consultation: {{ optional($visit->consultation)->id }}</div>
                    <div class="mb-2">Diagnosis: {{ optional($visit->consultation)->diagnosis }}</div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-3 mt-3">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Invoices for this Visit</div>
                <div class="card-body">
                    @php
                        $invoices = \App\Models\Invoice::where('visit_id', $visit->id)->latest()->get();
                        $total = $invoices->sum('total_amount');
                    @endphp
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Type</th>
                                <th>Status</th>
                                <th>State</th>
                                <th>Total</th>
                                <th>Issued</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($invoices as $inv)
                                <tr>
                                    <td>{{ $inv->invoice_number }}</td>
                                    <td>{{ $inv->invoice_type }}</td>
                                    <td>{{ $inv->status }}</td>
                                    <td>{{ $inv->state }}</td>
                                    <td>{{ number_format($inv->total_amount, 2) }}</td>
                                    <td>{{ optional($inv->issued_at)->format('Y-m-d') }}</td>
                                    <td><a href="{{ route('billing.show', $inv) }}" class="btn btn-sm btn-outline-primary">View</a></td>
                                </tr>
                            @empty
                                <tr><td colspan="7">No invoices yet for this visit.</td></tr>
                            @endforelse
                        </tbody>
                        <tfoot>
                            <tr>
                                <th colspan="4" class="text-end">Visit Total</th>
                                <th colspan="3">{{ number_format($total, 2) }}</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            @can('create', \App\Models\Invoice::class)
            <div class="card">
                <div class="card-header">Add Procedure/Service Invoice</div>
                <div class="card-body">
                    <form method="post" action="{{ route('visits.procedure.store', $visit) }}">
                        @csrf
                        <div class="mb-2">
                            <label class="form-label">Description</label>
                            <input type="text" name="description" class="form-control" required>
                        </div>
                        <div class="mb-2">
                            <label class="form-label">Quantity</label>
                            <input type="number" name="quantity" class="form-control" value="1" min="1" required>
                        </div>
                        <div class="mb-2">
                            <label class="form-label">Unit Price</label>
                            <input type="number" step="0.01" name="unit_price" class="form-control" required>
                        </div>
                        <button type="submit" class="btn btn-primary btn-sm">Generate Invoice</button>
                    </form>
                </div>
            </div>
            @endcan
        </div>
    </div>
</x-app-layout>
