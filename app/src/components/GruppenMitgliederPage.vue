<script setup lang="ts">
import { ref, onMounted } from "vue";
import { useRoute, useRouter } from "vue-router";
import ProfilePicture from "./ProfilePicture.vue";
import GroupLinkDisplay from "./GroupLinkDisplay.vue";
import { toast } from "vue-sonner";

const route = useRoute();
const router = useRouter();
const groupId = Number(route.params.id);

const members = ref<[]>([]);
const loading = ref(true);
const error = ref<string | null>(null);
const gruppeName = ref<string>("");
const gruppeLink = ref<string>("");
const kuerzel = ref<string>("");

function copyToClipboard(text: string, type: "link" | "kuerzel") {
  navigator.clipboard.writeText(text).then(() => {
    if (type === "link") {
      toast.success("Link kopiert!");
    } else if (type === "kuerzel") {
      toast.success("Kürzel kopiert!");
    }
  });
}

onMounted(async () => {
  try {
    const res = await fetch("/api/get_group_members.php", {
      method: "POST",
      credentials: "include",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({ gruppe_id: groupId }),
    });

    if (res.status === 401) {
      await router.push("/login");
      return;
    }

    const data = await res.json();
    if (res.status !== 200) {
      error.value = data || "Fehler beim Laden der Mitglieder.";
    } else {
      members.value = data;
      gruppeName.value = data[0].Gruppe_Name;
      kuerzel.value = data[0].Kuerzel;
      gruppeLink.value = `${window.location.origin}/${data[0].Kuerzel}`;
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

    <!-- Group Link and Kürzel -->
    

    <div v-if="loading" class="text-center text-brown">Lade Mitglieder…</div>
    <div v-else-if="error" class="text-center text-red-500">{{ error }}</div>

    <div v-else class="space-y-3">
      <div
        v-for="m in members"
        :key="m.User_ID"
        class="bg-[#FFEFF6] text-brown flex items-center px-4 py-2 rounded-md shadow-sm"
      >
        <RouterLink :to="{ name: 'steckbrief', params: { id: m.User_ID } }" class="flex items-center">
          <ProfilePicture :userId="m.User_ID" :initial-url="m.Profilbild_URL" class="w-10 h-10 mr-3" />
          <span class="text-xl">{{ m.Nutzer }}</span>
        </RouterLink>
      </div>
    </div>

    <div class="mb-6">
      <GroupLinkDisplay :gruppe-link="gruppeLink" :kuerzel="kuerzel" />
    </div>
    
    <RouterLink :to="{ name: 'GruppeVerlassen', params: { id: groupId } }">
      <button class="btn btn-sm btn-primary mt-8"> Gruppe verlassen </button>
    </RouterLink>
  </div>
</template>

<style scoped>
/* Bei Bedarf noch Fein‐Tuning */
</style>
