// stores/categories.js
import { defineStore } from "pinia";
import { useCrudStore } from "@/stores/composables/useCrudStore";
import { ref } from "vue";

export const useCategoriesStore = defineStore("categories", () => {
    // Initialisation du CRUD générique
    const crud = useCrudStore("/api/categories", "catégorie", "catégories");

    // État spécifique à ce store
    const selectOptCategories = ref([]);

    // Action spécifique
    async function fetchSelectOptCategories(
        firstOptionText = null,
        IsfirstOptionDisabled = false
    ) {
        await crud.fetchAll({ all: true });

        const options = crud.items().data.map((category) => ({
            key: category.id,
            text: category.name,
        }));

        selectOptCategories.value = firstOptionText
            ? [
                  {
                      key: null,
                      text: firstOptionText,
                      disabled: IsfirstOptionDisabled,
                  },
                  ...options,
              ]
            : options;
    }

    return {
        ...crud, // expose tout ce que retourne useCrudStore : data, loading, fetchAll, etc.
        selectOptCategories,
        fetchSelectOptCategories,
    };
});
