import { createRouter, createWebHistory } from 'vue-router'
import Dashboard from '../views/Dashboard.vue'

const routes = [
  {
    path: '/',
    name: 'Dashboard',
    component: Dashboard
  },
  {
    path: '/staff',
    name: 'StaffDirectory',
    component: () => import('../views/StaffDirectory.vue')
  }
]

const router = createRouter({
  history: createWebHistory(),
  routes
})

export default router
