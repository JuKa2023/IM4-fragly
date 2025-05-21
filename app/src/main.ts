import { createApp } from 'vue';
import './style.css';
import App from './App.vue';
import { createRouter, createWebHistory } from "vue-router";

import HomePage from "./components/HomePage.vue";
import Register from "./components/Register.vue";
import LoginPage from "./components/LoginPage.vue";
import MeineGruppenPage from "./components/MeineGruppenPage.vue";
import UpdateUserdata from "./components/UpdateUserdata.vue";
import LandingPage from "./components/LandingPage.vue";
import GruppeErstellen from "./components/GruppeErstellenPage.vue";
import Steckbrief from "./components/Steckbrief.vue";
import Fragebogen from "./components/Fragebogen.vue";

const router = createRouter({
    history: createWebHistory(),
    routes: [
        { path: '/', component: LandingPage, name: 'landing' },
        { path: "/home", component: HomePage, meta: { requiresAuth: true } },
        { path: "/registrieren", component: Register },
        { path: "/gruppen", component: MeineGruppenPage, meta: { requiresAuth: true } },
        { path: "/anmelden", component: LoginPage },
        { path: "/benutzerdatenbearbeiten", component: UpdateUserdata, meta: { requiresAuth: true } },
        { path: "/gruppeerstellen", component: GruppeErstellen, meta: { requiresAuth: true } },
        { path: "/meinsteckbrief", component: Steckbrief, meta: { requiresAuth: true } },
        { path: "/fragebogen", component: Fragebogen, meta: { requiresAuth: true } },

    ],
});

// 🔐 Navigation Guard
router.beforeEach(async (to, from, next) => {
    if (to.meta.requiresAuth) {
        try {
            const res = await fetch('/api/session_check.php');
            if (res.ok) {
                next(); // user is logged in
            } else {
                next('/'); // or wherever your login/landing page is
            }
        } catch (error) {
            console.error('Session check failed:', error);
            next('/anmelden');
        }
    } else {
        next(); // No auth required
    }
});

createApp(App).use(router).mount('#app');