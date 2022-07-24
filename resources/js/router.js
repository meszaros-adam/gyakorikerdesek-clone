import { createRouter, createWebHistory } from 'vue-router'
import login from './components/pages/auth/login.vue'
import register from './components/pages/auth/register.vue'

const routes = [
    {
        path: '/login',
        component: login,
    },
    {
        path: '/register',
        component: register,
    },
]

const router = createRouter({
    history: createWebHistory(),
    routes
})

export default router