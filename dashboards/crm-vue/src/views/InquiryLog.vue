<template>
  <div class="inquiry-page">
    <div class="d-flex justify-content-between align-items-center mb-4">
      <h4 class="mb-0">Inquiry Log</h4>
      <div class="d-flex gap-2">
        <button class="btn btn-outline-secondary" @click="fetchInquiries" :disabled="loading">
          <i class="ti ti-refresh me-2"></i> Refresh
        </button>
        <button class="btn btn-primary" @click="showModal = true">
          <i class="ti ti-plus me-2"></i> New Inquiry
        </button>
      </div>
    </div>

    <!-- Stats Row -->
    <div class="row g-3 mb-4">
      <div class="col-md-4">
        <div class="card border-0 shadow-sm p-3">
          <div class="text-muted small mb-1">Total Inquiries</div>
          <div class="h4 mb-0">{{ inquiries.length }}</div>
        </div>
      </div>
      <div class="col-md-4">
        <div class="card border-0 shadow-sm p-3">
          <div class="text-muted small mb-1">Pending</div>
          <div class="h4 mb-0 text-warning">{{ inquiries.filter(i => i.status === 'pending').length }}</div>
        </div>
      </div>
      <div class="col-md-4">
        <div class="card border-0 shadow-sm p-3">
          <div class="text-muted small mb-1">Closed Today</div>
          <div class="h4 mb-0 text-success">0</div>
        </div>
      </div>
    </div>

    <div v-if="loading" class="text-center py-5">
      <div class="spinner-border text-primary" role="status">
        <span class="visually-hidden">Loading...</span>
      </div>
    </div>

    <div v-else class="card border-0 shadow-sm">
      <div class="card-body p-0">
        <div class="table-responsive">
          <table class="table table-hover align-middle mb-0">
            <thead class="bg-light">
              <tr>
                <th class="ps-4">Source</th>
                <th>Patient Name</th>
                <th>Subject</th>
                <th>Status</th>
                <th>Date</th>
                <th class="text-end pe-4">Actions</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="inquiry in inquiries" :key="inquiry.id">
                <td class="ps-4">
                  <span class="badge rounded-pill" :class="getSourceClass(inquiry.source)">
                    {{ inquiry.source }}
                  </span>
                </td>
                <td>
                  <div class="fw-semibold">{{ inquiry.patient?.name || 'Walk-in' }}</div>
                  <div class="text-muted fs-12">{{ inquiry.patient?.phone || 'N/A' }}</div>
                </td>
                <td>{{ inquiry.subject }}</td>
                <td>
                  <span class="badge" :class="getStatusClass(inquiry.status)">
                    {{ inquiry.status }}
                  </span>
                </td>
                <td>{{ formatDate(inquiry.created_at) }}</td>
                <td class="text-end pe-4">
                  <button class="btn btn-sm btn-light me-2" title="View"><i class="ti ti-eye"></i></button>
                  <button v-if="inquiry.status === 'pending'" class="btn btn-sm btn-light text-primary" @click="closeInquiry(inquiry)" title="Mark Responded">
                    <i class="ti ti-check"></i>
                  </button>
                </td>
              </tr>
              <tr v-if="inquiries.length === 0">
                <td colspan="6" class="text-center py-4 text-muted">No inquiries found</td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>

    <!-- New Inquiry Modal -->
    <div v-if="showModal" class="modal d-block" style="background: rgba(0,0,0,0.5)">
      <div class="modal-dialog">
        <div class="modal-content border-0 shadow">
          <div class="modal-header">
            <h5 class="modal-title">New Inquiry</h5>
            <button type="button" class="btn-close" @click="showModal = false"></button>
          </div>
          <div class="modal-body">
            <form @submit.prevent="submitInquiry">
              <div class="mb-3">
                <label class="form-label">Subject</label>
                <input v-model="form.subject" type="text" class="form-control" required placeholder="e.g. Appointment Request">
              </div>
              <div class="mb-3">
                <label class="form-label">Source</label>
                <select v-model="form.source" class="form-select" required>
                  <option value="walk-in">Walk-in</option>
                  <option value="phone">Phone</option>
                  <option value="whatsapp">WhatsApp</option>
                  <option value="facebook">Facebook</option>
                  <option value="website">Website</option>
                </select>
              </div>
              <div class="mb-3">
                <label class="form-label">Priority</label>
                <select v-model="form.priority" class="form-select" required>
                  <option value="low">Low</option>
                  <option value="medium">Medium</option>
                  <option value="high">High</option>
                </select>
              </div>
              <div class="mb-3">
                <label class="form-label">Message/Notes</label>
                <textarea v-model="form.message" class="form-control" rows="3" required></textarea>
              </div>
            </form>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-light" @click="showModal = false">Cancel</button>
            <button type="button" class="btn btn-primary" @click="submitInquiry" :disabled="submitting">
              {{ submitting ? 'Saving...' : 'Save Inquiry' }}
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import api from '../services/api';

const inquiries = ref([]);
const loading = ref(false);
const showModal = ref(false);
const submitting = ref(false);

const form = ref({
  subject: '',
  source: 'walk-in',
  priority: 'medium',
  message: '',
  patient_id: null
});

const fetchInquiries = async () => {
  loading.value = true;
  try {
    const response = await api.get('/inquiries');
    inquiries.value = response.data.data.data || response.data.data;
  } catch (error) {
    console.error('Failed to fetch inquiries:', error);
  } finally {
    loading.value = false;
  }
};

const submitInquiry = async () => {
  submitting.value = true;
  try {
    await api.post('/inquiries', form.value);
    showModal.value = false;
    form.value = { subject: '', source: 'walk-in', priority: 'medium', message: '', patient_id: null };
    fetchInquiries();
  } catch (error) {
    console.error('Failed to submit inquiry:', error);
    alert('Failed to save inquiry.');
  } finally {
    submitting.value = false;
  }
};

const closeInquiry = async (inquiry) => {
  try {
    await api.patch(`/inquiries/${inquiry.id}`, { status: 'responded' });
    fetchInquiries();
  } catch (error) {
    console.error('Failed to update status:', error);
  }
};

const formatDate = (dateString) => {
  if (!dateString) return 'N/A';
  return new Date(dateString).toLocaleString();
};

const getSourceClass = (source) => {
  const map = {
    'website': 'bg-info-subtle text-info',
    'whatsapp': 'bg-success-subtle text-success',
    'phone': 'bg-secondary-subtle text-secondary',
    'facebook': 'bg-primary-subtle text-primary',
    'walk-in': 'bg-dark-subtle text-dark'
  };
  return map[source?.toLowerCase()] || 'bg-light';
};

const getStatusClass = (status) => {
  const map = {
    'pending': 'bg-warning-subtle text-warning',
    'responded': 'bg-primary-subtle text-primary',
    'closed': 'bg-success-subtle text-success'
  };
  return map[status?.toLowerCase()] || 'bg-light';
};

onMounted(() => {
  fetchInquiries();
});
</script>

<style scoped>
.modal { display: block; }
.fs-12 { font-size: 12px; }
.bg-info-subtle { background-color: #e0f7fa; }
.bg-success-subtle { background-color: #e6f4ec; }
.bg-secondary-subtle { background-color: #f8f9fa; }
.bg-primary-subtle { background-color: #e7f1ff; }
.bg-warning-subtle { background-color: #fff4e6; }
</style>
