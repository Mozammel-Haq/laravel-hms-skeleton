<template>
  <div class="departments-page">
    <div class="d-flex justify-content-between align-items-center mb-4">
      <h4 class="mb-0">Departments</h4>
      <div>
        <button class="btn btn-primary me-2" @click="openForCreate" :disabled="loading">
          <i class="ti ti-plus me-2"></i> Add Department
        </button>
        <button class="btn btn-outline-secondary" @click="fetchDepartments" :disabled="loading">
          <i class="ti ti-refresh me-2"></i> Refresh
        </button>
      </div>
    </div>
    <div v-if="loading" class="text-center py-5">
      <div class="spinner-border text-primary" role="status"></div>
    </div>
    <div v-else class="card border-0 shadow-sm">
      <div class="card-body p-0">
        <div class="table-responsive">
          <table class="table table-hover align-middle mb-0">
            <thead class="bg-light">
              <tr>
                <th class="ps-4">Name</th>
                <th>Status</th>
                <th>Floor</th>
                <th>Phone Ext</th>
                <th class="text-end pe-4">Actions</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="dept in departments" :key="dept.id">
                <td class="ps-4">{{ dept.name }}</td>
                <td>
                  <span :class="['badge', dept.status === 'active' ? 'bg-success-subtle text-success' : 'bg-secondary-subtle text-secondary']">
                    {{ dept.status }}
                  </span>
                </td>
                <td>{{ dept.floor_number || '—' }}</td>
                <td>{{ dept.phone_extension || '—' }}</td>
                <td class="text-end pe-4">
                  <button class="btn btn-sm btn-light me-2" @click="openForEdit(dept)"><i class="ti ti-edit"></i></button>
                  <button class="btn btn-sm btn-light text-danger" @click="deleteItem(dept)"><i class="ti ti-trash"></i></button>
                </td>
              </tr>
              <tr v-if="departments.length === 0">
                <td colspan="5" class="text-center py-4 text-muted">No departments</td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
  <div v-if="showModal" class="modal fade show" style="display: block;" tabindex="-1">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">{{ isEditing ? 'Edit Department' : 'Add Department' }}</h5>
          <button type="button" class="btn-close" @click="closeModal"></button>
        </div>
        <div class="modal-body">
          <div v-if="formError" class="alert alert-danger">{{ formError }}</div>
          <div class="row g-3">
            <div class="col-md-6">
              <label class="form-label">Name</label>
              <input type="text" class="form-control" v-model="form.name" />
              <div class="text-danger small mt-1" v-if="errors.name">{{ errors.name }}</div>
            </div>
            <div class="col-md-6">
              <label class="form-label">Status</label>
              <select class="form-select" v-model="form.status">
                <option value="active">active</option>
                <option value="inactive">inactive</option>
              </select>
              <div class="text-danger small mt-1" v-if="errors.status">{{ errors.status }}</div>
            </div>
            <div class="col-md-6">
              <label class="form-label">Floor Number</label>
              <input type="text" class="form-control" v-model="form.floor_number" />
              <div class="text-danger small mt-1" v-if="errors.floor_number">{{ errors.floor_number }}</div>
            </div>
            <div class="col-md-6">
              <label class="form-label">Phone Extension</label>
              <input type="text" class="form-control" v-model="form.phone_extension" />
              <div class="text-danger small mt-1" v-if="errors.phone_extension">{{ errors.phone_extension }}</div>
            </div>
            <div class="col-12">
              <label class="form-label">Description</label>
              <textarea class="form-control" rows="3" v-model="form.description"></textarea>
              <div class="text-danger small mt-1" v-if="errors.description">{{ errors.description }}</div>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-light" @click="closeModal" :disabled="saving">Cancel</button>
          <button type="button" class="btn btn-primary" @click="submitForm" :disabled="saving">
            <span v-if="saving" class="spinner-border spinner-border-sm me-2"></span>
            {{ isEditing ? 'Update' : 'Create' }}
          </button>
        </div>
      </div>
    </div>
  </div>
  <div v-if="showModal" class="modal-backdrop fade show"></div>
  </template>
<script setup>
import { ref, onMounted } from 'vue';
import api from '../services/api';

const departments = ref([]);
const loading = ref(false);
const showModal = ref(false);
const isEditing = ref(false);
const saving = ref(false);
const currentId = ref(null);
const formError = ref('');
const form = ref({
  name: '',
  status: 'active',
  floor_number: '',
  phone_extension: '',
  description: ''
});
const errors = ref({});

const resetForm = () => {
  form.value = { name: '', status: 'active', floor_number: '', phone_extension: '', description: '' };
  errors.value = {};
  formError.value = '';
  currentId.value = null;
};

const openForCreate = () => {
  resetForm();
  isEditing.value = false;
  showModal.value = true;
};

const openForEdit = (item) => {
  isEditing.value = true;
  currentId.value = item.id;
  form.value = {
    name: item.name || '',
    status: item.status || 'active',
    floor_number: item.floor_number || '',
    phone_extension: item.phone_extension || '',
    description: item.description || ''
  };
  errors.value = {};
  formError.value = '';
  showModal.value = true;
};

const closeModal = () => {
  showModal.value = false;
};

const submitForm = async () => {
  saving.value = true;
  errors.value = {};
  formError.value = '';
  try {
    if (isEditing.value && currentId.value) {
      await api.patch(`/departments/${currentId.value}`, form.value);
    } else {
      await api.post('/departments', form.value);
    }
    await fetchDepartments();
    showModal.value = false;
  } catch (e) {
    if (e.response?.status === 422) {
      const ve = e.response.data.errors || {};
      const mapped = {};
      Object.keys(ve).forEach(k => mapped[k] = Array.isArray(ve[k]) ? ve[k][0] : ve[k]);
      errors.value = mapped;
      formError.value = e.response.data.message || 'Validation error';
    } else {
      formError.value = e.response?.data?.message || 'Failed to save';
    }
  } finally {
    saving.value = false;
  }
};

const deleteItem = async (item) => {
  if (!confirm('Delete this department?')) return;
  try {
    await api.delete(`/departments/${item.id}`);
    await fetchDepartments();
  } catch (e) {}
};

const fetchDepartments = async () => {
  loading.value = true;
  try {
    const res = await api.get('/departments');
    const list = res.data.data?.data || res.data.data || [];
    departments.value = list;
  } finally {
    loading.value = false;
  }
};

onMounted(fetchDepartments);
</script>
