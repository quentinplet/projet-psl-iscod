import { defineStore } from "pinia";
import { useCrudStore } from "./composables/useCrudStore";
import { ref } from "vue";

export const useSuppliersStore = defineStore("suppliers", () => {
    // Initialisation du CRUD générique
    const crud = useCrudStore("/api/suppliers", "fournisseur", "fournisseurs");

    // État spécifique à ce store
    const selectOptSuppliers = ref([]);

    // Action spécifique
    async function fetchSelectOptSuppliers(
        firstOptionText = null,
        IsfirstOptionDisabled = false
    ) {
        await crud.fetchAll({ all: true });

        const options = crud.items().data.map((supplier) => ({
            key: supplier.id,
            text: supplier.name,
        }));

        selectOptSuppliers.value = firstOptionText
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
        selectOptSuppliers,
        fetchSelectOptSuppliers,
    };
});
