<template>
  <div class="p-8 w-full text-brown">
    <h2 class="text-2xl font-bold text-center mb-6">
      {{ success ? "Hier dein Link und Kürzel" : "Gebe folgende Angaben an" }}
    </h2>

    <!-- Success View -->
    <div v-if="success" class="text-center space-y-6">
      <!-- Link -->
      <div
        class="flex items-center justify-center gap-2 cursor-pointer group"
        @click="copyToClipboard(gruppeLink, 'link')"
      >
        <span class="text-2xl">🔗</span>
        <a
          :href="gruppeLink"
          target="_blank"
          class="text-xl underline font-bold group-hover:text-pink-600"
        >
          {{ gruppeLink }}
        </a>
      </div>
      <p v-if="copied === 'link'" class="text-green-600 text-sm">
        Link kopiert!
      </p>

      <!-- Kürzel -->
      <div
        class="flex items-center justify-center gap-2 cursor-pointer group"
        @click="copyToClipboard(kuerzel, 'kuerzel')"
      >
        <span class="text-2xl">🔗</span>
        <span class="text-xl underline font-bold group-hover:text-pink-600">
          {{ kuerzel }}
        </span>
      </div>
      <p v-if="copied === 'kuerzel'" class="text-green-600 text-sm">
        Kürzel kopiert!
      </p>

      <RouterLink to="/gruppen" class="btn btn-lg btn-primary mt-6">
        meine Gruppen
      </RouterLink>
    </div>

    <!-- Form View -->
    <form v-else @submit.prevent="submitGroup">
      <BaseInput
        id="gruppe-name"
        label="*Gruppenbezeichnung"
        v-model="groupName"
        placeholder="Familie Huber"
        type="text"
      />
      <BaseInput
        id="loeschdatum"
        label="Löschdatum"
        v-model="loeschdatum"
        type="date"
      />
      <button type="submit" class="btn btn-lg btn-primary w-full mt-6">
        Gruppe erstellen
      </button>
      <p v-if="message" class="mt-4 text-center text-red-600">{{ message }}</p>
    </form>
  </div>
</template>

<script setup lang="ts">
import { ref } from "vue";
import BaseInput from "./BaseInput.vue";
import { RouterLink } from "vue-router";

const groupName = ref("");
const loeschdatum = ref("");
const message = ref("");
const success = ref(false);
const kuerzel = ref("");
const gruppeLink = ref("");
const copied = ref<null | "link" | "kuerzel">(null);

async function submitGroup() {
  message.value = "";
  success.value = false;

  const formData = new FormData();
  formData.append("Gruppe_Name", groupName.value);
  formData.append("Loeschdatum", loeschdatum.value || "");

  try {
    const res = await fetch("api/insert_group.php", {
      method: "POST",
      credentials: "include",
      body: formData,
    });

    // Check if the response is JSON
    const contentType = res.headers.get("content-type");
    if (!contentType || !contentType.includes("application/json")) {
      throw new Error("Keine gültige JSON-Antwort vom Server");
    }

    const data = await res.json();

    message.value = data.message;

    if (res.ok && data.success) {
      success.value = true;
      kuerzel.value = data.kuerzel;
      gruppeLink.value = data.link;
    } else {
      success.value = false;
    }
  } catch (err) {
    message.value = "Verbindungsfehler.";
    success.value = false;
  }
}

function copyToClipboard(text: string, type: "link" | "kuerzel") {
  navigator.clipboard.writeText(text).then(() => {
    copied.value = type;
    setTimeout(() => (copied.value = null), 2000);
  });
}
</script>

<style scoped>
.text-brown {
  color: #5c2e00;
}
</style>
