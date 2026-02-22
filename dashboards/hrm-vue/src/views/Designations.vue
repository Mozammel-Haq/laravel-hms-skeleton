<template>
  <div class="designations-page">
    <div class="card p-3 mb-3 border-0 shadow-sm">
      <div class="d-flex justify-content-between align-items-center bg-primary-subtle text-primary px-4 pt-3 pb-3 rounded-3 mb-0">
        <div>
          <h5 class="fw-bold mb-1 text-primary">Designations</h5>
          <nav aria-label="breadcrumb">
            <ol class="breadcrumb breadcrumb-dots mb-0 text-muted small">
              <li class="breadcrumb-item">
                <router-link to="/">Dashboard</router-link>
              </li>
              <li class="breadcrumb-item active" aria-current="page">Designations</li>
            </ol>
          </nav>
        </div>
        <div>
          <button class="btn btn-primary me-2" @click="openForCreate" :disabled="loading">
            <i class="ti ti-plus me-2"></i> Add Designation
          </button>
          <button class="btn btn-outline-primary" @click="fetchDesignations" :disabled="loading">
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
        <div class="bg-light p-3 rounded mb-4 mx-3 mt-3">
          <form @submit.prevent="applyFilters">
            <div class="row g-2 align-items-end justify-content-center">
              <div class="col-md-4">
                <label class="form-label small fw-bold text-muted mb-1">Search</label>
                <input v-model="filters.search" type="text" class="form-control form-control-sm" placeholder="Name or code..." />
              </div>
              <div class="col-md-3">
                <label class="form-label small fw-bold text-muted mb-1">Status</label>
                <select v-model="filters.status" class="form-select form-select-sm">
                  <option value="">All</option>
                  <option value="active">Active</option>
                  <option value="inactive">Inactive</option>
                </select>
              </div>
              <div class="col-md-2">
                <label class="form-label small fw-bold text-muted mb-1 d-block">&nbsp;</label>
                <button type="submit" class="btn btn-sm btn-primary w-100">
                  <i class="ti ti-filter"></i>
                </button>
              </div>
              <div class="col-md-2">
                <label class="form-label small fw-bold text-muted mb-1 d-block">&nbsp;</label>
                <button type="button" class="btn btn-sm btn-outline-danger w-100" @click="resetFilters">
                  Reset
                </button>
              </div>
            </div>
          </form>
        </div>
        <div class="table-responsive">
          <table class="table table-hover align-middle mb-0">
            <thead class="bg-light">
              <tr>
                <th class="ps-4">Name</th>
                <th>Code</th>
                <th>Grade</th>
                <th>Status</th>
                <th class="text-end pe-4">Actions</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="d in filteredRows" :key="d.id">
                <td class="ps-4">{{ d.name }}</td>
                <td>{{ d.code || '—' }}</td>
                <td>{{ d.grade || '—' }}</td>
                <td>
                  <span :class="['badge', d.status === 'active' ? 'bg-success-subtle text-success' : 'bg-secondary-subtle text-secondary']">
                    {{ d.status }}
                  </span>
                </td>
                <td class="text-end pe-4">
                  <div class="dropdown">
                    <button
                      type="button"
                      class="btn btn-sm btn-light btn-icon"
                      @click="toggleRowMenu(d.id)"
                    >
                      <i class="ti ti-dots-vertical"></i>
                    </button>
                    <ul
                      class="dropdown-menu dropdown-menu-end shadow-sm border-0"
                      :class="{ show: openMenuId === d.id }"
                    >
                      <li>
                        <a
                          class="dropdown-item"
                          href="#"
                          @click.prevent="() => { closeRowMenu(); openForEdit(d); }"
                        >
                          <i class="ti ti-edit me-2"></i>Edit
                        </a>
                      </li>
                      <li><hr class="dropdown-divider" /></li>
                      <li>
                        <a
                          class="dropdown-item text-danger"
                          href="#"
                          @click.prevent="() => { closeRowMenu(); deleteItem(d); }"
                        >
                          <i class="ti ti-trash me-2"></i>Delete
                        </a>
                      </li>
                    </ul>
                  </div>
                </td>
              </tr>
              <tr v-if="filteredRows.length === 0">
                <td colspan="5" class="text-center py-4 text-muted">No designations</td>
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
            designations
          </div>
          <nav aria-label="Designations pagination">
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
  <div v-if="showModal" class="modal fade show" style="display: block;" tabindex="-1">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">{{ isEditing ? 'Edit Designation' : 'Add Designation' }}</h5>
          <button type="button" class="btn-close" @click="closeModal"></button>
        </div>
        <div class="modal-body">
          <div v-if="formError" class="alert alert-danger">{{ formError }}</div>
          <div class="row g-3">
            <div class="col-md-6">
              <label class="form-label">Name</label>
              <input type="text" class="form-control" v-model="form.name" />
              <div class="text-danger small mt-1" v-if="errors.name">{{ errors.name }}</div>
            </div>
            <div class="col-md-6">
              <label class="form-label">Slug</label>
              <input type="text" class="form-control" v-model="form.slug" />
              <div class="text-danger small mt-1" v-if="errors.slug">{{ errors.slug }}</div>
            </div>
            <div class="col-md-6">
              <label class="form-label">Code</label>
              <input type="text" class="form-control" v-model="form.code" />
              <div class="text-danger small mt-1" v-if="errors.code">{{ errors.code }}</div>
            </div>
            <div class="col-md-6">
              <label class="form-label">Grade</label>
              <input type="text" class="form-control" v-model="form.grade" />
              <div class="text-danger small mt-1" v-if="errors.grade">{{ errors.grade }}</div>
            </div>
            <div class="col-md-6">
              <label class="form-label">Status</label>
              <select class="form-select" v-model="form.status">
                <option value="active">active</option>
                <option value="inactive">inactive</option>
              </select>
              <div class="text-danger small mt-1" v-if="errors.status">{{ errors.status }}</div>
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
  <div v-if="showModal" class="modal-backdrop fade show"></div>
