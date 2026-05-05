<script setup>
import { Link, usePage } from "@inertiajs/vue3";
import { ref, onMounted, onUnmounted } from "vue";
import { route } from "ziggy-js";

const open = ref(false);

// toggle dropdown
function toggleMenu() {
    open.value = !open.value;
}

// close on outside click
function handleClickOutside(e) {
    if (!e.target.closest(".profile-dropdown")) {
        open.value = false;
    }
}

onMounted(() => {
    document.addEventListener("click", handleClickOutside);
});

onUnmounted(() => {
    document.removeEventListener("click", handleClickOutside);
});

const isDark = ref(false);

// apply theme
function applyTheme() {
    if (isDark.value) {
        document.documentElement.classList.add("dark");
        localStorage.setItem("theme", "dark");
    } else {
        document.documentElement.classList.remove("dark");
        localStorage.setItem("theme", "light");
    }
}

// toggle button
function toggleTheme() {
    isDark.value = !isDark.value;
    applyTheme();
}

// load saved theme
onMounted(() => {
    const saved = localStorage.getItem("theme");

    if (saved === "dark") {
        isDark.value = true;
    } else {
        isDark.value = false;
    }

    applyTheme();
});

const props = defineProps({
    auth: Object,
});
const page = usePage();
const auth = props.auth || page.props.auth || {};
</script>

<template>
    <div
        class="flex justify-between bg-indigo-800 py-4 px-10 items-center border-b-2"
    >
        <div>
            <Link :href="route('admin.dashboard')"
                ><img
                    :src="`/storage/${$page.props.site?.logo}`"
                    class="h-12"
                    alt="ComitsBD Admin"
            /></Link>
        </div>
        <div class="flex gap-10 items-center">
            <h2 class="text-lg underline font-bold text-slate-200">
                <a :href="route('home')" target="_blank">Visit Site</a>
            </h2>
            <button
                @click="toggleTheme"
                class="border rounded-full h-12 w-12 border-white text-white flex items-center justify-center gap-2"
            >
                <!-- Sun (Light mode) -->
                <i v-if="!isDark" class="fa-regular fa-sun text-yellow-400"></i>

                <!-- Moon (Dark mode) -->
                <i v-else class="fa-regular fa-moon text-gray-200"></i>
            </button>
            <div class="relative profile-dropdown">
                <!-- USER ICON -->
                <div
                    @click="toggleMenu"
                    class="flex gap-3 items-center text-xl text-white"
                >
                    <span class="cursor-pointer">{{ auth.user?.name }}</span>
                    <div
                        class="h-12 w-12 rounded-full flex justify-center items-center border border-white text-white cursor-pointer"
                    >
                        <img
                            v-if="auth.user?.image"
                            :src="`/storage/${auth.user?.image}`"
                            class="w-full rounded-full"
                        />
                        <i v-else class="fa-regular fa-user text-2xl"></i>
                    </div>
                </div>

                <!-- DROPDOWN -->
                <ul
                    v-show="open"
                    class="absolute right-0 mt-2 w-48 bg-white text-black shadow-lg rounded-md overflow-hidden"
                >
                    <li>
                        <Link
                            :href="route('admin.profile')"
                            class="p-3 w-full block hover:bg-gray-100"
                            >Profile</Link
                        >
                    </li>

                    <li>
                        <Link
                            :href="route('admin.logout')"
                            method="post"
                            class="p-3 w-full block text-left hover:bg-gray-100 text-red-500"
                            >Logout</Link
                        >
                    </li>
                </ul>
            </div>
        </div>
    </div>
</template>

<style scoped>
li {
    font-weight: 600;
}
</style>
