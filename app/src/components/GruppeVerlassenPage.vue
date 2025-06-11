<script setup lang="ts">
import { ref, computed, onMounted } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { toast } from "vue-sonner";

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
      groupName.value = data[0].Gruppe_Name
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
  <div class="bg-[#ffebd2] min-h-screen p-6 rounded-3xl w-max-md m-2">
    <button
      @click="$router.back()"
      class="absolute top-4 right-4 w-8 h-8 rounded-full btn btn-sm btn-primary flex items-center justify-center"
    >
      ×
    </button>

    <div class="bg-white rounded-2xl p-6 max-w-sm w-full text-center">
      <h1 class="text-xl font-semibold text-gray-800 mb-4">
        Bist du sicher, dass du die Gruppe verlassen möchtest?
      </h1>
      <p class="text-sm text-gray-600 mb-6">
        Bestätige, dass du die Gruppe verlassen möchtest durch die Eingabe der Gruppenbezeichnung <span class="font-medium">{{ groupName }}</span>.
      </p>
      <input
        v-model="inputName"
        type="text"
        placeholder="Gruppenbezeichnung"
        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-pink-300 mb-4"
      />
      <div class="flex gap-4">
        <button
          @click="$router.back()"
          class="btn btn-sm btn-primary"
        >
          Abbrechen
        </button>
        <button
          :disabled="!isMatch"
          @click="onLeave"
          class="btn btn-sm btn-secondary"
        >
          Gruppe verlassen
        </button>
      </div>
    </div>
  </div>
</template>