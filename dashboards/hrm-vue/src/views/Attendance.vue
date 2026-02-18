<template>
  <div class="container-fluid">
    <div class="card p-3 mb-3 border-0 shadow-sm">
      <div class="d-flex justify-content-between align-items-center bg-primary-subtle text-primary px-4 pt-3 pb-3 rounded-3 mb-0">
        <div>
          <h5 class="fw-bold mb-1 text-primary">Daily Attendance</h5>
          <nav aria-label="breadcrumb">
            <ol class="breadcrumb breadcrumb-dots mb-0 text-muted small">
              <li class="breadcrumb-item">
                <router-link to="/">Dashboard</router-link>
              </li>
              <li class="breadcrumb-item">Attendance</li>
              <li class="breadcrumb-item active" aria-current="page">Daily Attendance</li>
            </ol>
          </nav>
        </div>
        <div class="d-flex gap-2 align-items-center">
          <input
            v-model="selectedDate"
            type="date"
            class="form-control form-control-sm w-auto"
            @change="loadAttendance"
          />
          <button
            v-if="canManage"
            class="btn btn-primary btn-sm"
            @click="openModal"
          >
            <i class="ti ti-plus me-1"></i> Add Manual Entry
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
                <th>Check In</th>
                <th>Check Out</th>
                <th>Worked Hours</th>
                <th>Status</th>
                <th>Flags</th>
                <th class="text-end pe-4" v-if="canManage">Actions</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="row in records" :key="row.id">
                <td class="ps-4">
                  <div class="fw-semibold">
                    {{ row.user?.name || 'User #' + row.user_id }}
                  </div>
                  <div class="text-muted fs-12">
                    {{ row.user?.email || '' }}
                  </div>
                </td>
                <td>{{ row.check_in_time || '-' }}</td>
                <td>{{ row.check_out_time || '-' }}</td>
                <td>{{ row.worked_hours?.toFixed ? row.worked_hours.toFixed(2) : row.worked_hours }}</td>
                <td>
                  <span
                    class="badge"
                    :class="statusClass(row.status)"
                  >
                    {{ (row.status || 'present').toUpperCase() }}
                  </span>
                </td>
                <td>
                  <span
                    v-if="row.is_late"
                    class="badge bg-warning-subtle text-warning me-1"
                  >
                    Late
                  </span>
                  <span
                    v-if="row.is_early_exit"
                    class="badge bg-info-subtle text-info"
                  >
                    Early Exit
                  </span>
                </td>
                <td class="text-end pe-4" v-if="canManage">
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
                  No attendance records for the selected date
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
              {{ editing ? 'Edit Attendance' : 'Add Attendance' }}
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
                  <label class="form-label">Date</label>
                  <input
                    v-model="form.attendance_date"
                    type="date"
                    class="form-control"
                  />
                </div>
                <div class="col-md-3 mb-3">
                  <label class="form-label">Status</label>
                  <select v-model="form.status" class="form-select">
                    <option value="present">Present</option>
                    <option value="absent">Absent</option>
                    <option value="leave">On Leave</option>
                    <option value="half-day">Half Day</option>
                    <option value="holiday">Holiday</option>
                  </select>
                </div>
              </div>
              <div class="row">
                <div class="col-md-3 mb-3">
                  <label class="form-label">Check In</label>
                  <input
                    v-model="form.check_in_time"
                    type="time"
                    class="form-control"
                  />
                </div>
                <div class="col-md-3 mb-3">
                  <label class="form-label">Check Out</label>
                  <input
                    v-model="form.check_out_time"
                    type="time"
                    class="form-control"
                  />
                </div>
                <div class="col-md-3 mb-3 d-flex align-items-center">
                  <div class="form-check mt-4">
                    <input
                      v-model="form.is_late"
                      class="form-check-input"
                      type="checkbox"
                      id="isLate"
                    />
                    <label class="form-check-label" for="isLate">
                      Late Arrival
                    </label>
                  </div>
                </div>
                <div class="col-md-3 mb-3 d-flex align-items-center">
                  <div class="form-check mt-4">
                    <input
                      v-model="form.is_early_exit"
                      class="form-check-input"
                      type="checkbox"
                      id="isEarlyExit"
                    />
                    <label class="form-check-label" for="isEarlyExit">
                      Early Exit
                    </label>
                  </div>
                </div>
              </div>
            </form>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-light" @click="closeModal">Cancel</button>
            <button
              type="button"
              class="btn btn-primary"
              @click="save"
              :disabled="saving"
            >
              <span v-if="saving" class="spinner-border spinner-border-sm me-1"></span>
              {{ editing ? 'Save Changes' : 'Save Attendance' }}
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
import { useToastStore } from '../store/toastStore';

