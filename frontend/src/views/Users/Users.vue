<template>
    <div class="flex items-center justify-between mb-3">
        <h1 class="text-3xl font-semibold">Liste des utilisateurs</h1>
        <button
            type="submit"
            @click="showUserModal"
            class="flex cursor-pointer justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
        >
            Ajouter un utilisateur
        </button>
    </div>
    <usersModal
        v-model="showModal"
        :user="userModel"
        :authUser="authUser"
        @close="onModalClose"
    />
    <usersTable @clickEdit="editUser" />
</template>

<script setup>
import { ref } from "vue";
import usersModal from "./UserModal.vue";
import usersTable from "./UsersTable.vue";
import { useUsersStore } from "@/stores/users";
import { storeToRefs } from "pinia";
import { useAuthStore } from "@/stores/auth";

const DEFAULT_USER_OBJECT = {
    id: "",
    name: "",
    email: "",
    created_at: "",
};

const usersStore = useUsersStore();
const authStore = useAuthStore();

const { user: authUser } = storeToRefs(authStore);

const showModal = ref(false);
const userModel = ref({ ...DEFAULT_USER_OBJECT });

function showUserModal() {
    showModal.value = true;
}

async function editUser(user) {
    userModel.value = await usersStore.getOne(user);
    showUserModal();
}

function onModalClose() {
    userModel.value = { ...DEFAULT_USER_OBJECT };
}
</script>

<style lang="scss" scoped></style>
