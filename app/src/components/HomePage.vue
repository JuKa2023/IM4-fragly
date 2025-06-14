<script setup></script>

<template>
  <div
      class="card-default"
  >
    <div class="flex justify-between space-x-4 mb-8">
      <RouterLink class="btn btn-sm btn-primary" to="/gruppen">
        Meine Gruppen
      </RouterLink>
      <RouterLink :to="{ name: 'steckbriefMe' }" class="btn btn-sm btn-secondary">
        Mein Steckbrief
      </RouterLink>
    </div>

    <div class="text-center space-y-6">
      <h1>
        Gemeinsam mehr übereinander erfahren
      </h1>
      <p>
        Erstelle deinen persönlichen Steckbrief, beantworte Fragen aus
        verschiedenen Kategorien und entdecke, was dich mit Familie, Freunden
        oder Gästen verbindet. Ganz einfach in Gruppen – zum Beispiel bei
        Hochzeiten, Geburtstagen oder in der Familie.
      </p>
      <p class="p-bold">
        Gruppe erstellen. Fragen beantworten. Gemeinsam lachen.
      </p>
    </div>

    <div class="mt-8 text-right">
      <RouterLink :to="{name: 'createGroup'}" class="btn btn-sm btn-primary">
        Gruppe erstellen
      </RouterLink>
    </div>
  </div>
</template>
