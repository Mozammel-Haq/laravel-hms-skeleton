import { createRouter, createWebHistory } from 'vue-router'
import Dashboard from '../views/Dashboard.vue'

const routes = [
  {
    path: '/',
    name: 'Dashboard',
    component: Dashboard
  },
  {
    path: '/forbidden',
    name: 'Forbidden',
    component: () => import('../views/Forbidden.vue')
  },
  {
    path: '/staff',
    name: 'StaffDirectory',
    component: () => import('../views/StaffDirectory.vue')
  },
  {
    path: '/staff/:id',
    name: 'StaffView',
    component: () => import('../views/StaffView.vue')
  },
  {
    path: '/leaves',
    name: 'Leaves',
    component: () => import('../views/Leaves.vue')
  },
  {
    path: '/login',
    name: 'Login',
    component: () => import('../views/Login.vue')
  },
  {
    path: '/hr/departments',
    name: 'Departments',
    component: () => import('../views/Departments.vue')
  },
  {
    path: '/hr/designations',
    name: 'Designations',
    component: () => import('../views/Designations.vue')
  }
]

const router = createRouter({
  history: createWebHistory(),
  routes
})

export default router
