<template>
  <div class="bg-white p-4 rounded-lg shadow animate-fade-in-down">
    <div class="flex justify-between border-b-2 pb-3">
      <div class="flex items-center gap-4">
        <SelectPerPage v-model="perPage" @change="handleSelectChange" />
        <CustomSelect
          v-model="categorieIdSelected"
          :select-options="selectOptCategories"
          @update:model-value="handleSelectChange"
        />
        <CustomSelect
          v-model="supplierIdSelected"
          :select-options="selectOptSuppliers"
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
              @click="sortProducts"
              >ID</TableHeaderCell
            >
            <TableHeaderCell
              class="border-b-2 p-2 text-left"
              field=""
              :sort-field="sortField"
              :sort-direction="sortDirection"
              >Image</TableHeaderCell
            >
            <TableHeaderCell
              class="border-b-2 p-2 text-left"
              field="name"
              :sort-field="sortField"
              :sort-direction="sortDirection"
              @click="sortProducts"
              >Nom</TableHeaderCell
            >
            <TableHeaderCell
              class="border-b-2 p-2 text-left"
              field="price"
              :sort-field="sortField"
              :sort-direction="sortDirection"
              @click="sortProducts"
              >Prix</TableHeaderCell
            >
            <TableHeaderCell
              class="border-b-2 p-2 text-left"
              field="quantity"
              :sort-field="sortField"
              :sort-direction="sortDirection"
              @click="sortProducts"
              >Quantité</TableHeaderCell
            >
            <TableHeaderCell
              class="border-b-2 p-2 text-left"
              field="category.name"
              >Catégorie</TableHeaderCell
            >
            <TableHeaderCell
              class="border-b-2 p-2 text-left"
              field="supplier.name"
              >Fournisseur</TableHeaderCell
            >
            <TableHeaderCell
              class="border-b-2 p-2 text-left"
              field="updated_at"
              :sort-field="sortField"
              :sort-direction="sortDirection"
              @click="sortProducts"
            >
              Last Updated At
            </TableHeaderCell>
            <TableHeaderCell field="actions"> Actions </TableHeaderCell>
          </tr>
        </thead>
        <tbody v-if="isLoading">
          <tr>
            <td colspan="9" class="text-center p-4">
              <Spinner class="my-4" />
            </td>
          </tr>
        </tbody>
        <tbody v-else-if="!productsData.data?.length">
          <tr>
            <td colspan="9" class="text-center p-4">
              Pas de produits trouvés.
            </td>
          </tr>
        </tbody>
        <tbody v-else>
          <tr v-for="(product, index) in productsData?.data" :key="product.id">
            <td class="border-b p-2">{{ product.id }}</td>
            <td class="border-b p-2">
              <img
                class="w-16 object-cover"
                :src="getImageUrl(product.image_path)"
                :alt="product.name"
              />
            </td>
            <td class="border-b p-2">
              {{ product.name }}
            </td>
            <td class="border-b p-2">{{ product.price }}&nbsp;€</td>
            <td class="border-b p-2">
              {{ product.quantity }}
            </td>
            <td class="border-b p-2">
              {{ product.category?.name || "N/A" }}
            </td>
            <td class="border-b p-2">
              {{ product.supplier?.name || "N/A" }}
            </td>
            <td class="border-b p-2">
              {{ product.updated_at }}
            </td>
            <td class="border-b p-2">
              <Menu as="div" class="relative inline-block text-left">
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
                            name: 'app.products.edit',
                            params: {
                              id: product.id,
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
                          Edit
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
                          @click="deleteProduct(product)"
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
        v-if="productsData?.meta"
        class="flex justify-between items-center mt-5"
      >
        <Pagination
          :data="productsData?.meta"
          :entity-name="'produits'"
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
import { useProductsStore } from "@/stores/products";
import { PRODUCTS_PER_PAGE } from "@/constant";
import { ArrowRightStartOnRectangleIcon } from "@heroicons/vue/24/outline";
import TableHeaderCell from "@/components/core/Table/TableHeaderCell.vue";

import { Menu, MenuButton, MenuItems, MenuItem } from "@headlessui/vue";

import { EllipsisVerticalIcon } from "@heroicons/vue/20/solid";

import { PencilIcon, TrashIcon } from "@heroicons/vue/24/outline";
import SelectPerPage from "@/components/core/Table/SelectPerPage.vue";
import SearchInput from "@/components/core/Table/SearchInput.vue";
import CustomInput from "@/components/core/CustomInput.vue";
import { useCategoriesStore } from "@/stores/categories";
import CustomSelect from "@/components/core/Table/CustomSelect.vue";
import { useSuppliersStore } from "@/stores/suppliers";
import { getImageUrl } from "@/utils.js";
import Pagination from "@/components/core/Pagination.vue";

const emit = defineEmits(["clickEdit"]);

const productsStore = useProductsStore();

const {
  data: productsData,
  loading: isLoading,
  search,
  sortField,
  sortDirection,
  perPage,
} = storeToRefs(productsStore);

const categoriesStore = useCategoriesStore();
const suppliersStore = useSuppliersStore();

const { selectOptCategories } = storeToRefs(categoriesStore);
const { selectOptSuppliers } = storeToRefs(suppliersStore);

const categorieIdSelected = ref(null);
const supplierIdSelected = ref(null);

onMounted(async () => {
  try {
    // Lance toutes les requêtes en parallèle et attend qu'elles soient toutes terminées
    await Promise.all([
      productsStore.fetchAll(),
      categoriesStore.fetchSelectOptCategories("Toutes les catégories"),
      suppliersStore.fetchSelectOptSuppliers("Tous les fournisseurs"),
    ]);
  } catch (error) {
    console.error("Erreur lors du chargement des données des stores :", error);
  }
});

function resetFilters() {
  perPage.value = PRODUCTS_PER_PAGE;
  search.value = "";
  sortField.value = "updated_at";
  sortDirection.value = "desc";
  categorieIdSelected.value = null;
  supplierIdSelected.value = null;
  handleSelectChange();
}

async function handleSelectChange() {
  await productsStore.fetchAll({
    categoryId: categorieIdSelected.value,
    supplierId: supplierIdSelected.value,
  });
}

async function getForPage(url) {
  await productsStore.fetchAll({
    url,
    categoryId: categorieIdSelected.value,
    supplierId: supplierIdSelected.value,
  });
}

async function sortProducts(field) {
  if (sortField.value === field) {
    sortDirection.value = sortDirection.value === "asc" ? "desc" : "asc";
  } else {
    sortField.value = field;
    sortDirection.value = "asc";
  }

  await productsStore.fetchAll({
    categoryId: categorieIdSelected.value,
    supplierId: supplierIdSelected.value,
  });
}

async function deleteProduct(product) {
  if (!confirm("Are you sure you want to delete this product?")) return;

  await productsStore.delete(product.id);
  await productsStore.fetchAll({
    categoryId: categorieIdSelected.value,
    supplierId: supplierIdSelected.value,
  });
}
</script>

<style lang="scss" scoped></style>
