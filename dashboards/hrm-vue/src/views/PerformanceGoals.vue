<template>
  <div class="container-fluid">
    <div class="card p-3 mb-3 border-0 shadow-sm">
      <div class="d-flex justify-content-between align-items-center bg-primary-subtle text-primary px-4 pt-3 pb-3 rounded-3 mb-0">
        <div>
          <h5 class="fw-bold mb-1 text-primary">Performance Goals</h5>
          <nav aria-label="breadcrumb">
            <ol class="breadcrumb breadcrumb-dots mb-0 text-muted small">
              <li class="breadcrumb-item">
                <router-link to="/">Dashboard</router-link>
              </li>
              <li class="breadcrumb-item">Performance</li>
              <li class="breadcrumb-item active" aria-current="page">Goals</li>
            </ol>
          </nav>
        </div>
        <div class="d-flex gap-2">
          <button
            type="button"
            class="btn btn-outline-primary"
            @click="fetchGoals"
            :disabled="loading"
          >
            <i class="ti ti-refresh me-2"></i>Refresh
          </button>
          <button
            type="button"
            class="btn btn-primary"
            @click="openForm()"
          >
            <i class="ti ti-plus me-2"></i>New Goal
          </button>
        </div>
      </div>
    </div>

    <div class="card shadow-sm border-0">
      <div class="card-body">
        <div class="row g-3 mb-3">
          <div class="col-md-3">
            <select v-model="filters.status" class="form-select" @change="fetchGoals">
              <option value="">All Status</option>
              <option value="draft">Draft</option>
              <option value="in_progress">In Progress</option>
              <option value="completed">Completed</option>
              <option value="cancelled">Cancelled</option>
            </select>
          </div>
        </div>

        <div v-if="loading" class="text-center py-5">
          <span class="spinner-border text-primary"></span>
        </div>

        <div v-else-if="goals.length === 0" class="text-center py-5 text-muted">
          No goals defined yet.
        </div>

        <div v-else class="table-responsive">
          <table class="table table-hover align-middle">
            <thead>
              <tr>
                <th>Goal</th>
                <th>Employee</th>
                <th>KPI</th>
                <th>Period</th>
                <th>Target</th>
                <th>Current</th>
                <th>Status</th>
                <th class="text-end">Actions</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="goal in goals" :key="goal.id">
                <td>
                  <div class="fw-semibold">{{ goal.title }}</div>
                  <div class="text-muted small" v-if="goal.notes">{{ truncate(goal.notes) }}</div>
                </td>
                <td>{{ goal.user?.name || '-' }}</td>
                <td>{{ goal.kpi?.name || '-' }}</td>
                <td>
                  <span v-if="goal.period_start || goal.period_end">
                    {{ formatDate(goal.period_start) }} - {{ formatDate(goal.period_end) }}
                  </span>
                  <span v-else>-</span>
                </td>
                <td>
                  <span v-if="goal.target_value != null">
                    {{ goal.target_value }} {{ goal.unit || '' }}
                  </span>
                  <span v-else>-</span>
                </td>
                <td>
                  <span v-if="goal.current_value != null">
                    {{ goal.current_value }} {{ goal.unit || '' }}
                  </span>
                  <span v-else>-</span>
                </td>
                <td>
                  <span class="badge bg-secondary-subtle text-secondary" v-if="goal.status === 'draft'">Draft</span>
                  <span class="badge bg-info-subtle text-info" v-else-if="goal.status === 'in_progress'">In Progress</span>
                  <span class="badge bg-success-subtle text-success" v-else-if="goal.status === 'completed'">Completed</span>
                  <span class="badge bg-danger-subtle text-danger" v-else>Cancelled</span>
                </td>
                <td class="text-end">
                  <div class="dropdown">
                    <button
                      type="button"
                      class="btn btn-sm btn-light btn-icon"
                      @click="toggleRowMenu(goal.id)"
                    >
                      <i class="ti ti-dots-vertical"></i>
                    </button>
                    <ul
                      class="dropdown-menu dropdown-menu-end shadow-sm border-0"
                      :class="{ show: openMenuId === goal.id }"
                    >
                      <li>
                        <a
                          href="#"
                          class="dropdown-item"
                          @click.prevent="() => { closeRowMenu(); openForm(goal); }"
                        >
                          <i class="ti ti-edit me-2"></i>Edit
                        </a>
                      </li>
                      <li>
                        <a
                          href="#"
                          class="dropdown-item text-danger"
                          @click.prevent="() => { closeRowMenu(); confirmDelete(goal); }"
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

    <div v-if="showForm">
      <div class="modal-backdrop fade show"></div>
      <div class="modal fade show d-block" tabindex="-1">
        <div class="modal-dialog modal-lg modal-dialog-centered">
          <div class="modal-content">
          <form @submit.prevent="handleSubmit">
            <div class="modal-header">
              <h5 class="modal-title">{{ editingGoal ? 'Edit Goal' : 'New Goal' }}</h5>
              <button type="button" class="btn-close" @click="closeForm"></button>
            </div>
            <div class="modal-body">
              <div class="row g-3">
                <div class="col-md-6">
                  <label class="form-label">Title</label>
                  <input v-model="form.title" type="text" class="form-control" required />
                </div>
                <div class="col-md-3">
                  <label class="form-label">Employee</label>
                  <input v-model="form.user_name" type="text" class="form-control" disabled />
                </div>
                <div class="col-md-3">
                  <label class="form-label">KPI</label>
                  <select v-model="form.kpi_id" class="form-select">
                    <option :value="null">None</option>
                    <option v-for="k in kpiOptions" :key="k.id" :value="k.id">{{ k.name }}</option>
                  </select>
                </div>
                <div class="col-md-3">
                  <label class="form-label">Start Date</label>
                  <input v-model="form.period_start" type="date" class="form-control" />
                </div>
                <div class="col-md-3">
                  <label class="form-label">End Date</label>
                  <input v-model="form.period_end" type="date" class="form-control" />
                </div>
                <div class="col-md-3">
                  <label class="form-label">Target</label>
                  <div class="input-group">
                    <input v-model.number="form.target_value" type="number" step="0.01" class="form-control" />
                    <input v-model="form.unit" type="text" class="form-control" placeholder="Unit" />
                  </div>
                </div>
                <div class="col-md-3">
                  <label class="form-label">Current</label>
                  <input v-model.number="form.current_value" type="number" step="0.01" class="form-control" />
                </div>
                <div class="col-md-3">
                  <label class="form-label">Status</label>
                  <select v-model="form.status" class="form-select">
                    <option value="draft">Draft</option>
                    <option value="in_progress">In Progress</option>
                    <option value="completed">Completed</option>
                    <option value="cancelled">Cancelled</option>
                  </select>
                </div>
                <div class="col-12">
                  <label class="form-label">Notes</label>
                  <textarea v-model="form.notes" rows="3" class="form-control"></textarea>
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
    </div>
  </div>
  </div>
