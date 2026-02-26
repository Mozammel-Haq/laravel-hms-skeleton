<template>
  <div class="departments-page">
    <div class="card p-3 mb-3 border-0 shadow-sm">
      <div class="d-flex justify-content-between align-items-center bg-primary-subtle text-primary px-4 pt-3 pb-3 rounded-3 mb-0">
        <div>
          <h5 class="fw-bold mb-1 text-primary">Departments</h5>
          <nav aria-label="breadcrumb">
            <ol class="breadcrumb breadcrumb-dots mb-0 text-muted small">
              <li class="breadcrumb-item">
                <router-link to="/">Dashboard</router-link>
              </li>
              <li class="breadcrumb-item active" aria-current="page">Departments</li>
            </ol>
          </nav>
        </div>
        <div>
          <button class="btn btn-primary me-2" @click="openForCreate" :disabled="loading">
            <i class="ti ti-plus me-2"></i> Add Department
          </button>
          <button class="btn btn-outline-primary" @click="fetchDepartments" :disabled="loading">
            <i class="ti ti-refresh me-2"></i> Refresh
          </button>
        </div>
      </div>
    </div>
    <div v-if="loading" class="text-center py-5">
      <div class="spinner-border text-primary" role="status"></div>
    </div>
    <div v-else class="card border-0 shadow-sm">
      <div class="card-body p-0">
        <div class="table-responsive">
          <table class="table table-hover align-middle mb-0">
            <thead class="bg-light">
              <tr>
                <th class="ps-4">Name</th>
                <th>Status</th>
                <th>Floor</th>
                <th>Phone Ext</th>
                <th class="text-end pe-4">Actions</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="dept in departments" :key="dept.id">
                <td class="ps-4">{{ dept.name }}</td>
                <td>
                  <span :class="['badge', dept.status === 'active' ? 'bg-success-subtle text-success' : 'bg-secondary-subtle text-secondary']">
                    {{ dept.status }}
                  </span>
                </td>
                <td>{{ dept.floor_number || '—' }}</td>
                <td>{{ dept.phone_extension || '—' }}</td>
                <td class="text-end pe-4">
                  <div class="dropdown">
                    <button
                      type="button"
                      class="btn btn-sm btn-light btn-icon"
                      @click="toggleRowMenu(dept.id)"
                    >
                      <i class="ti ti-dots-vertical"></i>
                    </button>
                    <ul
                      class="dropdown-menu dropdown-menu-end shadow-sm border-0"
                      :class="{ show: openMenuId === dept.id }"
                    >
                      <li>
                        <a
                          class="dropdown-item"
                          href="#"
                          @click.prevent="() => { closeRowMenu(); openForEdit(dept); }"
                        >
                          <i class="ti ti-edit me-2"></i>Edit
                        </a>
                      </li>
                      <li><hr class="dropdown-divider" /></li>
                      <li>
                        <a
                          class="dropdown-item text-danger"
                          href="#"
                          @click.prevent="() => { closeRowMenu(); deleteItem(dept); }"
                        >
                          <i class="ti ti-trash me-2"></i>Delete
                        </a>
                      </li>
                    </ul>
                  </div>
                </td>
              </tr>
              <tr v-if="departments.length === 0">
                <td colspan="5" class="text-center py-4 text-muted">No departments</td>
              </tr>
            </tbody>
          </table>
        </div>
        <div v-if="pagination.total > 0" class="d-flex justify-content-between align-items-center px-4 py-3 border-top pagination-bar">
          <div class="text-muted small pagination-summary">
            Showing
            <span class="fw-semibold">{{ pagination.from }}</span>
            to
            <span class="fw-semibold">{{ pagination.to }}</span>
            of
            <span class="fw-semibold">{{ pagination.total }}</span>
            departments
          </div>
          <nav aria-label="Departments pagination">
            <ul class="pagination pagination-sm mb-0">
              <li :class="['page-item', { disabled: !pagination.prev_page_url }]">
                <button
                  class="page-link"
                  type="button"
                  @click="changePage(pagination.current_page - 1)"
                  :disabled="!pagination.prev_page_url"
                  aria-label="Previous page"
                >
                  <i class="ti ti-chevron-left"></i>
                </button>
              </li>
              <li
                v-for="page in paginationPages"
                :key="page.key"
                :class="['page-item', { active: page.number === pagination.current_page, disabled: page.isSeparator }]"
              >
                <button v-if="!page.isSeparator" class="page-link" type="button" @click="changePage(page.number)">
                  {{ page.label }}
                </button>
                <span
                  v-else
                  class="page-link border-0 bg-transparent text-muted"
                >
                  …
                </span>
              </li>
              <li :class="['page-item', { disabled: !pagination.next_page_url }]">
                <button
                  class="page-link"
                  type="button"
                  @click="changePage(pagination.current_page + 1)"
                  :disabled="!pagination.next_page_url"
                  aria-label="Next page"
                >
                  <i class="ti ti-chevron-right"></i>
                </button>
              </li>
            </ul>
          </nav>
        </div>
      </div>
    </div>
  </div>
  <div v-if="showModal">
    <div class="modal-backdrop fade show"></div>
    <div class="modal fade show d-block" tabindex="-1">
      <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">{{ isEditing ? 'Edit Department' : 'Add Department' }}</h5>
            <button type="button" class="btn-close" @click="closeModal"></button>
          </div>
          <div class="modal-body">
            <div v-if="formError" class="alert alert-danger py-2 mb-3">{{ formError }}</div>
            <div class="row g-3">
              <div class="col-md-6">
                <label class="form-label">Name</label>
                <input type="text" class="form-control" v-model="form.name" />
                <div class="text-danger small mt-1" v-if="errors.name">{{ errors.name }}</div>
              </div>
              <div class="col-md-6">
                <label class="form-label">Status</label>
                <select class="form-select" v-model="form.status">
                  <option value="active">active</option>
                  <option value="inactive">inactive</option>
                </select>
                <div class="text-danger small mt-1" v-if="errors.status">{{ errors.status }}</div>
              </div>
              <div class="col-md-6">
                <label class="form-label">Floor Number</label>
                <input type="text" class="form-control" v-model="form.floor_number" />
                <div class="text-danger small mt-1" v-if="errors.floor_number">{{ errors.floor_number }}</div>
              </div>
              <div class="col-md-6">
                <label class="form-label">Phone Extension</label>
                <input type="text" class="form-control" v-model="form.phone_extension" />
                <div class="text-danger small mt-1" v-if="errors.phone_extension">{{ errors.phone_extension }}</div>
              </div>
              <div class="col-12">
                <label class="form-label">Description</label>
                <textarea class="form-control" rows="3" v-model="form.description"></textarea>
                <div class="text-danger small mt-1" v-if="errors.description">{{ errors.description }}</div>
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-light" @click="closeModal" :disabled="saving">Cancel</button>
            <button type="button" class="btn btn-primary" @click="submitForm" :disabled="saving">
              <span v-if="saving" class="spinner-border spinner-border-sm me-2"></span>
              {{ isEditing ? 'Update' : 'Create' }}
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
  </template>
