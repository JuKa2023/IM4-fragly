<script setup lang="ts">
import { useRouter } from 'vue-router';

const router = useRouter();

const emit = defineEmits<{
  (e: 'close'): void
}>();

function handleClick() {
  emit('close');
  router.back();
}
</script>

<template>
  <button
    @click="handleClick"
    class="rounded-full btn p-6 btn-primary flex items-center justify-center"
  >
    <img src="../assets/xicon.svg" alt="Close"/>
  </button>
</template> 