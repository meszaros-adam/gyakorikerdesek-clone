import { createRouter, createWebHistory } from 'vue-router'
import login from './components/pages/auth/login.vue'
import register from './components/pages/auth/register.vue'
import lastFiveQuestions from './components/pages/lastFiveQuestions.vue'
import questionSingle from './components/pages/questionSingle.vue'
import messages from './components/pages/messages.vue'
import myQuestions from './components/pages/myQuestions.vue'
import myAnsweredQuestions from './components/pages/myAnsweredQuestions.vue'
import questionsByCategory from './components/pages/questionsByCategory.vue'
import questionsByTag from './components/pages/questionsByTag.vue'

//Admin
import categoriesAdmin from './components/pages/admin/categoriesAdmin.vue'
import questionsAdmin from './components/pages/admin/questionsAdmin.vue'
import tagsAdmin from './components/pages/admin/tagsAdmin.vue'
import usersAdmin from './components/pages/admin/usersAdmin.vue'

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
    {
        path: '/my-answered-questions',
        name: 'my--answered-questions',
        component: myAnsweredQuestions,
    },
    {
        path: '/category/:id/:title',
        name: 'category',
        component: questionsByCategory,
    },
    {
        path: '/tag/:id/:title',
        name: 'tag',
        component: questionsByTag,
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
    {
        path: '/admin/tags',
        component: tagsAdmin,
    },
    {
        path: '/admin/users',
        component: usersAdmin,
    },
]

const router = createRouter({
    history: createWebHistory(),
    routes,
})

export default router