import { createRouter, createWebHistory } from 'vue-router'
import Dashboard from '../views/Dashboard.vue'

const routes = [
  {
    path: '/',
    name: 'Dashboard',
    component: Dashboard
  },
  {
    path: '/inventory',
    name: 'Inventory',
    component: () => import('../views/Inventory.vue')
  },
  {
    path: '/procurement',
    name: 'Procurement',
    component: () => import('../views/Procurement.vue')
  }
]

const router = createRouter({
  history: createWebHistory(),
  routes
})

export default router
