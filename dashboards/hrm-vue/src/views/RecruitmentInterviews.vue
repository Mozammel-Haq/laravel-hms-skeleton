<template>
  <div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
      <h4 class="mb-0">Interviews</h4>
      <button class="btn btn-primary btn-sm" @click="openForm()">
        <i class="ti ti-plus me-1"></i>
        Schedule Interview
      </button>
    </div>

    <div class="card border-0 shadow-sm mb-3">
      <div class="card-body">
        <div class="row g-3 align-items-end">
          <div class="col-md-3">
            <label class="form-label">Result</label>
            <select v-model="filters.result" class="form-select">
              <option value="">All</option>
              <option value="pending">Pending</option>
              <option value="shortlisted">Shortlisted</option>
              <option value="rejected">Rejected</option>
              <option value="on_hold">On Hold</option>
            </select>
          </div>
          <div class="col-md-3">
            <label class="form-label">From</label>
            <input v-model="filters.from_date" type="date" class="form-control" />
          </div>
          <div class="col-md-3">
            <label class="form-label">To</label>
            <input v-model="filters.to_date" type="date" class="form-control" />
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
                <th>Job</th>
                <th>Schedule</th>
                <th>Mode</th>
                <th>Interviewer</th>
                <th>Result</th>
                <th class="text-end">Actions</th>
              </tr>
            </thead>
            <tbody>
              <tr v-if="loading">
                <td colspan="7" class="text-center py-4">
                  <div class="spinner-border spinner-border-sm text-primary me-2" role="status"></div>
                  Loading interviews...
                </td>
              </tr>
              <tr v-else-if="items.length === 0">
                <td colspan="7" class="text-center py-4 text-muted">
                  No interviews scheduled.
                </td>
              </tr>
              <tr v-for="item in items" :key="item.id">
                <td>
                  <div class="fw-semibold">
                    {{ item.candidate?.name || 'Candidate #'+item.candidate_id }}
                  </div>
                  <div class="text-muted small">
                    #{{ item.candidate_id }}
                  </div>
                </td>
                <td>
                  <div v-if="item.job_post">
                    {{ item.job_post.title }}
                  </div>
                  <div v-else class="text-muted small">
                    #{{ item.job_post_id || '-' }}
                  </div>
                </td>
                <td>
                  <div class="small">{{ formatDateTime(item.scheduled_at) }}</div>
                  <div class="text-muted small" v-if="item.location">
                    {{ item.location }}
                  </div>
                </td>
                <td>{{ formatMode(item.mode) }}</td>
                <td>
                  <div class="small">
                    <div v-if="item.interviewer_name">{{ item.interviewer_name }}</div>
                    <div v-if="item.interviewer_user_id" class="text-muted">
                      User #{{ item.interviewer_user_id }}
                    </div>
                  </div>
                </td>
                <td>
                  <span class="badge" :class="resultClass(item.result)">
                    {{ item.result }}
                  </span>
                </td>
                <td class="text-end">
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
                          @click.prevent="() => { closeRowMenu(); deleteItem(item); }"
                          :class="{ disabled: savingId === item.id }"
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
            <h5 class="modal-title">{{ form.id ? 'Edit Interview' : 'Schedule Interview' }}</h5>
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
                <label class="form-label">Job Post ID</label>
                <input v-model.number="form.job_post_id" type="number" class="form-control" />
              </div>
              <div class="col-md-4 mb-3">
                <label class="form-label">Scheduled At</label>
                <input v-model="form.scheduled_at" type="datetime-local" class="form-control" />
              </div>
            </div>
            <div class="row">
              <div class="col-md-4 mb-3">
                <label class="form-label">Mode</label>
                <select v-model="form.mode" class="form-select">
                  <option value="in_person">In Person</option>
                  <option value="video">Video</option>
                  <option value="phone">Phone</option>
                </select>
              </div>
              <div class="col-md-4 mb-3">
                <label class="form-label">Location</label>
                <input v-model="form.location" type="text" class="form-control" />
              </div>
              <div class="col-md-4 mb-3">
                <label class="form-label">Interviewer</label>
                <input v-model="form.interviewer_name" type="text" class="form-control" />
              </div>
            </div>
            <div class="row">
              <div class="col-md-4 mb-3">
                <label class="form-label">Interviewer User ID</label>
                <input v-model.number="form.interviewer_user_id" type="number" class="form-control" />
              </div>
              <div class="col-md-4 mb-3">
                <label class="form-label">Result</label>
                <select v-model="form.result" class="form-select">
                  <option value="pending">Pending</option>
                  <option value="shortlisted">Shortlisted</option>
                  <option value="rejected">Rejected</option>
                  <option value="on_hold">On Hold</option>
                </select>
              </div>
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
import { ref, onMounted } from 'vue';
import api from '../services/api';

