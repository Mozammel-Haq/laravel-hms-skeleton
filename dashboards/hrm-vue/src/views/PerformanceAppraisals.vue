<template>
  <div class="container-fluid">
    <div class="card p-3 mb-3 border-0 shadow-sm">
      <div class="d-flex justify-content-between align-items-center bg-primary-subtle text-primary px-4 pt-3 pb-3 rounded-3 mb-0">
        <div>
          <h5 class="fw-bold mb-1 text-primary">Performance Appraisals</h5>
          <nav aria-label="breadcrumb">
            <ol class="breadcrumb breadcrumb-dots mb-0 text-muted small">
              <li class="breadcrumb-item">
                <router-link to="/">Dashboard</router-link>
              </li>
              <li class="breadcrumb-item">Performance</li>
              <li class="breadcrumb-item active" aria-current="page">Appraisals</li>
            </ol>
          </nav>
        </div>
        <div class="d-flex gap-2">
          <button
            type="button"
            class="btn btn-outline-primary"
            @click="fetchAppraisals"
            :disabled="loading"
          >
            <i class="ti ti-refresh me-2"></i>Refresh
          </button>
          <button
            type="button"
            class="btn btn-primary"
            @click="openForm()"
          >
            <i class="ti ti-plus me-2"></i>New Appraisal
          </button>
        </div>
      </div>
    </div>

    <div class="card shadow-sm border-0">
      <div class="card-body">
        <div class="row g-3 mb-3">
          <div class="col-md-3">
            <select v-model="filters.status" class="form-select" @change="fetchAppraisals">
              <option value="">All Status</option>
              <option value="draft">Draft</option>
              <option value="recommended">Recommended</option>
              <option value="approved">Approved</option>
              <option value="rejected">Rejected</option>
            </select>
          </div>
        </div>

        <div v-if="loading" class="text-center py-5">
          <span class="spinner-border text-primary"></span>
        </div>

        <div v-else-if="appraisals.length === 0" class="text-center py-5 text-muted">
          No appraisals recorded yet.
        </div>

        <div v-else class="table-responsive">
          <table class="table table-hover align-middle">
            <thead>
              <tr>
                <th>Employee</th>
                <th>Effective Date</th>
                <th>Salary Change</th>
                <th>Promotion</th>
                <th>Status</th>
                <th class="text-end">Actions</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="item in appraisals" :key="item.id">
                <td>{{ item.user?.name || '-' }}</td>
                <td>{{ formatDate(item.effective_date) || '-' }}</td>
                <td>
                  <div v-if="item.new_salary != null">
                    {{ formatCurrency(item.current_salary) }} → {{ formatCurrency(item.new_salary) }}
                  </div>
                  <div class="text-muted small" v-if="item.salary_change_percent != null">
                    {{ item.salary_change_percent }}%
                  </div>
                </td>
                <td>
                  <span v-if="item.promotion_to_designation_id">
                    {{ item.promotion_designation?.name || 'New designation' }}
                  </span>
                  <span v-else>-</span>
                </td>
                <td>
                  <span class="badge bg-secondary-subtle text-secondary" v-if="item.status === 'draft'">Draft</span>
                  <span class="badge bg-info-subtle text-info" v-else-if="item.status === 'recommended'">Recommended</span>
                  <span class="badge bg-success-subtle text-success" v-else-if="item.status === 'approved'">Approved</span>
                  <span class="badge bg-danger-subtle text-danger" v-else>Rejected</span>
                </td>
                <td class="text-end">
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
                          @click.prevent="() => { closeRowMenu(); confirmDelete(item); }"
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
              <h5 class="modal-title">{{ editingAppraisal ? 'Edit Appraisal' : 'New Appraisal' }}</h5>
              <button type="button" class="btn-close" @click="closeForm"></button>
            </div>
            <div class="modal-body">
              <div class="row g-3">
                <div class="col-md-6">
                  <label class="form-label">Employee</label>
                  <select v-model.number="form.user_id" class="form-select" :disabled="editingAppraisal" @change="handleEmployeeChange" required>
                    <option :value="null" disabled>Select employee</option>
                    <option v-for="emp in staffOptions" :key="emp.id" :value="emp.id">
                      {{ emp.name }} ({{ emp.email }})
                    </option>
                  </select>
                </div>
                <div class="col-md-6">
                  <label class="form-label">Related Review</label>
                  <select v-model.number="form.review_id" class="form-select" :disabled="editingAppraisal">
                    <option :value="null">No specific review</option>
                    <option v-for="review in reviewOptions" :key="review.id" :value="review.id">
                      {{ review.user?.name }} ({{ review.period?.start_date }} - {{ review.period?.end_date }})
                    </option>
                  </select>
                </div>
                <div class="col-md-4">
                  <label class="form-label">Effective Date</label>
                  <input v-model="form.effective_date" type="date" class="form-control" required />
                </div>
                <div class="col-md-4">
                  <label class="form-label">Current Salary</label>
                  <div class="input-group">
                    <span class="input-group-text">$</span>
                    <input v-model.number="form.current_salary" type="number" class="form-control" readonly disabled />
                  </div>
                </div>
                <div class="col-md-4">
                  <label class="form-label">New Salary</label>
                  <div class="input-group">
                    <span class="input-group-text">$</span>
                    <input v-model.number="form.new_salary" type="number" step="0.01" class="form-control" @input="handleNewSalaryInput" />
                  </div>
                </div>
                <div class="col-md-4">
                  <label class="form-label">Change Amount</label>
                  <div class="input-group">
                    <span class="input-group-text">$</span>
                    <input v-model.number="form.salary_change_amount" type="number" step="0.01" class="form-control" @input="handleChangeAmountInput" />
                  </div>
                </div>
                <div class="col-md-4">
                  <label class="form-label">Change %</label>
                  <div class="input-group">
                    <input v-model.number="form.salary_change_percent" type="number" step="0.1" class="form-control" @input="handleChangePercentInput" />
                    <span class="input-group-text">%</span>
                  </div>
                </div>
                <div class="col-md-4">
                  <label class="form-label">Status</label>
                  <select v-model="form.status" class="form-select">
                    <option value="draft">Draft</option>
                    <option value="recommended">Recommended</option>
                    <option value="approved">Approved</option>
                    <option value="rejected">Rejected</option>
                  </select>
                </div>
                <div class="col-md-4">
                  <label class="form-label">Promotion To</label>
                  <select v-model.number="form.promotion_to_designation_id" class="form-select">
                    <option :value="null">No promotion</option>
                    <option v-for="d in designationOptions" :key="d.id" :value="d.id">
                      {{ d.name }}
                    </option>
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
import { computed, onMounted, reactive, ref } from 'vue';
import api from '../services/api';

