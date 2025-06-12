<script setup lang="ts">
import { toast } from "vue-sonner";

defineProps<{
  gruppeLink: string;
  kuerzel: string;
}>();

function copyToClipboard(text: string, type: "link" | "kuerzel") {
  navigator.clipboard.writeText(text).then(() => {
    if (type === "link") {
      toast.success("Link kopiert!");
    } else if (type === "kuerzel") {
      toast.success("Kürzel kopiert!");
    }
  });
}
</script>

<template>
  <div class="space-y-4 mt-16">
    <!-- Link Label -->
    <div class="mb-1 text-left">
      <span class="text-bm text-[#472402] font-bold">Gruppenlink</span>
    </div>
    <!-- Link -->
    <div
      class="flex items-center px-4 py-3 rounded-md border border-[#FFD6EC] bg-[#FFEFF6]/60 cursor-pointer transition duration-200 group text-left"
      @click="copyToClipboard(gruppeLink, 'link')"
    >
      <span class="text-[#7CA4A0] mr-2">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1" />
        </svg>
      </span>
      <a
        :href="gruppeLink"
        target="_blank"
        class="text-[#472402] text-s font-medium group-hover:underline transition-colors duration-200"
      >
        {{ gruppeLink }}
      </a>
      <span class="ml-auto text-xs text-[#7CA4A0] opacity-0 group-hover:opacity-100 transition-opacity duration-200">
        Klicken zum Kopieren
      </span>
    </div>

    <!-- Kürzel Label -->
    <div class="mb-1 mt-4 text-left">
      <span class="mb-1 text-left">Gruppen-Kürzel</span>
    </div>
    <!-- Kürzel -->
    <div
      class="flex items-center px-4 py-3 rounded-md border border-[#FFD6EC] bg-[#FFEFF6]/60 cursor-pointer transition duration-200 group text-left"
      @click="copyToClipboard(kuerzel, 'kuerzel')"
    >
      <span class="text-[#7CA4A0]">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" />
        </svg>
      </span>
      <span class="text-[#472402] text-s font-medium ml-3 group-hover:text-[#7CA4A0] transition-colors duration-200">
        {{ kuerzel }}
      </span>
      <span class="ml-auto text-sm text-[#7CA4A0] opacity-0 group-hover:opacity-100 transition-opacity duration-200">
        Klicken zum Kopieren
      </span>
    </div>
  </div>
</template> 