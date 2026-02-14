<template>
  <div class="leaves-page">
    <div class="d-flex justify-content-between align-items-center mb-4">
      <h4 class="mb-0">Leave Requests</h4>
      <div class="d-flex gap-2">
        <button class="btn btn-outline-secondary" @click="fetchLeaves" :disabled="loading">
          <i class="ti ti-refresh me-2"></i> Refresh
        </button>
        <button class="btn btn-primary" @click="showNewModal = true">
          <i class="ti ti-plus me-2"></i> New Leave
        </button>
      </div>
    </div>

    <div v-if="loading" class="text-center py-5">
      <div class="spinner-border text-primary" role="status">
        <span class="visually-hidden">Loading...</span>
      </div>
    </div>

    <div v-else class="card border-0 shadow-sm">
      <div class="card-body p-0">
        <div class="table-responsive">
          <table class="table table-hover align-middle mb-0">
            <thead class="bg-light">
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
                  <div class="fw-semibold">{{ leave.employee?.name || 'Unknown' }}</div>
                  <div class="text-muted fs-12">ID: #{{ leave.employee_id }}</div>
                </td>
                <td>{{ leave.type || 'Annual' }}</td>
                <td>{{ formatDate(leave.start_date) }} - {{ formatDate(leave.end_date) }}</td>
                <td>{{ leave.days || calcDays(leave.start_date, leave.end_date) }}</td>
                <td>
                  <span class="badge" :class="statusClass(leave.status)">
                    {{ (leave.status || 'pending').toUpperCase() }}
                  </span>
                </td>
                <td class="text-end pe-4">
                  <button class="btn btn-sm btn-light me-2"><i class="ti ti-eye"></i></button>
                  <button v-if="leave.status==='pending'" class="btn btn-sm btn-success me-2" @click="approve(leave)">
                    <i class="ti ti-check"></i>
                  </button>
                  <button v-if="leave.status==='pending'" class="btn btn-sm btn-danger" @click="reject(leave)">
                    <i class="ti ti-x"></i>
                  </button>
                </td>
              </tr>
              <tr v-if="leaves.length === 0">
                <td colspan="6" class="text-center py-4 text-muted">No leave requests found</td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>

    <!-- New Leave Modal -->
    <div v-if="showNewModal" class="modal d-block" style="background: rgba(0,0,0,0.5)">
      <div class="modal-dialog">
        <div class="modal-content border-0 shadow">
          <div class="modal-header">
            <h5 class="modal-title">Request Leave</h5>
            <button type="button" class="btn-close" @click="showNewModal = false"></button>
          </div>
          <div class="modal-body">
            <form @submit.prevent="submit">
              <div class="mb-3">
                <label class="form-label">Type</label>
                <select v-model="form.type" class="form-select" required>
                  <option value="annual">Annual</option>
                  <option value="sick">Sick</option>
                  <option value="casual">Casual</option>
                </select>
              </div>
              <div class="row">
                <div class="col-md-6 mb-3">
                  <label class="form-label">Start Date</label>
                  <input v-model="form.start_date" type="date" class="form-control" required>
                </div>
                <div class="col-md-6 mb-3">
                  <label class="form-label">End Date</label>
                  <input v-model="form.end_date" type="date" class="form-control" required>
                </div>
              </div>
              <div class="mb-3">
                <label class="form-label">Reason</label>
                <textarea v-model="form.reason" class="form-control" rows="3" required></textarea>
              </div>
            </form>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-light" @click="showNewModal = false">Cancel</button>
            <button type="button" class="btn btn-primary" @click="submit" :disabled="submitting">
              {{ submitting ? 'Submitting...' : 'Submit Request' }}
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

const loading = ref(false);
const submitting = ref(false);
const showNewModal = ref(false);
const leaves = ref([]);

const form = ref({
  type: 'annual',
  start_date: '',
  end_date: '',
  reason: ''
});

const fetchLeaves = async () => {
  loading.value = true;
  try {
    const res = await api.get('/leaves');
    leaves.value = res.data.data?.data || res.data.data || [];
  } catch (e) {
    console.error('Failed to fetch leaves', e);
  } finally {
    loading.value = false;
  }
};

const submit = async () => {
  submitting.value = true;
  try {
    await api.post('/leaves', form.value);
    showNewModal.value = false;
    form.value = { type: 'annual', start_date: '', end_date: '', reason: '' };
    fetchLeaves();
  } catch (e) {
    console.error('Failed to submit leave', e);
    alert('Failed to submit leave request.');
  } finally {
    submitting.value = false;
  }
};

const approve = async (leave) => {
  try {
    await api.patch(`/leaves/${leave.id}`, { status: 'approved' });
    fetchLeaves();
  } catch (e) {
    console.error('Failed to approve', e);
  }
};

const reject = async (leave) => {
  try {
    await api.patch(`/leaves/${leave.id}`, { status: 'rejected' });
    fetchLeaves();
  } catch (e) {
    console.error('Failed to reject', e);
  }
};

const statusClass = (status) => {
  const s = (status || 'pending').toLowerCase();
  switch (s) {
    case 'approved': return 'bg-success-subtle text-success';
    case 'rejected': return 'bg-danger-subtle text-danger';
    default: return 'bg-warning-subtle text-warning';
  }
};

const formatDate = (date) => {
  if (!date) return 'N/A';
  return new Date(date).toLocaleDateString();
};

const calcDays = (start, end) => {
  if (!start || !end) return 0;
  const s = new Date(start);
  const e = new Date(end);
  return Math.round((e - s) / (1000*60*60*24)) + 1;
};

onMounted(fetchLeaves);
</script>
