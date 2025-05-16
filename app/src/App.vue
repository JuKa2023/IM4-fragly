<script setup lang="ts">
import { ref, onMounted, onBeforeUnmount } from 'vue'
import logo from './assets/logo.svg'
import gruppeicon from './assets/gruppeicon.svg'
import { useRouter } from 'vue-router'

interface User {
  id: number;
  name: string;
  email: string;
}

const users = ref<User[]>([]);
const dropdownOpen = ref(false);
const dropdownRef = ref<HTMLElement | null>(null);

onMounted(async () => {
  try {
    const response = await fetch("/api?resource=users");
    users.value = await response.json();
  } catch (error) {
    console.error('Error fetching users:', error);
  }

  document.addEventListener('click', handleClickOutside);
});

onBeforeUnmount(() => {
  document.removeEventListener('click', handleClickOutside);
});

const toggleDropdown = () => {
  dropdownOpen.value = !dropdownOpen.value;
}

const handleClickOutside = (event: MouseEvent) => {
  if (
      dropdownRef.value &&
      !dropdownRef.value.contains(event.target as Node)
  ) {
    dropdownOpen.value = false;
  }
};

// Logout function

const router = useRouter();

const logout = async () => {
  try {
    const response = await fetch("api/logout.php", {
      method: "GET",
      credentials: "include", // Important for cookie-based sessions
    });

    const result = await response.json();

    if (result.status === "success") {
      // Redirect to login page after logout
      router.push("/landingpage");
    } else {
      console.error("Logout failed");
      alert("Logout failed. Please try again.");
    }
  } catch (error) {
    console.error("Logout error:", error);
    alert("Something went wrong during logout!");
  }
};
</script>

<template>
  <header class="bg-orange-300 h-16 w-full px-6 shadow-md z-20">
    <div class="mx-auto max-w-xl h-full flex items-center">
      <nav class="flex items-center justify-between w-full h-full">
        <a href="#" class="p-2 cursor-pointer iconHeader w-auto h-full">
          <img :src="logo" alt="Logo" class="h-full"/>
        </a>

        <!-- Right User Dropdown -->
        <div class="relative" ref="dropdownRef">
          <div
              @click="toggleDropdown"
              class="p-2 h-full w-auto cursor-pointer iconHeader"
          >
            <img :src="gruppeicon" alt="Nutzer" class="cursor-pointer iconHeader" />
          </div>

          <!-- Dropdown Menu with transition -->
          <transition name="dropdown">
            <div
                v-show="dropdownOpen"
                class="absolute right-0 w-56 bg-orange-300 shadow-md overflow-hidden z-30"
            >
              <RouterLink class="block px-4 py-2 text-gray-800 hover:bg-gray-100 transition-colors" to="/benutzerdatenbearbeiten">Benutzerdaten bearbeiten</RouterLink>

              <RouterLink class="block px-4 py-2 text-gray-800 hover:bg-gray-100 transition-colors" to="/landingpage" @click.prevent="logout">Abmelden</RouterLink>
            </div>
          </transition>
        </div>
      </nav>
    </div>
  </header>
    <main>
      <router-view>

      </router-view>
    </main>


<footer class="bg-orange-300 h-16 w-full px-6 shadow-inner">
  <div class="mx-auto max-w-xl h-full flex items-center justify-center">
  <p class="text-brown text-sm">
   © 2025 Fragly
  </p>
  </div>
</footer>
</template>
