<script lang="ts" setup>
import {ref} from "vue";
import {toast} from "vue-sonner";
import BaseInput from "./BaseInput.vue";
import {useRouter} from "vue-router";

const username = ref("");
const email = ref("");
const password = ref("");
const confirmPassword = ref("");

const router = useRouter();

const register = async () => {
  if (!username.value || !email.value || !password.value) {
    toast.error("Bitte fülle alle Felder aus");
    return;
  }
  if (password.value.length < 8) {
    toast.error("Passwort muss mindestens 8 Zeichen lang sein");
    return;
  }
  if (password.value !== confirmPassword.value) {
    toast.error("Passwörter stimmen nicht überein");
    return;
  }

  // Prepare FormData
  const formData = new FormData();
  formData.append("username", username.value);
  formData.append("email", email.value);
  formData.append("password", password.value);

  try {
    const res = await fetch("/api/register.php", {
      method: "POST",
      body: formData,
    });
    const reply = await res.text();
    toast(reply);

    if (reply === "Registrierung erfolgreich") {
      router.push("/anmelden");
    }
  } catch (err) {
    toast.error("Fehler beim Senden: " + err);
  }
};
</script>

<template>
  <div class="page-default flex items-center justify-center">
    <div class="w-full">
      <h1 class="mb-8">Registrieren</h1>

      <form id="registerForm" @submit.prevent="register">
        <BaseInput
            id="username"
            v-model="username"
            label="Benutzername"
            placeholder="Gib deinen Benutzernamen ein"
            type="text"
        />

        <BaseInput
            id="email"
            v-model="email"
            label="Email"
            placeholder="Gib deine Email ein"
            type="email"
        />

        <BaseInput
            id="password"
            v-model="password"
            label="Passwort wählen"
            placeholder="Gib dein Passwort ein"
            type="password"
        />

        <BaseInput
            id="confirm-password"
            v-model="confirmPassword"
            label="Passwort erneut eingeben"
            placeholder="Gib dein Passwort erneut ein"
            type="password"
        />

        <button class="btn btn-lg btn-primary mt-8" type="submit">
          Konto erstellen
        </button>
      </form>
    </div>
  </div>
</template>
