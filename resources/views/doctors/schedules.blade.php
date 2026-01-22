<x-app-layout>
    <div class="container-fluid" x-data="scheduleCalendar()" x-init="init();
    view = '{{ request('view', 'calendar') }}'">
        <div class="d-flex justify-content-between align-items-center my-3 px-3">
            <h3 class="page-title mb-0">Doctor Schedules</h3>
            <div class="d-flex gap-2">
                <div class="btn-group">
                    <button class="btn" :class="view === 'calendar' ? 'btn-primary' : 'btn-outline-primary'"
                        @click="view = 'calendar'">
                        <i class="bi bi-calendar3"></i> Calendar
                    </button>
                    <button class="btn" :class="view === 'list' ? 'btn-primary' : 'btn-outline-primary'"
                        @click="view = 'list'">
                        <i class="bi bi-list"></i> List
                    </button>
                </div>
                <a href="{{ route('doctors.index') }}" class="btn btn-outline-secondary">Back to Doctors</a>
            </div>
        </div>

        <!-- Calendar View -->
        <div x-show="view === 'calendar'" style="display: none;">
            <div class="card shadow-sm">
                <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
                    <button class="btn btn-outline-secondary btn-sm" @click="prevMonth()">
                        <i class="bi bi-chevron-left"></i> Previous
                    </button>
                    <div class="d-flex align-items-center gap-2">
                        <h4 class="mb-0 fw-bold" x-text="monthName + ' ' + year"></h4>
                        <div x-show="loading" class="spinner-border spinner-border-sm text-primary" role="status">
                        </div>
                    </div>
                    <button class="btn btn-outline-secondary btn-sm" @click="nextMonth()">
                        Next <i class="bi bi-chevron-right"></i>
                    </button>
                </div>
                <div class="card-body p-0">
                    <!-- Weekday Headers -->
                    <div class="row g-0 text-center border-bottom bg-light fw-bold py-2">
                        <div class="col">Sun</div>
                        <div class="col">Mon</div>
                        <div class="col">Tue</div>
                        <div class="col">Wed</div>
                        <div class="col">Thu</div>
                        <div class="col">Fri</div>
                        <div class="col">Sat</div>
                    </div>

                    <!-- Calendar Grid -->
                    <div class="calendar-grid">
                        <template x-for="(week, wIndex) in calendarDays" :key="wIndex">
                            <div class="row g-0 border-bottom">
                                <template x-for="(day, dIndex) in week" :key="dIndex">
                                    <div class="col border-end p-2 position-relative transition-all"
                                        style="min-height: 120px; cursor: pointer;"
                                        :class="{
                                            'bg-light text-muted': !day.isCurrentMonth,
                                            'bg-white hover-bg-gray-100': day
                                                .isCurrentMonth
                                        }"
                                        @click="selectDate(day)">

                                        <div class="d-flex justify-content-between align-items-start mb-2">
                                            <span class="fw-semibold" :class="{ 'text-primary': isToday(day.fullDate) }"
                                                x-text="day.date"></span>
                                            <template x-if="day.doctors.length > 0">
                                                <span class="badge bg-success rounded-pill"
                                                    x-text="day.doctors.length"></span>
                                            </template>
                                        </div>

                                        <div class="small overflow-hidden">
                                            <template x-for="doc in day.doctors.slice(0, 3)">
                                                <div class="text-truncate text-muted mb-1" style="font-size: 0.75rem;">
                                                    <i class="bi bi-person-fill"></i> <span x-text="doc.name"></span>
                                                </div>
                                            </template>
                                            <template x-if="day.doctors.length > 3">
                                                <div class="text-primary fst-italic" style="font-size: 0.75rem;"
                                                    x-text="'+' + (day.doctors.length - 3) + ' more'"></div>
                                            </template>
                                        </div>
                                    </div>
                                </template>
                            </div>
                        </template>
                    </div>
                </div>
            </div>
        </div>

        <!-- List View (Original) -->
        <div x-show="view === 'list'" style="display: none;">
            <div class="card shadow-sm">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle datatable datatable-server">
                            <thead class="table-light">
                                <tr>
                                    <th>Doctor</th>
                                    <th>Availability</th>
                                    <th>Time Slots</th>
                                    <th class="text-end">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($doctors as $doctor)
                                    <tr>
                                        <td>
                                            <div class="fw-semibold">{{ $doctor->user?->name ?? 'Deleted Doctor' }}
                                            </div>
                                            <div class="text-muted small">{{ $doctor->specialization }}</div>
                                        </td>
                                        <td>
                                            @php
                                                $days = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];
                                                $activeDays = $doctor->schedules->pluck('day_of_week')->toArray();
                                            @endphp
                                            @foreach ($days as $index => $day)
                                                <span
                                                    class="badge {{ in_array($index, $activeDays) ? 'bg-success' : 'bg-light text-dark border' }} me-1">{{ $day }}</span>
                                            @endforeach
                                        </td>
                                        <td>
                                            @if ($doctor->schedules->isNotEmpty())
                                                <div class="d-flex flex-column">
                                                    <span>{{ \Carbon\Carbon::parse($doctor->schedules->first()->start_time)->format('H:i') }}
                                                        -
                                                        {{ \Carbon\Carbon::parse($doctor->schedules->first()->end_time)->format('H:i') }}</span>
                                                    @if ($doctor->schedules->count() > 1)
                                                        <small
                                                            class="text-muted">(+{{ $doctor->schedules->count() - 1 }}
                                                            more)</small>
                                                    @endif
                                                </div>
                                            @else
                                                <span class="text-muted fst-italic">Not Set</span>
                                            @endif
                                        </td>
                                        <td class="text-end">
                                            <div class="dropdown">
                                                <button class="btn btn-sm btn-light btn-icon dropdown-toggle hide-arrow"
                                                    type="button" data-bs-toggle="dropdown">
                                                    <i class="ti ti-dots-vertical"></i>
                                                </button>
                                                <ul class="dropdown-menu dropdown-menu-end">
                                                    <li>
                                                        <a class="dropdown-item"
                                                            href="{{ route('doctors.schedule', $doctor) }}">
                                                            <i class="ti ti-pencil me-1"></i> Edit
                                                        </a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-3">
                        {{ $doctors->links() }}
                    </div>
                </div>
            </div>
        </div>

        <!-- Details Modal -->
        <div class="modal fade" id="scheduleDetailModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">
                            Availability for <span x-text="formatDate(selectedDateStr)"></span>
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div x-show="selectedDateDoctors.length === 0" class="text-center py-5 text-muted">
                            <i class="bi bi-calendar-x display-4 mb-3 d-block"></i>
                            <p>No doctors available on this date.</p>
                        </div>

                        <div class="row g-3">
                            <template x-for="doc in selectedDateDoctors" :key="doc.id">
                                <div class="col-md-6">
                                    <div class="card h-100 border-primary border-opacity-25">
                                        <div class="card-body">
                                            <div class="d-flex justify-content-between mb-2">
                                                <h6 class="card-title fw-bold mb-0" x-text="doc.name"></h6>
                                                <span class="badge"
                                                    :class="doc.type === 'specific' ? 'bg-info' : 'bg-primary'"
                                                    x-text="doc.type === 'specific' ? 'Specific' : 'Weekly'"></span>
                                            </div>
                                            <div class="text-muted small mb-2" x-text="doc.specialization"></div>
                                            <div class="d-flex align-items-center text-dark">
                                                <i class="bi bi-clock me-2"></i>
                                                <span class="fw-semibold"
                                                    x-text="formatTime(doc.start_time) + ' - ' + formatTime(doc.end_time)"></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </template>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <style>
        .hover-bg-gray-100:hover {
            background-color: #f8f9fa !important;
        }

        .transition-all {
            transition: all 0.2s;
        }

        [x-cloak] {
            display: none !important;
        }
    </style>

    <script>
        function scheduleCalendar(defaultView = 'calendar') {
            return {
                view: defaultView,
                currentDate: new Date(),
                events: {},
                loading: false,
                selectedDateDoctors: [],
                selectedDateStr: '',
                detailModal: null,

                get year() {
                    return this.currentDate.getFullYear();
                },
                get month() {
                    return this.currentDate.getMonth() + 1;
                },
                get monthName() {
                    return this.currentDate.toLocaleString('default', {
                        month: 'long'
                    });
                },

                get calendarDays() {
                    const year = this.year;
                    const month = this.month - 1;
                    const firstDay = new Date(year, month, 1);
                    const lastDay = new Date(year, month + 1, 0);

                    const days = [];
                    let start = new Date(firstDay);
                    start.setDate(start.getDate() - start.getDay());

                    let end = new Date(lastDay);
                    end.setDate(end.getDate() + (6 - end.getDay()));

                    let current = new Date(start);

                    // Generate full weeks
                    while (current <= end) {
                        let week = [];
                        for (let i = 0; i < 7; i++) {
                            const dateStr = current.getFullYear() + '-' +
                                String(current.getMonth() + 1).padStart(2, '0') + '-' +
                                String(current.getDate()).padStart(2, '0');

                            week.push({
                                date: current.getDate(),
                                fullDate: dateStr,
                                isCurrentMonth: current.getMonth() === month,
                                doctors: this.events[dateStr] || []
                            });
                            current.setDate(current.getDate() + 1);
                        }
                        days.push(week);
                    }
                    return days;
                },

                init() {
                    this.fetchEvents();
                    // Initialize Bootstrap modal
                    this.$nextTick(() => {
                        const modalEl = document.getElementById('scheduleDetailModal');
                        if (modalEl && window.bootstrap) {
                            this.detailModal = new bootstrap.Modal(modalEl);
                        }
                    });
                },

                fetchEvents() {
                    this.loading = true;
                    // Reset events to trigger reactivity if needed, though replacing object works
                    const url = `{{ route('doctors.schedules.events') }}?year=${this.year}&month=${this.month}`;

                    fetch(url)
                        .then(response => {
                            if (!response.ok) throw new Error('Network response was not ok');
                            return response.json();
                        })
                        .then(data => {
                            this.events = data;
                            this.loading = false;
                        })
                        .catch(error => {
                            console.error('Error fetching events:', error);
                            this.loading = false;
                        });
                },

                prevMonth() {
                    this.currentDate = new Date(this.year, this.month - 2, 1);
                    this.fetchEvents();
                },

                nextMonth() {
                    this.currentDate = new Date(this.year, this.month, 1);
                    this.fetchEvents();
                },

                selectDate(day) {
                    this.selectedDateStr = day.fullDate;
                    this.selectedDateDoctors = day.doctors;
                    if (this.detailModal) {
                        this.detailModal.show();
                    } else {
                        // Fallback if bootstrap object not available directly
                        const modalEl = document.getElementById('scheduleDetailModal');
                        const modal = new bootstrap.Modal(modalEl);
                        this.detailModal = modal;
                        modal.show();
                    }
                },

                isToday(dateStr) {
                    const today = new Date();
                    const todayStr = today.getFullYear() + '-' +
                        String(today.getMonth() + 1).padStart(2, '0') + '-' +
                        String(today.getDate()).padStart(2, '0');
                    return dateStr === todayStr;
                },

                formatDate(dateStr) {
                    if (!dateStr) return '';
                    const date = new Date(dateStr);
                    return date.toLocaleDateString(undefined, {
                        weekday: 'long',
                        year: 'numeric',
                        month: 'long',
                        day: 'numeric'
                    });
                },

                formatTime(timeStr) {
                    if (!timeStr) return '';
                    // Simple parsing for HH:mm:ss
                    const [hours, minutes] = timeStr.split(':');
                    const h = parseInt(hours, 10);
                    const ampm = h >= 12 ? 'PM' : 'AM';
                    const h12 = h % 12 || 12;
                    return `${h12}:${minutes} ${ampm}`;
                }
            }
        }
    </script>
</x-app-layout>
