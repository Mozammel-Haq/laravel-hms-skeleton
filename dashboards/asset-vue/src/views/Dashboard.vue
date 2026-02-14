<template>
  <div class="dashboard-page">
    <div class="d-flex justify-content-between align-items-center mb-4">
      <h4 class="mb-0">Asset & Inventory Dashboard</h4>
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
            <div class="mt-2 fs-12 text-danger" v-if="kpi.alert">
              <i class="ti ti-alert-triangle"></i> {{ kpi.alert }} items low
            </div>
            <div class="mt-2 fs-12 text-success" v-else>
              <i class="ti ti-check"></i> All systems optimal
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="row">
      <div class="col-md-7">
        <div class="card border-0 shadow-sm">
          <div class="card-header bg-white py-3 border-0">
            <h5 class="mb-0">Inventory Levels</h5>
          </div>
          <div class="card-body">
            <div v-for="item in stock" :key="item.name" class="mb-3">
              <div class="d-flex justify-content-between mb-1 fs-14">
                <span>{{ item.name }}</span>
                <span :class="item.level < 30 ? 'text-danger' : 'text-muted'">{{ item.level }}%</span>
              </div>
              <div class="progress" style="height: 6px;">
                <div class="progress-bar" :class="item.level < 30 ? 'bg-danger' : 'bg-primary'" :style="{ width: item.level + '%' }"></div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-md-5">
        <div class="card border-0 shadow-sm">
          <div class="card-header bg-white py-3 border-0">
            <h5 class="mb-0">Pending Purchase Orders</h5>
          </div>
          <div class="card-body">
            <div class="table-responsive">
              <table class="table table-sm fs-12">
                <thead>
                  <tr>
                    <th>PO #</th>
                    <th>Supplier</th>
                    <th>Status</th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="po in pos" :key="po.id">
                    <td>#{{ po.id }}</td>
                    <td>{{ po.supplier }}</td>
                    <td><span class="badge bg-warning-subtle text-warning">{{ po.status }}</span></td>
                  </tr>
                </tbody>
              </table>
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
  { label: 'Total Assets', value: '1,240', icon: 'ti-building-hospital', bg: 'linear-gradient(135deg, #f0f7ff 0%, #e6f0ff 100%)' },
  { label: 'Low Stock Items', value: '18', icon: 'ti-package-off', alert: '12', bg: 'linear-gradient(135deg, #fff0f0 0%, #ffe6e6 100%)' },
  { label: 'Maintenance Due', value: '4', icon: 'ti-tools', alert: '2', bg: 'linear-gradient(135deg, #fff9f0 0%, #fff4e6 100%)' },
  { label: 'Active Suppliers', value: '42', icon: 'ti-truck-delivery', bg: 'linear-gradient(135deg, #f0f9fb 0%, #e6f4f8 100%)' }
]);

const stock = ref([
  { name: 'Surgical Gloves', level: 85 },
  { name: 'Syringes (5ml)', level: 25 },
  { name: 'Antibiotics A', level: 60 },
  { name: 'Patient Gowns', level: 15 }
]);

const pos = ref([
  { id: '8821', supplier: 'MedTech Corp', status: 'Pending Approval' },
  { id: '8822', supplier: 'Global Pharma', status: 'Awaiting Delivery' },
  { id: '8823', supplier: 'SanitizePlus', status: 'In Review' }
]);
</script>

<style scoped>
.fs-12 { font-size: 12px; }
.fs-14 { font-size: 14px; }
.bg-warning-subtle { background-color: #fff4e6; }
</style>
