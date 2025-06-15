<script lang="ts" setup>
import {onMounted, ref} from "vue";
import {useRoute} from "vue-router";
import ProfilePicture from "./ProfilePicture.vue";
import CloseButton from "./CloseButton.vue";

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
      body: JSON.stringify({user_id: userId}),
    });
    const data = await res.json();
    fields.value = data.filter((q: FrageRow) => q.antwort !== null);
  } catch (e) {
    console.error("Fehler beim Laden der Fragen:", e);
  } finally {
    loaded.value = true;
  }


  const resUser = await fetch("/api/nutzer_meta.php", {
    credentials: "include",
    method: "POST",
    body: JSON.stringify({user_id: userId}),
  });
  user.value = await resUser.json();

});
</script>

<template>
  <div
      class="card-default relative"
  >
    <div class="flex items-baseline justify-between mb-4">
      <CloseButton class="absolute top-4 right-4"/>
      <h1 class="text-3xl font-bold mb-6">Steckbrief</h1>
    </div>

    <ProfilePicture
        :editable="false"
        :initial-url="user?.avatar_url"
        class="w-22 aspect-square flex-shrink-0"
    />

    <p v-if="!loaded" class="text-center">Lade …</p>

    <p v-else-if="fields.length === 0" class="text-center">
      Es wurden noch keine Fragen beantwortet.
    </p>

    <div v-else class="text-left">
      <div v-for="f in fields" :key="f.frage_id" class="mb-6 last:mb-0">
        <p class="p-bold">{{ f.frage }}</p>
        <p>{{ f.antwort }}</p>
      </div>
    </div>
    <RouterLink
        v-if="!userId"
        :to="{ name: 'fragebogen' }"
        class="btn btn-sm btn-primary mt-8"
        replace
    >
      Bearbeiten
    </RouterLink>
  </div>
</template>
