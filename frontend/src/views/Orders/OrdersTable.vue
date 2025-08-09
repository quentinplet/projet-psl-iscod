<template>
    <div class="bg-white p-4 rounded-lg shadow animate-fade-in-down">
        <div class="flex justify-between border-b-2 pb-3">
            <div class="flex items-center gap-4">
                <SelectPerPage v-model="perPage" @change="handleSelectChange" />
                <CustomSelect
                    v-model="statusFilterSelected"
                    :select-options="selectOptStatuses"
                    @update:model-value="handleSelectChange"
                />
                <CustomSelect
                    v-model="retailStoreIdFilterSelected"
                    :select-options="selectOptRetailStores"
                    @update:model-value="handleSelectChange"
                />
                <button @click="resetFilters" class="btn">
                    Rénitialiser les filtres
                </button>
            </div>
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
                            @click="sortOrders"
                            >ID</TableHeaderCell
                        >
                        <TableHeaderCell
                            class="border-b-2 p-2 text-left"
                            field="status"
                            :sort-field="sortField"
                            :sort-direction="sortDirection"
                            @click="sortOrders"
                            >Status</TableHeaderCell
                        >
                        <TableHeaderCell
                            class="border-b-2 p-2 text-left"
                            field="total_price"
                            :sort-field="sortField"
                            :sort-direction="sortDirection"
                            @click="sortOrders"
                            >Prix total</TableHeaderCell
                        >
                        <TableHeaderCell
                            class="border-b-2 p-2 text-left"
                            field="number_of_items"
                            :hasCursorPointer="false"
                        >
                            Quantité
                        </TableHeaderCell>
                        <TableHeaderCell
                            class="border-b-2 p-2 text-left"
                            field="order.user.name"
                            :sort-field="sortField"
                            :sort-direction="sortDirection"
                            @click="sortOrders"
                        >
                            Acheteur
                        </TableHeaderCell>
                        <TableHeaderCell
                            class="border-b-2 p-2 text-left"
                            field="retail_store"
                            :sort-field="sortField"
                            :sort-direction="sortDirection"
                            @click="sortOrders"
                            >Point de vente</TableHeaderCell
                        >
                        <TableHeaderCell
                            class="border-b-2 p-2 text-left"
                            field=""
                            :sort-field="sortField"
                            :sort-direction="sortDirection"
                            @click="sortOrders"
                            >Date de livraison prévue</TableHeaderCell
                        >
                        <TableHeaderCell field="actions">
                            Actions
                        </TableHeaderCell>
                    </tr>
                </thead>
                <tbody v-if="loading">
                    <tr>
                        <td colspan="8" class="text-center">
                            <Spinner v-if="loading" class="my-4" />
                        </td>
                    </tr>
                </tbody>
                <tbody v-else-if="!orders.data?.data?.length">
                    <tr>
                        <td colspan="8" class="text-center py-6">
                            Aucune commande trouvée.
                        </td>
                    </tr>
                </tbody>
                <tbody v-else>
                    <tr
                        v-for="(order, index) in orders.data.data"
                        :key="order.id"
                    >
                        <td class="border-b p-2">{{ order.id }}</td>
                        <td class="border-b p-2 w-60">
                            <!-- Sélecteur de statut -->
                            <select
                                v-model="order.status"
                                v-if="
                                    editingOrderStatusID === order.id &&
                                    userRole === 'gestionnaire'
                                "
                                @change="handleSelectChangeOrderStatus(order)"
                                @blur="editingOrderStatusID = null"
                                @click.stop
                                class="select select-primary w-fit cursor-pointer rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                            >
                                <option
                                    v-for="statusOption in selectOptStatusesforUpdate"
                                    :key="statusOption.key"
                                    :value="statusOption.key"
                                >
                                    {{ statusOption.text }}
                                </option>
                            </select>
                            <span
                                v-else
                                @click="editingOrderStatusID = order.id"
                                :class="[
                                    colorOrderStatus.find(
                                        (status) => status.key === order.status
                                    )?.color || 'bg-gray-200 text-gray-800',
                                    'w-full p-2 cursor-pointer rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500',
                                ]"
                            >
                                {{ order.status }}
                            </span>
                        </td>
                        <td class="border-b p-2">
                            {{ order.total_price }}
                        </td>
                        <td class="border-b p-2">
                            {{ order.number_of_items }}
                        </td>
                        <td class="border-b p-2">
                            {{ order.user.name }}
                        </td>
                        <td class="border-b p-2">
                            {{ order.user?.retail_store?.name || "N/A" }}
                        </td>
                        <td class="border-b p-2">
                            {{ order.expected_delivery_date || "N/A" }}
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
                                                        name: 'app.orders.show',
                                                        params: {
                                                            id: order.id,
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
                                            <MenuItem
                                                v-slot="{ active }"
                                                v-if="
                                                    userRole === 'gestionnaire'
                                                "
                                            >
                                                <button
                                                    :class="[
                                                        active
                                                            ? 'bg-indigo-600 text-white'
                                                            : 'text-gray-900',
                                                        'group flex w-full items-center rounded-md px-2 py-2 text-sm',
                                                    ]"
                                                    @click="
                                                        editingOrderStatusID =
                                                            order.id
                                                    "
                                                >
                                                    <PencilIcon
                                                        :active="active"
                                                        class="mr-2 h-5 w-5 text-indigo-400"
                                                        aria-hidden="true"
                                                    />
                                                    Modifier le statut
                                                </button>
                                            </MenuItem>
                                            <MenuItem
                                                v-slot="{ active }"
                                                v-if="
                                                    userRole === 'gestionnaire'
                                                "
                                            >
                                                <button
                                                    :class="[
                                                        active
                                                            ? 'bg-indigo-600 text-white'
                                                            : 'text-gray-900',
                                                        'group flex w-full items-center rounded-md px-2 py-2 text-sm',
                                                    ]"
                                                    @click="deleteOrder(order)"
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
                v-if="orders.data?.meta && orders.data?.meta.total > 0"
                class="flex justify-between items-center mt-5"
            >
                <Pagination
                    :data="orders.data?.meta"
                    :entity-name="'commandes'"
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
import { useOrdersStore } from "@/stores/orders";
import { ORDERS_PER_PAGE } from "@/constant";
import {
    ArrowRightStartOnRectangleIcon,
    EyeIcon,
} from "@heroicons/vue/24/outline";
import TableHeaderCell from "@/components/core/Table/TableHeaderCell.vue";

