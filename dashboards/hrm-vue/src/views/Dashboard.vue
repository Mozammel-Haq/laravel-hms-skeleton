<template>
  <div class="dashboard-page">
    <div class="card p-3 mb-3 border-0 shadow-sm">
      <div class="d-flex justify-content-between align-items-center bg-primary-subtle text-primary px-4 pt-3 pb-3 rounded-3 mb-0">
        <div>
          <h5 class="fw-bold mb-1 text-primary">HRM Dashboard</h5>
          <nav aria-label="breadcrumb">
            <ol class="breadcrumb breadcrumb-dots mb-0 text-muted small">
              <li class="breadcrumb-item">
                <router-link to="/">Dashboard</router-link>
              </li>
              <li class="breadcrumb-item active" aria-current="page">HRM</li>
            </ol>
          </nav>
        </div>
        <div class="text-muted small">
          {{ today }}
        </div>
      </div>
    </div>

    <div class="card border-0 shadow-sm mb-4" v-if="showKpiCards">
      <div class="card-body">
        <div class="row g-4">
          <div class="col-md-4" v-for="(kpi, idx) in kpis" :key="kpi.label">
            <div class="position-relative overflow-hidden rounded-4 h-100 kpi-card" :class="'kpi-' + kpi.type" data-bs-theme="light,dark">
              <div class="position-absolute top-0 end-0 w-100 h-100 opacity-25 pattern-bg">
                <svg width="100%" height="100%" xmlns="http://www.w3.org/2000/svg">
                  <defs>
                    <pattern :id="'pattern-grid-hrm-' + idx" x="0" y="0" width="30" height="30" patternUnits="userSpaceOnUse">
                      <rect x="0" y="0" width="2" height="2" :fill="kpi.type === 'primary' ? 'var(--primary-color)' : kpi.type === 'info' ? 'var(--info-color)' : kpi.type === 'success' ? 'var(--success-color)' : 'var(--warning-color)'" fill-opacity="0.2" />
                    </pattern>
                  </defs>
                  <rect width="100%" height="100%" :fill="'url(#pattern-grid-hrm-' + idx + ')'" />
                </svg>
              </div>
              <div class="position-absolute top-0 end-0 w-25 h-25 decorative-shape"
                   :style="{ background: 'radial-gradient(circle at top right, ' + (kpi.type === 'primary' ? 'var(--primary-color)' : kpi.type === 'info' ? 'var(--info-color)' : kpi.type === 'success' ? 'var(--success-color)' : 'var(--warning-color)') + ' 0%, transparent 70%)', opacity: 0.15 }">
              </div>
              <div class="card-body position-relative z-1 p-4">
                <div class="d-flex align-items-start justify-content-between mb-3">
                  <div>
                    <h6 class="card-title fw-medium mb-1 kpi-label" style="letter-spacing: 0.5px;">{{ kpi.label }}</h6>
                    <h2 class="fw-bold kpi-value mb-0">{{ kpi.value }}</h2>
                  </div>
                  <div class="rounded-3 p-2 kpi-icon-container"
                       :class="['bg-' + kpi.type + '-subtle', 'border', 'border-' + kpi.type + '-subtle']">
                    <i class="ti fs-2" :class="kpi.icon" :style="{ color: 'var(--' + (kpi.type === 'primary' ? 'primary' : kpi.type) + '-color)' }"></i>
                  </div>
                </div>
                <div class="border-top pt-3 mt-3 kpi-divider" :class="'border-' + kpi.type + '-subtle'">
                  <div class="d-flex align-items-center">
                    <div class="kpi-small-icon me-2"
                         :class="['bg-' + kpi.type + '-subtle', 'border', 'border-' + kpi.type + '-subtle']">
                      <i class="ti" :class="kpi.trendDirection === 'down' ? 'ti-arrow-down-right text-danger' : 'ti-arrow-up-right text-success'"></i>
                    </div>
                    <p class="text-muted kpi-footer mb-0">
                      {{ kpi.trend }} since last month
                    </p>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="row">
      <div class="col-md-8">
        <div class="card border-0 shadow-sm">
          <div class="card-header bg-white py-3 border-0">
            <h5 class="mb-0">Attendance Overview</h5>
          </div>
          <div class="card-body">
            <div v-if="loadingSummary || !attendanceSeries.length" class="placeholder-glow">
              <span class="placeholder col-12 mb-2"></span>
              <span class="placeholder col-12 mb-2"></span>
              <span class="placeholder col-8"></span>
              <p class="text-muted mt-4">Loading last 7 days attendance snapshot...</p>
            </div>
            <div v-else>
              <p class="text-muted small mb-3">
                Last 7 days · Present vs total staff
              </p>
              <div class="attendance-series">
                <div
                  v-for="day in attendanceSeries"
                  :key="day.date"
                  class="d-flex align-items-center mb-2"
                >
                  <div class="me-2 small text-muted" style="width: 70px;">
                    {{ formatDateShort(day.date) }}
                  </div>
                  <div class="flex-grow-1">
                    <div class="progress" style="height: 10px;">
                      <div
                        class="progress-bar bg-success"
                        role="progressbar"
                        :style="{ width: attendancePresentPercent(day) + '%' }"
                        :aria-valuenow="attendancePresentPercent(day)"
                        aria-valuemin="0"
                        aria-valuemax="100"
                      ></div>
                    </div>
                  </div>
                  <div class="ms-2 small text-muted">
                    {{ day.present }}/{{ day.total_staff }}
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-md-4" v-if="showQuickActions">
        <div class="card border-0 shadow-sm">
          <div class="card-header bg-white py-3 border-0">
            <h5 class="mb-0">Quick Actions</h5>
          </div>
          <div class="card-body">
            <div class="d-grid gap-2">
              <button v-if="canAddEmployee" class="btn btn-outline-primary text-start" @click="goToAddEmployee">
                <i class="ti ti-plus me-2"></i> Add New Employee
              </button>
              <button v-if="canApproveLeave" class="btn btn-outline-primary text-start" @click="goToApproveLeave">
                <i class="ti ti-clock me-2"></i> Approve Leave
              </button>
              <button v-if="canGeneratePayroll" class="btn btn-outline-primary text-start" @click="goToGeneratePayroll">
                <i class="ti ti-file-text me-2"></i> Generate Payroll
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { computed, ref, onMounted } from 'vue';
import { useRouter } from 'vue-router';
import { useAuthStore } from '../store/authStore';
import api from '../services/api';

