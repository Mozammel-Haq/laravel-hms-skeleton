<template>
  <header class="navbar-header">
    <div class="page-container topbar-menu">
      <div class="d-flex align-items-center gap-2">
        <a href="#" class="logo">
          <span class="logo-light">
            <span class="logo-lg fw-bold text-primary">CityCare</span>
            <span class="logo-sm fw-bold text-primary">CC</span>
          </span>
          <span class="logo-dark d-none"></span>
        </a>

        <a class="mobile-btn" href="#sidebar">
          <i class="ti ti-menu-deep fs-24"></i>
        </a>

        <button class="sidenav-toggle-btn btn border-0 p-0" @click="$emit('toggle')">
          <i class="ti ti-arrow-right"></i>
        </button>
      </div>

      <div class="ms-auto d-flex align-items-center">
        <div v-if="auth.user?.clinic" class="me-4 text-end d-none d-md-block">
          <div class="fw-semibold fs-14">{{ auth.user.clinic.name }}</div>
          <div class="text-muted fs-12">{{ auth.user.clinic.branch_name || 'Main Branch' }}</div>
        </div>

        <div class="dropdown">
          <button class="btn border-0 d-flex align-items-center" type="button" data-bs-toggle="dropdown">
            <div class="avatar bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 35px; height: 35px;">
              {{ getInitials(auth.user?.name) }}
            </div>
            <span class="ms-2 d-none d-md-inline">{{ auth.user?.name || 'Loading...' }}</span>
          </button>
          <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0">
            <li><a class="dropdown-item" href="#">Profile</a></li>
            <li><a class="dropdown-item" href="#">Settings</a></li>
            <li><hr class="dropdown-divider"></li>
            <li><a class="dropdown-item text-danger" href="#" @click.prevent="auth.logout()">Logout</a></li>
          </ul>
        </div>
      </div>
    </div>
  </header>
</template>

<script setup>
import { useAuthStore } from '../../store/authStore';

const auth = useAuthStore();

defineProps({
  isCollapsed: Boolean
});

const getInitials = (name) => {
  if (!name) return '??';
  return name.split(' ').map(n => n[0]).join('').toUpperCase().substring(0, 2);
};
</script>

<style lang="scss" scoped>
.fs-14 { font-size: 14px; }
.fs-12 { font-size: 12px; }
</style>