const items = ref([]);
const loading = ref(false);
const saving = ref(false);
const savingId = ref(null);
const filters = ref({
  result: '',
  from_date: '',
  to_date: ''
});

const formModal = ref(null);
const form = ref({
  id: null,
  candidate_id: null,
  job_post_id: null,
  scheduled_at: '',
  mode: 'in_person',
  location: '',
  interviewer_name: '',
  interviewer_user_id: null,
  result: 'pending',
  notes: ''
});
const formError = ref('');

const openMenuId = ref(null);

const formatMode = (mode) => {
  if (!mode) return '-';
  return mode.replace('_', ' ').replace(/\b\w/g, (c) => c.toUpperCase());
};

const resultClass = (result) => {
  const map = {
    pending: 'bg-secondary',
    shortlisted: 'bg-success',
    rejected: 'bg-danger',
    on_hold: 'bg-warning'
  };
  return map[result] || 'bg-light';
};

const toggleRowMenu = (id) => {
  openMenuId.value = openMenuId.value === id ? null : id;
};

const closeRowMenu = () => {
  openMenuId.value = null;
};

const formatDateTime = (value) => {
  if (!value) return '';
  try {
    return new Date(value).toLocaleString();
  } catch {
    return value;
  }
};

const fetchItems = async () => {
  loading.value = true;
  try {
    const params = {};
    if (filters.value.result) params.result = filters.value.result;
    if (filters.value.from_date) params.from_date = filters.value.from_date;
    if (filters.value.to_date) params.to_date = filters.value.to_date;
    const response = await api.get('/interviews', { params });
    const payload = response.data;
    items.value = Array.isArray(payload.data) ? payload.data : [];
  } catch (e) {
    console.error(e);
  } finally {
    loading.value = false;
  }
};

const resetFilters = () => {
  filters.value = {
    result: '',
    from_date: '',
    to_date: ''
  };
  fetchItems();
};

const openForm = (item = null) => {
  formError.value = '';
  if (item) {
    const dt = item.scheduled_at ? item.scheduled_at.substring(0, 16) : '';
    form.value = {
      id: item.id,
      candidate_id: item.candidate_id,
      job_post_id: item.job_post_id,
      scheduled_at: dt,
      mode: item.mode || 'in_person',
      location: item.location || '',
      interviewer_name: item.interviewer_name || '',
      interviewer_user_id: item.interviewer_user_id || null,
      result: item.result || 'pending',
      notes: item.notes || ''
    };
  } else {
    form.value = {
      id: null,
      candidate_id: null,
      job_post_id: null,
      scheduled_at: '',
      mode: 'in_person',
      location: '',
      interviewer_name: '',
      interviewer_user_id: null,
      result: 'pending',
      notes: ''
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
      candidate_id: form.value.candidate_id,
      job_post_id: form.value.job_post_id || null,
      scheduled_at: form.value.scheduled_at,
      mode: form.value.mode,
      location: form.value.location || null,
      interviewer_name: form.value.interviewer_name || null,
      interviewer_user_id: form.value.interviewer_user_id || null,
      result: form.value.result,
      notes: form.value.notes || null
    };
    if (form.value.id) {
      savingId.value = form.value.id;
      await api.put(`/interviews/${form.value.id}`, payload);
    } else {
      await api.post('/interviews', payload);
    }
    closeForm();
    await fetchItems();
  } catch (e) {
    const message = e?.response?.data?.message;
    formError.value = typeof message === 'string' ? message : 'Failed to save interview';
  } finally {
    saving.value = false;
    savingId.value = null;
  }
};

const deleteItem = async (item) => {
  if (!window.confirm('Delete this interview?')) return;
  savingId.value = item.id;
  try {
    await api.delete(`/interviews/${item.id}`);
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
