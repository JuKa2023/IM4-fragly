<template>
    <div class="mb-4">
      <label :for="id" class="block text-[#472402] font-medium mb-1">{{ label }}</label>
      <input
        :id="id"
        :type="type"
        :placeholder="placeholder"
        :value="modelValue"
        @input="$emit('update:modelValue', $event.target.value)"
        class="bg-white w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[#FCC1DB] custom-placeholder"
      />
    </div>
  </template>
  
  <script setup lang="ts">
  defineProps<{
    label: string
    modelValue: string
    id: string
    type?: string
    placeholder?: string
  }>()
  defineEmits(['update:modelValue'])
  </script>

<style scoped>
.custom-placeholder::placeholder {
  color: #B3C7CA;
  opacity: 1; /* Ensures full color */
}
</style>