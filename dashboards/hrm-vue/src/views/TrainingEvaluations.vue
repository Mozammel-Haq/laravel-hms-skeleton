<template>
  <div class="container-fluid py-4">
    <div class="card p-3 mb-3 border-0 shadow-sm">
      <div
        class="d-flex justify-content-between align-items-center bg-primary-subtle text-primary px-4 pt-3 pb-3 rounded-3 mb-0"
      >
        <div>
          <h5 class="fw-bold mb-1 text-primary">Training Evaluations</h5>
          <nav aria-label="breadcrumb">
            <ol class="breadcrumb breadcrumb-dots mb-0 text-muted small">
              <li class="breadcrumb-item">
                <router-link to="/">Dashboard</router-link>
              </li>
              <li class="breadcrumb-item">Training</li>
              <li class="breadcrumb-item active" aria-current="page">Evaluations</li>
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
            <i class="ti ti-plus me-2"></i>Record Evaluation
          </button>
        </div>
      </div>
    </div>

    <div class="card border-0 shadow-sm mb-3">
      <div class="card-body">
        <div class="row g-3 align-items-end">
          <div class="col-md-4">
            <label class="form-label">Session</label>
            <select v-model="filters.session_id" class="form-select">
              <option value="">All</option>
              <option v-for="session in sessions" :key="session.id" :value="session.id">
                {{ formatSessionLabel(session) }}
              </option>
            </select>
          </div>
          <div class="col-md-3">
            <label class="form-label">User ID</label>
            <input v-model="filters.user_id" type="number" class="form-control" />
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
          <table class="table table-sm table-hover align-middle mb-0">
            <thead class="table-light">
              <tr>
                <th>Session</th>
                <th>User</th>
                <th>Rating</th>
                <th>Feedback</th>
                <th>Completed At</th>
                <th class="text-end pe-4">Actions</th>
              </tr>
            </thead>
            <tbody>
              <tr v-if="loading">
                <td colspan="6" class="text-center py-4">
                  <div class="spinner-border spinner-border-sm text-primary me-2" role="status"></div>
                  Loading evaluations...
                </td>
              </tr>
              <tr v-else-if="items.length === 0">
                <td colspan="6" class="text-center py-4 text-muted">
                  No evaluations found.
                </td>
              </tr>
              <tr v-for="item in items" :key="item.id">
                <td>
                  <div class="fw-semibold">
                    {{ item.session?.course?.title || 'Course #' + item.session_id }}
                  </div>
                  <div class="text-muted small">
                    Session #{{ item.session_id }}
                  </div>
                </td>
                <td>#{{ item.user_id }}</td>
                <td>
                  <span v-if="item.rating" class="badge bg-success">
                    {{ item.rating }}/5
                  </span>
                  <span v-else class="text-muted">Not rated</span>
                </td>
                <td class="small">
                  {{ item.feedback || '-' }}
                </td>
                <td>
                  {{ item.completed_at ? formatDate(item.completed_at) : '-' }}
                </td>
                <td class="text-end pe-4">
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
            <h5 class="modal-title">{{ form.id ? 'Edit Evaluation' : 'Record Evaluation' }}</h5>
            <button type="button" class="btn-close" @click="closeForm"></button>
          </div>
          <div class="modal-body">
            <div v-if="formError" class="alert alert-danger py-2 mb-3">
              {{ formError }}
            </div>
            <div class="row">
              <div class="col-md-6 mb-3">
                <label class="form-label">Session</label>
                <select v-model.number="form.session_id" class="form-select">
                  <option disabled value="">Select session</option>
                  <option v-for="session in sessions" :key="session.id" :value="session.id">
                    {{ formatSessionLabel(session) }}
                  </option>
                </select>
              </div>
              <div class="col-md-6 mb-3">
                <label class="form-label">User ID</label>
                <input v-model.number="form.user_id" type="number" class="form-control" />
              </div>
            </div>
            <div class="row">
              <div class="col-md-4 mb-3">
                <label class="form-label">Rating</label>
                <select v-model.number="form.rating" class="form-select">
                  <option :value="null">Not rated</option>
                  <option v-for="value in [1,2,3,4,5]" :key="value" :value="value">
                    {{ value }}
                  </option>
                </select>
              </div>
              <div class="col-md-4 mb-3">
                <label class="form-label">Completed At</label>
                <input v-model="form.completed_at" type="date" class="form-control" />
              </div>
            </div>
            <div class="mb-2">
              <label class="form-label">Feedback</label>
              <textarea v-model="form.feedback" rows="3" class="form-control"></textarea>
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
const sessions = ref([]);
const loading = ref(false);
const saving = ref(false);
const savingId = ref(null);
const filters = ref({
  session_id: '',
  user_id: ''
});

const formModal = ref(null);
const form = ref({
  id: null,
  session_id: '',
  user_id: null,
  rating: null,
  feedback: '',
  completed_at: ''
});
const formError = ref('');

const openMenuId = ref(null);

const formatDate = (value) => {
  if (!value) return '';
  try {
    return new Date(value).toLocaleDateString();
  } catch {
    return value;
  }
};

const formatSessionLabel = (session) => {
  const title = session.course?.title || 'Course #' + session.course_id;
  const date = session.start_date ? formatDate(session.start_date) : '';
  return date ? `${title} (${date})` : title;
};

const toggleRowMenu = (id) => {
  openMenuId.value = openMenuId.value === id ? null : id;
};

const closeRowMenu = () => {
  openMenuId.value = null;
};

const fetchSessions = async () => {
  try {
    const response = await api.get('/training-sessions');
    const payload = response.data;
    sessions.value = Array.isArray(payload.data) ? payload.data : [];
  } catch (e) {
    console.error(e);
  }
};

const fetchItems = async () => {
  loading.value = true;
  try {
    const params = {};
    if (filters.value.session_id) params.session_id = filters.value.session_id;
    if (filters.value.user_id) params.user_id = filters.value.user_id;
    const response = await api.get('/training-evaluations', { params });
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
    session_id: '',
    user_id: ''
  };
  fetchItems();
};

const openForm = (item = null) => {
  formError.value = '';
  if (item) {
    form.value = {
      id: item.id,
      session_id: item.session_id,
      user_id: item.user_id,
      rating: item.rating ?? null,
      feedback: item.feedback || '',
      completed_at: item.completed_at ? item.completed_at.substring(0, 10) : ''
    };
  } else {
    form.value = {
      id: null,
      session_id: '',
      user_id: null,
      rating: null,
      feedback: '',
      completed_at: ''
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
      session_id: form.value.session_id,
      user_id: form.value.user_id,
      rating: form.value.rating ?? null,
      feedback: form.value.feedback || null,
      completed_at: form.value.completed_at || null
    };
    await api.post('/training-evaluations', payload);
    closeForm();
    await fetchItems();
  } catch (e) {
    const message = e?.response?.data?.message;
    formError.value = typeof message === 'string' ? message : 'Failed to save evaluation';
  } finally {
    saving.value = false;
    savingId.value = null;
  }
};

const deleteItem = async (item) => {
  if (!window.confirm('Delete this evaluation?')) return;
  savingId.value = item.id;
  try {
    await api.delete(`/training-evaluations/${item.id}`);
    await fetchItems();
  } catch (e) {
    console.error(e);
  } finally {
    savingId.value = null;
  }
};

onMounted(() => {
  fetchSessions();
  fetchItems();
});
</script>
