import axiosClient from "@/axios";
import { defineStore } from "pinia";

import { useToast } from "vue-toastification";
import { useCrudStore } from "./composables/useCrudStore";
import { ref } from "vue";
const toast = useToast();

export const useUsersStore = defineStore("users", () => {
    // Initialisation du CRUD générique
    const crud = useCrudStore("/api/users", "utilisateur", "utilisateurs");

    const usersRoles = ref([]);

    async function getUserRoles() {
        const res = await axiosClient.get("/api/users-roles");
        usersRoles.value = res.data.roles;
        return res.data.roles;
    }

    return {
        ...crud, // expose tout ce que retourne useCrudStore : data, loading, fetchAll, etc.
        usersRoles,
        getUserRoles,
    };
});
