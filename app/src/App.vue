<script setup lang="ts">
import { ref, onMounted } from 'vue'
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
            class="p-2 h-full w-auto cursor-pointer iconHeader"
          >
            <!-- Replace this with your own SVG if needed -->
              <img :src="gruppeicon" alt="Nutzer" class=" cursor-pointer iconHeader" />
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
    <main>
      <router-view>
        
      </router-view>
    </main>


<footer class="bg-orange-300 h-16 w-full px-6 shadow-inner">
  <div class="mx-auto max-w-xl h-full flex items-center justify-center">
  <p class="text-brown text-sm">
   © 2025 Fragly
  </p>
  </div>
</footer>
</template>


<style scoped>
.iconHeader {
	transition: transform 0.6s ease;
	transform-origin: center;
}
</style>
