<script lang="ts" setup>
import {ref} from "vue";
import {toast} from "vue-sonner";
import BaseInput from "./BaseInput.vue";
import {useRouter} from "vue-router";

// Reactive input fields
const username = ref("");
const password = ref("");

const router = useRouter();

// Login function
const login = async () => {
  if (!username.value || !password.value) {
    toast.error("Bitte fülle alle Felder aus");
    return;
  }

  const formData = new FormData();
  formData.append("username", username.value);
  formData.append("password", password.value);

  try {
    const res = await fetch("api/login.php", {
      method: "POST",
      body: formData,
    });
    const reply = await res.text();
    console.log("Antwort vom Server:\n" + reply);
    toast.error(reply);

    if (reply === "Login erfolgreich") {
      router.push("/home");
    }
  } catch (err) {
    console.error("Fehler beim Senden:", err);
  }
};
</script>

<template>
  <div class="page-default flex items-center justify-center">
    <div class="w-full">
      <h1 class="font-bold text-[#472402] mb-6">Melde dich an</h1>

      <form id="loginForm" @submit.prevent="login">
        <BaseInput
            id="username"
            v-model="username"
            label="Benutzername eingeben"
            placeholder="JuKa365"
            type="text"
        />

        <BaseInput
            id="password"
            v-model="password"
            label="Passwort eingeben"
            placeholder="*****"
            type="password"
        />

        <button class="btn btn-lg btn-primary mt-8" type="submit">Anmelden</button>
      </form>
    </div>
  </div>
</template>
