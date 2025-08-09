import axiosClient from "@/axios";
import loading from "daisyui/components/loading";
import { defineStore } from "pinia";
import { useToast } from "vue-toastification";

const toast = useToast();

export const useOrdersStore = defineStore("ordersStore", {
  state: () => {
    return {
      orders: {
        data: [],
        links: {},
        from: null,
        to: null,
        page: 1,
        limit: null,
        total: null,
      },
      loading: false,
      selectOptStatuses: [],
    };
  },
  actions: {
    async fetchAllOrders(options = {}) {
      const {
        url = "/api/orders",
        search = "",
        perPage = 10,
        sortField = null,
        sortDirection = null,
        statusFilterSelected = null,
        retailStoreIdFilterSelected = null,
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
            status: statusFilterSelected,
            retail_store_id: retailStoreIdFilterSelected,
          };
      this.loading = true;
      try {
        const res = await axiosClient.get(url, {
          params,
        });

        this.orders = {
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
    async getOrder(id) {
      this.loading = true;
      try {
        const res = await axiosClient.get(`/api/orders/${id}`);
        return res.data;
      } catch (error) {
        throw error;
      } finally {
        this.loading = false;
      }
    },

    async createOrder(order) {
      this.loading = true;
      try {
        const res = await axiosClient.post("/api/orders", order);
        toast.success("La commande a été créée avec succès !");
        return res.data;
      } catch (error) {
        toast.error("Erreur lors de la création de la commande.");
        throw error;
      } finally {
        this.loading = false;
      }
    },

    async updateOrderStatus(order) {
      const id = order.id;
      order._method = "PUT";

      if (!order.status) {
        throw new Error("Status is required to update the order.");
      }
      const status = {
        status: order.status,
      };

      this.loading = true;
      try {
        await axiosClient.put(`/api/orders/${id}`, status);
        toast.success("Le statut de la commande a été mis à jour !");
      } catch (error) {
        toast.error("Erreur lors de la mise à jour du statut de la commande.");
        throw error;
      } finally {
        this.loading = false;
      }
    },

    async deleteOrder(order) {
      try {
        await axiosClient.delete(`/api/orders/${order.id}`);
        toast.success("La commande a été supprimée avec succès !");
      } catch (error) {
        throw error;
      } finally {
        this.loading = false;
      }
    },

    async fetchOrderStatuses(
      firstOptionText = null,
      IsfirstOptionDisabled = false
    ) {
      this.loading = true;
      try {
        const res = await axiosClient.get("/api/orders/statuses");
        this.selectOptStatuses = res.data.statuses.map((status) => ({
          key: status,
          text: status.charAt(0).toUpperCase() + status.slice(1),
        }));
        if (firstOptionText) {
          this.selectOptStatuses.unshift({
            key: null,
            text: firstOptionText,
            disabled: IsfirstOptionDisabled,
          });
        }
      } catch (error) {
        throw error;
      } finally {
        this.loading = false;
      }
    },
  },
});