<script setup>
import { ref, onMounted, computed } from 'vue';
import api from '../services/api';

const departments = ref([]);
const loading = ref(false);
const openMenuId = ref(null);
const pagination = ref({
  current_page: 1,
  last_page: 1,
  per_page: 10,
  total: 0,
  from: 0,
  to: 0,
  prev_page_url: null,
  next_page_url: null
});
const showModal = ref(false);
const isEditing = ref(false);
const saving = ref(false);
const currentId = ref(null);
const formError = ref('');
const form = ref({
  name: '',
  status: 'active',
  floor_number: '',
  phone_extension: '',
  description: ''
});
const errors = ref({});

const resetForm = () => {
  form.value = { name: '', status: 'active', floor_number: '', phone_extension: '', description: '' };
  errors.value = {};
  formError.value = '';
  currentId.value = null;
};

const openForCreate = () => {
  resetForm();
  isEditing.value = false;
  showModal.value = true;
};

const openForEdit = (item) => {
  isEditing.value = true;
  currentId.value = item.id;
  form.value = {
    name: item.name || '',
    status: item.status || 'active',
    floor_number: item.floor_number || '',
    phone_extension: item.phone_extension || '',
    description: item.description || ''
  };
  errors.value = {};
  formError.value = '';
  showModal.value = true;
};

const closeModal = () => {
  showModal.value = false;
};

const submitForm = async () => {
  saving.value = true;
  errors.value = {};
  formError.value = '';
  try {
    if (isEditing.value && currentId.value) {
      await api.patch(`/departments/${currentId.value}`, form.value);
    } else {
      await api.post('/departments', form.value);
    }
    await fetchDepartments();
    showModal.value = false;
  } catch (e) {
    if (e.response?.status === 422) {
      const ve = e.response.data.errors || {};
      const mapped = {};
      Object.keys(ve).forEach(k => mapped[k] = Array.isArray(ve[k]) ? ve[k][0] : ve[k]);
      errors.value = mapped;
      formError.value = e.response.data.message || 'Validation error';
    } else {
      formError.value = e.response?.data?.message || 'Failed to save';
    }
  } finally {
    saving.value = false;
  }
};

const toggleRowMenu = (id) => {
  openMenuId.value = openMenuId.value === id ? null : id;
};

const closeRowMenu = () => {
  openMenuId.value = null;
};

const deleteItem = async (item) => {
  if (!confirm('Delete this department?')) return;
  try {
    await api.delete(`/departments/${item.id}`);
    await fetchDepartments();
  } catch (e) {}
};

const fetchDepartments = async () => {
  loading.value = true;
  try {
    const res = await api.get('/departments', {
      params: { page: pagination.value.current_page, per_page: pagination.value.per_page }
    });
    const data = res.data.data || {};
    const list = data.data || [];
    departments.value = list;
    const meta = data.meta || {};
    pagination.value = {
      current_page: meta.current_page || 1,
      last_page: meta.last_page || 1,
      per_page: meta.per_page || pagination.value.per_page || 10,
      total: meta.total || departments.value.length,
      from: meta.from || (departments.value.length ? 1 : 0),
      to: meta.to || departments.value.length,
      prev_page_url: data.prev_page_url || null,
      next_page_url: data.next_page_url || null
    };
  } finally {
    loading.value = false;
  }
};

const changePage = (page) => {
  if (page < 1 || page > pagination.value.last_page || page === pagination.value.current_page) return;
  pagination.value.current_page = page;
  fetchDepartments();
};

const paginationPages = computed(() => {
  const pages = [];
  const total = pagination.value.last_page;
  const current = pagination.value.current_page;
  if (total <= 7) {
    for (let i = 1; i <= total; i += 1) {
      pages.push({ key: `p-${i}`, number: i, label: i, isSeparator: false });
    }
    return pages;
  }
  const addPage = (n) => pages.push({ key: `p-${n}`, number: n, label: n, isSeparator: false });
  const addSep = (idx) => pages.push({ key: `s-${idx}`, number: null, label: '…', isSeparator: true });
  addPage(1);
  if (current > 4) addSep('start');
  const start = Math.max(2, current - 1);
  const end = Math.min(total - 1, current + 1);
  for (let i = start; i <= end; i += 1) {
    addPage(i);
  }
  if (current < total - 3) addSep('end');
  addPage(total);
  return pages;
});

onMounted(fetchDepartments);
</script>
