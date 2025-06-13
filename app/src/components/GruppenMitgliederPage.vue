<script setup lang="ts">
import { ref, onMounted } from "vue";
import { useRoute, useRouter } from "vue-router";
import ProfilePicture from "./ProfilePicture.vue";
import GroupLinkDisplay from "./GroupLinkDisplay.vue";
import CloseButton from "./CloseButton.vue";
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
      gruppeName.value = data[0].name;
      kuerzel.value = data[0].kuerzel;
    }
  } finally {
    loading.value = false;
  }
});
</script>

<template>
  <div class="card-default relative">

    <div class="flex items-baseline justify-between mb-6">
      <h1>{{ gruppeName }}</h1>
      <CloseButton class="btn-close" />
    </div>    

    <div v-if="loading" class="text-center text-brown">Lade Mitglieder…</div>
    <div v-else-if="error" class="text-center text-red-500">{{ error }}</div>

    <div v-else class="space-y-6">
      <RouterLink
        v-for="m in members"
        :key="m.user_id"
        :to="{ name: 'steckbrief', params: { id: m.user_id } }"
        class="bg-[#FFEFF6] flex items-center px-4 py-2 rounded-md shadow-md cursor-pointer hover:shadow-none transition duration-200 ease-in-out"      >
          <ProfilePicture :userId="m.user_id" :initial-url="m.avatar_url" />
          <span class="text-xl text-[#472402]">{{ m.nutzer }}</span>
      </RouterLink>
    </div>

    <div class="mb-6">
      <GroupLinkDisplay :kuerzel="kuerzel" />
    </div>

    <RouterLink :to="{ name: 'GruppeVerlassen', params: { id: groupId } }">
      <button class="btn btn-sm btn-primary mt-8"> Gruppe verlassen </button>
    </RouterLink>
  </div>
</template>
