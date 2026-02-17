<template>
  <div class="container-fluid">
    <div class="card p-3 mb-3 border-0 shadow-sm">
      <div class="d-flex justify-content-between align-items-center bg-primary-subtle text-primary px-4 pt-3 pb-3 rounded-3 mb-0">
        <div>
          <h5 class="fw-bold mb-1 text-primary">Holidays</h5>
          <nav aria-label="breadcrumb">
            <ol class="breadcrumb breadcrumb-dots mb-0 text-muted small">
              <li class="breadcrumb-item">
                <router-link to="/">Dashboard</router-link>
              </li>
              <li class="breadcrumb-item">Attendance</li>
              <li class="breadcrumb-item active" aria-current="page">Holidays</li>
            </ol>
          </nav>
        </div>
        <div class="d-flex gap-2">
          <select v-model.number="selectedYear" class="form-select form-select-sm w-auto">
            <option v-for="y in yearOptions" :key="y" :value="y">{{ y }}</option>
          </select>
          <button class="btn btn-primary btn-sm" v-if="canManage" @click="openModal">
            <i class="ti ti-plus me-2"></i> New Holiday
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
                <th class="ps-4">Date</th>
                <th>Name</th>
                <th>Type</th>
                <th>Status</th>
                <th class="text-end pe-4" v-if="canManage">Actions</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="h in holidays" :key="h.id">
                <td class="ps-4">
                  <div class="fw-semibold">{{ formatDate(h.date) }}</div>
                </td>
                <td>
                  <div class="fw-semibold">{{ h.name }}</div>
                </td>
                <td>
                  <span class="badge bg-info-subtle text-info">
                    {{ (h.type || 'public').toUpperCase() }}
                  </span>
                </td>
                <td>
                  <span
                    class="badge"
                    :class="h.status === 'active' ? 'bg-success-subtle text-success' : 'bg-secondary-subtle text-secondary'"
                  >
                    {{ (h.status || 'active').toUpperCase() }}
                  </span>
                </td>
                <td class="text-end pe-4" v-if="canManage">
                  <button class="btn btn-sm btn-light me-1" type="button" @click="editHoliday(h)">
                    <i class="ti ti-edit"></i>
                  </button>
                  <button
                    class="btn btn-sm btn-outline-danger"
                    type="button"
                    @click="deactivateHoliday(h)"
                    :disabled="h.status === 'inactive'"
                  >
                    <i class="ti ti-archive"></i>
                  </button>
                </td>
              </tr>
              <tr v-if="!loading && holidays.length === 0">
                <td colspan="5" class="text-center py-4 text-muted">
                  No holidays defined for this year
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
              {{ editingHoliday ? 'Edit Holiday' : 'New Holiday' }}
            </h5>
            <button type="button" class="btn-close" @click="closeModal"></button>
          </div>
          <div class="modal-body">
            <div v-if="formError" class="alert alert-danger py-2 mb-3">
              {{ formError }}
            </div>
            <form @submit.prevent>
              <div class="mb-3">
                <label class="form-label">Date</label>
                <input v-model="form.date" type="date" class="form-control" required />
              </div>
              <div class="mb-3">
                <label class="form-label">Name</label>
                <input v-model="form.name" type="text" class="form-control" required />
              </div>
              <div class="mb-3">
                <label class="form-label">Type</label>
                <select v-model="form.type" class="form-select">
                  <option value="public">Public Holiday</option>
                  <option value="clinic">Clinic Holiday</option>
                  <option value="optional">Optional Holiday</option>
                </select>
              </div>
              <div class="form-check mb-3">
                <input v-model="form.is_full_day" class="form-check-input" type="checkbox" id="isFullDay" />
                <label class="form-check-label" for="isFullDay">
                  Full day
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
            <button type="button" class="btn btn-primary" @click="saveHoliday" :disabled="saving">
              <span v-if="saving" class="spinner-border spinner-border-sm me-1"></span>
              {{ editingHoliday ? 'Save Changes' : 'Save Holiday' }}
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

const holidays = ref([]);
const loading = ref(false);
const showModal = ref(false);
const saving = ref(false);
const editingHoliday = ref(null);
const formError = ref('');

const now = new Date();
const selectedYear = ref(now.getFullYear());

const yearOptions = computed(() => {
  const current = now.getFullYear();
  const years = [];
  for (let y = current - 1; y <= current + 2; y += 1) {
    years.push(y);
  }
  return years;
});

const abilities = computed(() =>
  Array.isArray(auth.user?.abilities) ? auth.user.abilities : []
);
const has = (perm) => abilities.value.includes(perm);

const canManage = computed(() => has('manage_leaves'));

const form = ref({
  date: '',
  name: '',
  type: 'public',
  is_full_day: true,
  status: 'active',
});

const loadHolidays = async () => {
  loading.value = true;
  try {
    const res = await api.get('/holidays', {
      params: {
        year: selectedYear.value,
      },
    });
    const payload = res.data || {};
    holidays.value = payload.data || [];
  } catch (e) {
    console.error('Failed to load holidays', e);
  } finally {
    loading.value = false;
  }
};

const formatDate = (value) => {
  if (!value) return 'N/A';
  const d = new Date(value);
  if (Number.isNaN(d.getTime())) return value;
  return d.toLocaleDateString();
};

const openModal = () => {
  editingHoliday.value = null;
  formError.value = '';
  form.value = {
    date: '',
    name: '',
    type: 'public',
    is_full_day: true,
    status: 'active',
  };
  showModal.value = true;
};

const editHoliday = (holiday) => {
  editingHoliday.value = holiday;
  formError.value = '';
  form.value = {
    date: holiday.date ? holiday.date.slice(0, 10) : '',
    name: holiday.name || '',
    type: holiday.type || 'public',
    is_full_day: !!holiday.is_full_day,
    status: holiday.status || 'active',
  };
  showModal.value = true;
};

const closeModal = () => {
  showModal.value = false;
  editingHoliday.value = null;
};

const saveHoliday = async () => {
  saving.value = true;
  formError.value = '';
  try {
    const payload = {
      date: form.value.date,
      name: form.value.name,
      type: form.value.type,
      is_full_day: form.value.is_full_day,
      status: form.value.status,
    };
    if (editingHoliday.value) {
      await api.put(`/holidays/${editingHoliday.value.id}`, payload);
    } else {
      await api.post('/holidays', payload);
    }
    closeModal();
    loadHolidays();
  } catch (e) {
    console.error('Failed to save holiday', e);
    if (e.response && e.response.data && e.response.data.message) {
      formError.value = e.response.data.message;
    } else {
      formError.value = 'Failed to save holiday';
    }
  } finally {
    saving.value = false;
  }
};

const deactivateHoliday = async (holiday) => {
  try {
    await api.put(`/holidays/${holiday.id}`, { status: 'inactive' });
    loadHolidays();
  } catch (e) {
    console.error('Failed to deactivate holiday', e);
  }
};

onMounted(() => {
  loadHolidays();
});
</script>

