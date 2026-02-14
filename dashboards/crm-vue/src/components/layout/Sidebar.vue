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
          <li class="menu-title"><span>CRM</span></li>
          <li>
            <router-link to="/inquiries">
              <i class="ti ti-message-dots"></i><span>Inquiry Log</span>
            </router-link>
          </li>
          <li>
            <router-link to="/patients">
              <i class="ti ti-users-group"></i><span>Patients</span>
            </router-link>
          </li>
          <li>
            <router-link to="/loyalty">
              <i class="ti ti-star"></i><span>Loyalty Points</span>
            </router-link>
          </li>
          <li>
            <router-link to="/tickets">
              <i class="ti ti-ticket"></i><span>Support Tickets</span>
            </router-link>
          </li>
          <li>
            <router-link to="/campaigns">
              <i class="ti ti-broadcast"></i><span>Campaigns</span>
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
