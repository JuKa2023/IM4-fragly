<template>
  <div>
    <button class="absolute top-4 right-4 w-8 h-8 rounded-full btn btn-sm btn-primary flex items-center justify-center">
      ×
    </button>

    <div class="bg-[#ffebd2] min-h-screen p-6 flex flex-col justify-between rounded-3xl w-max-md m-2">
      <!-- Title -->
      <h2 class="text-2xl font-bold text-brown mb-6">Deine Gruppen</h2>

      <!-- Group List OR Empty Message -->
      <div class="space-y-3 flex-1">
        <div v-if="noGroups" class="bg-[#FFEFF6] text-brown p-4 rounded-md shadow-sm">
          <p class="text-lg">Du bist noch in keiner Gruppe. Möchtest du einer Gruppe beitreten?</p>
        </div>

        <div
            v-else
            v-for="group in groups"
            :key="group.Gruppe_Name"
            class="bg-[#FFEFF6] text-brown flex items-center px-4 py-2 rounded-md shadow-sm cursor-pointer"
        >
          <UserGroupIcon class="w-6 h-6 text-[#7CA4A0] mr-3" />
          <span class="text-xl">{{ group.Gruppe_Name }}</span>
        </div>
      </div>

      <!-- Bottom Button -->
      <div class="mt-8 text-center">
        <RouterLink class="btn btn-sm btn-primary" to="/gruppebeitreten">
          Gruppe beitreten
        </RouterLink>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue';
import { UserGroupIcon } from '@heroicons/vue/24/solid';

type Group = {
  Gruppe_Name: string;
};

const groups = ref<Group[]>([]);
const noGroups = ref(false); // <- this controls whether to show the "no group" message

onMounted(async () => {
  try {
    const res = await fetch('api/get_user_groups.php', {
      credentials: 'include',
    });

    if (res.status === 204) {
      noGroups.value = true;
      return;
    }

    if (res.status === 200) {
      const data = await res.json();
      if (data.status === 'success') {
        groups.value = data.groups;
      } else {
        console.error('Fehler beim Laden der Gruppen:', data.message);
      }
    } else if (res.status === 401) {
      console.warn('Nicht eingeloggt. Weiterleitung zur Anmeldung empfohlen.');
      // Optional: router.push('/anmelden')
    } else {
      console.error('Unbekannter Fehler:', res.status);
    }
  } catch (error) {
    console.error('Verbindungsfehler:', error);
  }
});
</script>

<style scoped>
/* Optional styling */
</style>