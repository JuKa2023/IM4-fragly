<script setup></script>

<template>
  <div
    class="bg-[#ffebd2] min-h-screen p-6 flex flex-col justify-between rounded-3xl w-max-md m-2"
  >
    <div class="flex justify-between space-x-4 mb-8">
      <RouterLink class="btn btn-sm btn-primary" to="/gruppen">
        Meine Gruppen
      </RouterLink>
      <RouterLink class="btn btn-sm btn-secondary" :to="{ name: 'steckbriefMe' }">
        Mein Steckbrief
      </RouterLink>
    </div>

    <div class="text-center space-y-6">
      <h1 class="text-3xl font-bold leading-snug">
        Gemeinsam mehr <br />
        übereinander <br />
        erfahren
      </h1>
      <p class="text-xl">
        Erstelle deinen persönlichen Steckbrief, beantworte Fragen aus
        verschiedenen Kategorien und entdecke, was dich mit Familie, Freunden
        oder Gästen verbindet. Ganz einfach in Gruppen – zum Beispiel bei
        Hochzeiten, Geburtstagen oder in der Familie.
      </p>
      <p class="text-xl font-semibold">
        Gruppe erstellen. Fragen beantworten. Gemeinsam lachen.
      </p>
    </div>

    <div class="mt-8 text-center">
      <RouterLink class="btn btn-sm btn-primary" :to="{name: 'createGroup'}">
        Gruppe erstellen
      </RouterLink>
    </div>
  </div>
</template>
