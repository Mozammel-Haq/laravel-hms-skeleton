<template>
  <AppLayout>
    <router-view />
  </AppLayout>
</template>

<script setup>
import { onMounted } from 'vue';
import AppLayout from './components/layout/AppLayout.vue';
import { useAuthStore } from './store/authStore';

const auth = useAuthStore();

onMounted(() => {
  auth.fetchUser();
});
</script>
