<template>
  <div class="dashboard-page">
    <div class="d-flex justify-content-between align-items-center mb-4">
      <h4 class="mb-0">HRM Dashboard</h4>
      <div class="text-muted">{{ today }}</div>
    </div>

    <div class="row g-4 mb-4">
      <div class="col-md-3" v-for="(kpi, idx) in kpis" :key="kpi.label">
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

    <div class="row">
      <div class="col-md-8">
        <div class="card border-0 shadow-sm">
          <div class="card-header bg-white py-3 border-0">
            <h5 class="mb-0">Attendance Overview</h5>
          </div>
          <div class="card-body">
            <div class="placeholder-glow">
              <span class="placeholder col-12 mb-2"></span>
              <span class="placeholder col-12 mb-2"></span>
              <span class="placeholder col-8"></span>
            </div>
            <p class="text-muted mt-4">Chart integration pending...</p>
          </div>
        </div>
      </div>
      <div class="col-md-4">
        <div class="card border-0 shadow-sm">
          <div class="card-header bg-white py-3 border-0">
            <h5 class="mb-0">Quick Actions</h5>
          </div>
          <div class="card-body">
            <div class="d-grid gap-2">
              <button class="btn btn-outline-primary text-start">
                <i class="ti ti-plus me-2"></i> Add New Employee
              </button>
              <button class="btn btn-outline-primary text-start">
                <i class="ti ti-clock me-2"></i> Approve Leave
              </button>
              <button class="btn btn-outline-primary text-start">
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
import { ref } from 'vue';

const today = new Date().toLocaleDateString('en-US', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' });

const kpis = ref([
  { label: 'Total Employees', value: '124', icon: 'ti-users', trend: '4%', trendDirection: 'up', type: 'primary' },
  { label: 'Present Today', value: '112', icon: 'ti-user-check', trend: '2%', trendDirection: 'up', type: 'success' },
  { label: 'On Leave', value: '8', icon: 'ti-calendar-off', trend: '1%', trendDirection: 'down', type: 'warning' },
  { label: 'Open Positions', value: '12', icon: 'ti-briefcase', trend: '5%', trendDirection: 'up', type: 'info' }
]);
</script>
