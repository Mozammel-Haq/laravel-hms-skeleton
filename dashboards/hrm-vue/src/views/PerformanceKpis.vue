<template>
  <div class="container-fluid">
    <div class="card p-3 mb-3 border-0 shadow-sm">
      <div class="d-flex justify-content-between align-items-center bg-primary-subtle text-primary px-4 pt-3 pb-3 rounded-3 mb-0">
        <div>
          <h5 class="fw-bold mb-1 text-primary">Performance KPIs</h5>
          <nav aria-label="breadcrumb">
            <ol class="breadcrumb breadcrumb-dots mb-0 text-muted small">
              <li class="breadcrumb-item">
                <router-link to="/">Dashboard</router-link>
              </li>
              <li class="breadcrumb-item">Performance</li>
              <li class="breadcrumb-item active" aria-current="page">KPIs</li>
            </ol>
          </nav>
        </div>
        <div class="d-flex gap-2">
          <button
            type="button"
            class="btn btn-outline-primary"
            @click="fetchKpis"
            :disabled="loading"
          >
            <i class="ti ti-refresh me-2"></i>Refresh
          </button>
          <button
            type="button"
            class="btn btn-primary"
            @click="openForm()"
          >
            <i class="ti ti-plus me-2"></i>New KPI
          </button>
        </div>
      </div>
    </div>

    <div class="card shadow-sm border-0">
      <div class="card-body">
        <div class="row g-3 mb-3">
          <div class="col-md-4">
            <input
              v-model="filters.search"
              type="text"
              class="form-control"
              placeholder="Search by name, code or category"
              @input="fetchKpis"
            />
          </div>
          <div class="col-md-3">
            <select v-model="filters.status" class="form-select" @change="fetchKpis">
              <option value="">All Status</option>
              <option value="draft">Draft</option>
              <option value="active">Active</option>
              <option value="inactive">Inactive</option>
              <option value="archived">Archived</option>
            </select>
          </div>
        </div>

        <div v-if="loading" class="text-center py-5">
          <span class="spinner-border text-primary"></span>
        </div>

        <div v-else-if="kpis.length === 0" class="text-center py-5 text-muted">
          No KPIs defined yet.
        </div>

        <div v-else class="table-responsive">
          <table class="table table-hover align-middle">
            <thead>
              <tr>
                <th>Name</th>
                <th>Code</th>
                <th>Category</th>
                <th>Frequency</th>
                <th>Weight</th>
                <th>Status</th>
                <th class="text-end">Actions</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="kpi in kpis" :key="kpi.id">
                <td>
                  <div class="fw-semibold">{{ kpi.name }}</div>
                  <div class="text-muted small" v-if="kpi.description">{{ truncate(kpi.description) }}</div>
                </td>
                <td>{{ kpi.code || '-' }}</td>
                <td>{{ kpi.category || '-' }}</td>
                <td class="text-capitalize">{{ kpi.frequency }}</td>
                <td>{{ kpi.weight }}%</td>
                <td>
                  <span class="badge bg-secondary-subtle text-secondary" v-if="kpi.status === 'draft'">Draft</span>
                  <span class="badge bg-success-subtle text-success" v-else-if="kpi.status === 'active'">Active</span>
                  <span class="badge bg-warning-subtle text-warning" v-else-if="kpi.status === 'inactive'">Inactive</span>
                  <span class="badge bg-danger-subtle text-danger" v-else>Archived</span>
                </td>
                <td class="text-end">
                  <div class="dropdown">
                    <button
                      type="button"
                      class="btn btn-sm btn-light btn-icon"
                      @click="toggleRowMenu(kpi.id)"
                    >
                      <i class="ti ti-dots-vertical"></i>
                    </button>
                    <ul
                      class="dropdown-menu dropdown-menu-end shadow-sm border-0"
                      :class="{ show: openMenuId === kpi.id }"
                    >
                      <li>
                        <a
                          href="#"
                          class="dropdown-item"
                          @click.prevent="() => { closeRowMenu(); openForm(kpi); }"
                        >
                          <i class="ti ti-edit me-2"></i>Edit
                        </a>
                      </li>
                      <li>
                        <a
                          href="#"
                          class="dropdown-item text-danger"
                          @click.prevent="() => { closeRowMenu(); confirmDelete(kpi); }"
                        >
                          <i class="ti ti-trash me-2"></i>Delete
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
              <h5 class="modal-title">{{ editingKpi ? 'Edit KPI' : 'New KPI' }}</h5>
              <button type="button" class="btn-close" @click="closeForm"></button>
            </div>
            <div class="modal-body">
              <div class="row g-3">
                <div class="col-md-6">
                  <label class="form-label">Name</label>
                  <input v-model="form.name" type="text" class="form-control" required />
                </div>
                <div class="col-md-3">
                  <label class="form-label">Code</label>
                  <input v-model="form.code" type="text" class="form-control" />
                </div>
                <div class="col-md-3">
                  <label class="form-label">Category</label>
                  <input v-model="form.category" type="text" class="form-control" />
                </div>
                <div class="col-md-4">
                  <label class="form-label">Frequency</label>
                  <select v-model="form.frequency" class="form-select">
                    <option value="annually">Annually</option>
                    <option value="quarterly">Quarterly</option>
                    <option value="monthly">Monthly</option>
                  </select>
                </div>
                <div class="col-md-4">
                  <label class="form-label">Weight (%)</label>
                  <input v-model.number="form.weight" type="number" min="0" max="100" class="form-control" />
                </div>
                <div class="col-md-4">
                  <label class="form-label">Status</label>
                  <select v-model="form.status" class="form-select">
                    <option value="draft">Draft</option>
                    <option value="active">Active</option>
                    <option value="inactive">Inactive</option>
                    <option value="archived">Archived</option>
                  </select>
                </div>
                <div class="col-12">
                  <label class="form-label">Description</label>
                  <textarea v-model="form.description" rows="3" class="form-control"></textarea>
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

