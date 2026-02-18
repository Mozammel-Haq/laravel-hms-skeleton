<template>
  <div class="container-fluid py-4">
    <div class="card p-3 mb-3 border-0 shadow-sm">
      <div
        class="d-flex justify-content-between align-items-center bg-primary-subtle text-primary px-4 pt-3 pb-3 rounded-3 mb-0"
      >
        <div>
          <h5 class="fw-bold mb-1 text-primary">Training Sessions</h5>
          <nav aria-label="breadcrumb">
            <ol class="breadcrumb breadcrumb-dots mb-0 text-muted small">
              <li class="breadcrumb-item">
                <router-link to="/">Dashboard</router-link>
              </li>
              <li class="breadcrumb-item">Training</li>
              <li class="breadcrumb-item active" aria-current="page">Sessions</li>
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
            <i class="ti ti-plus me-2"></i>Schedule Session
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
              <option value="scheduled">Scheduled</option>
              <option value="ongoing">Ongoing</option>
              <option value="completed">Completed</option>
              <option value="cancelled">Cancelled</option>
            </select>
          </div>
          <div class="col-md-3">
            <label class="form-label">Course</label>
            <select v-model="filters.course_id" class="form-select">
              <option value="">All</option>
              <option v-for="course in courses" :key="course.id" :value="course.id">
                {{ course.title }}
              </option>
            </select>
          </div>
          <div class="col-md-2">
            <label class="form-label">From Date</label>
            <input v-model="filters.from_date" type="date" class="form-control" />
          </div>
          <div class="col-md-2">
            <label class="form-label">To Date</label>
            <input v-model="filters.to_date" type="date" class="form-control" />
          </div>
          <div class="col-md-2 d-flex gap-2">
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
                <th>Course</th>
                <th>Dates</th>
                <th>Location</th>
                <th>Capacity</th>
                <th>Status</th>
                <th class="text-end pe-4">Actions</th>
              </tr>
            </thead>
            <tbody>
              <tr v-if="loading">
                <td colspan="6" class="text-center py-4">
                  <div class="spinner-border spinner-border-sm text-primary me-2" role="status"></div>
                  Loading sessions...
                </td>
              </tr>
              <tr v-else-if="items.length === 0">
                <td colspan="6" class="text-center py-4 text-muted">
                  No sessions found.
                </td>
              </tr>
              <tr v-for="item in items" :key="item.id">
                <td>
                  <div class="fw-semibold">
                    {{ item.course?.title || 'Course #' + item.course_id }}
                  </div>
                  <div class="text-muted small">
                    Facilitator: {{ item.facilitator_user_id ? '#' + item.facilitator_user_id : 'Not set' }}
                  </div>
                </td>
                <td>
                  <div class="small">
                    <div v-if="item.start_date">Start: {{ formatDate(item.start_date) }}</div>
                    <div v-if="item.end_date">End: {{ formatDate(item.end_date) }}</div>
                  </div>
                </td>
                <td>{{ item.location || '-' }}</td>
                <td>{{ item.capacity || '-' }}</td>
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
            <h5 class="modal-title">{{ form.id ? 'Edit Session' : 'Schedule Session' }}</h5>
            <button type="button" class="btn-close" @click="closeForm"></button>
          </div>
          <div class="modal-body">
            <div v-if="formError" class="alert alert-danger py-2 mb-3">
              {{ formError }}
            </div>
            <div class="row">
              <div class="col-md-6 mb-3">
                <label class="form-label">Course</label>
                <select v-model.number="form.course_id" class="form-select">
                  <option disabled value="">Select course</option>
                  <option v-for="course in courses" :key="course.id" :value="course.id">
                    {{ course.title }}
                  </option>
                </select>
              </div>
              <div class="col-md-3 mb-3">
                <label class="form-label">Start Date</label>
                <input v-model="form.start_date" type="date" class="form-control" />
              </div>
              <div class="col-md-3 mb-3">
                <label class="form-label">End Date</label>
                <input v-model="form.end_date" type="date" class="form-control" />
              </div>
            </div>
            <div class="row">
              <div class="col-md-4 mb-3">
                <label class="form-label">Facilitator User ID</label>
                <input v-model.number="form.facilitator_user_id" type="number" class="form-control" />
              </div>
              <div class="col-md-4 mb-3">
                <label class="form-label">Location</label>
                <input v-model="form.location" type="text" class="form-control" />
              </div>
              <div class="col-md-4 mb-3">
                <label class="form-label">Capacity</label>
                <input v-model.number="form.capacity" type="number" min="1" class="form-control" />
              </div>
            </div>
            <div class="mb-3">
              <label class="form-label">Status</label>
              <select v-model="form.status" class="form-select">
                <option value="scheduled">Scheduled</option>
                <option value="ongoing">Ongoing</option>
                <option value="completed">Completed</option>
                <option value="cancelled">Cancelled</option>
              </select>
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
const courses = ref([]);
const loading = ref(false);
const saving = ref(false);
const savingId = ref(null);
const filters = ref({
  status: '',
  course_id: '',
  from_date: '',
  to_date: ''
});

