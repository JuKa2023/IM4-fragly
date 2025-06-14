<script setup lang="ts">
import { useRouter, type RouteLocationRaw } from "vue-router";

const router = useRouter();

const emit = defineEmits<{ (e: "close"): void }>();

const props = defineProps<{
  to?: RouteLocationRaw;
}>();

function handleClick() {
  emit("close");

  if (props.to) {
    router.push(props.to);
  } else {
    router.back();
  }
}
</script>

<template>
  <button class="btn-close" @click="handleClick">
    <img src="../assets/xicon.svg" alt="Close" />
  </button>
</template>