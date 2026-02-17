<template>
  <div class="container-fluid">
    <div class="card p-3 mb-3 border-0 shadow-sm">
      <div class="d-flex justify-content-between align-items-center bg-primary-subtle text-primary px-4 pt-3 pb-3 rounded-3 mb-0">
        <div>
          <h5 class="fw-bold mb-1 text-primary">Shift Calendar</h5>
          <nav aria-label="breadcrumb">
            <ol class="breadcrumb breadcrumb-dots mb-0 text-muted small">
              <li class="breadcrumb-item">
                <router-link to="/">Dashboard</router-link>
              </li>
              <li class="breadcrumb-item">Attendance</li>
              <li class="breadcrumb-item active" aria-current="page">Shift Calendar</li>
            </ol>
          </nav>
        </div>
        <div class="d-flex align-items-center gap-2">
          <button class="btn btn-outline-primary btn-sm" type="button" @click="prevMonth">
            <i class="ti ti-chevron-left"></i>
          </button>
          <h6 class="mb-0 fw-semibold">
            {{ currentMonthLabel }}
          </h6>
          <button class="btn btn-outline-primary btn-sm" type="button" @click="nextMonth">
            <i class="ti ti-chevron-right"></i>
          </button>
        </div>
      </div>
    </div>

    <div class="card border-0 shadow-sm">
      <div class="card-body">
        <div v-if="loading" class="text-center py-4">
          <div class="spinner-border text-primary" role="status">
            <span class="visually-hidden">Loading...</span>
          </div>
        </div>
        <div v-else>
          <div class="row mb-3">
            <div class="col-md-4">
              <div class="border rounded-3 p-3 bg-light">
                <h6 class="fw-bold mb-2">Summary</h6>
                <p class="mb-1 fs-12 text-muted">
                  Total staff assignments for this month
                </p>
                <div class="d-flex flex-wrap gap-2 mt-2">
                  <span
                    v-for="shift in shifts"
                    :key="shift.id"
                    class="badge bg-primary-subtle text-primary"
                  >
                    {{ shift.name }}:
                    {{ summaryByShift[shift.id] || 0 }}
                  </span>
                  <span v-if="shifts.length === 0" class="text-muted fs-12">
                    No shifts defined
                  </span>
                </div>
              </div>
            </div>
            <div
              class="col-md-8 d-flex justify-content-md-end align-items-start mt-3 mt-md-0"
            >
              <div class="btn-group btn-group-sm me-2" role="group" aria-label="View mode">
                <button
                  type="button"
                  class="btn"
                  :class="mode === 'shift' ? 'btn-primary' : 'btn-outline-primary'"
                  @click="setMode('shift')"
                >
                  By Shift
                </button>
                <button
                  v-if="canUseStaffMode"
                  type="button"
                  class="btn"
                  :class="mode === 'staff' ? 'btn-primary' : 'btn-outline-primary'"
                  @click="setMode('staff')"
                >
                  By Staff
                </button>
              </div>
              <div
                v-if="mode === 'staff' && canUseStaffMode"
                class="ms-2"
                style="min-width: 220px;"
              >
                <select v-model.number="selectedUserId" class="form-select form-select-sm">
                  <option :value="null">All Staff</option>
                  <option
                    v-for="staff in staffOptions"
                    :key="staff.id"
                    :value="staff.id"
                  >
                    {{ staff.name }} ({{ staff.email }})
                  </option>
                </select>
              </div>
            </div>
          </div>

          <div class="calendar-grid">
            <div class="calendar-header">
              <div v-for="d in weekDays" :key="d" class="calendar-header-cell">
                {{ d }}
              </div>
            </div>
            <div class="calendar-body">
              <div
                v-for="cell in calendarCells"
                :key="cell.dateKey"
                class="calendar-cell"
                :class="{
                  'is-today': cell.isToday,
                  'is-other-month': cell.isOtherMonth,
                }"
              >
                <div class="calendar-date">
                  <span>{{ cell.dateObj.getDate() }}</span>
                </div>
                <div class="calendar-meta" v-if="cell.totalAssignments > 0">
                  <span
                    v-if="mode === 'shift'"
                    class="badge bg-secondary-subtle text-secondary mb-1"
                  >
                    {{ cell.totalAssignments }} assignments
                  </span>
                  <div class="small" :class="mode === 'shift' ? 'text-muted' : ''">
                    <div v-for="entry in cell.shiftEntries" :key="entry.shiftId">
                      <template v-if="mode === 'shift'">
                        {{ entry.name }}: {{ entry.count }}
                      </template>
                      <template v-else>
                        {{ entry.name }}
                      </template>
                    </div>
                  </div>
                </div>
                <div v-else class="calendar-meta text-muted fs-12">
                  <span v-if="mode === 'staff'">Off</span>
                  <span v-else>No assignments</span>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { computed, onMounted, ref } from 'vue';
