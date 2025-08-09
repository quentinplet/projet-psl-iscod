<template>
    <div class="flex items-center justify-between mb-3">
        <h1 class="text-3xl font-semibold">Liste des catégories de produits</h1>
        <button
            type="submit"
            @click="createCategory"
            class="flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
        >
            Ajouter une catégorie
        </button>
    </div>
    <CategoryModal
        v-model="showModal"
        :category="categoryModel"
        @close="onModalClose"
    />
    <CategoriesTable @clickEdit="editCategory" />
</template>

<script setup>
import { ref } from "vue";
import CategoryModal from "./CategoryModal.vue";
import CategoriesTable from "./CategoriesTable.vue";

import { storeToRefs } from "pinia";
import { useCategoriesStore } from "@/stores/categories";

const DEFAULT_CATEGORY_OBJECT = {
    id: "",
    name: "",
    description: "",
    is_active: true,
};

const categoriesStore = useCategoriesStore();

const showModal = ref(false);
const categoryModel = ref({ ...DEFAULT_CATEGORY_OBJECT });

function showCategoryModal() {
    showModal.value = true;
}

function createCategory() {
    categoryModel.value = { ...DEFAULT_CATEGORY_OBJECT };
    showCategoryModal();
}

async function editCategory(category) {
    const response = await categoriesStore.getOne(category.id);
    categoryModel.value = response.data;
    showCategoryModal();
}

function onModalClose() {
    categoryModel.value = { ...DEFAULT_CATEGORY_OBJECT };
}
</script>

<style lang="scss" scoped></style>
