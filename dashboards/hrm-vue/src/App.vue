<template>
  <component :is="currentLayout">
    <router-view />
  </component>
</template>

<script setup>
import { computed } from 'vue'
import { useRoute } from 'vue-router'
import AppLayout from './components/layout/AppLayout.vue'

const route = useRoute()
const currentLayout = computed(() => ((route.path === '/login' || route.path === '/forbidden') ? 'div' : AppLayout))
</script>
