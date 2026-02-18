import { defineStore } from 'pinia';

export const useToastStore = defineStore('toast', {
  state: () => ({
    items: [],
    nextId: 1,
  }),

  actions: {
    push(message, type = 'info', duration = 3000) {
      const id = this.nextId++;

      this.items.push({
        id,
        message,
        type,
      });

      if (duration > 0) {
        setTimeout(() => {
          this.remove(id);
        }, duration);
      }
    },

    remove(id) {
      this.items = this.items.filter((toast) => toast.id !== id);
    },

    success(message, duration = 3000) {
      this.push(message, 'success', duration);
    },

    error(message, duration = 5000) {
      this.push(message, 'danger', duration);
    },

    info(message, duration = 3000) {
      this.push(message, 'info', duration);
    },
  },
});

