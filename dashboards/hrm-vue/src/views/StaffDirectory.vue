<template>
  <div class="staff-page">
    <div class="d-flex justify-content-between align-items-center mb-4">
      <h4 class="mb-0">Staff Directory</h4>
      <div class="d-flex gap-2">
        <button class="btn btn-outline-secondary" @click="fetchStaff" :disabled="loading">
          <i class="ti ti-refresh me-2"></i> Refresh
        </button>
        <button class="btn btn-primary" v-if="canCreate" @click="openCreateModal">
          <i class="ti ti-plus me-2"></i> Add Staff
        </button>
      </div>
    </div>

    <!-- Stats Row (Dashboard-style KPI cards) -->
    <div class="row g-4 mb-4">
      <div class="col-md-3" v-for="(kpi, idx) in kpis" :key="kpi.label">
        <div
          class="position-relative overflow-hidden rounded-4 h-100 kpi-card"
          :class="'kpi-' + kpi.type"
          data-bs-theme="light,dark"
        >
          <div class="position-absolute top-0 end-0 w-100 h-100 opacity-25 pattern-bg">
            <svg width="100%" height="100%" xmlns="http://www.w3.org/2000/svg">
              <defs>
                <pattern
                  :id="'pattern-grid-staff-' + idx"
                  x="0"
                  y="0"
                  width="30"
                  height="30"
                  patternUnits="userSpaceOnUse"
                >
                  <rect
                    x="0"
                    y="0"
                    width="2"
                    height="2"
                    :fill="
                      kpi.type === 'primary'
                        ? 'var(--primary-color)'
                        : kpi.type === 'info'
                          ? 'var(--info-color)'
                          : kpi.type === 'success'
                            ? 'var(--success-color)'
                            : 'var(--warning-color)'
                    "
                    fill-opacity="0.2"
                  />
                </pattern>
              </defs>
              <rect width="100%" height="100%" :fill="'url(#pattern-grid-staff-' + idx + ')'" />
            </svg>
          </div>
          <div
            class="position-absolute top-0 end-0 w-25 h-25 decorative-shape"
            :style="{
              background:
                'radial-gradient(circle at top right, ' +
                (kpi.type === 'primary'
                  ? 'var(--primary-color)'
                  : kpi.type === 'info'
                    ? 'var(--info-color)'
                    : kpi.type === 'success'
                      ? 'var(--success-color)'
                      : 'var(--warning-color)') +
                ' 0%, transparent 70%)',
              opacity: 0.15
            }"
          ></div>
          <div class="card-body position-relative z-1 p-4">
            <div class="d-flex align-items-start justify-content-between mb-3">
              <div>
                <h6 class="card-title fw-medium mb-1 kpi-label" style="letter-spacing: 0.5px;">
                  {{ kpi.label }}
                </h6>
                <h2 class="fw-bold kpi-value mb-0">{{ kpi.value }}</h2>
              </div>
              <div
                class="rounded-3 p-2 kpi-icon-container"
                :class="['bg-' + kpi.type + '-subtle', 'border', 'border-' + kpi.type + '-subtle']"
              >
                <i
                  class="ti fs-2"
                  :class="kpi.icon"
                  :style="{ color: 'var(--' + (kpi.type === 'primary' ? 'primary' : kpi.type) + '-color)' }"
                ></i>
              </div>
            </div>
            <div class="border-top pt-3 mt-3 kpi-divider" :class="'border-' + kpi.type + '-subtle'">
              <p class="text-muted kpi-footer mb-0">
                Clinic staff statistics
              </p>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div v-if="loading" class="text-center py-5">
      <div class="spinner-border text-primary" role="status">
        <span class="visually-hidden">Loading...</span>
      </div>
    </div>

    <div v-else class="card border-0 shadow-sm">
      <div class="card-body p-0">
        <div class="bg-light p-3 rounded mb-4 mx-3 mt-3">
          <form @submit.prevent="applyFilters">
            <div class="row g-2 align-items-end">
              <div class="col-md-3">
                <label class="form-label small fw-bold text-muted mb-1">Search</label>
                <input v-model="filters.search" type="text" class="form-control form-control-sm" placeholder="Name or Email..." />
              </div>
              <div class="col-md-2">
                <label class="form-label small fw-bold text-muted mb-1">Status</label>
                <select v-model="filters.status" class="form-select form-select-sm">
                  <option value="active">Active</option>
                  <option value="trashed">Trashed</option>
                </select>
              </div>
              <div class="col-md-2">
                <label class="form-label small fw-bold text-muted mb-1">Role</label>
                <select v-model.number="filters.role" class="form-select form-select-sm">
                  <option :value="null">All Roles</option>
                  <option v-for="r in roleOptions" :key="r.id" :value="r.id">{{ r.name }}</option>
                </select>
              </div>
              <div class="col-md-2">
                <label class="form-label small fw-bold text-muted mb-1">From</label>
                <input v-model="filters.from" type="date" class="form-control form-control-sm" />
              </div>
              <div class="col-md-2">
                <label class="form-label small fw-bold text-muted mb-1">To</label>
                <input v-model="filters.to" type="date" class="form-control form-control-sm" />
              </div>
              <div class="col-md-1">
                <label class="form-label small fw-bold text-muted mb-1 d-block">&nbsp;</label>
                <button type="submit" class="btn btn-sm btn-primary w-100">
                  <i class="ti ti-filter"></i>
                </button>
              </div>
            </div>
          </form>
        </div>
        <div class="table-responsive">
          <table class="table table-sm table-hover align-middle mb-0">
            <thead class="table-light">
              <tr>
                <th class="small text-uppercase text-muted ps-4">Name</th>
                <th class="small text-uppercase text-muted">Email</th>
                <th class="small text-uppercase text-muted">Roles</th>
                <th class="small text-uppercase text-muted">Status</th>
                <th class="small text-uppercase text-muted">Joined</th>
                <th class="text-end small text-uppercase text-muted pe-4">Actions</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="staff in staffList" :key="staff.id">
                <td class="ps-4">
                  <div class="d-flex align-items-center">
                    <div class="avatar bg-primary-subtle text-primary rounded-circle me-3 d-flex align-items-center justify-content-center fw-bold" style="width: 32px; height: 32px;">
                      {{ getInitials(staff.name) }}
                    </div>
                    <div class="fw-semibold">{{ staff.name }}</div>
                  </div>
                </td>
                <td class="small">{{ staff.email }}</td>
                <td>
                  <span v-for="role in staff.roles" :key="role.id" class="badge bg-primary-subtle text-primary border border-primary-subtle me-1">
                    {{ role.name }}
                  </span>
                </td>
                <td>
                  <span class="badge bg-success-subtle text-success" v-if="!staff.deleted_at">Active</span>
                  <span class="badge bg-danger-subtle text-danger" v-else>Deleted</span>
                </td>
                <td>{{ formatDate(staff.created_at) }}</td>
                <td class="text-end pe-4">
                  <div class="dropdown">
                    <button
                      type="button"
                      class="btn btn-sm btn-light btn-icon"
                      @click="toggleRowMenu(staff.id)"
                    >
                      <i class="ti ti-dots-vertical"></i>
                    </button>
                    <ul
                      class="dropdown-menu dropdown-menu-end shadow-sm border-0"
                      :class="{ show: openMenuId === staff.id }"
                    >
                      <li>
                        <a
                          class="dropdown-item"
                          href="#"
                          @click.prevent="() => { closeRowMenu(); goToView(staff); }"
                        >
                          <i class="ti ti-eye me-2"></i>View
                        </a>
                      </li>
                      <li v-if="canEdit">
                        <a
                          class="dropdown-item"
                          href="#"
                          @click.prevent="() => { closeRowMenu(); openEditModal(staff); }"
                        >
                          <i class="ti ti-edit me-2"></i>Edit
                        </a>
                      </li>
                      <li><hr class="dropdown-divider" /></li>
                      <li v-if="canDelete">
                        <a
                          class="dropdown-item text-danger"
                          href="#"
                          @click.prevent="() => { closeRowMenu(); confirmDelete(staff); }"
                        >
                          <i class="ti ti-trash me-2"></i>Delete
                        </a>
                      </li>
                    </ul>
                  </div>
                </td>
              </tr>
              <tr v-if="staffList.length === 0">
                <td colspan="6" class="text-center py-4 text-muted">No staff found</td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>

    <div class="modal fade" id="staffModal" tabindex="-1" aria-hidden="true" ref="staffModalRef">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">{{ editingStaff ? 'Edit Staff' : 'Add Staff' }}</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <form @submit.prevent="saveStaff">
            <div class="modal-body">
              <div class="mb-3">
                <label class="form-label">Name</label>
                <input v-model="form.name" type="text" class="form-control" required />
              </div>
              <div class="mb-3" v-if="!editingStaff">
                <label class="form-label">Email</label>
                <input v-model="form.email" type="email" class="form-control" required />
              </div>
              <div class="mb-3" v-if="!editingStaff">
                <label class="form-label">Password</label>
                <input v-model="form.password" type="password" class="form-control" required minlength="8" />
              </div>
              <div class="mb-3">
                <label class="form-label">Role</label>
                <select v-model.number="form.role_id" class="form-select" required>
                  <option :value="null" disabled>Select role</option>
                  <option v-for="r in roleOptions" :key="r.id" :value="r.id">{{ r.name }}</option>
                </select>
              </div>
              <div v-if="formError" class="alert alert-danger py-2 px-3 mb-0">
                {{ formError }}
              </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
              <button type="submit" class="btn btn-primary" :disabled="saving">
                <span v-if="saving" class="spinner-border spinner-border-sm me-1"></span>
                {{ editingStaff ? 'Save Changes' : 'Create Staff' }}
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, computed } from 'vue';
import { useRouter } from 'vue-router';
import api from '../services/api';
import { useAuthStore } from '../store/authStore';
import * as bootstrap from 'bootstrap';

