<template>
  <div class="leave-approvals-page">
    <div class="card p-3 mb-3 border-0 shadow-sm">
      <div class="d-flex justify-content-between align-items-center bg-primary-subtle text-primary px-4 pt-3 pb-3 rounded-3 mb-0">
        <div>
          <h5 class="fw-bold mb-1 text-primary">Leave Approvals</h5>
          <nav aria-label="breadcrumb">
            <ol class="breadcrumb breadcrumb-dots mb-0 text-muted small">
              <li class="breadcrumb-item">
                <router-link to="/">Dashboard</router-link>
              </li>
              <li class="breadcrumb-item"><router-link to="/hr/leaves/requests">Leaves</router-link></li>
              <li class="breadcrumb-item active" aria-current="page">Approvals</li>
            </ol>
          </nav>
        </div>
        <div class="d-flex gap-2">
          <button class="btn btn-outline-primary" @click="fetchLeaves" :disabled="loading">
            <i class="ti ti-refresh me-2"></i> Refresh
          </button>
        </div>
      </div>
    </div>

    <div v-if="error" class="alert alert-danger">{{ error }}</div>

    <div class="card border-0 shadow-sm">
      <div class="card-body p-0">
        <div class="table-responsive">
          <table class="table table-sm table-hover align-middle mb-0">
            <thead class="table-light">
              <tr>
                <th class="ps-4">Employee</th>
                <th>Type</th>
                <th>Period</th>
                <th>Days</th>
                <th>Status</th>
                <th class="text-end pe-4">Actions</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="leave in leaves" :key="leave.id">
                <td class="ps-4">
                  <div class="fw-semibold">{{ leave.user?.name || 'Unknown' }}</div>
                  <div class="text-muted fs-12">ID: #{{ leave.user_id }}</div>
                </td>
                <td class="text-capitalize">{{ leave.leave_type || 'annual' }}</td>
                <td>{{ formatDate(leave.start_date) }} - {{ formatDate(leave.end_date) }}</td>
                <td>{{ calcDays(leave.start_date, leave.end_date) }}</td>
                <td>
                  <span class="badge" :class="statusClass(leave.status)">
                    {{ (leave.status || 'pending').toUpperCase() }}
                  </span>
                </td>
                <td class="text-end pe-4">
                  <div class="dropdown">
                    <button
                      type="button"
                      class="btn btn-sm btn-light btn-icon"
                      @click="toggleRowMenu(leave.id)"
                    >
                      <i class="ti ti-dots-vertical"></i>
                    </button>
                    <ul
                      class="dropdown-menu dropdown-menu-end shadow-sm border-0"
                      :class="{ show: openMenuId === leave.id }"
                    >
                      <li>
                        <button
                          class="dropdown-item text-success"
                          type="button"
                          :disabled="actionBusyId === leave.id"
                          @click.prevent="() => { closeRowMenu(); updateStatus(leave, 'approved'); }"
                        >
                          <span
                            v-if="actionBusyId === leave.id && pendingAction === 'approved'"
                            class="spinner-border spinner-border-sm me-2"
                          ></span>
                          <i class="ti ti-circle-check me-2"></i>Approve
                        </button>
                      </li>
                      <li>
                        <button
                          class="dropdown-item text-danger"
                          type="button"
                          :disabled="actionBusyId === leave.id"
                          @click.prevent="() => { closeRowMenu(); updateStatus(leave, 'rejected'); }"
                        >
                          <span
                            v-if="actionBusyId === leave.id && pendingAction === 'rejected'"
                            class="spinner-border spinner-border-sm me-2"
                          ></span>
                          <i class="ti ti-circle-x me-2"></i>Reject
                        </button>
                      </li>
                    </ul>
                  </div>
                </td>
              </tr>
              <tr v-if="!loading && leaves.length === 0">
                <td colspan="6" class="text-center py-4 text-muted">No pending requests</td>
              </tr>
            </tbody>
          </table>
        </div>
        <div v-if="loading" class="text-center py-4">
          <div class="spinner-border text-primary" role="status">
            <span class="visually-hidden">Loading...</span>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import api from '../services/api';
import { useToastStore } from '../store/toastStore';

const toast = useToastStore();

const loading = ref(false);
const error = ref('');
const leaves = ref([]);
const actionBusyId = ref(null);
const pendingAction = ref('');

const openMenuId = ref(null);

const statusClass = (status) => {
  const s = (status || 'pending').toLowerCase();
  if (s === 'approved') return 'bg-success-subtle text-success';
  if (s === 'rejected') return 'bg-danger-subtle text-danger';
  return 'bg-warning-subtle text-warning';
};

const formatDate = (d) => {
  if (!d) return '—';
  return new Date(d).toLocaleDateString();
};

const calcDays = (start, end) => {
  if (!start || !end) return 0;
  const s = new Date(start);
  const e = new Date(end);
  return Math.max(1, Math.round((e - s) / (1000 * 60 * 60 * 24)) + 1);
};

const toggleRowMenu = (id) => {
  openMenuId.value = openMenuId.value === id ? null : id;
};

const closeRowMenu = () => {
  openMenuId.value = null;
};

const fetchLeaves = async () => {
  loading.value = true;
  error.value = '';
  try {
    const res = await api.get('/leaves', { params: { status: 'pending', per_page: 50 } });
    const payload = res.data?.data;
    const page = payload?.data || [];
    leaves.value = page;
  } catch (e) {
    error.value = e.response?.data?.message || 'Failed to load leave requests';
    console.error('Load leaves error', e);
    toast.error(error.value || 'Failed to load leave requests');
  } finally {
    loading.value = false;
  }
};

const updateStatus = async (leave, status) => {
  actionBusyId.value = leave.id;
  pendingAction.value = status;
  try {
    await api.patch(`/leaves/${leave.id}`, { status });
    leaves.value = leaves.value.filter((l) => l.id !== leave.id);
  } catch (e) {
    console.error('Update leave status error', e);
    const message = e.response?.data?.message;
    const text = typeof message === 'string' ? message : 'Failed to update status';
    toast.error(text);
  } finally {
    actionBusyId.value = null;
    pendingAction.value = '';
  }
};

onMounted(fetchLeaves);
</script>
