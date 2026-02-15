<template>
  <div class="designations-page">
    <div class="d-flex justify-content-between align-items-center mb-4">
      <h4 class="mb-0">Designations</h4>
      <div>
        <button class="btn btn-primary me-2" @click="openForCreate" :disabled="loading">
          <i class="ti ti-plus me-2"></i> Add Designation
        </button>
        <button class="btn btn-outline-secondary" @click="fetchDesignations" :disabled="loading">
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
                <th>Code</th>
                <th>Grade</th>
                <th>Status</th>
                <th class="text-end pe-4">Actions</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="d in rows" :key="d.id">
                <td class="ps-4">{{ d.name }}</td>
                <td>{{ d.code || '—' }}</td>
                <td>{{ d.grade || '—' }}</td>
                <td>
                  <span :class="['badge', d.status === 'active' ? 'bg-success-subtle text-success' : 'bg-secondary-subtle text-secondary']">
                    {{ d.status }}
                  </span>
                </td>
                <td class="text-end pe-4">
                  <button class="btn btn-sm btn-light me-2" @click="openForEdit(d)"><i class="ti ti-edit"></i></button>
                  <button class="btn btn-sm btn-light text-danger" @click="deleteItem(d)"><i class="ti ti-trash"></i></button>
                </td>
              </tr>
              <tr v-if="rows.length === 0">
                <td colspan="5" class="text-center py-4 text-muted">No designations</td>
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
          <h5 class="modal-title">{{ isEditing ? 'Edit Designation' : 'Add Designation' }}</h5>
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
              <label class="form-label">Slug</label>
              <input type="text" class="form-control" v-model="form.slug" />
              <div class="text-danger small mt-1" v-if="errors.slug">{{ errors.slug }}</div>
            </div>
            <div class="col-md-6">
              <label class="form-label">Code</label>
              <input type="text" class="form-control" v-model="form.code" />
              <div class="text-danger small mt-1" v-if="errors.code">{{ errors.code }}</div>
            </div>
            <div class="col-md-6">
              <label class="form-label">Grade</label>
              <input type="text" class="form-control" v-model="form.grade" />
              <div class="text-danger small mt-1" v-if="errors.grade">{{ errors.grade }}</div>
            </div>
            <div class="col-md-6">
              <label class="form-label">Status</label>
              <select class="form-select" v-model="form.status">
                <option value="active">active</option>
                <option value="inactive">inactive</option>
              </select>
              <div class="text-danger small mt-1" v-if="errors.status">{{ errors.status }}</div>
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

const rows = ref([]);
const loading = ref(false);
const showModal = ref(false);
const isEditing = ref(false);
const saving = ref(false);
const currentId = ref(null);
const formError = ref('');
const form = ref({
  name: '',
  slug: '',
  code: '',
  grade: '',
  status: 'active',
  description: ''
});
const errors = ref({});

const fetchDesignations = async () => {
  loading.value = true;
  try {
    const res = await api.get('/designations');
    rows.value = res.data.data.data || res.data.data;
  } finally {
    loading.value = false;
  }
};

onMounted(fetchDesignations);

const resetForm = () => {
  form.value = { name: '', slug: '', code: '', grade: '', status: 'active', description: '' };
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
    slug: item.slug || '',
    code: item.code || '',
    grade: item.grade || '',
    status: item.status || 'active',
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
      await api.patch(`/designations/${currentId.value}`, form.value);
    } else {
      await api.post('/designations', form.value);
    }
    await fetchDesignations();
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
  if (!confirm('Delete this designation?')) return;
  try {
    await api.delete(`/designations/${item.id}`);
    await fetchDesignations();
  } catch (e) {}
};
</script>
