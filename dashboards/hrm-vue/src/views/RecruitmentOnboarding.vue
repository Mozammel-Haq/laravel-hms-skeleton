<template>
  <div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
      <h4 class="mb-0">Onboarding</h4>
      <button class="btn btn-primary btn-sm" @click="openForm()">
        <i class="ti ti-plus me-1"></i>
        New Onboarding
      </button>
    </div>

    <div class="card border-0 shadow-sm mb-3">
      <div class="card-body">
        <div class="row g-3 align-items-end">
          <div class="col-md-3">
            <label class="form-label">Status</label>
            <select v-model="filters.status" class="form-select">
              <option value="">All</option>
              <option value="pending">Pending</option>
              <option value="in_progress">In Progress</option>
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
                <th>Candidate</th>
                <th>User</th>
                <th>Dates</th>
                <th>Status</th>
                <th>Checklist</th>
                <th class="text-end">Actions</th>
              </tr>
            </thead>
            <tbody>
              <tr v-if="loading">
                <td colspan="6" class="text-center py-4">
                  <div class="spinner-border spinner-border-sm text-primary me-2" role="status"></div>
                  Loading onboarding records...
                </td>
              </tr>
              <tr v-else-if="items.length === 0">
                <td colspan="6" class="text-center py-4 text-muted">
                  No onboarding records found.
                </td>
              </tr>
              <tr v-for="item in items" :key="item.id">
                <td>
                  <div class="fw-semibold">
                    {{ item.candidate?.name || (item.candidate_id ? 'Candidate #'+item.candidate_id : '-') }}
                  </div>
                  <div class="text-muted small" v-if="item.candidate_id">
                    #{{ item.candidate_id }}
                  </div>
                </td>
                <td>
                  <div v-if="item.user_id" class="small">
                    User #{{ item.user_id }}
                  </div>
                  <div v-else class="text-muted small">Not linked</div>
                </td>
                <td>
                  <div class="small">
                    <div v-if="item.start_date">Start: {{ formatDate(item.start_date) }}</div>
                    <div v-if="item.completion_date">Complete: {{ formatDate(item.completion_date) }}</div>
                  </div>
                </td>
                <td>
                  <span class="badge" :class="statusClass(item.status)">
                    {{ item.status }}
                  </span>
                </td>
                <td>
                  <div class="small">
                    <span v-if="Array.isArray(item.checklist)">
                      {{ completedCount(item.checklist) }}/{{ item.checklist.length }} done
                    </span>
                    <span v-else class="text-muted">-</span>
                  </div>
                </td>
                <td class="text-end">
                  <button class="btn btn-link btn-sm text-primary me-2" @click="openForm(item)">
                    <i class="ti ti-edit"></i>
                  </button>
                  <button
                    class="btn btn-link btn-sm text-danger"
                    :disabled="savingId === item.id"
                    @click="deleteItem(item)"
                  >
                    <i class="ti ti-trash"></i>
                  </button>
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
            <h5 class="modal-title">{{ form.id ? 'Edit Onboarding' : 'New Onboarding' }}</h5>
            <button type="button" class="btn-close" @click="closeForm"></button>
          </div>
          <div class="modal-body">
            <div v-if="formError" class="alert alert-danger py-2 mb-3">
              {{ formError }}
            </div>
            <div class="row">
              <div class="col-md-4 mb-3">
                <label class="form-label">Candidate ID</label>
                <input v-model.number="form.candidate_id" type="number" class="form-control" />
              </div>
              <div class="col-md-4 mb-3">
                <label class="form-label">User ID</label>
                <input v-model.number="form.user_id" type="number" class="form-control" />
              </div>
              <div class="col-md-4 mb-3">
                <label class="form-label">Status</label>
                <select v-model="form.status" class="form-select">
                  <option value="pending">Pending</option>
                  <option value="in_progress">In Progress</option>
                  <option value="completed">Completed</option>
                  <option value="cancelled">Cancelled</option>
                </select>
              </div>
            </div>
            <div class="row">
              <div class="col-md-6 mb-3">
                <label class="form-label">Start Date</label>
                <input v-model="form.start_date" type="date" class="form-control" />
              </div>
              <div class="col-md-6 mb-3">
                <label class="form-label">Completion Date</label>
                <input v-model="form.completion_date" type="date" class="form-control" />
              </div>
            </div>
            <div class="mb-3">
              <label class="form-label">Checklist (one item per line, prefix [x] when done)</label>
              <textarea
                v-model="formChecklistText"
                rows="4"
                class="form-control"
                placeholder="[ ] Collect documents&#10;[x] Issue ID card"
              ></textarea>
            </div>
            <div class="mb-2">
              <label class="form-label">Notes</label>
              <textarea v-model="form.notes" rows="3" class="form-control"></textarea>
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
import { ref, onMounted, watch } from 'vue';
import api from '../services/api';

