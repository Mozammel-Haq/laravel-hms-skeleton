<template>
  <div class="container-fluid">
    <div class="card p-3 mb-3 border-0 shadow-sm">
      <div class="d-flex justify-content-between align-items-center bg-primary-subtle text-primary px-4 pt-3 pb-3 rounded-3 mb-0">
        <div>
          <h5 class="fw-bold mb-1 text-primary">Employee Profiles</h5>
          <nav aria-label="breadcrumb">
            <ol class="breadcrumb breadcrumb-dots mb-0 text-muted small">
              <li class="breadcrumb-item">
                <router-link to="/">Dashboard</router-link>
              </li>
              <li class="breadcrumb-item">Employees</li>
              <li class="breadcrumb-item active" aria-current="page">Profiles</li>
            </ol>
          </nav>
        </div>
      </div>
    </div>

    <div class="card border-0 shadow-sm">
      <div class="card-header bg-transparent border-0 pb-0 px-4 pt-4">
        <div class="d-flex justify-content-between align-items-center">
          <div>
            <h6 class="mb-1 fw-bold">Staff Profiles</h6>
            <p class="text-muted fs-12 mb-0">Overview of employees with department, designation, and status</p>
          </div>
          <div class="d-flex gap-2">
            <div class="input-group input-group-sm">
              <span class="input-group-text bg-light border-end-0">
                <i class="ti ti-search"></i>
              </span>
              <input
                v-model="search"
                type="text"
                class="form-control border-start-0"
                placeholder="Search by name, email, or department"
              />
            </div>
          </div>
        </div>
      </div>
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
                <th>Department</th>
                <th>Designation</th>
                <th>Join Date</th>
                <th>Status</th>
                <th class="text-end pe-4">Actions</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="staff in filteredStaff" :key="staff.id">
                <td class="ps-4">
                  <div class="d-flex align-items-center">
                    <div class="avatar avatar-md rounded-circle bg-primary-subtle text-primary d-flex align-items-center justify-content-center me-2">
                      <span class="fw-bold">
                        {{ staff.name ? staff.name.charAt(0).toUpperCase() : '?' }}
                      </span>
                    </div>
                    <div>
                      <div class="fw-semibold">{{ staff.name || 'User #' + staff.id }}</div>
                      <div class="text-muted fs-12">
                        {{ staff.email || 'No email' }}
                      </div>
                    </div>
                  </div>
                </td>
                <td>
                  <div class="fw-semibold">
                    {{ staff.department?.name || 'Not set' }}
                  </div>
                </td>
                <td>
                  <div class="fw-semibold">
                    {{ staff.designation?.name || 'Not set' }}
                  </div>
                </td>
                <td>
                  <span>{{ formatDate(staff.join_date) }}</span>
                </td>
                <td>
                  <span
                    class="badge"
                    :class="statusClass(staff.status)"
                  >
                    {{ (staff.status || 'active').toUpperCase() }}
                  </span>
                </td>
                <td class="text-end pe-4">
                  <button
                    class="btn btn-sm btn-light me-1"
                    type="button"
                    @click="viewProfile(staff)"
                  >
                    <i class="ti ti-eye"></i>
                  </button>
                  <button
                    v-if="canEdit"
                    class="btn btn-sm btn-outline-primary"
                    type="button"
                    @click="goToStaffEdit(staff)"
                  >
                    <i class="ti ti-edit"></i>
                  </button>
                </td>
              </tr>
              <tr v-if="!loading && filteredStaff.length === 0">
                <td colspan="6" class="text-center py-4 text-muted">
                  No staff profiles found
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { computed, onMounted, ref } from 'vue';
import { useRouter } from 'vue-router';
import api from '../services/api';
import { useAuthStore } from '../store/authStore';

const auth = useAuthStore();
const router = useRouter();

const loading = ref(false);
const staffList = ref([]);
const search = ref('');

const abilities = computed(() =>
  Array.isArray(auth.user?.abilities) ? auth.user.abilities : []
);
const has = (perm) => abilities.value.includes(perm);

const canEdit = computed(() => has('edit_staff'));

const fetchStaff = async () => {
  loading.value = true;
  try {
    const res = await api.get('/staff', { params: { per_page: 200 } });
    const payload = res.data || {};
    const pageData = payload.data || {};
    const list = pageData.data || [];
    staffList.value = list;
  } catch (e) {
    console.error('Failed to load staff profiles', e);
  } finally {
    loading.value = false;
  }
};

const filteredStaff = computed(() => {
  const term = search.value.trim().toLowerCase();
  if (!term) return staffList.value;
  return staffList.value.filter((s) => {
    const name = (s.name || '').toLowerCase();
    const email = (s.email || '').toLowerCase();
    const department = (s.department?.name || '').toLowerCase();
    const designation = (s.designation?.name || '').toLowerCase();
    return (
      name.includes(term) ||
      email.includes(term) ||
      department.includes(term) ||
      designation.includes(term)
    );
  });
});

const formatDate = (value) => {
  if (!value) return 'Not set';
  const date = new Date(value);
  if (Number.isNaN(date.getTime())) return value;
  return date.toLocaleDateString();
};

const statusClass = (status) => {
  const s = (status || 'active').toLowerCase();
  if (s === 'inactive' || s === 'banned') {
    return 'bg-danger-subtle text-danger';
  }
  return 'bg-success-subtle text-success';
};

const viewProfile = (staff) => {
  router.push({ name: 'StaffView', params: { id: staff.id } });
};

const goToStaffEdit = (staff) => {
  router.push({ name: 'StaffView', params: { id: staff.id } });
};

onMounted(() => {
  fetchStaff();
});
</script>

