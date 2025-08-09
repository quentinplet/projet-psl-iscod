<template>
    <!-- Affichage des informations de pagination -->
    <span>
        Affichage de {{ data.from }} à {{ data.to }} sur {{ data.total }}
        {{ entityName }}
    </span>

    <!-- Navigation de pagination -->
    <nav
        v-if="data.total > data.per_page"
        class="relative z-0 inline-flex justify-center rounded-md shadow-md -space-x-px"
        aria-label="Pagination"
    >
        <a
            v-for="(link, i) of data.links"
            :key="i"
            :disabled="!link.url"
            href="#"
            @click.prevent="getForPage(link)"
            aria-current="page"
            class="relative inline-flex items-center px-4 py-2 border text-sm font-medium whitespace-nowrap"
            :class="[
                link.active
                    ? 'z-10 bg-indigo-50 border-indigo-500 text-indigo-600'
                    : 'bg-white border-gray-300 text-gray-500 hover:bg-gray-50',
                i === 0 ? 'rounded-l-md' : '',
                i === data.links.length - 1 && 'rounded-r-md',
                !link.url &&
                    'bg-gray-100 text-gray-700 cursor-not-allowed pointer-events-none',
            ]"
            v-html="link.label"
        ></a>
    </nav>
</template>

<script setup>
import { defineProps, defineEmits, onMounted } from "vue";

const props = defineProps({
    // L'objet de pagination complet (ex: orders, suppliers)
    data: {
        type: Object,
        required: true,
    },
    entityName: {
        type: String,
        default: "éléments", // Valeur par défaut si non spécifié
    },
});

const emit = defineEmits(["page-changed"]);

function getForPage(link) {
    if (!link.url || link.active) {
        return;
    }
    // Émet l'URL du lien pour que le composant parent puisse charger les données
    emit("page-changed", link.url);
}
</script>

<style scoped></style>