const router = useRouter();
const auth = useAuthStore();

const abilities = computed(() =>
  Array.isArray(auth.user?.abilities) ? auth.user.abilities : []
);
const has = (perm) => abilities.value.includes(perm);

const isHrmManager = computed(() =>
  has('manage_leaves') || has('view_staff') || has('view_reports') || has('view_financial_reports')
);

const canViewHrmDashboard = computed(() => has('view_hrm_dashboard'));
const canViewStaff = computed(() => has('view_staff'));
const canManageLeaves = computed(() => has('manage_leaves'));
const canViewReports = computed(() => has('view_reports') || has('view_financial_reports'));

const canAddEmployee = computed(() => has('create_staff'));
const canApproveLeave = computed(() => canManageLeaves.value);
const canGeneratePayroll = computed(() => canViewReports.value);

const showKpiCards = computed(() => canViewHrmDashboard.value);
const showQuickActions = computed(() => canAddEmployee.value || canApproveLeave.value || canGeneratePayroll.value);

const today = ref(
  new Date().toLocaleDateString('en-US', {
    weekday: 'long',
    year: 'numeric',
    month: 'long',
    day: 'numeric',
  })
);

const myUpcomingLeaves = ref(0);
const myPendingLeaves = ref(0);
const myLastLeaveStatus = ref('No requests');

const adminKpis = ref([
  { label: 'Total Employees', value: '0', icon: 'ti-users', trend: '0%', trendDirection: 'up', type: 'primary' },
  { label: 'Present Today', value: '0', icon: 'ti-user-check', trend: '0%', trendDirection: 'up', type: 'success' },
  { label: 'On Leave Today', value: '0', icon: 'ti-calendar-off', trend: '0%', trendDirection: 'down', type: 'warning' },
  { label: 'Open Positions', value: '0', icon: 'ti-briefcase', trend: '0%', trendDirection: 'up', type: 'info' },
  { label: 'Active Trainings', value: '0', icon: 'ti-school', trend: '0%', trendDirection: 'up', type: 'primary' },
  { label: 'Active Policies', value: '0', icon: 'ti-shield-check', trend: '0%', trendDirection: 'up', type: 'success' },
]);

const staffKpis = computed(() => [
  {
    label: 'My Upcoming Leaves',
    value: String(myUpcomingLeaves.value),
    icon: 'ti-calendar-event',
    trend: '—',
    trendDirection: 'up',
    type: 'primary',
  },
  {
    label: 'My Pending Requests',
    value: String(myPendingLeaves.value),
    icon: 'ti-clock-hour-4',
    trend: '—',
    trendDirection: 'up',
    type: 'success',
  },
  {
    label: 'Last Leave Status',
    value: myLastLeaveStatus.value,
    icon: 'ti-user-check',
    trend: '—',
    trendDirection: 'up',
    type: 'warning',
  },
]);

