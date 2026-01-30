<x-app-layout>
    <div class="container-fluid mx-2 mt-3">
        <div class="d-flex justify-content-between align-items-center mb-4 card-body">
            <div>
                <h3 class="page-title mb-0">New Consultation</h3>
                <div class="text-muted">
                    Patient: {{ $patient->name }} |
                    Appointment: {{ $appointment->appointment_date }}
                </div>
            </div>
            <a href="{{ route('appointments.index') }}" class="btn btn-outline-primary">
                <i class="ti ti-arrow-left me-1"></i> Back
            </a>
        </div>
        <hr>

        <form action="{{ route('clinical.consultations.store', $appointment) }}" method="POST">
            @csrf

            <div class="row">
                <div class="col-lg-8">
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-header bg-transparent py-3">
                            <h5 class="card-title mb-0">Clinical Notes</h5>
                        </div>

                        <div class="card-body">
                            <div class="mb-3">
                                <label class="form-label">Symptoms</label>
                                <div id="symptom-wrapper">
                                    <div class="d-flex gap-2 mb-2">
                                        <input type="text" name="symptoms[]" class="form-control"
                                            placeholder="Enter symptom">
                                        <button type="button" class="btn btn-sm btn-primary add-symptom">
                                            <i class="ti ti-plus"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Diagnosis <span class="text-danger">*</span></label>
                                <textarea name="diagnosis" class="form-control" rows="2" required>{{ old('diagnosis') }}</textarea>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Doctor Notes <span class="text-danger">*</span></label>
                                <textarea name="doctor_notes" class="form-control" rows="4" required>{{ old('doctor_notes') }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right: Follow Up -->
                <div class="col-lg-4">
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-header bg-transparent py-3">
                            <h5 class="card-title mb-0">Follow Up</h5>
                        </div>

                        <div class="card-body">
                            <div class="form-check form-switch mb-3">
                                <input type="checkbox" class="form-check-input" id="follow_up_required"
                                    name="follow_up_required" value="1">
                                <label class="form-check-label" for="follow_up_required">
                                    Follow-up required?
                                </label>
                            </div>

                            <div id="follow_up_date_box" class="d-none">
                                <label class="form-label">Follow-up Date</label>
                                <input type="date" name="follow_up_date" class="form-control">
                            </div>
                        </div>
                    </div>

                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-header bg-transparent py-3 d-flex justify-content-between align-items-center">
                            <h5 class="card-title mb-0">Vitals History</h5>
                            @if (isset($appointment->visit) && $appointment->visit)
                                <a href="{{ route('vitals.record', ['visit_id' => $appointment->visit->id, 'appointment_id' => $appointment->id]) }}"
                                    class="btn btn-sm btn-outline-success">
                                    <i class="ti ti-heart-rate-monitor me-1"></i> Record Vitals
                                </a>
                            @endif
                        </div>
                        <div class="card-body">
                            @php
                                $history = isset($vitalsHistory) ? $vitalsHistory : collect();
                            @endphp
                            @if ($history->isNotEmpty())
                                <div class="mb-3">
                                    <div class="fw-semibold mb-1">Latest</div>
                                    <div class="mb-1">Recorded At:
                                        {{ $history->first()->recorded_at?->format('d M Y H:i') }}</div>
                                    <div class="mb-1">Temperature: {{ $history->first()->temperature }}</div>
                                    <div class="mb-1">Pulse: {{ $history->first()->heart_rate }}</div>
                                    <div class="mb-1">BP: {{ $history->first()->blood_pressure }}</div>
                                    <div class="mb-1">Resp Rate: {{ $history->first()->respiratory_rate }}</div>
                                </div>
                                <hr>
                                <div class="table">
                                    <table class="table table-sm table-hover mb-0 datatable">
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
                                            @foreach ($history as $v)
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

                    <div class="d-grid">
                        <button class="btn btn-primary btn-lg">
                            <i class="ti ti-check me-2"></i> Save Consultation
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <script>
        document.getElementById('follow_up_required')
            .addEventListener('change', function() {
                document.getElementById('follow_up_date_box')
                    .classList.toggle('d-none', !this.checked);
            });

        document.addEventListener('DOMContentLoaded', function() {
            var wrapper = document.getElementById('symptom-wrapper');
            if (!wrapper) {
                return;
            }

            wrapper.addEventListener('click', function(e) {
                if (e.target.closest('.add-symptom')) {
                    var div = document.createElement('div');
                    div.className = 'd-flex gap-2 mb-2';
                    div.innerHTML =
                        '<input type="text" name="symptoms[]" class="form-control" placeholder="Enter symptom"><button type="button" class="btn btn-sm btn-outline-danger remove-symptom"><i class="ti ti-trash"></i></button>';
                    wrapper.appendChild(div);
                } else if (e.target.closest('.remove-symptom')) {
                    var row = e.target.closest('.d-flex');
                    if (row && wrapper.children.length > 1) {
                        wrapper.removeChild(row);
                    } else if (row) {
                        var input = row.querySelector('input[name="symptoms[]"]');
                        if (input) {
                            input.value = '';
                        }
                    }
                }
            });
        });
    </script>
</x-app-layout>
