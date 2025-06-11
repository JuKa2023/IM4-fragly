<script setup lang="ts">
import 'vue-sonner/style.css';
import {toast, Toaster} from "vue-sonner";
import {onBeforeUnmount, onMounted, ref} from "vue";
import logo from "./assets/logo.svg";
import {useRouter} from "vue-router";
import gruppeicon from "./assets/gruppeicon.svg";

const dropdownOpen = ref(false);
const dropdownRef = ref<HTMLElement | null>(null);

onMounted(async () => {
  document.addEventListener("click", handleClickOutside);
});

onBeforeUnmount(() => {
  document.removeEventListener("click", handleClickOutside);
});

const toggleDropdown = () => {
  dropdownOpen.value = !dropdownOpen.value;
};

const handleClickOutside = (event: MouseEvent) => {
  if (dropdownRef.value && !dropdownRef.value.contains(event.target as Node)) {
    dropdownOpen.value = false;
  }
};

const router = useRouter();

const logout = async () => {
  try {
    const response = await fetch("api/logout.php", {
      method: "GET",
      credentials: "include",
    });
    const result = await response.json();

    if (result.status === "success") {
      toast.success("Erfolgreich abgemeldet!");
      localStorage.removeItem("check");

      await router.push("/");
    } else {
      toast.error("Logout fehlgeschlagen. Bitte versuche es erneut.");
    }
  } catch (error) {
    console.error("Logout error:", error);
  }
};

const isLoggedIn = ref(!!localStorage.getItem("check"));
setInterval(() => {
  isLoggedIn.value = !!localStorage.getItem("check");
}, 100);

</script>

<template>
  <Toaster position="top-right"/>
  <div class="flex flex-col h-screen w-full">
    <header class="bg-orange-300 py-5 z-20">
      <div class="mx-auto max-w-xl h-full flex items-center">
        <nav class="flex items-center justify-between w-full h-full">
          <RouterLink class="p-2 cursor-pointer iconHeader w-auto h-full" to="/">
            <img :src="logo" alt="Logo" class="h-full"/>
          </RouterLink>

          <!-- Right User Dropdown -->
          <div v-if="isLoggedIn" class="relative" ref="dropdownRef">
            <div
                @click="toggleDropdown"
                class="p-2 h-full w-auto cursor-pointer iconHeader"
            >
              <img
                  :src="gruppeicon"
                  alt="Nutzer"
                  class="cursor-pointer iconHeader"
              />
            </div>

            <!-- Dropdown Menu with transition -->
            <transition name="dropdown">
              <div
                  v-show="dropdownOpen"
                  class="absolute right-0 w-56 bg-orange-300 shadow-md overflow-hidden z-30"
              >
                <RouterLink
                    class="block px-4 py-2 text-gray-800 hover:bg-[#FCC1DB] transition-colors"
                    to="/benutzerdatenbearbeiten"
                >Benutzerdaten bearbeiten
                </RouterLink
                >

                <RouterLink
                    class="block px-4 py-2 text-gray-800 hover:bg-[#FCC1DB] transition-colors"
                    to="/"
                    @click.prevent="logout"
                >Abmelden
                </RouterLink
                >
              </div>
            </transition>
          </div>
        </nav>
      </div>
    </header>
    <main class="flex-1 overflow-yauto p-5">
      <div class="page-wrapper">
        <router-view></router-view>
      </div>
    </main>

    <footer class="bg-orange-300 py-5 text-center">
      <div class="mx-auto max-w-xl h-full flex items-center justify-center">
        <p class="text-brown text-sm">© 2025 Fragly</p>
      </div>
    </footer>
  </div>
</template>