const items = ref([]);
const loading = ref(false);
const saving = ref(false);
const savingId = ref(null);
const filters = ref({
  status: ''
});

const formModal = ref(null);
const form = ref({
  id: null,
  candidate_id: null,
  user_id: null,
  start_date: '',
  completion_date: '',
  status: 'pending',
  checklist: [],
  notes: ''
});
const formChecklistText = ref('');
const formError = ref('');

watch(
  () => formChecklistText.value,
  (value) => {
    const lines = value.split('\n').map((line) => line.trim()).filter(Boolean);
    form.value.checklist = lines.map((line) => {
      const done = line.startsWith('[x]') || line.startsWith('[X]');
      const label = line.replace(/^\[(x|X| )\]\s*/i, '');
      return { label, done };
    });
  }
);

const statusClass = (status) => {
  const map = {
    pending: 'bg-secondary',
    in_progress: 'bg-info',
    completed: 'bg-success',
    cancelled: 'bg-danger'
  };
  return map[status] || 'bg-light';
};

const formatDate = (value) => {
  if (!value) return '';
  try {
    return new Date(value).toLocaleDateString();
  } catch {
    return value;
  }
};

const completedCount = (checklist) => {
  if (!Array.isArray(checklist)) return 0;
  return checklist.filter((item) => item && item.done).length;
};

const fetchItems = async () => {
  loading.value = true;
  try {
    const params = {};
    if (filters.value.status) params.status = filters.value.status;
    const response = await api.get('/onboardings', { params });
    const payload = response.data;
    items.value = Array.isArray(payload.data) ? payload.data : [];
  } catch (e) {
    console.error(e);
  } finally {
    loading.value = false;
  }
};

const resetFilters = () => {
  filters.value = { status: '' };
  fetchItems();
};

const openForm = (item = null) => {
  formError.value = '';
  if (item) {
    form.value = {
      id: item.id,
      candidate_id: item.candidate_id || null,
      user_id: item.user_id || null,
      start_date: item.start_date ? item.start_date.substring(0, 10) : '',
      completion_date: item.completion_date ? item.completion_date.substring(0, 10) : '',
      status: item.status || 'pending',
      checklist: Array.isArray(item.checklist) ? item.checklist : [],
      notes: item.notes || ''
    };
  } else {
    form.value = {
      id: null,
      candidate_id: null,
      user_id: null,
      start_date: '',
      completion_date: '',
      status: 'pending',
      checklist: [],
      notes: ''
    };
  }

  formChecklistText.value = Array.isArray(form.value.checklist)
    ? form.value.checklist
        .map((item) => {
          if (!item) return '';
          const prefix = item.done ? '[x] ' : '[ ] ';
          return prefix + (item.label || '');
        })
        .filter(Boolean)
        .join('\n')
    : '';

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
      candidate_id: form.value.candidate_id || null,
      user_id: form.value.user_id || null,
      start_date: form.value.start_date || null,
      completion_date: form.value.completion_date || null,
      status: form.value.status,
      checklist: form.value.checklist,
      notes: form.value.notes || null
    };
    if (form.value.id) {
      savingId.value = form.value.id;
      await api.put(`/onboardings/${form.value.id}`, payload);
    } else {
      await api.post('/onboardings', payload);
    }
    closeForm();
    await fetchItems();
  } catch (e) {
    const message = e?.response?.data?.message;
    formError.value = typeof message === 'string' ? message : 'Failed to save onboarding';
  } finally {
    saving.value = false;
    savingId.value = null;
  }
};

const deleteItem = async (item) => {
  if (!window.confirm('Delete this onboarding record?')) return;
  savingId.value = item.id;
  try {
    await api.delete(`/onboardings/${item.id}`);
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

