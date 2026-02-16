<template>
  <div class="sidebar" :class="{ 'collapsed': isCollapsed }" id="sidebar">
    <div class="sidebar-logo">
      <div>
        <a href="#" class="logo logo-normal">
          <img :src="assetBase + '/img/logo.svg'" alt="Logo" />
        </a>
        <a href="#" class="logo-small">
          <img :src="assetBase + '/img/logo-small.svg'" alt="Logo" />
        </a>
        <a href="#" class="dark-logo">
          <img :src="assetBase + '/img/logo-white.svg'" alt="Logo" />
        </a>
      </div>
      <button class="sidenav-toggle-btn btn border-0 p-0 active" id="toggle_btn" @click="$emit('toggle')">
        <i class="ti ti-arrow-left text-body"></i>
      </button>
      <button class="sidebar-close" @click="closeMobileSidebar">
        <i class="ti ti-x align-middle"></i>
      </button>
    </div>

    <div class="sidebar-inner" data-simplebar>
      <div id="sidebar-menu" class="sidebar-menu">
        <div class="sidebar-top shadow-sm p-2 rounded-1 mb-3 dropend">
          <a href="javascript:void(0);" class="drop-arrow-none">
            <div class="d-flex justify-content-between align-items-center">
              <div class="d-flex align-items-center">
                <span class="avatar rounded-circle flex-shrink-0 p-0 border bg-white overflow-hidden d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                  <img :src="assetBase + '/img/icons/trustcare.svg'" alt="Clinic Logo" class="w-100 h-100 object-fit-contain p-2" />
                </span>
                <div class="ms-2">
                  <h6 class="fs-14 fw-semibold mb-0">Trustcare Clinic</h6>
                  <p class="fs-13 mb-0">Location</p>
                </div>
              </div>
            </div>
          </a>
        </div>

        <ul>
          <li class="menu-title"><span>Main Menu</span></li>
          <li>
            <router-link to="/" active-class="active" exact-active-class="active">
              <i class="ti ti-layout-dashboard"></i><span>Dashboard</span>
            </router-link>
          </li>

          <li class="menu-title"><span>Human Resources</span></li>

          <li class="submenu" v-if="canViewEmployees">
            <a href="#"><i class="ti ti-users"></i><span>Employees</span><span class="menu-arrow"></span></a>
            <ul>
              <li><router-link to="/staff" active-class="active" exact-active-class="active">Directory</router-link></li>
              <li><router-link to="/hr/departments" active-class="active" exact-active-class="active">Departments</router-link></li>
              <li><router-link to="/hr/designations" active-class="active" exact-active-class="active">Designations</router-link></li>
              <li><router-link to="/hr/shifts" active-class="active" exact-active-class="active">Shifts</router-link></li>
              <li><router-link to="/hr/profiles" active-class="active" exact-active-class="active">Profiles</router-link></li>
            </ul>
          </li>

          <li class="submenu" v-if="canViewAttendance">
            <a href="#"><i class="ti ti-fingerprint"></i><span>Attendance</span><span class="menu-arrow"></span></a>
            <ul>
              <li><router-link to="/hr/attendance" active-class="active" exact-active-class="active">Daily Attendance</router-link></li>
              <li><router-link to="/hr/timesheets" active-class="active" exact-active-class="active">Timesheets</router-link></li>
              <li><router-link to="/hr/holidays" active-class="active" exact-active-class="active">Holidays</router-link></li>
              <li><router-link to="/hr/overtime" active-class="active" exact-active-class="active">Overtime</router-link></li>
              <li><router-link to="/hr/shift-calendar" active-class="active" exact-active-class="active">Shift Calendar</router-link></li>
            </ul>
          </li>

          <li class="submenu">
            <a href="#"><i class="ti ti-plane"></i><span>Leaves</span><span class="menu-arrow"></span></a>
            <ul>
              <li><router-link to="/hr/leaves/requests" active-class="active" exact-active-class="active">Requests</router-link></li>
              <li v-if="canManageLeaves"><router-link to="/hr/leaves/approvals" active-class="active" exact-active-class="active">Approvals</router-link></li>
              <li v-if="canManageLeaves"><router-link to="/hr/leaves/calendar" active-class="active" exact-active-class="active">Leave Calendar</router-link></li>
              <li v-if="canManageLeaves"><router-link to="/hr/leaves/types" active-class="active" exact-active-class="active">Leave Types</router-link></li>
              <li v-if="canManageLeaves"><router-link to="/hr/leaves/accruals" active-class="active" exact-active-class="active">Accruals</router-link></li>
            </ul>
          </li>

          <li class="submenu" v-if="canViewPayroll">
            <a href="#"><i class="ti ti-cash"></i><span>Payroll</span><span class="menu-arrow"></span></a>
            <ul>
              <li><router-link to="/hr/payroll/runs" active-class="active" exact-active-class="active">Payroll Runs</router-link></li>
              <li><router-link to="/hr/payroll/payslips" active-class="active" exact-active-class="active">Payslips</router-link></li>
              <li><router-link to="/hr/payroll/structure" active-class="active" exact-active-class="active">Salary Structure</router-link></li>
              <li><router-link to="/hr/payroll/allowances" active-class="active" exact-active-class="active">Allowances</router-link></li>
              <li><router-link to="/hr/payroll/deductions" active-class="active" exact-active-class="active">Deductions</router-link></li>
              <li><router-link to="/hr/payroll/taxes" active-class="active" exact-active-class="active">Taxes</router-link></li>
            </ul>
          </li>

          <li class="submenu" v-if="canViewRecruitment">
            <a href="#"><i class="ti ti-briefcase"></i><span>Recruitment</span><span class="menu-arrow"></span></a>
            <ul>
              <li><router-link to="/hr/recruitment/jobs" active-class="active" exact-active-class="active">Job Posts</router-link></li>
              <li><router-link to="/hr/recruitment/candidates" active-class="active" exact-active-class="active">Candidates</router-link></li>
              <li><router-link to="/hr/recruitment/interviews" active-class="active" exact-active-class="active">Interviews</router-link></li>
              <li><router-link to="/hr/recruitment/offers" active-class="active" exact-active-class="active">Offers</router-link></li>
              <li><router-link to="/hr/recruitment/onboarding" active-class="active" exact-active-class="active">Onboarding</router-link></li>
            </ul>
          </li>

          <li class="submenu" v-if="canViewTraining">
            <a href="#"><i class="ti ti-school"></i><span>Training</span><span class="menu-arrow"></span></a>
            <ul>
              <li><router-link to="/hr/training/courses" active-class="active" exact-active-class="active">Courses</router-link></li>
              <li><router-link to="/hr/training/sessions" active-class="active" exact-active-class="active">Sessions</router-link></li>
              <li><router-link to="/hr/training/evaluations" active-class="active" exact-active-class="active">Evaluations</router-link></li>
            </ul>
          </li>

          <li class="submenu" v-if="canViewPerformance">
            <a href="#"><i class="ti ti-target"></i><span>Performance</span><span class="menu-arrow"></span></a>
            <ul>
              <li><router-link to="/hr/performance/kpis" active-class="active" exact-active-class="active">KPIs</router-link></li>
              <li><router-link to="/hr/performance/goals" active-class="active" exact-active-class="active">Goals</router-link></li>
              <li><router-link to="/hr/performance/reviews" active-class="active" exact-active-class="active">Reviews</router-link></li>
              <li><router-link to="/hr/performance/appraisals" active-class="active" exact-active-class="active">Appraisals</router-link></li>
            </ul>
          </li>

          <li class="submenu" v-if="canViewCompliance">
            <a href="#"><i class="ti ti-file-text"></i><span>Compliance</span><span class="menu-arrow"></span></a>
            <ul>
              <li><router-link to="/hr/compliance/policies" active-class="active" exact-active-class="active">Policies</router-link></li>
              <li><router-link to="/hr/compliance/documents" active-class="active" exact-active-class="active">Documents</router-link></li>
              <li><router-link to="/hr/compliance/letters" active-class="active" exact-active-class="active">HR Letters</router-link></li>
            </ul>
          </li>

          <li class="submenu" v-if="canManageHrSettings">
            <a href="#"><i class="ti ti-settings"></i><span>HR Settings</span><span class="menu-arrow"></span></a>
            <ul>
              <li><router-link to="/hr/settings" active-class="active" exact-active-class="active">General</router-link></li>
              <li><router-link to="/hr/settings/roles" active-class="active" exact-active-class="active">Roles & Permissions</router-link></li>
            </ul>
          </li>
        </ul>
      </div>
    </div>
  </div>
