<template>
  <div class="container-fluid py-4">
    <div class="card p-3 mb-3 border-0 shadow-sm">
      <div class="d-flex justify-content-between align-items-center bg-primary-subtle text-primary px-4 pt-3 pb-3 rounded-3 mb-0">
        <div>
          <h5 class="fw-bold mb-1 text-primary">Payroll Runs</h5>
          <nav aria-label="breadcrumb">
            <ol class="breadcrumb breadcrumb-dots mb-0 text-muted small">
              <li class="breadcrumb-item">
                <router-link to="/">Dashboard</router-link>
              </li>
              <li class="breadcrumb-item">Payroll</li>
              <li class="breadcrumb-item active" aria-current="page">Runs</li>
            </ol>
          </nav>
        </div>
        <div class="d-flex gap-2">
          <button
            type="button"
            class="btn btn-outline-primary"
            @click="fetchItems"
            :disabled="loading"
          >
            <i class="ti ti-refresh me-2"></i>Refresh
          </button>
          <button
            type="button"
            class="btn btn-primary"
            @click="openForm()"
          >
            <i class="ti ti-plus me-2"></i>New Payroll Run
          </button>
        </div>
      </div>
    </div>

    <div class="card border-0 shadow-sm mb-3">
      <div class="card-body">
        <div class="row g-3 align-items-end">
          <div class="col-md-3">
            <label class="form-label">From</label>
            <input v-model="filters.from_date" type="date" class="form-control" />
          </div>
          <div class="col-md-3">
            <label class="form-label">To</label>
            <input v-model="filters.to_date" type="date" class="form-control" />
          </div>
          <div class="col-md-3">
            <label class="form-label">Status</label>
            <select v-model="filters.status" class="form-select">
              <option value="">All</option>
              <option value="draft">Draft</option>
              <option value="processing">Processing</option>
              <option value="completed">Completed</option>
              <option value="cancelled">Cancelled</option>
            </select>
          </div>
          <div class="col-md-3 d-flex gap-2">
            <button class="btn btn-outline-primary w-100" @click="fetchItems">
              Apply
            </button>
            <button class="btn btn-light w-100" @click="resetFilters">
              Reset
            </button>
          </div>
        </div>
      </div>
    </div>

    <div class="card border-0 shadow-sm">
      <div class="card-body">
        <div class="table-responsive">
          <table class="table table-sm align-middle">
            <thead>
              <tr>
                <th>Period</th>
                <th>Status</th>
                <th class="text-end">Total Gross</th>
                <th class="text-end">Total Net</th>
                <th>Processed By</th>
                <th>Updated</th>
                <th class="text-end">Actions</th>
              </tr>
            </thead>
            <tbody>
              <tr v-if="loading">
                <td colspan="7" class="text-center py-4">
                  <div class="spinner-border spinner-border-sm text-primary me-2" role="status"></div>
                  Loading payroll runs...
                </td>
              </tr>
              <tr v-else-if="items.length === 0">
                <td colspan="7" class="text-center py-4 text-muted">
                  No payroll runs found.
                </td>
              </tr>
              <tr v-for="run in items" :key="run.id">
                <td>
                  {{ formatDateOnly(run.period_start) }} to {{ formatDateOnly(run.period_end) }}
                </td>
                <td>
                  <span class="badge" :class="statusClass(run.status)">
                    {{ run.status }}
                  </span>
                </td>
                <td class="text-end">{{ formatMoney(run.total_gross) }}</td>
                <td class="text-end">{{ formatMoney(run.total_net) }}</td>
                <td>
                  <span v-if="run.status === 'processing'" class="text-warning small">
                    Running...
                  </span>
                  <span v-else>
                    <template v-if="run.processor && run.processor.name">
                      {{ run.processor.name }}
                    </template>
                    <template v-else>
                      {{ run.processed_by ? '#' + run.processed_by : '-' }}
                    </template>
                  </span>
                </td>
                <td>{{ formatDateTime(run.updated_at) }}</td>
                <td class="text-end">
                  <div class="dropdown">
                    <button
                      type="button"
                      class="btn btn-sm btn-light btn-icon"
                      @click="toggleRowMenu(run.id)"
                    >
                      <i class="ti ti-dots-vertical"></i>
                    </button>
                    <ul
                      class="dropdown-menu dropdown-menu-end shadow-sm border-0"
                      :class="{ show: openMenuId === run.id }"
                    >
                      <li v-if="run.status === 'draft' || run.status === 'cancelled'">
                        <a
                          href="#"
                          class="dropdown-item"
                          @click.prevent="() => { closeRowMenu(); processRun(run); }"
                        >
                          <i class="ti ti-play me-2"></i>Run Payroll
                        </a>
                      </li>
                      <li>
                        <a
                          href="#"
                          class="dropdown-item"
                          @click.prevent="() => { closeRowMenu(); openForm(run); }"
                        >
                          <i class="ti ti-edit me-2"></i>Edit
                        </a>
                      </li>
                      <li>
                        <a
                          href="#"
                          class="dropdown-item text-danger"
                          @click.prevent="() => { closeRowMenu(); deleteRun(run); }"
                          :class="{ disabled: run.status === 'completed' || deletingId === run.id }"
                        >
                          <i class="ti ti-trash me-2"></i>Delete
                        </a>
                      </li>
                    </ul>
                  </div>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>

    <div class="modal fade" tabindex="-1" ref="formModal">
      <div class="modal-dialog modal-md modal-dialog-centered">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">{{ form.id ? 'Edit Payroll Run' : 'New Payroll Run' }}</h5>
            <button type="button" class="btn-close" @click="closeForm"></button>
          </div>
          <div class="modal-body">
            <div v-if="formError" class="alert alert-danger py-2 mb-3">
              {{ formError }}
            </div>
            <div class="mb-3">
              <label class="form-label">Period Start</label>
              <input v-model="form.period_start" type="date" class="form-control" />
            </div>
            <div class="mb-3">
              <label class="form-label">Period End</label>
              <input v-model="form.period_end" type="date" class="form-control" />
            </div>
            <div class="mb-3" v-if="form.id">
              <label class="form-label">Status</label>
              <select v-model="form.status" class="form-select">
                <option value="draft">Draft</option>
                <option value="processing">Processing</option>
                <option value="completed">Completed</option>
                <option value="cancelled">Cancelled</option>
              </select>
              <div class="form-text">
                Mark as completed after verifying payslips for this period.
              </div>
            </div>
            <div class="row" v-if="form.id">
              <div class="col-md-6 mb-3">
                <label class="form-label">Total Gross</label>
                <input v-model.number="form.total_gross" type="number" step="0.01" class="form-control" />
              </div>
              <div class="col-md-6 mb-3">
                <label class="form-label">Total Net</label>
                <input v-model.number="form.total_net" type="number" step="0.01" class="form-control" />
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-light" @click="closeForm">Cancel</button>
            <button type="button" class="btn btn-primary" :disabled="saving" @click="saveForm">
              <span v-if="saving" class="spinner-border spinner-border-sm me-2"></span>
              Save
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import api from '../services/api';

