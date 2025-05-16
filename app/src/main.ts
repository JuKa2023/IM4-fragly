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
      { path: "/gruppen", component: MeineGruppenPage },
      { path: "/login", component: LoginPage },
      { path: "/benutzerdatenbearbeiten", component: UpdateUserdata },
  ],
});


createApp(App).use(router).mount('#app')
