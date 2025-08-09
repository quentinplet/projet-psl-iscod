<template>
    <div class="mx-auto w-full max-w-7xl">
        <div class="flex items-center justify-between mb-5">
            <h1 v-if="!loading" class="text-3xl font-semibold">
                {{
                    supplier.id
                        ? `Mettre à jour un fournisseur: "${supplier.name}"`
                        : "Ajouter un fournisseur"
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
                <div
                    class="px-5 pt-5 pb-4 grid justify-items-center grid-cols-1 gap-2 xl:grid-cols-2"
                >
                    <div>
                        <h2
                            class="text-1xl w-fit font-medium mb-3 underline underline-offset-8"
                        >
                            Informations
                        </h2>
                        <fieldset class="fieldset w-fit">
                            <CustomInputText
                                placeholder="Nom"
                                label="Nom du fournisseur"
                                id="name"
                                name="name"
                                v-model="supplier.name"
                                :errors="errors?.errors.name"
                            />
                            <CustomInputText
                                type="email"
                                id="email"
                                name="email"
                                placeholder="mail@site.com"
                                label="Email"
                                v-model="supplier.email"
                                :errors="errors?.errors.email"
                            />
                            <CustomInputText
                                type="phone"
                                id="phone"
                                name="phone"
                                placeholder="+33 6 12 34 56 78"
                                label="Téléphone"
                                v-model="supplier.phone"
                                :errors="errors?.errors.phone"
                            />
                            <CustomInputText
                                type="number"
                                id="siret"
                                name="siret"
                                placeholder="12345678901234"
                                label="Siret (optionnel)"
                                v-model="supplier.siret"
                                :errors="errors?.errors.siret"
                            />
                            <CustomTextArea
                                placeholder="Description du point de vente"
                                label="Description (optionnelle)"
                                id="description"
                                name="description"
                                v-model="supplier.description"
                                :errors="errors?.errors.description"
                            />
                        </fieldset>
                    </div>
                    <div>
                        <h2
                            class="text-1xl font-medium mb-3 underline underline-offset-8"
                        >
                            Adresse
                        </h2>
                        <fieldset class="fieldset w-fit">
                            <CustomInputText
                                placeholder="123 Rue de Paris"
                                id="street"
                                name="street"
                                label="Numéro et rue"
                                v-model="supplier.address.street"
                                :errors="errors?.errors['address.street']"
                            />
                            <CustomInputText
                                placeholder="Paris"
                                id="city"
                                name="city"
                                label="Ville"
                                v-model="supplier.address.city"
                                :errors="errors?.errors['address.city']"
                            />
                            <CustomInputText
                                placeholder="97500"
                                id="postal_code"
                                name="postal_code"
                                label="Code postal"
                                v-model="supplier.address.postal_code"
                                :errors="errors?.errors['address.postal_code']"
                            />

                            <CustomTextArea
                                placeholder="Informations supplémentaires"
                                id="additional_info"
                                name="additional_info"
                                label="Informations supplémentaires (optionnelle)"
                                v-model="supplier.address.additional_info"
                                :errors="
                                    errors?.errors['address.additional_info']
                                "
                            />
                        </fieldset>
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
                        :to="{ name: 'app.suppliers' }"
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

const suppliersStore = useSuppliersStore();
const { loading } = storeToRefs(suppliersStore);

const errorStore = useErrorStore();
const { errors } = storeToRefs(errorStore);

const route = useRoute();

const supplier = ref({
    id: null,
    name: "",
    email: "",
    siret: null,
    phone: "",
    description: "",
    address: {
        street: "",
        city: "",
        postal_code: "",
        additional_info: "",
    },
});

onMounted(async () => {
    if (route.params.id) {
        const id = route.params.id;
        const supplierData = await suppliersStore.getOne(id);
        supplier.value = supplierData.data;
    }

    if (errors.value) {
        errorStore.clearErrors();
    }
});

async function onSubmit($event, close = false) {
    $event.preventDefault();

    if (supplier.value.id) {
        await suppliersStore.update(supplier.value.id, supplier.value);
    } else {
        const response = await suppliersStore.create(supplier.value);
        supplier.value.id = response.data.id;
    }

    if (close) {
        router.push({ name: "app.suppliers" });
    } else {
        router.push({
            name: "app.suppliers.edit",
            params: { id: supplier.value.id },
        });
    }
}
</script>

<style lang="scss" scoped></style>
