<x-app-layout>
    <div class="content">

        <!-- start row -->
        <div class="row m-auto justify-content-center">
            <div class="col-lg-10">

                <!-- Start Page Header -->
                <div class="d-flex align-items-sm-center flex-sm-row flex-column gap-2 mb-3">
                    <div class="flex-grow-1">
                        <h6 class="fw-bold mb-0 d-flex align-items-center">
                            <a href="{{ route('clinical.prescriptions.index') }}">
                                <i class="ti ti-chevron-left me-1 fs-14"></i>Prescription
                            </a>
                        </h6>
                    </div>
                </div>
                <!-- End Page Header -->

                <div class="card">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="card-body">
                        <form id="prescription-form" method="POST"
                            action="{{ route('clinical.prescriptions.store', ['consultation' => $consultation]) }}">
                            @csrf
                            <!-- Header (EXACT) -->
                            <div
                                class="d-flex align-items-center justify-content-between border-1 border-bottom pb-3 mb-3">
                                <div class="invoice-logo">
                                    <img src="{{ asset('assets/img/logo.svg') }}" class="logo-white" alt="logo">
                                    <img src="{{ asset('assets/img/logo-white.svg') }}" class="logo-dark"
                                        alt="logo">
                                </div>
                                <span class="badge bg-danger text-white"> New Prescription </span>
                            </div>

                            <!-- Clinic & Doctor Details (New Style) -->
                            <div
                                class="d-flex align-items-center justify-content-between border-1 border-bottom pb-3 mb-3 flex-wrap gap-3">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="avatar avatar-xxl rounded bg-light border p-2">
                                        <img src="{{ asset('assets/img/icons/trust-care.svg') }}" alt="clinic-logo"
                                            class="img-fluid">
                                    </div>
                                    <div>
                                        <h6 class="text-dark fw-semibold mb-1">
                                            {{ optional(auth()->user()->clinic)->name ?? 'Clinic' }}</h6>
                                        <p class="mb-1">Dr.
                                            {{ $consultation->visit?->appointment?->doctor?->user?->name ?? auth()->user()->name }}
                                        </p>
                                        <p class="mb-0">
                                            {{ $consultation->visit?->appointment?->doctor?->department?->name ?? 'Department' }}
                                        </p>
                                    </div>
                                </div>

                                <div class="text-lg-end">
                                    <p class="text-dark mb-1">Department : <span
                                            class="text-body">{{ $consultation->visit?->appointment?->doctor?->department?->name ?? 'N/A' }}</span>
                                    </p>
                                    <p class="text-dark mb-1">Prescribed on : <span
                                            class="text-body">{{ now()->format('d M Y') }}</span></p>
                                    <p class="text-dark mb-0">Consultation : <span
                                            class="text-body">#{{ $consultation->id }}</span></p>
                                </div>
                            </div>

                            <div class="mb-4">
                                <h6 class="mb-2 fs-14 fw-medium"> Patient Details </h6>
                                <div
                                    class="px-3 py-2 bg-light rounded d-flex align-items-center justify-content-between">
                                    <div class="fw-semibold">
                                        {{ optional($consultation->visit->appointment->patient)->name ?? 'Patient' }}
                                    </div>

                                    <div class="d-flex align-items-center gap-3">
                                        <p class="mb-0 text-dark me-2"><span class="text-body">Age</span> :
                                            {{ optional($consultation->visit->appointment->patient)->age ?? 'Null' }}
                                            Years</p>
                                        <p class="mb-0 text-dark"><span class="text-body">Blood</span> :
                                            {{ optional($consultation->visit->appointment->patient)->blood_group ?? 'Null' }}
                                        </p>

                                    </div>
                                    <div class="d-flex align-items-center gap-3">

                                        <p class="mb-0 text-dark">Patient ID : <span
                                                class="text-body">P-00{{ optional($consultation->visit->appointment->patient)->id }}</span>
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <div class="mb-4">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <h6 class="fs-14 fw-medium mb-0">Vitals History (This Visit)</h6>
                                    @php
                                        $patientId = optional($consultation->visit->appointment->patient)->id ?? null;
                                    @endphp
                                    @if ($patientId)
                                        <a href="{{ route('vitals.history', ['patient_id' => $patientId]) }}" target="_blank" class="btn btn-xs btn-outline-primary">
                                            View All Vitals
                                        </a>
                                    @else
                                        <a href="{{ route('vitals.history') }}" target="_blank" class="btn btn-xs btn-outline-p">
                                            View All Vitals
                                        </a>
                                    @endif
                                </div>
                                <div class="border rounded p-2">
                                    @php
                                        $vitals = isset($vitalsHistory) ? $vitalsHistory : collect();
                                    @endphp
                                    @if ($vitals->isNotEmpty())
                                        <div class="table-responsive">
                                            <table class="table table-sm table-hover mb-0">
                                                <thead>
                                                    <tr>
                                                        <th>Date</th>
                                                        <th>Temp</th>
                                                        <th>Pulse</th>
                                                        <th>BP</th>
                                                        <th>Resp</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($vitals as $v)
                                                        <tr>
                                                            <td>{{ $v->recorded_at?->format('d M Y H:i') }}</td>
                                                            <td>{{ $v->temperature }}</td>
                                                            <td>{{ $v->heart_rate }}</td>
                                                            <td>{{ $v->blood_pressure }}</td>
                                                            <td>{{ $v->respiratory_rate }}</td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    @else
                                        <div class="text-muted">No vitals recorded for this visit.</div>
                                    @endif
                                </div>
                            </div>


                            <!-- Patient Complaints (Smaller Input) -->
                            <div class="mb-4">
                                <h6 class="mb-3 fs-16 fw-bold"> Patient Complaints </h6>

                                <div id="complaint-wrapper">
                                    <div class="d-flex gap-2 mb-2">
                                        <input type="text" name="complaints[]" class="form-control w-100"
                                            placeholder="Enter complaint">
                                        <button type="button" class="btn btn-xs btn-primary add-complaint">
                                            <i class="ti ti-plus"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>



                            <!-- Medicines (TABLE BLOCK EXACT) -->
                            <div class="mb-4">
                                <h6 class="mb-3 fs-16 fw-bold"> Prescribed Medicines </h6>

                                <div class="table-responsive border bg-white">
                                    <table class="table table-nowrap">
                                        <thead class="table-light">
                                            <tr>
                                                <th>#</th>
                                                <th>Medicine</th>
                                                <th>Dosage</th>
                                                <th>Frequency</th>
                                                <th>Duration</th>
                                                <th>Instructions</th>
                                                <th class="text-center">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody id="medicine-rows">
                                            <tr>
                                                <td class="row-index">1</td>
                                                <td>
                                                    <select name="items[0][medicine_id]" class="form-select">
                                                        <option value="">Select medicine</option>
                                                        @foreach ($medicines as $med)
                                                            <option value="{{ $med->id }}">{{ $med->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </td>
                                                <td><input type="text" name="items[0][dosage]" class="form-control">
                                                </td>
                                                <td><input type="text" name="items[0][frequency]"
                                                        class="form-control"></td>
                                                <td><input type="number" name="items[0][duration_days]"
                                                        class="form-control"></td>
                                                <td><input type="text" name="items[0][instructions]"
                                                        class="form-control"></td>
                                                <td class="text-center">
                                                    <button type="button" class="btn btn-xs btn-primary add-row">
                                                        <i class="ti ti-plus"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            
                                                        <div class="mb-4">
                                <h6 class="mb-3 fs-16 fw-bold">Notes / Advice</h6>
                                <textarea name="notes" class="form-control" rows="3" placeholder="Enter any notes or advice">{{ old('notes') }}</textarea>
                            </div>
                            <!-- Terms + Signature (EXACT) -->
                            <div
                                class="pb-3 mb-3 border-1 border-bottom d-flex align-items-center justify-content-between flex-wrap gap-2">
                                <div>
                                    <div class="mb-3">
                                        <h6 class="mb-1 fs-14 fw-semibold"> Terms and Conditions </h6>
                                        <p> Medicines must be taken exactly as prescribed. </p>
                                    </div>
                                </div>

                                <div>
                                    <img src="{{ asset('assets/img/icons/signature-img.svg') }}" class="img-fluid">
                                    <h6 class="fs-14 fw-semibold"> Dr.
                                        {{ optional($consultation->visit->appointment->doctor->user)->name ?? auth()->user()->name }}
                                    </h6>
                                    <p class="fs-13 fw-normal"> Authorized Physician </p>
                                </div>
                            </div>

                            <!-- Footer Actions (EXACT) -->
                            <div class="text-center d-flex align-items-center justify-content-center">
                                <button type="submit" class="btn btn-md btn-primary d-flex align-items-center">
                                    <i class="ti ti-device-floppy me-1"></i> Save Prescription
                                </button>
                            </div>

                        </form>
                    </div>
                </div>

            </div>
        </div>
        <!-- end row -->

    </div>



    @push('scripts')
        <script>
            document.addEventListener("DOMContentLoaded", function() {
                let rowIndex = 1;
                const tbody = document.getElementById('medicine-rows');

                // Add row
                tbody.addEventListener('click', function(e) {
                    if (e.target.closest('.add-row')) {
                        const row = document.createElement('tr');

                        row.innerHTML = `
                <td class="row-index">${rowIndex + 1}</td>
                <td>
                    <select name="items[${rowIndex}][medicine_id]" class="form-select">
                        <option value="">Select medicine</option>
                        @foreach ($medicines as $med)
                            <option value="{{ $med->id }}">{{ $med->name }}</option>
                        @endforeach
                    </select>
                </td>
                <td>
                    <input type="text" name="items[${rowIndex}][dosage]" class="form-control" placeholder="1-0-1">
                </td>
                <td>
                    <input type="text" name="items[${rowIndex}][frequency]" class="form-control" placeholder="BID">
                </td>
                <td>
                    <input type="number" name="items[${rowIndex}][duration_days]" class="form-control" min="1" value="5">
                </td>
                <td>
                    <input type="text" name="items[${rowIndex}][instructions]" class="form-control" placeholder="After food">
                </td>
                <td class="text-center">
                    <button type="button" class="btn btn-xs btn-outline-danger remove-row">
                        <i class="ti ti-trash fs-16"></i>
                    </button>
                </td>
            `;

                        tbody.appendChild(row);
                        rowIndex++;
                        updateRowNumbers();
                    }

                    // Remove row
                    if (e.target.closest('.remove-row')) {
                        e.target.closest('tr').remove();
                        updateRowNumbers();
                    }
                });

                function updateRowNumbers() {
                    document.querySelectorAll('.row-index').forEach((td, index) => {
                        td.textContent = index + 1;
                    });
                }
            });
        </script>
        <script>
            document.addEventListener('click', function(e) {
                if (e.target.closest('.add-complaint')) {
                    const wrapper = document.getElementById('complaint-wrapper');
                    const div = document.createElement('div');
                    div.className = 'd-flex gap-2 mb-2';
                    div.innerHTML = `
            <input type="text" name="complaints[]" class="form-control">
            <button type="button" class="btn btn-xs btn-danger remove-complaint">
                <i class="ti ti-trash"></i>
            </button>
        `;
                    wrapper.appendChild(div);
                }

                if (e.target.closest('.remove-complaint')) {
                    e.target.closest('.d-flex').remove();
                }
            });
        </script>
    @endpush

</x-app-layout>
