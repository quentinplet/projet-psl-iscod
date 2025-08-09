// composables/useCrudStore.js
import { ref, reactive } from "vue";
import axiosClient from "@/axios"; // Assurez-vous d'avoir votre instance axios configurée
import { useToast } from "vue-toastification"; // Importez useToast pour les notifications

/**
 * Crée une logique CRUD réutilisable pour les stores Pinia.
 *
 * @param {string} baseUrl - L'URL de base de l'API pour cette entité (ex: '/orders', '/products').
 * @param {string} entityNameSingular - Le nom singulier de l'entité (ex: 'commande', 'produit') pour les messages.
 * @param {string} entityNamePlural - Le nom pluriel de l'entité (ex: 'commandes', 'produits') pour les messages.
 * @returns {Object} Un objet contenant l'état, les getters et les actions communs.
 */
export function useCrudStore(
  baseUrl,
  entityNameSingular = "élément",
  entityNamePlural = "éléments"
) {
  const toast = useToast();

  // État commun
  const loading = ref(false);
  const search = ref("");
  const sortField = ref("updated_at");
  const sortDirection = ref("desc");
  const perPage = ref(10); // Nombre d'éléments par page

  const data = reactive({
    data: [], // Les éléments réels (ex: commandes, produits)
    links: {}, // Liens de pagination simplifiés (first, last, prev, next)
    meta: {
      // Métadonnées de pagination détaillées
      current_page: 1,
      from: 0,
      last_page: 1,
      links: [], // Liens de pagination numérotés (1, 2, ..., next)
      path: "",
      per_page: 10,
      to: 0,
      total: 0,
    },
  });

  // Getters communs
  const getters = {
    items: () => data.data,
    paginationMeta: (state) => state.data.meta,
    paginationLinks: (state) => state.data.meta.links,
    isLoading: (state) => state.loading,
  };

  // Actions communes
  const actions = {
    /**
     * Récupère toutes les entités avec filtres, tri et pagination.
     * @param {Object} options - Contient les paramètres de requête (url, perPage, search, sortField, sortDirection, all, etc.).
     */
    async fetchAll(options = {}) {
      this.loading = true;
      let requestUrl = options.url || baseUrl;

      const queryParams = {
        per_page: options.perPage ?? perPage.value,
        search: options.search ?? search.value,
        sort_field: options.sortField ?? sortField.value,
        sort_direction: options.sortDirection ?? sortDirection.value,
        all: options.all || false, // Gère le paramètre 'all'
        category_id: options.categoryId || null, // pour les filtres produits
        supplier_id: options.supplierId || null, // pour les filtres produits
        retail_store_id: options.retailStoreId || null, // pour les filtres clients
        role: options.role || null, // pour les filtres roles utilisateurs
      };

      // --- DÉBOGAGE : Vérifiez si cette ligne est affichée dans la console ---
      console.log(
        `[useCrudStore] Appel API: GET ${requestUrl} avec params:`,
        queryParams
      );
      // --- FIN DÉBOGAGE ---

      try {
        const response = await axiosClient.get(requestUrl, {
          params: queryParams,
        });

        if (options.all) {
          // Si 'all' est true, l'API renvoie généralement un tableau direct, pas un objet paginé
          data.data = response.data;
          // Réinitialiser les métadonnées de pagination si 'all' est true
          data.links = {};
          data.meta = {
            current_page: 1,
            from: 0,
            last_page: 1,
            links: [],
            path: "",
            per_page: 0,
            total: response.data.length,
            to: response.data.length,
          };
        } else {
          // Si ce n'est pas 'all', on s'attend à une réponse paginée
          data.data = response.data.data;
          data.links = response.data.links;
          data.meta = response.data.meta;
        }
      } catch (error) {
        toast.error(`Échec du chargement des ${entityNamePlural}.`);
        // Réinitialiser l'état en cas d'erreur
        data.data = [];
        data.links = {};
        data.meta = {
          current_page: 1,
          from: 0,
          last_page: 1,
          links: [],
          path: "",
          per_page: 10,
          to: 0,
          total: 0,
        };
        throw error;
      } finally {
        this.loading = false;
      }
    },

    /**
     * Récupère une seule entité par son ID.
     * @param {number|string} id - L'ID de l'entité à récupérer.
     * @returns {Object} L'entité récupérée.
     */
    async getOne(id) {
      this.loading = true;
      // --- DÉBOGAGE : Vérifiez si cette ligne est affichée dans la console ---
      console.log(`[useCrudStore] Appel API: GET ${baseUrl}/${id}`);
      // --- FIN DÉBOGAGE ---
      try {
        const response = await axiosClient.get(`${baseUrl}/${id}`);
        return response.data;
      } catch (error) {
        console.error(
          `[useCrudStore] Erreur lors de la récupération de la ${entityNameSingular}:`,
          error
        );
        toast.error(`Échec de la récupération de la ${entityNameSingular}.`);
        throw error;
      } finally {
        this.loading = false;
      }
    },

    /**
     * Crée une nouvelle entité.
     * @param {Object} payload - Les données de l'entité à créer.
     */
    async create(payload) {
      this.loading = true;
      // --- DÉBOGAGE : Vérifiez si cette ligne est affichée dans la console ---
      console.log(
        `[useCrudStore] Appel API: POST ${baseUrl} avec payload:`,
        payload
      );
      // --- FIN DÉBOGAGE ---
      try {
        const response = await axiosClient.post(baseUrl, payload);
        toast.success(`${entityNameSingular} créée avec succès !`);
        return response.data;
      } catch (error) {
        console.error(
          `[useCrudStore] Erreur lors de la création de la ${entityNameSingular}:`,
          error
        );
        toast.error(`Échec de la création de la ${entityNameSingular}.`);
        throw error;
      } finally {
        this.loading = false;
      }
    },

    /**
     * Met à jour une entité existante.
     * @param {number|string} id - L'ID de l'entité.
     * @param {Object} payload - Les données à mettre à jour.
     */
    async update(id, payload) {
      this.loading = true;
      // --- DÉBOGAGE : Vérifiez si cette ligne est affichée dans la console ---
      console.log(
        `[useCrudStore] Appel API: PUT ${baseUrl}/${id} avec payload:`,
        payload
      );
      // --- FIN DÉBOGAGE ---
      try {
        const response = await axiosClient.put(`${baseUrl}/${id}`, payload);
        toast.success(`${entityNameSingular} mise à jour avec succès !`);
        return response.data;
      } catch (error) {
        console.error(
          `[useCrudStore] Erreur lors de la mise à jour de la ${entityNameSingular}:`,
          error
        );
        toast.error(`Échec de la mise à jour de la ${entityNameSingular}.`);
        throw error;
      } finally {
        this.loading = false;
      }
    },

    /**
     * Supprime une entité.
     * @param {number|string} id - L'ID de l'entité à supprimer.
     */
    async delete(id) {
      this.loading = true;
      // --- DÉBOGAGE : Vérifiez si cette ligne est affichée dans la console ---
      console.log(`[useCrudStore] Appel API: DELETE ${baseUrl}/${id}`);
      // --- FIN DÉBOGAGE ---
      try {
        await axiosClient.delete(`${baseUrl}/${id}`);
        toast.success(`${entityNameSingular} supprimée avec succès !`);
      } catch (error) {
        console.error(
          `[useCrudStore] Erreur lors de la suppression de la ${entityNameSingular}:`,
          error
        );
        toast.error(`Échec de la suppression de la ${entityNameSingular}.`);
        throw error;
      } finally {
        this.loading = false;
      }
    },
  };

  return {
    loading,
    data,
    search,
    sortField,
    sortDirection,
    perPage,
    ...getters,
    ...actions,
  };
}
