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
            class="btn btn-sm btn-secondary"        >
          Abbrechen
        </button>
        <button
            type="submit"
            class="btn btn-sm btn-primary">
          Speichern
        </button>
      </div>
    </form>
  </div>
</template>

<script setup lang="ts">
import { ref, reactive, onMounted } from 'vue';
// OPTION A: use the browser history API directly
// const goBack = () => window.history.back();

// OPTION B: use Vue Router
import { useRouter } from 'vue-router';
const router = useRouter();
const goBack = () => router.back();

import BaseInput from './BaseInput.vue';

interface FrageRow {
  frage_id: number;
  frage: string;
  antwort_vorschlag: string | null;
  antwort: string | null;
}

const loading = ref(true);
const questions = ref<FrageRow[]>([]);
const answers = reactive<Record<number, string>>({});
let originalAnswers: Record<number, string> = {};

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

function onCancel() {
  // reset local answers (if you still want that), then go back
  Object.keys(answers).forEach(key => {
    answers[+key] = originalAnswers[+key] || '';
  });
  goBack();
}

async function onSave() {
  const payload = questions.value.map(q => ({
    frage_id: q.frage_id,
    antwort: answers[q.frage_id].trim()
  }));
  try {
    const res = await fetch('/api/save_answers.php', {
      method: 'POST',
      credentials: 'include',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({ answers: payload })
    });
    const result = await res.json();
    if (res.ok) {
      originalAnswers = { ...answers };
      alert('Deine Antworten wurden gespeichert.');
      goBack();
    } else {
      alert('Fehler beim Speichern: ' + (result.error || res.statusText));
    }
  } catch (err) {
    console.error('Save-Error:', err);
    alert('Netzwerkfehler beim Speichern.');
  }
}

onMounted(loadQuestions);
</script>

<style scoped>
.bg-cream-light { background-color: #FFF3E0; }
.text-brown     { color: #5C2E00; }
.bg-brown       { background-color: #5C2E00; }
.bg-brown-dark  { background-color: #4A2400; }
</style>