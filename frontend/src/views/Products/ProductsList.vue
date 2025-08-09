<template>
    <div class="drawer drawer-end">
        <!-- Checkbox cachée qui contrôle l'ouverture/fermeture du tiroir -->
        <input id="cart-drawer" type="checkbox" class="drawer-toggle" />
        <div class="drawer-content container mx-auto p-4">
            <h1 class="text-3xl font-bold mb-6">Nos Produits</h1>
            <div class="mb-4">
                <input
                    type="text"
                    v-model="search"
                    placeholder="Rechercher des produits..."
                    class="input border rounded-md px-3 py-2 w-full max-w-md mb-5 bg-white focus:outline-none focus:ring-1 focus:ring-indigo-400"
                    @change="handleSelectChange"
                />
                <div class="flex justify-between pb-3">
                    <div class="flex items-center gap-4">
                        <SelectPerPage
                            v-model="perPage"
                            @change="handleSelectChange"
                        />
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
                        <!-- Bouton pour ouvrir le tiroir du panier -->
                        <label
                            for="cart-drawer"
                            class="btn btn-primary drawer-button bg-indigo-600 hover:bg-indigo-700 text-white rounded-full"
                        >
                            Ouvir le panier ({{ cartStore.cartCount }})
                        </label>
                    </div>
                </div>
            </div>
            <div v-if="loading" class="text-center py-8">
                Chargement des produits...
            </div>
            <div
                v-else-if="productsData.data?.length === 0"
                class="text-center py-8 text-gray-600"
            >
                Aucun produit trouvé.
            </div>
            <div
                v-else
                class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6"
            >
                <div
                    v-for="product in productsData?.data"
                    :key="product.id"
                    class="card bg-white rounded-lg shadow-md overflow-hidden"
                >
                    <img
                        :src="
                            getImageUrl(product.image_path) ||
                            '/placeholder-product.png'
                        "
                        :alt="product.name"
                        class="w-full h-48 object-contain"
                    />
                    <div class="card-body p-4">
                        <h2 class="card-title">
                            {{ product.name }}
                        </h2>
                        <p class="text-gray-700 mb-3">
                            {{ product.description.substring(0, 70) }}...
                        </p>
                        <div class="flex justify-between items-center">
                            <span class="text-lg font-bold"
                                >{{ product.price }} €</span
                            >
                            <div class="card-actions">
                                <button
                                    @click="cartStore.addItem(product)"
                                    class="btn btn-primary bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-md"
                                >
                                    Ajouter au panier
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
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
        <!-- Contenu du tiroir (le panier) - Maintenant un composant séparé -->
        <div class="drawer-side z-20">
            <label
                for="cart-drawer"
                aria-label="close sidebar"
                class="drawer-overlay"
            ></label>
            <ul
                class="menu p-4 w-80 min-h-full bg-base-200 text-base-content shadow-xl"
            >
                <!-- Utilisation du nouveau composant CartDrawerContent -->
                <CartDrawerContent @order-placed="closeCartDrawer" />
            </ul>
        </div>
    </div>
</template>

<script setup>
import { onMounted, ref, watch } from "vue";
import { useProductsStore } from "@/stores/products";
import { storeToRefs } from "pinia";
import { getImageUrl } from "@/utils";
import SelectPerPage from "@/components/core/Table/SelectPerPage.vue";
import CustomSelect from "@/components/core/Table/CustomSelect.vue";
import { useCategoriesStore } from "@/stores/categories";
import { useSuppliersStore } from "@/stores/suppliers";
import { PRODUCTS_PER_PAGE } from "@/constant";
import CartDrawerContent from "@/components/Cart/CartDrawerContent.vue";
import { useCartStore } from "@/stores/cart";
import Pagination from "@/components/core/Pagination.vue";
// import { useCartStore } from "@/stores/cart"; // Importer le store du panier

const productsStore = useProductsStore();
const {
    data: productsData,
    loading: isLoading,
    search,
} = storeToRefs(productsStore);

const categoriesStore = useCategoriesStore();
const suppliersStore = useSuppliersStore();
const cartStore = useCartStore();

const { selectOptCategories } = storeToRefs(categoriesStore);
const { selectOptSuppliers } = storeToRefs(suppliersStore);

const perPage = ref(PRODUCTS_PER_PAGE);
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
        console.error(
            "Erreur lors du chargement des données des stores :",
            error
        );
    }
});

async function handleSelectChange() {
    await productsStore.fetchAll({
        perPage: perPage.value,
        categoryId: categorieIdSelected.value,
        supplierId: supplierIdSelected.value,
    });
}

function resetFilters() {
    perPage.value = PRODUCTS_PER_PAGE;
    search.value = "";
    categorieIdSelected.value = null;
    supplierIdSelected.value = null;

    handleSelectChange();
}

async function getForPage(url) {
    await productsStore.fetchAll({
        url,
        perPage: perPage.value,
        categoryId: categorieIdSelected.value,
        supplierId: supplierIdSelected.value,
    });
}

function closeCartDrawer() {
    // Fermer le panier
    const drawerToggle = document.getElementById("cart-drawer");
    if (drawerToggle) {
        drawerToggle.checked = false;
    }
}
</script>
