<template>
  <div class="container-fluid">
    <div class="card p-3 mb-3 border-0 shadow-sm">
      <div class="d-flex justify-content-between align-items-center bg-primary-subtle text-primary px-4 pt-3 pb-3 rounded-3 mb-0">
        <div>
          <h5 class="fw-bold mb-1 text-primary">Compliance Documents</h5>
          <nav aria-label="breadcrumb">
            <ol class="breadcrumb breadcrumb-dots mb-0 text-muted small">
              <li class="breadcrumb-item">
                <router-link to="/">Dashboard</router-link>
              </li>
              <li class="breadcrumb-item">Compliance</li>
              <li class="breadcrumb-item active" aria-current="page">Documents</li>
            </ol>
          </nav>
        </div>
        <div class="d-flex gap-2">
          <button
            type="button"
            class="btn btn-outline-primary"
            @click="fetchDocuments"
            :disabled="loading"
          >
            <i class="ti ti-refresh me-2"></i>Refresh
          </button>
          <button
            type="button"
            class="btn btn-primary"
            @click="openForm()"
          >
            <i class="ti ti-plus me-2"></i>New Document
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
              placeholder="Title, type or category"
              @keyup.enter="fetchDocuments"
            />
          </div>
          <div class="col-md-3">
            <label class="form-label">Status</label>
            <select v-model="filters.status" class="form-select" @change="fetchDocuments">
              <option value="">All Status</option>
              <option value="draft">Draft</option>
              <option value="active">Active</option>
              <option value="archived">Archived</option>
            </select>
          </div>
          <div class="col-md-3 d-flex align-items-end">
            <div class="d-flex gap-2">
              <button class="btn btn-outline-primary" type="button" @click="fetchDocuments">
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

        <div v-else-if="documents.length === 0" class="text-center py-5 text-muted">
          No documents uploaded yet.
        </div>

        <div v-else class="table-responsive">
          <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
              <tr>
                <th class="ps-4">Title</th>
                <th>Type</th>
                <th>Category</th>
                <th>Published At</th>
                <th>Status</th>
                <th class="text-end pe-4">Actions</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="item in documents" :key="item.id">
                <td class="ps-4">
                  <div class="fw-semibold">{{ item.title }}</div>
                  <div class="text-muted small" v-if="item.description">
                    {{ truncate(item.description) }}
                  </div>
                  <div class="small">
                    <a
                      v-if="item.storage_path"
                      :href="item.storage_path"
                      target="_blank"
                      rel="noopener noreferrer"
                    >
                      <i class="ti ti-link me-1"></i>Open
                    </a>
                    <span v-else class="text-muted">No file link</span>
                  </div>
                </td>
                <td>{{ item.document_type || '-' }}</td>
                <td>{{ item.category || '-' }}</td>
                <td>{{ item.published_at ? formatDate(item.published_at) : '-' }}</td>
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
                          @click.prevent="() => { closeRowMenu(); archiveDocument(item); }"
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
              <h5 class="modal-title">{{ editingDocument ? 'Edit Document' : 'New Document' }}</h5>
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
                  <label class="form-label">Document Type</label>
                  <input v-model="form.document_type" type="text" class="form-control" />
                </div>
                <div class="col-md-4">
                  <label class="form-label">File Link / Path</label>
                  <input v-model="form.storage_path" type="text" class="form-control" />
                </div>
                <div class="col-md-4">
                  <label class="form-label">Published At</label>
                  <input v-model="form.published_at" type="date" class="form-control" />
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

const documents = ref([]);
const loading = ref(false);
const saving = ref(false);
const savingId = ref(null);
const showForm = ref(false);
const editingDocument = ref(null);
const formError = ref('');

const filters = reactive({
  search: '',
  status: '',
});

const form = reactive({
  title: '',
  category: '',
  document_type: '',
  storage_path: '',
  description: '',
  status: 'draft',
  published_at: '',
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

const fetchDocuments = async () => {
  loading.value = true;
  try {
    const response = await api.get('/compliance-documents', {
      params: {
        search: filters.search || undefined,
        status: filters.status || undefined,
      },
    });
    documents.value = response.data.data || [];
  } catch (e) {
    console.error('Failed to load documents', e);
    toast.error('Failed to load documents');
  } finally {
    loading.value = false;
  }
};

const resetFilters = () => {
  filters.search = '';
  filters.status = '';
  fetchDocuments();
};

const resetForm = () => {
  form.title = '';
  form.category = '';
  form.document_type = '';
  form.storage_path = '';
  form.description = '';
  form.status = 'draft';
  form.published_at = '';
  formError.value = '';
};

const openForm = (documentItem = null) => {
  if (documentItem) {
    editingDocument.value = documentItem;
    form.title = documentItem.title || '';
    form.category = documentItem.category || '';
    form.document_type = documentItem.document_type || '';
    form.storage_path = documentItem.storage_path || '';
    form.description = documentItem.description || '';
    form.status = documentItem.status || 'draft';
    form.published_at = documentItem.published_at || '';
  } else {
    editingDocument.value = null;
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
      document_type: form.document_type || null,
      storage_path: form.storage_path || null,
      description: form.description || null,
      status: form.status,
      published_at: form.published_at || null,
    };
    if (editingDocument.value) {
      savingId.value = editingDocument.value.id;
      await api.put(`/compliance-documents/${editingDocument.value.id}`, payload);
    } else {
      await api.post('/compliance-documents', payload);
    }
    showForm.value = false;
    await fetchDocuments();
  } catch (e) {
    console.error('Failed to save document', e);
    formError.value = e.response?.data?.message || 'Failed to save document';
    toast.error(formError.value);
  } finally {
    saving.value = false;
    savingId.value = null;
  }
};

const archiveDocument = async (documentItem) => {
  if (!window.confirm(`Archive document "${documentItem.title}"?`)) return;
  savingId.value = documentItem.id;
  try {
    await api.delete(`/compliance-documents/${documentItem.id}`);
    await fetchDocuments();
  } catch (e) {
    console.error('Failed to archive document', e);
    toast.error('Failed to archive document');
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

onMounted(fetchDocuments);
</script>
