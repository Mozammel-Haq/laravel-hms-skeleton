<template>
  <div class="container-fluid py-4">
    <div class="card card-flat p-3 mb-1 page-header">
      <div class="d-flex justify-content-between align-items-center bg-primary-subtle text-primary px-4 pt-3 pb-3 rounded-3 mb-0">
        <div>
          <h5 class="fw-bold mb-1 text-primary">Payroll Run Detail</h5>
          <nav aria-label="breadcrumb">
            <ol class="breadcrumb breadcrumb-dots mb-0 text-muted small">
              <li class="breadcrumb-item">
                <router-link to="/">Dashboard</router-link>
              </li>
              <li class="breadcrumb-item">Payroll</li>
              <li class="breadcrumb-item">
                <router-link :to="{ name: 'PayrollRuns' }">Runs</router-link>
              </li>
              <li class="breadcrumb-item active" aria-current="page">
                {{ runPeriod }}
              </li>
            </ol>
          </nav>
        </div>
        <div class="d-flex align-items-center gap-2">
          <button type="button" class="btn btn-outline-primary" @click="refresh" :disabled="loadingRun || loadingPayslips">
            <i class="ti ti-refresh me-2"></i>Refresh
          </button>
          <button type="button" class="btn btn-primary" @click="printPage">
            <i class="ti ti-printer me-2"></i>Print
          </button>
        </div>
      </div>
    </div>
    <div class="row g-3">
      <div class="col-12">
        <div class="card card-flat">
          <div class="card-header bg-white border-0">
            <div class="d-flex justify-content-between align-items-center">
              <h6 class="mb-0">Payslips</h6>
              <div class="input-group input-group-sm w-auto">
                <span class="input-group-text bg-light border-end-0">
                  <i class="ti ti-search"></i>
                </span>
                <input v-model="search" type="text" class="form-control border-start-0" placeholder="Search employee" />
              </div>
            </div>
          </div>
          <div class="card-body p-0">
            <div v-if="loadingPayslips" class="text-center py-4">
              <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Loading...</span>
              </div>
            </div>
            <div v-else class="table-responsive">
              <table class="table table-sm align-middle mb-0">
                <thead class="table-light">
                  <tr>
                    <th class="ps-4">Employee</th>
                    <th class="text-end">Basic</th>
                    <th class="text-end">Allowances</th>
                    <th class="text-end">Deductions</th>
                    <th class="text-end">Taxes</th>
                    <th class="text-end">Gross</th>
                    <th class="text-end">Net</th>
                    <th class="text-end">Details</th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-if="filteredPayslips.length === 0">
                    <td colspan="8" class="text-center py-4 text-muted">No payslips found</td>
                  </tr>
                  <template v-for="item in filteredPayslips" :key="item.id">
                    <tr>
                      <td class="ps-4">
                        <div class="fw-semibold">{{ item.user?.name || ('Employee #' + item.user_id) }}</div>
                        <div class="text-muted small">{{ item.user?.email }}</div>
                      </td>
                      <td class="text-end">{{ formatMoney(item.basic) }}</td>
                      <td class="text-end">{{ formatMoney(item.total_allowances) }}</td>
                      <td class="text-end">{{ formatMoney(otherDeductions(item)) }}</td>
                      <td class="text-end">{{ formatMoney(taxTotal(item)) }}</td>
                      <td class="text-end">{{ formatMoney(item.gross) }}</td>
                      <td class="text-end fw-semibold">{{ formatMoney(item.net) }}</td>
                      <td class="text-end">
                        <button type="button" class="btn btn-sm btn-light" @click="toggleExpand(item.id)">
                          <span v-if="expandedId === item.id">Hide</span>
                          <span v-else>View</span>
                        </button>
                      </td>
                    </tr>
                    <tr v-if="expandedAll || expandedId === item.id">
                      <td colspan="8" class="bg-light-subtle">
                        <div class="py-2 px-3 mb-0">
                          <div class="row g-1 detail-grid">
                            <div class="col-md-4">
                              <div class="card card-flat card-section">
                                <div class="card-body">
                                  <h6 class="mb-2">Earnings</h6>
                                  <ul class="list-unstyled mb-0 compact-list">
                                    <li v-for="a in (item.meta?.allowances || [])" :key="'a-' + a.id" class="list-row">
                                      <span>{{ a.name }}</span>
                                      <span class="amount fw-semibold">{{ formatMoney(a.calculated_amount) }}</span>
                                    </li>
                                    <li v-if="item.meta?.overtime_allowance" class="list-row">
                                      <span>Overtime</span>
                                      <span class="amount fw-semibold">{{ formatMoney(item.meta.overtime_allowance) }}</span>
                                    </li>
                                    <li v-if="(item.meta?.allowances || []).length === 0 && !item.meta?.overtime_allowance" class="text-muted">None</li>
                                  </ul>
                                </div>
                              </div>
                            </div>
                            <div class="col-md-4">
                              <div class="card card-flat card-section">
                                <div class="card-body">
                                  <h6 class="mb-2">Deductions</h6>
                                  <ul class="list-unstyled mb-0 compact-list">
                                    <li v-if="item.meta?.attendance_deduction" class="list-row">
                                      <span>Attendance</span>
                                      <span class="amount fw-semibold">{{ formatMoney(item.meta.attendance_deduction) }}</span>
                                    </li>
                                    <li v-for="d in (item.meta?.deductions || [])" :key="'d-' + d.id" class="list-row">
                                      <span>{{ d.name }}</span>
                                      <span class="amount fw-semibold">{{ formatMoney(d.calculated_amount) }}</span>
                                    </li>
                                    <li v-if="(item.meta?.deductions || []).length === 0 && !item.meta?.attendance_deduction" class="text-muted">None</li>
                                  </ul>
                                </div>
                              </div>
                            </div>
                            <div class="col-md-4">
                              <div class="card card-flat card-section">
                                <div class="card-body">
                                  <h6 class="mb-2">Taxes</h6>
                                  <ul class="list-unstyled mb-0 compact-list">
                                    <li v-for="t in (item.meta?.taxes || [])" :key="'t-' + t.id" class="list-row">
                                      <span>{{ t.name }}</span>
                                      <span class="amount fw-semibold">{{ formatMoney(t.calculated_amount) }}</span>
                                    </li>
                                    <li v-if="(item.meta?.taxes || []).length === 0" class="text-muted">None</li>
                                  </ul>
                                </div>
                              </div>
                            </div>
                          </div>
                          <div class="row g-1 mt-2">
                            <div class="col-md-6">
                              <div class="card card-flat card-section">
                                <div class="card-body">
                                  <h6 class="mb-2">Work</h6>
                                  <div class="small">
                                    <div v-if="item.meta?.attendance" class="d-flex flex-wrap gap-2">
                                      <span class="badge bg-light text-dark">Days {{ item.meta.attendance.period_days }}</span>
                                      <span class="badge bg-success-subtle text-success">Present {{ item.meta.attendance.present_days }}</span>
                                      <span class="badge bg-warning-subtle text-warning">Half {{ item.meta.attendance.half_days }}</span>
                                      <span class="badge bg-danger-subtle text-danger">Absent {{ item.meta.attendance.absent_days }}</span>
                                      <span class="badge bg-info-subtle text-info">Leave {{ item.meta.attendance.leave_days }}</span>
                                      <span class="badge bg-secondary-subtle text-secondary">Holiday {{ item.meta.attendance.holiday_days }}</span>
                                    </div>
                                    <div class="mt-2" v-if="item.meta?.timesheets">
                                      <span class="badge bg-primary-subtle text-primary">Timesheet {{ item.meta.timesheets.total_hours }}h</span>
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </div>
                            <div class="col-md-6" v-if="item.meta?.overtime && item.meta.overtime.length">
                              <div class="card card-flat card-section">
                                <div class="card-body">
                                  <h6 class="mb-2">Overtime Items</h6>
                                  <div class="table-responsive">
                                    <table class="table table-sm align-middle mb-0 compact-table">
                                      <thead>
                                        <tr>
                                          <th>Date</th>
                                          <th class="text-end">Hours</th>
                                          <th class="text-end">Mult</th>
                                          <th class="text-end">Amount</th>
                                        </tr>
                                      </thead>
                                      <tbody>
                                        <tr v-for="ot in item.meta.overtime" :key="'ot-' + ot.date + '-' + ot.hours">
                                          <td>{{ ot.date }}</td>
                                          <td class="text-end">{{ ot.hours }}</td>
                                          <td class="text-end">{{ ot.multiplier }}</td>
                                          <td class="text-end">{{ formatMoney(ot.calculated_amount) }}</td>
                                        </tr>
                                      </tbody>
                                    </table>
                                  </div>
                                </div>
                              </div>
                            </div>
                          </div>
                          <div class="row g-1 mt-3">
                            <div class="col-12">
                              <div class="card card-flat card-section">
                                <div class="card-body">
                                  <h6 class="mb-2">Totals</h6>
                                  <div class="compact-totals">
                                    <div class="d-flex justify-content-between">
                                      <span>Basic</span>
                                      <span class="fw-semibold">{{ formatMoney(item.basic) }}</span>
                                    </div>
                                    <div class="d-flex justify-content-between">
                                      <span>Total Earnings</span>
                                      <span class="fw-semibold">{{ formatMoney(item.total_allowances) }}</span>
                                    </div>
                                    <div class="d-flex justify-content-between">
                                      <span>Total Deductions</span>
                                      <span class="fw-semibold">{{ formatMoney(otherDeductions(item)) }}</span>
                                    </div>
                                    <div class="d-flex justify-content-between">
                                      <span>Tax Total</span>
                                      <span class="fw-semibold">{{ formatMoney(taxTotal(item)) }}</span>
                                    </div>
                                    <div class="d-flex justify-content-between">
                                      <span>Gross</span>
                                      <span class="fw-semibold">{{ formatMoney(item.gross) }}</span>
                                    </div>
                                    <div class="d-flex justify-content-between">
                                      <span>Net</span>
                                      <span class="fw-semibold">{{ formatMoney(item.net) }}</span>
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                      </td>
                    </tr>
                  </template>
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
  import { computed, onMounted, ref } from 'vue';
  import { nextTick } from 'vue';
  import { useRoute } from 'vue-router';
  import api from '../services/api';

  const route = useRoute();

  const run = ref(null);
  const loadingRun = ref(false);
  const runError = ref('');

  const payslips = ref([]);
  const loadingPayslips = ref(false);
  const search = ref('');
  const expandedId = ref(null);
  const expandedAll = ref(false);

  const formatMoney = (value) => {
    const n = typeof value === 'number' ? value : parseFloat(value || 0);
    return n.toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 });
  };

  const formatDateOnly = (value) => {
    if (!value) return '';
    const d = new Date(value);
    if (Number.isNaN(d.getTime())) return value;
    return d.toLocaleDateString();
  };

  const formatDateTime = (value) => {
    if (!value) return '';
    const d = new Date(value);
    if (Number.isNaN(d.getTime())) return value;
    return d.toLocaleString();
  };

  const statusClass = (status) => {
    if (status === 'completed') return 'bg-success';
    if (status === 'processing') return 'bg-info';
    if (status === 'cancelled') return 'bg-danger';
    return 'bg-secondary';
  };

  const runPeriod = computed(() => {
    if (!run.value) return '';
    return `${formatDateOnly(run.value.period_start)} to ${formatDateOnly(run.value.period_end)}`;
  });

  const taxTotal = (p) => {
    const list = Array.isArray(p?.meta?.taxes) ? p.meta.taxes : [];
    return list.reduce((sum, t) => sum + (parseFloat(t.calculated_amount || 0)), 0);
  };

  const otherDeductions = (p) => {
    const taxes = taxTotal(p);
    const total = parseFloat(p.total_deductions || 0);
    const v = total - taxes;
    return v < 0 ? 0 : v;
  };

  const toggleExpand = (id) => {
    expandedId.value = expandedId.value === id ? null : id;
  };

  const filteredPayslips = computed(() => {
    const term = search.value.trim().toLowerCase();
    if (!term) return payslips.value;
    return payslips.value.filter((p) => {
      const n = (p.user?.name || '').toLowerCase();
      const e = (p.user?.email || '').toLowerCase();
      return n.includes(term) || e.includes(term);
    });
  });

  const fetchRun = async () => {
    loadingRun.value = true;
    runError.value = '';
    try {
      const id = route.params.id;
      const res = await api.get(`/payroll-runs/${id}`);
      run.value = res.data.data || res.data;
    } catch (e) {
      const message = e?.response?.data?.message || 'Failed to load payroll run';
      runError.value = message;
    } finally {
      loadingRun.value = false;
    }
  };

  const fetchPayslips = async () => {
    loadingPayslips.value = true;
    try {
      const id = route.params.id;
      const res = await api.get('/payslips', { params: { payroll_run_id: id, per_page: 200 } });
      const payload = res.data || {};
      const pageData = payload.data || {};
      const list = pageData.data || pageData || [];
      payslips.value = Array.isArray(list) ? list : [];
    } catch (e) {
      console.error('Failed to load payslips', e);
    } finally {
      loadingPayslips.value = false;
    }
  };

  const refresh = async () => {
    await Promise.all([fetchRun(), fetchPayslips()]);
  };

  onMounted(refresh);

  const printPage = async () => {
    expandedAll.value = true;
    await nextTick();
    const handler = () => {
      expandedAll.value = false;
      window.removeEventListener('afterprint', handler);
    };
    window.addEventListener('afterprint', handler);
    window.print();
  };
  </script>

  <style>
  .card-flat {
    border: 1px solid #dee2e6;
    box-shadow: none !important;
    margin: 0 !important;
  }
  .card-section .card-body {
    padding: .5rem .75rem;
  }
  .detail-grid,
  .row.g-1 {
    align-items: stretch;
  }
  .card-section {
    height: 100%;
  }
  .compact-list li {
    padding: 2px 0;
  }
  .compact-list .list-row {
    display: flex;
    align-items: center;
    gap: 16px;
  }
  .compact-list .list-row .amount {
    min-width: 80px;
    text-align: right;
  }
  .compact-table th,
  .compact-table td {
    padding: 0.25rem 0.5rem;
    font-size: 0.875rem;
  }
  .compact-totals > div {
    padding: 2px 0;
  }
  @media (max-width: 768px) {
    .table thead th:nth-child(2),
    .table tbody td:nth-child(2),
    .table thead th:nth-child(6),
    .table tbody td:nth-child(6) {
      display: none;
    }
  }
  @media print {
    .btn,
    .breadcrumb,
    .input-group,
    .card-header,
    .page-header {
      display: none !important;
    }
    header,
    nav,
    .navbar,
    .topbar,
    .app-header,
    .layout-header,
    .brand,
    .logo,
    .sidebar,
    .page-title,
    .brand-logo,
    .brand img,
    .logo img {
      display: none !important;
    }
    html, body {
      margin: 0 !important;
      padding: 0 !important;
    }
    .container-fluid,
    .container-fluid.py-4 {
      padding: 0 !important;
      margin: 0 !important;
      max-width: 100% !important;
    }
    .row {
      --bs-gutter-x: 0;
      --bs-gutter-y: 0;
    }
    .card {
      box-shadow: none !important;
      border: none !important;
      margin: 0 0 4px 0 !important;
    }
    .card-flat {
      border: none !important;
      box-shadow: none !important;
    }
    .card-body {
      padding: 8px !important;
    }
    .card-header {
      padding: 0 !important;
      margin: 0 !important;
    }
    .table {
      font-size: 11px;
      margin: 0 !important;
    }
    .table thead {
      display: none !important;
    }
    .table th,
    .table td {
      padding: 0.2rem 0.3rem !important;
    }
    .bg-light-subtle {
      background: transparent !important;
    }
    .compact-list li {
      padding: 1px 0;
    }
    .compact-table th,
    .compact-table td {
      padding: 0.2rem 0.3rem !important;
      font-size: 0.8rem;
    }
    .compact-totals > div {
      padding: 0 !important;
    }
    .compact-list .list-row {
      gap: 8px !important;
    }
    .compact-list .list-row .amount {
      min-width: 60px;
    }
    .detail-grid {
      display: inline-flex !important;
      flex-direction: row !important;
      flex-wrap: wrap !important;
      gap: 0 !important;
      width: 100% !important;
    }
    .detail-grid .col-md-4,
    .detail-grid .col-md-6,
    .detail-grid .col-xl-3 {
      width: auto !important;
      padding: 0 !important;
      margin: 0 !important;
    }
    .card-section {
      display: inline-block !important;
      margin: 0 !important;
      border: none !important;
    }
    .detail-grid h6 {
      display: inline-block !important;
      margin: 0 8px 0 0 !important;
      font-weight: 600 !important;
    }
    .compact-list {
      display: inline-flex !important;
      flex-wrap: wrap !important;
      gap: 6px !important;
      margin: 0 !important;
    }
    .compact-totals {
      display: inline-flex !important;
      flex-wrap: wrap !important;
      gap: 8px !important;
    }
    .compact-totals > div {
      display: inline-flex !important;
      gap: 6px !important;
      margin-right: 8px !important;
    }
  }
  </style>
