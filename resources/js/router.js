import { createRouter, createWebHistory } from 'vue-router'
import login from './/components/pages/auth/login.vue'

const routes = [
    {
        path:'/login',
        componet: login,
    }
]

const router = createRouter({
    history: createWebHistory(),
    routes
  })
  export default router