<template>
  <div class="dashboard-page">
    <div class="d-flex justify-content-between align-items-center mb-4">
      <h4 class="mb-0">Asset & Inventory Dashboard</h4>
      <div class="text-muted">{{ today }}</div>
    </div>

    <!-- KPI Cards -->
    <div class="row g-4 mb-4">
      <div class="col-md-3" v-for="(kpi, idx) in kpis" :key="kpi.label">
        <div class="position-relative overflow-hidden rounded-4 h-100 kpi-card" :class="'kpi-' + kpi.type" data-bs-theme="light,dark">
          <div class="position-absolute top-0 end-0 w-100 h-100 opacity-25 pattern-bg">
            <svg width="100%" height="100%" xmlns="http://www.w3.org/2000/svg">
              <defs>
                <pattern :id="'pattern-grid-asset-' + idx" x="0" y="0" width="30" height="30" patternUnits="userSpaceOnUse">
                  <rect x="0" y="0" width="2" height="2" :fill="kpi.type === 'primary' ? 'var(--primary-color)' : kpi.type === 'info' ? 'var(--info-color)' : kpi.type === 'success' ? 'var(--success-color)' : kpi.type === 'warning' ? 'var(--warning-color)' : '#dc3545'" fill-opacity="0.2" />
                </pattern>
              </defs>
              <rect width="100%" height="100%" :fill="'url(#pattern-grid-asset-' + idx + ')'" />
            </svg>
          </div>
          <div class="position-absolute top-0 end-0 w-25 h-25 decorative-shape"
               :style="{ background: 'radial-gradient(circle at top right, ' + (kpi.type === 'primary' ? 'var(--primary-color)' : kpi.type === 'info' ? 'var(--info-color)' : kpi.type === 'success' ? 'var(--success-color)' : kpi.type === 'warning' ? 'var(--warning-color)' : '#dc3545') + ' 0%, transparent 70%)', opacity: 0.15 }">
          </div>
          <div class="card-body position-relative z-1 p-4">
            <div class="d-flex align-items-start justify-content-between mb-3">
              <div>
                <h6 class="card-title fw-medium mb-1 kpi-label" style="letter-spacing: 0.5px;">{{ kpi.label }}</h6>
                <h2 class="fw-bold kpi-value mb-0">{{ kpi.value }}</h2>
              </div>
              <div class="rounded-3 p-2 kpi-icon-container"
                   :class="['bg-' + (kpi.type === 'danger' ? 'danger' : kpi.type) + '-subtle', 'border', 'border-' + (kpi.type === 'danger' ? 'danger' : kpi.type) + '-subtle']">
                <i class="ti fs-2" :class="kpi.icon"></i>
              </div>
            </div>
            <div class="border-top pt-3 mt-3 kpi-divider" :class="'border-' + (kpi.type === 'danger' ? 'danger' : kpi.type) + '-subtle'">
              <div class="d-flex align-items-center">
                <div class="kpi-small-icon me-2"
                     :class="['bg-' + (kpi.type === 'danger' ? 'danger' : kpi.type) + '-subtle', 'border', 'border-' + (kpi.type === 'danger' ? 'danger' : kpi.type) + '-subtle']">
                  <i class="ti" :class="kpi.alert ? 'ti-alert-triangle text-danger' : 'ti-check text-success'"></i>
                </div>
                <p class="text-muted kpi-footer mb-0">
                  <span v-if="kpi.alert">{{ kpi.alert }} items low</span>
                  <span v-else>All systems optimal</span>
                </p>
              </div>
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
  { label: 'Total Assets', value: '1,240', icon: 'ti-building-hospital', type: 'primary' },
  { label: 'Low Stock Items', value: '18', icon: 'ti-package-off', alert: '12', type: 'danger' },
  { label: 'Maintenance Due', value: '4', icon: 'ti-tools', alert: '2', type: 'warning' },
  { label: 'Active Suppliers', value: '42', icon: 'ti-truck-delivery', type: 'info' }
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
