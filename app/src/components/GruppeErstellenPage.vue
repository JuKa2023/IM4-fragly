<script setup lang="ts">
import { ref } from 'vue';
import BaseInput from './BaseInput.vue';

const groupName = ref('');
const loeschdatum = ref('');
const message = ref('');
const success = ref(false);
const kuerzel = ref('');
const gruppeLink = ref('');
const copied = ref<null | 'link' | 'kuerzel'>(null);

async function submitGroup() {
  message.value = '';

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

    if (reply.toLowerCase().includes('erfolgreich')) {
      success.value = true;

      // Simulate Kürzel + Link for now (your PHP needs to return this later!)
      kuerzel.value = generateFakeKuerzel(); // placeholder
      gruppeLink.value = `https://example.com/${kuerzel.value}`;
    }
  } catch (err) {
    message.value = 'Verbindungsfehler.';
    success.value = false;
  }
}

// You can replace this with a real value from PHP later
function generateFakeKuerzel() {
  return Math.random().toString(36).substring(2, 8).toUpperCase() + '#JK';
}

function copyToClipboard(text: string, type: 'link' | 'kuerzel') {
  navigator.clipboard.writeText(text).then(() => {
    copied.value = type;
    setTimeout(() => copied.value = null, 2000);
  });
}
</script>

<template>
  <div class="p-8 w-full text-brown">
    <h2 class="text-2xl font-bold text-center mb-6">
      {{ success ? 'Hier dein Link und Kürzel' : 'Gebe folgende Angaben an' }}
    </h2>

    <!-- Success View -->
    <div v-if="success" class="text-center space-y-6">
      <!-- Link -->
      <div
          class="flex items-center justify-center gap-2 cursor-pointer group"
          @click="copyToClipboard(gruppeLink, 'link')"
      >
        <span class="text-2xl">🔗</span>
        <a :href="gruppeLink" target="_blank" class="text-xl underline font-bold group-hover:text-pink-600">
          {{ gruppeLink }}
        </a>
      </div>
      <p v-if="copied === 'link'" class="text-green-600 text-sm">Link kopiert!</p>

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
    </form>
  </div>
</template>

<style scoped>
.text-brown {
  color: #5C2E00;
}
</style>