<template>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <div class="flex items-center">
            <span class="whitespace-nowrap text-gray-500">
                Points de vente
            </span>
            <span class="mx-2 text-gray-400">/</span>
            <span class="whitespace-nowrap text-gray-500">
                {{ supplier?.data?.name }}
            </span>
        </div>
        <div class="flex items-center justify-between mb-3">
            <h1 class="text-3xl font-semibold">Informations du fournisseur</h1>
            <div class="flex gap-2">
                <RouterLink
                    :to="{ name: 'app.suppliers' }"
                    class="flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                >
                    Retour
                </RouterLink>
                <RouterLink
                    :to="{
                        name: 'app.suppliers.edit',
                        params: { id: supplier?.data?.id },
                    }"
                    class="flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                >
                    Modifier le fournisseur
                </RouterLink>
            </div>
        </div>
        <div class="bg-white shadow overflow-hidden sm:rounded-lg">
            <div class="px-4 py-5 sm:px-6">
                <Spinner
                    v-if="loading"
                    class="absolute left-0 top-0 bg-white right-0 bottom-0 flex items-center justify-center"
                />
                <h3 class="text-lg leading-6 font-medium text-gray-900">
                    Détails du fournisseur
                </h3>
                <p class="mt-1 max-w-2xl text-sm text-gray-500">
                    Informations générales sur le fournisseur.
                </p>
            </div>
            <div class="border-t border-gray-200">
                <dl>
                    <div
                        class="bg-gray-50 border-b-1 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6"
                    >
                        <dt class="text-mdfont-medium text-gray-500">Nom</dt>
                        <dd
                            class="mt-1 text-md text-gray-900 sm:col-span-2 sm:mt-0"
                        >
                            {{ supplier?.data?.name }}
                        </dd>
                    </div>
                    <div
                        class="bg-gray-50 border-b-1 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6"
                    >
                        <dt class="text-mdfont-medium text-gray-500">Email</dt>
                        <dd
                            class="mt-1 text-md text-gray-900 sm:col-span-2 sm:mt-0"
                        >
                            {{ supplier?.data?.email }}
                        </dd>
                    </div>
                    <div
                        class="bg-gray-50 border-b-1 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6"
                    >
                        <dt class="text-mdfont-medium text-gray-500">
                            Téléphone
                        </dt>
                        <dd
                            class="mt-1 text-md text-gray-900 sm:col-span-2 sm:mt-0"
                        >
                            {{
                                supplier?.data?.phone ||
                                "Aucun numéro de téléphone fourni"
                            }}
                        </dd>
                    </div>
                    <div
                        class="bg-gray-50 border-b-1 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6"
                    >
                        <dt class="text-mdfont-medium text-gray-500">Siret</dt>
                        <dd
                            class="mt-1 text-md text-gray-900 sm:col-span-2 sm:mt-0"
                        >
                            {{ supplier?.data?.siret || "Aucun siret fourni" }}
                        </dd>
                    </div>
                    <div
                        class="bg-gray-50 border-b-1 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6"
                    >
                        <dt class="text-md font-medium text-gray-500">
                            Adresse
                        </dt>
                        <dd
                            class="mt-1 text-md text-gray-900 sm:col-span-2 sm:mt-0"
                        >
                            {{ supplier?.data?.address?.street }},
                            {{ supplier?.data?.address?.city }},
                            {{ supplier?.data?.address?.postal_code }}
                        </dd>
                    </div>
                    <div
                        class="bg-gray-50 border-b-1 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6"
                    >
                        <dt class="text-md font-medium text-gray-500">
                            Produits fournis
                        </dt>
                        <dd
                            class="mt-1 text-md text-gray-900 sm:col-span-2 sm:mt-0"
                        >
                            <ul class="list-disc pl-5">
                                <li
                                    v-for="product in supplier?.data?.products"
                                    :key="product.id"
                                >
                                    {{ product.name }}
                                </li>
                            </ul>
                            <span
                                v-if="supplier?.data?.products.length === 0"
                                class="text-gray-500"
                            >
                                Aucun produit fourni
                            </span>
                        </dd>
                    </div>
                    <div
                        class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6"
                    >
                        <dt class="text-md font-medium text-gray-500">
                            Informations supplémentaires
                        </dt>
                        <dd
                            class="mt-1 text-md text-gray-900 sm:col-span-2 sm:mt-0"
                        >
                            {{
                                supplier?.data?.address?.additional_info ||
                                "Aucune information supplémentaire"
                            }}
                        </dd>
                    </div>
                </dl>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted } from "vue";
import { useRoute } from "vue-router";
import { storeToRefs } from "pinia";
import Spinner from "@/components/core/Spinner.vue";
import { useSuppliersStore } from "@/stores/suppliers";
const suppliersStore = useSuppliersStore();
const { loading } = storeToRefs(suppliersStore);
const route = useRoute();
const supplier = ref({});

onMounted(async () => {
    const id = route.params.id;
    supplier.value = await suppliersStore.getOne(id);
});
</script>