const formModal = ref(null);
const form = ref({
  id: null,
  course_id: '',
  facilitator_user_id: null,
  start_date: '',
  end_date: '',
  location: '',
  capacity: null,
  status: 'scheduled',
  notes: ''
});
const formError = ref('');

const openMenuId = ref(null);

const statusClass = (status) => {
  if (status === 'scheduled') return 'bg-secondary';
  if (status === 'ongoing') return 'bg-info';
  if (status === 'completed') return 'bg-success';
  if (status === 'cancelled') return 'bg-danger';
  return 'bg-light';
};

const toggleRowMenu = (id) => {
  openMenuId.value = openMenuId.value === id ? null : id;
};

const closeRowMenu = () => {
  openMenuId.value = null;
};

const formatDate = (value) => {
  if (!value) return '';
  try {
    return new Date(value).toLocaleDateString();
  } catch {
    return value;
  }
};

const fetchCourses = async () => {
  try {
    const response = await api.get('/training-courses', { params: { status: 'active' } });
    const payload = response.data;
    courses.value = Array.isArray(payload.data) ? payload.data : [];
  } catch (e) {
    console.error(e);
  }
};

const fetchItems = async () => {
  loading.value = true;
  try {
    const params = {};
    if (filters.value.status) params.status = filters.value.status;
    if (filters.value.course_id) params.course_id = filters.value.course_id;
    if (filters.value.from_date) params.from_date = filters.value.from_date;
    if (filters.value.to_date) params.to_date = filters.value.to_date;
    const response = await api.get('/training-sessions', { params });
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
    course_id: '',
    from_date: '',
    to_date: ''
  };
  fetchItems();
};

const openForm = (item = null) => {
  formError.value = '';
  if (item) {
    form.value = {
      id: item.id,
      course_id: item.course_id,
      facilitator_user_id: item.facilitator_user_id || null,
      start_date: item.start_date || '',
      end_date: item.end_date || '',
      location: item.location || '',
      capacity: item.capacity ?? null,
      status: item.status || 'scheduled',
      notes: item.notes || ''
    };
  } else {
    form.value = {
      id: null,
      course_id: '',
      facilitator_user_id: null,
      start_date: '',
      end_date: '',
      location: '',
      capacity: null,
      status: 'scheduled',
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
      course_id: form.value.course_id,
      facilitator_user_id: form.value.facilitator_user_id || null,
      start_date: form.value.start_date || null,
      end_date: form.value.end_date || null,
      location: form.value.location || null,
      capacity: form.value.capacity || null,
      status: form.value.status,
      notes: form.value.notes || null
    };
    if (form.value.id) {
      savingId.value = form.value.id;
      await api.put(`/training-sessions/${form.value.id}`, payload);
    } else {
      await api.post('/training-sessions', payload);
    }
    closeForm();
    await fetchItems();
  } catch (e) {
    const message = e?.response?.data?.message;
    formError.value = typeof message === 'string' ? message : 'Failed to save session';
  } finally {
    saving.value = false;
    savingId.value = null;
  }
};

const deleteItem = async (item) => {
  if (!window.confirm('Delete this session?')) return;
  savingId.value = item.id;
  try {
    await api.delete(`/training-sessions/${item.id}`);
    await fetchItems();
  } catch (e) {
    console.error(e);
  } finally {
    savingId.value = null;
  }
};

onMounted(() => {
  fetchCourses();
  fetchItems();
});
</script>
