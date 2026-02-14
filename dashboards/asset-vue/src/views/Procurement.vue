<template>
  <div class="procurement-page">
    <div class="d-flex justify-content-between align-items-center mb-4">
      <h4 class="mb-0">Procurement Orders</h4>
      <div class="d-flex gap-2">
        <button class="btn btn-outline-secondary" @click="fetchOrders" :disabled="loading">
          <i class="ti ti-refresh me-2"></i> Refresh
        </button>
        <button class="btn btn-primary" @click="showNewOrderModal = true">
          <i class="ti ti-plus me-2"></i> New Purchase Order
        </button>
      </div>
    </div>

    <!-- Stats Row -->
    <div class="row g-3 mb-4">
      <div class="col-md-3">
        <div class="card border-0 shadow-sm p-3">
          <div class="text-muted small mb-1">Total Orders</div>
          <div class="h4 mb-0">{{ orders.length }}</div>
        </div>
      </div>
      <div class="col-md-3">
        <div class="card border-0 shadow-sm p-3">
          <div class="text-muted small mb-1">Pending</div>
          <div class="h4 mb-0">{{ orders.filter(o => o.status === 'pending').length }}</div>
        </div>
      </div>
      <div class="col-md-3">
        <div class="card border-0 shadow-sm p-3">
          <div class="text-muted small mb-1">Received</div>
          <div class="h4 mb-0 text-success">{{ orders.filter(o => o.status === 'received').length }}</div>
        </div>
      </div>
      <div class="col-md-3">
        <div class="card border-0 shadow-sm p-3">
          <div class="text-muted small mb-1">Total Value</div>
          <div class="h4 mb-0">${{ totalValue.toFixed(2) }}</div>
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
                <th class="ps-4">Order #</th>
                <th>Supplier</th>
                <th>Date</th>
                <th>Total Amount</th>
                <th>Status</th>
                <th class="text-end pe-4">Actions</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="order in orders" :key="order.id">
                <td class="ps-4">
                  <span class="fw-bold text-primary">{{ order.order_number }}</span>
                </td>
                <td>{{ order.supplier_name }}</td>
                <td>{{ formatDate(order.order_date) }}</td>
                <td>${{ parseFloat(order.total_amount).toFixed(2) }}</td>
                <td>
                  <span class="badge" :class="getStatusClass(order.status)">
                    {{ order.status }}
                  </span>
                </td>
                <td class="text-end pe-4">
                  <button v-if="order.status === 'pending'" class="btn btn-sm btn-outline-success me-2" @click="openReceiveModal(order)">
                    <i class="ti ti-package-import me-1"></i> Receive
                  </button>
                  <button class="btn btn-sm btn-light" title="View Details"><i class="ti ti-eye"></i></button>
                </td>
              </tr>
              <tr v-if="orders.length === 0">
                <td colspan="6" class="text-center py-4 text-muted">No procurement orders found</td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>

    <!-- New Order Modal -->
    <div v-if="showNewOrderModal" class="modal d-block" style="background: rgba(0,0,0,0.5)">
      <div class="modal-dialog modal-lg">
        <div class="modal-content border-0 shadow">
          <div class="modal-header">
            <h5 class="modal-title">Create Purchase Order</h5>
            <button type="button" class="btn-close" @click="showNewOrderModal = false"></button>
          </div>
          <div class="modal-body">
            <form @submit.prevent="submitOrder">
              <div class="row mb-3">
                <div class="col-md-6">
                  <label class="form-label">Supplier Name</label>
                  <input v-model="newOrder.supplier_name" type="text" class="form-control" required placeholder="e.g. Acme Pharma">
                </div>
                <div class="col-md-6">
                  <label class="form-label">Order Date</label>
                  <input v-model="newOrder.order_date" type="date" class="form-control" required>
                </div>
              </div>

              <h6>Order Items</h6>
              <div class="table-responsive mb-3">
                <table class="table table-sm">
                  <thead>
                    <tr>
                      <th>Item/Medicine</th>
                      <th style="width: 120px">Qty</th>
                      <th style="width: 150px">Unit Price</th>
                      <th style="width: 50px"></th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr v-for="(item, index) in newOrder.items" :key="index">
                      <td>
                        <input v-model="item.item_name" type="text" class="form-control form-control-sm" placeholder="Item name">
                      </td>
                      <td>
                        <input v-model.number="item.quantity" type="number" class="form-control form-control-sm" min="1">
                      </td>
                      <td>
                        <div class="input-group input-group-sm">
                          <span class="input-group-text">$</span>
                          <input v-model.number="item.unit_price" type="number" step="0.01" class="form-control form-control-sm">
                        </div>
                      </td>
                      <td>
                        <button type="button" class="btn btn-sm text-danger" @click="removeItem(index)">
                          <i class="ti ti-trash"></i>
                        </button>
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>
              <button type="button" class="btn btn-sm btn-outline-primary mb-3" @click="addItem">
                <i class="ti ti-plus me-1"></i> Add Item
              </button>

              <div class="text-end h5">
                Total: ${{ orderTotal.toFixed(2) }}
              </div>
            </form>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-light" @click="showNewOrderModal = false">Cancel</button>
            <button type="button" class="btn btn-primary" @click="submitOrder" :disabled="submitting">
              {{ submitting ? 'Creating...' : 'Create Order' }}
            </button>
          </div>
        </div>
      </div>
    </div>

    <!-- Receive Modal -->
    <div v-if="showReceiveModal" class="modal d-block" style="background: rgba(0,0,0,0.5)">
      <div class="modal-dialog modal-lg">
        <div class="modal-content border-0 shadow">
          <div class="modal-header">
            <h5 class="modal-title">Receive Order: {{ selectedOrder?.order_number }}</h5>
            <button type="button" class="btn-close" @click="showReceiveModal = false"></button>
          </div>
          <div class="modal-body">
            <div class="table-responsive">
              <table class="table table-sm align-middle">
                <thead>
                  <tr>
                    <th>Item</th>
                    <th style="width: 100px">Ordered</th>
                    <th style="width: 100px">Received</th>
                    <th>Batch #</th>
                    <th>Expiry</th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="item in receiveItems" :key="item.id">
                    <td>{{ item.item_name }}</td>
                    <td>{{ item.quantity }}</td>
                    <td>
                      <input v-model.number="item.received_quantity" type="number" class="form-control form-control-sm" :max="item.quantity">
                    </td>
                    <td>
                      <input v-model="item.batch_number" type="text" class="form-control form-control-sm" placeholder="Batch #">
                    </td>
                    <td>
                      <input v-model="item.expiry_date" type="date" class="form-control form-control-sm">
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-light" @click="showReceiveModal = false">Cancel</button>
            <button type="button" class="btn btn-success" @click="submitReceive" :disabled="submitting">
              {{ submitting ? 'Processing...' : 'Complete GRN' }}
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue';
import api from '../services/api';

