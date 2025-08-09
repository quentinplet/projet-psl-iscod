import axios from "axios";
import { useAuthStore } from "@/stores/auth";
import { useErrorStore } from "@/stores/error";

const axiosClient = axios.create({});

axiosClient.interceptors.request.use((config) => {
    const authStore = useAuthStore();
    if (localStorage.getItem("token")) {
        config.headers.Authorization = `Bearer ${localStorage.getItem(
            "token"
        )}`;
    }
    return config;
});

axiosClient.interceptors.response.use(
    (response) => {
        return response;
    },
    (error) => {
        const errorStore = useErrorStore();
        // Gérer les erreurs spécifiques, par exemple les erreurs 401 (non authentifié)
        if (error.response && error.response.status === 401) {
            // Redirection vers la page de login si l'utilisateur est déconnecté
            localStorage.removeItem("token"); // Suppression du token
            router.push({ name: "login" });
        }

        // Si ce n'est pas une erreur 401, on passe l'erreur au store d'erreurs
        errorStore.handleAxiosError(error);

        // Retourner l'erreur pour que l'appel API puisse être traité dans les composants (si nécessaire)
        return Promise.reject(error);
    }
);

export default axiosClient;
