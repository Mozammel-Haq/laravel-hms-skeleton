<x-app-layout>
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-4 card-body">
            <div>
                <h3 class="page-title mb-0">New Consultation</h3>
                <div class="text-muted">
                    Patient: {{ $patient->name }} |
                    Appointment: {{ $appointment->appointment_date }}
                </div>
            </div>
            <a href="{{ route('appointments.index') }}" class="btn btn-outline-secondary">
                <i class="ti ti-arrow-left me-1"></i> Back
            </a>
        </div>

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
                        <div class="card-header bg-transparent py-3">
                            <h5 class="card-title mb-0">Latest Vitals</h5>
                        </div>
                        <div class="card-body">
                            @if (isset($latestVitals) && $latestVitals)
                                <div class="mb-1">Recorded At: {{ $latestVitals->recorded_at?->format('d M Y H:i') }}
                                </div>
                                <div class="mb-1">Temperature: {{ $latestVitals->temperature }}</div>
                                <div class="mb-1">Pulse: {{ $latestVitals->heart_rate }}</div>
                                <div class="mb-1">BP: {{ $latestVitals->blood_pressure }}</div>
                                <div class="mb-1">Resp Rate: {{ $latestVitals->respiratory_rate }}</div>
                            @else
                                <div class="text-muted">No vitals recorded for this visit.</div>
                            @endif
                        </div>
                    </div>

                    <div class="d-grid">
                        <button class="btn btn-primary btn-lg">
                            <i class="ti ti-check me-2"></i> Save Consultation
                        </button>
                        @if (isset($appointment->visit) && $appointment->visit)
                            <a href="{{ route('vitals.record', ['visit_id' => $appointment->visit->id, 'appointment_id' => $appointment->id]) }}"
                                class="btn btn-outline-success btn-lg mt-2">
                                <i class="ti ti-heart-rate-monitor me-2"></i> Record Vitals
                            </a>
                        @endif
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
    </script>
</x-app-layout>
