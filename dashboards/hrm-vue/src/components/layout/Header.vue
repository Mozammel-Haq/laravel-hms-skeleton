<template>
  <header class="navbar-header">
    <div class="page-container topbar-menu">
      <div class="d-flex align-items-center gap-2">
        <a href="#" class="logo">
          <span class="logo-light">
            <span class="logo-lg"><img :src="assetBase + '/img/logo.svg'" alt="logo"></span>
            <span class="logo-sm"><img :src="assetBase + '/img/logo-small.svg'" alt="small logo"></span>
          </span>
          <span class="logo-dark d-none">
            <span class="logo-lg"><img :src="assetBase + '/img/logo-white.svg'" alt="dark logo"></span>
          </span>
        </a>

        <a id="mobile_btn" class="mobile-btn" href="#sidebar" @click.prevent="handleMobileToggle">
          <i class="ti ti-menu-deep fs-24"></i>
        </a>

        <button id="toggle_btn2" class="sidenav-toggle-btn btn border-0 p-0 active" @click="$emit('toggle')">
          <i class="ti ti-arrow-right"></i>
        </button>

        <div class="me-auto d-lg-flex d-none header-search">
          <div class="input-icon-start position-relative me-2">
            <span class="input-icon-addon">
              <i class="ti ti-search"></i>
            </span>
            <input type="text" class="form-control shadow-sm" placeholder="Search" />
            <span class="input-icon-addon text-dark shadow fs-18 d-inline-flex p-0 header-search-icon">
              <i class="ti ti-command"></i>
            </span>
          </div>
        </div>
      </div>

      <div class="ms-auto d-flex align-items-center">
        <div class="header-item d-flex d-lg-none me-2">
          <button class="topbar-link btn btn-icon" data-bs-toggle="modal" data-bs-target="#searchModal" type="button">
            <i class="ti ti-search fs-16"></i>
          </button>
        </div>

        <div class="me-4 text-end d-none d-md-block">
          <div class="fw-semibold fs-14">{{ clinicName }}</div>
          <div class="text-muted fs-12">{{ clinicBranch }}</div>
        </div>

        <a href="javascript:void(0);" class="btn btn-liner-gradient me-3 d-lg-flex d-none">
          AI Assistance<i class="ti ti-chart-bubble-filled ms-1"></i>
        </a>

        <div class="header-item">
          <div class="dropdown me-2">
            <a href="#" class="btn topbar-link"><i class="ti ti-calendar-due"></i></a>
          </div>
        </div>

        <div class="header-item">
          <div class="dropdown me-2">
            <a href="#" class="btn topbar-link"><i class="ti ti-settings-2"></i></a>
          </div>
        </div>

        <div class="header-item d-flex me-2">
          <button class="topbar-link btn btn-icon topbar-link" id="light-dark-mode" type="button" @click="toggleTheme">
            <i class="ti ti-moon fs-16"></i>
          </button>
        </div>



        <div class="header-item">
          <div class="dropdown me-3">
            <button class="topbar-link btn btn-icon topbar-link dropdown-toggle drop-arrow-none" data-bs-toggle="dropdown" data-bs-offset="0,24" type="button" aria-haspopup="false" aria-expanded="false">
              <i class="ti ti-bell-check fs-16"></i>
            </button>
            <div class="dropdown-menu p-0 dropdown-menu-end dropdown-menu-lg" style="min-height: 200px;">
              <div class="p-2 border-bottom">
                <div class="row align-items-center">
                  <div class="col">
                    <h6 class="m-0 fs-16 fw-semibold">Notifications</h6>
                  </div>
                </div>
              </div>
              <div class="p-4 text-center">
                <p class="text-muted mb-0">No new notifications</p>
              </div>
            </div>
          </div>
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
            <li><a class="dropdown-item text-danger" href="#" @click.prevent="handleLogout">Logout</a></li>
          </ul>
        </div>
      </div>
    </div>
  </header>
</template>

<script setup>
import { useAuthStore } from '../../store/authStore';
import { computed } from 'vue';
import { useRouter } from 'vue-router';
import { onMounted } from 'vue';

const auth = useAuthStore();
const assetBase = window.LARAVEL_ASSET_BASE || '/assets';
const router = useRouter();

defineProps({
  isCollapsed: Boolean
});

const getInitials = (name) => {
  if (!name) return '??';
  return name.split(' ').map(n => n[0]).join('').toUpperCase().substring(0, 2);
};

const handleLogout = async () => {
  try {
    await auth.logout();
  } finally {
    router.replace('/login');
  }
};

const clinicName = computed(() => auth.user?.clinic?.name || 'Dhaka Medical Center');
const clinicBranch = computed(() => auth.user?.clinic?.branch_name || 'Main Branch');

const toggleTheme = () => {
  const html = document.documentElement;
  const current = html.getAttribute('data-bs-theme') || 'light';
  const next = current === 'light' ? 'dark' : 'light';
  html.setAttribute('data-bs-theme', next);
  try {
    const key = '__THEME_CONFIG__';
    const cfg = sessionStorage.getItem(key);
    const obj = cfg ? JSON.parse(cfg) : {};
    obj.theme = next;
    sessionStorage.setItem(key, JSON.stringify(obj));
    window.config = Object.assign({}, window.config || {}, { theme: next });
  } catch (_) {}
};

const handleMobileToggle = () => {
  const wrapper = document.querySelector('.main-wrapper');
  if (wrapper) {
    wrapper.classList.toggle('slide-nav');
  }
  let overlay = document.querySelector('.sidebar-overlay');
  if (!overlay) {
    overlay = document.createElement('div');
    overlay.className = 'sidebar-overlay';
    document.body.appendChild(overlay);
  }
  overlay.classList.toggle('opened');
  document.documentElement.classList.add('menu-opened');
};

onMounted(() => {
  const html = document.documentElement;
  const w = window.innerWidth;
  if (w <= 767.98) {
    html.setAttribute('data-layout', 'full-width');
  }
});
</script>
