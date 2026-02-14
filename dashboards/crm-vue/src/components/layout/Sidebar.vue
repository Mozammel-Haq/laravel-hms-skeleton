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

          <li class="menu-title"><span>CRM</span></li>
          <li class="submenu">
            <a href="#"><i class="ti ti-compass"></i><span>Leads</span><span class="menu-arrow"></span></a>
            <ul>
              <li><router-link to="/crm/leads" active-class="active" exact-active-class="active">Lead List</router-link></li>
              <li><router-link to="/crm/lead-capture" active-class="active" exact-active-class="active">Lead Capture</router-link></li>
              <li><router-link to="/crm/segments" active-class="active" exact-active-class="active">Segments</router-link></li>
              <li><router-link to="/crm/pipelines" active-class="active" exact-active-class="active">Pipelines & Stages</router-link></li>
            </ul>
          </li>

          <li class="submenu">
            <a href="#"><i class="ti ti-users-group"></i><span>Contacts</span><span class="menu-arrow"></span></a>
            <ul>
              <li><router-link to="/crm/contacts" active-class="active" exact-active-class="active">All Contacts</router-link></li>
              <li><router-link to="/crm/patients" active-class="active" exact-active-class="active">Patients</router-link></li>
              <li><router-link to="/crm/organizations" active-class="active" exact-active-class="active">Organizations</router-link></li>
              <li><router-link to="/crm/tags" active-class="active" exact-active-class="active">Tags</router-link></li>
            </ul>
          </li>

          <li class="submenu">
            <a href="#"><i class="ti ti-broadcast"></i><span>Campaigns</span><span class="menu-arrow"></span></a>
            <ul>
              <li><router-link to="/crm/campaigns/email" active-class="active" exact-active-class="active">Email</router-link></li>
              <li><router-link to="/crm/campaigns/sms" active-class="active" exact-active-class="active">SMS</router-link></li>
              <li><router-link to="/crm/campaigns/templates" active-class="active" exact-active-class="active">Templates</router-link></li>
              <li><router-link to="/crm/campaigns/segments" active-class="active" exact-active-class="active">Segments</router-link></li>
            </ul>
          </li>

          <li class="submenu">
            <a href="#"><i class="ti ti-robot"></i><span>Automations</span><span class="menu-arrow"></span></a>
            <ul>
              <li><router-link to="/crm/automations/workflows" active-class="active" exact-active-class="active">Workflows</router-link></li>
              <li><router-link to="/crm/automations/triggers" active-class="active" exact-active-class="active">Triggers</router-link></li>
            </ul>
          </li>

          <li class="submenu">
            <a href="#"><i class="ti ti-lifebuoy"></i><span>Tickets</span><span class="menu-arrow"></span></a>
            <ul>
              <li><router-link to="/crm/tickets" active-class="active" exact-active-class="active">All Tickets</router-link></li>
              <li><router-link to="/crm/tickets/categories" active-class="active" exact-active-class="active">Categories</router-link></li>
              <li><router-link to="/crm/tickets/sla" active-class="active" exact-active-class="active">SLA</router-link></li>
            </ul>
          </li>

          <li class="submenu">
            <a href="#"><i class="ti ti-calendar-event"></i><span>Follow-ups</span><span class="menu-arrow"></span></a>
            <ul>
              <li><router-link to="/crm/followups/reminders" active-class="active" exact-active-class="active">Reminders</router-link></li>
              <li><router-link to="/crm/followups/schedules" active-class="active" exact-active-class="active">Schedules</router-link></li>
            </ul>
          </li>

          <li class="submenu">
            <a href="#"><i class="ti ti-chart-line"></i><span>Analytics</span><span class="menu-arrow"></span></a>
            <ul>
              <li><router-link to="/crm/analytics/funnel" active-class="active" exact-active-class="active">Conversion Funnel</router-link></li>
              <li><router-link to="/crm/analytics/campaigns" active-class="active" exact-active-class="active">Campaign Performance</router-link></li>
              <li><router-link to="/crm/analytics/retention" active-class="active" exact-active-class="active">Retention</router-link></li>
            </ul>
          </li>

          <li class="submenu">
            <a href="#"><i class="ti ti-settings"></i><span>CRM Settings</span><span class="menu-arrow"></span></a>
            <ul>
              <li><router-link to="/crm/settings/channels" active-class="active" exact-active-class="active">Channels</router-link></li>
              <li><router-link to="/crm/settings/integrations" active-class="active" exact-active-class="active">Integrations</router-link></li>
              <li><router-link to="/crm/settings/pipelines" active-class="active" exact-active-class="active">Pipelines</router-link></li>
              <li><router-link to="/crm/settings/tags" active-class="active" exact-active-class="active">Tags</router-link></li>
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
