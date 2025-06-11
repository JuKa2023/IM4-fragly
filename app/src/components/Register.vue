<script setup lang="ts">
import { ref } from "vue";
import { toast } from "vue-sonner";
import BaseInput from "./BaseInput.vue";
import { useRouter } from "vue-router";

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
  <div class="min-h-screen flex items-center justify-center w-md">
    <div class="p-8 w-full">
      <h1 class="font-bold text-[#472402] mb-6">Registrieren</h1>

      <form @submit.prevent="register" id="registerForm">
        <BaseInput
          label="Benutzername"
          v-model="username"
          id="username"
          type="text"
          placeholder="Gib deinen Benutzernamen ein"
        />

        <BaseInput
          label="Email"
          v-model="email"
          id="email"
          type="email"
          placeholder="Gib deine Email ein"
        />

        <BaseInput
          label="Passwort wählen"
          v-model="password"
          id="password"
          type="password"
          placeholder="Gib dein Passwort ein"
        />

        <BaseInput
          label="Passwort erneut eingeben"
          v-model="confirmPassword"
          id="confirm-password"
          type="password"
          placeholder="Gib dein Passwort erneut ein"
        />

        <button type="submit" class="btn btn-lg btn-primary">
          Konto erstellen
        </button>
      </form>
    </div>
  </div>
</template>
