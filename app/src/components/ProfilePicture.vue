<script lang="ts" setup>
import {computed, ref, watch} from "vue";
import cameraIcon from "../assets/Kameraicon.svg";
import gruppeIcon from "../assets/gruppeicon.svg";

defineOptions({ inheritAttrs: false });

interface Props {
  initialUrl: string | null;
  editable?: boolean;
  uploadEndpoint?: string;
}

const props = defineProps<Props>();

const emit = defineEmits<{
  (e: "uploaded", url: string): void;
}>();


const currentUrl = ref<string | null>(props.initialUrl ?? null);
const fileInput = ref<HTMLInputElement | null>(null);
const uploading = ref(false);
const progress = ref(0);
const error = ref<string | null>(null);

const cacheBuster = computed(() =>
    currentUrl.value ? `?v=${Date.now()}` : "",
);

const isEditable = computed(() => props.editable ?? false);

watch(
    () => props.initialUrl,
    (newVal) => {
      currentUrl.value = newVal ? newVal : gruppeIcon;
    },
);

function openFileDialog() {
  fileInput.value?.click();
}

function handleFileChange(evt: Event) {
  const files = (evt.target as HTMLInputElement).files;
  if (!files || files.length === 0) return;
  const file = files[0];

  if (!file.type.startsWith("image/")) {
    error.value = "Bitte eine Bilddatei auswählen.";
    return;
  }

  uploadFile(file);
}

async function uploadFile(file: File) {
  uploading.value = true;
  error.value = null;
  progress.value = 0;

  try {
    const formData = new FormData();
    formData.append("profile_picture", file);

    const xhr = new XMLHttpRequest();
    xhr.open(
        "POST",
        props.uploadEndpoint ?? "/api/upload_profile_picture.php",
        true,
    );

    xhr.upload.onprogress = (evt) => {
      if (evt.lengthComputable) {
        progress.value = Math.round((evt.loaded / evt.total) * 100);
      }
    };

    xhr.onreadystatechange = () => {
      if (xhr.readyState === XMLHttpRequest.DONE) {
        uploading.value = false;
        if (xhr.status === 200) {
          try {
            const response = JSON.parse(xhr.responseText);
            if (response.success && response.url) {
              currentUrl.value = response.url;
              emit("uploaded", response.url);
            } else {
              error.value = response.error ?? "Hochladen fehlgeschlagen.";
            }
          } catch (e) {
            error.value = "Server antwort konnte nicht verarbeitet werden.";
          }
        } else {
          error.value = `Hochladen fehlgeschlagen.`;
        }
        if (fileInput.value) fileInput.value.value = "";
      }
    };

    xhr.send(formData);
  } catch (e) {
    uploading.value = false;
    error.value = "Unexpected error occurred.";
  }
}
</script>

<template>
  <div class="flex flex-col items-center">
    <div class="relative inline-block">
      <img
          v-if="currentUrl"
          v-bind="$attrs"
          :src="'/api' + currentUrl + cacheBuster"
          alt="Profilbild"
          class="rounded-full object-cover
               ring-4 ring-[#472402] ring-offset-2 shadow-md"
      />
      <img
          v-else
          v-bind="$attrs"
          :src="gruppeIcon"
          alt="Profilbild"
          class="rounded-full object-cover
               ring-4 ring-[#472402] ring-offset-2 shadow-md"
      />

      <div
          v-if="isEditable && !uploading"
          class="absolute bottom-0 right-0
               translate-x-1/3 translate-y-1/3
               w-12 h-12 bg-pink-300 rounded-full shadow-xl
               flex items-center justify-center cursor-pointer group"
          @click="openFileDialog"
      >
        <img
            :src="cameraIcon"
            alt="Neues Bild auswählen"
            class="w-6 h-6 select-none transition-transform group-hover:scale-105"
        />
      </div>

      <div
          v-if="isEditable"
          class="absolute inset-0 rounded-full
               bg-black/50 flex items-center justify-center
               opacity-0 hover:opacity-100 transition-opacity cursor-pointer"
          @click="openFileDialog"
      >
        <span class="text-white text-base">Change</span>
      </div>
    </div>

    <input
        ref="fileInput"
        class="hidden"
        type="file"
        accept="image/*"
        @change="handleFileChange"
    />

    <p v-if="uploading" class="mt-3 text-sm text-gray-600">
      Uploading… {{ progress }}%
    </p>
    <p v-if="error" class="mt-3 text-sm text-red-500">
      {{ error }}
    </p>
  </div>
</template>