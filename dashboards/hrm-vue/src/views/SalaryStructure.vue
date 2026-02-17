<template>
  <div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
      <h4 class="mb-0">Salary Structures</h4>
      <button class="btn btn-primary btn-sm" @click="openForm()">
        <i class="ti ti-plus me-1"></i>
        New Structure
      </button>
    </div>

    <div class="card border-0 shadow-sm">
      <div class="card-body">
        <div class="table-responsive">
          <table class="table table-sm align-middle">
            <thead>
              <tr>
                <th>Name</th>
                <th>Code</th>
                <th class="text-end">Basic Amount</th>
                <th>Default</th>
                <th>Status</th>
                <th class="text-end">Actions</th>
              </tr>
            </thead>
            <tbody>
              <tr v-if="loading">
                <td colspan="6" class="text-center py-4">
                  <div class="spinner-border spinner-border-sm text-primary me-2" role="status"></div>
                  Loading structures...
                </td>
              </tr>
              <tr v-else-if="items.length === 0">
                <td colspan="6" class="text-center py-4 text-muted">
                  No salary structures defined.
                </td>
              </tr>
              <tr v-for="item in items" :key="item.id">
                <td>{{ item.name }}</td>
                <td>{{ item.code || '-' }}</td>
                <td class="text-end">{{ formatMoney(item.basic_amount) }}</td>
                <td>
                  <span v-if="item.is_default" class="badge bg-primary">Default</span>
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
            <h5 class="modal-title">{{ form.id ? 'Edit Structure' : 'New Structure' }}</h5>
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
              <label class="form-label">Basic Amount</label>
              <input v-model.number="form.basic_amount" type="number" step="0.01" class="form-control" />
            </div>
            <div class="form-check form-switch mb-3">
              <input
                class="form-check-input"
                type="checkbox"
                id="isDefault"
                v-model="form.is_default"
              />
              <label class="form-check-label" for="isDefault">
                Set as default for new staff
              </label>
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

const formModal = ref(null);
const form = ref({
  id: null,
  name: '',
  code: '',
  basic_amount: 0,
  is_default: false,
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
    const response = await api.get('/salary-structures');
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
      basic_amount: item.basic_amount ?? 0,
      is_default: !!item.is_default,
      status: item.status || 'active'
    };
  } else {
    form.value = {
      id: null,
      name: '',
      code: '',
      basic_amount: 0,
      is_default: false,
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
      basic_amount: form.value.basic_amount,
      is_default: form.value.is_default,
      status: form.value.status
    };
    if (form.value.id) {
      savingId.value = form.value.id;
      await api.put(`/salary-structures/${form.value.id}`, payload);
    } else {
      await api.post('/salary-structures', payload);
    }
    closeForm();
    await fetchItems();
  } catch (e) {
    const message = e?.response?.data?.message;
    formError.value = typeof message === 'string' ? message : 'Failed to save structure';
  } finally {
    saving.value = false;
    savingId.value = null;
  }
};

const archiveItem = async (item) => {
  if (!window.confirm('Mark this structure as inactive?')) return;
  savingId.value = item.id;
  try {
    await api.delete(`/salary-structures/${item.id}`);
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

