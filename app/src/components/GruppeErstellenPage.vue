<script setup lang="ts">
import { ref } from "vue";
import BaseInput from "./BaseInput.vue";
import { RouterLink } from "vue-router";
import { toast } from "vue-sonner";
import GroupLinkDisplay from "./GroupLinkDisplay.vue";

const groupName = ref("");
const loeschdatum = ref("");
const message = ref("");
const success = ref(false);
const kuerzel = ref("");
const gruppeLink = ref("");

async function submitGroup() {
  message.value = "";
  success.value = false

  const formData = new FormData();
  formData.append("Gruppe_Name", groupName.value);
  formData.append("Loeschdatum", loeschdatum.value || "");

  try {
    const res = await fetch("/api/insert_group.php", {
      method: "POST",
      credentials: "include",
      body: formData,
    });

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
      if (message.value) {
        toast.error(message.value);
      }
    }
  } catch (err) {
    message.value = "Verbindungsfehler.";
    success.value = false;
    toast.error(message.value);
  }
}
</script>

<template>
  <div class="page-default">
    <h1>
      {{ success ? "Hier dein Link" : "Gebe folgende Angaben an" }}
    </h1>

    <!-- Success View -->
    <div v-if="success" class="text-center space-y-6">
      <GroupLinkDisplay :gruppe-link="gruppeLink" />

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
  :allowOnlyFutureDates="true"
/>
      <button type="submit" class="btn btn-lg btn-primary w-full mt-6">
        Gruppe erstellen
      </button>
    </form>
  </div>
</template>

<style scoped>
.text-brown {
  color: #5c2e00;
}
</style>
