<template>
  <div class="container-fluid">
    <div class="card p-3 mb-3 border-0 shadow-sm">
      <div class="d-flex justify-content-between align-items-center bg-primary-subtle text-primary px-4 pt-3 pb-3 rounded-3 mb-0">
        <div>
          <h5 class="fw-bold mb-1 text-primary">Leave Types</h5>
          <nav aria-label="breadcrumb">
            <ol class="breadcrumb breadcrumb-dots mb-0 text-muted small">
              <li class="breadcrumb-item">
                <router-link to="/">Dashboard</router-link>
              </li>
              <li class="breadcrumb-item">Leaves</li>
              <li class="breadcrumb-item active" aria-current="page">Leave Types</li>
            </ol>
          </nav>
        </div>
        <button v-if="canManage" class="btn btn-primary btn-sm" @click="openModal">
          <i class="ti ti-plus me-1"></i> New Leave Type
        </button>
      </div>
    </div>

    <div class="card border-0 shadow-sm">
      <div class="card-body p-0">
        <div v-if="loading" class="text-center py-4">
          <div class="spinner-border text-primary" role="status">
            <span class="visually-hidden">Loading...</span>
          </div>
        </div>
        <div v-else class="table-responsive">
          <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
              <tr>
                <th class="ps-4">Name</th>
                <th>Code</th>
                <th>Default Days</th>
                <th>Carry Forward</th>
                <th>Status</th>
                <th class="text-end pe-4" v-if="canManage">Actions</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="type in leaveTypes" :key="type.id">
                <td class="ps-4">
                  <div class="fw-semibold">{{ type.name }}</div>
                </td>
                <td>
                  <span class="text-muted">{{ type.code || '-' }}</span>
                </td>
                <td>
                  <span>{{ type.default_days ?? 0 }}</span>
                </td>
                <td>
                  <span
                    class="badge"
                    :class="type.carry_forward ? 'bg-success-subtle text-success' : 'bg-secondary-subtle text-secondary'"
                  >
                    {{ type.carry_forward ? 'YES' : 'NO' }}
                  </span>
                </td>
                <td>
                  <span
                    class="badge"
                    :class="type.status === 'active' ? 'bg-success-subtle text-success' : 'bg-secondary-subtle text-secondary'"
                  >
                    {{ (type.status || 'active').toUpperCase() }}
                  </span>
                </td>
                <td class="text-end pe-4" v-if="canManage">
                  <div class="dropdown">
                    <button
                      type="button"
                      class="btn btn-sm btn-light btn-icon"
                      @click="toggleRowMenu(type.id)"
                    >
                      <i class="ti ti-dots-vertical"></i>
                    </button>
                    <ul
                      class="dropdown-menu dropdown-menu-end shadow-sm border-0"
                      :class="{ show: openMenuId === type.id }"
                    >
                      <li>
                        <a
                          href="#"
                          class="dropdown-item"
                          @click.prevent="() => { closeRowMenu(); editType(type); }"
                        >
                          <i class="ti ti-edit me-2"></i>Edit
                        </a>
                      </li>
                      <li>
                        <a
                          href="#"
                          class="dropdown-item text-danger"
                          @click.prevent="() => { closeRowMenu(); archiveType(type); }"
                          :class="{ disabled: type.status === 'inactive' }"
                        >
                          <i class="ti ti-archive me-2"></i>Archive
                        </a>
                      </li>
                    </ul>
                  </div>
                </td>
              </tr>
              <tr v-if="!loading && leaveTypes.length === 0">
                <td colspan="6" class="text-center py-4 text-muted">
                  No leave types defined yet
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>

    <div class="modal fade show" tabindex="-1" style="display: block" v-if="showModal">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">
              {{ editingType ? 'Edit Leave Type' : 'New Leave Type' }}
            </h5>
            <button type="button" class="btn-close" @click="closeModal"></button>
          </div>
          <div class="modal-body">
            <div v-if="formError" class="alert alert-danger py-2 mb-3">
              {{ formError }}
            </div>
            <form @submit.prevent>
              <div class="mb-3">
                <label class="form-label">Name</label>
                <input v-model="form.name" type="text" class="form-control" required />
              </div>
              <div class="mb-3">
                <label class="form-label">Code</label>
                <input v-model="form.code" type="text" class="form-control" placeholder="Optional short code" />
              </div>
              <div class="mb-3">
                <label class="form-label">Default Days Per Year</label>
                <input
                  v-model.number="form.default_days"
                  type="number"
                  step="0.5"
                  min="0"
                  max="365"
                  class="form-control"
                />
              </div>
              <div class="form-check mb-3">
                <input v-model="form.carry_forward" class="form-check-input" type="checkbox" id="carryForward" />
                <label class="form-check-label" for="carryForward">
                  Allow carry forward
                </label>
              </div>
              <div class="mb-3">
                <label class="form-label">Status</label>
                <select v-model="form.status" class="form-select">
                  <option value="active">Active</option>
                  <option value="inactive">Inactive</option>
                </select>
              </div>
            </form>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-light" @click="closeModal">Cancel</button>
            <button type="button" class="btn btn-primary" @click="saveType" :disabled="saving">
              <span v-if="saving" class="spinner-border spinner-border-sm me-1"></span>
              {{ editingType ? 'Save Changes' : 'Save Leave Type' }}
            </button>
          </div>
        </div>
      </div>
    </div>
    <div class="modal-backdrop fade show" v-if="showModal"></div>
  </div>
