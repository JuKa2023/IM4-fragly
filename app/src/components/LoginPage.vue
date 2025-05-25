<script setup lang="ts">
import { ref } from "vue";
import BaseInput from "./BaseInput.vue";
import { useRouter } from "vue-router";

// Reactive input fields
const username = ref("");
const password = ref("");

const router = useRouter();

// Login function
const login = async () => {
  if (!username.value || !password.value) {
    alert("Bitte fülle alle Felder aus");
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
    alert(reply);

    if (reply === "Login erfolgreich") {
      router.push("/home");
    }
  } catch (err) {
    console.error("Fehler beim Senden:", err);
  }
};
</script>

<template>
  <div class="min-h-screen flex items-center justify-center w-md">
    <div class="p-8 w-full">
      <h1 class="font-bold text-[#472402] mb-6">Melde dich an</h1>

      <form id="loginForm" @submit.prevent="login">
        <BaseInput
          label="Benutzername eingeben"
          v-model="username"
          id="username"
          type="text"
          placeholder="JuKa365"
        />

        <BaseInput
          label="Passwort eingeben"
          v-model="password"
          id="password"
          type="password"
          placeholder="*****"
        />

        <button type="submit" class="btn btn-lg btn-primary">Anmelden</button>
      </form>
    </div>
  </div>
</template>
