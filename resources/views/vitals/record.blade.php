<x-app-layout>
    <div class="container-fluid">

        <div class="card mt-2 mx-2">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h3 class="page-title mb-0">Record Vitals</h3>
                    <a href="{{ route('vitals.history') }}" class="btn btn-outline-secondary">View History</a>
                </div>
                <hr>
                <form class="row g-3 mt-3" method="POST" action="{{ route('vitals.store') }}">
                    @csrf
                    @php
                        $singlePatient =
                            $patients instanceof \Illuminate\Support\Collection && $patients->count() === 1
                                ? $patients->first()
                                : null;
                        $hasContext =
                            (isset($visit) && $visit) ||
                            request()->has('admission_id') ||
                            request()->has('appointment_id');
                    @endphp
                    @if ($singlePatient && $hasContext)
                        <div class="col-md-6">
                            <label class="form-label">Patient</label>
                            <input type="hidden" name="patient_id" value="{{ $singlePatient->id }}">
                            <div class="form-control-plaintext fw-semibold">
                                {{ $singlePatient->name ?? ($singlePatient->full_name ?? 'Patient') }}
                            </div>
                        </div>
                    @else
                        <div class="col-md-6">
                            <label class="form-label">Patient</label>
                            <select class="form-select select2-patient" name="patient_id" required>
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
                    @endif
                    @if (isset($visit) && $visit)
                        <input type="hidden" name="visit_id" value="{{ $visit->id }}">
                    @endif
                    @if (request()->has('admission_id'))
                        <input type="hidden" name="admission_id" value="{{ request('admission_id') }}">
                    @endif
                    @if (request()->has('inpatient_round_id'))
                        <input type="hidden" name="inpatient_round_id" value="{{ request('inpatient_round_id') }}">
                    @endif
                    <div class="col-md-3">
                        <label class="form-label">Temperature (°C)</label>
                        <input type="number" step="0.1" class="form-control" name="temperature"
                            value="{{ old('temperature') }}" placeholder="36.6">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Pulse (bpm)</label>
                        <input type="number" class="form-control" name="heart_rate" value="{{ old('heart_rate') }}"
                            placeholder="72">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">BP (mmHg)</label>
                        <input type="text" class="form-control" name="blood_pressure"
                            value="{{ old('blood_pressure') }}" placeholder="120/80">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Respiratory Rate</label>
                        <input type="number" class="form-control" name="respiratory_rate"
                            value="{{ old('respiratory_rate') }}" placeholder="16">
                    </div>
                    <div class="col-12">
                        <button class="btn btn-primary" type="submit">Save</button>
                        <button class="btn btn-outline-secondary" type="reset">Clear</button>
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
                                page: params.page
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
