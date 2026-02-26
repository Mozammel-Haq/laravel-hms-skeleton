<template>
  <div class="container-fluid">
    <div class="card p-3 mb-3 border-0 shadow-sm">
      <div class="d-flex justify-content-between align-items-center bg-primary-subtle text-primary px-4 pt-3 pb-3 rounded-3 mb-0">
        <div>
          <h5 class="fw-bold mb-1 text-primary">Overtime</h5>
          <nav aria-label="breadcrumb">
            <ol class="breadcrumb breadcrumb-dots mb-0 text-muted small">
              <li class="breadcrumb-item">
                <router-link to="/">Dashboard</router-link>
              </li>
              <li class="breadcrumb-item">Attendance</li>
              <li class="breadcrumb-item active" aria-current="page">Overtime</li>
            </ol>
          </nav>
        </div>
        <div class="d-flex gap-2 align-items-center">
          <input
            v-model="filters.from_date"
            type="date"
            class="form-control form-control-sm w-auto"
            @change="loadOvertime"
          />
          <input
            v-model="filters.to_date"
            type="date"
            class="form-control form-control-sm w-auto"
            @change="loadOvertime"
          />
          <button
            class="btn btn-primary btn-sm"
            @click="openModal"
          >
            <i class="ti ti-plus me-1"></i> Log Overtime
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
                <th>Employee</th>
                <th>Hours</th>
                <th>Multiplier</th>
                <th>Reason</th>
                <th>Status</th>
                <th class="text-end pe-4">Actions</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="row in records" :key="row.id">
                <td class="ps-4">{{ formatDate(row.date) }}</td>
                <td>
                  <div class="fw-semibold">
                    {{ row.user?.name || 'User #' + row.user_id }}
                  </div>
                  <div class="text-muted fs-12">
                    {{ row.user?.email || '' }}
                  </div>
                </td>
                <td>{{ row.hours }}</td>
                <td>x{{ row.multiplier }}</td>
                <td>{{ row.reason || '-' }}</td>
                <td>
                  <span
                    class="badge"
                    :class="statusClass(row.status)"
                  >
                    {{ (row.status || 'approved').toUpperCase() }}
                  </span>
                </td>
                <td class="text-end pe-4">
                  <div class="dropdown">
                    <button
                      type="button"
                      class="btn btn-sm btn-light btn-icon"
                      @click="toggleRowMenu(row.id)"
                    >
                      <i class="ti ti-dots-vertical"></i>
                    </button>
                    <ul
                      class="dropdown-menu dropdown-menu-end shadow-sm border-0"
                      :class="{ show: openMenuId === row.id }"
                    >
                      <li>
                        <a
                          href="#"
                          class="dropdown-item"
                          @click.prevent="() => { closeRowMenu(); editRow(row); }"
                        >
                          <i class="ti ti-edit me-2"></i>Edit
                        </a>
                      </li>
                      <li>
                        <a
                          href="#"
                          class="dropdown-item text-danger"
                          @click.prevent="() => { closeRowMenu(); deleteRow(row); }"
                        >
                          <i class="ti ti-trash me-2"></i>Delete
                        </a>
                      </li>
                    </ul>
                  </div>
                </td>
              </tr>
              <tr v-if="!loading && records.length === 0">
                <td colspan="7" class="text-center py-4 text-muted">
                  No overtime records in the selected range
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>

    <div v-if="showModal">
      <div class="modal-backdrop fade show"></div>
      <div class="modal fade show d-block" tabindex="-1">
        <div class="modal-dialog modal-lg modal-dialog-centered">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title">
                {{ editing ? 'Edit Overtime' : 'Request Overtime' }}
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
                    <select v-model.number="form.user_id" class="form-select" :disabled="editing">
                      <option value="" disabled>Select employee</option>
                      <option v-for="staff in staffOptions" :key="staff.id" :value="staff.id">
                        {{ staff.name }} ({{ staff.email }})
                      </option>
                    </select>
                  </div>
                  <div class="col-md-6 mb-3">
                    <label class="form-label">Date</label>
                    <input v-model="form.overtime_date" type="date" class="form-control" :disabled="editing" />
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-4 mb-3">
                    <label class="form-label">Hours</label>
                    <input v-model.number="form.hours" type="number" step="0.5" min="0.5" class="form-control" />
                  </div>
                  <div class="col-md-4 mb-3">
                    <label class="form-label">Rate Multiplier</label>
                    <input v-model.number="form.rate_multiplier" type="number" step="0.1" min="1" class="form-control" />
                  </div>
                  <div class="col-md-4 mb-3">
                    <label class="form-label">Status</label>
                    <select v-model="form.status" class="form-select">
                      <option value="pending">Pending</option>
                      <option value="approved">Approved</option>
                      <option value="rejected">Rejected</option>
                      <option value="paid">Paid</option>
                    </select>
                  </div>
                </div>
                <div class="mb-3">
                  <label class="form-label">Reason</label>
                  <textarea v-model="form.reason" rows="2" class="form-control"></textarea>
                </div>
              </form>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-light" @click="closeModal">Cancel</button>
              <button type="button" class="btn btn-primary" @click="save" :disabled="saving">
                <span v-if="saving" class="spinner-border spinner-border-sm me-1"></span>
                {{ editing ? 'Save Changes' : 'Submit Request' }}
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { onMounted, ref } from 'vue';
import api from '../services/api';
import { useToastStore } from '../store/toastStore';