</template>

<script setup>
import { computed, onMounted, watch } from 'vue';
import { useRoute } from 'vue-router';
import { useAuthStore } from '../../store/authStore';
const assetBase = window.LARAVEL_ASSET_BASE || '/assets';
defineProps({
  isCollapsed: Boolean
});
defineEmits(['toggle']);
const route = useRoute();
const auth = useAuthStore();

const abilities = computed(() => Array.isArray(auth.user?.abilities) ? auth.user.abilities : []);
const has = (perm) => abilities.value.includes(perm);

const canViewEmployees = computed(() => has('view_staff'));
const canViewAttendance = computed(() => has('view_hrm_dashboard') && has('view_staff'));
const canManageLeaves = computed(() => has('manage_leaves'));
const canViewPayroll = computed(() => has('view_reports'));
const canViewRecruitment = computed(() => has('view_reports'));
const canViewTraining = computed(() => has('view_reports'));
const canViewPerformance = computed(() => has('view_reports'));
const canViewCompliance = computed(() => has('view_reports'));
const canManageHrSettings = computed(() => has('manage_roles') || has('manage_clinic_settings'));

const closeMobileSidebar = () => {
  const wrapper = document.querySelector('.main-wrapper');
  if (wrapper) {
    wrapper.classList.remove('slide-nav');
  }
  const overlay = document.querySelector('.sidebar-overlay');
  if (overlay) {
    overlay.classList.remove('opened');
  }
  document.documentElement.classList.remove('menu-opened');
};

