<template>
  <div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
      <h4 class="mb-0">Payroll Taxes</h4>
      <button class="btn btn-primary btn-sm" @click="openForm()">
        <i class="ti ti-plus me-1"></i>
        New Tax Rule
      </button>
    </div>

    <div class="card border-0 shadow-sm">
      <div class="card-body">
        <div class="row g-3 align-items-end mb-3">
          <div class="col-md-3">
            <label class="form-label">Status</label>
            <select v-model="filters.status" class="form-select" @change="fetchItems">
              <option value="">All</option>
              <option value="active">Active</option>
              <option value="inactive">Inactive</option>
            </select>
          </div>
        </div>

        <div class="table-responsive">
          <table class="table table-sm align-middle">
            <thead>
              <tr>
                <th>Name</th>
                <th>Code</th>
                <th>Type</th>
                <th class="text-end">Rate / Amount</th>
                <th class="text-end">Threshold</th>
                <th>Status</th>
                <th class="text-end">Actions</th>
              </tr>
            </thead>
            <tbody>
              <tr v-if="loading">
                <td colspan="7" class="text-center py-4">
                  <div class="spinner-border spinner-border-sm text-primary me-2" role="status"></div>
                  Loading taxes...
                </td>
              </tr>
              <tr v-else-if="items.length === 0">
                <td colspan="7" class="text-center py-4 text-muted">
                  No tax rules found.
                </td>
              </tr>
              <tr v-for="item in items" :key="item.id">
                <td>{{ item.name }}</td>
                <td>{{ item.code || '-' }}</td>
                <td>{{ item.calculation_type === 'flat' ? 'Flat' : 'Percent' }}</td>
                <td class="text-end">
                  <span v-if="item.calculation_type === 'flat'">
                    {{ formatMoney(item.rate) }}
                  </span>
                  <span v-else>
                    {{ item.rate }}%
                  </span>
                </td>
                <td class="text-end">
                  {{ item.threshold != null ? formatMoney(item.threshold) : '-' }}
                </td>
                <td>
                  <span class="badge" :class="item.status === 'active' ? 'bg-success' : 'bg-secondary'">
                    {{ item.status }}
                  </span>
                </td>
                <td class="text-end">
                  <button class="btn btn-link btn-sm text-primary me-2" @click="openForm(item)">
                    <i class="ti ti-edit"></i>
                  </button>
                  <button
                    class="btn btn-link btn-sm text-danger"
                    :disabled="item.status === 'inactive' || savingId === item.id"
                    @click="archiveItem(item)"
                  >
                    <i class="ti ti-archive"></i>
                  </button>
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
            <h5 class="modal-title">{{ form.id ? 'Edit Tax Rule' : 'New Tax Rule' }}</h5>
            <button type="button" class="btn-close" @click="closeForm"></button>
          </div>
          <div class="modal-body">
            <div v-if="formError" class="alert alert-danger py-2 mb-3">
              {{ formError }}
            </div>
            <div class="mb-3">
              <label class="form-label">Name</label>
              <input v-model="form.name" type="text" class="form-control" />
            </div>
            <div class="mb-3">
              <label class="form-label">Code</label>
              <input v-model="form.code" type="text" class="form-control" />
            </div>
            <div class="mb-3">
              <label class="form-label">Calculation Type</label>
              <select v-model="form.calculation_type" class="form-select">
                <option value="percent">Percent</option>
                <option value="flat">Flat Amount</option>
              </select>
            </div>
            <div class="mb-3">
              <label class="form-label">Rate / Amount</label>
              <input v-model.number="form.rate" type="number" step="0.001" class="form-control" />
              <div class="form-text" v-if="form.calculation_type === 'percent'">
                Percentage of taxable income.
              </div>
            </div>
            <div class="mb-3">
              <label class="form-label">Threshold</label>
              <input v-model.number="form.threshold" type="number" step="0.01" class="form-control" />
              <div class="form-text">
                Optional minimum salary before this tax applies.
              </div>
            </div>
            <div class="mb-2">
              <label class="form-label">Status</label>
              <select v-model="form.status" class="form-select">
                <option value="active">Active</option>
                <option value="inactive">Inactive</option>
              </select>
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

const items = ref([]);
const loading = ref(false);
const saving = ref(false);
const savingId = ref(null);
const filters = ref({
  status: 'active'
});

const formModal = ref(null);
const form = ref({
  id: null,
  name: '',
  code: '',
  calculation_type: 'percent',
  rate: 0,
  threshold: null,
  status: 'active'
});
const formError = ref('');

const formatMoney = (value) => {
  const n = typeof value === 'number' ? value : parseFloat(value || 0);
  return n.toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 });
};

const fetchItems = async () => {
  loading.value = true;
  try {
    const params = {};
    if (filters.value.status) {
      params.status = filters.value.status;
    }
    const response = await api.get('/payroll-taxes', { params });
    const payload = response.data;
    items.value = Array.isArray(payload.data) ? payload.data : [];
  } catch (e) {
    console.error(e);
  } finally {
    loading.value = false;
  }
};

const openForm = (item = null) => {
  formError.value = '';
  if (item) {
    form.value = {
      id: item.id,
      name: item.name || '',
      code: item.code || '',
      calculation_type: item.calculation_type || 'percent',
      rate: item.rate ?? 0,
      threshold: item.threshold,
      status: item.status || 'active'
    };
  } else {
    form.value = {
      id: null,
      name: '',
      code: '',
      calculation_type: 'percent',
      rate: 0,
      threshold: null,
      status: 'active'
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
      name: form.value.name,
      code: form.value.code || null,
      calculation_type: form.value.calculation_type,
      rate: form.value.rate,
      threshold: form.value.threshold,
      status: form.value.status
    };
    if (form.value.id) {
      savingId.value = form.value.id;
      await api.put(`/payroll-taxes/${form.value.id}`, payload);
    } else {
      await api.post('/payroll-taxes', payload);
    }
    closeForm();
    await fetchItems();
  } catch (e) {
    const message = e?.response?.data?.message;
    formError.value = typeof message === 'string' ? message : 'Failed to save tax rule';
  } finally {
    saving.value = false;
    savingId.value = null;
  }
};

const archiveItem = async (item) => {
  if (!window.confirm('Mark this tax rule as inactive?')) return;
  savingId.value = item.id;
  try {
    await api.delete(`/payroll-taxes/${item.id}`);
    await fetchItems();
  } catch (e) {
    console.error(e);
  } finally {
    savingId.value = null;
  }
};

onMounted(() => {
  fetchItems();
});
</script>

