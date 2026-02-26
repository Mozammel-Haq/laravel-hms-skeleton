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

    <ToastContainer />

    <div class="modal fade" id="searchModal">
      <div class="modal-dialog modal-lg">
        <div class="modal-content bg-transparent">
          <div class="card shadow-none mb-0">
            <div class="px-3 py-2 d-flex flex-row align-items-center" id="search-top">
              <i class="ti ti-search fs-22"></i>
              <input type="search" class="form-control border-0" placeholder="Search" />
              <button type="button" class="btn p-0" data-bs-dismiss="modal" aria-label="Close">
                <i class="ti ti-x fs-22"></i>
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue';
import Header from './Header.vue';
import Sidebar from './Sidebar.vue';
import Footer from './Footer.vue';
import ToastContainer from '../feedback/ToastContainer.vue';

const isSidebarCollapsed = ref(false);

const toggleSidebar = () => {
  isSidebarCollapsed.value = !isSidebarCollapsed.value;
};
</script>

<style>
.page-wrapper .table-responsive {
  min-height: 240px;
}
.page-wrapper .card-body .table {
  margin-bottom: 0;
}
</style>
