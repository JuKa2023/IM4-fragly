<script setup lang="ts">
import { ref } from 'vue'
import BaseInput from './BaseInput.vue'

const username = ref('')
const email = ref('')
const password = ref('')
const confirmPassword = ref('')

const updateUserData = () => {
  // Add your update logic here
  console.log('Form submitted:', {
    username: username.value,
    email: email.value,
    password: password.value,
    confirmPassword: confirmPassword.value
  })
}

const cancelUpdate = () => {
  // Reset form or navigate away
  username.value = ''
  email.value = ''
  password.value = ''
  confirmPassword.value = ''
}
</script>

<template>
    <div class="min-h-screen flex items-center justify-center">
      <div class="p-8 rounded-2xl w-full max-w-md">
        <h1 class="font-bold text-[#472402] mb-6">Nutzerdaten bearbeiten</h1>
  
        <form @submit.prevent="updateUserData">
          <BaseInput
            label="Benutzername bearbeiten"
            v-model="username"
            id="username"
            type="text"
            placeholder="Aktueller Nutzername"
          />
  
          <BaseInput
            label="change the Email"
            v-model="email"
            id="email"
            type="email"
            placeholder="akktuelle email"
          />
  
          <BaseInput
            label="Neues Passwort"
            v-model="password"
            id="password"
            type="password"
            placeholder="Akktuelles passwort"
          />
  
          <BaseInput
            label="Passwort erneut eingeben"
            v-model="confirmPassword"
            id="confirm-password"
            type="password"
            placeholder="Gib dein Passwort erneut ein"
          />
  
          <div class="flex gap-4 mt-6">
            <button type="submit" class="btn btn-lg btn-primary flex-1">
              Speichern
            </button>
            <button type="button" @click="cancelUpdate" class="btn btn-lg btn-secondary flex-1">
              Abbrechen
            </button>
          </div>
        </form>
      </div>
    </div>
  </template>
  