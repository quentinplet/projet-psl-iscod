<template>
    <div class="mb-2 grid space-y-1">
        <label :for="name" v-if="label" class="label">{{ label }}</label>
        <input
            :type="type"
            :id="id"
            :name="name"
            class="input w-100 md:w-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
            :placeholder="placeholder"
            v-model="inputValue"
            :step="step"
        />
        <p v-if="errors" class="text-red-600">{{ errors[0] }}</p>
    </div>
</template>

<script setup>
const props = defineProps({
    type: {
        type: String,
        default: "text",
    },
    label: String,
    name: String,
    required: Boolean,
    placeholder: String,
    id: String,
    errors: {
        type: Object,
        required: false,
    },
    step: {
        type: [String, Number],
        default: "any",
    },
});

const inputValue = defineModel({
    type: String,
    default: "",
});
</script>

<style lang="scss" scoped></style>
