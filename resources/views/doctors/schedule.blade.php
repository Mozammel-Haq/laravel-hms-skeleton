<x-app-layout>
    <div class="container-fluid">


        <div class="row justify-content-center">
            <div class="col-lg-12">
                <div class="card border-0 mt-2">
                    <div class="card-body p-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h3 class="page-title mb-0">Manage Schedule</h3>
                <div class="text-muted">Dr. {{ $doctor->user?->name ?? 'Deleted Doctor' }}
                    ({{ $doctor->specialization }})</div>
            </div>
            <a href="{{ route('doctors.index') }}" class="btn btn-outline-secondary">
                <i class="ti ti-arrow-left me-1"></i> Back to Doctors
            </a>
        </div>
                        @if ($errors->any())
                            <div class="alert alert-danger mb-4">
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <div class="alert alert-info d-flex align-items-center mb-4">
                            <i class="ti ti-info-circle fs-4 me-2"></i>
                            <div>
                                Define the weekly working hours for this doctor. Appointment slots will be generated
                                based on these settings.
                            </div>
                        </div>

                        <form action="{{ route('doctors.schedule.update', $doctor) }}" method="POST"
                            id="schedule-form">
                            @csrf
                            @method('PUT')

                            <div class="table-responsive mb-4">
                                <table class="table table-bordered align-middle" id="schedule-table">
                                    <thead class="table-light">
                                        <tr>
                                            <th style="width: 15%">Type</th>
                                            <th style="width: 25%">Day / Date</th>
                                            <th style="width: 20%">Start Time</th>
                                            <th style="width: 20%">End Time</th>
                                            <th style="width: 15%">Slot (min)</th>
                                            <th style="width: 5%" class="text-center">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody id="schedule-container">
                                        @forelse($schedules as $index => $schedule)
                                            @php
                                                $type = $schedule->schedule_date ? 'date' : 'weekly';
                                            @endphp
                                            <tr>
                                                <td>
                                                    <select name="schedules[{{ $index }}][type]"
                                                        class="form-select schedule-type" onchange="toggleType(this)">
                                                        <option value="weekly"
                                                            {{ $type == 'weekly' ? 'selected' : '' }}>Weekly</option>
                                                        <option value="date" {{ $type == 'date' ? 'selected' : '' }}>
                                                            Specific Date</option>
                                                    </select>
                                                </td>
                                                <td>
                                                    <div class="weekly-field"
                                                        style="display: {{ $type == 'weekly' ? 'block' : 'none' }}">
                                                        <select name="schedules[{{ $index }}][day_of_week]"
                                                            class="form-select"
                                                            {{ $type == 'weekly' ? 'required' : '' }}>
                                                            <option value="0"
                                                                {{ $schedule->day_of_week == 0 ? 'selected' : '' }}>
                                                                Sunday</option>
                                                            <option value="1"
                                                                {{ $schedule->day_of_week == 1 ? 'selected' : '' }}>
                                                                Monday</option>
                                                            <option value="2"
                                                                {{ $schedule->day_of_week == 2 ? 'selected' : '' }}>
                                                                Tuesday</option>
                                                            <option value="3"
                                                                {{ $schedule->day_of_week == 3 ? 'selected' : '' }}>
                                                                Wednesday</option>
                                                            <option value="4"
                                                                {{ $schedule->day_of_week == 4 ? 'selected' : '' }}>
                                                                Thursday</option>
                                                            <option value="5"
                                                                {{ $schedule->day_of_week == 5 ? 'selected' : '' }}>
                                                                Friday</option>
                                                            <option value="6"
                                                                {{ $schedule->day_of_week == 6 ? 'selected' : '' }}>
                                                                Saturday</option>
                                                        </select>
                                                    </div>
                                                    <div class="date-field"
                                                        style="display: {{ $type == 'date' ? 'block' : 'none' }}">
                                                        <input type="date"
                                                            name="schedules[{{ $index }}][schedule_date]"
                                                            class="form-control" value="{{ $schedule->schedule_date }}"
                                                            {{ $type == 'date' ? 'required' : '' }}>
                                                    </div>
                                                </td>
                                                <td>
                                                    <input type="time"
                                                        name="schedules[{{ $index }}][start_time]"
                                                        class="form-control"
                                                        value="{{ \Carbon\Carbon::parse($schedule->start_time)->format('H:i') }}"
                                                        required>
                                                </td>
                                                <td>
                                                    <input type="time"
                                                        name="schedules[{{ $index }}][end_time]"
                                                        class="form-control"
                                                        value="{{ \Carbon\Carbon::parse($schedule->end_time)->format('H:i') }}"
                                                        required>
                                                </td>
                                                <td>
                                                    <input type="number"
                                                        name="schedules[{{ $index }}][slot_duration_minutes]"
                                                        class="form-control"
                                                        value="{{ $schedule->slot_duration_minutes }}" min="5"
                                                        step="5" required>
                                                </td>
                                                <td class="text-center">
                                                    <button type="button"
                                                        class="btn btn-outline-danger btn-sm remove-row">
                                                        <i class="ti ti-trash"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                        @empty
                                            <!-- Empty state handled by JS if needed, or just show one empty row -->
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>

                            <div class="d-flex justify-content-between align-items-center">
                                <button type="button" class="btn btn-outline-primary" id="add-slot-btn">
                                    <i class="ti ti-plus me-1"></i> Add Time Slot
                                </button>

                                <div class="d-flex gap-2">
                                    <a href="{{ route('doctors.index') }}" class="btn btn-light">Cancel</a>
                                    <button type="submit" class="btn btn-primary">Save Schedule</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const container = document.getElementById('schedule-container');
            const addBtn = document.getElementById('add-slot-btn');

            // If no schedules, add one empty row
            if (container.children.length === 0) {
                addSlotRow();
            }

            addBtn.addEventListener('click', addSlotRow);

            container.addEventListener('click', function(e) {
                if (e.target.closest('.remove-row')) {
                    e.target.closest('tr').remove();
                }
            });

            function addSlotRow() {
                const index = container.children
                .length; // Simple index logic, might need timestamp for unique keys if deleting
                // Better to use a counter that only increments to avoid index collisions if we delete rows
                const uniqueIndex = Date.now();

                const tr = document.createElement('tr');
                tr.innerHTML = `
                    <td>
                        <select name="schedules[${uniqueIndex}][type]" class="form-select schedule-type" onchange="toggleType(this)">
                            <option value="weekly" selected>Weekly</option>
                            <option value="date">Specific Date</option>
                        </select>
                    </td>
                    <td>
                        <div class="weekly-field">
                            <select name="schedules[${uniqueIndex}][day_of_week]" class="form-select" required>
                                <option value="">Select Day</option>
                                <option value="1">Monday</option>
                                <option value="2">Tuesday</option>
                                <option value="3">Wednesday</option>
                                <option value="4">Thursday</option>
                                <option value="5">Friday</option>
                                <option value="6">Saturday</option>
                                <option value="0">Sunday</option>
                            </select>
                        </div>
                        <div class="date-field" style="display: none;">
                            <input type="date" name="schedules[${uniqueIndex}][schedule_date]" class="form-control">
                        </div>
                    </td>
                    <td>
                        <input type="time" name="schedules[${uniqueIndex}][start_time]" class="form-control" value="09:00" required>
                    </td>
                    <td>
                        <input type="time" name="schedules[${uniqueIndex}][end_time]" class="form-control" value="17:00" required>
                    </td>
                    <td>
                        <input type="number" name="schedules[${uniqueIndex}][slot_duration_minutes]" class="form-control" value="15" min="5" step="5" required>
                    </td>
                    <td class="text-center">
                        <button type="button" class="btn btn-outline-danger btn-sm remove-row">
                            <i class="ti ti-trash"></i>
                        </button>
                    </td>
                `;
                container.appendChild(tr);
            }

            window.toggleType = function(select) {
                const tr = select.closest('tr');
                const weeklyField = tr.querySelector('.weekly-field');
                const dateField = tr.querySelector('.date-field');

                if (select.value === 'weekly') {
                    weeklyField.style.display = 'block';
                    weeklyField.querySelector('select').required = true;
                    dateField.style.display = 'none';
                    dateField.querySelector('input').required = false;
                } else {
                    weeklyField.style.display = 'none';
                    weeklyField.querySelector('select').required = false;
                    dateField.style.display = 'block';
                    dateField.querySelector('input').required = true;
                }
            }
        });
    </script>
</x-app-layout>
