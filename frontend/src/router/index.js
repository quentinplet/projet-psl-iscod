import AppLayout from "@/components/AppLayout.vue";
import { useAuthStore } from "@/stores/auth";
import Categories from "@/views/Categories/Categories.vue";
import Dashboard from "@/views/Dashboard.vue";
import Login from "@/views/Login.vue";
import NotFound from "@/views/notFound.vue";
import ProductForm from "@/views/Products/ProductForm.vue";
import Products from "@/views/Products/Products.vue";
import RequestPassword from "@/views/RequestPassword.vue";
import ResetPassword from "@/views/ResetPassword.vue";
import Users from "@/views/Users/Users.vue";
import Customers from "@/views/Customers/Customers.vue";
import Orders from "@/views/Orders/Orders.vue";
import RetailStores from "@/views/RetailStores/RetailStores.vue";
import RetailStoreForm from "@/views/RetailStores/RetailStoreForm.vue";
import RetailStoreShow from "@/views/RetailStores/RetailStoreShow.vue";
import Suppliers from "@/views/Suppliers/Suppliers.vue";
import ProductsList from "@/views/Products/ProductsList.vue";

import SupplierForm from "@/views/Suppliers/SupplierForm.vue";
import SupplierShow from "@/views/Suppliers/SupplierShow.vue";

import { storeToRefs } from "pinia";
import { createRouter, createWebHistory } from "vue-router";
import OrderShow from "@/views/Orders/OrderShow.vue";
import { useErrorStore } from "@/stores/error";

// Métadonnées communes pour éviter la répétition
const gestionnaireOnly = {
  allowedRoles: ["gestionnaire"],
};

const idProps = {
  id: (value) => /^\d+$/.test(value),
};

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: "/",
      redirect: "/app/dashboard",
    },
    {
      path: "/app",
      name: "app",
      component: AppLayout,
      meta: {
        requiresAuth: true,
      },
      children: [
        {
          path: "dashboard",
          name: "app.dashboard",
          component: Dashboard,
        },
        {
          path: "users",
          name: "app.users",
          component: Users,
          allowedRoles: ["gestionnaire", "admin"],
        },

        // Groupe: Liste des produits pour les acheteurs
        {
          path: "products/list",
          name: "app.products.list",
          component: ProductsList,
          meta: {
            requiresAuth: true,
            allowedRoles: ["acheteur"],
          },
        },

        // Groupe: Routes réservées aux gestionnaires
        {
          path: "categories",
          name: "app.categories",
          component: Categories,
          meta: gestionnaireOnly,
        },
        {
          path: "customers",
          name: "app.customers",
          component: Customers,
          meta: gestionnaireOnly,
        },

        {
          path: "orders",
          meta: {
            requiresAuth: true,
            allowedRoles: ["gestionnaire", "acheteur"],
          },
          children: [
            {
              path: "",
              name: "app.orders",
              component: Orders,
            },
            {
              path: ":id/show",
              name: "app.orders.show",
              component: OrderShow,
              props: idProps,
            },
          ],
        },

        {
          path: "products",
          meta: gestionnaireOnly,
          children: [
            {
              path: "",
              name: "app.products",
              component: Products,
            },
            {
              path: "create",
              name: "app.products.create",
              component: ProductForm,
            },
            {
              path: ":id",
              name: "app.products.edit",
              component: ProductForm,
              props: idProps,
            },
          ],
        },

        // Groupe: Gestion des points de vente
        {
          path: "retail-stores",
          meta: gestionnaireOnly,
          children: [
            {
              path: "",
              name: "app.retailStores",
              component: RetailStores,
            },
            {
              path: "create",
              name: "app.retailStores.create",
              component: RetailStoreForm,
            },
            {
              path: ":id",
              name: "app.retailStores.edit",
              component: RetailStoreForm,
              props: idProps,
            },
            {
              path: ":id/show",
              name: "app.retailStores.show",
              component: RetailStoreShow,
              props: idProps,
            },
          ],
        },
        // Groupe: Gestion des fournisseurs
        {
          path: "suppliers",
          meta: gestionnaireOnly,
          children: [
            {
              path: "",
              name: "app.suppliers",
              component: Suppliers,
            },
            {
              path: "create",
              name: "app.suppliers.create",
              component: SupplierForm,
            },
            {
              path: ":id",
              name: "app.suppliers.edit",
              component: SupplierForm,
              props: idProps,
            },
            {
              path: ":id/show",
              name: "app.suppliers.show",
              component: SupplierShow,
              props: idProps,
            },
          ],
        },
      ],
    },

    // Groupe: Routes d'authentification
    {
      path: "/login",
      name: "login",
      meta: {
        requiresGuest: true,
      },
      component: Login,
    },
    {
      path: "/reset-password",
      name: "resetPassword",
      meta: {
        requiresGuest: true,
      },
      component: ResetPassword,
    },

    {
      path: "/request-password",
      name: "requestPassword",
      component: RequestPassword,
      meta: {
        requiresGuest: true,
      },
    },

    // Route catch-all pour les erreurs 404
    {
      path: "/:catchAll(.*)*",
      name: "notFound",
      component: NotFound,
    },
  ],
});

router.beforeEach(async (to, from, next) => {
  const authStore = useAuthStore();
  const errorStore = useErrorStore();
  errorStore.clearErrors();
  const { user } = storeToRefs(useAuthStore());
  await authStore.fetchAuthUser();

  if (!authStore.user && to.meta.requiresAuth) {
    next({ name: "login" });
    return;
  }

  if (authStore.user && to.meta.requiresGuest) {
    next({ name: "app.dashboard" });
    return;
  }

  if (to.meta.allowedRoles && authStore.user) {
    const userRole = user.value.role;
    if (!to.meta.allowedRoles.includes(userRole)) {
      next({ name: "app.dashboard" });
      return;
    }
  }

  next();
});

export default router;
