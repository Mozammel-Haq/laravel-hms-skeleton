<template>
  <div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
      <h4 class="mb-0">Candidates</h4>
      <button class="btn btn-primary btn-sm" @click="openForm()">
        <i class="ti ti-plus me-1"></i>
        New Candidate
      </button>
    </div>

    <div class="card border-0 shadow-sm mb-3">
      <div class="card-body">
        <div class="row g-3 align-items-end">
          <div class="col-md-3">
            <label class="form-label">Status</label>
            <select v-model="filters.status" class="form-select">
              <option value="">All</option>
              <option value="new">New</option>
              <option value="screening">Screening</option>
              <option value="interview">Interview</option>
              <option value="offered">Offered</option>
              <option value="hired">Hired</option>
              <option value="rejected">Rejected</option>
              <option value="archived">Archived</option>
            </select>
          </div>
          <div class="col-md-3">
            <label class="form-label">Job Post ID</label>
            <input v-model="filters.job_post_id" type="number" class="form-control" />
          </div>
          <div class="col-md-3">
            <label class="form-label">Search</label>
            <input
              v-model="filters.search"
              type="text"
              class="form-control"
              placeholder="Name, email, phone"
            />
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
                <th>Contact</th>
                <th>Job Post</th>
                <th>Source</th>
                <th>Status</th>
                <th>Created</th>
                <th class="text-end">Actions</th>
              </tr>
            </thead>
            <tbody>
              <tr v-if="loading">
                <td colspan="7" class="text-center py-4">
                  <div class="spinner-border spinner-border-sm text-primary me-2" role="status"></div>
                  Loading candidates...
                </td>
              </tr>
              <tr v-else-if="items.length === 0">
                <td colspan="7" class="text-center py-4 text-muted">
                  No candidates found.
                </td>
              </tr>
              <tr v-for="item in items" :key="item.id">
                <td>
                  <div class="fw-semibold">{{ item.name }}</div>
                  <div class="text-muted small">
                    Candidate #{{ item.id }}
                  </div>
                </td>
                <td>
                  <div class="small">
                    <div v-if="item.email">{{ item.email }}</div>
                    <div v-if="item.phone">{{ item.phone }}</div>
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
                <td>{{ item.source || '-' }}</td>
                <td>
                  <span class="badge" :class="statusClass(item.status)">
                    {{ item.status }}
                  </span>
                </td>
                <td class="small">{{ formatDateTime(item.created_at) }}</td>
                <td class="text-end">
                  <a
                    v-if="item.resume_url"
                    :href="item.resume_url"
                    target="_blank"
                    rel="noopener"
                    class="btn btn-link btn-sm text-secondary me-1"
                  >
                    <i class="ti ti-file-text"></i>
                  </a>
                  <button class="btn btn-link btn-sm text-primary me-2" @click="openForm(item)">
                    <i class="ti ti-edit"></i>
                  </button>
                  <button
                    class="btn btn-link btn-sm text-danger"
                    :disabled="item.status === 'archived' || savingId === item.id"
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
      <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">{{ form.id ? 'Edit Candidate' : 'New Candidate' }}</h5>
            <button type="button" class="btn-close" @click="closeForm"></button>
          </div>
          <div class="modal-body">
            <div v-if="formError" class="alert alert-danger py-2 mb-3">
              {{ formError }}
            </div>
            <div class="row">
              <div class="col-md-6 mb-3">
                <label class="form-label">Name</label>
                <input v-model="form.name" type="text" class="form-control" />
              </div>
              <div class="col-md-3 mb-3">
                <label class="form-label">Email</label>
                <input v-model="form.email" type="email" class="form-control" />
              </div>
              <div class="col-md-3 mb-3">
                <label class="form-label">Phone</label>
                <input v-model="form.phone" type="text" class="form-control" />
              </div>
            </div>
            <div class="row">
              <div class="col-md-4 mb-3">
                <label class="form-label">Job Post ID</label>
                <input v-model.number="form.job_post_id" type="number" class="form-control" />
              </div>
              <div class="col-md-4 mb-3">
                <label class="form-label">Source</label>
                <input v-model="form.source" type="text" class="form-control" />
              </div>
              <div class="col-md-4 mb-3">
                <label class="form-label">Status</label>
                <select v-model="form.status" class="form-select">
                  <option value="new">New</option>
                  <option value="screening">Screening</option>
                  <option value="interview">Interview</option>
                  <option value="offered">Offered</option>
                  <option value="hired">Hired</option>
                  <option value="rejected">Rejected</option>
                  <option value="archived">Archived</option>
                </select>
              </div>
            </div>
            <div class="mb-3">
              <label class="form-label">Resume URL</label>
              <input v-model="form.resume_url" type="text" class="form-control" />
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
  status: '',
  job_post_id: '',
  search: ''
});

const formModal = ref(null);
const form = ref({
  id: null,
  job_post_id: null,
  name: '',
  email: '',
  phone: '',
  source: '',
  resume_url: '',
  notes: '',
  status: 'new'
});
const formError = ref('');

const statusClass = (status) => {
  const map = {
    new: 'bg-secondary',
    screening: 'bg-info',
    interview: 'bg-primary',
    offered: 'bg-warning',
    hired: 'bg-success',
    rejected: 'bg-danger',
    archived: 'bg-dark'
  };
  return map[status] || 'bg-light';
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
    if (filters.value.status) params.status = filters.value.status;
    if (filters.value.job_post_id) params.job_post_id = filters.value.job_post_id;
    if (filters.value.search) params.search = filters.value.search;
    const response = await api.get('/candidates', { params });
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
    status: '',
    job_post_id: '',
    search: ''
  };
  fetchItems();
};

const openForm = (item = null) => {
  formError.value = '';
  if (item) {
    form.value = {
      id: item.id,
      job_post_id: item.job_post_id || null,
      name: item.name || '',
      email: item.email || '',
      phone: item.phone || '',
      source: item.source || '',
      resume_url: item.resume_url || '',
      notes: item.notes || '',
      status: item.status || 'new'
    };
  } else {
    form.value = {
      id: null,
      job_post_id: null,
      name: '',
      email: '',
      phone: '',
      source: '',
      resume_url: '',
      notes: '',
      status: 'new'
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
      job_post_id: form.value.job_post_id || null,
      name: form.value.name,
      email: form.value.email || null,
      phone: form.value.phone || null,
      source: form.value.source || null,
      resume_url: form.value.resume_url || null,
      notes: form.value.notes || null,
      status: form.value.status
    };
    if (form.value.id) {
      savingId.value = form.value.id;
      await api.put(`/candidates/${form.value.id}`, payload);
    } else {
      await api.post('/candidates', payload);
    }
    closeForm();
    await fetchItems();
  } catch (e) {
    const message = e?.response?.data?.message;
    formError.value = typeof message === 'string' ? message : 'Failed to save candidate';
  } finally {
    saving.value = false;
    savingId.value = null;
  }
};

const archiveItem = async (item) => {
  if (!window.confirm('Archive this candidate?')) return;
  savingId.value = item.id;
  try {
    await api.delete(`/candidates/${item.id}`);
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

