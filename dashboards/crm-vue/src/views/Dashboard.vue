<template>
  <div class="dashboard-page">
    <div class="d-flex justify-content-between align-items-center mb-4">
      <h4 class="mb-0">CRM Dashboard</h4>
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
              <i class="ti ti-trending-up"></i> {{ kpi.trend }} increase
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
  { label: 'Total Patients', value: '4,280', icon: 'ti-users', trend: '12%', bg: 'linear-gradient(135deg, #f0f7ff 0%, #e6f0ff 100%)' },
  { label: 'New Leads', value: '85', icon: 'ti-user-plus', trend: '8%', bg: 'linear-gradient(135deg, #f0f9f5 0%, #e6f4ec 100%)' },
  { label: 'Conversion Rate', value: '24%', icon: 'ti-chart-arrows', trend: '5%', bg: 'linear-gradient(135deg, #fff9f0 0%, #fff4e6 100%)' },
  { label: 'Loyalty Points', value: '125k', icon: 'ti-award', trend: '15%', bg: 'linear-gradient(135deg, #f0f9fb 0%, #e6f4f8 100%)' }
]);
</script>

<style scoped>
.fs-10 { font-size: 10px; }
.fs-12 { font-size: 12px; }
.fs-14 { font-size: 14px; }
.bg-primary-subtle { background-color: #e7f1ff; }
</style>
