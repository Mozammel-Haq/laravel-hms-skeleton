<template>
  <div class="leave-calendar-page">
    <div class="card p-3 mb-3 border-0 shadow-sm">
      <div class="d-flex flex-wrap justify-content-between align-items-start gap-3 bg-primary-subtle text-primary px-4 pt-3 pb-3 rounded-3 mb-0">
        <div>
          <h5 class="fw-bold mb-1 text-primary">Leave Calendar</h5>
          <nav aria-label="breadcrumb">
            <ol class="breadcrumb breadcrumb-dots mb-0 text-muted small">
              <li class="breadcrumb-item">
                <router-link to="/">Dashboard</router-link>
              </li>
              <li class="breadcrumb-item"><router-link to="/hr/leaves/requests">Leaves</router-link></li>
              <li class="breadcrumb-item active" aria-current="page">Calendar</li>
            </ol>
          </nav>
        </div>
        <div class="d-flex flex-wrap gap-2 align-items-center">
          <div class="btn-group btn-group-sm" role="group">
            <button
              type="button"
              class="btn"
              :class="viewMode === 'month' ? 'btn-primary' : 'btn-outline-primary'"
              @click="viewMode = 'month'"
            >
              <i class="ti ti-calendar-month me-1"></i> Month
            </button>
            <button
              type="button"
              class="btn"
              :class="viewMode === 'list' ? 'btn-primary' : 'btn-outline-primary'"
              @click="viewMode = 'list'"
            >
              <i class="ti ti-list-details me-1"></i> List
            </button>
          </div>
          <div class="d-flex align-items-center gap-2">
            <button class="btn btn-outline-primary btn-sm" @click="prevMonth">
              <i class="ti ti-chevron-left"></i>
            </button>
            <div class="fw-semibold small text-primary text-nowrap">{{ monthLabel }}</div>
            <button class="btn btn-outline-primary btn-sm" @click="nextMonth">
              <i class="ti ti-chevron-right"></i>
            </button>
          </div>
          <div class="calendar-filters d-flex align-items-center gap-2">
            <input
              v-model="search"
              type="search"
              class="form-control form-control-sm"
              placeholder="Search employee..."
            >
            <button class="btn btn-outline-secondary btn-sm" @click="loadData" :disabled="loading">
              <i class="ti ti-refresh me-1"></i> Refresh
            </button>
          </div>
        </div>
      </div>
    </div>

    <div v-if="error" class="alert alert-danger">{{ error }}</div>

    <div class="row g-3">
      <div class="col-lg-9">
        <div class="card border-0 shadow-sm h-100">
          <div class="card-body">
            <div v-if="viewMode === 'month'" class="calendar-scroll">
              <div class="calendar-grid">
                <div class="calendar-header" v-for="d in weekDays" :key="d">{{ d }}</div>
                <div
                  v-for="cell in calendarCells"
                  :key="cell.key"
                  class="calendar-cell"
                  :class="{
                    'is-other-month': !cell.currentMonth,
                    'is-today': isToday(cell.date) && cell.currentMonth
                  }"
                >
                  <div class="d-flex justify-content-between align-items-center mb-1">
                    <div class="date-label fw-semibold">{{ cell.date.getDate() }}</div>
                    <div class="d-flex align-items-center gap-1">
                      <span
                        v-if="dayEvents(cell.date).length"
                        class="badge bg-light text-muted border-0 small"
                      >
                        {{ dayEvents(cell.date).length }}
                      </span>
                      <span
                        v-if="isToday(cell.date) && cell.currentMonth"
                        class="badge bg-primary text-white border-0 small"
                      >
                        Today
                      </span>
                    </div>
                  </div>
                  <div class="events">
                    <button
                      v-for="ev in dayEvents(cell.date)"
                      :key="ev.id"
                      type="button"
                      class="badge bg-primary-subtle text-primary border border-primary-subtle d-block text-truncate mb-1 text-start w-100"
                      :class="leaveTypeClass(ev.leave_type)"
                      @click="openEvent(ev)"
                    >
                      <span class="fw-semibold">{{ ev.user?.name || 'Employee' }}</span>
                      <span class="text-muted"> · {{ ev.leave_type || 'annual' }}</span>
                    </button>
                  </div>
                </div>
              </div>
            </div>
            <div v-else>
              <div v-if="approvedEvents.length === 0" class="text-center py-4 text-muted">
                No approved leaves found for this period
              </div>
              <div v-else class="table-responsive">
                <table class="table table-sm align-middle mb-0">
                  <thead class="table-light">
                    <tr>
                      <th>Employee</th>
                      <th>Type</th>
                      <th>Period</th>
                      <th>Days</th>
                      <th>Status</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr v-for="ev in approvedEvents" :key="ev.id">
                      <td>
                        <div class="fw-semibold">{{ ev.user?.name || 'Employee' }}</div>
                        <div class="text-muted fs-12">ID: #{{ ev.user_id }}</div>
                      </td>
                      <td class="text-capitalize">{{ ev.leave_type || 'annual' }}</td>
                      <td>{{ formatDate(ev.start_date) }} - {{ formatDate(ev.end_date) }}</td>
                      <td>{{ calcDays(ev.start_date, ev.end_date) }}</td>
                      <td>
                        <span class="badge bg-success-subtle text-success">
                          {{ (ev.status || 'approved').toUpperCase() }}
                        </span>
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-lg-3">
        <div class="card border-0 shadow-sm h-100">
          <div class="card-body">
            <h6 class="fw-semibold mb-3">Summary</h6>
            <ul class="list-unstyled mb-3 small">
              <li class="d-flex justify-content-between mb-1">
                <span class="text-muted">Total approved</span>
                <span class="fw-semibold">{{ approvedEvents.length }}</span>
              </li>
              <li class="d-flex justify-content-between mb-1">
                <span class="text-muted">Unique employees</span>
                <span class="fw-semibold">{{ uniqueEmployeeCount }}</span>
              </li>
            </ul>
            <h6 class="fw-semibold mb-2">Legend</h6>
            <div class="d-flex align-items-center mb-2">
              <span class="legend-dot bg-primary me-2"></span>
              <span class="small text-muted">Approved leave</span>
            </div>
            <p class="text-muted small mb-0">
              Use the search box to quickly find leaves for a specific employee.
            </p>
          </div>
        </div>
      </div>
    </div>

    <div v-if="selectedEvent" class="modal d-block" style="background: rgba(0,0,0,0.5)">
      <div class="modal-dialog">
        <div class="modal-content border-0 shadow">
          <div class="modal-header">
            <h5 class="modal-title">Leave details</h5>
            <button type="button" class="btn-close" @click="closeEvent"></button>
          </div>
          <div class="modal-body">
            <div class="mb-2">
              <div class="text-muted fs-12">Employee</div>
              <div class="fw-semibold">
                {{ selectedEvent.user?.name || 'Employee' }}
                <span class="text-muted fs-12">(#{{ selectedEvent.user_id }})</span>
              </div>
            </div>
            <div class="row mb-3">
              <div class="col-6">
                <div class="text-muted fs-12">Type</div>
                <div class="fw-semibold text-capitalize">{{ selectedEvent.leave_type || 'annual' }}</div>
              </div>
              <div class="col-6">
                <div class="text-muted fs-12">Status</div>
                <span class="badge bg-success-subtle text-success">
                  {{ (selectedEvent.status || 'approved').toUpperCase() }}
                </span>
              </div>
            </div>
            <div class="row mb-3">
              <div class="col-4">
                <div class="text-muted fs-12">Start</div>
                <div class="fw-semibold">{{ formatDate(selectedEvent.start_date) }}</div>
              </div>
              <div class="col-4">
                <div class="text-muted fs-12">End</div>
                <div class="fw-semibold">{{ formatDate(selectedEvent.end_date) }}</div>
              </div>
              <div class="col-4">
                <div class="text-muted fs-12">Days</div>
                <div class="fw-semibold">{{ calcDays(selectedEvent.start_date, selectedEvent.end_date) }}</div>
              </div>
            </div>
            <div v-if="selectedEvent.reason" class="mb-0">
              <div class="text-muted fs-12">Reason</div>
              <p class="mb-0">{{ selectedEvent.reason }}</p>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-light" @click="closeEvent">Close</button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue';
import api from '../services/api';

const today = new Date();
const viewYear = ref(today.getFullYear());
const viewMonth = ref(today.getMonth()); // 0-based
const loading = ref(false);
const error = ref('');
const events = ref([]);
const viewMode = ref('month');
const search = ref('');
const selectedEvent = ref(null);

const weekDays = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];

