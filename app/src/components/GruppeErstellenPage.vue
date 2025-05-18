<script setup lang="ts">
import { ref } from 'vue';
import BaseInput from './BaseInput.vue';

const groupName = ref('');
const loeschdatum = ref('');
const message = ref('');
const success = ref(false);

async function submitGroup() {
  message.value = '';

  // Use FormData instead of JSON
  const formData = new FormData();
  formData.append('Gruppe_Name', groupName.value);
  formData.append('Loeschdatum', loeschdatum.value || '');

  try {
    const res = await fetch('api/insert_group.php', {
      method: 'POST',
      credentials: 'include',
      body: formData,
    });

    const reply = await res.text();
    message.value = reply;
    success.value = reply.toLowerCase().includes('erfolgreich');
  } catch (err) {
    message.value = 'Verbindungsfehler.';
    success.value = false;
  }
}
</script>

<template>
  <div class="p-8 w-full">
    <h2 class="text-2xl font-bold text-brown text-center mb-6">Gebe folgende Angaben an</h2>

    <form @submit.prevent="submitGroup">
      <!-- Gruppenbezeichnung -->
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

      <!-- Submit Button -->
      <button
          type="submit"
          class="btn btn-lg btn-primary"
      >
        Gruppe erstellen
      </button>
    </form>
  </div>
</template>

<style scoped>
.text-brown {
  color: #5C2E00;
}
</style>