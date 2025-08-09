<template>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <div class="flex items-center justify-between mb-3">
            <div>
                <span class="whitespace-nowrap text-gray-700"> Commande </span>
                <span class="mx-2 text-gray-400">/</span>
                <span class="whitespace-nowrap text-gray-700">
                    {{ order?.id }}
                </span>
            </div>
            <RouterLink
                :to="{ name: 'app.orders' }"
                class="flex justify-center py-2 px-10 border border-transparent text-sm font-medium rounded-lg text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-700"
            >
                Retour
            </RouterLink>
        </div>
        <div class="bg-white shadow overflow-hidden sm:rounded-lg">
            <div class="px-4 py-5 sm:px-6">
                <Spinner
                    v-if="loading"
                    class="absolute left-0 top-0 bg-white right-0 bottom-0 flex items-center justify-center"
                />
                <h3 class="text-lg leading-6 font-medium text-gray-900">
                    Détails de la commande
                </h3>
            </div>
            <div class="border-t border-gray-200">
                <dl class="divide-y divide-gray-200">
                    <div
                        class="bg-gray-50 border-b-1 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6"
                    >
                        <dt class="text-mdfont-medium text-gray-700">ID</dt>
                        <dd
                            class="mt-1 text-md text-gray-900 sm:col-span-2 sm:mt-0"
                        >
                            {{ order?.id }}
                        </dd>
                    </div>
                    <div
                        class="bg-gray-50 border-b-1 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6"
                    >
                        <dt class="text-mdfont-medium text-gray-700">Status</dt>
                        <dd
                            class="mt-1 text-md text-gray-900 sm:col-span-2 sm:mt-0"
                        >
                            {{ order?.status }}
                        </dd>
                    </div>
                    <div
                        class="bg-gray-50 border-b-1 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6"
                    >
                        <dt class="text-mdfont-medium text-gray-700">
                            Prix total
                        </dt>
                        <dd
                            class="mt-1 text-md text-gray-900 sm:col-span-2 sm:mt-0"
                        >
                            {{ order?.total_price }} €
                        </dd>
                    </div>
                    <div
                        class="bg-gray-50 border-b-1 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6"
                    >
                        <dt class="text-mdfont-medium text-gray-700">
                            Acheteur
                        </dt>
                        <dd
                            class="mt-1 text-md text-gray-900 sm:col-span-2 sm:mt-0"
                        >
                            {{ order?.user?.name }}
                        </dd>
                    </div>
                    <div
                        class="bg-gray-50 border-b-1 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6"
                    >
                        <dt class="text-md font-medium text-gray-700">
                            Point de vente
                        </dt>
                        <dd
                            class="mt-1 text-md text-gray-900 sm:col-span-2 sm .mt-0"
                        >
                            {{ order?.user?.retail_store?.name || "N/A" }}
                        </dd>
                    </div>
                    <div
                        class="bg-gray-50 border-b-1 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6"
                    >
                        <dt class="text-md font-medium text-gray-700">
                            Date de livraison prévue
                        </dt>
                        <dd
                            class="mt-1 text-md text-gray-900 sm:col-span-2 sm:mt-0"
                        >
                            {{
                                order?.expected_delivery_date
                                    ? new Date(
                                          order.expected_delivery_date
                                      ).toLocaleDateString("fr-FR", {
                                          year: "numeric",
                                          month: "2-digit",
                                          day: "2-digit",
                                      })
                                    : "Aucune date de livraison prévue"
                            }}
                        </dd>
                    </div>
                    <div
                        class="bg-gray-50 border-b-1 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6"
                    >
                        <dt class="text-md font-medium text-gray-700">
                            Nombre d'articles
                        </dt>
                        <dd
                            class="mt-1 text-md text-gray-900 sm:col-span-2 sm:mt-0"
                        >
                            {{ order?.number_of_items }} article(s)
                        </dd>
                    </div>
                    <div
                        class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6"
                    >
                        <dt class="text-md font-medium text-gray-700">
                            Liste des articles
                        </dt>
                        <dd
                            class="mt-1 text-md text-gray-900 sm:col-span-2 sm:mt-0"
                        >
                            {{
                                order?.items?.length
                                    ? order.items
                                          .map(
                                              (item) =>
                                                  `${item.product.name} (x${item.quantity})`
                                          )
                                          .join(", ")
                                    : "Aucun article dans cette commande"
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
import { useOrdersStore } from "@/stores/orders";

const ordersStore = useOrdersStore();
const { loading } = storeToRefs(ordersStore);
const route = useRoute();
const order = ref({});

onMounted(async () => {
    const id = route.params.id;
    order.value = await ordersStore.getOrder(id);
});
</script>
