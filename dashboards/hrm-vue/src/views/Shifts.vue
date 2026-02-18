<template>
  <div class="shifts-page">
    <div class="card p-3 mb-3 border-0 shadow-sm">
      <div class="d-flex justify-content-between align-items-center bg-primary-subtle text-primary px-4 pt-3 pb-3 rounded-3 mb-0">
        <div>
          <h5 class="fw-bold mb-1 text-primary">Employee Shifts</h5>
          <nav aria-label="breadcrumb">
            <ol class="breadcrumb breadcrumb-dots mb-0 text-muted small">
              <li class="breadcrumb-item">
                <router-link to="/">Dashboard</router-link>
              </li>
              <li class="breadcrumb-item">Employees</li>
              <li class="breadcrumb-item active" aria-current="page">Shifts</li>
            </ol>
          </nav>
        </div>
        <div class="d-flex gap-2">
          <button class="btn btn-outline-primary" @click="refreshAll" :disabled="loadingShifts || loadingAssignments">
            <i class="ti ti-refresh me-2"></i> Refresh
          </button>
          <button class="btn btn-primary" v-if="canManage" @click="openShiftModal">
            <i class="ti ti-calendar-time me-2"></i> New Shift
          </button>
          <button class="btn btn-outline-primary" v-if="canManage" @click="openAssignmentModal">
            <i class="ti ti-user-shield me-2"></i> Assign Shift
          </button>
        </div>
      </div>
    </div>

    <div class="row g-4">
      <div class="col-lg-5">
        <div class="card border-0 shadow-sm h-100">
          <div class="card-header bg-transparent border-0 pb-0 px-4 pt-4">
            <div class="d-flex justify-content-between align-items-center">
              <div>
                <h6 class="mb-1 fw-bold">Shift Templates</h6>
                <p class="text-muted fs-12 mb-0">Standard working shifts for this clinic</p>
              </div>
            </div>
          </div>
          <div class="card-body p-0">
            <div v-if="loadingShifts" class="text-center py-4">
              <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Loading...</span>
              </div>
            </div>
            <div v-else class="table-responsive">
              <table class="table table-sm table-hover align-middle mb-0">
                <thead class="table-light">
                  <tr>
                    <th class="ps-4">Name</th>
                    <th>Code</th>
                    <th>Time</th>
                    <th>Status</th>
                    <th class="text-end pe-4" v-if="canManage">Actions</th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="shift in shifts" :key="shift.id">
                    <td class="ps-4 fw-semibold">{{ shift.name }}</td>
                    <td>{{ shift.code || '—' }}</td>
                    <td>{{ formatTimeRange(shift.start_time, shift.end_time) }}</td>
                    <td>
                      <span
                        class="badge"
                        :class="shift.status === 'active' ? 'bg-success-subtle text-success' : 'bg-secondary-subtle text-secondary'"
                      >
                        {{ shift.status || 'active' }}
                      </span>
                    </td>
                    <td class="text-end pe-4" v-if="canManage">
                      <div class="dropdown">
                        <button
                          type="button"
                          class="btn btn-sm btn-light btn-icon"
                          @click="toggleShiftMenu(shift.id)"
                        >
                          <i class="ti ti-dots-vertical"></i>
                        </button>
                        <ul
                          class="dropdown-menu dropdown-menu-end shadow-sm border-0"
                          :class="{ show: openShiftMenuId === shift.id }"
                        >
                          <li>
                            <a
                              href="#"
                              class="dropdown-item"
                              @click.prevent="() => { closeShiftMenu(); editShift(shift); }"
                            >
                              <i class="ti ti-edit me-2"></i>Edit
                            </a>
                          </li>
                          <li>
                            <a
                              href="#"
                              class="dropdown-item text-danger"
                              @click.prevent="() => { closeShiftMenu(); deactivateShift(shift); }"
                              :class="{ disabled: shift.status === 'inactive' }"
                            >
                              <i class="ti ti-archive me-2"></i>Archive
                            </a>
                          </li>
                        </ul>
                      </div>
                    </td>
                  </tr>
                  <tr v-if="!loadingShifts && shifts.length === 0">
                    <td colspan="5" class="text-center py-4 text-muted">No shifts defined yet</td>
                  </tr>
                </tbody>
              </table>
            </div>
            <div
              v-if="shiftPagination.total > 0"
              class="d-flex justify-content-between align-items-center px-4 py-3 border-top pagination-bar"
            >
              <div class="text-muted small">
                Showing
                <span class="fw-semibold">{{ shiftPagination.from }}</span>
                to
                <span class="fw-semibold">{{ shiftPagination.to }}</span>
                of
                <span class="fw-semibold">{{ shiftPagination.total }}</span>
                shifts
              </div>
              <nav aria-label="Shifts pagination">
                <ul class="pagination pagination-sm mb-0">
                  <li :class="['page-item', { disabled: !shiftPagination.prev_page_url }]">
                    <button
                      class="page-link"
                      type="button"
                      @click="changeShiftPage(shiftPagination.current_page - 1)"
                      :disabled="!shiftPagination.prev_page_url"
                    >
                      <i class="ti ti-chevron-left"></i>
                    </button>
                  </li>
                  <li
                    v-for="page in shiftPages"
                    :key="page.key"
                    :class="['page-item', { active: page.number === shiftPagination.current_page, disabled: page.isSeparator }]"
                  >
                    <button
                      v-if="!page.isSeparator"
                      class="page-link"
                      type="button"
                      @click="changeShiftPage(page.number)"
                    >
                      {{ page.label }}
                    </button>
                    <span
                      v-else
                      class="page-link border-0 bg-transparent text-muted"
                    >
                      …
                    </span>
                  </li>
                  <li :class="['page-item', { disabled: !shiftPagination.next_page_url }]">
                    <button
                      class="page-link"
                      type="button"
                      @click="changeShiftPage(shiftPagination.current_page + 1)"
                      :disabled="!shiftPagination.next_page_url"
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

      <div class="col-lg-7">
        <div class="card border-0 shadow-sm h-100">
          <div class="card-header bg-transparent border-0 pb-0 px-4 pt-4">
            <div class="d-flex justify-content-between align-items-center">
              <div>
                <h6 class="mb-1 fw-bold">Assignments</h6>
                <p class="text-muted fs-12 mb-0">Which staff are on which shift</p>
              </div>
            </div>
          </div>
          <div class="card-body p-0">
            <div class="bg-light p-3 rounded mb-3 mx-3 mt-3">
              <form @submit.prevent="applyAssignmentFilters">
                <div class="row g-2 align-items-end">
                  <div class="col-md-4">
                    <label class="form-label small fw-bold text-muted mb-1">Employee ID</label>
                    <input
                      v-model.number="assignmentFilters.user_id"
                      type="number"
                      class="form-control form-control-sm"
                      placeholder="Filter by user ID"
                    />
                  </div>
                  <div class="col-md-4">
                    <label class="form-label small fw-bold text-muted mb-1">Status</label>
                    <select v-model="assignmentFilters.status" class="form-select form-select-sm">
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
                </div>
              </form>
            </div>
            <div v-if="loadingAssignments" class="text-center py-4">
              <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Loading...</span>
              </div>
            </div>
            <div v-else class="table-responsive">
              <table class="table table-sm table-hover align-middle mb-0">
                <thead class="table-light">
                  <tr>
                    <th class="ps-4">Employee</th>
                    <th>Shift</th>
                    <th>From</th>
                    <th>To</th>
                    <th>Primary</th>
                    <th>Status</th>
                    <th class="text-end pe-4" v-if="canManage">Actions</th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="a in assignments" :key="a.id">
                    <td class="ps-4">
                      <div class="fw-semibold">
                        {{ a.user?.name || 'User #' + a.user_id }}
                      </div>
                      <div class="text-muted fs-12">ID #{{ a.user_id }}</div>
                    </td>
                    <td>
                      <div class="fw-semibold">{{ a.shift?.name || 'Shift #' + a.shift_id }}</div>
                      <div class="text-muted fs-12">
                        {{ formatTimeRange(a.shift?.start_time, a.shift?.end_time) }}
                      </div>
                    </td>
                    <td>{{ formatDate(a.effective_from) }}</td>
                    <td>{{ a.effective_to ? formatDate(a.effective_to) : 'Open-ended' }}</td>
                    <td>
                      <span
                        class="badge"
                        :class="a.is_primary ? 'bg-info-subtle text-info' : 'bg-secondary-subtle text-secondary'"
                      >
                        {{ a.is_primary ? 'Primary' : 'Secondary' }}
                      </span>
                    </td>
                    <td>
                      <span
                        class="badge"
                        :class="a.status === 'active' ? 'bg-success-subtle text-success' : 'bg-secondary-subtle text-secondary'"
                      >
                        {{ a.status || 'active' }}
                      </span>
                    </td>
                    <td class="text-end pe-4" v-if="canManage">
                      <div class="dropdown">
                        <button
                          type="button"
                          class="btn btn-sm btn-light btn-icon"
                          @click="toggleAssignmentMenu(a.id)"
                        >
                          <i class="ti ti-dots-vertical"></i>
                        </button>
                        <ul
                          class="dropdown-menu dropdown-menu-end shadow-sm border-0"
                          :class="{ show: openAssignmentMenuId === a.id }"
                        >
                          <li>
                            <a
                              href="#"
                              class="dropdown-item"
                              @click.prevent="() => { closeAssignmentMenu(); editAssignment(a); }"
                            >
                              <i class="ti ti-edit me-2"></i>Edit
                            </a>
                          </li>
                          <li>
                            <a
                              href="#"
                              class="dropdown-item text-danger"
                              @click.prevent="() => { closeAssignmentMenu(); deactivateAssignment(a); }"
                              :class="{ disabled: a.status === 'inactive' }"
                            >
                              <i class="ti ti-archive me-2"></i>Archive
                            </a>
                          </li>
                        </ul>
                      </div>
                    </td>
                  </tr>
                  <tr v-if="!loadingAssignments && assignments.length === 0">
                    <td colspan="7" class="text-center py-4 text-muted">No shift assignments found</td>
                  </tr>
                </tbody>
              </table>
            </div>
            <div
              v-if="assignmentPagination.total > 0"
              class="d-flex justify-content-between align-items-center px-4 py-3 border-top pagination-bar"
            >
              <div class="text-muted small">
                Showing
                <span class="fw-semibold">{{ assignmentPagination.from }}</span>
                to
                <span class="fw-semibold">{{ assignmentPagination.to }}</span>
                of
                <span class="fw-semibold">{{ assignmentPagination.total }}</span>
                assignments
              </div>
              <nav aria-label="Shift assignments pagination">
                <ul class="pagination pagination-sm mb-0">
                  <li :class="['page-item', { disabled: !assignmentPagination.prev_page_url }]">
                    <button
                      class="page-link"
                      type="button"
                      @click="changeAssignmentPage(assignmentPagination.current_page - 1)"
                      :disabled="!assignmentPagination.prev_page_url"
                    >
                      <i class="ti ti-chevron-left"></i>
                    </button>
                  </li>
                  <li
                    v-for="page in assignmentPages"
                    :key="page.key"
                    :class="['page-item', { active: page.number === assignmentPagination.current_page, disabled: page.isSeparator }]"
                  >
                    <button
                      v-if="!page.isSeparator"
                      class="page-link"
                      type="button"
                      @click="changeAssignmentPage(page.number)"
                    >
                      {{ page.label }}
                    </button>
                    <span
                      v-else
                      class="page-link border-0 bg-transparent text-muted"
                    >
                      …
                    </span>
                  </li>
                  <li :class="['page-item', { disabled: !assignmentPagination.next_page_url }]">
                    <button
                      class="page-link"
                      type="button"
                      @click="changeAssignmentPage(assignmentPagination.current_page + 1)"
                      :disabled="!assignmentPagination.next_page_url"
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
    </div>

    <div v-if="showShiftModal" class="modal d-block" style="background: rgba(0,0,0,0.5)">
      <div class="modal-dialog">
        <div class="modal-content border-0 shadow">
          <div class="modal-header">
            <h5 class="modal-title">
              {{ editingShift ? 'Edit Shift' : 'New Shift' }}
            </h5>
            <button type="button" class="btn-close" @click="closeShiftModal"></button>
          </div>
          <div class="modal-body">
            <form @submit.prevent="saveShift">
              <div class="mb-3">
                <label class="form-label">Name</label>
                <input v-model="shiftForm.name" type="text" class="form-control" required />
              </div>
              <div class="mb-3">
                <label class="form-label">Code</label>
                <input v-model="shiftForm.code" type="text" class="form-control" />
              </div>
              <div class="row">
                <div class="col-md-6 mb-3">
                  <label class="form-label">Start Time</label>
                  <input v-model="shiftForm.start_time" type="time" class="form-control" required />
                </div>
                <div class="col-md-6 mb-3">
                  <label class="form-label">End Time</label>
                  <input v-model="shiftForm.end_time" type="time" class="form-control" required />
                </div>
              </div>
              <div class="row">
                <div class="col-md-6 mb-3">
                  <label class="form-label">Break Minutes</label>
                  <input v-model.number="shiftForm.break_minutes" type="number" class="form-control" min="0" max="600" />
                </div>
                <div class="col-md-6 mb-3">
                  <label class="form-label">Status</label>
                  <select v-model="shiftForm.status" class="form-select">
                    <option value="active">Active</option>
                    <option value="inactive">Inactive</option>
                  </select>
                </div>
              </div>
              <div v-if="shiftError" class="alert alert-danger py-2 px-3">
                {{ shiftError }}
              </div>
            </form>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-light" @click="closeShiftModal">Cancel</button>
            <button type="button" class="btn btn-primary" @click="saveShift" :disabled="savingShift">
              <span v-if="savingShift" class="spinner-border spinner-border-sm me-1"></span>
              {{ editingShift ? 'Save Changes' : 'Create Shift' }}
            </button>
          </div>
        </div>
      </div>
    </div>

    <div v-if="showAssignmentModal" class="modal d-block" style="background: rgba(0,0,0,0.5)">
      <div class="modal-dialog">
        <div class="modal-content border-0 shadow">
          <div class="modal-header">
            <h5 class="modal-title">
              {{ editingAssignment ? 'Edit Assignment' : 'Assign Shift' }}
            </h5>
            <button type="button" class="btn-close" @click="closeAssignmentModal"></button>
          </div>
          <div class="modal-body">
            <form @submit.prevent="saveAssignment">
              <div v-if="!editingAssignment" class="mb-3">
                <label class="form-label">Employee</label>
                <select v-model.number="assignmentForm.user_id" class="form-select" required>
                  <option :value="null" disabled>Select employee</option>
                  <option v-for="staff in staffOptions" :key="staff.id" :value="staff.id">
                    {{ staff.name }} (ID #{{ staff.id }})
                  </option>
                </select>
              </div>
              <div class="mb-3">
                <label class="form-label">Shift</label>
                <select v-model.number="assignmentForm.shift_id" class="form-select" required>
                  <option :value="null" disabled>Select shift</option>
                  <option v-for="shift in shifts" :key="shift.id" :value="shift.id">
                    {{ shift.name }} ({{ formatTimeRange(shift.start_time, shift.end_time) }})
                  </option>
                </select>
              </div>
              <div class="row">
                <div class="col-md-6 mb-3">
                  <label class="form-label">Effective From</label>
                  <input v-model="assignmentForm.effective_from" type="date" class="form-control" required />
                </div>
                <div class="col-md-6 mb-3">
                  <label class="form-label">Effective To</label>
                  <input v-model="assignmentForm.effective_to" type="date" class="form-control" />
                </div>
              </div>
              <div class="row">
                <div class="col-md-6 mb-3">
                  <label class="form-label">Primary</label>
                  <select v-model="assignmentForm.is_primary" class="form-select">
                    <option :value="true">Primary</option>
                    <option :value="false">Secondary</option>
                  </select>
                </div>
                <div class="col-md-6 mb-3">
                  <label class="form-label">Status</label>
                  <select v-model="assignmentForm.status" class="form-select">
                    <option value="active">Active</option>
                    <option value="inactive">Inactive</option>
                  </select>
                </div>
              </div>
              <div v-if="assignmentError" class="alert alert-danger py-2 px-3">
                {{ assignmentError }}
              </div>
            </form>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-light" @click="closeAssignmentModal">Cancel</button>
            <button type="button" class="btn btn-primary" @click="saveAssignment" :disabled="savingAssignment">
              <span v-if="savingAssignment" class="spinner-border spinner-border-sm me-1"></span>
              {{ editingAssignment ? 'Save Changes' : 'Save Assignment' }}
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue';
import api from '../services/api';
import { useAuthStore } from '../store/authStore';
import { useToastStore } from '../store/toastStore';

const auth = useAuthStore();
const toast = useToastStore();

const shifts = ref([]);
const shiftPagination = ref({
  current_page: 1,
  last_page: 1,
  per_page: 10,
  total: 0,
  from: 0,
  to: 0,
  prev_page_url: null,
  next_page_url: null
});
const loadingShifts = ref(false);

const openShiftMenuId = ref(null);

const assignments = ref([]);
const assignmentPagination = ref({
  current_page: 1,
  last_page: 1,
  per_page: 10,
  total: 0,
  from: 0,
  to: 0,
  prev_page_url: null,
  next_page_url: null
});
const loadingAssignments = ref(false);

const openAssignmentMenuId = ref(null);

const assignmentFilters = ref({
  user_id: null,
  status: ''
});

const staffOptions = ref([]);
const staffLoading = ref(false);

const showShiftModal = ref(false);
const editingShift = ref(null);
const savingShift = ref(false);
const shiftError = ref('');
const shiftForm = ref({
  name: '',
  code: '',
  start_time: '',
  end_time: '',
  break_minutes: 0,
  status: 'active'
});

const showAssignmentModal = ref(false);
const editingAssignment = ref(null);
const savingAssignment = ref(false);
const assignmentError = ref('');
const assignmentForm = ref({
  user_id: null,
  shift_id: null,
  effective_from: '',
  effective_to: '',
  is_primary: true,
  status: 'active'
});

const canManage = computed(() => {
  const abilities = auth.user?.abilities || [];
  return Array.isArray(abilities) && abilities.includes('create_staff');
});

const toggleShiftMenu = (id) => {
  openShiftMenuId.value = openShiftMenuId.value === id ? null : id;
};

const closeShiftMenu = () => {
  openShiftMenuId.value = null;
};

const toggleAssignmentMenu = (id) => {
  openAssignmentMenuId.value = openAssignmentMenuId.value === id ? null : id;
};

const closeAssignmentMenu = () => {
  openAssignmentMenuId.value = null;
};

const refreshAll = async () => {
  await Promise.all([fetchShifts(), fetchAssignments()]);
};

const fetchShifts = async () => {
  loadingShifts.value = true;
  try {
    const res = await api.get('/shifts', {
      params: {
        page: shiftPagination.value.current_page,
        per_page: shiftPagination.value.per_page
      }
    });
    const payload = res.data || {};
    const pageData = payload.data || {};
    const list = pageData.data || [];
    shifts.value = list;
    const meta = pageData.meta || {};
    shiftPagination.value = {
      current_page: meta.current_page || 1,
      last_page: meta.last_page || 1,
      per_page: meta.per_page || shiftPagination.value.per_page || 10,
      total: meta.total || shifts.value.length,
      from: meta.from || (shifts.value.length ? 1 : 0),
      to: meta.to || shifts.value.length,
      prev_page_url: pageData.prev_page_url || null,
      next_page_url: pageData.next_page_url || null
    };
  } catch (e) {
    console.error('Failed to fetch shifts', e);
    toast.error('Failed to load shifts');
  } finally {
    loadingShifts.value = false;
  }
};

const fetchAssignments = async () => {
  loadingAssignments.value = true;
  try {
    const params = {
      page: assignmentPagination.value.current_page,
      per_page: assignmentPagination.value.per_page
    };
    if (assignmentFilters.value.user_id) params.user_id = assignmentFilters.value.user_id;
    if (assignmentFilters.value.status) params.status = assignmentFilters.value.status;
    const res = await api.get('/shift-assignments', { params });
    const payload = res.data || {};
    const pageData = payload.data || {};
    const list = pageData.data || [];
    assignments.value = list;
    const meta = pageData.meta || {};
    assignmentPagination.value = {
      current_page: meta.current_page || 1,
      last_page: meta.last_page || 1,
      per_page: meta.per_page || assignmentPagination.value.per_page || 10,
      total: meta.total || assignments.value.length,
      from: meta.from || (assignments.value.length ? 1 : 0),
      to: meta.to || assignments.value.length,
      prev_page_url: pageData.prev_page_url || null,
      next_page_url: pageData.next_page_url || null
    };
  } catch (e) {
    console.error('Failed to fetch assignments', e);
    toast.error('Failed to load shift assignments');
  } finally {
    loadingAssignments.value = false;
  }
};

const loadStaffOptions = async () => {
  if (!canManage.value) return;
  staffLoading.value = true;
  try {
    const res = await api.get('/staff', { params: { per_page: 100 } });
    const payload = res.data || {};
    const pageData = payload.data || {};
    const list = pageData.data || [];
    staffOptions.value = list.map((u) => ({
      id: u.id,
      name: u.name,
      email: u.email
    }));
  } catch (e) {
    console.error('Failed to load staff options', e);
    toast.error('Failed to load staff');
  } finally {
    staffLoading.value = false;
  }
};

const changeShiftPage = (page) => {
  if (page < 1 || page > shiftPagination.value.last_page || page === shiftPagination.value.current_page) return;
  shiftPagination.value.current_page = page;
  fetchShifts();
};

const changeAssignmentPage = (page) => {
  if (page < 1 || page > assignmentPagination.value.last_page || page === assignmentPagination.value.current_page) return;
  assignmentPagination.value.current_page = page;
  fetchAssignments();
};

const shiftPages = computed(() => {
  const pages = [];
  const total = shiftPagination.value.last_page;
  const current = shiftPagination.value.current_page;
  if (total <= 7) {
    for (let i = 1; i <= total; i += 1) {
      pages.push({ key: `s-${i}`, number: i, label: i, isSeparator: false });
    }
    return pages;
  }
  const addPage = (n) => pages.push({ key: `s-${n}`, number: n, label: n, isSeparator: false });
  const addSep = (idx) => pages.push({ key: `s-sep-${idx}`, number: null, label: '…', isSeparator: true });
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

const assignmentPages = computed(() => {
  const pages = [];
  const total = assignmentPagination.value.last_page;
  const current = assignmentPagination.value.current_page;
  if (total <= 7) {
    for (let i = 1; i <= total; i += 1) {
      pages.push({ key: `a-${i}`, number: i, label: i, isSeparator: false });
    }
    return pages;
  }
  const addPage = (n) => pages.push({ key: `a-${n}`, number: n, label: n, isSeparator: false });
  const addSep = (idx) => pages.push({ key: `a-sep-${idx}`, number: null, label: '…', isSeparator: true });
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

const formatTimeRange = (start, end) => {
  if (!start || !end) return 'N/A';
  const format = (t) => {
    const date = new Date(`1970-01-01T${String(t).substring(11, 19) || String(t)}`);
    return date.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
  };
  return `${format(start)} - ${format(end)}`;
};

const formatDate = (date) => {
  if (!date) return 'N/A';
  return new Date(date).toLocaleDateString();
};

const openShiftModal = () => {
  editingShift.value = null;
  shiftForm.value = {
    name: '',
    code: '',
    start_time: '',
    end_time: '',
    break_minutes: 0,
    status: 'active'
  };
  shiftError.value = '';
  showShiftModal.value = true;
};

const editShift = (shift) => {
  editingShift.value = shift;
  shiftForm.value = {
    name: shift.name || '',
    code: shift.code || '',
    start_time: String(shift.start_time).substring(11, 16),
    end_time: String(shift.end_time).substring(11, 16),
    break_minutes: shift.break_minutes ?? 0,
    status: shift.status || 'active'
  };
  shiftError.value = '';
  showShiftModal.value = true;
};

const closeShiftModal = () => {
  showShiftModal.value = false;
  editingShift.value = null;
};

const saveShift = async () => {
  savingShift.value = true;
  shiftError.value = '';
  try {
    if (editingShift.value) {
      await api.put(`/shifts/${editingShift.value.id}`, shiftForm.value);
    } else {
      await api.post('/shifts', shiftForm.value);
    }
    showShiftModal.value = false;
    await fetchShifts();
  } catch (e) {
    console.error('Failed to save shift', e);
    shiftError.value = e.response?.data?.message || 'Failed to save shift';
    toast.error(shiftError.value);
  } finally {
    savingShift.value = false;
  }
};

const deactivateShift = async (shift) => {
  if (!window.confirm(`Deactivate shift "${shift.name}"?`)) return;
  try {
    await api.put(`/shifts/${shift.id}`, { status: 'inactive' });
    await fetchShifts();
  } catch (e) {
    console.error('Failed to deactivate shift', e);
    toast.error('Failed to deactivate shift');
  }
};

const openAssignmentModal = async () => {
  editingAssignment.value = null;
  assignmentForm.value = {
    user_id: null,
    shift_id: shifts.value[0]?.id || null,
    effective_from: new Date().toISOString().substring(0, 10),
    effective_to: '',
    is_primary: true,
    status: 'active'
  };
  assignmentError.value = '';
  showAssignmentModal.value = true;
  if (!staffOptions.value.length) {
    await loadStaffOptions();
  }
};

const editAssignment = (assignment) => {
  editingAssignment.value = assignment;
  assignmentForm.value = {
    user_id: assignment.user_id,
    shift_id: assignment.shift_id,
    effective_from: assignment.effective_from,
    effective_to: assignment.effective_to,
    is_primary: !!assignment.is_primary,
    status: assignment.status || 'active'
  };
  assignmentError.value = '';
  showAssignmentModal.value = true;
};

const closeAssignmentModal = () => {
  showAssignmentModal.value = false;
  editingAssignment.value = null;
};

const saveAssignment = async () => {
  savingAssignment.value = true;
  assignmentError.value = '';
  try {
    const isPrimary = assignmentForm.value.is_primary === true
      || assignmentForm.value.is_primary === 'true'
      || assignmentForm.value.is_primary === 1
      || assignmentForm.value.is_primary === '1';
    if (editingAssignment.value) {
      await api.put(`/shift-assignments/${editingAssignment.value.id}`, {
        effective_from: assignmentForm.value.effective_from,
        effective_to: assignmentForm.value.effective_to,
        is_primary: isPrimary,
        status: assignmentForm.value.status
      });
    } else {
      await api.post('/shift-assignments', {
        ...assignmentForm.value,
        is_primary: isPrimary
      });
    }
    showAssignmentModal.value = false;
    await fetchAssignments();
  } catch (e) {
    console.error('Failed to save assignment', e);
    assignmentError.value = e.response?.data?.message || 'Failed to save assignment';
    toast.error(assignmentError.value);
  } finally {
    savingAssignment.value = false;
  }
};

const deactivateAssignment = async (assignment) => {
  if (!window.confirm(`Deactivate assignment for user #${assignment.user_id}?`)) return;
  try {
    await api.put(`/shift-assignments/${assignment.id}`, { status: 'inactive' });
    await fetchAssignments();
  } catch (e) {
    console.error('Failed to deactivate assignment', e);
    toast.error('Failed to deactivate assignment');
  }
};

const applyAssignmentFilters = () => {
  assignmentPagination.value.current_page = 1;
  fetchAssignments();
};

onMounted(async () => {
  await fetchShifts();
  await fetchAssignments();
  if (canManage.value) {
    await loadStaffOptions();
  }
});
</script>
