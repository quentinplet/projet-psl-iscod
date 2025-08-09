import axiosClient from "@/axios";
import { defineStore } from "pinia";

import { useToast } from "vue-toastification";
const toast = useToast();

export const useCategoriesStore = defineStore("categoriesStore", {
    state: () => {
        return {
            categories: {
                data: [],
                links: {},
                from: null,
                to: null,
                page: 1,
                limit: null,
                total: null,
            },
            selectOptCategories: [],
            loading: false,
        };
    },
    actions: {
        async fetchAllCategories(options = {}) {
            const {
                url = "/api/categories",
                search = "",
                perPage = 10,
                sortField = null,
                sortDirection = null,
                all = false,
            } = options;

            const params = all
                ? {
                      all: true,
                  }
                : {
                      search,
                      per_page: perPage,
                      sort_field: sortField,
                      sort_direction: sortDirection,
                  };

            this.loading = true;
            try {
                const res = await axiosClient.get(url, {
                    params,
                });

                this.categories = {
                    data: res.data,
                    links: res.data.meta?.links,
                    from: res.data.meta?.from,
                    to: res.data.meta?.to,
                    page: res.data.meta?.current_page,
                    limit: res.data.meta?.per_page,
                    total: res.data.meta?.total,
                };
            } catch (error) {
                throw error;
            } finally {
                this.loading = false;
            }
        },

        async getCategory(category) {
            this.loading = true;
            try {
                const res = await axiosClient.get(
                    `/api/categories/${category.id}`
                );
                return res.data;
            } catch (error) {
                throw error;
            } finally {
                this.loading = false;
            }
        },

        async createCategory(category) {
            this.loading = true;
            try {
                const res = await axiosClient.post("/api/categories", category);
                toast.success("Catégorie créée avec succès !");
                return res.data;
            } catch (error) {
                toast.error("Erreur lors de la création de la catégorie.");
                throw error;
            } finally {
                this.loading = false;
            }
        },

        async updateCategory(category) {
            const id = category.id;
            category._method = "PUT";

            this.loading = true;
            try {
                await axiosClient.post(`/api/categories/${id}`, category);
                toast.success("Catégorie mise à jour avec succès !");
            } catch (error) {
                toast.error("Erreur lors de la mise à jour de la catégorie.");
                throw error;
            } finally {
                this.loading = false;
            }
        },

        async deleteCategory(category) {
            try {
                await axiosClient.delete(`/api/categories/${category.id}`);
                toast.success("Catégorie supprimée avec succès !");
            } catch (error) {
                toast.error("Erreur lors de la suppression de la catégorie.");
                throw error;
            } finally {
                this.loading = false;
            }
        },

        async fetchSelectOptCategories(
            firstOptionText = null,
            IsfirstOptionDisabled = false
        ) {
            await this.fetchAllCategories({ all: true });
            this.selectOptCategories = [
                {
                    key: null,
                    text: firstOptionText,
                    disabled: IsfirstOptionDisabled,
                },
                ...this.categories.data?.data.map((category) => ({
                    key: category.id,
                    text: category.name,
                })),
            ];
        },
    },
});
