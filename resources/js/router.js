import { createRouter, createWebHistory } from 'vue-router'
import login from './components/pages/auth/login.vue'
import register from './components/pages/auth/register.vue'
import home from './components/pages/home.vue'
import categoriesAdmin from './components/pages/admin/categories.vue'

const routes = [
    {
        path: '/login',
        component: login,
    },
    {
        path: '/register',
        component: register,
    },
    {
        path: '/',
        component: home,
    },
    {
        path: '/admin/categories',
        component: categoriesAdmin,
    },
]

const router = createRouter({
    history: createWebHistory(),
    routes
})

export default router