import api from '../services/api';
import { useAuthStore } from '../store/authStore';

const loading = ref(false);
const shifts = ref([]);
const assignmentsRaw = ref([]);

const auth = useAuthStore();

const abilities = computed(() =>
  Array.isArray(auth.user?.abilities) ? auth.user.abilities : []
);
const has = (perm) => abilities.value.includes(perm);

const canViewStaff = computed(() => has('view_staff'));
const canUseStaffMode = computed(() => canViewStaff.value);

const mode = ref('shift');
const staffOptions = ref([]);
const selectedUserId = ref(null);

const today = new Date();
const currentYear = ref(today.getFullYear());
const currentMonth = ref(today.getMonth());

const weekDays = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];

const currentMonthLabel = computed(() => {
  const date = new Date(currentYear.value, currentMonth.value, 1);
  return date.toLocaleString(undefined, { month: 'long', year: 'numeric' });
});

const loadStaff = async () => {
  if (!canUseStaffMode.value) return;
  try {
    const res = await api.get('/staff', { params: { per_page: 200 } });
    const payload = res.data || {};
    const page = payload.data || {};
    staffOptions.value = page.data || payload.data || [];
  } catch (e) {
    console.error('Failed to load staff for shift calendar', e);
  }
};

const loadData = async () => {
  loading.value = true;
  try {
    const [shiftsRes, assignmentsRes] = await Promise.all([
      api.get('/shifts', { params: { per_page: 200 } }),
      api.get('/shift-assignments', {
        params: {
          per_page: 500,
        },
      }),
    ]);

    const shiftsPayload = shiftsRes.data || {};
    const shiftsPage = shiftsPayload.data || {};
    shifts.value = shiftsPage.data || shiftsPayload.data || [];

    const assignPayload = assignmentsRes.data || {};
    const assignPage = assignPayload.data || {};
    assignmentsRaw.value = assignPage.data || assignPayload.data || [];
  } catch (e) {
    console.error('Failed to load shift calendar data', e);
  } finally {
    loading.value = false;
  }
};

const buildDateKey = (date) => {
  const y = date.getFullYear();
  const m = String(date.getMonth() + 1).padStart(2, '0');
  const d = String(date.getDate()).padStart(2, '0');
  return `${y}-${m}-${d}`;
};

const firstDayOfMonth = computed(() => new Date(currentYear.value, currentMonth.value, 1));
const lastDayOfMonth = computed(() => new Date(currentYear.value, currentMonth.value + 1, 0));

const filteredAssignments = computed(() => {
  if (mode.value === 'staff' && selectedUserId.value) {
    return assignmentsRaw.value.filter(
      (assignment) => assignment.user_id === selectedUserId.value
    );
  }
  return assignmentsRaw.value;
});

const assignmentsByDate = computed(() => {
  const map = {};
  const start = firstDayOfMonth.value;
  const end = lastDayOfMonth.value;

  filteredAssignments.value.forEach((assignment) => {
    if (!assignment.shift || !assignment.status || assignment.status !== 'active') {
      return;
    }

    const effFrom = assignment.effective_from ? new Date(assignment.effective_from) : null;
    const effTo = assignment.effective_to ? new Date(assignment.effective_to) : null;
    if (!effFrom) return;

    let rangeStart = new Date(effFrom);
    let rangeEnd = effTo ? new Date(effTo) : new Date(effFrom);

    if (rangeEnd < start || rangeStart > end) {
      return;
    }

    if (rangeStart < start) rangeStart = new Date(start);
    if (rangeEnd > end) rangeEnd = new Date(end);

    for (
      let d = new Date(rangeStart);
      d <= rangeEnd;
      d.setDate(d.getDate() + 1)
    ) {
      const key = buildDateKey(d);
      if (!map[key]) {
        map[key] = {};
      }
      const shiftId = assignment.shift.id;
      if (!map[key][shiftId]) {
        map[key][shiftId] = 0;
      }
      map[key][shiftId] += 1;
    }
  });

  return map;
});