const toast = useToastStore();

const today = new Date();
const startOfMonth = new Date(today.getFullYear(), today.getMonth(), 1);
const endOfMonth = new Date(today.getFullYear(), today.getMonth() + 1, 0);

const formatIso = (d) => d.toISOString().slice(0, 10);

const normalizeDateForPeriod = (value) => {
  if (!value) return null;
  const v = String(value).slice(0, 10);
  return `${v}T00:00:00.000000Z`;
};

const filters = ref({
  from_date: formatIso(startOfMonth),
  to_date: formatIso(endOfMonth),
});

const records = ref([]);
const staffOptions = ref([]);
const loading = ref(false);
const showModal = ref(false);
const saving = ref(false);
const editing = ref(false);
const currentId = ref(null);
const formError = ref('');

const openMenuId = ref(null);

const form = ref({
  user_id: '',
  date: formatIso(today),
  hours: 2,
  multiplier: 1.5,
  reason: '',
  status: 'approved',
});

const formatDate = (value) => {
  if (!value) return 'N/A';
  const d = new Date(value);
  if (Number.isNaN(d.getTime())) return value;
  return d.toLocaleDateString();
};

const statusClass = (status) => {
  const s = (status || 'approved').toLowerCase();
  if (s === 'pending') return 'bg-warning-subtle text-warning';
  if (s === 'rejected') return 'bg-danger-subtle text-danger';
  return 'bg-success-subtle text-success';
};

const toggleRowMenu = (id) => {
  openMenuId.value = openMenuId.value === id ? null : id;
};

const closeRowMenu = () => {
  openMenuId.value = null;
};

const loadStaff = async () => {
  try {
    const res = await api.get('/staff', { params: { per_page: 200 } });
    const payload = res.data || {};
    const page = payload.data || {};
    staffOptions.value = page.data || payload.data || [];
  } catch (e) {
    console.error('Failed to load staff list', e);
    toast.error('Failed to load staff list');
  }
};

const loadOvertime = async () => {
  loading.value = true;
  try {
    const from = normalizeDateForPeriod(filters.value.from_date);
    const to = normalizeDateForPeriod(filters.value.to_date);
    const res = await api.get('/overtimes', {
      params: {
        from_date: from,
        to_date: to,
        per_page: 200,
      },
    });
    const payload = res.data || {};
    const page = payload.data || {};
    records.value = page.data || payload.data || [];
  } catch (e) {
    console.error('Failed to load overtime records', e);
    toast.error('Failed to load overtime records');
  } finally {
    loading.value = false;
  }
};

const openModal = () => {
  editing.value = false;
  currentId.value = null;
  formError.value = '';
  form.value = {
    user_id: '',
    date: formatIso(today),
    hours: 2,
    multiplier: 1.5,
    reason: '',
    status: 'approved',
  };
  showModal.value = true;
};

const editRow = (row) => {
  editing.value = true;
  currentId.value = row.id;
  formError.value = '';
  form.value = {
    user_id: row.user_id,
    date: row.date?.slice(0, 10) || formatIso(today),
    hours: row.hours,
    multiplier: row.multiplier,
    reason: row.reason || '',
    status: row.status || 'approved',
  };
  showModal.value = true;
};

const closeModal = () => {
  showModal.value = false;
  editing.value = false;
  currentId.value = null;
};

const save = async () => {
  saving.value = true;
  formError.value = '';
  try {
    const payload = {
      user_id: form.value.user_id,
      date: form.value.date,
      hours: form.value.hours,
      multiplier: form.value.multiplier,
      reason: form.value.reason || null,
      status: form.value.status,
    };
    if (editing.value && currentId.value) {
      await api.put(`/overtimes/${currentId.value}`, payload);
      toast.success('Overtime updated');
    } else {
      await api.post('/overtimes', payload);
      toast.success('Overtime saved');
    }
    closeModal();
    loadOvertime();
  } catch (e) {
    console.error('Failed to save overtime', e);
    if (e.response && e.response.data && e.response.data.message) {
      formError.value = e.response.data.message;
    } else {
      formError.value = 'Failed to save overtime';
    }
    toast.error(formError.value);
  } finally {
    saving.value = false;
  }
};

const deleteRow = async (row) => {
  if (!row.id) return;
  try {
    await api.delete(`/overtimes/${row.id}`);
    loadOvertime();
  } catch (e) {
    console.error('Failed to delete overtime record', e);
    toast.error('Failed to delete overtime record');
  }
};

onMounted(async () => {
  await Promise.all([loadStaff(), loadOvertime()]);
});
</script>
