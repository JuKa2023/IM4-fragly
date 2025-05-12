<script setup lang="ts">
import HelloWorld from './components/HelloWorld.vue'
import { ref, onMounted } from 'vue'
import Header from './components/Header.vue'
import Login from './components/Login.vue'


interface User {
  id: number;
  name: string;
  email: string;
}

const users = ref<User[]>([]);

onMounted(async () => {
  try {
    const response = await fetch("/api?resource=users");
    users.value = await response.json();
  } catch (error) {
    console.error('Error fetching users:', error);
  }
});
</script>

<template>
    <Header />
    <Login />
  
  <div class="users">
    <h2>Users</h2>
    <ul>
      <li v-for="user in users" :key="user.id">
        {{ user.name }} ({{ user.email }})
      </li>
    </ul>
  </div>
</template>

<style scoped>

.users {
  margin-top: 2em;
  padding: 1em;
}

.users ul {
  list-style: none;
  padding: 0;
}

.users li {
  padding: 0.5em;
  margin: 0.5em 0;
  background: #f5f5f5;
  border-radius: 4px;
}
</style>
