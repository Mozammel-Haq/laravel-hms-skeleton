<template>
  <div class="container py-5">
    <div class="row justify-content-center">
      <div class="col-md-8">
        <div class="card border-0 shadow-sm">
          <div class="card-body p-5 text-center">
            <div class="display-6 mb-3">Access Denied</div>
            <p class="text-muted mb-4">
              Your account does not have permission to view the HRM dashboard.
            </p>
            <button class="btn btn-primary" @click="goLogin">Back to Login</button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { useRouter } from 'vue-router'
import { useAuthStore } from '../store/authStore'

const router = useRouter()
const auth = useAuthStore()

const goLogin = async () => {
  try {
    await auth.logout()
  } finally {
    router.replace('/login')
  }
}
</script>
