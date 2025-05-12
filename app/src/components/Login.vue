<template>
    <div class="min-h-screen flex items-center justify-center">
      <div class="bg-white p-8 rounded-2xl shadow-md w-full max-w-md">
        <h2 class="text-2xl font-bold text-[#472402] mb-6">Registrieren</h2>
  
        <form @submit.prevent="login">
          <div class="mb-4">
            <label class="block text-[#472402] font-medium mb-1" for="username">Benutzername</label>
            <input
              v-model="username"
              id="username"
              type="text"
              class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-orange-300"
              placeholder="Gib deinen Benutzernamen ein"
            />
          </div>
  
          <div class="mb-4">
            <label class="block text-[#472402] font-medium mb-1" for="email">Email</label>
            <input
              v-model="email"
              id="email"
              type="email"
              class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-orange-300"
              placeholder="Gib deine Email ein"
            />
          </div>
  
          <div class="mb-6">
            <label class="block text-[#472402] font-medium mb-1" for="password">Passwort</label>
            <input
              v-model="password"
              id="password"
              type="password"
              class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-orange-300"
              placeholder="Gib dein Passwort ein"
            />
          </div>
  
          <button type="submit" class="btn btn-lg btn-primary">
            Einloggen
          </button>
        </form>
      </div>
    </div>
  </template>
  
  <script setup lang="ts">
  import { ref } from 'vue'
  
  const username = ref('')
  const email = ref('')
  const password = ref('')
  
  const login = () => {
    console.log('Login attempt:', {
      username: username.value,
      email: email.value,
      password: password.value
    })
  }
  </script>