</template>

<script setup>
import { computed, onMounted, ref } from 'vue';
import api from '../services/api';
import { useAuthStore } from '../store/authStore';

const auth = useAuthStore();

const leaveTypes = ref([]);
const loading = ref(false);
const showModal = ref(false);
const saving = ref(false);
const editingType = ref(null);
const formError = ref('');

const openMenuId = ref(null);

const form = ref({
  name: '',
  code: '',
  default_days: 0,
  carry_forward: false,
  status: 'active',
});

const abilities = computed(() =>
  Array.isArray(auth.user?.abilities) ? auth.user.abilities : []
);
const has = (perm) => abilities.value.includes(perm);

const canManage = computed(() => has('manage_leaves'));

const toggleRowMenu = (id) => {
  openMenuId.value = openMenuId.value === id ? null : id;
};

const closeRowMenu = () => {
  openMenuId.value = null;
};

const loadTypes = async () => {
  loading.value = true;
  try {
    const res = await api.get('/leave-types');
    const payload = res.data || {};
    leaveTypes.value = payload.data || [];
  } catch (e) {
    console.error('Failed to load leave types', e);
  } finally {
    loading.value = false;
  }
};

const openModal = () => {
  editingType.value = null;
  formError.value = '';
  form.value = {
    name: '',
    code: '',
    default_days: 0,
    carry_forward: false,
    status: 'active',
  };
  showModal.value = true;
};

const editType = (type) => {
  editingType.value = type;
  formError.value = '';
  form.value = {
    name: type.name || '',
    code: type.code || '',
    default_days: type.default_days ?? 0,
    carry_forward: !!type.carry_forward,
    status: type.status || 'active',
  };
  showModal.value = true;
};

const closeModal = () => {
  showModal.value = false;
  editingType.value = null;
};

const saveType = async () => {
  saving.value = true;
  formError.value = '';
  try {
    const payload = {
      name: form.value.name,
      code: form.value.code || null,
      default_days: form.value.default_days,
      carry_forward: form.value.carry_forward,
      status: form.value.status,
    };
    if (editingType.value) {
      await api.put(`/leave-types/${editingType.value.id}`, payload);
    } else {
      await api.post('/leave-types', payload);
    }
    closeModal();
    loadTypes();
  } catch (e) {
    console.error('Failed to save leave type', e);
    if (e.response && e.response.data && e.response.data.message) {
      formError.value = e.response.data.message;
    } else {
      formError.value = 'Failed to save leave type';
    }
  } finally {
    saving.value = false;
  }
};

const archiveType = async (type) => {
  try {
    await api.put(`/leave-types/${type.id}`, { status: 'inactive' });
    loadTypes();
  } catch (e) {
    console.error('Failed to archive leave type', e);
  }
};

onMounted(() => {
  loadTypes();
});
</script>
