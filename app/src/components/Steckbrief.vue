<script setup lang="ts">
import { ref, onMounted } from "vue";
import { useRoute } from "vue-router";
import ProfilePicture from "./ProfilePicture.vue";

interface FrageRow {
  frage_id: number;
  frage: string;
  antwort: string | null;
}

const fields = ref<FrageRow[]>([]);
const loaded = ref(false);
const user = ref(null);

const route = useRoute();
const userId = route.params.id;

onMounted(async () => {
  try {
    const res = await fetch("/api/get_questions.php", {
      credentials: "include",
      method: "POST",
      body: JSON.stringify({ user_id: userId }),
    });
    const data = await res.json();
    fields.value = data.filter((q: FrageRow) => q.antwort !== null);
  } catch (e) {
    console.error("Fehler beim Laden der Fragen:", e);
  } finally {
    loaded.value = true;
  }


  const resUser = await fetch("/api/nutzer.php", {
    credentials: "include",
    method: "POST",
    body: JSON.stringify({ user_id: userId }),
  });
  user.value = await resUser.json();

});
</script>

<template>
  <div
    class="p-8 max-w-2xl mx-auto bg-cream-light rounded-xl shadow-md text-brown"
  >
    <h1 class="text-3xl font-bold mb-6">Steckbrief</h1>

    <ProfilePicture
      :initial-url="user?.Profilbild_URL"
      :editable="false"
    />

    <!-- Loader -->
    <p v-if="!loaded" class="text-center">Lade …</p>

    <!-- Keine Antworten -->
    <p v-else-if="fields.length === 0" class="text-center">
      Es wurden noch keine Fragen beantwortet.
    </p>

    <!-- Antworten-Liste -->
    <div v-else class="text-left">
      <div v-for="f in fields" :key="f.frage_id" class="mb-6 last:mb-0">
        <h2 class="font-semibold mb-1">{{ f.frage }}</h2>
        <p>{{ f.antwort }}</p>
      </div>
    </div>
    <RouterLink
      :to="{ name: 'fragebogen'}"
      class="btn btn-sm btn-primary mt-8"
      v-if="!userId">
        Bearbeiten
    </RouterLink>
  </div>
</template>

<style scoped>
.bg-cream-light {
  background-color: #ffebd2;
}
.text-brown {
  color: #472402;
}
</style>
