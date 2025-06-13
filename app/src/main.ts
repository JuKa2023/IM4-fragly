import {createApp} from "vue";
import "./style.css";
import App from "./App.vue";
import {createRouter, createWebHistory} from "vue-router";

import HomePage from "./components/HomePage.vue";
import Register from "./components/Register.vue";
import LoginPage from "./components/LoginPage.vue";
import MeineGruppenPage from "./components/MeineGruppenPage.vue";
import UpdateUserdata from "./components/Profile.vue";
import LandingPage from "./components/LandingPage.vue";
import GruppeErstellen from "./components/GruppeErstellenPage.vue";
import Steckbrief from "./components/Steckbrief.vue";
import Fragebogen from "./components/Fragebogen.vue";
import GruppenMitgliederPage from "./components/GruppenMitgliederPage.vue";
import GruppeVerlassenPage from "./components/GruppeVerlassenPage.vue";
import GruppeBeitretenManuel from "./components/GruppeBeitretenPage.vue";

const router = createRouter({
    history: createWebHistory(),
    routes: [
        {
            path: "/",
            component: LandingPage,
            name: "landing"
        },
        {
            path: "/home",
            name: "home",
            component: HomePage,
            meta: {requiresAuth: true}
        },
        {
            path: "/registrieren",
            name: "register",
            component: Register
        },
        {
            path: "/gruppen",
            component: MeineGruppenPage,
            name: "groups",
            meta: {requiresAuth: true},
        },
        {
            path: "/anmelden",
            name: "login",
            component: LoginPage
        },
        {
            path: "/ich",
            name: "ich",
            component: UpdateUserdata,
            meta: {requiresAuth: true},
        },
        {
            path: "/gruppen/erstellen",
            name: "createGroup",
            component: GruppeErstellen,
            meta: {requiresAuth: true},
        },
        {
            path: "/steckbrief/ich",
            component: Steckbrief,
            name: "steckbriefMe",
            meta: {requiresAuth: true},
        },
        {
            path: "/steckbrief/:id",
            name: "steckbrief",
            component: Steckbrief,
            props: true,
            meta: {requiresAuth: true},
        },
        {
            path: "/fragebogen",
            name: "fragebogen",
            component: Fragebogen,
            meta: {requiresAuth: true},
        },
        {
            path: "/gruppen/:id/mitglieder",
            name: "GruppenMitglieder",
            component: GruppenMitgliederPage,
            props: true,
            meta: {requiresAuth: true},
        },
        {
            path: "/gruppe/:id/verlassen",
            name: "GruppeVerlassen",
            component: GruppeVerlassenPage,
            props: true,
            meta: {requiresAuth: true},
        },

        {
            path: "/beitreten/:code",
            name: "InviteJoin",
            component: GruppeBeitretenManuel,
            meta: {requiresAuth: true}
        },

        {
            path: "/gruppen/beitreten",
            name: "joinGroupManual",
            component: GruppeBeitretenManuel,
            meta: {requiresAuth: true}
        },

    ],
});

router.beforeEach(async (to, from, next) => {
    if (to.meta.requiresAuth) {
        try {
            const res = await fetch("/api/is_logged_in.php");
            if (res.ok) {
                localStorage.setItem("check", "true");
                next();
            } else {
                next("/");
                localStorage.removeItem("check");
            }
        } catch (error) {
            console.error("Session check failed:", error);
            next("/");
        }
    } else {
        next();
    }
});

createApp(App).use(router).mount("#app");
