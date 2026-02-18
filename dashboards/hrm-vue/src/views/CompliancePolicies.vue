<template>
  <div class="container-fluid">
    <div class="card p-3 mb-3 border-0 shadow-sm">
      <div class="d-flex justify-content-between align-items-center bg-primary-subtle text-primary px-4 pt-3 pb-3 rounded-3 mb-0">
        <div>
          <h5 class="fw-bold mb-1 text-primary">Compliance Policies</h5>
          <nav aria-label="breadcrumb">
            <ol class="breadcrumb breadcrumb-dots mb-0 text-muted small">
              <li class="breadcrumb-item">
                <router-link to="/">Dashboard</router-link>
              </li>
              <li class="breadcrumb-item">Compliance</li>
              <li class="breadcrumb-item active" aria-current="page">Policies</li>
            </ol>
          </nav>
        </div>
        <div class="d-flex gap-2">
          <button
            type="button"
            class="btn btn-outline-primary"
            @click="fetchPolicies"
            :disabled="loading"
          >
            <i class="ti ti-refresh me-2"></i>Refresh
          </button>
          <button
            type="button"
            class="btn btn-primary"
            @click="openForm()"
          >
            <i class="ti ti-plus me-2"></i>New Policy
          </button>
        </div>
      </div>
    </div>

    <div class="card shadow-sm border-0 mb-3">
      <div class="card-body">
        <div class="row g-3">
          <div class="col-md-4">
            <label class="form-label">Search</label>
            <input
              v-model="filters.search"
              type="text"
              class="form-control"
              placeholder="Title or category"
              @keyup.enter="fetchPolicies"
            />
          </div>
          <div class="col-md-3">
            <label class="form-label">Status</label>
            <select v-model="filters.status" class="form-select" @change="fetchPolicies">
              <option value="">All Status</option>
              <option value="draft">Draft</option>
              <option value="active">Active</option>
              <option value="archived">Archived</option>
            </select>
          </div>
          <div class="col-md-3 d-flex align-items-end">
            <div class="d-flex gap-2">
              <button class="btn btn-outline-primary" type="button" @click="fetchPolicies">
                Apply
              </button>
              <button class="btn btn-light" type="button" @click="resetFilters">
                Reset
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="card border-0 shadow-sm">
      <div class="card-body p-0">
        <div v-if="loading" class="text-center py-5">
          <span class="spinner-border text-primary"></span>
        </div>

        <div v-else-if="policies.length === 0" class="text-center py-5 text-muted">
          No policies defined yet.
        </div>

        <div v-else class="table-responsive">
          <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
              <tr>
                <th class="ps-4">Title</th>
                <th>Category</th>
                <th>Effective From</th>
                <th>Status</th>
                <th class="text-end pe-4">Actions</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="item in policies" :key="item.id">
                <td class="ps-4">
                  <div class="fw-semibold">{{ item.title }}</div>
                  <div class="text-muted small" v-if="item.description">
                    {{ truncate(item.description) }}
                  </div>
                </td>
                <td>{{ item.category || '-' }}</td>
                <td>{{ item.effective_from ? formatDate(item.effective_from) : '-' }}</td>
                <td>
                  <span class="badge" :class="statusClass(item.status)">
                    {{ statusLabel(item.status) }}
                  </span>
                </td>
                <td class="text-end pe-4">
                  <div class="dropdown">
                    <button
                      type="button"
                      class="btn btn-sm btn-light btn-icon"
                      @click="toggleRowMenu(item.id)"
                    >
                      <i class="ti ti-dots-vertical"></i>
                    </button>
                    <ul
                      class="dropdown-menu dropdown-menu-end shadow-sm border-0"
                      :class="{ show: openMenuId === item.id }"
                    >
                      <li>
                        <a
                          href="#"
                          class="dropdown-item"
                          @click.prevent="() => { closeRowMenu(); openForm(item); }"
                        >
                          <i class="ti ti-edit me-2"></i>Edit
                        </a>
                      </li>
                      <li>
                        <a
                          href="#"
                          class="dropdown-item text-danger"
                          :class="{ disabled: item.status === 'archived' || savingId === item.id }"
                          @click.prevent="() => { closeRowMenu(); archivePolicy(item); }"
                        >
                          <i class="ti ti-archive me-2"></i>Archive
                        </a>
                      </li>
                    </ul>
                  </div>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>

    <div class="modal fade show d-block" tabindex="-1" v-if="showForm">
      <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
          <form @submit.prevent="handleSubmit">
            <div class="modal-header">
              <h5 class="modal-title">{{ editingPolicy ? 'Edit Policy' : 'New Policy' }}</h5>
              <button type="button" class="btn-close" @click="closeForm"></button>
            </div>
            <div class="modal-body">
              <div class="row g-3">
                <div class="col-md-8">
                  <label class="form-label">Title</label>
                  <input v-model="form.title" type="text" class="form-control" required />
                </div>
                <div class="col-md-4">
                  <label class="form-label">Category</label>
                  <input v-model="form.category" type="text" class="form-control" />
                </div>
                <div class="col-md-4">
                  <label class="form-label">Effective From</label>
                  <input v-model="form.effective_from" type="date" class="form-control" />
                </div>
                <div class="col-md-4">
                  <label class="form-label">Status</label>
                  <select v-model="form.status" class="form-select">
                    <option value="draft">Draft</option>
                    <option value="active">Active</option>
                    <option value="archived">Archived</option>
                  </select>
                </div>
                <div class="col-12">
                  <label class="form-label">Description</label>
                  <textarea v-model="form.description" rows="4" class="form-control"></textarea>
                </div>
              </div>
              <div v-if="formError" class="alert alert-danger mt-3">
                {{ formError }}
              </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-light" @click="closeForm">Cancel</button>
              <button type="submit" class="btn btn-primary" :disabled="saving">
                <span v-if="saving" class="spinner-border spinner-border-sm me-1"></span>
                Save
              </button>
            </div>
          </form>
        </div>
      </div>
      <div class="modal-backdrop fade show"></div>
    </div>
  </div>
