<script setup lang="ts">
import { ref, onMounted } from 'vue'
import Login from './components/Register.vue'
import LandingPage from './components/LandingPage.vue'
import HomePage from './components/HomePage.vue'
import MeineGruppen from './components/MeineGruppen.vue'  

import logo from './assets/logo.svg'
import gruppeicon from './assets/gruppeicon.svg'

interface User {
  id: number;
  name: string;
  email: string;
}

const users = ref<User[]>([]);

onMounted(async () => {
  try {
    const response = await fetch("/api?resource=users");
    users.value = await response.json();
  } catch (error) {
    console.error('Error fetching users:', error);
  }
});

</script>

<template>
  <header class="bg-orange-300 h-16 w-full px-6 shadow-md">
    <div class="mx-auto max-w-xl h-full flex items-center">
      <nav class="flex items-center justify-between w-full h-full">
        <a href="#" class="p-2 cursor-pointer iconHeader w-auto h-full">
          <img :src="logo" alt="Logo" class="h-full"/>
        </a>
    
        <!-- Right User Dropdown -->
        <div class="relative">
          <div
            @click="toggleDropdown"
            class="p-2 cursor-pointer iconHeader"
          >
            <!-- Replace this with your own SVG if needed -->
              <img :src="gruppeicon" alt="Nutzer" class="p-2 cursor-pointer iconHeader" />
          </div>
    
          <div
            v-if="dropdownOpen"
            class="absolute right-0 mt-2 w-56 bg-orange-300 shadow-lg z-20"
          >
            <a
              href="#"
              class="block px-4 py-2 text-gray-800 hover:bg-gray-100 transition-colors"
            >
              Benutzerdaten bearbeiten
            </a>
            <a
              href="#"
              class="block px-4 py-2 text-gray-800 hover:bg-gray-100 transition-colors"
            >
              Logout
            </a>
            </div>
          </div>
      </nav>
    </div>
  </header>

    <Login />

    <UpdateUsernmae />

    <LandingPage />

    <HomePage />

    <MeineGruppen />

    <main>
      <router-view></router-view>
    </main>
  
  <div class="users">
    <h2>Users</h2>
    <ul>
      <li v-for="user in users" :key="user.id">
        {{ user.name }} ({{ user.email }})
      </li>
    </ul>
  </div>
</template>

<style scoped>

.users {
  margin-top: 2em;
  padding: 1em;
}

.users ul {
  list-style: none;
  padding: 0;
}

.users li {
  padding: 0.5em;
  margin: 0.5em 0;
  background: #f5f5f5;
  border-radius: 4px;
}

.iconHeader {
	padding-bottom: 10px;
	transition: transform 0.6s ease;
	transform-origin: center;
}
</style>
