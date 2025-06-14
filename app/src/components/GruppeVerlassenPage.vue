<script setup lang="ts">
import { ref, computed, onMounted } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { toast } from "vue-sonner";

import BaseInput from './BaseInput.vue'

const route = useRoute()
const router = useRouter()
const groupId = Number(route.params.id)
const groupName = ref('')
const inputName = ref('')

const isMatch = computed(() => inputName.value.trim() === groupName.value)

onMounted(async () => {
  try {
    const res = await fetch("/api/get_group_members.php", {
      method: "POST",
      credentials: "include",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({ gruppe_id: groupId }),
    })

    if (res.status === 401) {
      router.push("/login")
      return
    }

    const data = await res.json()
    if (res.status === 200 && data.length > 0) {
      groupName.value = data[0].name
    } else {
      router.push("/gruppen")
    }
  } catch (error) {
    console.error("Error loading group:", error)
    router.push("/gruppen")
  }
})

async function onLeave() {
  if (!isMatch.value) return

  try {
    const res = await fetch("/api/gruppe_verlassen.php", {
      method: "POST",
      credentials: "include",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({ gruppe_id: groupId }),
    })

    if (res.ok) {
      router.push("/gruppen")
    } else {
      toast.success("Fehler beim Verlassen der Gruppe")
    }
  } catch (error) {
    console.error("Error leaving group:", error)
    toast.error("Fehler beim Verlassen der Gruppe")
  }
}
</script>

<template>
  <div class="card-default">
    <form @submit.prevent="onLeave" class="flex flex-col gap-4">

      <h1 class="mb-1">
        Bist du sicher, dass du die Gruppe verlassen möchtest?
      </h1>

      <p class="mb-6">
        Bestätige, dass du die Gruppe verlassen möchtest, durch die Eingabe
        der Gruppenbezeichnung
        <span class="font-medium">{{ groupName }}</span>.
      </p>

      <BaseInput
          v-model="inputName"
          id="confirm-group-name"
          label="Gruppenbezeichnung"
          placeholder="Gruppenbezeichnung"
          type="text"
          required
      />

      <div class="flex justify-between space-x-4 mt-4">
        <button
            type="button"
            @click="$router.back()"
            class="btn btn-sm btn-primary"
        >
          Abbrechen
        </button>

        <button
            type="submit"
            :disabled="!isMatch"
            class="btn btn-sm btn-secondary"
        >
          Gruppe verlassen
        </button>
      </div>
    </form>
  </div>
</template>