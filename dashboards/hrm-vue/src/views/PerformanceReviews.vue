<template>
  <div class="container-fluid">
    <div class="card p-3 mb-3 border-0 shadow-sm">
      <div class="d-flex justify-content-between align-items-center bg-primary-subtle text-primary px-4 pt-3 pb-3 rounded-3 mb-0">
        <div>
          <h5 class="fw-bold mb-1 text-primary">Performance Reviews</h5>
          <nav aria-label="breadcrumb">
            <ol class="breadcrumb breadcrumb-dots mb-0 text-muted small">
              <li class="breadcrumb-item">
                <router-link to="/">Dashboard</router-link>
              </li>
              <li class="breadcrumb-item">Performance</li>
              <li class="breadcrumb-item active" aria-current="page">Reviews</li>
            </ol>
          </nav>
        </div>
        <div class="d-flex gap-2">
          <button
            type="button"
            class="btn btn-outline-primary"
            @click="fetchReviews"
            :disabled="loading"
          >
            <i class="ti ti-refresh me-2"></i>Refresh
          </button>
          <button
            type="button"
            class="btn btn-primary"
            @click="openForm()"
          >
            <i class="ti ti-plus me-2"></i>New Review
          </button>
        </div>
      </div>
    </div>

    <div class="card shadow-sm border-0">
      <div class="card-body">
        <div class="row g-3 mb-3">
          <div class="col-md-3">
            <select v-model="filters.status" class="form-select" @change="fetchReviews">
              <option value="">All Status</option>
              <option value="draft">Draft</option>
              <option value="submitted">Submitted</option>
              <option value="finalized">Finalized</option>
            </select>
          </div>
        </div>

        <div v-if="loading" class="text-center py-5">
          <span class="spinner-border text-primary"></span>
        </div>

        <div v-else-if="reviews.length === 0" class="text-center py-5 text-muted">
          No reviews recorded yet.
        </div>

        <div v-else class="table-responsive">
          <table class="table table-hover align-middle">
            <thead>
              <tr>
                <th>Employee</th>
                <th>Reviewer</th>
                <th>Period</th>
                <th>Rating</th>
                <th>Status</th>
                <th class="text-end">Actions</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="review in reviews" :key="review.id">
                <td>{{ review.user?.name || '-' }}</td>
                <td>{{ review.reviewer?.name || '-' }}</td>
                <td>
                  <span v-if="review.period_start || review.period_end">
                    {{ formatDate(review.period_start) }} - {{ formatDate(review.period_end) }}
                  </span>
                  <span v-else>-</span>
                </td>
                <td>
                  <span v-if="review.overall_rating">
                    {{ review.overall_rating }} / 5
                  </span>
                  <span v-else>-</span>
                </td>
                <td>
                  <span class="badge bg-secondary-subtle text-secondary" v-if="review.status === 'draft'">Draft</span>
                  <span class="badge bg-info-subtle text-info" v-else-if="review.status === 'submitted'">Submitted</span>
                  <span class="badge bg-success-subtle text-success" v-else>Finalized</span>
                </td>
                <td class="text-end">
                  <div class="dropdown">
                    <button
                      type="button"
                      class="btn btn-sm btn-light btn-icon"
                      @click="toggleRowMenu(review.id)"
                    >
                      <i class="ti ti-dots-vertical"></i>
                    </button>
                    <ul
                      class="dropdown-menu dropdown-menu-end shadow-sm border-0"
                      :class="{ show: openMenuId === review.id }"
                    >
                      <li>
                        <a
                          href="#"
                          class="dropdown-item"
                          @click.prevent="() => { closeRowMenu(); openForm(review); }"
                        >
                          <i class="ti ti-edit me-2"></i>Edit
                        </a>
                      </li>
                      <li>
                        <a
                          href="#"
                          class="dropdown-item text-danger"
                          @click.prevent="() => { closeRowMenu(); confirmDelete(review); }"
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
              <h5 class="modal-title">{{ editingReview ? 'Edit Review' : 'New Review' }}</h5>
              <button type="button" class="btn-close" @click="closeForm"></button>
            </div>
            <div class="modal-body">
              <div class="row g-3">
                <div class="col-md-4">
                  <label class="form-label">Employee</label>
                  <input v-model="form.user_name" type="text" class="form-control" disabled />
                </div>
                <div class="col-md-4">
                  <label class="form-label">Reviewer</label>
                  <input v-model="form.reviewer_name" type="text" class="form-control" disabled />
                </div>
                <div class="col-md-2">
                  <label class="form-label">Rating</label>
                  <input v-model.number="form.overall_rating" type="number" min="1" max="5" class="form-control" />
                </div>
                <div class="col-md-2">
                  <label class="form-label">Status</label>
                  <select v-model="form.status" class="form-select">
                    <option value="draft">Draft</option>
                    <option value="submitted">Submitted</option>
                    <option value="finalized">Finalized</option>
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
                <div class="col-12">
                  <label class="form-label">Summary</label>
                  <textarea v-model="form.summary" rows="4" class="form-control"></textarea>
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

