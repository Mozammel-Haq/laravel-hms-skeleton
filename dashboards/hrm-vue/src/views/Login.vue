<template>
  <div class="auth-wrapper d-flex flex-column min-vh-100">
    <div class="container-fluid flex-grow-1">
      <div class="row min-vh-100">
        <div class="col-lg-6 d-none d-lg-flex p-0">
          <div class="auth-side w-100 h-100 position-relative" :style="sideStyle">
            <div class="auth-overlay position-absolute top-0 start-0 w-100 h-100"></div>
            <svg class="auth-pattern position-absolute top-0 start-0 w-100 h-100" xmlns="http://www.w3.org/2000/svg">
              <defs>
                <pattern id="grid" width="40" height="40" patternUnits="userSpaceOnUse">
                  <path d="M 40 0 L 0 0 0 40" fill="none" stroke="rgba(255,255,255,0.06)" stroke-width="1"/>
                </pattern>
                <linearGradient id="fade" x1="0" y1="0" x2="0" y2="1">
                  <stop offset="0%" stop-color="rgba(0,0,0,0)" />
                  <stop offset="100%" stop-color="rgba(0,0,0,0.15)" />
                </linearGradient>
              </defs>
              <rect width="100%" height="100%" fill="url(#grid)"/>
              <rect width="100%" height="100%" fill="url(#fade)"/>
            </svg>
            <div class="auth-hero d-flex align-items-center justify-content-center w-100 h-100">
              <div class="glass border rounded-4 p-4 p-lg-5 w-75">
                <div class="text-center mb-4">
                  <h4 class="text-white fw-bold mb-2">Demo Login Accounts</h4>
                  <p class="text-white-50 small mb-0">Click a role to auto-fill credentials</p>
                </div>

                <div class="row g-3">
                  <div class="col-4" v-for="demo in demoAccounts" :key="demo.email">
                    <button type="button"
                      class="card-left w-100 d-flex align-items-center justify-content-center gap-2 py-3 rounded-3 border-0"
                      @click="fillDemo(demo.email)">
                      <i :class="'ti ' + demo.icon + ' fs-4'"></i>
                      <span>{{ demo.role }}</span>
                    </button>
                  </div>
                </div>

              </div>
            </div>
          </div>
        </div>
        <div class="col-lg-6 d-flex align-items-center justify-content-center px-4 px-lg-5 py-5">
          <div class="w-100" style="max-width: 520px;">
            <div class="text-center mb-4 d-lg-none">
              <img :src="assetBase + '/img/logo.svg'" alt="Logo" style="height:36px" />
            </div>
            <div class="card border-1 rounded-3 p-4 px-5">
              <div class="card-body p-0">
                <div class="text-center mb-3 d-none d-lg-block">
                  <img :src="assetBase + '/img/logo.svg'" alt="Brand" style="height:36px" />
                </div>
                <h4 class="mb-1 text-center">Sign In</h4>
                <p class="text-muted mb-4 text-center">Please enter below details to access the dashboard</p>


                <form @submit.prevent="handleSubmit">
                  <div class="mb-3">
                    <label class="form-label">Email Address</label>
                    <div class="input-group">
                      <span class="input-group-text"><i class="ti ti-mail"></i></span>
                      <input v-model="email" type="email" class="form-control" placeholder="Enter Email Address" required />
                    </div>
                  </div>
                  <div class="mb-3">
                    <label class="form-label">Password</label>
                    <div class="input-group">
                      <span class="input-group-text"><i class="ti ti-lock"></i></span>
                      <input v-model="password" :type="showPassword ? 'text' : 'password'" class="form-control" placeholder="****************" required />
                      <button type="button" class="btn btn-outline-primary" @click="togglePassword" :aria-label="showPassword ? 'Hide password' : 'Show password'">
                        <i :class="showPassword ? 'ti ti-eye' : 'ti ti-eye-off'"></i>
                      </button>
                    </div>
                  </div>
                  <div class="d-flex justify-content-between align-items-center mb-3">
                    <div class="form-check">
                      <input v-model="rememberMe" class="form-check-input" type="checkbox" id="rememberMe" />
                      <label class="form-check-label" for="rememberMe">Remember Me</label>
                    </div>
                    <a href="#" class="text-danger small">Forgot your password?</a>
                  </div>
                  <div v-if="error" class="alert alert-danger py-2">{{ error }}</div>
                  <button class="btn btn-primary w-100" :disabled="loading">
                    <span v-if="loading" class="spinner-border spinner-border-sm me-2"></span>
                    Login
                  </button>
                </form>
                <div class="d-flex align-items-center my-3">
                  <hr class="flex-grow-1"/>
                  <span class="text-muted px-2">OR</span>
                  <hr class="flex-grow-1"/>
                </div>
                <div class="d-flex justify-content-center gap-2 mb-2">
                  <a href="#" class="btn btn-light border rounded-circle p-0 social-btn" aria-label="Facebook">
                    <i class="ti ti-brand-facebook-filled"></i>
                  </a>
                  <a href="#" class="btn btn-light border rounded-circle p-0 social-btn" aria-label="Google">
                    <i class="ti ti-brand-google-filled"></i>
                  </a>
                  <a href="#" class="btn btn-light border rounded-circle p-0 social-btn" aria-label="Apple">
                    <i class="ti ti-brand-apple"></i>
                  </a>
                </div>
                <div class="text-center mt-3">
                  <span class="text-muted">Don’t have an account yet?</span>
                  <a href="#" class="ms-1">Register</a>
                </div>
                                <!-- Mobile Demo Credentials (Visible only on mobile) -->
                <div class="card shadow-sm mb-4 d-lg-none bg-light border-0">
                  <div class="card-body p-3">
                    <h6 class="fw-bold text-center mb-2">Demo Login Accounts</h6>
                    <div class="list-group list-group-flush rounded-3 overflow-hidden">
                      <button v-for="demo in demoAccounts" :key="demo.email"
                        type="button"
                        class="list-group-item list-group-item-action py-2 small"
                        @click="fillDemo(demo.email)">
                        <i :class="'ti ' + demo.icon + ' me-2'"></i>
                        {{ demo.role }}
                      </button>
                    </div>
                    <div class="text-center mt-2">
                      <span class="badge bg-white text-dark border">Password: password</span>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  </template>

  <script setup>
  import { ref, onMounted, watch } from 'vue'
  import { useRouter, useRoute } from 'vue-router'
  import { useAuthStore } from '../store/authStore'

  const assetBase = window.LARAVEL_ASSET_BASE || '/assets'
  const router = useRouter()
  const route = useRoute()
  const auth = useAuthStore()

  const email = ref('')
  const password = ref('')
  const rememberMe = ref(false)
  const error = ref(null)
  const loading = ref(false)

  const demoAccounts = [
    { role: 'Super Admin', email: 'superadmin@hospital.com', icon: 'ti-shield-lock' },
    { role: 'Clinic Admin', email: 'admin@hospital.com', icon: 'ti-settings' },
    { role: 'HR Manager', email: 'hr@hospital.com', icon: 'ti-users' },
    { role: 'Doctor', email: 'doctor@hospital.com', icon: 'ti-stethoscope' },
    { role: 'Nurse', email: 'nurse@hospital.com', icon: 'ti-heartbeat' },
    { role: 'Accountant', email: 'accountant@hospital.com', icon: 'ti-report-money' },
    { role: 'Pharmacist', email: 'pharmacist@hospital.com', icon: 'ti-pill' },
    { role: 'Receptionist', email: 'receptionist@hospital.com', icon: 'ti-headset' },
    { role: 'Lab', email: 'lab@hospital.com', icon: 'ti-flask' }
  ]

  const fillDemo = (demoEmail) => {
    email.value = demoEmail
    password.value = 'password'
  }

  const sideStyle = {
    backgroundImage: 'linear-gradient(180deg, #2f3ec9 0%, #2a35b8 70%, #222c9a 100%)',
    backgroundSize: 'cover',
    backgroundPosition: 'center',
  }

  const togglePassword = () => {
    showPassword.value = !showPassword.value
  }

  const setReasonError = () => {
    if (route.query.reason === 'forbidden') {
      error.value = 'Access denied: your account lacks HRM permission.'
    }
  }
  onMounted(setReasonError)
  watch(() => route.query.reason, setReasonError)

  const handleSubmit = async () => {
    error.value = null
    loading.value = true
    try {
      await auth.login({ email: email.value, password: password.value, remember: rememberMe.value })
      console.info('[Login] Success, fetching dashboard...')
      router.replace('/')
    } catch (e) {
      console.error('[Login] Failed', e)
      error.value = auth.error || (e.response?.data?.message) || 'Login failed'
    } finally {
      loading.value = false
    }
  }
  </script>

  <style scoped>
  .auth-side {
    background: linear-gradient(180deg, #5867f1 0%, #2c39c7 70%, #2e39b8 100%);
    position: relative;
  }
  .auth-side:before{
    content:'';
    position:absolute;
    top:60px;
    left:60px;
    width:20px;
    height:20px;
    background:#ff7a00;
    border-radius:50%;
    box-shadow:0 0 40px rgba(255,122,0,0.6);
    pointer-events: none;
    z-index: 1;
  }
  .auth-side:after{
    content:'';
    position:absolute;
    bottom:80px;
    left:120px;
    width:420px;
    height:420px;
    border-radius:50%;
    box-shadow:0 0 0 80px rgba(255,255,255,0.06), 0 0 0 160px rgba(255,255,255,0.04);
    background: transparent;
    pointer-events: none;
    z-index: 1;
  }
  .auth-overlay {
    background: rgba(0, 0, 0, 0.25);
    backdrop-filter: blur(1px);
    pointer-events: none;
    z-index: 1;
  }
  .auth-pattern {
    pointer-events: none;
    z-index: 1;
  }
  .auth-hero {
    position: relative;
    z-index: 10;
  }
  .glass {
    background: rgba(255,255,255,0.08);
    box-shadow: inset 0 0 0 1px rgba(255,255,255,0.12);
    backdrop-filter: blur(4px);
    position: relative;
    z-index: 20;
  }
  .border-white-10 {
    border-color: rgba(255,255,255,0.1) !important;
  }
  .card-left {
    background: rgba(255, 255, 255, 0.885);
    transition: all 0.2s ease;
    color: #222;
    font-weight: 500;
    font-size: 14px;
  }
  .card-left:hover {
    background: #fff;
    /* transform: translateY(-2px); */
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
  }
  button{
     cursor: pointer;
  }
  .demo-btn {
    transition: all 0.2s ease-in-out;
    background: rgba(255, 255, 255, 0.05);
  }
  .demo-btn:hover {
    background: rgba(255, 255, 255, 0.15);
    transform: translateY(-2px);
    border-color: #fff;
  }
  .social-btn {
    width: 44px;
    height: 44px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 18px;
  }
  @media (prefers-color-scheme: dark) {
    .auth-overlay {
      background: rgba(0, 0, 0, 0.35);
    }
  }
  </style>