</template>

<script setup>
import { onMounted, reactive, ref } from 'vue';
import api from '../services/api';
import { useToastStore } from '../store/toastStore';

const toast = useToastStore();

const policies = ref([]);
const loading = ref(false);
const saving = ref(false);
const savingId = ref(null);
const showForm = ref(false);
const editingPolicy = ref(null);
const formError = ref('');

const filters = reactive({
  search: '',
  status: '',
});

const form = reactive({
  title: '',
  category: '',
  effective_from: '',
  description: '',
  status: 'draft',
});

const openMenuId = ref(null);

const truncate = (text, length = 80) => {
  if (!text) return '';
  return text.length > length ? text.slice(0, length) + '…' : text;
};

const formatDate = (value) => {
  if (!value) return '';
  const d = new Date(value);
  if (Number.isNaN(d.getTime())) return '';
  return d.toLocaleDateString();
};

const statusLabel = (status) => {
  if (status === 'active') return 'Active';
  if (status === 'archived') return 'Archived';
  return 'Draft';
};

const statusClass = (status) => {
  if (status === 'active') return 'bg-success-subtle text-success';
  if (status === 'archived') return 'bg-danger-subtle text-danger';
  return 'bg-secondary-subtle text-secondary';
};

const fetchPolicies = async () => {
  loading.value = true;
  try {
    const response = await api.get('/compliance-policies', {
      params: {
        search: filters.search || undefined,
        status: filters.status || undefined,
      },
    });
    policies.value = response.data.data || [];
  } catch (e) {
    console.error('Failed to load policies', e);
    toast.error('Failed to load policies');
  } finally {
    loading.value = false;
  }
};

const resetFilters = () => {
  filters.search = '';
  filters.status = '';
  fetchPolicies();
};

const resetForm = () => {
  form.title = '';
  form.category = '';
  form.effective_from = '';
  form.description = '';
  form.status = 'draft';
  formError.value = '';
};

const openForm = (policy = null) => {
  if (policy) {
    editingPolicy.value = policy;
    form.title = policy.title || '';
    form.category = policy.category || '';
    form.effective_from = policy.effective_from || '';
    form.description = policy.description || '';
    form.status = policy.status || 'draft';
  } else {
    editingPolicy.value = null;
    resetForm();
  }
  showForm.value = true;
};

const closeForm = () => {
  showForm.value = false;
};

const handleSubmit = async () => {
  saving.value = true;
  formError.value = '';
  try {
    const payload = {
      title: form.title,
      category: form.category || null,
      effective_from: form.effective_from || null,
      description: form.description || null,
      status: form.status,
    };
    if (editingPolicy.value) {
      savingId.value = editingPolicy.value.id;
      await api.put(`/compliance-policies/${editingPolicy.value.id}`, payload);
    } else {
      await api.post('/compliance-policies', payload);
    }
    showForm.value = false;
    await fetchPolicies();
  } catch (e) {
    console.error('Failed to save policy', e);
    formError.value = e.response?.data?.message || 'Failed to save policy';
    toast.error(formError.value);
  } finally {
    saving.value = false;
    savingId.value = null;
  }
};

const archivePolicy = async (policy) => {
  if (!window.confirm(`Archive policy "${policy.title}"?`)) return;
  savingId.value = policy.id;
  try {
    await api.delete(`/compliance-policies/${policy.id}`);
    await fetchPolicies();
  } catch (e) {
    console.error('Failed to archive policy', e);
    toast.error('Failed to archive policy');
  } finally {
    savingId.value = null;
  }
};

const toggleRowMenu = (id) => {
  openMenuId.value = openMenuId.value === id ? null : id;
};

const closeRowMenu = () => {
  openMenuId.value = null;
};

onMounted(fetchPolicies);
</script>