const router = useRouter();
const staffList = ref([]);
const stats = ref({ total_staff: 0, doctors: 0, nurses: 0, on_duty: 0 });
const openMenuId = ref(null);
const kpis = computed(() => [
  {
    label: 'Total Staff',
    value: stats.value.total_staff ?? staffList.value.length,
    icon: 'ti-users',
    type: 'primary',
  },
  {
    label: 'Doctors',
    value:
      stats.value.doctors ??
      staffList.value.filter(s => s.roles?.some(r => r.name === 'Doctor')).length,
    icon: 'ti-stethoscope',
    type: 'info',
  },
  {
    label: 'Nurses',
    value:
      stats.value.nurses ??
      staffList.value.filter(s => s.roles?.some(r => r.name === 'Nurse')).length,
    icon: 'ti-nurse',
    type: 'info',
  },
  {
    label: 'On Duty',
    value:
      stats.value.on_duty ??
      staffList.value.filter(s => !s.deleted_at && s.status === 'active').length,
    icon: 'ti-user-check',
    type: 'success',
  },
]);
const loading = ref(false);
const roleOptions = ref([]);
const filters = ref({
  search: '',
  status: 'active',
  role: null,
  from: '',
  to: '',
});

const auth = useAuthStore();
const canCreate = computed(() => Array.isArray(auth.user?.abilities) && auth.user.abilities.includes('create_staff'));
const canEdit = computed(() => Array.isArray(auth.user?.abilities) && auth.user.abilities.includes('edit_staff'));
const canDelete = computed(() => Array.isArray(auth.user?.abilities) && auth.user.abilities.includes('delete_staff'));
const staffModalRef = ref(null);
let staffModalInstance = null;
const editingStaff = ref(null);
const saving = ref(false);
const formError = ref('');
const form = ref({
  name: '',
  email: '',
  password: '',
  role_id: null,
});