const normalizeDateForPeriod = (value) => {
  if (!value) return null;
  const v = String(value).slice(0, 10);
  return `${v}T00:00:00.000000Z`;
};

const toDateInputValue = (value) => {
  if (!value) return '';
  return String(value).slice(0, 10);
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

const items = ref([]);
const loading = ref(false);
const saving = ref(false);
const deletingId = ref(null);
const processingId = ref(null);
const filters = ref({
  from_date: '',
  to_date: '',
  status: ''
});

const formModal = ref(null);
const form = ref({
  id: null,
  period_start: '',
  period_end: '',
  status: 'draft',
  total_gross: 0,
  total_net: 0
});
const formError = ref('');

const openMenuId = ref(null);

const formatMoney = (value) => {
  const n = typeof value === 'number' ? value : parseFloat(value || 0);
  return n.toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 });
};

const statusClass = (status) => {
  if (status === 'completed') return 'bg-success';
  if (status === 'processing') return 'bg-info';
  if (status === 'cancelled') return 'bg-danger';
  return 'bg-secondary';
};

const toggleRowMenu = (id) => {
  openMenuId.value = openMenuId.value === id ? null : id;
};

const closeRowMenu = () => {
  openMenuId.value = null;
};

const fetchItems = async () => {
  loading.value = true;
  try {
    const params = {};
    const from = normalizeDateForPeriod(filters.value.from_date);
    const to = normalizeDateForPeriod(filters.value.to_date);
    if (from) params.from_date = from;
    if (to) params.to_date = to;
    if (filters.value.status) params.status = filters.value.status;
    params.per_page = 50;
    const response = await api.get('/payroll-runs', { params });
    const payload = response.data;
    const pageData = payload.data;
    const data = pageData?.data || pageData;
    items.value = Array.isArray(data) ? data : [];
  } catch (e) {
    console.error(e);
  } finally {
    loading.value = false;
  }
};