const reviews = ref([]);
const loading = ref(false);
const saving = ref(false);
const formError = ref('');
const showForm = ref(false);
const editingReview = ref(null);

const filters = reactive({
  status: '',
});

const form = reactive({
  id: null,
  user_id: null,
  user_name: '',
  reviewer_user_id: null,
  reviewer_name: '',
  period_start: '',
  period_end: '',
  overall_rating: null,
  summary: '',
  status: 'draft',
});

const openMenuId = ref(null);

const formatDate = (value) => {
  if (!value) return '';
  return new Date(value).toLocaleDateString();
};

const fetchReviews = async () => {
  loading.value = true;
  try {
    const response = await api.get('/performance-reviews', {
      params: {
        status: filters.status || undefined,
      },
    });
    reviews.value = response.data.data?.data || response.data.data || [];
  } catch (e) {
    console.error('Failed to load reviews', e);
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

const openForm = (review = null) => {
  if (review) {
    editingReview.value = review;
    form.id = review.id;
    form.user_id = review.user_id;
    form.user_name = review.user?.name || '';
    form.reviewer_user_id = review.reviewer_user_id;
    form.reviewer_name = review.reviewer?.name || '';
    form.period_start = review.period_start || '';
    form.period_end = review.period_end || '';
    form.overall_rating = review.overall_rating;
    form.summary = review.summary || '';
    form.status = review.status || 'draft';
  } else {
    editingReview.value = null;
    form.id = null;
    form.user_id = null;
    form.user_name = 'Me';
    form.reviewer_user_id = null;
    form.reviewer_name = 'Me';
    form.period_start = '';
    form.period_end = '';
    form.overall_rating = null;
    form.summary = '';
    form.status = 'draft';
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
      reviewer_user_id: form.reviewer_user_id,
      period_start: form.period_start || null,
      period_end: form.period_end || null,
      overall_rating: form.overall_rating,
      summary: form.summary || null,
      status: form.status,
    };

    if (editingReview.value) {
      await api.put(`/performance-reviews/${editingReview.value.id}`, payload);
    } else {
      await api.post('/performance-reviews', payload);
    }

    showForm.value = false;
    await fetchReviews();
  } catch (e) {
    console.error('Failed to save review', e);
    formError.value = e.response?.data?.message || 'Failed to save review';
  } finally {
    saving.value = false;
  }
};

const confirmDelete = async (review) => {
  if (!window.confirm('Delete this performance review?')) return;
  try {
    await api.delete(`/performance-reviews/${review.id}`);
    await fetchReviews();
  } catch (e) {
    console.error('Failed to delete review', e);
  }
};

onMounted(fetchReviews);
</script>