const summaryByShift = computed(() => {
  const summary = {};
  Object.values(assignmentsByDate.value).forEach((dayMap) => {
    Object.entries(dayMap).forEach(([shiftId, count]) => {
      if (!summary[shiftId]) {
        summary[shiftId] = 0;
      }
      summary[shiftId] += count;
    });
  });
  return summary;
});

const calendarCells = computed(() => {
  const cells = [];
  const first = firstDayOfMonth.value;
  const last = lastDayOfMonth.value;

  const startOffset = first.getDay();
  const startDate = new Date(first);
  startDate.setDate(first.getDate() - startOffset);

  for (let i = 0; i < 42; i += 1) {
    const date = new Date(startDate);
    date.setDate(startDate.getDate() + i);
    const key = buildDateKey(date);
    const dayMap = assignmentsByDate.value[key] || {};
    const shiftEntries = Object.entries(dayMap).map(([shiftId, count]) => {
      const shift = shifts.value.find((s) => s.id === Number(shiftId));
      return {
        shiftId,
        name: shift ? shift.name : `Shift #${shiftId}`,
        count,
      };
    });
    const total = shiftEntries.reduce((sum, entry) => sum + entry.count, 0);

    cells.push({
      dateObj: new Date(date),
      dateKey: key,
      isToday:
        date.getFullYear() === today.getFullYear() &&
        date.getMonth() === today.getMonth() &&
        date.getDate() === today.getDate(),
      isOtherMonth: date.getMonth() !== currentMonth.value,
      totalAssignments: total,
      shiftEntries,
    });
  }

  return cells;
});

const setMode = (value) => {
  if (value === 'staff' && !canUseStaffMode.value) {
    return;
  }
  mode.value = value;
};

const prevMonth = () => {
  const m = currentMonth.value - 1;
  if (m < 0) {
    currentMonth.value = 11;
    currentYear.value -= 1;
  } else {
    currentMonth.value = m;
  }
};

const nextMonth = () => {
  const m = currentMonth.value + 1;
  if (m > 11) {
    currentMonth.value = 0;
    currentYear.value += 1;
  } else {
    currentMonth.value = m;
  }
};

onMounted(() => {
  loadData();
  loadStaff();
});
</script>

<style scoped>
.calendar-grid {
  display: flex;
  flex-direction: column;
  gap: 4px;
}

.calendar-header {
  display: grid;
  grid-template-columns: repeat(7, minmax(0, 1fr));
  gap: 4px;
}

.calendar-header-cell {
  text-align: center;
  font-size: 12px;
  font-weight: 600;
  color: var(--bs-secondary-color);
}

.calendar-body {
  display: grid;
  grid-template-columns: repeat(7, minmax(0, 1fr));
  gap: 4px;
}

.calendar-cell {
  min-height: 90px;
  border-radius: 0.5rem;
  border: 1px solid var(--bs-border-color);
  padding: 6px 8px;
  background-color: var(--bs-body-bg);
  display: flex;
  flex-direction: column;
  justify-content: flex-start;
  transition: background-color 0.15s ease, box-shadow 0.15s ease, transform 0.05s ease;
}

.calendar-cell:hover {
  background-color: var(--bs-light);
  box-shadow: 0 0.25rem 0.75rem rgba(15, 23, 42, 0.08);
  transform: translateY(-1px);
}

.calendar-cell.is-today {
  border-color: var(--bs-primary);
  box-shadow: 0 0 0 1px rgba(13, 110, 253, 0.2);
}

.calendar-cell.is-other-month {
  opacity: 0.6;
}

.calendar-date {
  font-weight: 600;
  font-size: 12px;
  margin-bottom: 4px;
}

.calendar-meta {
  font-size: 11px;
}
</style>
