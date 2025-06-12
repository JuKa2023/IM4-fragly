<script setup lang="ts">
import { ref, reactive, onMounted } from "vue";
import { useRouter } from "vue-router";
import { toast } from "vue-sonner";

import BaseInput from "./BaseInput.vue";
import ProfilePicture from "./ProfilePicture.vue";

interface FrageRow {
  frage_id: number;
  frage: string;
  antwort_vorschlag: string | null;
  antwort: string | null;
  input_type: string; // <-- Add this
}

const router = useRouter();

const redirectRoute = {name: 'steckbriefMe'};

const loading = ref(true);
const questions = ref<FrageRow[]>([]);
const answers = reactive<Record<number, string>>({});
let originalAnswers: Record<number, string> = {};

async function loadQuestions() {
  loading.value = true;
  try {
    const res = await fetch("/api/get_questions.php", {
      credentials: "include",
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({ user_id: undefined }), // undefined = current logged-in user
    });
    const data: FrageRow[] = await res.json();
    questions.value = data;

    data.forEach((q) => {
      answers[q.frage_id] = q.antwort ?? "";
    });

    originalAnswers = { ...answers };
  } catch (err) {
    console.error("Fehler beim Laden der Fragen:", err);
  } finally {
    loading.value = false;
  }
}

function onCancel() {
  Object.keys(answers).forEach((key) => {
    answers[+key] = originalAnswers[+key] || "";
  });
  router.push(redirectRoute);
}

async function onSave() {
  const diff = questions.value
      .map((q) => {
        const neu = answers[q.frage_id].trim();
        const alt = originalAnswers[q.frage_id] ?? "";
        return { frage_id: q.frage_id, neu, alt };
      })
      .filter((item) => item.neu !== item.alt);

  if (diff.length === 0) {
    return router.push(redirectRoute);
  }

  const toUpdate = diff
      .filter((d) => d.neu !== "")
      .map((d) => ({ frage_id: d.frage_id, antwort: d.neu }));

  const toDelete = diff.filter((d) => d.neu === "").map((d) => d.frage_id);

  try {
    const res = await fetch("/api/save_answers.php", {
      method: "POST",
      credentials: "include",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({ update: toUpdate, delete: toDelete }),
    });
    const result = await res.json();
    if (!res.ok) throw new Error(result.error || res.statusText);

    toUpdate.forEach((u) => {
      originalAnswers[u.frage_id] = u.antwort;
    });
    toDelete.forEach((id) => {
      delete originalAnswers[id];
      answers[id] = "";
    });

    toast.success("Deine Antworten wurden gespeichert.");
    await router.push(redirectRoute);
  } catch (err: any) {
    console.error("Save-Error:", err);
    toast.error("Fehler beim Speichern: " + err.message);
  }
}

onMounted(loadQuestions);
</script>

<template>
  <div
    class="p-8 max-w-2xl mx-auto bg-cream-light rounded-xl shadow-md text-brown"
  >
    <h1 class="text-3xl font-bold mb-6">Dein Steckbrief</h1>

    <ProfilePicture
      initial-url="/images/default-profile.png"
      :editable="true"
    />

    <!-- Loader -->
    <p v-if="loading" class="text-center">Lade …</p>

    <!-- Keine Fragen definiert -->
    <p v-else-if="questions.length === 0" class="text-center">
      Keine Fragen vorhanden.
    </p>

    <!-- Editierbares Formular -->
    <form v-else @submit.prevent="onSave">
      <div v-for="q in questions" :key="q.frage_id" class="mb-6 last:mb-0">
        <BaseInput
  v-model="answers[q.frage_id]"
  :label="q.frage"
  :placeholder="q.antwort_vorschlag || ''"
  :type="q.input_type || 'text'"
  :id="'frage-' + q.frage_id"
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
        <button type="submit" class="btn btn-sm btn-primary">Speichern</button>
      </div>
    </form>
  </div>
</template>



