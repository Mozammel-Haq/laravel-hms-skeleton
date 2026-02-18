<template>
  <div class="container-fluid py-4">
    <div class="card p-3 mb-3 border-0 shadow-sm">
      <div class="d-flex justify-content-between align-items-center bg-primary-subtle text-primary px-4 pt-3 pb-3 rounded-3 mb-0">
        <div>
          <h5 class="fw-bold mb-1 text-primary">Payslips</h5>
          <nav aria-label="breadcrumb">
            <ol class="breadcrumb breadcrumb-dots mb-0 text-muted small">
              <li class="breadcrumb-item">
                <router-link to="/">Dashboard</router-link>
              </li>
              <li class="breadcrumb-item">Payroll</li>
              <li class="breadcrumb-item active" aria-current="page">Payslips</li>
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
            v-if="canManage"
            type="button"
            class="btn btn-primary"
            @click="openForm()"
          >
            <i class="ti ti-plus me-2"></i>New Payslip
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
          <div class="col-md-3" v-if="canManage">
            <label class="form-label">Employee ID</label>
            <input v-model="filters.user_id" type="number" class="form-control" placeholder="User ID" />
          </div>
          <div class="col-md-3" v-if="canManage">
            <label class="form-label">Payroll Run ID</label>
            <input v-model="filters.payroll_run_id" type="number" class="form-control" placeholder="Run ID" />
          </div>
          <div class="col-md-3">
            <label class="form-label">Status</label>
            <select v-model="filters.status" class="form-select">
              <option value="">All</option>
              <option value="draft">Draft</option>
              <option value="confirmed">Confirmed</option>
              <option value="paid">Paid</option>
            </select>
          </div>
        </div>
        <div class="mt-3 d-flex gap-2">
          <button class="btn btn-outline-primary" @click="fetchItems">
            Apply
          </button>
          <button class="btn btn-light" @click="resetFilters">
            Reset
          </button>
        </div>
      </div>
    </div>

    <div class="card border-0 shadow-sm">
      <div class="card-body">
        <div class="table-responsive">
          <table class="table table-sm align-middle">
            <thead>
              <tr>
                <th>Employee</th>
                <th>Period</th>
                <th>Status</th>
                <th class="text-end">Basic</th>
                <th class="text-end">Allowances</th>
                <th class="text-end">Deductions</th>
                <th class="text-end">Net Pay</th>
                <th class="text-end" v-if="canManage">Actions</th>
              </tr>
            </thead>
            <tbody>
              <tr v-if="loading">
                <td :colspan="canManage ? 8 : 7" class="text-center py-4">
                  <div class="spinner-border spinner-border-sm text-primary me-2" role="status"></div>
                  Loading payslips...
                </td>
              </tr>
              <tr v-else-if="items.length === 0">
                <td :colspan="canManage ? 8 : 7" class="text-center py-4 text-muted">
                  No payslips found.
                </td>
              </tr>
              <tr v-for="item in items" :key="item.id">
                <td>
                  <div class="fw-semibold">
                    {{ item.user?.name || 'Employee #' + item.user_id }}
                  </div>
                  <div class="text-muted small">
                    {{ item.user?.email }}
                  </div>
                </td>
                <td>
                  {{ item.period_start }} to {{ item.period_end }}
                </td>
                <td>
                  <span class="badge" :class="statusClass(item.status)">
                    {{ item.status }}
                  </span>
                </td>
                <td class="text-end">{{ formatMoney(item.basic) }}</td>
                <td class="text-end">{{ formatMoney(item.total_allowances) }}</td>
                <td class="text-end">{{ formatMoney(item.total_deductions) }}</td>
                <td class="text-end fw-semibold">{{ formatMoney(item.net) }}</td>
                <td class="text-end" v-if="canManage">
                  <div class="dropdown">
                    <button
                      type="button"
                      class="btn btn-sm btn-light btn-icon"
                      @click="toggleRowMenu(item.id)"
                    >
                      <i class="ti ti-dots-vertical"></i>
                    </button>
                    <ul
                      class="dropdown-menu dropdown-menu-end shadow-sm border-0"
                      :class="{ show: openMenuId === item.id }"
                    >
                      <li>
                        <a
                          href="#"
                          class="dropdown-item"
                          @click.prevent="() => { closeRowMenu(); openForm(item); }"
                        >
                          <i class="ti ti-edit me-2"></i>Edit
                        </a>
                      </li>
                      <li>
                        <a
                          href="#"
                          class="dropdown-item text-danger"
                          @click.prevent="() => { closeRowMenu(); deletePayslip(item); }"
                          :class="{ disabled: item.status === 'paid' || deletingId === item.id }"
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
      <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">{{ form.id ? 'Edit Payslip' : 'New Payslip' }}</h5>
            <button type="button" class="btn-close" @click="closeForm"></button>
          </div>
          <div class="modal-body">
            <div v-if="formError" class="alert alert-danger py-2 mb-3">
              {{ formError }}
            </div>
            <div class="row">
              <div class="col-md-4 mb-3">
                <label class="form-label">Employee ID</label>
                <input v-model.number="form.user_id" type="number" class="form-control" />
              </div>
              <div class="col-md-4 mb-3">
                <label class="form-label">Period Start</label>
                <input v-model="form.period_start" type="date" class="form-control" />
              </div>
              <div class="col-md-4 mb-3">
                <label class="form-label">Period End</label>
                <input v-model="form.period_end" type="date" class="form-control" />
              </div>
            </div>
            <div class="row">
              <div class="col-md-4 mb-3">
                <label class="form-label">Basic</label>
                <input v-model.number="form.basic" type="number" step="0.01" class="form-control" />
              </div>
              <div class="col-md-4 mb-3">
                <label class="form-label">Total Allowances</label>
                <input v-model.number="form.total_allowances" type="number" step="0.01" class="form-control" />
              </div>
              <div class="col-md-4 mb-3">
                <label class="form-label">Total Deductions</label>
                <input v-model.number="form.total_deductions" type="number" step="0.01" class="form-control" />
              </div>
            </div>
            <div class="row">
              <div class="col-md-4 mb-3">
                <label class="form-label">Gross</label>
                <input v-model.number="form.gross" type="number" step="0.01" class="form-control" />
              </div>
              <div class="col-md-4 mb-3">
                <label class="form-label">Net</label>
                <input v-model.number="form.net" type="number" step="0.01" class="form-control" />
              </div>
              <div class="col-md-4 mb-3">
                <label class="form-label">Payroll Run ID</label>
                <input v-model.number="form.payroll_run_id" type="number" class="form-control" />
              </div>
            </div>
            <div class="row">
              <div class="col-md-4 mb-3">
                <label class="form-label">Status</label>
                <select v-model="form.status" class="form-select">
                  <option value="draft">Draft</option>
                  <option value="confirmed">Confirmed</option>
                  <option value="paid">Paid</option>
                </select>
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
import { computed, ref, onMounted } from 'vue';
import { useAuthStore } from '../store/authStore';
import api from '../services/api';

