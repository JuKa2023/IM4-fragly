import { createApp } from 'vue'
import './style.css'
import App from './App.vue'
import { createRouter, createWebHistory } from "vue-router";
import HomePage from "./components/HomePage.vue";
import Register from "./components/Register.vue";



const router = createRouter({
  history: createWebHistory(),
  routes: [
      { path: "/homepage", component: HomePage },
      { path: "/registrieren", component: Register },
  ],
});


createApp(App).use(router).mount('#app')
