<script setup lang="ts">
import { ref } from 'vue';
import { useRouter } from 'vue-router';
import BasInput from "./BaseInput.vue";


const router     = useRouter();

const groupName  = ref('');
const code       = ref('');          
const errorMsg   = ref('');
const joining    = ref(false);

async function joinGroup () {
  errorMsg.value = '';

  if (!groupName.value.trim() || !code.value.trim()) {
    errorMsg.value = 'Bitte fülle beide Felder aus.';
    return;
  }

  joining.value = true;
  try {
    const res  = await fetch('/api/gruppe_beitreten_manuel.php', {
      method: 'POST',
      credentials: 'include',
      headers: { 'Content-Type':'application/x-www-form-urlencoded' },
      body: new URLSearchParams({
        name : groupName.value.trim(),
        code : code.value.trim()
      })
    });

    const data = await res.json();
    if (!res.ok || !data.success) throw new Error(data.message || 'Beitritt fehlgeschlagen');

    router.push(`/gruppenmitglieder/${data.gruppeId}`);
  }
  catch (err:any) {
    errorMsg.value = err.message || 'Beitritt fehlgeschlagen';
  }
  finally {
    joining.value = false;
  }
}
</script>

<template>
  <div class="p-10 max-w-xs mx-auto text-brown">

    <h1 class="text-3xl font-bold leading-tight text-center mb-10">
      Gib bitte ein, wie die Gruppe heisst.
    </h1>

    <p v-if="errorMsg" class="text-red-600 mb-4 text-center">{{ errorMsg }}</p>

    <form @submit.prevent="joinGroup" class="flex flex-col gap-6">
      <BasInput
        id="groupName"
        label="Gruppenbezeichnung"
        placeholder="Familie Mustermann"
        v-model="groupName"
      />

      <BasInput
        id="code"
        label="Kürzel"
        placeholder="QR37#JK"
        v-model="code"
      />

      <button
        type="submit"
        class="btn btn-lg btn-primary mt-6"
        :disabled="joining"
      >
        Gruppe beitreten
      </button>
    </form>
  </div>
</template>

<style scoped>
.text-brown { color:#5c2e00; }
</style>