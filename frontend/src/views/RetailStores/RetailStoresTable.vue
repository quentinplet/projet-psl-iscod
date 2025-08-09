<template>
    <div class="bg-white p-4 rounded-lg shadow animate-fade-in-down">
        <div class="flex justify-between border-b-2 pb-3">
            <SelectPerPage v-model="perPage" @change="handleSelectChange" />
            <SearchInput v-model="search" @change="handleSelectChange" />
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
                            @click="sortRetailStores"
                            >ID</TableHeaderCell
                        >
                        <TableHeaderCell
                            class="border-b-2 p-2 text-left"
                            field=""
                            :sort-field="sortField"
                            :sort-direction="sortDirection"
                            @click="sortRetailStores"
                            >Nom</TableHeaderCell
                        >
                        <TableHeaderCell
                            class="border-b-2 p-2 text-left"
                            field="price"
                            :sort-field="sortField"
                            :sort-direction="sortDirection"
                            >Téléphone</TableHeaderCell
                        >
                        <TableHeaderCell
                            class="border-b-2 p-2 text-left"
                            field="price"
                            :sort-field="sortField"
                            :sort-direction="sortDirection"
                            >Email</TableHeaderCell
                        >
                        <TableHeaderCell
                            class="border-b-2 p-2 text-left"
                            field="quantity"
                            :sort-field="sortField"
                            :sort-direction="sortDirection"
                            >Adresse</TableHeaderCell
                        >
                        <TableHeaderCell
                            class="border-b-2 p-2 text-left"
                            field="updated_at"
                            :sort-field="sortField"
                            :sort-direction="sortDirection"
                            @click="sortRetailStores"
                        >
                            Date de mise à jour
                        </TableHeaderCell>
                        <TableHeaderCell field="actions">
                            Actions
                        </TableHeaderCell>
                    </tr>
                </thead>
                <tbody v-if="isLoading">
                    <tr>
                        <td colspan="6">
                            <Spinner v-if="isLoading" class="my-4" />
                        </td>
                    </tr>
                </tbody>
                <tbody v-else-if="!retailStoresData.data?.length">
                    <tr>
                        <td colspan="9" class="text-center p-4">
                            Pas de points de ventes trouvés.
                        </td>
                    </tr>
                </tbody>
                <tbody v-else>
                    <tr
                        v-for="(retailStore, index) in retailStoresData.data"
                        :key="retailStore.id"
                    >
                        <td class="border-b p-2">{{ retailStore.id }}</td>
                        <td
                            class="border-b p-2 max-w-[200px] whitespace-nowrap overflow-hidden text-ellipsis"
                        >
                            {{ retailStore.name }}
                        </td>
                        <td class="border-b p-2">
                            {{ retailStore.phone }}
                        </td>
                        <td class="border-b p-2">
                            {{ retailStore.email }}
                        </td>
                        <td class="border-b p-2">
                            {{ retailStore.address.street }},
                            {{ retailStore.address.city }}
                            {{ retailStore.address.state }}
                        </td>
                        <td class="border-b p-2">
                            {{ retailStore.updated_at }}
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
                                                <RouterLink
                                                    :to="{
                                                        name: 'app.retailStores.show',
                                                        params: {
                                                            id: retailStore.id,
                                                        },
                                                    }"
                                                    :class="[
                                                        active
                                                            ? 'bg-indigo-600 text-white'
                                                            : 'text-gray-900',
                                                        'group flex w-full items-center rounded-md px-2 py-2 text-sm',
                                                    ]"
                                                >
                                                    <EyeIcon
                                                        :active="active"
                                                        class="mr-2 h-5 w-5 text-indigo-400"
                                                        aria-hidden="true"
                                                    />
                                                    Détails
                                                </RouterLink>
                                            </MenuItem>
                                            <MenuItem v-slot="{ active }">
                                                <RouterLink
                                                    :to="{
                                                        name: 'app.retailStores.edit',
                                                        params: {
                                                            id: retailStore.id,
                                                        },
                                                    }"
                                                    :class="[
                                                        active
                                                            ? 'bg-indigo-600 text-white'
                                                            : 'text-gray-900',
                                                        'group flex w-full items-center rounded-md px-2 py-2 text-sm',
                                                    ]"
                                                >
                                                    <PencilIcon
                                                        :active="active"
                                                        class="mr-2 h-5 w-5 text-indigo-400"
                                                        aria-hidden="true"
                                                    />
                                                    Modifier
                                                </RouterLink>
                                            </MenuItem>
                                            <MenuItem v-slot="{ active }">
                                                <button
                                                    :class="[
                                                        active
                                                            ? 'bg-indigo-600 text-white'
                                                            : 'text-gray-900',
                                                        'group flex w-full items-center rounded-md px-2 py-2 text-sm',
                                                    ]"
                                                    @click="
                                                        deleteRetailStore(
                                                            retailStore
                                                        )
                                                    "
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
                v-if="retailStoresData?.meta"
                class="flex justify-between items-center mt-5"
            >
                <Pagination
                    :data="retailStoresData?.meta"
                    :entity-name="'points de vente'"
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
import { PRODUCTS_PER_PAGE } from "@/constant";
import {
    ArrowRightStartOnRectangleIcon,
    EyeIcon,
} from "@heroicons/vue/24/outline";
import TableHeaderCell from "@/components/core/Table/TableHeaderCell.vue";

import { Menu, MenuButton, MenuItems, MenuItem } from "@headlessui/vue";

import { EllipsisVerticalIcon } from "@heroicons/vue/20/solid";

import { PencilIcon, TrashIcon } from "@heroicons/vue/24/outline";
import { useRetailStoresStore } from "@/stores/retailStores";
import SelectPerPage from "@/components/core/Table/SelectPerPage.vue";
import SearchInput from "@/components/core/Table/SearchInput.vue";
import Pagination from "@/components/core/Pagination.vue";

const emit = defineEmits(["clickEdit"]);

const retailStoresStore = useRetailStoresStore();
const {
    data: retailStoresData,
    loading: isLoading,
    search,
    sortField,
    sortDirection,
    perPage,
} = storeToRefs(retailStoresStore);

onMounted(async () => {
    await retailStoresStore.fetchAll();
});

async function handleSelectChange() {
    await retailStoresStore.fetchAll();
}

async function getForPage(url) {
    await retailStoresStore.fetchAll({
        url: url,
    });
}

async function sortRetailStores(field) {
    if (sortField.value === field) {
        sortDirection.value = sortDirection.value === "asc" ? "desc" : "asc";
    } else {
        sortField.value = field;
        sortDirection.value = "asc";
    }
    await retailStoresStore.fetchAll();
}

async function deleteRetailStore(retailStore) {
    if (!confirm("Are you sure you want to delete this retailStore?")) return;

    await retailStoresStore.delete(retailStore.id);
    await retailStoresStore.fetchAll();
}
</script>

<style lang="scss" scoped></style>
