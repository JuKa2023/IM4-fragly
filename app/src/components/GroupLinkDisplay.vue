<script lang="ts" setup>
import {toast} from "vue-sonner";
import {computed} from "vue";

const props = defineProps<{
  kuerzel: string;
}>();

const gruppeLink = computed(() => `${window.location.origin}/beitreten/${props.kuerzel}`);

function copyToClipboard(text: string) {
  navigator.clipboard.writeText(text).then(() => {
    toast.success("Link kopiert!")
  });
}
</script>

<template>
  <div class="space-y-4 mt-16">
    <div class="mb-1 text-left">
      <span class="text-bm text-[#472402] font-bold">Gruppenlink</span>
    </div>
    <div
        class="flex items-center px-4 py-3 rounded-md border border-[#FFD6EC] bg-[#FFEFF6]/60 cursor-pointer transition duration-200 group text-left"
        @click="copyToClipboard(gruppeLink.value)"
    >
      <span class="text-[#7CA4A0] mr-2">
        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
          <path
              d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"
              stroke-linecap="round" stroke-linejoin="round"
              stroke-width="2"/>
        </svg>
      </span>
      {{ gruppeLink }}
      <span class="ml-auto text-xs text-[#7CA4A0] opacity-0 group-hover:opacity-100 transition-opacity duration-200">
        Klicken zum Kopieren
      </span>
    </div>
  </div>
</template>