import { createRouter, createWebHistory } from 'vue-router'
import Dashboard from '../views/Dashboard.vue'

const routes = [
  {
    path: '/',
    name: 'Dashboard',
    component: Dashboard
  },
  {
    path: '/inquiries',
    name: 'InquiryLog',
    component: () => import('../views/InquiryLog.vue')
  }
]

const router = createRouter({
  history: createWebHistory(),
  routes
})

export default router
