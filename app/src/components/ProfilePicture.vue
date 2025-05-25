<template>
  <div class="flex flex-col items-center gap-4">
    <div class="relative group">
      <img
        v-if="currentUrl"
        :src="'/api' + currentUrl + cacheBuster"
        alt="Profile picture"
        class="w-32 h-32 rounded-full object-cover shadow"
      />
      <div
        v-if="isEditable"
        class="absolute inset-0 bg-black/50 rounded-full flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity cursor-pointer"
        @click="openFileDialog"
      >
        <span class="text-white text-sm">Change</span>
      </div>
    </div>

    <input
      ref="fileInput"
      type="file"
      accept="image/*"
      class="hidden"
      @change="handleFileChange"
    />

    <button
      v-if="isEditable && !uploading"
      class="btn btn-primary"
      @click="openFileDialog"
    >
      Upload new picture
    </button>

    <p v-if="uploading" class="text-sm text-gray-600">
      Uploading… {{ progress }}%
    </p>

    <p v-if="error" class="text-sm text-red-500">{{ error }}</p>
  </div>
</template>

<script setup lang="ts">
import { ref, watch, computed } from "vue";

interface Props {
  /** The url of the current profile picture (can be null). */
  initialUrl: string | null;
  /** Whether the component should allow uploading/ replacing the picture. */
  editable?: boolean;
  /** API endpoint that processes the upload. */
  uploadEndpoint?: string;
}

const props = defineProps<Props>();

const emit = defineEmits<{
  (e: "uploaded", url: string): void;
}>();

const currentUrl = ref<string | null>(props.initialUrl);
const fileInput = ref<HTMLInputElement | null>(null);
const uploading = ref(false);
const progress = ref(0);
const error = ref<string | null>(null);

// give browsers a hint to break cache when image changes
const cacheBuster = computed(() =>
  currentUrl.value ? `?v=${Date.now()}` : "",
);

const isEditable = computed(() => props.editable ?? false);

watch(
  () => props.initialUrl,
  (newVal) => {
    currentUrl.value = newVal;
  },
);

/** Programmatically open the hidden file input */
function openFileDialog() {
  fileInput.value?.click();
}

function handleFileChange(evt: Event) {
  const files = (evt.target as HTMLInputElement).files;
  if (!files || files.length === 0) return;
  const file = files[0];

  if (!file.type.startsWith("image/")) {
    error.value = "Please select an image file.";
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
              error.value = response.error ?? "Upload failed.";
            }
          } catch (e) {
            error.value = "Server returned an invalid response.";
          }
        } else {
          error.value = `Upload failed with status ${xhr.status}.`;
        }
        // reset file input so same file can be selected again if needed
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

<style scoped>
.btn {
}
</style>
