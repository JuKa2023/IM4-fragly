import { createApp } from 'vue'
import './style.css'
import App from './App.vue'
import { createRouter, createWebHistory } from "vue-router";
import HomePage from "./components/HomePage.vue";
import Register from "./components/Register.vue";
import MeineGruppenPage from "./components/MeineGruppenPage.vue";
import LoginPage from "./components/LoginPage.vue";



const router = createRouter({
  history: createWebHistory(),
  routes: [
      { path: "/homepage", component: HomePage },
      { path: "/registrieren", component: Register },
      { path: "/gruppen", component: MeineGruppenPage },
      { path: "/login", component: LoginPage },
  ],
});


createApp(App).use(router).mount('#app')
