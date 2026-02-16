<template>
  <div class="staff-view-page">
    <div class="card p-3 mb-3 border-0 shadow-sm">
      <div class="d-flex justify-content-between align-items-center bg-primary-subtle text-primary px-4 pt-3 pb-3 rounded-3 mb-0">
        <div>
          <h5 class="fw-bold mb-1 text-primary">Staff Profile</h5>
          <nav aria-label="breadcrumb">
            <ol class="breadcrumb breadcrumb-dots mb-0 text-muted small">
              <li class="breadcrumb-item">
                <router-link to="/">Dashboard</router-link>
              </li>
              <li class="breadcrumb-item">
                <router-link :to="{ name: 'StaffDirectory' }">Staff</router-link>
              </li>
              <li class="breadcrumb-item active" aria-current="page">
                {{ staff?.name || 'Profile' }}
              </li>
            </ol>
          </nav>
        </div>
        <button class="btn btn-sm btn-outline-primary" @click="goBack">
          <i class="ti ti-arrow-left me-1"></i>
          Back to Directory
        </button>
      </div>
    </div>

    <div v-if="loading" class="text-center py-5">
      <div class="spinner-border text-primary" role="status">
        <span class="visually-hidden">Loading...</span>
      </div>
    </div>

    <div v-else-if="error" class="alert alert-danger">
      {{ error }}
    </div>

    <div v-else-if="staff" class="row g-4">
      <div class="col-md-4">
        <div class="card border-0 shadow-sm">
          <div class="card-body text-center">
            <div
              class="avatar bg-primary-subtle text-primary rounded-circle d-flex align-items-center justify-content-center fw-bold mx-auto mb-3"
              style="width: 72px; height: 72px;"
            >
              {{ getInitials(staff.name) }}
            </div>
            <h5 class="mb-1">{{ staff.name }}</h5>
            <p class="text-muted mb-2">{{ staff.email }}</p>
            <div class="mb-2">
              <span
                v-for="role in staff.roles"
                :key="role.id"
                class="badge bg-primary-subtle text-primary border border-primary-subtle me-1"
              >
                {{ role.name }}
              </span>
            </div>
            <p class="text-muted mb-0">
              Joined {{ formatDate(staff.created_at) }}
            </p>
          </div>
        </div>
      </div>

      <div class="col-md-8">
        <div class="card border-0 shadow-sm mb-3">
          <div class="card-header bg-white border-0 py-3">
            <h5 class="mb-0">Details</h5>
          </div>
          <div class="card-body">
            <div class="row mb-3">
              <div class="col-sm-4 text-muted">Clinic</div>
              <div class="col-sm-8">{{ staff.clinic?.name || 'N/A' }}</div>
            </div>
            <div class="row mb-3">
              <div class="col-sm-4 text-muted">Department</div>
              <div class="col-sm-8">{{ staff.department?.name || 'N/A' }}</div>
            </div>
            <div class="row mb-3">
              <div class="col-sm-4 text-muted">Designation</div>
              <div class="col-sm-8">{{ staff.designation?.name || 'N/A' }}</div>
            </div>
            <div class="row mb-3">
              <div class="col-sm-4 text-muted">Status</div>
              <div class="col-sm-8">
                <span class="badge bg-success-subtle text-success" v-if="!staff.deleted_at">
                  Active
                </span>
                <span class="badge bg-danger-subtle text-danger" v-else>
                  Deleted
                </span>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { onMounted, ref } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import api from '../services/api';

const route = useRoute();
const router = useRouter();

const staff = ref(null);
const loading = ref(false);
const error = ref('');

const getInitials = (name) => {
  if (!name) return '??';
  return name.split(' ').map(n => n[0]).join('').toUpperCase().substring(0, 2);
};

const formatDate = (dateString) => {
  if (!dateString) return 'N/A';
  return new Date(dateString).toLocaleDateString();
};

const goBack = () => {
  router.push({ name: 'StaffDirectory' });
};

const fetchStaff = async () => {
  loading.value = true;
  error.value = '';
  try {
    const id = route.params.id;
    const res = await api.get(`/staff/${id}`);
    staff.value = res.data.data || res.data;
  } catch (err) {
    console.error('Failed to load staff', err);
    const message = err.response?.data?.message || 'Failed to load staff profile';
    error.value = message;
  } finally {
    loading.value = false;
  }
};

onMounted(fetchStaff);
</script>