const kpis = ref([]);
const loading = ref(false);
const saving = ref(false);
const formError = ref('');
const showForm = ref(false);
const editingKpi = ref(null);

const filters = reactive({
  search: '',
  status: '',
});

const form = reactive({
  name: '',
  code: '',
  category: '',
  frequency: 'annually',
  weight: 0,
  description: '',
  status: 'draft',
});

const openMenuId = ref(null);

const resetForm = () => {
  form.name = '';
  form.code = '';
  form.category = '';
  form.frequency = 'annually';
  form.weight = 0;
  form.description = '';
  form.status = 'draft';
  formError.value = '';
};

const truncate = (text, length = 80) => {
  if (!text) return '';
  return text.length > length ? text.slice(0, length) + '…' : text;
};

const fetchKpis = async () => {
  loading.value = true;
  try {
    const response = await api.get('/performance-kpis', {
      params: {
        search: filters.search || undefined,
        status: filters.status || undefined,
      },
    });
    kpis.value = response.data.data || [];
  } catch (e) {
    console.error('Failed to load KPIs', e);
  } finally {
    loading.value = false;
  }
};

const openForm = (kpi = null) => {
  if (kpi) {
    editingKpi.value = kpi;
    form.name = kpi.name;
    form.code = kpi.code || '';
    form.category = kpi.category || '';
    form.frequency = kpi.frequency || 'annually';
    form.weight = kpi.weight ?? 0;
    form.description = kpi.description || '';
    form.status = kpi.status || 'draft';
  } else {
    editingKpi.value = null;
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
    if (editingKpi.value) {
      await api.put(`/performance-kpis/${editingKpi.value.id}`, form);
    } else {
      await api.post('/performance-kpis', form);
    }
    showForm.value = false;
    await fetchKpis();
  } catch (e) {
    console.error('Failed to save KPI', e);
    formError.value = e.response?.data?.message || 'Failed to save KPI';
  } finally {
    saving.value = false;
  }
};

const confirmDelete = async (kpi) => {
  if (!window.confirm(`Delete KPI "${kpi.name}"?`)) return;
  try {
    await api.delete(`/performance-kpis/${kpi.id}`);
    await fetchKpis();
  } catch (e) {
    console.error('Failed to delete KPI', e);
  }
};

const toggleRowMenu = (id) => {
  openMenuId.value = openMenuId.value === id ? null : id;
};

const closeRowMenu = () => {
  openMenuId.value = null;
};

onMounted(fetchKpis);
</script>
