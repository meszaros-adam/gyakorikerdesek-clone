import { createRouter, createWebHistory } from 'vue-router'
import login from './components/pages/auth/login.vue'
import register from './components/pages/auth/register.vue'
import lastFiveQuestions from './components/pages/lastFiveQuestions.vue'
import categoriesAdmin from './components/pages/admin/categoriesAdmin.vue'
import questionsAdmin from './components/pages/admin/questionsAdmin.vue'
import questionSingle from './components/pages/questionSingle.vue'
import messages from './components/pages/messages.vue'
import myQuestions from './components/pages/myQuestions.vue'

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
        component: lastFiveQuestions,
    },
    {
        path: '/question/:id',
        name: 'question',
        component: questionSingle,
    },
    {
        path: '/question/:id',
        name: 'question',
        component: questionSingle,
    },
    {
        path: '/messages',
        name: 'messages',
        component: messages,
    },
    {
        path: '/my-questions',
        name: 'my-questions',
        component: myQuestions,
    },
    //Admin
    {
        path: '/admin/categories',
        component: categoriesAdmin,
    },
    {
        path: '/admin/questions',
        component: questionsAdmin,
    },
]

const router = createRouter({
    history: createWebHistory(),
    routes
})

export default router