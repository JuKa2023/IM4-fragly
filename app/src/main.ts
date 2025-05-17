import { createApp } from 'vue';
import './style.css';
import App from './App.vue';
import { createRouter, createWebHistory } from "vue-router";

import HomePage from "./components/HomePage.vue";
import Register from "./components/Register.vue";
import LoginPage from "./components/LoginPage.vue";
import MeineGruppenPage from "./components/MeineGruppenPage.vue";
import UpdateUserdata from "./components/UpdateUserdata.vue";

const router = createRouter({
    history: createWebHistory(),
    routes: [
        { path: "/home", component: HomePage },
        { path: "/registrieren", component: Register },
        { path: "/gruppen", component: MeineGruppenPage, meta: { requiresAuth: true } },
        { path: "/login", component: LoginPage },
        { path: "/benutzerdatenbearbeiten", component: UpdateUserdata, meta: { requiresAuth: true } },
    ],
});

// 🔐 Navigation Guard
router.beforeEach(async (to, from, next) => {
    if (to.meta.requiresAuth) {
        try {
            const res = await fetch('/backend/session_check.php');
            if (res.ok) {
                next(); // User is logged in, allow access
            } else {
                next('/landing'); // Redirect to login
            }
        } catch (error) {
            console.error('Session check failed:', error);
            next('/login');
        }
    } else {
        next(); // No auth required
    }
});

createApp(App).use(router).mount('#app');