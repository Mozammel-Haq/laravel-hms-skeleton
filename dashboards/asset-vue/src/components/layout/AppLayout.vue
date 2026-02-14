<template>
  <div class="main-wrapper">
    <Header :is-collapsed="isSidebarCollapsed" @toggle="toggleSidebar" />
    <Sidebar :is-collapsed="isSidebarCollapsed" @toggle="toggleSidebar" />
    
    <div class="page-wrapper" :class="{ 'sidebar-collapsed': isSidebarCollapsed }">
      <div class="content container-fluid p-4">
        <slot></slot>
      </div>
      <Footer />
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue';
import Header from './Header.vue';
import Sidebar from './Sidebar.vue';
import Footer from './Footer.vue';

const isSidebarCollapsed = ref(false);

const toggleSidebar = () => {
  isSidebarCollapsed.value = !isSidebarCollapsed.value;
};
</script>

<style lang="scss">
// Global styles already in main.scss
</style>
