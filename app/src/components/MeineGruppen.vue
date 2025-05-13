<template>
        <button class="absolute top-4 right-4 w-8 h-8 rounded-full btn btn-sm btn-primary flex items-center justify-center">
        ×
      </button>
    <div class="bg-[#ffebd2] min-h-screen p-6 flex flex-col justify-between rounded-3xl w-md m-2">  
      <!-- Titel -->
      <h2 class="text-2xl font-bold text-brown mb-6">Deine Gruppen</h2>
  
      <!-- Gruppenliste -->
      <div class="space-y-3 flex-1">
        <div v-for="group in groups" :key="group" class="bg-[#FFEFF6] text-brown flex items-center px-4 py-2 rounded-md shadow-sm">
          <UserGroupIcon class="w-6 h-6 text-[#7CA4A0] mr-3" />
          <span class="text-xl">{{ group }}</span>
        </div>
      </div>
  
      <!-- Button unten -->
      <div class="mt-8 text-center">
        <button class="btn btn-sm btn-primary">Gruppe beitreten</button>
      </div>
    </div>
  </template>
  
  <script setup>
  import { UserGroupIcon } from '@heroicons/vue/24/solid'
  
  const groups = [
    'Familie Mustermann',
    'Fluri Holz AG',
    'Hochzeit Tina & Alex',
    'Weihnachten bei Meiers',
  ]
  </script>
  
  <style scoped>
  </style>