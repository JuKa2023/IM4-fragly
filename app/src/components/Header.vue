<template>
    <header class="bg-orange-300 h-16 flex items-center justify-between px-6 shadow-md relative">
      <a href="#">
        <img alt="Home" src="" />
      </a>
  
      <!-- Right User Dropdown -->
      <div class="relative">
        <div
          @click="toggleDropdown"
          class="rounded-full p-2 cursor-pointer"
        >
          <!-- Replace this with your own SVG if needed -->
            <img alt="Nutzer" src="">
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
    </header>
  </template>
  
  <script setup>
  import { ref } from 'vue'
  
  const dropdownOpen = ref(false)
  
  const toggleDropdown = () => {
    dropdownOpen.value = !dropdownOpen.value
  }
  </script>