<template>
  <div class="toast-container position-fixed top-0 end-0 p-3" style="z-index: 1080">
    <div
      v-for="toast in items"
      :key="toast.id"
      class="toast show mb-2 border-0 shadow-sm"
      :class="toastClass(toast.type)"
      role="alert"
      aria-live="assertive"
      aria-atomic="true"
    >
      <div class="toast-body d-flex align-items-center">
        <div class="flex-grow-1">
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
  if (type === 'success') return 'bg-success text-white';
  if (type === 'danger') return 'bg-danger text-white';
  if (type === 'info') return 'bg-primary text-white';
  return 'bg-secondary text-white';
};
</script>

