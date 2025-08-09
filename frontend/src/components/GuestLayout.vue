<template>
    <div
        class="flex min-h-full flex-1 flex-col justify-center px-6 py-12 lg:px-8"
    >
        <div class="sm:mx-auto sm:w-full sm:max-w-sm">
            <LogoPSL class="w-[400px] h-[120px] mx-auto" />
            <h2
                class="mt-10 text-center text-2xl/9 font-bold tracking-tight text-gray-900"
            >
                {{ title }}
            </h2>
        </div>

        <div class="mt-10 sm:mx-auto sm:w-full sm:max-w-sm">
            <slot></slot>
        </div>
    </div>
</template>

<script setup>
import { RouterLink } from "vue-router";
import { defineProps } from "vue";
import LogoPSL from "./core/LogoPSL.vue";

const props = defineProps({
    title: {
        type: String,
        required: true,
    },
});
</script>
