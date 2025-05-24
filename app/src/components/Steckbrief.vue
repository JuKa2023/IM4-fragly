<template>
    <div class="p-8 max-w-2xl mx-auto bg-cream-light rounded-xl shadow-md text-brown">
      <h1 class="text-3xl font-bold mb-6">Steckbrief</h1>
  
      <!-- Loader -->
      <p v-if="!loaded" class="text-center">Lade …</p>
  
      <!-- Keine Antworten -->
      <p v-else-if="fields.length === 0" class="text-center">
        Du hast noch keine Fragen beantwortet.
      </p>
  
      <!-- Antworten-Liste -->
      <div v-else class="text-left">
        <div
          v-for="f in fields"
          :key="f.frage_id"
          class="mb-6 last:mb-0"
        >
          <h2 class="font-semibold mb-1">{{ f.frage }}</h2>
          <p>{{ f.antwort }}</p>
        </div>
      </div>
      <RouterLink to="/fragebogen" class="btn btn-sm btn-primary mt-8">Bearbeiten</RouterLink>

    </div>
  </template>
  
  <script setup lang="ts">
  import { ref, onMounted } from 'vue';
  
  interface FrageRow {
    frage_id: number;
    frage: string;
    antwort: string | null;
  }
  
  const fields = ref<FrageRow[]>([]);
  const loaded = ref(false);
  
  onMounted(async () => {
    try {
      const res = await fetch('/api/get_questions.php', {
        credentials: 'include'
      });
      const data: FrageRow[] = await res.json();
  
      // Nur wirklich beantwortete Fragen anzeigen
      fields.value = data.filter(q =>
        q.antwort !== null && q.antwort.trim() !== ''
      );
    } catch (e) {
      console.error('Fehler beim Laden der Fragen:', e);
    } finally {
      loaded.value = true;
    }
  });
  </script>
  
  <style scoped>
  .bg-cream-light { background-color: #FFEBD2; }
  .text-brown     { color: #472402; }
  </style>