const auth = useAuthStore();
const abilities = computed(() => Array.isArray(auth.user?.abilities) ? auth.user.abilities : []);
const has = (perm) => abilities.value.includes(perm);
const canManage = computed(() => has('view_reports') || has('view_financial_reports'));

const items = ref([]);
const loading = ref(false);
const saving = ref(false);
const deletingId = ref(null);
const filters = ref({
  from_date: '',
  to_date: '',
  status: '',
  user_id: '',
  payroll_run_id: ''
});

const formModal = ref(null);
const form = ref({
  id: null,
  user_id: null,
  payroll_run_id: null,
  period_start: '',
  period_end: '',
  basic: 0,
  total_allowances: 0,
  total_deductions: 0,
  gross: 0,
  net: 0,
  status: 'draft'
});
const formError = ref('');

const openMenuId = ref(null);

const formatMoney = (value) => {
  const n = typeof value === 'number' ? value : parseFloat(value || 0);
  return n.toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 });
};

const statusClass = (status) => {
  if (status === 'paid') return 'bg-success';
  if (status === 'confirmed') return 'bg-info';
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
    if (filters.value.from_date) params.from_date = filters.value.from_date;
    if (filters.value.to_date) params.to_date = filters.value.to_date;
    if (filters.value.status) params.status = filters.value.status;
    if (filters.value.user_id && canManage.value) params.user_id = filters.value.user_id;
    if (filters.value.payroll_run_id && canManage.value) params.payroll_run_id = filters.value.payroll_run_id;
    params.per_page = 50;
    const response = await api.get('/payslips', { params });
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
    status: '',
    user_id: '',
    payroll_run_id: ''
  };
  fetchItems();
};

const openForm = (payslip = null) => {
  formError.value = '';
  if (payslip) {
    form.value = {
      id: payslip.id,
      user_id: payslip.user_id,
      payroll_run_id: payslip.payroll_run_id ?? null,
      period_start: payslip.period_start,
      period_end: payslip.period_end,
      basic: payslip.basic ?? 0,
      total_allowances: payslip.total_allowances ?? 0,
      total_deductions: payslip.total_deductions ?? 0,
      gross: payslip.gross ?? 0,
      net: payslip.net ?? 0,
      status: payslip.status
    };
  } else {
    form.value = {
      id: null,
      user_id: null,
      payroll_run_id: null,
      period_start: '',
      period_end: '',
      basic: 0,
      total_allowances: 0,
      total_deductions: 0,
      gross: 0,
      net: 0,
      status: 'draft'
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
    const payload = {
      user_id: form.value.user_id,
      payroll_run_id: form.value.payroll_run_id,
      period_start: form.value.period_start,
      period_end: form.value.period_end,
      basic: form.value.basic,
      total_allowances: form.value.total_allowances,
      total_deductions: form.value.total_deductions,
      gross: form.value.gross,
      net: form.value.net,
      status: form.value.status
    };
    if (form.value.id) {
      await api.put(`/payslips/${form.value.id}`, payload);
    } else {
      await api.post('/payslips', payload);
    }
    closeForm();
    await fetchItems();
  } catch (e) {
    const message = e?.response?.data?.message;
    formError.value = typeof message === 'string' ? message : 'Failed to save payslip';
  } finally {
    saving.value = false;
  }
};

const deletePayslip = async (payslip) => {
  if (!window.confirm('Delete this payslip?')) return;
  deletingId.value = payslip.id;
  try {
    await api.delete(`/payslips/${payslip.id}`);
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
