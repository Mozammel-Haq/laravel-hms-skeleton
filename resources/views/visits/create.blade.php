<x-app-layout>
    <div class="container-fluid mx-2">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card border-0 mt-2 shadow-sm">
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-center mb-4 border-bottom pb-3 bg-primary-subtle text-primary px-4 py-2 rounded-top shadow-sm">
                            <div>
                                <h4 class="fw-bold text-primary mb-2">Start New Visit</h4>
                                <nav aria-label="breadcrumb">
                                    <ol class="breadcrumb breadcrumb-dots mb-0">
                                        <li class="breadcrumb-item"><a href="{{ route('visits.index') }}">Visits</a></li>
                                        <li class="breadcrumb-item active" aria-current="page">New Visit</li>
                                    </ol>
                                </nav>
                            </div>
                            <a href="{{ route('visits.index') }}" class="btn btn-sm btn-outline-primary">
                                <i class="ti ti-arrow-left me-1"></i> Back to List
                            </a>
                        </div>

                        <form method="POST" action="{{ route('visits.store') }}">
                            @csrf

                            <div class="card bg-light border-0 mb-4">
                                <div class="card-body">
                                    <div class="mb-3">
                                        <label class="form-label small fw-bold">Select Appointment <span class="text-danger">*</span></label>
                                        <select name="appointment_id" class="form-select form-select-sm" required>
                                            <option value="">Choose an appointment...</option>
                                            @foreach ($appointments as $a)
                                                <option value="{{ $a->id }}">
                                                    #{{ $a->id }} — {{ optional($a->patient)->name }} ({{ optional($a->doctor)->user->name ?? 'Doctor' }}) - {{ $a->appointment_date }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <div class="form-text small">Only confirmed appointments are listed here.</div>
                                    </div>
                                </div>
                            </div>

                            <h5 class="text-primary fw-bold mb-3 border-bottom pb-2">Financial Details</h5>
                            <div class="row g-3 mb-4">
                                <div class="col-md-6">
                                    <label class="form-label small fw-semibold">Discount Amount</label>
                                    <div class="input-group input-group-sm">
                                        <span class="input-group-text">৳</span>
                                        <input type="number" name="discount" class="form-control" step="0.01" min="0" value="0" placeholder="0.00">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label small fw-semibold">Tax Rate (%)</label>
                                    <div class="input-group input-group-sm">
                                        <input type="number" name="tax" class="form-control" step="0.01" min="0" value="0" placeholder="0">
                                        <span class="input-group-text">%</span>
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex justify-content-end gap-2">
                                <a href="{{ route('visits.index') }}" class="btn btn-sm btn-light border">Cancel</a>
                                <button type="submit" class="btn btn-sm btn-primary px-4">Start Visit</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
