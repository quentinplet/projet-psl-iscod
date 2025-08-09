import axiosClient from "@/axios";
import { defineStore } from "pinia";

import { useToast } from "vue-toastification";
const toast = useToast();

export const useAuthStore = defineStore("authStore", {
    state: () => {
        return {
            user: null,
            isLoading: false,
        };
    },
    getters: {
        isAuthenticated: (state) => !!state.user,
        hasToken: () => !!localStorage.getItem("token"),
    },
    actions: {
        async fetchAuthUser() {
            // Si l'utilisateur est déjà chargé, ne pas refaire la requête
            if (this.user) {
                return this.user;
            }

            if (localStorage.getItem("token")) {
                try {
                    this.isLoading = true;
                    const res = await axiosClient.get("/api/user");
                    this.user = res.data;
                } catch (error) {
                    localStorage.removeItem("token");
                    this.user = null;
                    throw error;
                } finally {
                    this.isLoading = false;
                }
            }
        },

        async authenticate(apiRoute, formData) {
            try {
                this.isLoading = true;
                const res = await axiosClient.post(
                    `/api/${apiRoute}`,
                    formData
                );
                localStorage.setItem("token", res.data.token);
                this.user = res.data.user;
                this.user.token = res.data.token;
                this.router.push({ name: "app.dashboard" });
                toast.success("Connexion réussie !");
            } catch (error) {
                toast.error("Erreur lors de la connexion. Veuillez réessayer.");
                throw error;
            } finally {
                this.isLoading = false;
            }
        },

        async logout() {
            const res = await axiosClient.post("/api/logout");
            const data = res.data;
            this.user = null;
            localStorage.removeItem("token");
            this.router.push({ name: "login" });
        },
    },
});
