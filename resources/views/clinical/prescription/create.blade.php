<x-app-layout>
<div class="content">

    <!-- start row -->
    <div class="row m-auto justify-content-center">
        <div class="col-lg-10">

            <!-- Start Page Header -->
            <div class="d-flex align-items-sm-center flex-sm-row flex-column gap-2 mb-3">
                <div class="flex-grow-1">
                    <h6 class="fw-bold mb-0 d-flex align-items-center">
                        <a href="{{ route('clinical.consultations.show', $consultation->id) }}">
                            <i class="ti ti-chevron-left me-1 fs-14"></i>Prescription
                        </a>
                    </h6>
                </div>
            </div>
            <!-- End Page Header -->

            <div class="card">
                <div class="card-body">
                    <form method="POST" action="{{ route('clinical.prescriptions.store', $consultation->id) }}">
                        @csrf

                        <!-- Header (EXACT) -->
                        <div class="d-flex align-items-center justify-content-between border-1 border-bottom pb-3 mb-3">
                            <div class="invoice-logo">
                                <img src="{{ asset('assets/img/logo.svg') }}" class="logo-white" alt="logo">
                                <img src="{{ asset('assets/img/logo-white.svg') }}" class="logo-dark" alt="logo">
                            </div>
                            <span class="badge bg-danger text-white"> New Prescription </span>
                        </div>

                        <!-- Clinic & Doctor Details (New Style) -->
                        <div class="d-flex align-items-center justify-content-between border-1 border-bottom pb-3 mb-3 flex-wrap gap-3">
                            <div class="d-flex align-items-center gap-3">
                                <div class="avatar avatar-xxl rounded bg-light border p-2">
                                    <img src="{{ asset('assets/img/icons/trust-care.svg') }}" alt="clinic-logo" class="img-fluid">
                                </div>
                                <div>
                                    <h6 class="text-dark fw-semibold mb-1">{{ $consultation->clinic->name ?? 'Clinic Name' }}</h6>
                                    <p class="mb-1">Dr. {{ auth()->user()->name }}</p>
                                    <p class="mb-0">MD Cardiologist. MBBS, MS</p>
                                </div>
                            </div>

                            <div class="text-lg-end">
                                <p class="text-dark mb-1">Department : <span class="text-body">Cardiology OP</span></p>
                                <p class="text-dark mb-1">Prescribed on : <span class="text-body">{{ now()->format('d M Y') }}</span></p>
                                <p class="text-dark mb-0">Consultation : <span class="text-body">#{{ $consultation->id }}</span></p>
                            </div>
                        </div>

<div class="mb-4">
    <h6 class="mb-2 fs-14 fw-medium"> Patient Details </h6>
    <div class="px-3 py-2 bg-light rounded d-flex align-items-center justify-content-between">
        <select name="patient_id" class="form-select m-0" style="max-width: 200px;">
            <option value="">Select Patient</option>
            <option value="1">M. Reyan Verol</option>
            <option value="2">A. John Smith</option>
            <option value="3">L. Emma Watson</option>
            <option value="4">K. Sophia Lee</option>
        </select>
        <div class="d-flex align-items-center gap-3">
            <p class="mb-0 text-dark">28Y / Male</p>
            <p class="mb-0 text-dark"><span class="text-body">Blood</span> : O+ve</p>
            <p class="mb-0 text-dark">Patient ID <span class="text-body">PT0025</span></p>
        </div>
    </div>
</div>


                        <!-- Patient Complaints (Smaller Input) -->
                        <div class="mb-4">
                            <h6 class="mb-3 fs-16 fw-bold"> Patient Complaints </h6>

                            <div id="complaint-wrapper">
                                <div class="d-flex gap-2 mb-2">
                                    <input type="text"
                                           name="complaints[]"
                                           class="form-control w-100"
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
                                                        <option value="{{ $med->id }}">{{ $med->name }}</option>
                                                    @endforeach
                                                </select>
                                            </td>
                                            <td><input type="text" name="items[0][dosage]" class="form-control"></td>
                                            <td><input type="text" name="items[0][frequency]" class="form-control"></td>
                                            <td><input type="number" name="items[0][duration_days]" class="form-control"></td>
                                            <td><input type="text" name="items[0][instructions]" class="form-control"></td>
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

                        <!-- Follow-up + Notes (Wider Textarea) -->
                        <div class="row pb-3 mb-3 border-1 border-bottom">
                            <div class="col-lg-4">
                                <h6 class="mb-2 mt-2 fs-16 fw-bold"> Follow Up </h6>
                                <div class="form-check">
                                    <input class="form-check-input"
                                           type="checkbox"
                                           name="is_followup"
                                           value="1"
                                           required>
                                    <label class="form-check-label">
                                        Follow-up required
                                    </label>
                                </div>
                            </div>

                            <div class="col-lg-8">
                                <h6 class="mb-2 fs-16 fw-bold"> Notes / Advice </h6>
                                <textarea name="notes" class="form-control w-100" rows="4"></textarea>
                            </div>
                        </div>

                        <!-- Terms + Signature (EXACT) -->
                        <div class="pb-3 mb-3 border-1 border-bottom d-flex align-items-center justify-content-between flex-wrap gap-2">
                            <div>
                                <div class="mb-3">
                                    <h6 class="mb-1 fs-14 fw-semibold"> Terms and Conditions </h6>
                                    <p> Medicines must be taken exactly as prescribed. </p>
                                </div>
                            </div>

                            <div>
                                <img src="{{ asset('assets/img/icons/signature-img.svg') }}" class="img-fluid">
                                <h6 class="fs-14 fw-semibold"> Dr. {{ auth()->user()->name }} </h6>
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
document.addEventListener("DOMContentLoaded", function () {
    let rowIndex = 1;
    const tbody = document.getElementById('medicine-rows');

    // Add row
    tbody.addEventListener('click', function (e) {
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
                    <button type="button" class="btn btn-sm btn-outline-danger remove-row">
                        <i class="ti ti-trash fs-24"></i>
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

@endpush

</x-app-layout>
