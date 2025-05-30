<script setup lang="ts">
import { ref, onMounted } from 'vue';
import { useRoute, useRouter } from 'vue-router';

const route  = useRoute();
const router = useRouter();
const code   = route.params.code as string;      // comes from /join/:code

const loading  = ref(true);
const joining  = ref(false);
const family   = ref<{ id:number; name:string }|null>(null);
const errorMsg = ref('');

/* ─────────────────────────────── GET meta once ── */
onMounted(async () => {
  try {
    const res  = await fetch(`/api/einladung_meta.php?code=${encodeURIComponent(code)}`);
    const data = await res.json();

    if (!res.ok) throw new Error(data.message || 'Einladung ungültig');
    family.value = { id:Number(data.gruppeId), name:data.familyName };
  } catch (err:any) {
    errorMsg.value = err.message || 'Einladung ungültig';
  } finally {
    loading.value = false;
  }
});

/* ─────────────────────────────── POST join ── */
async function accept () {
  if (!family.value) return;          // should never happen but be safe
  joining.value = true;

  try {
    const res  = await fetch('/api/gruppe_beitretten.php', {
      method: 'POST',
      credentials: 'include',
      headers: { 'Content-Type':'application/x-www-form-urlencoded' },
      body: new URLSearchParams({ code })
    });
    const data = await res.json();

    if (!res.ok) throw new Error(data.message || 'Beitritt fehlgeschlagen');

    router.push(`/gruppen/${data.gruppeId ?? family.value.id}`);
  } catch (err:any) {
    errorMsg.value = err.message || 'Beitritt fehlgeschlagen';
  } finally {
    joining.value = false;
  }
}
</script>

<template>
  <div class="p-10 text-center text-brown">
    <div v-if="loading">Lade Einladung…</div>

    <div v-else-if="errorMsg" class="text-red-600">{{ errorMsg }}</div>

    <div v-else>
      <h2 class="text-2xl font-bold mb-6">
        Möchtest du der
        <span class="text-teal-700">{{ family!.name }}</span>
        beitreten?
      </h2>

      <button class="btn btn-outline w-full mb-4" @click="$router.push('/')">
        Einladung ablehnen
      </button>

      <button class="btn btn-primary w-full"
              :disabled="joining"
              @click="accept">
        Gruppe beitreten
      </button>
    </div>
  </div>
</template>

<style scoped>
.text-brown { color:#5c2e00; }
</style>