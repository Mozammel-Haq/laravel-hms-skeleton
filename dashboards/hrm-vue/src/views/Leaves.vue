<template>
  <div class="leaves-page">
    <div class="card p-3 mb-3 border-0 shadow-sm">
      <div class="d-flex justify-content-between align-items-center bg-primary-subtle text-primary px-4 pt-3 pb-3 rounded-3 mb-0">
        <div>
          <h5 class="fw-bold mb-1 text-primary">Leave Requests</h5>
          <nav aria-label="breadcrumb">
            <ol class="breadcrumb breadcrumb-dots mb-0 text-muted small">
              <li class="breadcrumb-item">
                <router-link to="/">Dashboard</router-link>
              </li>
              <li class="breadcrumb-item active" aria-current="page">Leaves</li>
            </ol>
          </nav>
        </div>
        <div class="d-flex gap-2">
          <button class="btn btn-outline-primary" @click="fetchLeaves" :disabled="loading">
            <i class="ti ti-refresh me-2"></i> Refresh
          </button>
          <button class="btn btn-primary" @click="showNewModal = true">
            <i class="ti ti-plus me-2"></i> New Leave
          </button>
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
                  <div class="fw-semibold">{{ leave.user?.name || 'Unknown' }}</div>
                  <div class="text-muted fs-12">ID: #{{ leave.user_id }}</div>
                </td>
                <td>{{ leave.leave_type || 'Annual' }}</td>
                <td>{{ formatDate(leave.start_date) }} - {{ formatDate(leave.end_date) }}</td>
                <td>{{ leave.days || calcDays(leave.start_date, leave.end_date) }}</td>
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
                        <a
                          class="dropdown-item"
                          href="#"
                          @click.prevent="() => { closeRowMenu(); viewLeave(leave); }"
                        >
                          <i class="ti ti-eye me-2"></i>View
                        </a>
                      </li>
                      <li v-if="canManageLeaves && (leave.status || 'pending') === 'pending'">
                        <a
                          class="dropdown-item"
                          href="#"
                          @click.prevent="() => { closeRowMenu(); approve(leave); }"
                        >
                          <i class="ti ti-check me-2"></i>Approve
                        </a>
                      </li>
                      <li v-if="canManageLeaves && (leave.status || 'pending') === 'pending'">
                        <a
                          class="dropdown-item text-danger"
                          href="#"
                          @click.prevent="() => { closeRowMenu(); reject(leave); }"
                        >
                          <i class="ti ti-x me-2"></i>Reject
                        </a>
                      </li>
                    </ul>
                  </div>
                </td>
              </tr>
              <tr v-if="leaves.length === 0">
                <td colspan="6" class="text-center py-4 text-muted">No leave requests found</td>
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
            leave requests
          </div>
          <nav aria-label="Leave requests pagination">
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

    <!-- View Leave Modal -->
    <div v-if="showViewModal && selectedLeave" class="modal d-block" style="background: rgba(0,0,0,0.5)">
      <div class="modal-dialog modal-lg">
        <div class="modal-content border-0 shadow">
          <div class="modal-header">
            <h5 class="modal-title">Leave Request #{{ selectedLeave.id }}</h5>
            <button type="button" class="btn-close" @click="closeViewModal"></button>
          </div>
          <div class="modal-body">
            <div class="row">
              <div class="col-md-6 mb-3">
                <div class="text-muted fs-12">Employee</div>
                <div class="fw-semibold">
                  {{ selectedLeave.user?.name || 'Unknown' }}
                  <span class="text-muted fs-12">(#{{ selectedLeave.user_id }})</span>
                </div>
              </div>
              <div class="col-md-3 mb-3">
                <div class="text-muted fs-12">Type</div>
                <div class="fw-semibold text-capitalize">{{ selectedLeave.leave_type || 'annual' }}</div>
              </div>
              <div class="col-md-3 mb-3">
                <div class="text-muted fs-12">Status</div>
                <span class="badge" :class="statusClass(selectedLeave.status)">
                  {{ (selectedLeave.status || 'pending').toUpperCase() }}
                </span>
              </div>
            </div>
            <div class="row">
              <div class="col-md-3 mb-3">
                <div class="text-muted fs-12">Start Date</div>
                <div class="fw-semibold">{{ formatDate(selectedLeave.start_date) }}</div>
              </div>
              <div class="col-md-3 mb-3">
                <div class="text-muted fs-12">End Date</div>
                <div class="fw-semibold">{{ formatDate(selectedLeave.end_date) }}</div>
              </div>
              <div class="col-md-3 mb-3">
                <div class="text-muted fs-12">Days</div>
                <div class="fw-semibold">{{ selectedLeave.days || calcDays(selectedLeave.start_date, selectedLeave.end_date) }}</div>
              </div>
              <div class="col-md-3 mb-3" v-if="selectedLeave.created_at">
                <div class="text-muted fs-12">Requested At</div>
                <div class="fw-semibold">{{ formatDateTime(selectedLeave.created_at) }}</div>
              </div>
            </div>
            <div class="mb-3">
              <div class="text-muted fs-12">Reason</div>
              <p class="mb-0">{{ selectedLeave.reason }}</p>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-light" @click="closeViewModal">Close</button>
          </div>
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
              <div v-if="canManageLeaves" class="mb-3">
                <label class="form-label">Employee (optional)</label>
                <select v-model="form.user_id" class="form-select" :disabled="staffLoading">
                  <option :value="null">Self</option>
                  <option v-for="staff in staffOptions" :key="staff.id" :value="staff.id">
                    {{ staff.name }} (ID #{{ staff.id }})
                  </option>
                </select>
              </div>
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
import { ref, onMounted, computed } from 'vue';
import api from '../services/api';
import { useAuthStore } from '../store/authStore';

const auth = useAuthStore();
const loading = ref(false);
const submitting = ref(false);
const showNewModal = ref(false);
const showViewModal = ref(false);
const leaves = ref([]);
const selectedLeave = ref(null);
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

const form = ref({
  type: 'annual',
  start_date: '',
  end_date: '',
  reason: '',
  user_id: null
});

const staffOptions = ref([]);
const staffLoading = ref(false);

const canManageLeaves = computed(() => {
  const abilities = auth.user?.abilities || [];
  return Array.isArray(abilities) && abilities.includes('manage_leaves');
});

const fetchLeaves = async () => {
  loading.value = true;
  try {
    const res = await api.get('/leaves', {
      params: { page: pagination.value.current_page, per_page: pagination.value.per_page }
    });
    const payload = res.data;
    const pageData = payload.data || {};
    const list = pageData.data || [];
    leaves.value = list;
    const meta = pageData.meta || {};
    pagination.value = {
      current_page: meta.current_page || 1,
      last_page: meta.last_page || 1,
      per_page: meta.per_page || pagination.value.per_page || 10,
      total: meta.total || leaves.value.length,
      from: meta.from || (leaves.value.length ? 1 : 0),
      to: meta.to || leaves.value.length,
      prev_page_url: pageData.prev_page_url || null,
      next_page_url: pageData.next_page_url || null
    };
  } catch (e) {
    console.error('Failed to fetch leaves', e);
  } finally {
    loading.value = false;
  }
};

const loadStaff = async () => {
  if (!canManageLeaves.value) return;
  staffLoading.value = true;
  try {
    const res = await api.get('/staff', { params: { per_page: 100 } });
    const payload = res.data;
    const pageData = payload.data || {};
    const list = pageData.data || [];
    staffOptions.value = list.map((u) => ({
      id: u.id,
      name: u.name,
      email: u.email
    }));
  } catch (e) {
    console.error('Failed to load staff', e);
  } finally {
    staffLoading.value = false;
  }
};

const submit = async () => {
  submitting.value = true;
  try {
    await api.post('/leaves', form.value);
    showNewModal.value = false;
    form.value = { type: 'annual', start_date: '', end_date: '', reason: '', user_id: null };
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

const viewLeave = async (leave) => {
  try {
    const res = await api.get(`/leaves/${leave.id}`);
    const payload = res.data;
    selectedLeave.value = payload.data || leave;
    showViewModal.value = true;
  } catch (e) {
    console.error('Failed to load leave details', e);
    selectedLeave.value = leave;
    showViewModal.value = true;
  }
};

const closeViewModal = () => {
  showViewModal.value = false;
  selectedLeave.value = null;
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

const formatDateTime = (date) => {
  if (!date) return 'N/A';
  return new Date(date).toLocaleString();
};

const calcDays = (start, end) => {
  if (!start || !end) return 0;
  const s = new Date(start);
  const e = new Date(end);
  return Math.round((e - s) / (1000*60*60*24)) + 1;
};

const toggleRowMenu = (id) => {
  openMenuId.value = openMenuId.value === id ? null : id;
};

const closeRowMenu = () => {
  openMenuId.value = null;
};

const changePage = (page) => {
  if (page < 1 || page > pagination.value.last_page || page === pagination.value.current_page) return;
  pagination.value.current_page = page;
  fetchLeaves();
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

onMounted(async () => {
  await fetchLeaves();
  if (canManageLeaves.value) {
    await loadStaff();
  }
});
</script>
