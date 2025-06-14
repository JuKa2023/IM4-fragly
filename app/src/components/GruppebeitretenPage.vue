<script lang="ts" setup>
import { ref, computed, onMounted } from 'vue';
import { useRouter, useRoute } from 'vue-router';
import BaseInput from "./BaseInput.vue";

const router = useRouter();
const route  = useRoute();

const groupName = ref('');
const groupCode = computed(() => route.params.code as string | undefined);
const fetchedName = ref('');
const errorMsg = ref('');
const busy = ref(false);

onMounted(async () => {
  if (!groupCode.value) return;
  try {
    const res = await fetch(`/api/gruppe_meta.php?code=${encodeURIComponent(groupCode.value)}`,
        { credentials: 'include' });
    const data = await res.json();
    if (!res.ok || !data.success) throw new Error(data.message);
    fetchedName.value = data.name;
  } catch (e: any) {
    errorMsg.value = e.message || 'Gruppe nicht gefunden';
  }
});

async function join() {
  errorMsg.value = '';
  busy.value = true;

  const payload = new URLSearchParams(
      groupCode.value
          ? { code: groupCode.value.trim() }
          : { name: groupName.value.trim() }
  );

  if (!groupCode.value && !payload.get('name')) {
    errorMsg.value = 'Bitte gib einen Gruppennamen ein.';
    busy.value = false;
    return;
  }

  try {
    const res  = await fetch('/api/gruppe_beitreten.php', {
      method: 'POST',
      headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
      credentials: 'include',
      body: payload
    });
    const data = await res.json();
    if (!res.ok || !data.success) throw new Error(data.message);

    await router.push({ name: 'GruppenMitglieder', params: { id: data.gruppeId } });
  } catch (e: any) {
    errorMsg.value = e.message || 'Beitritt fehlgeschlagen';
  } finally {
    busy.value = false;
  }
}

function decline () {
  router.replace({ name: 'home' });
}
</script>

<template>
  <div class="page-default">
    <h1 v-if="!groupCode">
      Gib bitte ein, wie die&nbsp;Gruppe heisst.
    </h1>

    <h1 v-else
        class="text-3xl font-bold leading-tight text-center mb-8">
      Möchtest du der<br>
      <span class="text-teal-700">{{ fetchedName || '…' }}</span><br>
      beitreten?
    </h1>

    <p v-if="errorMsg"
       class="text-red-600 mb-4 text-center">
      {{ errorMsg }}
    </p>

    <form v-if="!groupCode"
          class="flex flex-col gap-8"
          @submit.prevent="join">
      <BaseInput id="groupName"
                 v-model="groupName"
                 label="Gruppenbezeichnung"
                 placeholder="Familie Mustermann"/>
      <button :disabled="busy"
              type="submit"
              class="btn btn-lg btn-primary self-center">
        Gruppe beitreten
      </button>
    </form>

    <div v-else class="flex flex-col items-center gap-8">
      <button :disabled="busy"
              class="btn btn-lg btn-secondary"
              @click="decline">
        Einladung ablehnen
      </button>

      <button :disabled="busy"
              class="btn btn-lg btn-primary"
              @click="join">
        Gruppe beitreten
      </button>
    </div>
  </div>
</template>