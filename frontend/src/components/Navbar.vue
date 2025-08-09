<template>
    <header
        class="flex items-center justify-between h-14 shadow bg-white px-3 py-2"
    >
        <div class="flex items-center space-x-4">
            <button
                @click="emit('toggle-sidebar')"
                class="grid place-items-center p-2 rounded transition-colors text-gray-700 hover:bg-black/10 hover:cursor-pointer"
            >
                <Bars3Icon class="w-6 h-6" />
            </button>
            <LogoPSL class="w-[200px] h-[50px]" />
        </div>

        <Menu
            as="div"
            class="relative inline-block text-left hover:cursor-pointer"
        >
            <MenuButton class="flex items-center hover:cursor-pointer">
                <img
                    src="https://randomuser.me/api/portraits/men/22.jpg"
                    alt=""
                    class="rounded-full w-8 mr-2 hover:cursor-pointer"
                />
                <p>{{ currentUser.name }}</p>
                <ChevronDownIcon
                    class="-mr-1 ml-2 h-5 w-5 text-indigo-200 hover:text-indigo-100"
                    aria-hidden="true"
                />
            </MenuButton>

            <transition
                enter-active-class="transition duration-100 ease-out"
                enter-from-class="transform scale-95 opacity-0"
                enter-to-class="transform scale-100 opacity-100"
                leave-active-class="transition duration-75 ease-in"
                leave-from-class="transform scale-100 opacity-100"
                leave-to-class="transform scale-95 opacity-0"
            >
                <MenuItems
                    class="absolute right-0 mt-2 w-36 origin-top-right divide-y divide-gray-100 rounded-md bg-white shadow-lg ring-1 ring-black/5 focus:outline-none"
                >
                    <div class="px-1 py-1">
                        <MenuItem v-slot="{ active }">
                            <button
                                :class="[
                                    active
                                        ? 'bg-indigo-600 text-white'
                                        : 'text-gray-900',
                                    'group flex w-full items-center rounded-md px-2 py-2 text-sm',
                                ]"
                            >
                                <UserIcon
                                    class="mr-2 h-5 w-5 text-indigo-400"
                                    aria-hidden="true"
                                />
                                Profile
                            </button>
                        </MenuItem>
                        <MenuItem v-slot="{ active }">
                            <button
                                @click="logout"
                                :class="[
                                    active
                                        ? 'bg-indigo-600 text-white'
                                        : 'text-gray-900',
                                    'group flex w-full items-center rounded-md px-2 py-2 text-sm',
                                ]"
                            >
                                <ArrowRightStartOnRectangleIcon
                                    class="mr-2 h-5 w-5 text-indigo-400"
                                    aria-hidden="true"
                                />
                                Logout
                            </button>
                        </MenuItem>
                    </div>
                </MenuItems>
            </transition>
        </Menu>
    </header>
</template>

<script setup>
import {
    Bars3Icon,
    ArrowRightStartOnRectangleIcon,
    UserIcon,
} from "@heroicons/vue/24/outline";
import { Menu, MenuButton, MenuItems, MenuItem } from "@headlessui/vue";
import { ChevronDownIcon } from "@heroicons/vue/20/solid";
import { useAuthStore } from "@/stores/auth";
import { storeToRefs } from "pinia";
import { computed } from "vue";
import LogoPSL from "./core/LogoPSL.vue";

const { user } = storeToRefs(useAuthStore());
const authStore = useAuthStore();

const emit = defineEmits(["toggle-sidebar"]);

const currentUser = computed(() => user.value);

function logout() {
    authStore.logout();
}
</script>