</template>

<script setup>
import { onMounted, reactive, ref } from 'vue';
import api from '../services/api';

const goals = ref([]);
const loading = ref(false);
const saving = ref(false);
const formError = ref('');
const showForm = ref(false);
const editingGoal = ref(null);
const kpiOptions = ref([]);

const filters = reactive({
  status: '',
});

const form = reactive({
  id: null,
  title: '',
  user_id: null,
  user_name: '',
  kpi_id: null,
  period_start: '',
  period_end: '',
  target_value: null,
  current_value: null,
  unit: '',
  status: 'draft',
  notes: '',
});

const openMenuId = ref(null);

const truncate = (text, length = 80) => {
  if (!text) return '';
  return text.length > length ? text.slice(0, length) + '…' : text;
};

const formatDate = (value) => {
  if (!value) return '';
  return new Date(value).toLocaleDateString();
};

const fetchGoals = async () => {
  loading.value = true;
  try {
    const response = await api.get('/performance-goals', {
      params: {
        status: filters.status || undefined,
      },
    });
    goals.value = response.data.data?.data || response.data.data || [];
  } catch (e) {
    console.error('Failed to load goals', e);
  } finally {
    loading.value = false;
  }
};

const toggleRowMenu = (id) => {
  openMenuId.value = openMenuId.value === id ? null : id;
};

const closeRowMenu = () => {
  openMenuId.value = null;
};

const fetchKpiOptions = async () => {
  try {
    const response = await api.get('/performance-kpis', {
      params: { status: 'active' },
    });
    kpiOptions.value = response.data.data || [];
  } catch (e) {
    console.error('Failed to load KPI options', e);
  }
};

const openForm = (goal = null) => {
  if (goal) {
    editingGoal.value = goal;
    form.id = goal.id;
    form.title = goal.title;
    form.user_id = goal.user_id;
    form.user_name = goal.user?.name || '';
    form.kpi_id = goal.kpi_id;
    form.period_start = goal.period_start || '';
    form.period_end = goal.period_end || '';
    form.target_value = goal.target_value;
    form.current_value = goal.current_value;
    form.unit = goal.unit || '';
    form.status = goal.status || 'draft';
    form.notes = goal.notes || '';
  } else {
    editingGoal.value = null;
    form.id = null;
    form.title = '';
    form.user_id = null;
    form.user_name = 'Me';
    form.kpi_id = null;
    form.period_start = '';
    form.period_end = '';
    form.target_value = null;
    form.current_value = null;
    form.unit = '';
    form.status = 'draft';
    form.notes = '';
  }
  formError.value = '';
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
      kpi_id: form.kpi_id || null,
      period_start: form.period_start || null,
      period_end: form.period_end || null,
      target_value: form.target_value,
      current_value: form.current_value,
      unit: form.unit || null,
      status: form.status,
      notes: form.notes || null,
    };

    if (editingGoal.value) {
      await api.put(`/performance-goals/${editingGoal.value.id}`, payload);
    } else {
      await api.post('/performance-goals', payload);
    }

    showForm.value = false;
    await fetchGoals();
  } catch (e) {
    console.error('Failed to save goal', e);
    formError.value = e.response?.data?.message || 'Failed to save goal';
  } finally {
    saving.value = false;
  }
};

const confirmDelete = async (goal) => {
  if (!window.confirm(`Delete performance goal "${goal.title}"?`)) return;
  try {
    await api.delete(`/performance-goals/${goal.id}`);
    await fetchGoals();
  } catch (e) {
    console.error('Failed to delete goal', e);
  }
};

onMounted(async () => {
  await Promise.all([fetchGoals(), fetchKpiOptions()]);
});
</script>
