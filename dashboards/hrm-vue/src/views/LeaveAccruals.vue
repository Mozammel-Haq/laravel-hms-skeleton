<template>
  <div class="container-fluid">
    <div class="card p-3 mb-3 border-0 shadow-sm">
      <div class="d-flex justify-content-between align-items-center bg-primary-subtle text-primary px-4 pt-3 pb-3 rounded-3 mb-0">
        <div>
          <h5 class="fw-bold mb-1 text-primary">Leave Balances</h5>
          <nav aria-label="breadcrumb">
            <ol class="breadcrumb breadcrumb-dots mb-0 text-muted small">
              <li class="breadcrumb-item">
                <router-link to="/">Dashboard</router-link>
              </li>
              <li class="breadcrumb-item">Leaves</li>
              <li class="breadcrumb-item active" aria-current="page">Leave Balances</li>
            </ol>
          </nav>
        </div>
        <div class="d-flex gap-2 align-items-center">
          <select v-model.number="filters.year" class="form-select form-select-sm w-auto" @change="loadBalances">
            <option v-for="y in yearOptions" :key="y" :value="y">{{ y }}</option>
          </select>
          <button v-if="canManage" class="btn btn-primary btn-sm" @click="openModal">
            <i class="ti ti-plus me-1"></i> Adjust Balance
          </button>
        </div>
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
                <th class="ps-4">Employee</th>
                <th>Leave Type</th>
                <th>Year</th>
                <th>Opening</th>
                <th>Accrued</th>
                <th>Used</th>
                <th>Balance</th>
                <th>Status</th>
                <th class="text-end pe-4" v-if="canManage">Actions</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="item in balances" :key="item.id">
                <td class="ps-4">
                  <div class="fw-semibold">
                    {{ item.user?.name || 'User #' + item.user_id }}
                  </div>
                  <div class="text-muted fs-12">
                    {{ item.user?.email || '' }}
                  </div>
                </td>
                <td>{{ item.leave_type }}</td>
                <td>{{ item.year }}</td>
                <td>{{ item.opening_balance }}</td>
                <td>{{ item.accrued }}</td>
                <td>{{ item.used }}</td>
                <td>
                  <span
                    class="badge"
                    :class="item.closing_balance > 0 ? 'bg-success-subtle text-success' : 'bg-warning-subtle text-warning'"
                  >
                    {{ item.closing_balance }}
                  </span>
                </td>
                <td>
                  <span
                    class="badge"
                    :class="item.status === 'active' ? 'bg-success-subtle text-success' : 'bg-secondary-subtle text-secondary'"
                  >
                    {{ (item.status || 'active').toUpperCase() }}
                  </span>
                </td>
                <td class="text-end pe-4" v-if="canManage">
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
                          @click.prevent="() => { closeRowMenu(); editBalance(item); }"
                        >
                          <i class="ti ti-edit me-2"></i>Edit
                        </a>
                      </li>
                      <li>
                        <a
                          href="#"
                          class="dropdown-item text-danger"
                          @click.prevent="() => { closeRowMenu(); deactivateBalance(item); }"
                          :class="{ disabled: item.status === 'inactive' }"
                        >
                          <i class="ti ti-archive me-2"></i>Archive
                        </a>
                      </li>
                    </ul>
                  </div>
                </td>
              </tr>
              <tr v-if="!loading && balances.length === 0">
                <td colspan="9" class="text-center py-4 text-muted">
                  No leave balances found
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>

    <div class="modal fade show" tabindex="-1" style="display: block" v-if="showModal">
      <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">
              {{ editingBalance ? 'Edit Leave Balance' : 'New Leave Balance' }}
            </h5>
            <button type="button" class="btn-close" @click="closeModal"></button>
          </div>
          <div class="modal-body">
            <div v-if="formError" class="alert alert-danger py-2 mb-3">
              {{ formError }}
            </div>
            <form @submit.prevent>
              <div class="row">
                <div class="col-md-6 mb-3">
                  <label class="form-label">Employee</label>
                  <select
                    v-model.number="form.user_id"
                    class="form-select"
                    :disabled="!!editingBalance"
                  >
                    <option value="" disabled>Select employee</option>
                    <option
                      v-for="staff in staffOptions"
                      :key="staff.id"
                      :value="staff.id"
                    >
                      {{ staff.name }} ({{ staff.email }})
                    </option>
                  </select>
                </div>
                <div class="col-md-3 mb-3">
                  <label class="form-label">Leave Type</label>
                  <select
                    v-model="form.leave_type"
                    class="form-select"
                    :disabled="!!editingBalance"
                  >
                    <option value="" disabled>Select type</option>
                    <option
                      v-for="t in leaveTypes"
                      :key="t.id"
                      :value="t.code || t.name"
                    >
                      {{ t.name }}
                    </option>
                  </select>
                </div>
                <div class="col-md-3 mb-3">
                  <label class="form-label">Year</label>
                  <select v-model.number="form.year" class="form-select">
                    <option v-for="y in yearOptions" :key="y" :value="y">
                      {{ y }}
                    </option>
                  </select>
                </div>
              </div>
              <div class="row">
                <div class="col-md-3 mb-3">
                  <label class="form-label">Opening Balance</label>
                  <input
                    v-model.number="form.opening_balance"
                    type="number"
                    step="0.5"
                    min="0"
                    max="365"
                    class="form-control"
                  />
                </div>
                <div class="col-md-3 mb-3">
                  <label class="form-label">Accrued</label>
                  <input
                    v-model.number="form.accrued"
                    type="number"
                    step="0.5"
                    min="0"
                    max="365"
                    class="form-control"
                  />
                </div>
                <div class="col-md-3 mb-3">
                  <label class="form-label">Used</label>
                  <input
                    v-model.number="form.used"
                    type="number"
                    step="0.5"
                    min="0"
                    max="365"
                    class="form-control"
                  />
                </div>
                <div class="col-md-3 mb-3">
                  <label class="form-label">Closing Balance</label>
                  <input
                    v-model.number="form.closing_balance"
                    type="number"
                    step="0.5"
                    min="0"
                    max="365"
                    class="form-control"
                  />
                  <div class="form-text">
                    If empty, calculated as opening + accrued - used
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-md-3 mb-3">
                  <label class="form-label">Status</label>
                  <select v-model="form.status" class="form-select">
                    <option value="active">Active</option>
                    <option value="inactive">Inactive</option>
                  </select>
                </div>
              </div>
            </form>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-light" @click="closeModal">Cancel</button>
            <button type="button" class="btn btn-primary" @click="saveBalance" :disabled="saving">
              <span v-if="saving" class="spinner-border spinner-border-sm me-1"></span>
              {{ editingBalance ? 'Save Changes' : 'Save Balance' }}
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

