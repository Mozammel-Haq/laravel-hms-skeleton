<template>
  <div class="container-fluid">
    <div class="card p-3 mb-3 border-0 shadow-sm">
      <div class="d-flex justify-content-between align-items-center bg-primary-subtle text-primary px-4 pt-3 pb-3 rounded-3 mb-0">
        <div>
          <h5 class="fw-bold mb-1 text-primary">HR Letters</h5>
          <nav aria-label="breadcrumb">
            <ol class="breadcrumb breadcrumb-dots mb-0 text-muted small">
              <li class="breadcrumb-item">
                <router-link to="/">Dashboard</router-link>
              </li>
              <li class="breadcrumb-item">Compliance</li>
              <li class="breadcrumb-item active" aria-current="page">HR Letters</li>
            </ol>
          </nav>
        </div>
        <div class="d-flex gap-2">
          <button
            type="button"
            class="btn btn-outline-primary"
            @click="fetchLetters"
            :disabled="loading"
          >
            <i class="ti ti-refresh me-2"></i>Refresh
          </button>
          <button
            type="button"
            class="btn btn-primary"
            @click="openForm()"
          >
            <i class="ti ti-plus me-2"></i>New Template
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
              placeholder="Name, code or subject"
              @keyup.enter="fetchLetters"
            />
          </div>
          <div class="col-md-3">
            <label class="form-label">Status</label>
            <select v-model="filters.status" class="form-select" @change="fetchLetters">
              <option value="">All Status</option>
              <option value="draft">Draft</option>
              <option value="active">Active</option>
              <option value="archived">Archived</option>
            </select>
          </div>
          <div class="col-md-3 d-flex align-items-end">
            <div class="d-flex gap-2">
              <button class="btn btn-outline-primary" type="button" @click="fetchLetters">
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

        <div v-else-if="letters.length === 0" class="text-center py-5 text-muted">
          No HR letter templates defined yet.
        </div>

        <div v-else class="table-responsive">
          <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
              <tr>
                <th class="ps-4">Name</th>
                <th>Code</th>
                <th>Category</th>
                <th>Subject</th>
                <th>Status</th>
                <th class="text-end pe-4">Actions</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="item in letters" :key="item.id">
                <td class="ps-4">
                  <div class="fw-semibold">{{ item.name }}</div>
                  <div class="text-muted small" v-if="item.body">
                    {{ truncate(item.body) }}
                  </div>
                </td>
                <td>{{ item.code || '-' }}</td>
                <td>{{ item.category || '-' }}</td>
                <td>{{ item.subject || '-' }}</td>
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
                          @click.prevent="() => { closeRowMenu(); archiveLetter(item); }"
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
              <h5 class="modal-title">{{ editingLetter ? 'Edit Template' : 'New Template' }}</h5>
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
                <div class="col-md-12">
                  <label class="form-label">Subject</label>
                  <input v-model="form.subject" type="text" class="form-control" />
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
                  <label class="form-label">Body</label>
                  <textarea v-model="form.body" rows="6" class="form-control"></textarea>
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

const letters = ref([]);
const loading = ref(false);
const saving = ref(false);
const savingId = ref(null);
const showForm = ref(false);
const editingLetter = ref(null);
const formError = ref('');

const filters = reactive({
  search: '',
  status: '',
});

const form = reactive({
  name: '',
  code: '',
  category: '',
  subject: '',
  body: '',
  status: 'draft',
});

const openMenuId = ref(null);

const truncate = (text, length = 80) => {
  if (!text) return '';
  return text.length > length ? text.slice(0, length) + '…' : text;
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

const fetchLetters = async () => {
  loading.value = true;
  try {
    const response = await api.get('/compliance-letters', {
      params: {
        search: filters.search || undefined,
        status: filters.status || undefined,
      },
    });
    letters.value = response.data.data || [];
  } catch (e) {
    console.error('Failed to load letters', e);
  } finally {
    loading.value = false;
  }
};

const resetFilters = () => {
  filters.search = '';
  filters.status = '';
  fetchLetters();
};

const resetForm = () => {
  form.name = '';
  form.code = '';
  form.category = '';
  form.subject = '';
  form.body = '';
  form.status = 'draft';
  formError.value = '';
};

const openForm = (letter = null) => {
  if (letter) {
    editingLetter.value = letter;
    form.name = letter.name || '';
    form.code = letter.code || '';
    form.category = letter.category || '';
    form.subject = letter.subject || '';
    form.body = letter.body || '';
    form.status = letter.status || 'draft';
  } else {
    editingLetter.value = null;
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
      name: form.name,
      code: form.code || null,
      category: form.category || null,
      subject: form.subject || null,
      body: form.body || null,
      status: form.status,
    };
    if (editingLetter.value) {
      savingId.value = editingLetter.value.id;
      await api.put(`/compliance-letters/${editingLetter.value.id}`, payload);
    } else {
      await api.post('/compliance-letters', payload);
    }
    showForm.value = false;
    await fetchLetters();
  } catch (e) {
    console.error('Failed to save letter', e);
    formError.value = e.response?.data?.message || 'Failed to save letter';
  } finally {
    saving.value = false;
    savingId.value = null;
  }
};

const archiveLetter = async (letter) => {
  if (!window.confirm(`Archive template "${letter.name}"?`)) return;
  savingId.value = letter.id;
  try {
    await api.delete(`/compliance-letters/${letter.id}`);
    await fetchLetters();
  } catch (e) {
    console.error('Failed to archive letter', e);
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

onMounted(fetchLetters);
</script>

