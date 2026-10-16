import { createApp } from 'vue'
import App from './App.vue'
import router from './router'

// ติดตั้ง Bootstrap ด้วย npm แล้ว import เข้ามาที่นี่ (ไม่ใช้ CDN)
import 'bootstrap/dist/css/bootstrap.min.css'
import 'bootstrap/dist/js/bootstrap.bundle.min.js'

createApp(App).use(router).mount('#app')
