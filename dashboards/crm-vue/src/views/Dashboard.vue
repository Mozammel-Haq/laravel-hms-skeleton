<template>
  <div class="dashboard-page">
    <div class="d-flex justify-content-between align-items-center mb-4">
      <h4 class="mb-0">CRM Dashboard</h4>
      <div class="text-muted">{{ today }}</div>
    </div>

    <!-- KPI Cards -->
    <div class="row g-4 mb-4">
      <div class="col-md-3" v-for="(kpi, idx) in kpis" :key="kpi.label">
        <div class="position-relative overflow-hidden rounded-4 h-100 kpi-card" :class="'kpi-' + kpi.type" data-bs-theme="light,dark">
          <div class="position-absolute top-0 end-0 w-100 h-100 opacity-25 pattern-bg">
            <svg width="100%" height="100%" xmlns="http://www.w3.org/2000/svg">
              <defs>
                <pattern :id="'pattern-grid-crm-' + idx" x="0" y="0" width="30" height="30" patternUnits="userSpaceOnUse">
                  <rect x="0" y="0" width="2" height="2" :fill="kpi.type === 'primary' ? 'var(--primary-color)' : kpi.type === 'info' ? 'var(--info-color)' : kpi.type === 'success' ? 'var(--success-color)' : 'var(--warning-color)'" fill-opacity="0.2" />
                </pattern>
              </defs>
              <rect width="100%" height="100%" :fill="'url(#pattern-grid-crm-' + idx + ')'" />
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
                <i class="ti fs-2" :class="kpi.icon"></i>
              </div>
            </div>
            <div class="border-top pt-3 mt-3 kpi-divider" :class="'border-' + kpi.type + '-subtle'">
              <div class="d-flex align-items-center">
                <div class="kpi-small-icon me-2"
                     :class="['bg-' + kpi.type + '-subtle', 'border', 'border-' + kpi.type + '-subtle']">
                  <i class="ti ti-trending-up text-success"></i>
                </div>
                <p class="text-muted kpi-footer mb-0">
                  {{ kpi.trend }} increase
                </p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Sales & Leads -->
    <div class="row">
      <div class="col-md-8">
        <div class="card border-0 shadow-sm">
          <div class="card-header bg-white py-3 border-0">
            <h5 class="mb-0">Patient Acquisition Trend</h5>
          </div>
          <div class="card-body" style="height: 300px;">
            <div class="d-flex h-100 align-items-center justify-content-center text-muted">
              Chart integration pending...
            </div>
          </div>
        </div>
      </div>
      <div class="col-md-4">
        <div class="card border-0 shadow-sm">
          <div class="card-header bg-white py-3 border-0">
            <h5 class="mb-0">Recent Inquiries</h5>
          </div>
          <div class="card-body">
            <div class="list-group list-group-flush">
              <div class="list-group-item px-0 border-0 mb-3" v-for="i in 3" :key="i">
                <div class="d-flex justify-content-between">
                  <h6 class="mb-1 fs-14">Inquiry #{{ 1024 + i }}</h6>
                  <small class="text-muted">2h ago</small>
                </div>
                <p class="mb-1 text-muted fs-12">New inquiry regarding cardiology consultation...</p>
                <span class="badge bg-primary-subtle text-primary fs-10">New</span>
              </div>
            </div>
            <button class="btn btn-light w-100 mt-2">View All</button>
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
  { label: 'Total Patients', value: '4,280', icon: 'ti-users', trend: '12%', type: 'primary' },
  { label: 'New Leads', value: '85', icon: 'ti-user-plus', trend: '8%', type: 'success' },
  { label: 'Conversion Rate', value: '24%', icon: 'ti-chart-arrows', trend: '5%', type: 'warning' },
  { label: 'Loyalty Points', value: '125k', icon: 'ti-award', trend: '15%', type: 'info' }
]);
</script>
