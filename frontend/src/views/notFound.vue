<template>
    <div
        class="flex justify-center items-center space-x-5 min-h-full w-full min-w-full"
    >
        <div class="flex">
            <div
                class="text-indigo-600 text-5xl px-8 font-bold border-r pb-8 leading-10"
            >
                404
            </div>
            <div class="px-8">
                <h1 class="text-5xl font-bold mb-2">Page Not Found</h1>
                <p class="text-gray-400 mb-12">
                    Please check the url in adress bar and try again
                </p>
                <div>
                    <RouterLink
                        class="rounded-md bg-indigo-600 px-3 py-3 text-sm/6 font-semibold text-white shadow-xs hover:bg-indigo-500 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600"
                        :to="{ name: 'app.dashboard' }"
                    >
                        Go back home
                    </RouterLink>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { RouterLink } from "vue-router";
</script>
