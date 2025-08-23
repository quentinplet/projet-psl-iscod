import { defineStore } from "pinia";
import router from "@/router";

export const useErrorStore = defineStore("errorStore", {
  state: () => ({
    errors: null,
  }),
  actions: {
    handleAxiosError(error) {
      if (error.response) {
        console.error(
          `❌ Erreur API (${error.response.status}):`,
          error.response.data
        );
        this.errors = error.response.data;

        if (error.response.status === 404) {
          //redirect to not found page
          router.push({ name: "notFound" });
        }
      } else if (error.request) {
        console.error("❌ Pas de réponse du serveur:", error.request);
        this.errors = error.request;
      } else {
        console.error("❌ Erreur:", error.message);
        this.errors = error.message;
      }
    },
    clearErrors() {
      this.errors = null;
    },
  },
});
