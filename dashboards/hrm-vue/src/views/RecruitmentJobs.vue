<template>
  <div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
      <h4 class="mb-0">Job Posts</h4>
      <button class="btn btn-primary btn-sm" @click="openForm()">
        <i class="ti ti-plus me-1"></i>
        New Job Post
      </button>
    </div>

    <div class="card border-0 shadow-sm mb-3">
      <div class="card-body">
        <div class="row g-3 align-items-end">
          <div class="col-md-3">
            <label class="form-label">Status</label>
            <select v-model="filters.status" class="form-select">
              <option value="">All</option>
              <option value="draft">Draft</option>
              <option value="open">Open</option>
              <option value="closed">Closed</option>
              <option value="archived">Archived</option>
            </select>
          </div>
          <div class="col-md-3">
            <label class="form-label">Department ID</label>
            <input v-model="filters.department_id" type="number" class="form-control" />
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
                <th>Title</th>
                <th>Department</th>
                <th>Type</th>
                <th>Openings</th>
                <th>Status</th>
                <th>Dates</th>
                <th class="text-end">Actions</th>
              </tr>
            </thead>
            <tbody>
              <tr v-if="loading">
                <td colspan="7" class="text-center py-4">
                  <div class="spinner-border spinner-border-sm text-primary me-2" role="status"></div>
                  Loading job posts...
                </td>
              </tr>
              <tr v-else-if="items.length === 0">
                <td colspan="7" class="text-center py-4 text-muted">
                  No job posts found.
                </td>
              </tr>
              <tr v-for="item in items" :key="item.id">
                <td>
                  <div class="fw-semibold">{{ item.title }}</div>
                  <div class="text-muted small">
                    {{ item.location || 'Location not set' }}
                  </div>
                </td>
                <td>#{{ item.department_id || '-' }}</td>
                <td>{{ formatType(item.employment_type) }}</td>
                <td>{{ item.openings }}</td>
                <td>
                  <span class="badge" :class="statusClass(item.status)">
                    {{ item.status }}
                  </span>
                </td>
                <td>
                  <div class="small">
                    <div v-if="item.posted_at">Posted: {{ formatDate(item.posted_at) }}</div>
                    <div v-if="item.closes_at">Closes: {{ formatDate(item.closes_at) }}</div>
                  </div>
                </td>
                <td class="text-end">
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
            <h5 class="modal-title">{{ form.id ? 'Edit Job Post' : 'New Job Post' }}</h5>
            <button type="button" class="btn-close" @click="closeForm"></button>
          </div>
          <div class="modal-body">
            <div v-if="formError" class="alert alert-danger py-2 mb-3">
              {{ formError }}
            </div>
            <div class="row">
              <div class="col-md-8 mb-3">
                <label class="form-label">Title</label>
                <input v-model="form.title" type="text" class="form-control" />
              </div>
              <div class="col-md-4 mb-3">
                <label class="form-label">Department ID</label>
                <input v-model.number="form.department_id" type="number" class="form-control" />
              </div>
            </div>
            <div class="row">
              <div class="col-md-4 mb-3">
                <label class="form-label">Employment Type</label>
                <select v-model="form.employment_type" class="form-select">
                  <option value="full_time">Full Time</option>
                  <option value="part_time">Part Time</option>
                  <option value="contract">Contract</option>
                  <option value="locum">Locum</option>
                  <option value="internship">Internship</option>
                </select>
              </div>
              <div class="col-md-4 mb-3">
                <label class="form-label">Location</label>
                <input v-model="form.location" type="text" class="form-control" />
              </div>
              <div class="col-md-4 mb-3">
                <label class="form-label">Openings</label>
                <input v-model.number="form.openings" type="number" min="1" class="form-control" />
              </div>
            </div>
            <div class="row">
              <div class="col-md-6 mb-3">
                <label class="form-label">Posted At</label>
                <input v-model="form.posted_at" type="date" class="form-control" />
              </div>
              <div class="col-md-6 mb-3">
                <label class="form-label">Closes At</label>
                <input v-model="form.closes_at" type="date" class="form-control" />
              </div>
            </div>
            <div class="mb-3">
              <label class="form-label">Status</label>
              <select v-model="form.status" class="form-select">
                <option value="draft">Draft</option>
                <option value="open">Open</option>
                <option value="closed">Closed</option>
                <option value="archived">Archived</option>
              </select>
            </div>
            <div class="mb-3">
              <label class="form-label">Description</label>
              <textarea v-model="form.description" rows="3" class="form-control"></textarea>
            </div>
            <div class="mb-2">
              <label class="form-label">Requirements</label>
              <textarea v-model="form.requirements" rows="3" class="form-control"></textarea>
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
  status: 'open',
  department_id: ''
});