onMounted(() => {
  const container = document.querySelector('#sidebar-menu');
  if (!container) return;
  container.querySelectorAll('li.submenu > a[href="#"], li.submenu > a:not([href])').forEach((a) => {
    a.addEventListener('click', (e) => {
      e.preventDefault();
      e.stopPropagation();
      if (typeof e.stopImmediatePropagation === 'function') {
        e.stopImmediatePropagation();
      }
      const link = e.currentTarget;
      const openSiblings = link.closest('ul')?.querySelectorAll('a.subdrop') || [];
      openSiblings.forEach((el) => {
        if (el !== link) {
          el.classList.remove('subdrop');
          const ul = el.nextElementSibling;
          if (ul && ul.tagName === 'UL') {
            ul.style.display = 'none';
          }
        }
      });
      link.classList.toggle('subdrop');
      const next = link.nextElementSibling;
      if (next && next.tagName === 'UL') {
        if (link.classList.contains('subdrop')) {
          next.style.display = 'block';
        } else {
          next.style.display = 'none';
        }
      }
    });
  });
  const expandActiveSections = () => {
    container.querySelectorAll('li.submenu').forEach((li) => {
      const anchor = li.querySelector(':scope > a');
      const ul = li.querySelector(':scope > ul');
      let activeChild = false;
      li.querySelectorAll(':scope ul a.router-link-active, :scope ul a.active').forEach(() => {
        activeChild = true;
      });
      if (activeChild && anchor && ul) {
        anchor.classList.add('subdrop', 'active');
        ul.style.display = 'block';
      } else if (anchor && ul) {
        if (!anchor.matches(':focus')) {
          anchor.classList.remove('subdrop');
          ul.style.display = 'none';
        }
      }
    });
  };
  expandActiveSections();
  watch(() => route.path, () => expandActiveSections());
});
</script>
