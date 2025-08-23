<template>
  <!-- Sidebar -->
  <aside
    class="w-[200px] bg-indigo-700 text-white py-4 px-2 flex flex-col space-y-2 transition-all"
  >
    <RouterLink
      v-for="link in menuLinks"
      :key="link.name"
      :to="link.to"
      class="flex items-center space-x-2 py-1 px-2 rounded transition-colors hover:bg-black/30"
      active-class="bg-indigo-500 text-white"
    >
      <span class="text-gray-300">
        <component :is="link.icon" class="w-4" />
      </span>
      <span class="text-base"> {{ link.name }} </span>
    </RouterLink>
  </aside>
</template>

<script setup>
import { RouterLink } from "vue-router";
import {
  HomeIcon,
  UserIcon,
  Bars4Icon,
  ChartBarIcon,
  BuildingStorefrontIcon,
  CubeIcon,
  ArchiveBoxIcon,
  TagIcon,
  ShoppingBagIcon,
} from "@heroicons/vue/24/outline";

import { useAuthStore } from "@/stores/auth";
import { computed } from "vue";

const auth = useAuthStore();
const userRole = computed(() => auth.user?.role);
console.log("User role:", userRole.value);

const menuLinks = computed(() => {
  const links = [
    {
      name: "Dashboard",
      to: { name: "app.dashboard" },
      icon: HomeIcon,
      roles: ["admin", "gestionnaire", "logisticien", "acheteur"],
    },
    {
      name: "Utilisateurs",
      to: { name: "app.users" },
      icon: UserIcon,
      roles: ["admin"],
    },
    {
      name: "Fournisseurs",
      to: { name: "app.suppliers" },
      icon: CubeIcon,
      roles: ["admin", "gestionnaire"],
    },
    {
      name: "Acheteurs",
      to: { name: "app.customers" },
      icon: UserIcon,
      roles: ["gestionnaire"],
    },
    {
      name: "Points de vente",
      to: { name: "app.retailStores" },
      icon: BuildingStorefrontIcon,
      roles: ["admin", "gestionnaire"],
    },
    {
      name: "Produits",
      to: { name: "app.products.list" },
      icon: ShoppingBagIcon,
      roles: ["acheteur"],
    },
    {
      name: "Produits",
      to: { name: "app.products" },
      icon: ArchiveBoxIcon,
      roles: ["gestionnaire"],
    },
    {
      name: "Catégories",
      to: { name: "app.categories" },
      icon: TagIcon,
      roles: ["admin", "gestionnaire"],
    },
    {
      name: "Commandes",
      to: { name: "app.orders" },
      icon: Bars4Icon,
      roles: ["admin", "gestionnaire", "logisticien", "acheteur"],
    },
  ];

  return links.filter((link) => link.roles.includes(userRole.value));
});
</script>
