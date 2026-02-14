<template>
  <div class="dashboard-page">
    <div class="d-flex justify-content-between align-items-center mb-4">
      <h4 class="mb-0">HRM Dashboard</h4>
      <div class="text-muted">{{ today }}</div>
    </div>

    <!-- KPI Cards -->
    <div class="row g-4 mb-4">
      <div class="col-md-3" v-for="kpi in kpis" :key="kpi.label">
        <div class="card border-0 shadow-sm" :style="{ background: kpi.bg }">
          <div class="card-body p-4">
            <div class="d-flex justify-content-between align-items-center mb-2">
              <span class="text-muted fw-medium">{{ kpi.label }}</span>
              <i class="ti fs-4" :class="kpi.icon"></i>
            </div>
            <h2 class="mb-0">{{ kpi.value }}</h2>
            <div class="mt-2 fs-12 text-success">
              <i class="ti ti-trending-up"></i> {{ kpi.trend }} since last month
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Recent Activity -->
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
import { ref, onMounted } from 'vue';

const today = new Date().toLocaleDateString('en-US', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' });

const kpis = ref([
  { label: 'Total Employees', value: '124', icon: 'ti-users', trend: '4%', bg: 'linear-gradient(135deg, #f0f7ff 0%, #e6f0ff 100%)' },
  { label: 'Present Today', value: '112', icon: 'ti-user-check', trend: '2%', bg: 'linear-gradient(135deg, #f0f9f5 0%, #e6f4ec 100%)' },
  { label: 'On Leave', value: '8', icon: 'ti-calendar-off', trend: '1%', bg: 'linear-gradient(135deg, #fff9f0 0%, #fff4e6 100%)' },
  { label: 'Open Positions', value: '12', icon: 'ti-briefcase', trend: '5%', bg: 'linear-gradient(135deg, #f0f9fb 0%, #e6f4f8 100%)' }
]);
</script>

<style scoped>
.fs-12 { font-size: 12px; }
</style>
