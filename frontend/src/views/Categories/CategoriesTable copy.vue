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
                            @click="sortCategories"
                            >ID</TableHeaderCell
                        >
                        <TableHeaderCell
                            class="border-b-2 p-2 text-left"
                            field="name"
                            :sort-field="sortField"
                            :sort-direction="sortDirection"
                            @click="sortCategories"
                            >Nom</TableHeaderCell
                        >
                        <TableHeaderCell
                            class="border-b-2 p-2 text-left"
                            field="slug"
                            :sort-field="sortField"
                            :sort-direction="sortDirection"
                            @click="sortCategories"
                            >Description</TableHeaderCell
                        >
                        <TableHeaderCell
                            class="border-b-2 p-2 text-left"
                            field="active"
                            :sort-field="sortField"
                            :sort-direction="sortDirection"
                            @click="sortCategories"
                            >Active</TableHeaderCell
                        >
                        <TableHeaderCell
                            class="border-b-2 p-2 text-left"
                            field="updated_at"
                            :sort-field="sortField"
                            :sort-direction="sortDirection"
                            @click="sortCategories"
                        >
                            Modifié le
                        </TableHeaderCell>
                        <TableHeaderCell field="actions">
                            Actions
                        </TableHeaderCell>
                    </tr>
                </thead>
                <tbody v-if="loading">
                    <tr>
                        <td colspan="7">
                            <Spinner v-if="loading" class="my-4" />
                            <p v-else class="text-center py-8 text-gray-700">
                                Il n'y a pas de catégories à afficher.
                            </p>
                        </td>
                    </tr>
                </tbody>
                <tbody v-else-if="!categories?.data?.data?.length">
                    <tr>
                        <td colspan="9" class="text-center p-4">
                            Pas de catégories trouvés.
                        </td>
                    </tr>
                </tbody>
                <tbody v-else>
                    <tr
                        v-for="(category, index) in categories?.data?.data"
                        :key="category.id"
                    >
                        <td class="border-b p-2">{{ category.id }}</td>
                        <td
                            class="border-b p-2 max-w-[200px] whitespace-nowrap overflow-hidden text-ellipsis"
                        >
                            {{ category.name }}
                        </td>
                        <td class="border-b p-2">
                            {{ category.description || "N/A" }}
                        </td>
                        <td class="border-b p-2">
                            {{ category.is_active ? "Oui" : "Non" }}
                        </td>
                        <td class="border-b p-2">
                            {{ category.updated_at }}
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
                                                    @click="
                                                        editCategory(category)
                                                    "
                                                >
                                                    <PencilIcon
                                                        :active="active"
                                                        class="mr-2 h-5 w-5 text-indigo-400"
                                                        aria-hidden="true"
                                                    />
                                                    Edit
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
                                                    @click="
                                                        deleteCategory(category)
                                                    "
                                                >
                                                    <TrashIcon
                                                        :active="active"
                                                        class="mr-2 h-5 w-5 text-indigo-400"
                                                        aria-hidden="true"
                                                    />
                                                    Delete
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
                v-if="categories.data?.meta"
                class="flex justify-between items-center mt-5"
            >
                <Pagination
                    :data="categories.data?.meta"
                    :entity-name="'catégories'"
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
import { CATEGORIES_PER_PAGE } from "@/constant";
import { useCategoriesStore } from "@/stores/categories";
import SelectPerPage from "@/components/core/Table/SelectPerPage.vue";
import SearchInput from "@/components/core/Table/SearchInput.vue";
import Pagination from "@/components/core/Pagination.vue";

const emit = defineEmits(["clickEdit"]);

const categoriesStore = useCategoriesStore();
const { categories, loading } = storeToRefs(categoriesStore);
const { fetchAllCategories } = categoriesStore;

const perPage = ref(CATEGORIES_PER_PAGE);
const search = ref("");
const sortField = ref("updated_at");
const sortDirection = ref("desc");

async function fetchData(url) {
    const params = {
        perPage: perPage.value,
        search: search.value,
        sortField: sortField.value,
        sortDirection: sortDirection.value,
    };

    if (url) params.url = url;
    await fetchAllSuppliers(params);
}

onMounted(async () => {
    await fetchAllCategories();
});

async function handleSelectChange() {
    await fetchAllCategories({
        perPage: perPage.value,
        search: search.value,
        sortField: sortField.value,
        sortDirection: sortDirection.value,
    });
}

async function getForPage(link) {
    await fetchAllCategories({
        url: link,
    });
}

async function sortCategories(field) {
    if (sortField.value === field) {
        sortDirection.value = sortDirection.value === "asc" ? "desc" : "asc";
    } else {
        sortField.value = field;
        sortDirection.value = "asc";
    }

    await fetchAllCategories({
        sortField: sortField.value,
        sortDirection: sortDirection.value,
        perPage: perPage.value,
        search: search.value,
    });
}

function editCategory(category) {
    emit("clickEdit", category);
}

async function deleteCategory(category) {
    if (!confirm("Are you sure you want to delete this Category?")) return;

    await categoriesStore.deleteCategory(category);
    await fetchAllCategories();
}
</script>

<style lang="scss" scoped></style>