</template>

<script setup>
import { ref, onMounted, computed } from 'vue';
import api from '../services/api';

const rows = ref([]);
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
  slug: '',
  code: '',
  grade: '',
  status: 'active',
  description: ''
});
const errors = ref({});

const filters = ref({
  search: '',
  status: ''
});

const filteredRows = computed(() => {
  const list = rows.value || [];
  const term = filters.value.search.trim().toLowerCase();
  const status = filters.value.status;
  return list.filter((d) => {
    const matchesTerm =
      !term ||
      (d.name || '').toLowerCase().includes(term) ||
      (d.code || '').toLowerCase().includes(term);
    const matchesStatus = !status || (d.status || '').toLowerCase() === status;
    return matchesTerm && matchesStatus;
  });
});

const applyFilters = () => {};
const resetFilters = () => {
  filters.value = { search: '', status: '' };
};

const fetchDesignations = async () => {
  loading.value = true;
  try {
    const res = await api.get('/designations', {
      params: { page: pagination.value.current_page, per_page: pagination.value.per_page }
    });
    const data = res.data.data || {};
    const list = data.data || [];
    rows.value = list;
    const meta = data.meta || {};
    pagination.value = {
      current_page: meta.current_page || 1,
      last_page: meta.last_page || 1,
      per_page: meta.per_page || pagination.value.per_page || 10,
      total: meta.total || rows.value.length,
      from: meta.from || (rows.value.length ? 1 : 0),
      to: meta.to || rows.value.length,
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
  fetchDesignations();
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

onMounted(fetchDesignations);

const resetForm = () => {
  form.value = { name: '', slug: '', code: '', grade: '', status: 'active', description: '' };
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
    slug: item.slug || '',
    code: item.code || '',
    grade: item.grade || '',
    status: item.status || 'active',
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
      await api.patch(`/designations/${currentId.value}`, form.value);
    } else {
      await api.post('/designations', form.value);
    }
    await fetchDesignations();
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

const deleteItem = async (item) => {
  if (!confirm('Delete this designation?')) return;
  try {
    await api.delete(`/designations/${item.id}`);
    await fetchDesignations();
  } catch (e) {}
};

const toggleRowMenu = (id) => {
  openMenuId.value = openMenuId.value === id ? null : id;
};

const closeRowMenu = () => {
  openMenuId.value = null;
};
</script>