const appraisals = ref([]);
const loading = ref(false);
const saving = ref(false);
const formError = ref('');
const showForm = ref(false);
const editingAppraisal = ref(null);

const filters = reactive({
  status: '',
});

const form = reactive({
  id: null,
  user_id: null,
  review_id: null,
  effective_date: new Date().toISOString().split('T')[0],
  current_salary: null,
  new_salary: null,
  salary_change_amount: null,
  salary_change_percent: null,
  promotion_to_designation_id: null,
  status: 'draft',
  notes: '',
});

const openMenuId = ref(null);
const designations = ref([]);
const staff = ref([]);
const reviews = ref([]);

const designationOptions = computed(() => designations.value);
const staffOptions = computed(() => staff.value);
const reviewOptions = computed(() => reviews.value);

const formatDate = (value) => {
  if (!value) return '';
  return new Date(value).toLocaleDateString();
};

const formatCurrency = (value) => {
  if (value == null) return '';
  return new Intl.NumberFormat(undefined, { style: 'currency', currency: 'BDT', minimumFractionDigits: 2 }).format(value);
};

const fetchAppraisals = async () => {
  loading.value = true;
  try {
    const response = await api.get('/performance-appraisals', {
      params: {
        status: filters.status || undefined,
      },
    });
    appraisals.value = response.data.data?.data || response.data.data || [];
  } catch (e) {
    console.error('Failed to load appraisals', e);
  } finally {
    loading.value = false;
  }
};

