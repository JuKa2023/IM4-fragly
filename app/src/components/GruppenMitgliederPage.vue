<script setup lang="ts">
import { ref, onMounted } from "vue";
import { UserIcon } from "@heroicons/vue/24/solid";
import { useRoute, useRouter } from "vue-router";

const route = useRoute();
const router = useRouter();
const groupId = Number(route.params.id);

const members = ref<[]>([]);
const loading = ref(true);
const error = ref<string | null>(null);
const gruppeName = ref<string>("");

onMounted(async () => {
  try {
    const res = await fetch("/api/get_group_members.php", {
      method: "POST",
      credentials: "include",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({ gruppe_id: groupId }),
    });

    if (res.status === 401) {
      // nicht eingeloggt
      router.push("/login");
      return;
    }

    const data = await res.json();
    console.log(data);

    if (res.status !== 200) {
      error.value = data || "Fehler beim Laden der Mitglieder.";
    } else {
      members.value = data;
      gruppeName.value = data[0].Gruppe_Name;
    }
  } finally {
    loading.value = false;
  }
});
</script>

<template>
  <div class="relative bg-[#ffebd2] min-h-screen p-6 rounded-3xl w-max-md m-2">
    <button
      @click="$router.back()"
      class="absolute top-4 right-4 w-8 h-8 rounded-full btn btn-sm btn-primary flex items-center justify-center"
    >
      ×
    </button>

    <h2 class="text-2xl font-bold text-brown mb-6">{{ gruppeName }}</h2>

    <div v-if="loading" class="text-center text-brown">Lade Mitglieder…</div>
    <div v-else-if="error" class="text-center text-red-500">{{ error }}</div>

    <div v-else class="space-y-3">
      <div
        v-for="m in members"
        :key="m.User_ID"
        class="bg-[#FFEFF6] text-brown flex items-center px-4 py-2 rounded-md shadow-sm"
      >
        <UserIcon class="w-6 h-6 text-[#7CA4A0] mr-3" />
        <span class="text-xl">{{ m.Nutzer }}</span>
      </div>
    </div>
  </div>
</template>

<style scoped>
/* Bei Bedarf noch Fein‐Tuning */
</style>
