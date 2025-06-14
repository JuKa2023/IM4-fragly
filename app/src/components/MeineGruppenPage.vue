<script lang="ts" setup>
import {onMounted, ref,} from "vue";
import CloseButton from "./CloseButton.vue";
import {UserGroupIcon} from "@heroicons/vue/24/solid";

type Group = {
  name: string;
  gruppe_id: number;
  kuerzel: string;
};

const groups = ref<Group[]>([]);
const noGroups = ref(false);

onMounted(async () => {
  try {
    const res = await fetch("api/get_user_groups.php", {
      method: "POST",
      credentials: "include",
    });

    if (res.status === 204) {
      noGroups.value = true;
      return;
    }

    if (res.status === 200) {
      const data = await res.json();
      if (data.status === "success") {
        groups.value = data.groups;
      } else {
        console.error("Fehler beim Laden der Gruppen:", data.message);
      }
    } else if (res.status === 401) {
      console.warn("Nicht eingeloggt. Weiterleitung zur Anmeldung empfohlen.");
    }
  } catch (error) {
    console.error("Verbindungsfehler:", error);
  }
});
</script>

<template>
  <div class="card-default relative">
    <div class="flex items-baseline justify-between mb-6">
      <h1>Deine Gruppen</h1>
      <CloseButton :to="{ name: 'home' }" class="btn-close"/>
    </div>

    <div class="flex-1">
      <div v-if="noGroups" class="text-center text-brown p-6">
        <p>Du bist noch in keiner Gruppe.</p>
        <p>Möchtest du einer Gruppe beitreten?</p>
      </div>

      <div v-else class="space-y-6">
        <RouterLink
            v-for="group in groups"
            :key="group.gruppe_id"
            :to="{ name: 'GruppenMitglieder', params: { id: group.gruppe_id } }"
            class="bg-[#FFEFF6] flex items-center px-4 py-2 rounded-md shadow-md cursor-pointer hover:shadow-none transition duration-200 ease-in-out"
        >
          <UserGroupIcon class="w-6 h-6 text-[#7CA4A0] mr-3"/>
          <span class="text-xl text-[#472402]">{{ group.name }}</span>
        </RouterLink>
      </div>
    </div>

    <div class="mt-8 flex flex-col items-center gap-y-6 mt-8">
      <RouterLink :to="{ name: 'joinGroupManual' }" class="btn btn-sm btn-primary">
        Gruppe beitreten
      </RouterLink>
      <RouterLink :to="{ name: 'createGroup' }" class="btn btn-sm btn-secondary">
        Gruppe erstellen
      </RouterLink>
    </div>
  </div>
</template>