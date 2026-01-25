<x-app-layout>
    <div class="container-fluid">

        <div class="card mt-3 mx-2">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h3 class="page-title mb-0">Order Lab Test</h3>
                    <a href="{{ route('lab.index') }}" class="btn btn-outline-secondary">Back</a>
                </div>
                <hr>
                <form method="post" action="{{ route('lab.store') }}">
                    @csrf
                    <input type="hidden" name="appointment_id" value="{{ $appointmentId ?? '' }}">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Patient</label>
                            <select name="patient_id" class="form-select select2-patient" required>
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
                            <label class="form-label">Test</label>
                            <select name="lab_test_id" class="form-select" required>
                                <option value="">Select test</option>
                                @foreach ($tests as $t)
                                    <option value="{{ $t->id }}">{{ $t->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        @if (isset($doctor) && $doctor)
                            <input type="hidden" name="doctor_id" value="{{ $doctor->id }}">
                            <div class="col-md-6">
                                <label class="form-label">Doctor</label>
                                <input type="text" class="form-control" value="{{ $doctor->user->name }}" disabled>
                            </div>
                        @else
                            <div class="col-md-6">
                                <label class="form-label">Doctor (optional)</label>
                                <select name="doctor_id" class="form-select">
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

                        <div class="col-md-12">
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </div>
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