const formModal = ref(null);
const form = ref({
  id: null,
  title: '',
  department_id: null,
  employment_type: 'full_time',
  location: '',
  openings: 1,
  status: 'draft',
  posted_at: '',
  closes_at: '',
  description: '',
  requirements: ''
});
const formError = ref('');

const formatType = (type) => {
  if (!type) return '-';
  return type.replace('_', ' ').replace(/\b\w/g, (c) => c.toUpperCase());
};

const statusClass = (status) => {
  if (status === 'open') return 'bg-success';
  if (status === 'draft') return 'bg-secondary';
  if (status === 'closed') return 'bg-warning';
  if (status === 'archived') return 'bg-dark';
  return 'bg-light';
};

const formatDate = (value) => {
  if (!value) return '';
  try {
    return new Date(value).toLocaleDateString();
  } catch {
    return value;
  }
};

const fetchItems = async () => {
  loading.value = true;
  try {
    const params = {};
    if (filters.value.status) params.status = filters.value.status;
    if (filters.value.department_id) params.department_id = filters.value.department_id;
    const response = await api.get('/job-posts', { params });
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
    status: 'open',
    department_id: ''
  };
  fetchItems();
};

const openForm = (item = null) => {
  formError.value = '';
  if (item) {
    form.value = {
      id: item.id,
      title: item.title || '',
      department_id: item.department_id || null,
      employment_type: item.employment_type || 'full_time',
      location: item.location || '',
      openings: item.openings ?? 1,
      status: item.status || 'draft',
      posted_at: item.posted_at ? item.posted_at.substring(0, 10) : '',
      closes_at: item.closes_at ? item.closes_at.substring(0, 10) : '',
      description: item.description || '',
      requirements: item.requirements || ''
    };
  } else {
    form.value = {
      id: null,
      title: '',
      department_id: null,
      employment_type: 'full_time',
      location: '',
      openings: 1,
      status: 'draft',
      posted_at: '',
      closes_at: '',
      description: '',
      requirements: ''
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
      title: form.value.title,
      department_id: form.value.department_id || null,
      employment_type: form.value.employment_type,
      location: form.value.location || null,
      openings: form.value.openings,
      status: form.value.status,
      posted_at: form.value.posted_at || null,
      closes_at: form.value.closes_at || null,
      description: form.value.description || null,
      requirements: form.value.requirements || null
    };
    if (form.value.id) {
      savingId.value = form.value.id;
      await api.put(`/job-posts/${form.value.id}`, payload);
    } else {
      await api.post('/job-posts', payload);
    }
    closeForm();
    await fetchItems();
  } catch (e) {
    const message = e?.response?.data?.message;
    formError.value = typeof message === 'string' ? message : 'Failed to save job post';
  } finally {
    saving.value = false;
    savingId.value = null;
  }
};

const archiveItem = async (item) => {
  if (!window.confirm('Archive this job post?')) return;
  savingId.value = item.id;
  try {
    await api.delete(`/job-posts/${item.id}`);
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