const fetchStaff = async () => {
  loading.value = true;
  try {
    const params = {};
    if (filters.value.search) params.search = filters.value.search;
    if (filters.value.status) params.status = filters.value.status;
    if (filters.value.role) params.role = filters.value.role;
    if (filters.value.from) params.from = filters.value.from;
    if (filters.value.to) params.to = filters.value.to;
    const response = await api.get('/staff', { params });
    const payload = response.data;
    const pageData = payload.data;
    const meta = payload.meta || {};
    const apiStats = meta.stats || {};
    staffList.value = pageData.data || pageData;
    stats.value = {
      total_staff: apiStats.total_staff ?? staffList.value.length,
      doctors: apiStats.doctors ?? 0,
      nurses: apiStats.nurses ?? 0,
      on_duty: apiStats.on_duty ?? 0,
    };
    const rolesSet = new Map();
    staffList.value.forEach(s => Array.isArray(s.roles) && s.roles.forEach(r => { if (!rolesSet.has(r.id)) rolesSet.set(r.id, { id: r.id, name: r.name }); }));
    roleOptions.value = Array.from(rolesSet.values());
  } catch (error) {
    console.error('Failed to fetch staff:', error);
  } finally {
    loading.value = false;
  }
};
const applyFilters = () => fetchStaff();

