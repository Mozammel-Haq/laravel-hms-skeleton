import { createApp } from 'vue'
import { createPinia } from 'pinia'
import router from './router'
import App from './App.vue'

// Import Bootstrap JS
import 'bootstrap/dist/js/bootstrap.bundle.min.js'

// Import custom SCSS
import './assets/scss/main.scss'

const app = createApp(App)

app.use(createPinia())
app.use(router)

app.mount('#app')
