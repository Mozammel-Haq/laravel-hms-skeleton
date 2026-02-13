<x-app-layout>
    <div class="py-4">
        <div class="container">
            <div class="card border-0 shadow-sm p-3">
                <div class="d-flex justify-content-between align-items-center bg-primary-subtle text-primary px-4 py-3 pt-3 rounded-top">
                    <div>
                        <h4 class="fw-bold mb-2 text-primary">Edit Appointment</h4>
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb breadcrumb-dots mb-0">
                                <li class="breadcrumb-item"><a href="{{ route('appointments.index') }}">Appointments</a></li>
                                <li class="breadcrumb-item active" aria-current="page">Edit Appointment</li>
                            </ol>
                        </nav>
                    </div>
                    <a href="{{ route('appointments.index') }}" class="btn btn-sm btn-outline-primary">
                        <i class="ti ti-arrow-left me-1"></i> Back to List
                    </a>
                </div>

                <div class="card-body p-4">
                <form method="POST" action="{{ route('appointments.update', $appointment) }}">
                    @csrf
                    @method('PUT')

                    <h5 class="text-primary fw-bold mb-3 border-bottom pb-2">Appointment Details</h5>

                    <div class="row g-3">
                        <!-- Patient Info (Read Only) -->
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">Patient</label>
                            <input type="text" class="form-control form-control-sm bg-light"
                                value="{{ $appointment->patient->name }} ({{ $appointment->patient->patient_code }})"
                                readonly disabled>
                        </div>

                        <!-- Type -->
                        <div class="col-md-6">
                            <label for="appointment_type" class="form-label small fw-semibold">Type</label>
                            <select id="appointment_type" name="appointment_type"
                                class="form-select form-select-sm @error('appointment_type') is-invalid @enderror">
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

                        <!-- Meeting Link -->
                        <div class="col-md-6">
                            <label for="meeting_link" class="form-label small fw-semibold">Meeting Link</label>
                            <input id="meeting_link" type="url" name="meeting_link"
                                class="form-control form-control-sm @error('meeting_link') is-invalid @enderror"
                                value="{{ old('meeting_link', $appointment->meeting_link) }}"
                                placeholder="https://meet.jit.si/..."
                                {{ old('appointment_type', $appointment->appointment_type) == 'in_person' ? 'disabled' : '' }}>
                            @error('meeting_link')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Doctor Selection -->
                        <div class="col-md-6">
                            <label for="doctor_id" class="form-label small fw-semibold">Doctor</label>
                            <select id="doctor_id" name="doctor_id"
                                class="form-select form-select-sm @error('doctor_id') is-invalid @enderror">
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
                            <label for="appointment_date" class="form-label small fw-semibold">Date</label>
                            <input id="appointment_date" type="date" name="appointment_date"
                                class="form-control form-control-sm @error('appointment_date') is-invalid @enderror"
                                value="{{ old('appointment_date', $appointment->appointment_date->format('Y-m-d')) }}"
                                min="{{ now()->toDateString() }}" required>
                            @error('appointment_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Time (Auto-filled or manual) -->
                        <div class="col-md-6">
                            <label for="start_time" class="form-label small fw-semibold">Start Time</label>
                            <input id="start_time" type="time" name="start_time"
                                class="form-control form-control-sm bg-light @error('start_time') is-invalid @enderror"
                                value="{{ old('start_time', $appointment->start_time->format('H:i')) }}"
                                required readonly>
                            <div class="form-text small">Select a slot below to set the time.</div>
                            @error('start_time')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Available Slots -->
                        <div class="col-12" id="slots-container" style="display: none;">
                            <label class="form-label small fw-bold text-primary">Available Slots</label>
                            <div id="slots-list" class="d-flex flex-wrap gap-2 mt-1 p-3 bg-light rounded border">
                                <!-- Slots will be injected here via JS -->
                            </div>
                            <p id="no-slots-msg" class="text-danger mt-2 d-none small">No slots available for this date.</p>
                        </div>

                        <!-- Status -->
                        <div class="col-md-6">
                            <label for="status" class="form-label small fw-semibold">Status</label>
                            <select id="status" name="status"
                                class="form-select form-select-sm @error('status') is-invalid @enderror">
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
                            <label for="reason_for_visit" class="form-label small fw-semibold">Reason for Visit</label>
                            <textarea id="reason_for_visit" name="reason_for_visit"
                                class="form-control form-control-sm @error('reason_for_visit') is-invalid @enderror" rows="3">{{ old('reason_for_visit', $appointment->reason_for_visit) }}</textarea>
                            @error('reason_for_visit')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="d-flex justify-content-end mt-4 gap-2">
                        <a href="{{ route('appointments.index') }}" class="btn btn-sm btn-light border">Cancel</a>
                        <button type="submit" class="btn btn-sm btn-primary px-4">Update Appointment</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const appointmentTypeSelect = document.getElementById('appointment_type');
            const meetingLinkInput = document.getElementById('meeting_link');

            function toggleMeetingLink() {
                if (appointmentTypeSelect.value === 'online') {
                    meetingLinkInput.disabled = false;
                } else {
                    meetingLinkInput.disabled = true;
                }
            }

            appointmentTypeSelect.addEventListener('change', toggleMeetingLink);
            // toggleMeetingLink(); // Don't run initially as PHP handles the initial state and we don't want to override it if user edits (actually PHP sets disabled attr, so we are good).
            // Wait, if PHP sets disabled, and we change to online, we need to remove it.
            // If PHP doesn't set disabled, and we change to in_person, we need to add it.
            // So running it initially is fine/good to sync JS state.

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
