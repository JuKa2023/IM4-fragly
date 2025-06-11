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
  <div class="space-y-4">
    <!-- Link -->
    <div
      class="flex items-center justify-center gap-2 cursor-pointer group"
      @click="copyToClipboard(gruppeLink, 'link')"
    >
      <span class="text-2xl">🔗</span>
      <a
        :href="gruppeLink"
        target="_blank"
        class="text-xl underline font-bold group-hover:text-pink-600"
      >
        {{ gruppeLink }}
      </a>
    </div>

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
  </div>
</template> 