const resetFilters = () => {
  filters.value = {
    from_date: '',
    to_date: '',
    status: ''
  };
  fetchItems();
};

const openForm = (run = null) => {
  formError.value = '';
  if (run) {
    form.value = {
      id: run.id,
      period_start: toDateInputValue(run.period_start),
      period_end: toDateInputValue(run.period_end),
      status: run.status,
      total_gross: run.total_gross ?? 0,
      total_net: run.total_net ?? 0
    };
  } else {
    form.value = {
      id: null,
      period_start: '',
      period_end: '',
      status: 'draft',
      total_gross: 0,
      total_net: 0
    };
  }
  const modalEl = formModal.value;
  if (modalEl) {
    const modal = new window.bootstrap.Modal(modalEl);
    modal.show();
    modalEl._modalInstance = modal;
  }
};

const closeForm = () => {
  const modalEl = formModal.value;
  if (modalEl && modalEl._modalInstance) {
    modalEl._modalInstance.hide();
  }
};

const saveForm = async () => {
  saving.value = true;
  formError.value = '';
  try {
    const start = normalizeDateForPeriod(form.value.period_start);
    const end = normalizeDateForPeriod(form.value.period_end);
    const payload = {
      period_start: start,
      period_end: end,
      status: form.value.status,
      total_gross: form.value.total_gross,
      total_net: form.value.total_net
    };
    if (form.value.id) {
      await api.put(`/payroll-runs/${form.value.id}`, payload);
    } else {
      await api.post('/payroll-runs', payload);
    }
    closeForm();
    await fetchItems();
  } catch (e) {
    const message = e?.response?.data?.message;
    formError.value = typeof message === 'string' ? message : 'Failed to save payroll run';
  } finally {
    saving.value = false;
  }
};

const processRun = async (run) => {
  if (!run || !run.id) return;
  if (!window.confirm('Run payroll for this period now?')) return;

  processingId.value = run.id;

  try {
    await api.put(`/payroll-runs/${run.id}`, {
      status: 'processing'
    });
    await fetchItems();
  } catch (e) {
    // For debugging: log full error details instead of showing an alert
    console.error('Failed to run payroll', {
      error: e,
      response: e?.response,
      data: e?.response?.data,
      status: e?.response?.status
    });
  } finally {
    processingId.value = null;
  }
};

const deleteRun = async (run) => {
  if (!window.confirm('Delete this payroll run?')) return;
  deletingId.value = run.id;
  try {
    await api.delete(`/payroll-runs/${run.id}`);
    await fetchItems();
  } catch (e) {
    console.error(e);
  } finally {
    deletingId.value = null;
  }
};

onMounted(() => {
  fetchItems();
});
</script>
