<script lang="ts" setup>
import {ref} from "vue";
import Datepicker from "@vuepic/vue-datepicker";
import "@vuepic/vue-datepicker/dist/main.css";

import sichtbaricon from "../assets/sichtbaricon.svg";
import unsichtbaricon from "../assets/unsichtbaricon.svg";

const props = defineProps<{
  label: string;
  modelValue: string;
  id: string;
  type?: string;
  placeholder?: string;
  disableFutureDates?: boolean;
  allowOnlyFutureDates?: boolean;
}>();

const emit = defineEmits(["update:modelValue"]);

const showPassword = ref(false);
const togglePassword = () => {
  showPassword.value = !showPassword.value;
};
</script>

<template>
  <div class="mb-4 relative">
    <label :for="id" class="block text-[#472402] font-medium mb-1 text-left">
      {{ label }}
    </label>

    <div class="relative">
      <!-- If textarea -->
      <textarea
          v-if="type === 'textarea'"
          :id="id"
          :placeholder="placeholder"
          :value="modelValue"
          class="bg-white w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[#FCC1DB] custom-placeholder text-left resize-y"
          @input="$emit('update:modelValue', $event.target.value)"
      ></textarea>

      <!-- If date -->
      <Datepicker
          v-else-if="type === 'date'"
          v-slot="{ inputValue, inputEvents, togglePopover }"
          :enable-time-picker="false" :format="'dd.MM.yyyy'"
          :locale="'de'"
          :max-date="disableFutureDates ? new Date() : undefined"
          :min-date="allowOnlyFutureDates ? new Date() : undefined"
          :model-value="modelValue"
          @update:model-value="$emit('update:modelValue', new Date($event).toISOString().slice(0, 10))"
      >
        <div class="relative">
          <input
              :placeholder="placeholder"
              :value="inputValue"
              class="bg-white w-full px-4 py-2 pr-12 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[#FCC1DB] custom-placeholder text-left"
              type="text"
              v-on="inputEvents"
          />

          <button
              class="absolute inset-y-0 right-3 flex items-center text-gray-500 hover:text-gray-700"
              type="button"
              @click="togglePopover"
          >
            <img
                alt="Kalender öffnen"
                class="w-5 h-5"
                src="../assets/icons/kalendericon.svg"
            />
          </button>
        </div>
      </Datepicker>

      <!-- Everything else -->
      <input
          v-else
          :id="id"
          :placeholder="placeholder"
          :type="type === 'password' && showPassword ? 'text' : type"
          :value="modelValue"
          class="bg-white w-full px-4 py-2 pr-12 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[#FCC1DB] custom-placeholder  text-left"
          @input="$emit('update:modelValue', $event.target.value)"
      />

      <!-- Password toggle -->
      <button
          v-if="type === 'password'"
          class="absolute inset-y-0 right-3 flex items-center text-gray-500 hover:text-gray-700"
          type="button"
          @click="togglePassword"
      >
        <img
            :alt="showPassword ? 'verbergen' : 'anzeigen'"
            :src="showPassword ? unsichtbaricon : sichtbaricon"
            class="w-5 h-5"
        />
      </button>
    </div>
  </div>
</template>

<style scoped>
.custom-placeholder::placeholder {
  color: #b3c7ca;
  opacity: 1;
}
</style>