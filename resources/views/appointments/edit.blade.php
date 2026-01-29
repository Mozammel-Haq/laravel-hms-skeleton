<x-app-layout>
    <div class="container-fluid">
        <div class="card border-0 mt-2 mx-2">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h4 class="page-title mb-0">Edit Appointment</h4>
                    <a href="{{ route('appointments.index') }}" class="btn btn-sm btn-outline-secondary">
                        <i class="ti ti-arrow-left me-1"></i> Back
                    </a>
                </div>
                <hr>

                <form method="POST" action="{{ route('appointments.update', $appointment) }}">
                    @csrf
                    @method('PUT')

                    <div class="row g-3">
                        <!-- Patient Info (Read Only) -->
                        <div class="col-md-6">
                            <label class="form-label">Patient</label>
                            <input type="text" class="form-control bg-light"
                                value="{{ $appointment->patient->name }} ({{ $appointment->patient->patient_code }})"
                                readonly disabled>
                        </div>

                        <!-- Doctor Selection -->
                        <div class="col-md-6">
                            <label for="doctor_id" class="form-label">Doctor</label>
                            <select id="doctor_id" name="doctor_id"
                                class="form-select @error('doctor_id') is-invalid @enderror">
                                <option value="">Select Doctor</option>
                                @foreach ($doctors as $doctor)
                                    <option value="{{ $doctor->id }}"
                                        {{ old('doctor_id', $appointment->doctor_id) == $doctor->id ? 'selected' : '' }}>
                                        {{ $doctor->user->name }}
                                        @if (!empty($doctor->specialization))
                                            ({{ collect($doctor->specialization)->flatten()->implode(', ') }})
                                        @endif
                                    </option>
                                @endforeach
                            </select>
                            @error('doctor_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Date -->
                        <div class="col-md-6">
                            <label for="appointment_date" class="form-label">Date</label>
                            <input id="appointment_date" type="date" name="appointment_date"
                                class="form-control @error('appointment_date') is-invalid @enderror"
                                value="{{ old('appointment_date', $appointment->appointment_date) }}"
                                min="{{ now()->toDateString() }}" required>
                            @error('appointment_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Time (Auto-filled or manual) -->
                        <div class="col-md-6">
                            <label for="start_time" class="form-label">Start Time</label>
                            <input id="start_time" type="time" name="start_time"
                                class="form-control bg-light @error('start_time') is-invalid @enderror"
                                value="{{ old('start_time', \Carbon\Carbon::parse($appointment->start_time)->format('H:i')) }}"
                                required readonly>
                            <div class="form-text">Select a slot below to set the time.</div>
                            @error('start_time')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Available Slots -->
                        <div class="col-12" id="slots-container" style="display: none;">
                            <label class="form-label">Available Slots</label>
                            <div id="slots-list" class="d-flex flex-wrap gap-2 mt-1">
                                <!-- Slots will be injected here via JS -->
                            </div>
                            <p id="no-slots-msg" class="text-danger mt-2 d-none">No slots available for this date.</p>
                        </div>

                        <!-- Type -->
                        <div class="col-md-6">
                            <label for="appointment_type" class="form-label">Type</label>
                            <select id="appointment_type" name="appointment_type"
                                class="form-select @error('appointment_type') is-invalid @enderror">
                                <option value="in_person"
                                    {{ old('appointment_type', $appointment->appointment_type) == 'in_person' ? 'selected' : '' }}>
                                    In Person
                                </option>
                                <option value="online"
                                    {{ old('appointment_type', $appointment->appointment_type) == 'online' ? 'selected' : '' }}>
                                    Online
                                </option>
                            </select>
                            @error('appointment_type')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Status -->
                        <div class="col-md-6">
                            <label for="status" class="form-label">Status</label>
                            <select id="status" name="status"
                                class="form-select @error('status') is-invalid @enderror">
                                <option value="pending"
                                    {{ old('status', $appointment->status) == 'pending' ? 'selected' : '' }}>Pending
                                </option>
                                <option value="confirmed"
                                    {{ old('status', $appointment->status) == 'confirmed' ? 'selected' : '' }}>
                                    Confirmed</option>
                                <option value="arrived"
                                    {{ old('status', $appointment->status) == 'arrived' ? 'selected' : '' }}>Arrived
                                </option>
                                <option value="cancelled"
                                    {{ old('status', $appointment->status) == 'cancelled' ? 'selected' : '' }}>
                                    Cancelled</option>
                                <option value="completed"
                                    {{ old('status', $appointment->status) == 'completed' ? 'selected' : '' }}>
                                    Completed</option>
                                <option value="noshow"
                                    {{ old('status', $appointment->status) == 'noshow' ? 'selected' : '' }}>No Show
                                </option>
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Reason -->
                        <div class="col-12">
                            <label for="reason_for_visit" class="form-label">Reason for Visit</label>
                            <textarea id="reason_for_visit" name="reason_for_visit"
                                class="form-control @error('reason_for_visit') is-invalid @enderror" rows="3">{{ old('reason_for_visit', $appointment->reason_for_visit) }}</textarea>
                            @error('reason_for_visit')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="d-flex justify-content-end mt-4 gap-2">
                        <a href="{{ route('appointments.index') }}" class="btn btn-light">Cancel</a>
                        <button type="submit" class="btn btn-primary">Update Appointment</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const doctorSelect = document.getElementById('doctor_id');
            const dateInput = document.getElementById('appointment_date');
            const slotsContainer = document.getElementById('slots-container');
            const slotsList = document.getElementById('slots-list');
            const noSlotsMsg = document.getElementById('no-slots-msg');
            const startTimeInput = document.getElementById('start_time');

            // Helper to fetch slots
            function fetchSlots() {
                const doctorId = doctorSelect.value;
                const date = dateInput.value;

                if (!doctorId || !date) {
                    slotsContainer.style.display = 'none';
                    return;
                }

                // Show loading state or clear previous
                slotsList.innerHTML = '<span class="text-muted">Loading slots...</span>';
                slotsContainer.style.display = 'block';
                noSlotsMsg.classList.add('d-none');

                // Construct URL: /api/doctors/{doctor}/slots?date={date}
                // We use a placeholder '000' to generate the route, then replace it with the actual ID
                // Use relative path (third argument false) to avoid mixed content issues (HTTP vs HTTPS)
                const urlTemplate = "{{ route('api.doctors.slots', '000', false) }}";
                const url = urlTemplate.replace('000', doctorId) + `?date=${date}`;

                fetch(url)
                    .then(response => {
                        if (!response.ok) {
                            throw new Error(`HTTP error! status: ${response.status}`);
                        }
                        return response.json();
                    })
                    .then(data => {
                        slotsList.innerHTML = '';
                        // API returns array or { slots: [] } depending on controller
                        // AppointmentController returns direct array
                        const slots = Array.isArray(data) ? data : (data.slots || []);
                        
                        if (slots.length === 0) {
                            noSlotsMsg.classList.remove('d-none');
                        } else {
                            slots.forEach(slot => {
                                // AppointmentService returns start_time/end_time
                                // Fallback to start/end if those exist (compatibility)
                                const start = slot.start_time || slot.start;
                                const end = slot.end_time || slot.end;

                                const btn = document.createElement('button');
                                btn.type = 'button';
                                btn.className = 'btn btn-sm btn-outline-primary';
                                btn.textContent = `${start} - ${end}`;

                                // Highlight logic
                                if (startTimeInput.value && start.startsWith(startTimeInput.value
                                        .substring(0, 5))) {
                                    btn.classList.remove('btn-outline-primary');
                                    btn.classList.add('btn-primary');
                                }

                                btn.addEventListener('click', function() {
                                    // Set time
                                    startTimeInput.value = start;
                                    // Update visual selection
                                    Array.from(slotsList.children).forEach(child => {
                                        child.classList.remove('btn-primary');
                                        child.classList.add('btn-outline-primary');
                                    });
                                    btn.classList.remove('btn-outline-primary');
                                    btn.classList.add('btn-primary');
                                });

                                slotsList.appendChild(btn);
                            });
                        }
                    })
                    .catch(error => {
                        console.error('Error fetching slots:', error);
                        slotsList.innerHTML = '<span class="text-danger">Error loading slots.</span>';
                    });
            }

            // Listeners
            doctorSelect.addEventListener('change', fetchSlots);
            dateInput.addEventListener('change', fetchSlots);

            // Initial load if data present
            if (doctorSelect.value && dateInput.value) {
                fetchSlots();
            }
        });
    </script>
</x-app-layout>