const getInitials = (name) => {
  if (!name) return '??';
  return name.split(' ').map(n => n[0]).join('').toUpperCase().substring(0, 2);
};

const formatDate = (dateString) => {
  if (!dateString) return 'N/A';
  return new Date(dateString).toLocaleDateString();
};

onMounted(() => {
  fetchStaff();
  if (staffModalRef.value) {
    staffModalInstance = bootstrap.Modal.getOrCreateInstance(staffModalRef.value);
  }
});

const resetForm = () => {
  form.value = {
    name: '',
    email: '',
    password: '',
    role_id: null,
  };
  formError.value = '';
  editingStaff.value = null;
};

const toggleRowMenu = (id) => {
  openMenuId.value = openMenuId.value === id ? null : id;
};

const closeRowMenu = () => {
  openMenuId.value = null;
};

const goToView = (staff) => {
  router.push({ name: 'StaffView', params: { id: staff.id } });
};

const openCreateModal = () => {
  resetForm();
  if (staffModalInstance) {
    staffModalInstance.show();
  }
};

const openEditModal = (staff) => {
  editingStaff.value = staff;
  form.value = {
    name: staff.name || '',
    email: staff.email || '',
    password: '',
    role_id: Array.isArray(staff.roles) && staff.roles[0] ? staff.roles[0].id : null,
  };
  formError.value = '';
  if (staffModalInstance) {
    staffModalInstance.show();
  }
};

const saveStaff = async () => {
  saving.value = true;
  formError.value = '';
  try {
    if (editingStaff.value) {
      await api.put(`/staff/${editingStaff.value.id}`, {
        name: form.value.name,
        role_id: form.value.role_id,
      });
    } else {
      await api.post('/staff', {
        name: form.value.name,
        email: form.value.email,
        password: form.value.password,
        role_id: form.value.role_id,
      });
    }
    if (staffModalInstance) {
      staffModalInstance.hide();
    }
    await fetchStaff();
  } catch (error) {
    formError.value = error.response?.data?.message || 'Failed to save staff';
    console.error('Failed to save staff:', error);
  } finally {
    saving.value = false;
  }
};

const confirmDelete = async (staff) => {
  if (!window.confirm(`Delete staff ${staff.name}?`)) {
    return;
  }
  try {
    await api.delete(`/staff/${staff.id}`);
    await fetchStaff();
  } catch (error) {
    console.error('Failed to delete staff:', error);
  }
};
</script>
