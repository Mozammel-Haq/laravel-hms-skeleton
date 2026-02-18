<template>
  <div class="container-fluid py-4">
    <div class="card p-3 mb-3 border-0 shadow-sm">
      <div
        class="d-flex justify-content-between align-items-center bg-primary-subtle text-primary px-4 pt-3 pb-3 rounded-3 mb-0"
      >
        <div>
          <h5 class="fw-bold mb-1 text-primary">Training Courses</h5>
          <nav aria-label="breadcrumb">
            <ol class="breadcrumb breadcrumb-dots mb-0 text-muted small">
              <li class="breadcrumb-item">
                <router-link to="/">Dashboard</router-link>
              </li>
              <li class="breadcrumb-item">Training</li>
              <li class="breadcrumb-item active" aria-current="page">Courses</li>
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
            <i class="ti ti-plus me-2"></i>New Course
          </button>
        </div>
      </div>
    </div>

    <div class="card border-0 shadow-sm mb-3">
      <div class="card-body">
        <div class="row g-3 align-items-end">
          <div class="col-md-3">
            <label class="form-label">Status</label>
            <select v-model="filters.status" class="form-select">
              <option value="">All</option>
              <option value="draft">Draft</option>
              <option value="active">Active</option>
              <option value="inactive">Inactive</option>
              <option value="archived">Archived</option>
            </select>
          </div>
          <div class="col-md-4">
            <label class="form-label">Search</label>
            <input v-model="filters.search" type="text" class="form-control" placeholder="Title, code or category" />
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
                <th>Title</th>
                <th>Code</th>
                <th>Category</th>
                <th>Target Role</th>
                <th>Mode</th>
                <th>Duration (hrs)</th>
                <th>Status</th>
                <th class="text-end pe-4">Actions</th>
              </tr>
            </thead>
            <tbody>
              <tr v-if="loading">
                <td colspan="8" class="text-center py-4">
                  <div class="spinner-border spinner-border-sm text-primary me-2" role="status"></div>
                  Loading courses...
                </td>
              </tr>
              <tr v-else-if="items.length === 0">
                <td colspan="8" class="text-center py-4 text-muted">
                  No courses found.
                </td>
              </tr>
              <tr v-for="item in items" :key="item.id">
                <td>
                  <div class="fw-semibold">{{ item.title }}</div>
                  <div class="text-muted small">
                    {{ item.description || 'No description' }}
                  </div>
                </td>
                <td>{{ item.code || '-' }}</td>
                <td>{{ item.category || '-' }}</td>
                <td>{{ item.target_role || '-' }}</td>
                <td>{{ formatMode(item.mode) }}</td>
                <td>{{ item.duration_hours }}</td>
                <td>
                  <span class="badge" :class="statusClass(item.status)">
                    {{ item.status }}
                  </span>
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
                          @click.prevent="() => { closeRowMenu(); archiveItem(item); }"
                          :class="{ disabled: item.status === 'archived' || savingId === item.id }"
                        >
                          <i class="ti ti-archive me-2"></i>Archive
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
            <h5 class="modal-title">{{ form.id ? 'Edit Course' : 'New Course' }}</h5>
            <button type="button" class="btn-close" @click="closeForm"></button>
          </div>
          <div class="modal-body">
            <div v-if="formError" class="alert alert-danger py-2 mb-3">
              {{ formError }}
            </div>
            <div class="row">
              <div class="col-md-7 mb-3">
                <label class="form-label">Title</label>
                <input v-model="form.title" type="text" class="form-control" />
              </div>
              <div class="col-md-3 mb-3">
                <label class="form-label">Code</label>
                <input v-model="form.code" type="text" class="form-control" />
              </div>
              <div class="col-md-2 mb-3">
                <label class="form-label">Duration (hrs)</label>
                <input v-model.number="form.duration_hours" type="number" min="0" class="form-control" />
              </div>
            </div>
            <div class="row">
              <div class="col-md-4 mb-3">
                <label class="form-label">Category</label>
                <input v-model="form.category" type="text" class="form-control" />
              </div>
              <div class="col-md-4 mb-3">
                <label class="form-label">Target Role</label>
                <input v-model="form.target_role" type="text" class="form-control" />
              </div>
              <div class="col-md-4 mb-3">
                <label class="form-label">Mode</label>
                <select v-model="form.mode" class="form-select">
                  <option value="classroom">Classroom</option>
                  <option value="online">Online</option>
                  <option value="blended">Blended</option>
                </select>
              </div>
            </div>
            <div class="mb-3">
              <label class="form-label">Status</label>
              <select v-model="form.status" class="form-select">
                <option value="draft">Draft</option>
                <option value="active">Active</option>
                <option value="inactive">Inactive</option>
                <option value="archived">Archived</option>
              </select>
            </div>
            <div class="mb-2">
              <label class="form-label">Description</label>
              <textarea v-model="form.description" rows="3" class="form-control"></textarea>
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
  search: ''
});

const formModal = ref(null);
const form = ref({
  id: null,
  title: '',
  code: '',
  category: '',
  target_role: '',
  mode: 'classroom',
  duration_hours: 0,
  status: 'draft',
  description: ''
});
const formError = ref('');

const openMenuId = ref(null);

const formatMode = (mode) => {
  if (!mode) return '-';
  return mode.charAt(0).toUpperCase() + mode.slice(1);
};

const statusClass = (status) => {
  if (status === 'active') return 'bg-success';
  if (status === 'draft') return 'bg-secondary';
  if (status === 'inactive') return 'bg-warning';
  if (status === 'archived') return 'bg-dark';
  return 'bg-light';
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
    if (filters.value.status) params.status = filters.value.status;
    if (filters.value.search) params.search = filters.value.search;
    const response = await api.get('/training-courses', { params });
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
    search: ''
  };
  fetchItems();
};

const openForm = (item = null) => {
  formError.value = '';
  if (item) {
    form.value = {
      id: item.id,
      title: item.title || '',
      code: item.code || '',
      category: item.category || '',
      target_role: item.target_role || '',
      mode: item.mode || 'classroom',
      duration_hours: item.duration_hours ?? 0,
      status: item.status || 'draft',
      description: item.description || ''
    };
  } else {
    form.value = {
      id: null,
      title: '',
      code: '',
      category: '',
      target_role: '',
      mode: 'classroom',
      duration_hours: 0,
      status: 'draft',
      description: ''
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
      code: form.value.code || null,
      category: form.value.category || null,
      target_role: form.value.target_role || null,
      mode: form.value.mode,
      duration_hours: form.value.duration_hours,
      status: form.value.status,
      description: form.value.description || null
    };
    if (form.value.id) {
      savingId.value = form.value.id;
      await api.put(`/training-courses/${form.value.id}`, payload);
    } else {
      await api.post('/training-courses', payload);
    }
    closeForm();
    await fetchItems();
  } catch (e) {
    const message = e?.response?.data?.message;
    formError.value = typeof message === 'string' ? message : 'Failed to save course';
  } finally {
    saving.value = false;
    savingId.value = null;
  }
};

const archiveItem = async (item) => {
  if (!window.confirm('Archive this course?')) return;
  savingId.value = item.id;
  try {
    await api.delete(`/training-courses/${item.id}`);
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
