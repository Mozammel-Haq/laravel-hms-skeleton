<template>
  <div class="sidebar" :class="{ 'collapsed': isCollapsed }" id="sidebar">
    <div class="sidebar-logo">
      <div>
        <a href="#" class="logo logo-normal"><img :src="assetBase + '/img/logo.svg'" alt="Logo" /></a>
        <a href="#" class="logo-small"><img :src="assetBase + '/img/logo-small.svg'" alt="Logo" /></a>
        <a href="#" class="dark-logo"><img :src="assetBase + '/img/logo-white.svg'" alt="Logo" /></a>
      </div>
      <button class="sidenav-toggle-btn btn border-0 p-0" id="toggle_btn" @click="$emit('toggle')">
        <i class="ti ti-arrow-left text-body"></i>
      </button>
      <button class="sidebar-close">
        <i class="ti ti-x align-middle"></i>
      </button>
    </div>

    <div class="sidebar-inner" data-simplebar>
      <div id="sidebar-menu" class="sidebar-menu">
        <ul>
          <li class="menu-title"><span>Main Menu</span></li>
          <li>
            <router-link to="/" active-class="active" exact-active-class="active">
              <i class="ti ti-layout-dashboard"></i><span>Dashboard</span>
            </router-link>
          </li>

          <li class="menu-title"><span>Asset Management</span></li>
          <li class="submenu">
            <a href="#"><i class="ti ti-box"></i><span>Inventory</span><span class="menu-arrow"></span></a>
            <ul>
              <li><router-link to="/assets" active-class="active" exact-active-class="active">All Assets</router-link></li>
              <li><router-link to="/assets/categories" active-class="active" exact-active-class="active">Categories</router-link></li>
              <li><router-link to="/assets/locations" active-class="active" exact-active-class="active">Locations</router-link></li>
              <li><router-link to="/assets/status" active-class="active" exact-active-class="active">Status</router-link></li>
              <li><router-link to="/assets/barcodes" active-class="active" exact-active-class="active">Barcodes</router-link></li>
              <li><router-link to="/assets/manufacturers" active-class="active" exact-active-class="active">Manufacturers</router-link></li>
            </ul>
          </li>

          <li class="submenu">
            <a href="#"><i class="ti ti-shopping-cart"></i><span>Procurement</span><span class="menu-arrow"></span></a>
            <ul>
              <li><router-link to="/procurement/requisitions" active-class="active" exact-active-class="active">Requisitions</router-link></li>
              <li><router-link to="/procurement/purchase-orders" active-class="active" exact-active-class="active">Purchase Orders</router-link></li>
              <li><router-link to="/procurement/vendors" active-class="active" exact-active-class="active">Vendors</router-link></li>
              <li><router-link to="/procurement/grn" active-class="active" exact-active-class="active">Goods Received</router-link></li>
            </ul>
          </li>

          <li class="submenu">
            <a href="#"><i class="ti ti-tools"></i><span>Maintenance</span><span class="menu-arrow"></span></a>
            <ul>
              <li><router-link to="/maintenance/work-orders" active-class="active" exact-active-class="active">Work Orders</router-link></li>
              <li><router-link to="/maintenance/schedule" active-class="active" exact-active-class="active">Preventive Schedule</router-link></li>
              <li><router-link to="/maintenance/vendors" active-class="active" exact-active-class="active">Service Vendors</router-link></li>
              <li><router-link to="/maintenance/downtime" active-class="active" exact-active-class="active">Downtime Log</router-link></li>
            </ul>
          </li>

          <li class="submenu">
            <a href="#"><i class="ti ti-arrows-transfer-up"></i><span>Lifecycle</span><span class="menu-arrow"></span></a>
            <ul>
              <li><router-link to="/lifecycle/assignments" active-class="active" exact-active-class="active">Assignments</router-link></li>
              <li><router-link to="/lifecycle/transfers" active-class="active" exact-active-class="active">Transfers</router-link></li>
              <li><router-link to="/lifecycle/depreciation" active-class="active" exact-active-class="active">Depreciation</router-link></li>
              <li><router-link to="/lifecycle/disposals" active-class="active" exact-active-class="active">Disposals</router-link></li>
            </ul>
          </li>

          <li class="submenu">
            <a href="#"><i class="ti ti-clipboard-list"></i><span>Audits</span><span class="menu-arrow"></span></a>
            <ul>
              <li><router-link to="/audits" active-class="active" exact-active-class="active">Audit Sessions</router-link></li>
              <li><router-link to="/audits/checklists" active-class="active" exact-active-class="active">Checklists</router-link></li>
            </ul>
          </li>

          <li class="submenu">
            <a href="#"><i class="ti ti-report-analytics"></i><span>Reports</span><span class="menu-arrow"></span></a>
            <ul>
              <li><router-link to="/reports/inventory" active-class="active" exact-active-class="active">Inventory</router-link></li>
              <li><router-link to="/reports/maintenance" active-class="active" exact-active-class="active">Maintenance Cost</router-link></li>
              <li><router-link to="/reports/depreciation" active-class="active" exact-active-class="active">Depreciation</router-link></li>
            </ul>
          </li>

          <li class="submenu">
            <a href="#"><i class="ti ti-settings"></i><span>Asset Settings</span><span class="menu-arrow"></span></a>
            <ul>
              <li><router-link to="/settings/asset-types" active-class="active" exact-active-class="active">Asset Types</router-link></li>
              <li><router-link to="/settings/warehouses" active-class="active" exact-active-class="active">Warehouses</router-link></li>
              <li><router-link to="/settings/custom-fields" active-class="active" exact-active-class="active">Custom Fields</router-link></li>
            </ul>
          </li>
        </ul>
      </div>
    </div>
  </div>
</template>

<script setup>
const assetBase = window.LARAVEL_ASSET_BASE || '/assets';
defineProps({
  isCollapsed: Boolean
});

defineEmits(['toggle']);
</script>
