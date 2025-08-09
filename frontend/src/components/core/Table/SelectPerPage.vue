<template>
    <div class="flex items-center">
        <span class="whitespace-nowrap mr-3">Par Page</span>
        <select
            v-model="perPage"
            class="select select-neutral cursor-pointer w-24"
        >
            <option value="5">5</option>
            <option value="10">10</option>
            <option value="20">20</option>
            <option value="50">50</option>
            <option value="100">100</option>
        </select>
    </div>
</template>

<script setup>
const perPage = defineModel();
</script>
