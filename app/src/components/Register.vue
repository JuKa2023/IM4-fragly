<script setup lang="ts">
import { ref } from 'vue'
import BaseInput from './BaseInput.vue'

const username = ref('')
const email = ref('')
const password = ref('')
const confirmPassword = ref('')

const login = () => {
  console.log('Login attempt:', {
    username: username.value,
    email: email.value,
    password: password.value,
    confirmPassword: confirmPassword.value
  })
}
</script>

<template>
    <div class="min-h-screen flex items-center justify-center">
      <div class="p-8 w-full max-w-md">
        <h1 class=" font-bold text-[#472402] mb-6">Registrieren</h1>
  
        <form @submit.prevent="login">
          <BaseInput
            label="Benutzername"
            v-model="username"
            id="username"
            type="text"
            placeholder="Gib deinen Benutzernamen ein"
          />
  
          <BaseInput
            label="Email"
            v-model="email"
            id="email"
            type="email"
            placeholder="Gib deine Email ein"
          />
  
          <BaseInput
            label="Passwort wählen"
            v-model="password"
            id="password"
            type="password"
            placeholder="Gib dein Passwort ein"
          />
  
          <BaseInput
            label="Passwort erneut eingeben"
            v-model="confirmPassword"
            id="confirm-password"
            type="password"
            placeholder="Gib dein Passwort erneut ein"
          />
  
          <button type="submit" class="btn btn-lg btn-primary">
            Konto erstellen
          </button>
        </form>
      </div>
    </div>
  </template>
  