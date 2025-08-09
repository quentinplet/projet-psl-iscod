<template>
    <div class="flex items-center justify-between mb-3">
        <h1 class="text-3xl font-semibold">Commandes en cours</h1>
    </div>
    <OrdersTable />
</template>

<script setup>
import { ref } from "vue";
import OrdersTable from "./OrdersTable.vue";
</script>

<style lang="scss" scoped></style>