const monthLabel = computed(() => {
  const d = new Date(viewYear.value, viewMonth.value, 1);
  return d.toLocaleString(undefined, { month: 'long', year: 'numeric' });
});

const startOfMonth = computed(() => new Date(viewYear.value, viewMonth.value, 1));
const endOfMonth = computed(() => new Date(viewYear.value, viewMonth.value + 1, 0));

const calendarCells = computed(() => {
  const start = new Date(startOfMonth.value);
  const end = new Date(endOfMonth.value);
  const startDay = start.getDay();
  const daysInMonth = end.getDate();

  const cells = [];
  // Leading blanks (previous month)
  for (let i = 0; i < startDay; i++) {
    const d = new Date(start);
    d.setDate(1 - (startDay - i));
    cells.push({ key: `p-${i}`, date: d, currentMonth: false });
  }
  // Current month
  for (let day = 1; day <= daysInMonth; day++) {
    const d = new Date(viewYear.value, viewMonth.value, day);
    cells.push({ key: `c-${day}`, date: d, currentMonth: true });
  }
  // Trailing blanks to complete weeks
  const remainder = cells.length % 7;
  if (remainder) {
    const add = 7 - remainder;
    const last = cells[cells.length - 1].date;
    for (let i = 1; i <= add; i++) {
      const d = new Date(last);
      d.setDate(last.getDate() + i);
      cells.push({ key: `n-${i}`, date: d, currentMonth: false });
    }
  }
  return cells;
});

