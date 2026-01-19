<x-app-layout>
    <div class="container-fluid mx-2">
        <div class="card border-0 mt-2 px-3 py-2">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <h4 class="mb-1">Discharge Patient</h4>
                        <p class="text-muted mb-0">Finalize inpatient discharge details</p>
                    </div>
                    <a href="{{ route('ipd.show', $admission) }}" class="btn btn-outline-secondary">
                        <i class="ti ti-arrow-left me-1"></i> Back to Admission
                    </a>
                </div>
                <hr>

                <div class="row g-4">
                    <div class="col-md-4">
                        <div class="card border-0 shadow-sm mb-4">
                            <div class="card-body">
                                <h5 class="card-title mb-3">Patient Information</h5>
                                <div class="d-flex align-items-center mb-3">
                                    <div class="avatar avatar-lg me-3">
                                        @if ($admission->patient->profile_photo)
                                            <img src="{{ asset($admission->patient->profile_photo) }}"
                                                alt="{{ $admission->patient->name }}"
                                                class="rounded-circle w-100 h-100 object-fit-cover">
                                        @else
                                            <span
                                                class="avatar-title rounded-circle bg-primary-subtle text-primary fs-3">
                                                {{ substr($admission->patient->name, 0, 1) }}
                                            </span>
                                        @endif
                                    </div>
                                    <div>
                                        <h6 class="mb-0">{{ $admission->patient->name }}</h6>
                                        <div class="text-muted small">{{ $admission->patient->patient_code }}</div>
                                    </div>
                                </div>
                                <ul class="list-group list-group-flush">
                                    <li class="list-group-item d-flex justify-content-between px-0">
                                        <span class="text-muted">Status</span>
                                        <span
                                            class="badge bg-{{ $admission->status === 'admitted' ? 'success' : 'secondary' }}">
                                            {{ ucfirst($admission->status) }}
                                        </span>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between px-0">
                                        <span class="text-muted">Admission Date</span>
                                        <span class="fw-semibold">{{ $admission->admission_date }}</span>
                                    </li>
                                    @if ($admission->discharge_date)
                                        <li class="list-group-item d-flex justify-content-between px-0">
                                            <span class="text-muted">Current Discharge Date</span>
                                            <span class="fw-semibold">{{ $admission->discharge_date }}</span>
                                        </li>
                                    @endif
                                    <li class="list-group-item d-flex justify-content-between px-0">
                                        <span class="text-muted">Attending Doctor</span>
                                        <span class="fw-semibold">Dr.
                                            {{ $admission->doctor?->user?->name ?? 'Deleted Doctor' }}</span>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-8">
                        <form action="{{ route('ipd.store-discharge', $admission) }}" method="POST">
                            @csrf

                            <div class="card border-0 shadow-sm mb-4">
                                <div class="card-header bg-transparent">
                                    <h5 class="card-title mb-0">Discharge Details</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <x-input-label for="discharge_date" :value="__('Discharge Date')" />
                                            <x-text-input id="discharge_date" class="block mt-1 w-full form-control"
                                                type="datetime-local" name="discharge_date" :value="old('discharge_date', now()->format('Y-m-d\TH:i'))"
                                                required />
                                            <x-input-error :messages="$errors->get('discharge_date')" class="mt-2" />
                                        </div>
                                    </div>

                                    <div class="row g-3 mt-2">
                                        <div class="col-md-6">
                                            <label class="form-label">Discount</label>
                                            <input type="number" name="discount" class="form-control" step="0.01"
                                                min="0" value="0">
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">Tax (%)</label>
                                            <input type="number" name="tax" class="form-control" step="0.01"
                                                min="0" value="0">
                                        </div>
                                    </div>

                                    @if (isset($unpaidInvoices) && $unpaidInvoices)
                                        <div class="alert alert-danger mt-4 mb-0 d-flex align-items-center">
                                            <i class="ti ti-ban fs-2 me-3"></i>
                                            <div>
                                                <h6 class="mb-1">Cannot Discharge</h6>
                                                <p class="mb-0 small">
                                                    This patient has unpaid invoices. Please settle all dues before
                                                    discharging.
                                                </p>
                                            </div>
                                        </div>
                                    @else
                                        <div class="alert alert-warning mt-4 mb-0 d-flex align-items-center">
                                            <i class="ti ti-alert-circle fs-2 me-3"></i>
                                            <div>
                                                <h6 class="mb-1">Confirm Discharge</h6>
                                                <p class="mb-0 small">
                                                    Discharging this patient will mark the admission as completed and
                                                    free up
                                                    any currently assigned bed.
                                                </p>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <div class="d-flex justify-content-end gap-2">
                                <a href="{{ route('ipd.show', $admission) }}" class="btn btn-outline-secondary">
                                    Cancel
                                </a>
                                <button type="submit" class="btn btn-danger"
                                    {{ isset($unpaidInvoices) && $unpaidInvoices ? 'disabled' : '' }}>
                                    <i class="ti ti-door-exit me-1"></i> Confirm Discharge
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
