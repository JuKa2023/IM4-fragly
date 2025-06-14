<script lang="ts" setup>
import {onMounted, reactive, ref} from "vue";
import {toast} from "vue-sonner";
import BaseInput from "./BaseInput.vue";
import ProfilePicture from "./ProfilePicture.vue";
import {useRouter} from "vue-router";

interface Nutzer {
  Benutzername: string;
  email: string;
  avatar_url: string | null;
}

const router = useRouter();
const loading = ref(true);
const error = ref<string | null>(null);

const nutzer = reactive<Nutzer>({
  Benutzername: "",
  email: "",
  avatar_url: null,
});

const newPassword = ref("");
const confirmPassword = ref("");

async function fetchNutzer() {
  loading.value = true;
  error.value = null;
  try {
    const res = await fetch("/api/nutzer_meta.php", {credentials: "include"});
    const json = await res.json();
    nutzer.Benutzername = json.nutzer;
    nutzer.email = json.email;
    nutzer.avatar_url = json.avatar_url ?? null;
  } catch (e) {
    error.value = (e as Error).message;
  } finally {
    loading.value = false;
  }
}

async function updateNutzer() {
  // wenn ein neues Passwort eingegeben wurde, müssen die beiden übereinstimmen
  if (newPassword.value && newPassword.value !== confirmPassword.value) {
    error.value = "Die Passwörter stimmen nicht überein.";
    return;
  }
  error.value = null;

  try {
    const payload = {
      ...nutzer,
      ...(newPassword.value ? {newPassword: newPassword.value} : {})
    };
    const res = await fetch("/api/update_nutzer.php", {
      method: "POST",
      credentials: "include",
      headers: {"Content-Type": "application/json"},
      body: JSON.stringify(payload),
    });
    const json = await res.json();
    if (!json.success)
      throw new Error(json.error ?? "Speichern fehlgeschlagen");

    newPassword.value = "";
    confirmPassword.value = "";
    toast.success("Änderungen gespeichert!");
  } catch (e) {
    error.value = (e as Error).message;
  }
}

function cancelUpdate() {
  router.push({name: "home"});
}

onMounted(fetchNutzer);
</script>

<template>
  <div class="p-4 sm-8 bg-[#FFF4EB] rounded-xl max-w-2xl mx-auto shadow-md">
    <h1 class="mb-6">Ich</h1>

    <p v-if="loading" class="text-sm text-gray-600">Lade Nutzerdaten…</p>
    <p v-if="error" class="text-sm text-red-500">{{ error }}</p>

    <div v-if="!loading">
      <div class="mb-6 flex justify-center">
        <ProfilePicture
            :editable="true"
            :initial-url="nutzer.avatar_url"
            class="w-25 aspect-square flex-shrink-0"
            @uploaded="(url) => (nutzer.avatar_url = url)"
        />
      </div>

      <form class="mt-6 space-y-4" @submit.prevent="updateNutzer">
        <BaseInput
            id="username"
            v-model="nutzer.Benutzername"
            label="Benutzername bearbeiten"
            placeholder="Aktueller Nutzername"
            type="text"
        />

        <BaseInput
            id="email"
            v-model="nutzer.email"
            label="E-Mail"
            placeholder="Aktuelle E-Mail"
            type="email"
        />

        <BaseInput
            id="password"
            v-model="newPassword"
            label="Neues Passwort"
            placeholder="Neues Passwort (optional)"
            type="password"
        />

        <BaseInput
            id="confirm-password"
            v-model="confirmPassword"
            label="Passwort wiederholen"
            placeholder="Passwort erneut eingeben"
            type="password"
        />

        <div class="flex justify-between space-x-4 mt-6">
          <button class="btn btn-lg btn-primary flex-1" type="submit">
            Speichern
          </button>
          <button
              class="btn btn-lg btn-secondary flex-1"
              type="reset"
              @click="cancelUpdate"
          >
            Abbrechen
          </button>
        </div>
      </form>
    </div>
  </div>
</template>