const isToday = (date) => {
  const now = new Date();
  return (
    date.getFullYear() === now.getFullYear() &&
    date.getMonth() === now.getMonth() &&
    date.getDate() === now.getDate()
  );
};

const normalize = (dateStr) => {
  const d = new Date(dateStr);
  return new Date(d.getFullYear(), d.getMonth(), d.getDate());
};

const normalizedEvents = computed(() => {
  const term = search.value.trim().toLowerCase();
  if (!term) return events.value;
  return events.value.filter((ev) => {
    const name = ev.user?.name || '';
    return name.toLowerCase().includes(term);
  });
});

const approvedEvents = computed(() => {
  return normalizedEvents.value.filter((ev) => {
    if ((ev.status || 'pending') !== 'approved') return false;
    if (!ev.start_date || !ev.end_date) return false;
    return true;
  });
});

const dayEvents = (date) => {
  return approvedEvents.value.filter((ev) => {
    if ((ev.status || 'pending') !== 'approved') return false;
    if (!ev.start_date || !ev.end_date) return false;
    const s = normalize(ev.start_date);
    const e = normalize(ev.end_date);
    const x = new Date(date.getFullYear(), date.getMonth(), date.getDate());
    return x >= s && x <= e;
  });
};

const loadData = async () => {
  loading.value = true;
  error.value = '';
  try {
    const res = await api.get('/leaves', { params: { per_page: 100 } });
    const payload = res.data?.data;
    events.value = payload?.data || [];
  } catch (e) {
    console.error('Load leaves error', e);
    error.value = e.response?.data?.message || 'Failed to load leaves';
  } finally {
    loading.value = false;
  }
};

const formatDate = (d) => {
  if (!d) return '—';
  return new Date(d).toLocaleDateString();
};

const calcDays = (start, end) => {
  if (!start || !end) return 0;
  const s = new Date(start);
  const e = new Date(end);
  return Math.max(1, Math.round((e - s) / (1000 * 60 * 60 * 24)) + 1);
};

const uniqueEmployeeCount = computed(() => {
  const ids = new Set();
  approvedEvents.value.forEach((ev) => {
    if (ev.user_id) ids.add(ev.user_id);
  });
  return ids.size;
});

const openEvent = (ev) => {
  selectedEvent.value = ev;
};

const closeEvent = () => {
  selectedEvent.value = null;
};

const leaveTypeClass = (type) => {
  const t = (type || 'annual').toLowerCase();
  if (t === 'sick') return 'badge-type-sick';
  if (t === 'casual') return 'badge-type-casual';
  return 'badge-type-annual';
};

const prevMonth = () => {
  if (viewMonth.value === 0) {
    viewMonth.value = 11;
    viewYear.value -= 1;
  } else {
    viewMonth.value -= 1;
  }
};

const nextMonth = () => {
  if (viewMonth.value === 11) {
    viewMonth.value = 0;
    viewYear.value += 1;
  } else {
    viewMonth.value += 1;
  }
};

onMounted(loadData);
</script>

<style scoped>
.calendar-scroll {
  overflow-x: auto;
  padding-bottom: 4px;
}
.calendar-grid {
  display: grid;
  grid-template-columns: repeat(7, minmax(0, 1fr));
  gap: 8px;
  min-width: 640px;
}
.calendar-header {
  text-align: center;
  font-weight: 600;
  color: var(--bs-secondary-color);
  font-size: 12px;
  text-transform: uppercase;
}
.calendar-cell {
  min-height: 120px;
  border: 1px solid var(--bs-border-color);
  border-radius: 10px;
  padding: 6px;
  background: var(--bs-body-bg);
  transition: background-color 0.15s ease, box-shadow 0.15s ease, transform 0.05s ease;
}
.calendar-cell:hover {
  background: var(--bs-light);
  box-shadow: 0 0.25rem 0.75rem rgba(15, 23, 42, 0.08);
  transform: translateY(-1px);
}
.calendar-cell.is-other-month {
  opacity: 0.6;
}
.calendar-cell.is-today {
  border-color: var(--bs-primary);
  box-shadow: 0 0 0 1px rgba(13, 110, 253, 0.2);
}
.date-label {
  font-size: 12px;
  color: var(--bs-secondary-color);
}
.events .badge {
  font-weight: 500;
  font-size: 11px;
}
.legend-dot {
  width: 10px;
  height: 10px;
  border-radius: 999px;
  background: var(--bs-primary);
}
.badge-type-annual {
  border-left: 3px solid var(--bs-primary);
}
.badge-type-sick {
  border-left: 3px solid var(--bs-danger);
}
.badge-type-casual {
  border-left: 3px solid var(--bs-warning);
}
</style>
