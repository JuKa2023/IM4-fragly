<template>
  <div class="p-8 max-w-2xl mx-auto bg-cream-light rounded-xl shadow-md text-brown">
    <h1 class="text-3xl font-bold mb-6">Dein Steckbrief</h1>

    <!-- Loader -->
    <p v-if="loading" class="text-center">Lade …</p>

    <!-- Keine Fragen definiert -->
    <p v-else-if="questions.length === 0" class="text-center">
      Keine Fragen vorhanden.
    </p>

    <!-- Editierbares Formular -->
    <form v-else @submit.prevent="onSave">
      <div
          v-for="q in questions"
          :key="q.frage_id"
          class="mb-6 last:mb-0"
      >
        <BaseInput
            v-model="answers[q.frage_id]"
            :label="q.frage"
            :placeholder="q.antwort_vorschlag || ''"
            textarea
        />
      </div>

      <div class="flex justify-end space-x-4 mt-8">
        <button
            type="button"
            @click="onCancel"
            class="btn btn-sm btn-secondary"
        >
          Abbrechen
        </button>
        <button
            type="submit"
            class="btn btn-sm btn-primary"
        >
          Speichern
        </button>
      </div>
    </form>
  </div>
</template>

<script setup lang="ts">
import { ref, reactive, onMounted } from 'vue';
import { useRouter } from 'vue-router';
import BaseInput from './BaseInput.vue';

interface FrageRow {
  frage_id: number;
  frage: string;
  antwort_vorschlag: string | null;
  antwort: string | null;
}

const router = useRouter();
const goBack = () => router.back();

const loading = ref(true);
const questions = ref<FrageRow[]>([]);
const answers = reactive<Record<number, string>>({});
let originalAnswers: Record<number, string> = {};

// initial load
async function loadQuestions() {
  loading.value = true;
  try {
    const res = await fetch('/api/get_questions.php', {
      method: 'POST',
      credentials: 'include',
    });
    const data: FrageRow[] = await res.json();
    questions.value = data;
    data.forEach(q => {
      answers[q.frage_id] = q.antwort ?? '';
    });
    originalAnswers = { ...answers };
  } catch (err) {
    console.error('Fehler beim Laden der Fragen:', err);
  } finally {
    loading.value = false;
  }
}

// Abbrechen: zurück zur vorherigen Seite
function onCancel() {
  // Antworten zurücksetzen (optional) …
  Object.keys(answers).forEach(key => {
    answers[+key] = originalAnswers[+key] || '';
  });
  goBack();
}

// Speichern: nur tatsächliche Änderungen schicken
async function onSave() {
  // 1) Diff zwischen originalAnswers und aktuellem answers
  const diff = questions.value
      .map(q => {
        const neu = answers[q.frage_id].trim();
        const alt = originalAnswers[q.frage_id] ?? '';
        return { frage_id: q.frage_id, neu, alt };
      })
      .filter(item => item.neu !== item.alt);

  // 2) Nichts geändert? sofort zurück
  if (diff.length === 0) {
    return goBack();
  }

  // 3) Trennen in Updates vs. Deletes
  const toUpdate = diff
      .filter(d => d.neu !== '')
      .map(d => ({ frage_id: d.frage_id, antwort: d.neu }));

  const toDelete = diff
      .filter(d => d.neu === '')
      .map(d => d.frage_id);

  // 4) Absenden
  try {
    const res = await fetch('/api/save_answers.php', {
      method: 'POST',
      credentials: 'include',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({ update: toUpdate, delete: toDelete })
    });
    const result = await res.json();
    if (!res.ok) throw new Error(result.error || res.statusText);

    // 5) Lokaler State aktualisieren
    toUpdate.forEach(u => {
      originalAnswers[u.frage_id] = u.antwort;
    });
    toDelete.forEach(id => {
      delete originalAnswers[id];
      answers[id] = '';
    });

    alert('Deine Antworten wurden gespeichert.');
    goBack();

  } catch (err: any) {
    console.error('Save-Error:', err);
    alert('Fehler beim Speichern: ' + err.message);
  }
}

onMounted(loadQuestions);
</script>

<style scoped>

</style>