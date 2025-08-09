<template>
    <label :for="name" v-if="label" class="label">{{ label }}</label>
    <textarea
        :type="type"
        :id="id"
        :name="name"
        class="textarea h-24 mb-2 w-100 md:w-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
        :placeholder="placeholder"
        v-model="textareaValue"
    ></textarea>
    <p v-if="errors && errors" class="text-red-600">{{ errors[0] }}</p>
</template>

<script setup>
const props = defineProps({
    type: {
        type: String,
        default: "text",
    },
    label: String,
    name: String,
    id: String,
    required: Boolean,
    placeholder: String,
    errors: {
        type: Object,
        required: false,
    },
});

const textareaValue = defineModel({
    type: String,
    default: "",
});
</script>

<style lang="scss" scoped></style>
