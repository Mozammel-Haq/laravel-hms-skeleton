<x-app-layout>
    <div class="container-fluid mx-2 mt-2">
        <div class="card shadow-sm border-0">

            <div class="card-body p-3">
                            <div class="d-flex justify-content-between align-items-center bg-primary-subtle text-primary px-4 pt-4 pb-3 rounded-top">
                <div>
                    <h5 class="fw-bold mb-1 text-primary">Order Lab Test</h5>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb breadcrumb-dots mb-0">
                            <li class="breadcrumb-item"><a href="{{ route('lab.index') }}">Lab Tests</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Order Lab Test</li>
                        </ol>
                    </nav>
                </div>
                <a href="{{ route('lab.index') }}" class="btn btn-sm btn-outline-primary">
                    <i class="ti ti-arrow-left me-1"></i> Back to List
                </a>
            </div>

            <hr>
                <form method="post" action="{{ route('lab.store') }}">
                    @csrf
                    <input type="hidden" name="appointment_id" value="{{ $appointmentId ?? '' }}">

                    <h5 class="text-primary fw-bold mb-3 border-bottom pb-2">Order Details</h5>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">Patient <span class="text-danger">*</span></label>
                            <select name="patient_id" class="form-select form-select-sm select2-patient" required>
                                <option value="">Select patient</option>
                                @if (isset($patients))
                                    @foreach ($patients as $patient)
                                        <option value="{{ $patient->id }}" selected>
                                            {{ $patient->name }} ({{ $patient->patient_code }})
                                        </option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">Test <span class="text-danger">*</span></label>
                            <select name="lab_test_id" class="form-select form-select-sm" required>
                                <option value="">Select test</option>
                                @foreach ($tests as $t)
                                    <option value="{{ $t->id }}">{{ $t->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        @if (isset($doctor) && $doctor)
                            <input type="hidden" name="doctor_id" value="{{ $doctor->id }}">
                            <div class="col-md-6">
                                <label class="form-label small fw-semibold">Doctor</label>
                                <input type="text" class="form-control form-control-sm bg-light" value="{{ $doctor->user->name }}" disabled>
                            </div>
                        @else
                            <div class="col-md-6">
                                <label class="form-label small fw-semibold">Doctor (optional)</label>
                                <select name="doctor_id" class="form-select form-select-sm">
                                    <option value="">Select doctor</option>
                                    @if (isset($doctors))
                                        @foreach ($doctors as $d)
                                            <option value="{{ $d->id }}">{{ $d->user->name ?? $d->id }}
                                            </option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                        @endif
                    </div>

                    <div class="d-flex justify-content-end gap-2 mt-4 pt-3 border-top">
                        <a href="{{ route('lab.index') }}" class="btn btn-sm btn-secondary">Cancel</a>
                        <button type="submit" class="btn btn-sm btn-primary px-4">Submit Order</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @push('scripts')
        <script>
            $(document).ready(function() {
                // Initialize Select2 with AJAX
                $('.select2-patient').select2({
                    theme: 'bootstrap-5',
                    ajax: {
                        url: '{{ route('patients.search') }}',
                        dataType: 'json',
                        delay: 250,
                        data: function(params) {
                            return {
                                term: params.term,
                                page: params.page,
                                type: 'lab_eligible'
                            };
                        },
                        processResults: function(data, params) {
                            params.page = params.page || 1;
                            return {
                                results: data.results,
                                pagination: {
                                    more: data.pagination.more
                                }
                            };
                        },
                        cache: true
                    },
                    placeholder: 'Search for a patient',
                    minimumInputLength: 0,
                    allowClear: true,
                    width: '100%'
                });
            });
        </script>
    @endpush
</x-app-layout>
