<template>
  <div class="mb-4 relative">
    <label :for="id" class="block text-[#472402] font-medium mb-1 text-left">{{
      label
    }}</label>

    <div class="relative">
      <input
        :id="id"
        :type="type === 'password' && showPassword ? 'text' : type"
        :placeholder="placeholder"
        :value="modelValue"
        @input="$emit('update:modelValue', $event.target.value)"
        class="bg-white w-full px-4 py-2 pr-12 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[#FCC1DB] custom-placeholder text-left"
      />

      <!-- Toggle Button for Password -->
      <button
        v-if="type === 'password'"
        type="button"
        class="absolute inset-y-0 right-3 flex items-center text-gray-500 hover:text-gray-700"
        @click="togglePassword"
      >
        <img
          :src="showPassword ? unsichtbaricon : sichtbaricon"
          :alt="showPassword ? 'verbergen' : 'anzeigen'"
          class="w-5 h-5"
        />
      </button>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref } from "vue";

import sichtbaricon from "../assets/sichtbaricon.svg";
import unsichtbaricon from "../assets/unsichtbaricon.svg";

const props = defineProps<{
  label: string;
  modelValue: string;
  id: string;
  type?: string;
  placeholder?: string;
}>();

const emit = defineEmits(["update:modelValue"]);

const showPassword = ref(false);

const togglePassword = () => {
  showPassword.value = !showPassword.value;
};
</script>

<style scoped>
.custom-placeholder::placeholder {
  color: #b3c7ca;
  opacity: 1;
}
</style>
