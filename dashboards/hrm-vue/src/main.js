import { createApp } from 'vue'
import { createPinia } from 'pinia'
import router from './router'
import App from './App.vue'
import { useAuthStore } from './store/authStore'
import './style.css'

const app = createApp(App)

const pinia = createPinia()
app.use(pinia)
app.use(router)

router.beforeEach(async (to, from, next) => {
  const publicPaths = ['/login', '/forbidden']
  const requiresAuth = !publicPaths.includes(to.path)
  const auth = useAuthStore()
  const token = localStorage.getItem('hrm_token')

  console.debug('[Guard] enter', { to: to.path, requiresAuth, hasToken: !!token, hasUser: !!auth.user })

  if (requiresAuth) {
    if (!auth.user && token) {
      try {
        console.debug('[Guard] fetchUser start')
        await auth.fetchUser()
        console.debug('[Guard] fetchUser done', { hasUser: !!auth.user, abilities: auth.user?.abilities })
      } catch (e) {}
    }

    if (!auth.user) {
      console.warn('[Guard] no user, redirect to /login')
      return next({ path: '/login', query: { redirect: to.fullPath } })
    }

    if (Array.isArray(auth.user.abilities) && !auth.user.abilities.includes('view_hrm_dashboard')) {
      console.warn('[Guard] forbidden hrm access, redirect to /forbidden')
      return next({ path: '/forbidden' })
    }
  }

  if (to.path === '/login' && auth.user) {
    console.debug('[Guard] already authenticated, redirecting to /')
    return next({ path: '/' })
  }

  console.debug('[Guard] allow', to.path)
  return next()
})

app.mount('#app')
