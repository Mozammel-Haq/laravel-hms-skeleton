<template>
  <div class="inventory-page">
    <div class="d-flex justify-content-between align-items-center mb-4">
      <h4 class="mb-0">Inventory Management</h4>
      <div class="d-flex gap-2">
        <button class="btn btn-outline-secondary" @click="fetchInventory" :disabled="loading">
          <i class="ti ti-refresh me-2"></i> Refresh
        </button>
        <button class="btn btn-primary">
          <i class="ti ti-plus me-2"></i> Add Item
        </button>
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
                <th class="ps-4">Item Name</th>
                <th>Category</th>
                <th>Stock Level</th>
                <th>Batches</th>
                <th>Price</th>
                <th class="text-end pe-4">Actions</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="item in items" :key="item.id">
                <td class="ps-4">
                  <div class="fw-semibold">{{ item.name }}</div>
                  <div class="text-muted fs-12">{{ item.generic_name }}</div>
                </td>
                <td>{{ item.dosage_form || 'Medicine' }}</td>
                <td>
                  <div class="d-flex align-items-center">
                    <span class="me-2 fw-bold" :class="getTotalStock(item) < 100 ? 'text-danger' : 'text-success'">
                      {{ getTotalStock(item) }}
                    </span>
                    <div class="progress flex-grow-1" style="height: 4px; max-width: 60px;">
                      <div class="progress-bar" :class="getTotalStock(item) < 100 ? 'bg-danger' : 'bg-success'" :style="{ width: Math.min((getTotalStock(item)/500)*100, 100) + '%' }"></div>
                    </div>
                  </div>
                </td>
                <td>
                  <span class="badge bg-light text-dark border">{{ item.batches?.length || 0 }} batches</span>
                </td>
                <td>${{ item.price }}</td>
                <td class="text-end pe-4">
                  <button class="btn btn-sm btn-light me-2"><i class="ti ti-history"></i></button>
                  <button class="btn btn-sm btn-light"><i class="ti ti-edit"></i></button>
                </td>
              </tr>
              <tr v-if="items.length === 0">
                <td colspan="6" class="text-center py-4 text-muted">No inventory items found</td>
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

const items = ref([]);
const loading = ref(false);

const fetchInventory = async () => {
  loading.value = true;
  try {
    const response = await api.get('/inventory');
    items.value = response.data.data.data || response.data.data;
  } catch (error) {
    console.error('Failed to fetch inventory:', error);
  } finally {
    loading.value = false;
  }
};

const getTotalStock = (item) => {
  if (!item.batches) return 0;
  return item.batches.reduce((sum, batch) => sum + batch.quantity_in_stock, 0);
};

onMounted(() => {
  fetchInventory();
});
</script>
