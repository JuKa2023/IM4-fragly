<script lang="ts" setup>
import {ref} from "vue";
import {RouterLink, useRouter} from "vue-router";
import BaseInput from "./BaseInput.vue";
import {toast} from "vue-sonner";
import GroupLinkDisplay from "./GroupLinkDisplay.vue";

const router = useRouter();

const groupName = ref("");
const loeschdatum = ref("");
const message = ref("");
const success = ref(false);
const kuerzel = ref("");

async function submitGroup() {
  message.value = "";
  success.value = false;

  const formData = new FormData();
  formData.append("name", groupName.value);
  formData.append("loeschdatum", loeschdatum.value || "");

  try {
    const res = await fetch("/api/gruppe_erstellen.php", {
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
    } else {
      success.value = false;
      if (message.value) toast.error(message.value);
    }
  } catch (err) {
    message.value = "Verbindungsfehler.";
    success.value = false;
    toast.error(message.value);
  }
}

function cancelGroup() {
  router.push({name: "groups"});
}
</script>

<template>
  <div class="page-default">
    <h1 class="mb-6">{{ success ? "Hier dein Link" : "Gebe folgende Angaben an" }}</h1>

    <!-- Success View -->
    <div v-if="success" class="text-center space-y-6">
      <GroupLinkDisplay :kuerzel="kuerzel"/>

      <RouterLink class="btn btn-lg btn-primary mt-6" to="/gruppen">
        Meine Gruppen
      </RouterLink>
    </div>

    <!-- Form View -->
    <form v-else @submit.prevent="submitGroup">
      <BaseInput
          id="gruppe-name"
          v-model="groupName"
          label="*Gruppenbezeichnung"
          placeholder="Familie Huber"
          type="text"
      />
      <BaseInput
          id="loeschdatum"
          v-model="loeschdatum"
          :allowOnlyFutureDates="true"
          label="Löschdatum"
          type="date"
      />

      <div class="flex flex-col items-center gap-y-6 mt-10 mx-auto">
        <button class="btn btn-lg btn-primary" type="submit">
          Gruppe erstellen
        </button>
        <button
            class="btn btn-lg btn-secondary"
            type="reset"
            @click="cancelGroup"
        >
          Abbrechen
        </button>
      </div>
    </form>
  </div>
</template>