const orders = ref([]);
const loading = ref(false);
const submitting = ref(false);
const showNewOrderModal = ref(false);
const showReceiveModal = ref(false);
const selectedOrder = ref(null);

const newOrder = ref({
  supplier_name: '',
  order_date: new Date().toISOString().split('T')[0],
  items: [
    { item_name: '', quantity: 1, unit_price: 0 }
  ]
});

const receiveItems = ref([]);

const totalValue = computed(() => {
  return orders.value.reduce((acc, order) => acc + parseFloat(order.total_amount), 0);
});

const orderTotal = computed(() => {
  return newOrder.value.items.reduce((acc, item) => acc + (item.quantity * item.unit_price), 0);
});

const fetchOrders = async () => {
  loading.value = true;
  try {
    const response = await api.get('/procurements');
    orders.value = response.data.data.data || response.data.data;
  } catch (error) {
    console.error('Failed to fetch orders:', error);
  } finally {
    loading.value = false;
  }
};

const addItem = () => {
  newOrder.value.items.push({ item_name: '', quantity: 1, unit_price: 0 });
};

const removeItem = (index) => {
  newOrder.value.items.splice(index, 1);
};

const submitOrder = async () => {
  submitting.value = true;
  try {
    await api.post('/procurements', newOrder.value);
    showNewOrderModal.value = false;
    newOrder.value = {
      supplier_name: '',
      order_date: new Date().toISOString().split('T')[0],
      items: [{ item_name: '', quantity: 1, unit_price: 0 }]
    };
    fetchOrders();
  } catch (error) {
    console.error('Failed to create order:', error);
    alert('Failed to create order. Please check inputs.');
  } finally {
    submitting.value = false;
  }
};

const openReceiveModal = (order) => {
  selectedOrder.value = order;
  receiveItems.value = order.items.map(item => ({
    id: item.id,
    item_name: item.item_name || (item.medicine ? item.medicine.name : 'Unknown'),
    quantity: item.quantity,
    received_quantity: item.quantity,
    batch_number: '',
    expiry_date: ''
  }));
  showReceiveModal.value = true;
};

const submitReceive = async () => {
  submitting.value = true;
  try {
    await api.post(`/procurements/${selectedOrder.value.id}/receive`, {
      items: receiveItems.value
    });
    showReceiveModal.value = false;
    fetchOrders();
  } catch (error) {
    console.error('Failed to receive order:', error);
    alert('Failed to process GRN.');
  } finally {
    submitting.value = false;
  }
};

const formatDate = (dateString) => {
  if (!dateString) return 'N/A';
  return new Date(dateString).toLocaleDateString();
};

const getStatusClass = (status) => {
  const map = {
    'pending': 'bg-warning-subtle text-warning',
    'ordered': 'bg-info-subtle text-info',
    'received': 'bg-success-subtle text-success',
    'cancelled': 'bg-danger-subtle text-danger'
  };
  return map[status?.toLowerCase()] || 'bg-light';
};

onMounted(() => {
  fetchOrders();
});
</script>