const auth = useAuthStore();
const toast = useToastStore();

const now = new Date();
const selectedDate = ref(now.toISOString().slice(0, 10));

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
  attendance_date: selectedDate.value,
  check_in_time: '',
  check_out_time: '',
  status: 'present',
  is_late: false,
  is_early_exit: false,
});

const abilities = computed(() =>
  Array.isArray(auth.user?.abilities) ? auth.user.abilities : []
);
const has = (perm) => abilities.value.includes(perm);

const canManage = computed(() => has('view_staff'));

const statusClass = (status) => {
  const s = (status || 'present').toLowerCase();
  if (s === 'absent') return 'bg-danger-subtle text-danger';
  if (s === 'leave' || s === 'holiday') return 'bg-info-subtle text-info';
  if (s === 'half-day') return 'bg-warning-subtle text-warning';
  return 'bg-success-subtle text-success';
};

const toggleRowMenu = (id) => {
  openMenuId.value = openMenuId.value === id ? null : id;
};

const closeRowMenu = () => {
  openMenuId.value = null;
};

const loadStaff = async () => {
  if (!canManage.value) return;
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

const loadAttendance = async () => {
  loading.value = true;
  try {
    const res = await api.get('/attendance', {
      params: {
        date: selectedDate.value,
        per_page: 200,
      },
    });
    const payload = res.data || {};
    const page = payload.data || {};
    records.value = page.data || payload.data || [];
  } catch (e) {
    console.error('Failed to load attendance', e);
    toast.error('Failed to load attendance');
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
    attendance_date: selectedDate.value,
    check_in_time: '',
    check_out_time: '',
    status: 'present',
    is_late: false,
    is_early_exit: false,
  };
  showModal.value = true;
};

const editRow = (row) => {
  editing.value = true;
  currentId.value = row.id;
  formError.value = '';
  form.value = {
    user_id: row.user_id,
    attendance_date: row.attendance_date?.slice(0, 10) || selectedDate.value,
    check_in_time: row.check_in_time || '',
    check_out_time: row.check_out_time || '',
    status: row.status || 'present',
    is_late: !!row.is_late,
    is_early_exit: !!row.is_early_exit,
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
      attendance_date: form.value.attendance_date,
      check_in_time: form.value.check_in_time || null,
      check_out_time: form.value.check_out_time || null,
      status: form.value.status,
      is_late: form.value.is_late,
      is_early_exit: form.value.is_early_exit,
    };
    if (editing.value && currentId.value) {
      await api.put(`/attendance/${currentId.value}`, payload);
      toast.success('Attendance updated');
    } else {
      await api.post('/attendance', payload);
      toast.success('Attendance saved');
    }
    closeModal();
    loadAttendance();
  } catch (e) {
    console.error('Failed to save attendance', e);
    if (e.response && e.response.data && e.response.data.message) {
      formError.value = e.response.data.message;
    } else {
      formError.value = 'Failed to save attendance';
    }
    toast.error(formError.value);
  } finally {
    saving.value = false;
  }
};

const deleteRow = async (row) => {
  if (!row.id) return;
  try {
    await api.delete(`/attendance/${row.id}`);
    loadAttendance();
  } catch (e) {
    console.error('Failed to delete attendance', e);
    toast.error('Failed to delete attendance');
  }
};

onMounted(async () => {
  await Promise.all([loadStaff(), loadAttendance()]);
});
</script>
