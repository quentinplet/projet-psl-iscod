<template>
    <div class="mx-auto w-full max-w-7xl">
        <div class="flex items-center justify-between mb-5">
            <h1 v-if="!loading" class="text-3xl font-semibold">
                {{
                    product.id
                        ? `Mettre à jour un produit: "${product.name}"`
                        : "Ajouter un produit"
                }}
            </h1>
        </div>
        <div
            class="bg-white rounded-lg shadow animate-fade-in-down container max-w-6xl"
        >
            <Spinner
                v-if="loading"
                class="absolute left-0 top-0 bg-white right-0 bottom-0 flex items-center justify-center"
            />
            <form @submit.prevent="onSubmit">
                <div class="px-5 pt-5 pb-4">
                    <!-- <h2
                        class="text-1xl w-fit font-medium mb-3 underline underline-offset-8"
                    >
                        Informations
                    </h2> -->
                    <div
                        class="grid justify-items-center grid-cols-1 gap-2 xl:grid-cols-2"
                    >
                        <div>
                            <fieldset class="fieldset w-fit">
                                <CustomInputText
                                    placeholder="Nom"
                                    label="Produit"
                                    id="name"
                                    name="name"
                                    v-model="product.name"
                                    :errors="errors?.errors.name"
                                />
                                <CustomInputText
                                    placeholder="RAY-MET-001"
                                    label="Référence"
                                    id="reference"
                                    name="reference"
                                    v-model="product.reference"
                                    :errors="errors?.errors.reference"
                                />
                                <CustomInputText
                                    placeholder="0"
                                    type="number"
                                    label="Prix (€)"
                                    id="price"
                                    name="price"
                                    step="0.01"
                                    v-model.number="product.price"
                                    :errors="errors?.errors.price"
                                />
                                <CustomInputText
                                    placeholder="0"
                                    type="number"
                                    label="Quantité"
                                    id="quantity"
                                    name="quantity"
                                    v-model.number="product.quantity"
                                    :errors="errors?.errors.quantity"
                                />
                                <CustomTextArea
                                    placeholder="Description du produit"
                                    label="Description (optionnelle)"
                                    id="description"
                                    name="description"
                                    v-model="product.description"
                                    :errors="errors?.errors.description"
                                />
                            </fieldset>
                        </div>
                        <div>
                            <fieldset class="fieldset w-fit">
                                <CustomSelect
                                    firstOptionDisabled
                                    label="Catégorie"
                                    id="categorie_id"
                                    name="categorie_id"
                                    v-model.number="product.category_id"
                                    :selectOptions="selectOptCategories"
                                    :errors="errors?.errors.category_id"
                                />
                                <CustomSelect
                                    firstOptionDisabled
                                    label="Fournisseur"
                                    id="supplier_id"
                                    name="supplier_id"
                                    v-model.number="product.supplier_id"
                                    :selectOptions="selectOptSuppliers"
                                    :errors="errors?.errors.supplier_id"
                                />
                                <CustomInputFile
                                    label="Sélectionner une image"
                                    id="product_image_upload"
                                    name="product_image_upload"
                                    accept="image/*"
                                    :errors="errors?.errors.image"
                                    v-model="product.image"
                                />
                                <div
                                    v-if="previewImage || product.image_path"
                                    class="grid justify-items-center border border-dashed border-gray-300 p-2 rounded-lg"
                                >
                                    <img
                                        v-if="previewImage"
                                        :src="previewImage"
                                        alt="Preview"
                                        class="w-32 h-32 object-cover mt-2"
                                    />
                                    <img
                                        v-else-if="product.image_path"
                                        :src="product.image_path"
                                        alt="Product Image"
                                        class="w-32 h-32 object-cover mt-2"
                                    />
                                </div>
                            </fieldset>
                        </div>
                    </div>
                </div>
                <footer
                    class="bg-gray-50 rounded-b-lg px-4 py-3 flex gap-2 justify-end align-items-center sm:px-6"
                >
                    <button
                        type="submit"
                        class="px-4 py-2 mt-3 order-2 w-full inline-flex justify-center border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto"
                    >
                        Sauvegarder
                    </button>
                    <button
                        type="button"
                        @click="onSubmit($event, true)"
                        class="px-4 py-2 mt-3 order-2 w-full inline-flex justify-center border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto"
                    >
                        Sauvegarder et fermer
                    </button>
                    <RouterLink
                        :to="{ name: 'app.products' }"
                        class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto"
                    >
                        Annuler
                    </RouterLink>
                </footer>
            </form>
        </div>
    </div>
</template>

<script setup>
import { onMounted, ref, watch } from "vue";
import Spinner from "@/components/core/Spinner.vue";
import { useRoute } from "vue-router";
import router from "@/router";
import { useErrorStore } from "@/stores/error";
import { storeToRefs } from "pinia";
import axiosClient from "@/axios";
import CustomInputText from "@/components/core/Table/CustomInputText.vue";
import CustomTextArea from "@/components/core/Table/CustomTextArea.vue";
import { useSuppliersStore } from "@/stores/suppliers";
import { useProductsStore } from "@/stores/products";
import { useCategoriesStore } from "@/stores/categories";
import CustomSelect from "@/components/core/Table/CustomSelect.vue";
import CustomInputFile from "@/components/core/Table/CustomInputFile.vue";
import { getImageUrl } from "@/utils";

const productsStore = useProductsStore();
const categoriesStore = useCategoriesStore();
const suppliersStore = useSuppliersStore();

const { selectOptCategories } = storeToRefs(categoriesStore);
const { selectOptSuppliers } = storeToRefs(suppliersStore);
const { loading } = storeToRefs(productsStore);

const errorStore = useErrorStore();
const { errors } = storeToRefs(errorStore);

const route = useRoute();

const product = ref({
    id: null,
    name: "",
    description: "",
    price: null,
    quantity: null,
    category_id: null,
    supplier_id: null,
    image_path: null,
    image: null,
});

const previewImage = ref(null);

watch(
    () => product.value.image,
    (newImage) => {
        if (newImage && newImage instanceof File) {
            const reader = new FileReader();
            reader.onload = (e) => {
                previewImage.value = e.target.result;
            };
            reader.readAsDataURL(newImage);
        } else {
            previewImage.value = null;
        }
    },
    { immediate: true }
);

onMounted(async () => {
    await Promise.all([
        categoriesStore.fetchSelectOptCategories(
            "Sélectionner une catégorie",
            true
        ),
        suppliersStore.fetchSelectOptSuppliers(
            "Sélectionner un fournisseur",
            true
        ),
    ]);

    if (route.params.id) {
        const id = route.params.id;
        const productData = await productsStore.getOne(id);
        product.value = {
            ...productData,
            category_id: productData.category.id || null,
            supplier_id: productData.supplier.id || null,
            image_path: getImageUrl(productData.image_path),
        };
    }

    if (errors.value) {
        errorStore.clearErrors();
    }
});

async function onSubmit($event, close = false) {
    $event.preventDefault();

    if (product.value.id) {
        await productsStore.updateProduct(product.value);
    } else {
        const responseData = await productsStore.createProduct(product.value);
        product.value.id = responseData.id;
    }

    if (close) {
        router.push({ name: "app.products" });
    } else {
        router.push({
            name: "app.products.edit",
            params: { id: product.value.id },
        });
    }
}
</script>

<style lang="scss" scoped></style>
