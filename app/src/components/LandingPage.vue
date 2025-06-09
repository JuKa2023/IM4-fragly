<script setup lang="ts">

import {RouterLink, useRouter} from "vue-router";
import {onMounted} from "vue";

onMounted(() => {
  if (localStorage.getItem("check")) {
    useRouter().push({name: 'home'})
  }
});

</script>

<template>
  <div class="min-h-screen flex flex-col items-center justify-center">
    <div>
      <h1 class="text-4xl md:text-5xl font-normal text-[#472402]">
        Herzlich Willkommen bei
      </h1>
      <h1 class="text-5xl md:text-6xl font-bold text-[#537178]">Fragly</h1>
    </div>
    <div class="flex flex-col items-center gap-6 w-full max-w-xs">
      <RouterLink class="btn btn-lg btn-primary" to="/anmelden">
        Anmelden
      </RouterLink>
      <RouterLink to="/registrieren" class="btn btn-lg btn-secondary">
        Registrieren
      </RouterLink>
    </div>
  </div>
</template>

<style scoped>
/* Add any component-specific styles here */
</style>
