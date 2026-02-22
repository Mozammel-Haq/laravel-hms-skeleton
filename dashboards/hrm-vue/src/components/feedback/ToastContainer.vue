<template>
  <div class="toast-container position-fixed top-0 end-0 p-3" style="z-index: 1080; margin-top: 18px;">
    <div
      v-for="toast in items"
      :key="toast.id"
      class="toast-modern mb-2"
      :class="toastClass(toast.type)"
      role="alert"
      aria-live="assertive"
      aria-atomic="true"
    >
      <div class="toast-content">
        <div class="toast-icon" :class="iconClass(toast.type)">
          <i :class="iconName(toast.type)"></i>
        </div>
        <div class="toast-message">
          {{ toast.message }}
        </div>
        <button
          type="button"
          class="btn-close ms-2"
          aria-label="Close"
          @click="remove(toast.id)"
        ></button>
      </div>
    </div>
  </div>
</template>

<script setup>
import { computed } from 'vue';
import { useToastStore } from '../../store/toastStore';

const toastStore = useToastStore();

const items = computed(() => toastStore.items);

const remove = (id) => {
  toastStore.remove(id);
};

const toastClass = (type) => {
  if (type === 'success') return 'toast-success';
  if (type === 'danger') return 'toast-danger';
  if (type === 'info') return 'toast-info';
  return 'toast-secondary';
};

const iconName = (type) => {
  if (type === 'success') return 'ti ti-check';
  if (type === 'danger') return 'ti ti-alert-triangle';
  if (type === 'info') return 'ti ti-info-circle';
  return 'ti ti-bell';
};

const iconClass = (type) => {
  if (type === 'success') return 'text-success';
  if (type === 'danger') return 'text-danger';
  if (type === 'info') return 'text-primary';
  return 'text-secondary';
};
</script>

<style scoped>
.toast-modern {
    margin-top: 30px;
  display: block;
  border-radius: 10px;
  box-shadow: 0 4px 12px rgba(16, 24, 40, 0.06);
  min-width: 280px;
  max-width: 420px;
  overflow: hidden;
  animation: toast-slide-in 150ms ease-out;
  border: none;}
.toast-content {
  display: flex;
  align-items: center;
  gap: 12px;
  padding: 12px 16px;
  padding: 12px 16px;
}
.toast-icon {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  width: 20px;
  height: 20px;
  border-radius: 4px;
  background-color: transparent;
  flex: 0 0 auto;
}
.toast-message {
  flex: 1 1 auto;
  font-size: 0.92rem;
}
.toast-success {
  color: var(--bs-success, #1f9c62);
  /* border-left: 3px solid var(--bs-success, #198754); */
  background-color: var(--bs-success-bg-subtle, #e2ece8);
}
.toast-danger {
  color: var(--bs-danger, #dc3545);
  /* border-left: 3px solid var(--bs-danger, #dc3545); */
  background-color: var(--bs-danger-bg-subtle, #f3e6e7);
}
.toast-info {
  color: var(--bs-primary, #0d6efd);
  /* border-left: 3px solid var(--bs-primary, #0d6efd); */
  background-color: var(--bs-primary-bg-subtle, #cfe2ff);
}
.toast-secondary {
  color: var(--bs-secondary, #6c757d);
  /* border-left: 3px solid var(--bs-secondary, #6c757d); */
  background-color: var(--bs-secondary-bg-subtle, #e2e3e5);
}
@keyframes toast-slide-in {
  from { transform: translateY(-6px); opacity: 0; }
  to { transform: translateY(0); opacity: 1; }
}
</style>
