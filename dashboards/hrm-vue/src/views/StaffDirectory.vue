<template>
  <div class="staff-page">
    <div class="d-flex justify-content-between align-items-center mb-4">
      <h4 class="mb-0">Staff Directory</h4>
      <div class="d-flex gap-2">
        <button class="btn btn-outline-secondary" @click="fetchStaff" :disabled="loading">
          <i class="ti ti-refresh me-2"></i> Refresh
        </button>
        <button class="btn btn-primary">
          <i class="ti ti-plus me-2"></i> Add Staff
        </button>
      </div>
    </div>

    <!-- Stats Row -->
    <div class="row g-3 mb-4">
      <div class="col-md-3">
        <div class="position-relative overflow-hidden rounded-4 h-100 kpi-card kpi-primary" data-bs-theme="light,dark">
          <div class="position-absolute top-0 end-0 w-100 h-100 opacity-25 pattern-bg">
            <svg width="100%" height="100%" xmlns="http://www.w3.org/2000/svg">
              <defs>
                <pattern id="staff-kpi-1" x="0" y="0" width="30" height="30" patternUnits="userSpaceOnUse">
                  <rect x="0" y="0" width="2" height="2" fill="var(--primary-color)" fill-opacity="0.2" />
                </pattern>
              </defs>
              <rect width="100%" height="100%" fill="url(#staff-kpi-1)" />
            </svg>
          </div>
          <div class="card-body p-3 text-center position-relative z-1">
            <div class="text-muted small mb-1">Total Staff</div>
            <div class="h4 mb-0">{{ staffList.length }}</div>
          </div>
        </div>
      </div>
      <div class="col-md-3">
        <div class="position-relative overflow-hidden rounded-4 h-100 kpi-card kpi-info" data-bs-theme="light,dark">
          <div class="position-absolute top-0 end-0 w-100 h-100 opacity-25 pattern-bg">
            <svg width="100%" height="100%" xmlns="http://www.w3.org/2000/svg">
              <defs>
                <pattern id="staff-kpi-2" x="0" y="0" width="30" height="30" patternUnits="userSpaceOnUse">
                  <rect x="0" y="0" width="2" height="2" fill="var(--info-color)" fill-opacity="0.2" />
                </pattern>
              </defs>
              <rect width="100%" height="100%" fill="url(#staff-kpi-2)" />
            </svg>
          </div>
          <div class="card-body p-3 text-center position-relative z-1">
            <div class="text-muted small mb-1">Doctors</div>
            <div class="h4 mb-0 text-primary">{{ staffList.filter(s => s.roles?.some(r => r.name === 'Doctor')).length }}</div>
          </div>
        </div>
      </div>
      <div class="col-md-3">
        <div class="position-relative overflow-hidden rounded-4 h-100 kpi-card kpi-info" data-bs-theme="light,dark">
          <div class="position-absolute top-0 end-0 w-100 h-100 opacity-25 pattern-bg">
            <svg width="100%" height="100%" xmlns="http://www.w3.org/2000/svg">
              <defs>
                <pattern id="staff-kpi-3" x="0" y="0" width="30" height="30" patternUnits="userSpaceOnUse">
                  <rect x="0" y="0" width="2" height="2" fill="var(--info-color)" fill-opacity="0.2" />
                </pattern>
              </defs>
              <rect width="100%" height="100%" fill="url(#staff-kpi-3)" />
            </svg>
          </div>
          <div class="card-body p-3 text-center position-relative z-1">
            <div class="text-muted small mb-1">Nurses</div>
            <div class="h4 mb-0 text-info">0</div>
          </div>
        </div>
      </div>
      <div class="col-md-3">
        <div class="position-relative overflow-hidden rounded-4 h-100 kpi-card kpi-success" data-bs-theme="light,dark">
          <div class="position-absolute top-0 end-0 w-100 h-100 opacity-25 pattern-bg">
            <svg width="100%" height="100%" xmlns="http://www.w3.org/2000/svg">
              <defs>
                <pattern id="staff-kpi-4" x="0" y="0" width="30" height="30" patternUnits="userSpaceOnUse">
                  <rect x="0" y="0" width="2" height="2" fill="var(--success-color)" fill-opacity="0.2" />
                </pattern>
              </defs>
              <rect width="100%" height="100%" fill="url(#staff-kpi-4)" />
            </svg>
          </div>
          <div class="card-body p-3 text-center position-relative z-1">
            <div class="text-muted small mb-1">On Duty</div>
            <div class="h4 mb-0 text-success">{{ staffList.length }}</div>
          </div>
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
                <th>Role</th>
                <th>Contact</th>
                <th>Status</th>
                <th>Join Date</th>
                <th class="text-end pe-4">Actions</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="staff in staffList" :key="staff.id">
                <td class="ps-4">
                  <div class="d-flex align-items-center">
                    <div class="avatar bg-primary-subtle text-primary rounded-circle me-3 d-flex align-items-center justify-content-center fw-bold" style="width: 40px; height: 40px;">
                      {{ getInitials(staff.name) }}
                    </div>
                    <div>
                      <div class="fw-semibold">{{ staff.name }}</div>
                      <div class="text-muted fs-12">ID: #{{ staff.id }}</div>
                    </div>
                  </div>
                </td>
                <td>
                  <span v-for="role in staff.roles" :key="role.id" class="badge bg-light text-dark me-1">
                    {{ role.display_name || role.name }}
                  </span>
                </td>
                <td>
                  <div class="fs-13">{{ staff.email }}</div>
                  <div class="text-muted fs-12">{{ staff.phone || 'No phone' }}</div>
                </td>
                <td>
                  <span class="badge bg-success-subtle text-success">
                    Active
                  </span>
                </td>
                <td>{{ formatDate(staff.created_at) }}</td>
                <td class="text-end pe-4">
                  <button class="btn btn-sm btn-light me-2"><i class="ti ti-edit"></i></button>
                  <button class="btn btn-sm btn-light text-primary"><i class="ti ti-eye"></i></button>
                </td>
              </tr>
              <tr v-if="staffList.length === 0">
                <td colspan="6" class="text-center py-4 text-muted">No staff found</td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import api from '../services/api';

const staffList = ref([]);
const loading = ref(false);

const fetchStaff = async () => {
  loading.value = true;
  try {
    const response = await api.get('/staff');
    staffList.value = response.data.data.data || response.data.data;
  } catch (error) {
    console.error('Failed to fetch staff:', error);
  } finally {
    loading.value = false;
  }
};

const getInitials = (name) => {
  if (!name) return '??';
  return name.split(' ').map(n => n[0]).join('').toUpperCase().substring(0, 2);
};

const formatDate = (dateString) => {
  if (!dateString) return 'N/A';
  return new Date(dateString).toLocaleDateString();
};

onMounted(() => {
  fetchStaff();
});
</script>
