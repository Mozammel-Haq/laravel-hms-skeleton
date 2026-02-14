<template>
  <div class="designations-page">
    <div class="d-flex justify-content-between align-items-center mb-4">
      <h4 class="mb-0">Designations</h4>
      <div>
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
                  <button class="btn btn-sm btn-light me-2"><i class="ti ti-edit"></i></button>
                  <button class="btn btn-sm btn-light text-danger"><i class="ti ti-trash"></i></button>
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
</template>

<script setup>
import { ref, onMounted } from 'vue';
import api from '../services/api';

const rows = ref([]);
const loading = ref(false);

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
</script>
