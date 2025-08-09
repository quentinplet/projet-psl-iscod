<template>
    <div class="bg-white p-4 rounded-lg shadow animate-fade-in-down">
        <div class="flex justify-between border-b-2 pb-3">
            <div class="flex items-center gap-7">
                <SelectPerPage v-model="perPage" @change="handleSelectChange" />
                <div>
                    <CustomInput
                        v-if="currentUser.role === 'admin'"
                        type="select"
                        :select-options="selectOpt"
                        v-model="roleFilter"
                        @change="handleSelectChange"
                    />
                </div>
            </div>
            <div>
                <SearchInput v-model="search" @change="handleSelectChange" />
            </div>
        </div>
        <div>
            <table class="table-auto w-full">
                <thead>
                    <tr>
                        <TableHeaderCell
                            class="p-2 text-left"
                            field="id"
                            :sort-field="sortField"
                            :sort-direction="sortDirection"
                            @click="sortUsers"
                            >ID</TableHeaderCell
                        >
                        <TableHeaderCell
                            class="border-b-2 p-2 text-left"
                            field="name"
                            :sort-field="sortField"
                            :sort-direction="sortDirection"
                            @click="sortUsers"
                            >Name</TableHeaderCell
                        >
                        <TableHeaderCell
                            class="border-b-2 p-2 text-left"
                            field="email"
                            :sort-field="sortField"
                            :sort-direction="sortDirection"
                            @click="sortUsers"
                            >Email</TableHeaderCell
                        >
                        <TableHeaderCell
                            v-if="currentUser.role === 'admin'"
                            class="border-b-2 p-2 text-left"
                            field="role"
                            :sort-field="sortField"
                            :sort-direction="sortDirection"
                            @click="sortUsers"
                            >Rôle</TableHeaderCell
                        >
                        <TableHeaderCell
                            class="border-b-2 p-2 text-left"
                            field="created_at"
                            :sort-field="sortField"
                            :sort-direction="sortDirection"
                            @click="sortUsers"
                        >
                            Created At
                        </TableHeaderCell>
                        <TableHeaderCell field="actions">
                            Actions
                        </TableHeaderCell>
                    </tr>
                </thead>
                <tbody v-if="loading">
                    <tr>
                        <td colspan="6">
                            <Spinner v-if="loading" class="my-4" />
                        </td>
                    </tr>
                </tbody>
                <tbody v-else-if="!usersData?.data?.length">
                    <tr>
                        <td colspan="9" class="text-center p-4">
                            Pas d'utilisateurs trouvés.
                        </td>
                    </tr>
                </tbody>
                <tbody v-else>
                    <tr v-for="(user, index) in usersData?.data" :key="user.id">
                        <td class="border-b p-2">{{ user.id }}</td>
                        <td
                            class="border-b p-2 max-w-[200px] whitespace-nowrap overflow-hidden text-ellipsis"
                        >
                            {{ user.name }}
                        </td>
                        <td class="border-b p-2">
                            {{ user.email }}
                        </td>
                        <td class="border-b p-2">
                            {{ user.role }}
                        </td>
                        <td class="border-b p-2">
                            {{ user.created_at }}
                        </td>
                        <td class="border-b p-2">
                            <Menu
                                as="div"
                                class="relative inline-block text-left"
                            >
                                <div>
                                    <MenuButton
                                        class="inline-flex items-center justify-center w-full rounded-full h-10 bg-black/0 text-sm font-medium text-white hover:cursor-pointer hover:bg-black/10 hover:bg-opacity-5 focus:bg-opacity-5 focus:outline-none focus-visible:ring-2 focus-visible:ring-white focus-visible:ring-opacity-75"
                                    >
                                        <EllipsisVerticalIcon
                                            class="h-6 w-6 text-indigo-500"
                                            aria-hidden="true"
                                        />
                                    </MenuButton>
                                </div>
                                <transition
                                    enter-active-class="transition duration-100 ease-out"
                                    enter-from-class="transform scale-95 opacity-0"
                                    enter-to-class="transform scale-100 opacity-100"
                                    leave-active-class="transition duration-75 ease-in"
                                    leave-from-class="transform scale-100 opacity-100"
                                    leave-to-class="transform scale-95 opacity-0"
                                >
                                    <MenuItems
                                        class="absolute z-10 right-0 mt-2 w-32 origin-top-right divide-y divide-gray-100 rounded-md bg-white shadow-lg ring-1 ring-black/5 ring-opacity-5 focus:outline-none"
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
                                                    @click="editUser(user)"
                                                >
                                                    <PencilIcon
                                                        :active="active"
                                                        class="mr-2 h-5 w-5 text-indigo-400"
                                                        aria-hidden="true"
                                                    />
                                                    Modifier
                                                </button>
                                            </MenuItem>
                                            <MenuItem v-slot="{ active }">
                                                <button
                                                    :class="[
                                                        active
                                                            ? 'bg-indigo-600 text-white'
                                                            : 'text-gray-900',
                                                        'group flex w-full items-center rounded-md px-2 py-2 text-sm',
                                                    ]"
                                                    @click="deleteUser(user)"
                                                >
                                                    <TrashIcon
                                                        :active="active"
                                                        class="mr-2 h-5 w-5 text-indigo-400"
                                                        aria-hidden="true"
                                                    />
                                                    Supprimer
                                                </button>
                                            </MenuItem>
                                        </div>
                                    </MenuItems>
                                </transition>
                            </Menu>
                        </td>
                    </tr>
                </tbody>
            </table>

            <div
                v-if="usersData?.meta"
                class="flex justify-between items-center mt-5"
            >
                <Pagination
                    :data="usersData?.meta"
                    :entity-name="'fournisseurs'"
                    @page-changed="getForPage"
                />
            </div>
        </div>
    </div>