const balances = ref([]);
const leaveTypes = ref([]);
const staffOptions = ref([]);
const loading = ref(false);
const saving = ref(false);
const showModal = ref(false);
const editingBalance = ref(null);
const formError = ref('');

const openMenuId = ref(null);

const now = new Date();
const filters = ref({
  year: now.getFullYear(),
});

const form = ref({
  user_id: '',
  leave_type: '',
  year: now.getFullYear(),
  opening_balance: 0,
  accrued: 0,
  used: 0,
  closing_balance: null,
  status: 'active',
});

const abilities = computed(() =>
  Array.isArray(auth.user?.abilities) ? auth.user.abilities : []
);
const has = (perm) => abilities.value.includes(perm);

const canManage = computed(() => has('manage_leaves'));

const yearOptions = computed(() => {
  const current = now.getFullYear();
  const years = [];
  for (let y = current - 1; y <= current + 2; y += 1) {
    years.push(y);
  }
  return years;
});

const toggleRowMenu = (id) => {
  openMenuId.value = openMenuId.value === id ? null : id;
};

const closeRowMenu = () => {
  openMenuId.value = null;
};

const loadBalances = async () => {
  loading.value = true;
  try {
    const res = await api.get('/leave-balances', {
      params: {
        year: filters.value.year,
        per_page: 200,
      },
    });
    const payload = res.data || {};
    const page = payload.data || {};
    balances.value = page.data || payload.data || [];
  } catch (e) {
    console.error('Failed to load leave balances', e);
  } finally {
    loading.value = false;
  }
};

const loadMeta = async () => {
  try {
    const [typesRes, staffRes] = await Promise.all([
      api.get('/leave-types', { params: { status: 'active' } }),
      canManage.value ? api.get('/staff', { params: { per_page: 200 } }) : Promise.resolve({ data: { data: [] } }),
    ]);

    const typesPayload = typesRes.data || {};
    leaveTypes.value = typesPayload.data || [];

    if (canManage.value) {
      const staffPayload = staffRes.data || {};
      const staffPage = staffPayload.data || {};
      staffOptions.value = staffPage.data || staffPayload.data || [];
    }
  } catch (e) {
    console.error('Failed to load leave meta', e);
  }
};

const openModal = () => {
  editingBalance.value = null;
  formError.value = '';
  form.value = {
    user_id: '',
    leave_type: '',
    year: filters.value.year,
    opening_balance: 0,
    accrued: 0,
    used: 0,
    closing_balance: null,
    status: 'active',
  };
  showModal.value = true;
};

const editBalance = (item) => {
  editingBalance.value = item;
  formError.value = '';
  form.value = {
    user_id: item.user_id,
    leave_type: item.leave_type,
    year: item.year,
    opening_balance: item.opening_balance,
    accrued: item.accrued,
    used: item.used,
    closing_balance: item.closing_balance,
    status: item.status || 'active',
  };
  showModal.value = true;
};

const closeModal = () => {
  showModal.value = false;
  editingBalance.value = null;
};

const saveBalance = async () => {
  if (!canManage.value) return;
  saving.value = true;
  formError.value = '';
  try {
    const payload = {
      user_id: form.value.user_id,
      leave_type: form.value.leave_type,
      year: form.value.year,
      opening_balance: form.value.opening_balance,
      accrued: form.value.accrued,
      used: form.value.used,
      closing_balance: form.value.closing_balance,
      status: form.value.status,
    };

    if (editingBalance.value) {
      await api.put(`/leave-balances/${editingBalance.value.id}`, payload);
    } else {
      await api.post('/leave-balances', payload);
    }

    closeModal();
    loadBalances();
  } catch (e) {
    console.error('Failed to save leave balance', e);
    if (e.response && e.response.data && e.response.data.message) {
      formError.value = e.response.data.message;
    } else {
      formError.value = 'Failed to save leave balance';
    }
  } finally {
    saving.value = false;
  }
};

const deactivateBalance = async (item) => {
  try {
    await api.put(`/leave-balances/${item.id}`, { status: 'inactive' });
    loadBalances();
  } catch (e) {
    console.error('Failed to deactivate leave balance', e);
  }
};

onMounted(async () => {
  await Promise.all([loadMeta(), loadBalances()]);
});
</script>
