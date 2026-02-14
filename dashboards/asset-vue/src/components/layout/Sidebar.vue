<template>
  <div class="sidebar" :class="{ 'collapsed': isCollapsed }" id="sidebar">
    <div class="sidebar-logo">
      <div>
        <a href="#" class="logo logo-normal">
          <span class="fw-bold text-primary">CityCare</span>
        </a>
        <a href="#" class="logo-small">
          <span class="fw-bold text-primary">CC</span>
        </a>
        <a href="#" class="dark-logo d-none">
          <span class="fw-bold text-primary">CityCare</span>
        </a>
      </div>
      <button class="sidenav-toggle-btn btn border-0 p-0" @click="$emit('toggle')">
        <i class="ti ti-arrow-left text-body"></i>
      </button>
      <button class="sidebar-close d-none">
        <i class="ti ti-x align-middle"></i>
      </button>
    </div>

    <div class="sidebar-inner" data-simplebar>
      <div id="sidebar-menu" class="sidebar-menu">
        <ul>
          <li class="menu-title"><span>Main Menu</span></li>
          <li>
            <router-link to="/">
              <i class="ti ti-layout-dashboard"></i><span>Dashboard</span>
            </router-link>
          </li>
          <li class="menu-title"><span>Assets</span></li>
          <li>
            <router-link to="/inventory">
              <i class="ti ti-box"></i><span>Inventory</span>
            </router-link>
          </li>
          <li>
            <router-link to="/procurement">
              <i class="ti ti-shopping-cart"></i><span>Procurement</span>
            </router-link>
          </li>
          <li>
            <router-link to="/assets">
              <i class="ti ti-tool"></i><span>Asset Registry</span>
            </router-link>
          </li>
          <li>
            <router-link to="/maintenance">
              <i class="ti ti-calendar-event"></i><span>Maintenance</span>
            </router-link>
          </li>
          <li>
            <router-link to="/suppliers">
              <i class="ti ti-truck"></i><span>Suppliers</span>
            </router-link>
          </li>
        </ul>
      </div>
    </div>
  </div>
</template>

<script setup>
defineProps({
  isCollapsed: Boolean
});

defineEmits(['toggle']);
</script>

<style lang="scss" scoped>
.router-link-active {
  color: var(--menu-item-active-color) !important;
  font-weight: 600;
  background: var(--menu-active-bg);
}
</style>
