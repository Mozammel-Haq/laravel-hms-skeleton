<x-app-layout>
    <div class="container-fluid mx-2">
        <div class="row g-3">
            <div class="col-lg-4">
                <div class="card mt-2">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h3 class="page-title mb-0">Consultation</h3>
                            <a href="{{ route('clinical.prescriptions.index') }}"
                                class="btn btn-outline-secondary">Prescriptions</a>
                        </div>
                        <div class="fw-semibold mb-2">Patient</div>
                        <div class="d-flex align-items-center mb-3">
                            <span class="avatar avatar-lg rounded-circle me-3">
                                <img src="{{ asset('assets') }}/img/users/user-02.jpg" alt="img"
                                    class="rounded-circle">
                            </span>
                            <div>
                                <div class="h5 mb-0">
                                    {{ optional($consultation->patient)->full_name ?? (optional($consultation->patient)->name ?? 'Patient') }}
                                </div>
                                <div class="text-muted">#{{ optional($consultation->patient)->id }}</div>
                            </div>
                        </div>
                        <div class="fw-semibold mb-2">Doctor</div>
                        <div>{{ optional($consultation->doctor?->user)->name ?? 'Doctor' }}</div>
                    </div>
                </div>
            </div>
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-header bg-white fw-semibold">Details</div>
                    <div class="card-body">
                        <div class="row g-3 mb-3">
                            <div class="col-md-4">
                                <div class="text-muted">Consultation Fee</div>
                                <div class="fw-semibold">{{ number_format($feeInfo['fee'] ?? 0, 2) }} BDT</div>
                            </div>
                            <div class="col-md-4">
                                <div class="text-muted">Visit Type</div>
                                <div class="fw-semibold">
                                    {{ ($feeInfo['type'] ?? 'new') === 'follow_up' ? 'Follow-up' : 'Initial' }}</div>
                            </div>
                            <div class="col-md-4">
                                <div class="text-muted">Discount</div>
                                <div class="fw-semibold">{{ $feeInfo['is_discounted'] ?? false ? 'Applied' : 'None' }}
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            @if ($consultation->prescriptions->isEmpty())
                                @can('create', App\Models\Prescription::class)
                                    @if ($consultation->status !== 'completed')
                                        <a href="{{ route('clinical.prescriptions.create.withConsultation', $consultation->id) }}"
                                            class="btn btn-sm btn-primary">Create Prescription</a>
                                    @endif
                                @endcan
                            @else
                                <div class="d-flex gap-2 flex-wrap">
                                    @foreach ($consultation->prescriptions as $p)
                                        <a href="{{ route('clinical.prescriptions.show', $p->id) }}"
                                            class="btn btn-sm btn-outline-primary">View Prescription
                                            #{{ $p->id }}</a>
                                    @endforeach
                                    @can('create', App\Models\Prescription::class)
                                        @if ($consultation->status !== 'completed')
                                            <a href="{{ route('clinical.prescriptions.create.withConsultation', $consultation->id) }}"
                                                class="btn btn-sm btn-primary">Add Prescription</a>
                                        @endif
                                    @endcan
                                </div>
                            @endif
                        </div>
                        <div class="row g-3 mb-3">
                            <div class="col-md-4">
                                <div class="text-muted">Symptoms</div>
                                @php
                                    $symptoms = is_array($consultation->symptoms) ? $consultation->symptoms : ($consultation->symptoms ? [$consultation->symptoms] : []);
                                @endphp
                                @if (!empty($symptoms))
                                    <ul class="mb-0 ps-3">
                                        @foreach ($symptoms as $symptom)
                                            <li>{{ $symptom }}</li>
                                        @endforeach
                                    </ul>
                                @else
                                    <div class="fw-semibold">N/A</div>
                                @endif
                            </div>
                            <div class="col-md-4">
                                <div class="text-muted">Diagnosis</div>
                                <div class="fw-semibold">{{ $consultation->diagnosis ?? 'N/A' }}</div>
                            </div>
                            <div class="col-md-4">
                                <div class="text-muted">Notes</div>
                                <div class="fw-semibold">{{ optional($consultation->prescription)->notes ?? 'N/A' }}</div>
                            </div>
                        </div>
                        <div class="fw-semibold mb-2">Prescription Items</div>

                        @forelse($consultation->prescriptions as $prescription)
                            <div class="card mb-3 border">
                                <div class="card-header bg-light py-2 px-3">
                                    <span class="fw-medium">Prescription #{{ $prescription->id }}</span>
                                    <span
                                        class="text-muted small ms-2">({{ $prescription->issued_at ? $prescription->issued_at : 'N/A' }})</span>
                                </div>
                                <div class="table-responsive">
                                    <table class="table table-hover align-middle mb-0 datatable">
                                        <thead class="table-light">
                                            <tr>
                                                <th>Medicine</th>
                                                <th>Dosage</th>
                                                <th>Frequency</th>
                                                <th>Duration (days)</th>
                                                <th>Instructions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse ($prescription->items as $item)
                                                <tr>
                                                    <td>{{ optional($item->medicine)->name ?? 'Medicine' }}</td>
                                                    <td>{{ $item->dosage ?? 'N/A' }}</td>
                                                    <td>{{ $item->frequency ?? 'N/A' }}</td>
                                                    <td>{{ $item->duration_days ?? 'N/A' }}</td>
                                                    <td>{{ $item->instructions ?? 'N/A' }}</td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="5" class="text-center text-muted">No items</td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        @empty
                            <div class="alert alert-light text-center">No prescriptions found</div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