const fetchMetadata = async () => {
  try {
    const [desRes, staffRes, reviewRes] = await Promise.all([
      api.get('/designations', { params: { per_page: 100 } }),
      api.get('/staff', { params: { per_page: 500 } }),
      api.get('/performance-reviews', { params: { per_page: 100 } }),
    ]);
    designations.value = desRes.data?.data?.data || [];
    staff.value = staffRes.data?.data?.data || [];
    reviews.value = reviewRes.data?.data?.data || [];
  } catch (e) {
    console.error('Failed to load metadata', e);
  }
};

const handleEmployeeChange = () => {
  const selectedEmp = staff.value.find((s) => s.id === form.user_id);
  if (selectedEmp) {
    // Basic Salary Override takes priority
    form.current_salary = selectedEmp.basic_salary_override || 0;

    // Fallback to assigned Salary Structure if no override
    if (form.current_salary === 0) {
      // Check both camelCase and snake_case as JSON keys can vary
      const structure = selectedEmp.salary_structure || selectedEmp.salaryStructure;
      if (structure) {
        form.current_salary = structure.basic_amount || 0;
      }
    }
    handleNewSalaryInput();
  }
};

const handleNewSalaryInput = () => {
  const current = Number(form.current_salary) || 0;
  const next = Number(form.new_salary) || 0;
  form.salary_change_amount = Number((next - current).toFixed(2));
  form.salary_change_percent = current > 0 ? Number(((form.salary_change_amount / current) * 100).toFixed(2)) : 0;
};

const handleChangeAmountInput = () => {
  const current = Number(form.current_salary) || 0;
  const change = Number(form.salary_change_amount) || 0;
  form.new_salary = Number((current + change).toFixed(2));
  form.salary_change_percent = current > 0 ? Number(((change / current) * 100).toFixed(2)) : 0;
};

const handleChangePercentInput = () => {
  const current = Number(form.current_salary) || 0;
  const percent = Number(form.salary_change_percent) || 0;
  form.salary_change_amount = Number(((current * percent) / 100).toFixed(2));
  form.new_salary = Number((current + form.salary_change_amount).toFixed(2));
};

const toggleRowMenu = (id) => {
  openMenuId.value = openMenuId.value === id ? null : id;
};

const closeRowMenu = () => {
  openMenuId.value = null;
};

const openForm = (item = null) => {
  if (item) {
    editingAppraisal.value = item;
    form.id = item.id;
    form.user_id = item.user_id;
    form.review_id = item.review_id;
    form.effective_date = item.effective_date || '';
    form.current_salary = item.current_salary;
    form.new_salary = item.new_salary;
    form.salary_change_amount = item.salary_change_amount;
    form.salary_change_percent = item.salary_change_percent;
    form.promotion_to_designation_id = item.promotion_to_designation_id;
    form.status = item.status || 'draft';
    form.notes = item.notes || '';
  } else {
    editingAppraisal.value = null;
    form.id = null;
    form.user_id = null;
    form.review_id = null;
    form.effective_date = new Date().toISOString().split('T')[0];
    form.current_salary = null;
    form.new_salary = null;
    form.salary_change_amount = null;
    form.salary_change_percent = null;
    form.promotion_to_designation_id = null;
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
      user_id: form.user_id,
      review_id: form.review_id,
      effective_date: form.effective_date || null,
      current_salary: form.current_salary,
      new_salary: form.new_salary,
      salary_change_amount: form.salary_change_amount,
      salary_change_percent: form.salary_change_percent,
      promotion_to_designation_id: form.promotion_to_designation_id,
      status: form.status,
      notes: form.notes || null,
    };

    if (editingAppraisal.value) {
      await api.put(`/performance-appraisals/${editingAppraisal.value.id}`, payload);
    } else {
      await api.post('/performance-appraisals', payload);
    }

    showForm.value = false;
    await fetchAppraisals();
  } catch (e) {
    console.error('Failed to save appraisal', e);
    formError.value = e.response?.data?.message || 'Failed to save appraisal';
  } finally {
    saving.value = false;
  }
};

const confirmDelete = async (item) => {
  if (!window.confirm('Delete this appraisal?')) return;
  try {
    await api.delete(`/performance-appraisals/${item.id}`);
    await fetchAppraisals();
  } catch (e) {
    console.error('Failed to delete appraisal', e);
  }
};

onMounted(async () => {
  await Promise.all([fetchAppraisals(), fetchMetadata()]);
});
</script>
