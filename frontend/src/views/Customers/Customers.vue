<template>
    <div class="flex items-center justify-between mb-3">
        <h1 class="text-3xl font-semibold">Liste des acheteurs</h1>
        <button
            type="submit"
            @click="showUserModal"
            class="flex cursor-pointer justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
        >
            Ajouter un acheteur
        </button>
    </div>
    <CustomersModal
        v-model="showModal"
        :customer="userModel"
        :authUser="authUser"
        @close="onModalClose"
    />
    <CustomersTable @clickEdit="editUser" />
</template>

<script setup>
import { ref } from "vue";
import CustomersModal from "./CustomerModal.vue";
import CustomersTable from "./CustomersTable.vue";

import { useUsersStore } from "@/stores/users";
import { storeToRefs } from "pinia";
import { useAuthStore } from "@/stores/auth";

const DEFAULT_CUSTOMER_OBJECT = {
    id: "",
    name: "",
    email: "",
    created_at: "",
    role: "",
    retail_store_id: "",
};

const usersStore = useUsersStore();
const authStore = useAuthStore();

const { user: authUser } = storeToRefs(authStore);

const showModal = ref(false);
const userModel = ref({ ...DEFAULT_CUSTOMER_OBJECT });

function showUserModal() {
    showModal.value = true;
}

async function editUser(user) {
    userModel.value = await usersStore.getOne(user.id);
    showUserModal();
}

function onModalClose() {
    userModel.value = { ...DEFAULT_CUSTOMER_OBJECT };
}
</script>

<style lang="scss" scoped></style>
