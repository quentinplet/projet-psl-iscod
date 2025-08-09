import { defineStore } from "pinia";
import { useCrudStore } from "./composables/useCrudStore";
import { ref } from "vue";

export const useRetailStoresStore = defineStore("retailStoresStore", () => {
    // Initialisation du CRUD générique
    const crud = useCrudStore(
        "/api/retail-stores",
        "point de vente",
        "point de ventes"
    );

    // État spécifique à ce store
    const selectOptRetailStores = ref([]);

    // Action spécifique
    async function fetchSelectOptRetailStores(
        firstOptionText = null,
        IsfirstOptionDisabled = false
    ) {
        await crud.fetchAll({ all: true });

        const options = crud.items().data.map((retailStore) => ({
            key: retailStore.id,
            text: retailStore.name,
        }));

        selectOptRetailStores.value = firstOptionText
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
        selectOptRetailStores,
        fetchSelectOptRetailStores,
    };
});
