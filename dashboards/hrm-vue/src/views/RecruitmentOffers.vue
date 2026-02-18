<template>
  <div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
      <h4 class="mb-0">Job Offers</h4>
      <button class="btn btn-primary btn-sm" @click="openForm()">
        <i class="ti ti-plus me-1"></i>
        New Offer
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
              <option value="sent">Sent</option>
              <option value="accepted">Accepted</option>
              <option value="rejected">Rejected</option>
              <option value="withdrawn">Withdrawn</option>
            </select>
          </div>
          <div class="col-md-3">
            <label class="form-label">Candidate ID</label>
            <input v-model="filters.candidate_id" type="number" class="form-control" />
          </div>
          <div class="col-md-3">
            <label class="form-label">Job Post ID</label>
            <input v-model="filters.job_post_id" type="number" class="form-control" />
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
                <th>Role</th>
                <th>Job Post</th>
                <th>Salary</th>
                <th>Joining</th>
                <th>Status</th>
                <th class="text-end">Actions</th>
              </tr>
            </thead>
            <tbody>
              <tr v-if="loading">
                <td colspan="7" class="text-center py-4">
                  <div class="spinner-border spinner-border-sm text-primary me-2" role="status"></div>
                  Loading offers...
                </td>
              </tr>
              <tr v-else-if="items.length === 0">
                <td colspan="7" class="text-center py-4 text-muted">
                  No offers found.
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
                <td>{{ item.offered_role }}</td>
                <td>
                  <div v-if="item.job_post">
                    {{ item.job_post.title }}
                  </div>
                  <div v-else class="text-muted small">
                    #{{ item.job_post_id || '-' }}
                  </div>
                </td>
                <td>
                  {{ formatCurrency(item.salary_offered) }}
                </td>
                <td>
                  <div class="small">
                    {{ formatDate(item.joining_date) || '-' }}
                  </div>
                </td>
                <td>
                  <span class="badge" :class="statusClass(item.status)">
                    {{ item.status }}
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
            <h5 class="modal-title">{{ form.id ? 'Edit Offer' : 'New Offer' }}</h5>
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
                <label class="form-label">Offered Role</label>
                <input v-model="form.offered_role" type="text" class="form-control" />
              </div>
            </div>
            <div class="row">
              <div class="col-md-4 mb-3">
                <label class="form-label">Salary Offered</label>
                <input v-model.number="form.salary_offered" type="number" class="form-control" />
              </div>
              <div class="col-md-4 mb-3">
                <label class="form-label">Joining Date</label>
                <input v-model="form.joining_date" type="date" class="form-control" />
              </div>
              <div class="col-md-4 mb-3">
                <label class="form-label">Status</label>
                <select v-model="form.status" class="form-select">
                  <option value="draft">Draft</option>
                  <option value="sent">Sent</option>
                  <option value="accepted">Accepted</option>
                  <option value="rejected">Rejected</option>
                  <option value="withdrawn">Withdrawn</option>
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
  status: '',
  candidate_id: '',
  job_post_id: ''
});

const formModal = ref(null);
const form = ref({
  id: null,
  candidate_id: null,
  job_post_id: null,
  offered_role: '',
  salary_offered: 0,
  joining_date: '',
  status: 'draft',
  notes: ''
});
const formError = ref('');

const openMenuId = ref(null);

const statusClass = (status) => {
  const map = {
    draft: 'bg-secondary',
    sent: 'bg-info',
    accepted: 'bg-success',
    rejected: 'bg-danger',
    withdrawn: 'bg-dark'
  };
  return map[status] || 'bg-light';
};

const toggleRowMenu = (id) => {
  openMenuId.value = openMenuId.value === id ? null : id;
};

const closeRowMenu = () => {
  openMenuId.value = null;
};

const formatCurrency = (value) => {
  if (!value) return '0';
  try {
    return Number(value).toLocaleString(undefined, {
      maximumFractionDigits: 2
    });
  } catch {
    return value;
  }
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
    if (filters.value.candidate_id) params.candidate_id = filters.value.candidate_id;
    if (filters.value.job_post_id) params.job_post_id = filters.value.job_post_id;
    const response = await api.get('/job-offers', { params });
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
    candidate_id: '',
    job_post_id: ''
  };
  fetchItems();
};

const openForm = (item = null) => {
  formError.value = '';
  if (item) {
    form.value = {
      id: item.id,
      candidate_id: item.candidate_id,
      job_post_id: item.job_post_id || null,
      offered_role: item.offered_role || '',
      salary_offered: item.salary_offered ?? 0,
      joining_date: item.joining_date ? item.joining_date.substring(0, 10) : '',
      status: item.status || 'draft',
      notes: item.notes || ''
    };
  } else {
    form.value = {
      id: null,
      candidate_id: null,
      job_post_id: null,
      offered_role: '',
      salary_offered: 0,
      joining_date: '',
      status: 'draft',
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
      offered_role: form.value.offered_role,
      salary_offered: form.value.salary_offered,
      joining_date: form.value.joining_date || null,
      status: form.value.status,
      notes: form.value.notes || null
    };
    if (form.value.id) {
      savingId.value = form.value.id;
      await api.put(`/job-offers/${form.value.id}`, payload);
    } else {
      await api.post('/job-offers', payload);
    }
    closeForm();
    await fetchItems();
  } catch (e) {
    const message = e?.response?.data?.message;
    formError.value = typeof message === 'string' ? message : 'Failed to save offer';
  } finally {
    saving.value = false;
    savingId.value = null;
  }
};

const deleteItem = async (item) => {
  if (!window.confirm('Delete this offer?')) return;
  savingId.value = item.id;
  try {
    await api.delete(`/job-offers/${item.id}`);
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
