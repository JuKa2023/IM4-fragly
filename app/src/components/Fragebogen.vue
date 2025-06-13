<script lang="ts" setup>
import {onMounted, reactive, ref} from "vue";
import {useRouter} from "vue-router";
import {toast} from "vue-sonner";

import BaseInput from "./BaseInput.vue";
import ProfilePicture from "./ProfilePicture.vue";

interface FrageRow {
  frage_id: number;
  frage: string;
  antwort_vorschlag: string | null;
  antwort: string | null;
  input_type: string;
}

const router = useRouter();

const redirectRoute = {name: 'steckbriefMe'};

const loading = ref(true);
const questions = ref<FrageRow[]>([]);
const answers = reactive<Record<number, string>>({});
const avatar_url = ref<string | null>(null);

let originalAnswers: Record<number, string> = {};

async function loadQuestions() {
  loading.value = true;
  try {
    const res = await fetch("/api/get_questions.php", {
      credentials: "include",
      method: "POST",
      headers: {"Content-Type": "application/json"},
      body: JSON.stringify({user_id: undefined}), // undefined = current logged-in user
    });
    const data: FrageRow[] = await res.json();
    questions.value = data;

    data.forEach((q) => {
      answers[q.frage_id] = q.antwort ?? "";
    });

    originalAnswers = {...answers};
  } catch (err) {
    toast.error("Fehler beim Laden der Fragen:");
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
        return {frage_id: q.frage_id, neu, alt};
      })
      .filter((item) => item.neu !== item.alt);

  if (diff.length === 0) {
    return router.push(redirectRoute);
  }

  const toUpdate = diff
      .filter((d) => d.neu !== "")
      .map((d) => ({frage_id: d.frage_id, antwort: d.neu}));

  const toDelete = diff.filter((d) => d.neu === "").map((d) => d.frage_id);

  try {
    const res = await fetch("/api/save_answers.php", {
      method: "POST",
      credentials: "include",
      headers: {"Content-Type": "application/json"},
      body: JSON.stringify({update: toUpdate, delete: toDelete}),
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
    toast.error("Fehler beim Speichern: " + err.message);
  }
}

function shouldDisableFutureDates(q: FrageRow): boolean {
  return (
      q.input_type === "date" &&
      q.frage.toLowerCase().includes("geboren")
  );
}

onMounted(async () => {
  await loadQuestions();
  try {
    const res = await fetch("/api/nutzer_meta.php", { credentials: "include" });
    const json = await res.json();
    avatar_url.value = json.avatar_url ?? null;
  } catch (e) {
    toast.error("Fehler beim Laden des Nutzers: " + (e as Error).message);
  }
});

</script>

<template>
  <div
      class="p-8 max-w-2xl mx-auto bg-cream-light rounded-xl shadow-md text-brown"
  >
    <h1 class="text-3xl font-bold mb-6">Dein Steckbrief</h1>

    <ProfilePicture
        :editable="false"
        :initial-url="avatar_url"
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
            :id="'frage-' + q.frage_id"
            v-model="answers[q.frage_id]"
            :disableFutureDates="shouldDisableFutureDates(q)"
            :label="q.frage"
            :placeholder="q.antwort_vorschlag || ''"
            :type="q.input_type || 'text'"
        />
      </div>

      <div class="flex justify-end space-x-4 mt-8">
        <button
            class="btn btn-sm btn-secondary"
            type="button"
            @click="onCancel"
        >
          Abbrechen
        </button>
        <button class="btn btn-sm btn-primary" type="submit">Speichern</button>
      </div>
    </form>
  </div>
</template>



