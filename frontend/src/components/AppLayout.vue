<template>
    <div v-if="currentUser?.id" class="min-h-full bg-gray-200 flex">
        <Sidebar :class="{ '-ml-[200px]': !sidebarOpened }" />

        <div class="flex-1">
            <Navbar @toggle-sidebar="toggleSidebar" />

            <!-- Content -->
            <main class="p-6">
                <div class="p-4 rounded">
                    <RouterView></RouterView>
                </div>
            </main>
        </div>
    </div>
    <div v-else class="min-h-full bg-gray-200 flex items-center justify-center">
        <Spinner />
    </div>
</template>

<script setup>
import { RouterLink, RouterView } from "vue-router";
import { defineProps, ref, onMounted, onUnmounted, computed } from "vue";
import Sidebar from "./Sidebar.vue";
import TopHeader from "./Navbar.vue";
import Navbar from "./Navbar.vue";
import { useAuthStore } from "@/stores/auth";
import { storeToRefs } from "pinia";
import Spinner from "./core/Spinner.vue";

const { user } = storeToRefs(useAuthStore());

const props = defineProps({});

const sidebarOpened = ref(true);
const currentUser = computed(() => user.value);

function toggleSidebar() {
    sidebarOpened.value = !sidebarOpened.value;
}

onMounted(async () => {
    const authStore = useAuthStore();
    // const user = await authStore.getUser();
    handleSideBarOpened;
    window.addEventListener("resize", handleSideBarOpened);
});

function handleSideBarOpened() {
    sidebarOpened.value = window.outerWidth > 768;
}

onUnmounted(() => {
    window.removeEventListener("resize", handleSideBarOpened);
});
</script>
