<script setup lang="ts">
import { ref } from 'vue'
import BaseInput from './BaseInput.vue'

const username = ref('')
const password = ref('')

const errorMessage = ('')

const login = () => {
  console.log('Login attempt:', {
    username: username.value,
    password: password.value,
  })
}
</script>

<template>
    <div class="min-h-screen flex items-center justify-center w-md">
      <div class="p-8 w-full">
        <h1 class=" font-bold text-[#472402] mb-6">Melde dich an</h1>
  
        <form @submit.prevent="login">
          <BaseInput
            label="Benutzername eingeben"
            v-model="username"
            id="username"
            type="text"
            placeholder="JuKa365"
          />
  
          <BaseInput
            label="Passwort eingeben"
            v-model="password"
            id="password"
            type="password"
            placeholder="*****"
          />
  
          <button type="submit" class="btn btn-lg btn-primary">
            Anmelden
          </button>
        </form>
      </div>
    </div>
  </template>
  