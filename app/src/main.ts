import { createApp } from 'vue'
import './style.css'
import App from './App.vue'
import { createRouter, createWebHistory } from "vue-router";
import HomePage from "./components/HomePage.vue";
import Register from "./components/Register.vue";
import MeineGruppenPage from "./components/MeineGruppenPage.vue";



const router = createRouter({
  history: createWebHistory(),
  routes: [
      { path: "/homepage", component: HomePage },
      { path: "/registrieren", component: Register },
      { path: "/gruppen", component: MeineGruppenPage },
  ],
});


createApp(App).use(router).mount('#app')