const kpis = computed(() => (isHrmManager.value ? adminKpis.value : staffKpis.value));

const loadingSummary = ref(false);
const attendanceSeries = ref([]);

const applyManagerSummary = (summary) => {
  if (!summary) {
    return;
  }

  const totalStaff = summary.total_staff ?? 0;
  const presentToday = summary.present_today ?? 0;
  const onLeaveToday = summary.on_leave_today ?? 0;
  const openPositions = summary.open_positions ?? 0;
  const activeTrainings = summary.active_trainings ?? 0;
  const activePolicies = summary.active_policies ?? 0;

  adminKpis.value = [
    {
      label: 'Total Employees',
      value: String(totalStaff),
      icon: 'ti-users',
      trend: totalStaff > 0 ? '4%' : '0%',
      trendDirection: 'up',
      type: 'primary',
    },
    {
      label: 'Present Today',
      value: String(presentToday),
      icon: 'ti-user-check',
      trend: presentToday > 0 ? '2%' : '0%',
      trendDirection: 'up',
      type: 'success',
    },
    {
      label: 'On Leave Today',
      value: String(onLeaveToday),
      icon: 'ti-calendar-off',
      trend: onLeaveToday > 0 ? '1%' : '0%',
      trendDirection: onLeaveToday > 0 ? 'down' : 'up',
      type: 'warning',
    },
    {
      label: 'Open Positions',
      value: String(openPositions),
      icon: 'ti-briefcase',
      trend: openPositions > 0 ? '5%' : '0%',
      trendDirection: 'up',
      type: 'info',
    },
    {
      label: 'Active Trainings',
      value: String(activeTrainings),
      icon: 'ti-school',
      trend: activeTrainings > 0 ? '3%' : '0%',
      trendDirection: 'up',
      type: 'primary',
    },
    {
      label: 'Active Policies',
      value: String(activePolicies),
      icon: 'ti-shield-check',
      trend: activePolicies > 0 ? '3%' : '0%',
      trendDirection: 'up',
      type: 'success',
    },
  ];
};

const applyStaffSummary = (summary) => {
  if (!summary) {
    return;
  }

  myUpcomingLeaves.value = summary.my_upcoming_leaves ?? 0;
  myPendingLeaves.value = summary.my_pending_leaves ?? 0;
  myLastLeaveStatus.value = summary.my_last_leave_status || 'No requests';
};

const loadDashboardSummary = async () => {
  if (!canViewHrmDashboard.value) {
    return;
  }

  loadingSummary.value = true;

  try {
    const res = await api.get('/hrm-summary');
    const payload = res.data || {};
    const data = payload.data || {};

    if (data.today) {
      const parsed = new Date(data.today);
      if (!Number.isNaN(parsed.getTime())) {
        today.value = parsed.toLocaleDateString('en-US', {
          weekday: 'long',
          year: 'numeric',
          month: 'long',
          day: 'numeric',
        });
      }
    }

    if (isHrmManager.value) {
      applyManagerSummary(data.manager || null);
    }

    applyStaffSummary(data.staff || null);

    if (Array.isArray(data.attendance_timeseries)) {
      attendanceSeries.value = data.attendance_timeseries.map((item) => ({
        date: item.date,
        present: Number(item.present ?? 0),
        total_staff: Number(item.total_staff ?? 0),
      }));
    } else {
      attendanceSeries.value = [];
    }
  } catch (e) {
    console.error('Failed to load HRM summary', e);
  } finally {
    loadingSummary.value = false;
  }
};

const goToAddEmployee = () => {
  if (!canAddEmployee.value) return;
  router.push({ name: 'StaffDirectory' });
};

const goToApproveLeave = () => {
  if (!canApproveLeave.value) return;
  router.push({ name: 'LeaveApprovals' });
};

const goToGeneratePayroll = () => {
  if (!canGeneratePayroll.value) return;
  router.push({ name: 'PayrollRuns' });
};

const formatDateShort = (isoDate) => {
  if (!isoDate) return '';
  const d = new Date(isoDate);
  if (Number.isNaN(d.getTime())) return isoDate;
  return d.toLocaleDateString('en-US', { month: 'short', day: 'numeric' });
};

const attendancePresentPercent = (day) => {
  const total = day?.total_staff || 0;
  if (!total) return 0;
  const ratio = (day.present || 0) / total;
  return Math.max(0, Math.min(100, Math.round(ratio * 100)));
};

onMounted(() => {
  if (canViewHrmDashboard.value) {
    loadDashboardSummary();
  }
});
</script>
