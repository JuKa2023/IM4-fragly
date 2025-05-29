<script setup lang="ts">
import { reactive, ref, onMounted } from "vue";
import BaseInput from "./BaseInput.vue";
import ProfilePicture from "./ProfilePicture.vue";

interface Nutzer {
  Benutzername: string;
  Email: string;
  Profilbild_URL: string | null;
}

const loading = ref(true);
const error = ref<string | null>(null);

const nutzer = reactive<Nutzer>({
  Benutzername: "",
  Email: "",
  Profilbild_URL: null,
});

// nur neue Passwörter
const newPassword = ref("");
const confirmPassword = ref("");

async function fetchNutzer() {
  loading.value = true;
  error.value = null;
  try {
    const res = await fetch("/api/nutzer.php", { credentials: "include" });
    const json = await res.json();
    nutzer.Benutzername = json.Nutzer;
    nutzer.Email = json.Email;
    nutzer.Profilbild_URL = json.Profilbild_URL ?? null;
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
      ...(newPassword.value ? { newPassword: newPassword.value } : {})
    };
    const res = await fetch("/api/update_nutzer.php", {
      method: "POST",
      credentials: "include",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify(payload),
    });
    const json = await res.json();
    if (!json.success)
      throw new Error(json.error ?? "Speichern fehlgeschlagen");

    // Passwort-Felder zurücksetzen
    newPassword.value = "";
    confirmPassword.value = "";
    alert("Änderungen gespeichert!");
  } catch (e) {
    error.value = (e as Error).message;
  }
}

function cancelUpdate() {
  fetchNutzer();
  newPassword.value = "";
  confirmPassword.value = "";
}

onMounted(fetchNutzer);
</script>

<template>
  <div class="min-h-screen flex items-center justify-center">
    <div class="p-6 bg-[#FFF4EB] rounded-xl max-w-md mx-auto mt-8 shadow-md">
      <h1 class="font-bold text-[#472402] mb-6">Nutzerdaten bearbeiten</h1>

      <p v-if="loading" class="text-sm text-gray-600">Lade Nutzerdaten…</p>
      <p v-if="error" class="text-sm text-red-500">{{ error }}</p>

      <div v-if="!loading">
        <div class="mb-6 flex justify-center">
          <ProfilePicture
            :initial-url="nutzer.Profilbild_URL"
            :editable="true"
            @uploaded="(url) => (nutzer.Profilbild_URL = url)"
          />
        </div>

        <form @submit.prevent="updateNutzer">
          <BaseInput
            label="Benutzername bearbeiten"
            v-model="nutzer.Benutzername"
            id="username"
            type="text"
            placeholder="Aktueller Nutzername"
          />

          <BaseInput
            label="E-Mail"
            v-model="nutzer.Email"
            id="email"
            type="email"
            placeholder="Aktuelle E-Mail"
          />

          <BaseInput
            label="Neues Passwort"
            v-model="newPassword"
            id="password"
            type="password"
            placeholder="Neues Passwort (optional)"
          />

          <BaseInput
            label="Passwort wiederholen"
            v-model="confirmPassword"
            id="confirm-password"
            type="password"
            placeholder="Passwort erneut eingeben"
          />

          <div class="flex gap-4 mt-6">
            <button type="submit" class="btn btn-lg btn-primary flex-1">
              Speichern
            </button>
            <button
              type="reset"
              @click="cancelUpdate"
              class="btn btn-lg btn-secondary flex-1"
            >
              Abbrechen
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</template>