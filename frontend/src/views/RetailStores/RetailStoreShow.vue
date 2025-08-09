<template>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <div class="flex items-center">
            <span class="whitespace-nowrap text-gray-500">
                Points de vente
            </span>
            <span class="mx-2 text-gray-400">/</span>
            <span class="whitespace-nowrap text-gray-500">
                {{ retailStore?.data?.name }}
            </span>
        </div>
        <div class="flex items-center justify-between mb-3">
            <h1 class="text-3xl font-semibold">
                Informations du point de vente
            </h1>
            <div class="flex gap-2">
                <RouterLink
                    :to="{ name: 'app.retailStores' }"
                    class="flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                >
                    Retour
                </RouterLink>
                <RouterLink
                    :to="{
                        name: 'app.retailStores.edit',
                        params: { id: retailStore?.data?.id },
                    }"
                    class="flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                >
                    Modifier le point de vente
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
                    Détails du point de vente
                </h3>
                <p class="mt-1 max-w-2xl text-sm text-gray-500">
                    Informations générales sur le point de vente.
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
                            {{ retailStore?.data?.name }}
                        </dd>
                    </div>
                    <div
                        class="bg-gray-50 border-b-1 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6"
                    >
                        <dt class="text-mdfont-medium text-gray-500">Email</dt>
                        <dd
                            class="mt-1 text-md text-gray-900 sm:col-span-2 sm:mt-0"
                        >
                            {{ retailStore?.data?.email }}
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
                                retailStore?.data?.phone ||
                                "Aucun numéro de téléphone fourni"
                            }}
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
                            {{ retailStore?.data?.address?.street }},
                            {{ retailStore?.data?.address?.city }},
                            {{ retailStore?.data?.address?.postal_code }}
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
                                retailStore?.data?.address?.additional_info ||
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
import { useRetailStoresStore } from "@/stores/retailStores";
import { useRoute } from "vue-router";
import { storeToRefs } from "pinia";
import Spinner from "@/components/core/Spinner.vue";
const retailStoresStore = useRetailStoresStore();
const { loading } = storeToRefs(retailStoresStore);
const route = useRoute();
const retailStore = ref({});

onMounted(async () => {
    const id = route.params.id;
    retailStore.value = await retailStoresStore.getOne(id);
});
</script>
