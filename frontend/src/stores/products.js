import axiosClient from "@/axios";
import { defineStore } from "pinia";
import { toFormData } from "@/utils";

import { useToast } from "vue-toastification";
import { useCrudStore } from "./composables/useCrudStore";
const toast = useToast();

export const useProductsStore = defineStore("products", () => {
  // Initialisation du CRUD générique
  const crud = useCrudStore("/api/products", "produit", "produits");

  // Action spécifique
  async function createProduct(product) {
    crud.loading.value = true;
    try {
      if (product.image instanceof File) {
        const formData = toFormData(product);
        formData.append("_method", "POST");
        product = formData;
      }
      const response = await axiosClient.post("/api/products", product);
      toast.success("Produit créé avec succès !");
      return response.data;
    } catch (error) {
      toast.error("Erreur lors de la création du produit.");
      throw error;
    } finally {
      crud.loading.value = false;
    }
  }

  async function updateProduct(product) {
    const id = product.id;
    crud.loading.value = true;

    try {
      if (product.image instanceof File) {
        const formData = toFormData(product);
        formData.append("_method", "PUT");
        product = formData;
      } else {
        product._method = "PUT";
      }
      const res = await axiosClient.post(`/api/products/${id}`, product);
      toast.success("Produit mis à jour avec succès !");
      return res.data;
    } catch (error) {
      toast.error("Erreur lors de la mise à jour du produit.");
      throw error;
    } finally {
      crud.loading.value = false;
    }
  }

  return {
    ...crud, // expose tout ce que retourne useCrudStore : data, loading, fetchAll, etc.
    createProduct,
    updateProduct,
  };
});