</template>

<script setup>
import Spinner from "@/components/core/Spinner.vue";
import { ref, onMounted, computed } from "vue";
import { storeToRefs } from "pinia";
import { ArrowRightStartOnRectangleIcon } from "@heroicons/vue/24/outline";
import TableHeaderCell from "@/components/core/Table/TableHeaderCell.vue";

import { Menu, MenuButton, MenuItems, MenuItem } from "@headlessui/vue";

import { EllipsisVerticalIcon } from "@heroicons/vue/20/solid";

import { PencilIcon, TrashIcon } from "@heroicons/vue/24/outline";
import { USERS_PER_PAGE } from "@/constant";
import { useUsersStore } from "@/stores/users";
import CustomInput from "@/components/core/CustomInput.vue";
import { useAuthStore } from "@/stores/auth";
import { useRetailStoresStore } from "@/stores/retailStores";
import Pagination from "@/components/core/Pagination.vue";
import SelectPerPage from "@/components/core/Table/SelectPerPage.vue";
import SearchInput from "@/components/core/Table/SearchInput.vue";

const emit = defineEmits(["clickEdit"]);

const usersStore = useUsersStore();
const authStore = useAuthStore();
const { users, usersRoles } = storeToRefs(usersStore);
const { user: currentUser } = storeToRefs(authStore);

const {
    data: usersData,
    loading: isLoading,
    search,
    sortField,
    sortDirection,
    perPage,
} = storeToRefs(usersStore);

const roleFilter = ref("");

const selectOpt = ref([]);

onMounted(async () => {
    await usersStore.fetchAll();

    if (currentUser.value.role === "admin") {
        await usersStore.getUserRoles();

        selectOpt.value = [
            { key: "", text: "Tous les utilisateurs" },
            ...usersRoles.value.map((role) => ({
                key: role,
                text: role.charAt(0).toUpperCase() + role.slice(1),
            })),
        ];
    }
});

async function handleSelectChange() {
    await usersStore.fetchAll({
        role: roleFilter.value,
    });
}

async function getForPage(url) {
    await usersStore.fetchAll({
        url: url,
    });
}

async function sortUsers(field) {
    if (sortField.value === field) {
        sortDirection.value = sortDirection.value === "asc" ? "desc" : "asc";
    } else {
        sortField.value = field;
        sortDirection.value = "asc";
    }

    await usersStore.fetchAll();
}

function editUser(user) {
    emit("clickEdit", user);
}

async function deleteUser(user) {
    if (!confirm("Êtes-vous sûr de supprimer cet utilisateur ?")) return;

    await usersStore.delete(user.id);
    await usersStore.fetchAll();
}
</script>

<style lang="scss" scoped></style>