import { Menu, MenuButton, MenuItems, MenuItem } from "@headlessui/vue";

import { EllipsisVerticalIcon } from "@heroicons/vue/20/solid";

import { PencilIcon, TrashIcon } from "@heroicons/vue/24/outline";
import CustomSelect from "@/components/core/Table/CustomSelect.vue";
import SelectPerPage from "@/components/core/Table/SelectPerPage.vue";
import SearchInput from "@/components/core/Table/SearchInput.vue";
import Pagination from "@/components/core/Pagination.vue";
import { useAuthStore } from "@/stores/auth";
import { useRetailStoresStore } from "@/stores/retailStores";

const ordersStore = useOrdersStore();
const { fetchAllOrders, fetchOrderStatuses, updateOrderStatus } = ordersStore;
const { orders, selectOptStatuses, loading } = storeToRefs(ordersStore);

const retailStoresStore = useRetailStoresStore();
const { selectOptRetailStores } = storeToRefs(retailStoresStore);

const authStore = useAuthStore();
const { user } = storeToRefs(authStore);
const userRole = computed(() => authStore.user?.role);

const perPage = ref(ORDERS_PER_PAGE);
const search = ref("");
const sortField = ref("updated_at");
const sortDirection = ref("desc");
const statusFilterSelected = ref(null);
const retailStoreIdFilterSelected = ref(null);

const editingOrderStatusID = ref(null);

const selectOptStatusesforUpdate = computed(() => {
    return selectOptStatuses.value.filter((status) => status.key !== null);
});

onMounted(async () => {
    await Promise.all([
        fetchOrderStatuses("Tous les statuts"),
        retailStoresStore.fetchSelectOptRetailStores(
            "Tous les points de vente"
        ),
        fetchAllOrders(),
    ]);
});

async function handleSelectChange() {
    await fetchAllOrders({
        perPage: perPage.value,
        search: search.value,
        sortField: sortField.value,
        sortDirection: sortDirection.value,
        statusFilterSelected: statusFilterSelected.value,
        retailStoreIdFilterSelected: retailStoreIdFilterSelected.value,
    });
}

async function getForPage(link) {
    await fetchAllOrders({
        url: link,
        perPage: perPage.value,
        search: search.value,
        sortField: sortField.value,
        sortDirection: sortDirection.value,
        statusFilterSelected: statusFilterSelected.value,
    });
}

async function sortOrders(field) {
    if (sortField.value === field) {
        sortDirection.value = sortDirection.value === "asc" ? "desc" : "asc";
    } else {
        sortField.value = field;
        sortDirection.value = "asc";
    }

    await fetchAllOrders({
        sortField: sortField.value,
        sortDirection: sortDirection.value,
        perPage: perPage.value,
        search: search.value,
    });
}

function resetFilters() {
    perPage.value = ORDERS_PER_PAGE;
    search.value = "";
    sortField.value = "updated_at";
    sortDirection.value = "desc";
    statusFilterSelected.value = null;

    handleSelectChange();
}

async function deleteOrder(order) {
    if (!confirm("Are you sure you want to delete this order?")) return;

    await ordersStore.deleteOrder(order);
    await fetchAllOrders();
}

// Nouvelle fonction pour mettre à jour le statut de la commande
async function handleSelectChangeOrderStatus(order) {
    try {
        await updateOrderStatus(order, order.status);
        await fetchAllOrders();
    } catch (error) {
        console.error("Failed to update order status:", error);
    }
}

const colorOrderStatus = [
    { key: "en attente de préparation", color: "bg-blue-500 text-white" },
    { key: "en cours de préparation", color: "bg-orange-300 text-white" },
    { key: "prête pour expédition", color: "bg-purple-500 text-white" },
    { key: "expédiée", color: "bg-purple-700 text-white" },
    { key: "livrée", color: "bg-green-500 text-white" },
    { key: "annulée", color: "bg-red-600 text-white" },
];
</script>

<style lang="scss" scoped></style>
