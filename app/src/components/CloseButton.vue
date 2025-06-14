<script lang="ts" setup>
import {type RouteLocationRaw, useRouter} from "vue-router";

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
    <img alt="Close" src="../assets/xicon.svg"/>
  </button>
</template>