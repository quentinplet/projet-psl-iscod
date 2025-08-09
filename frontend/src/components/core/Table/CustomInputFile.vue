<template>
    <div class="mb-2 grid space-y-1">
        <label :for="name" v-if="label" class="label">{{ label }}</label>
        <input
            type="file"
            :id="id"
            :name="name"
            class="file-input w-100 md:w-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
            :placeholder="placeholder"
            @change="handleFileChange"
            :accept="accept"
        />
        <p v-if="errors" class="text-red-600">{{ errors[0] }}</p>
    </div>
</template>

<script setup>
const props = defineProps({
    label: String,
    name: String,
    required: Boolean,
    id: String,
    errors: {
        type: Object,
        required: false,
    },
    accept: {
        type: String,
        default: "image/*",
    },
});

const emit = defineEmits(["update:modelValue"]);

function handleFileChange(event) {
    const file = event.target.files[0] || null;
    if (file && file.type.startsWith("image/")) {
        emit("update:modelValue", file);
    } else {
        emit("update:modelValue", null);
    }
}
</script>

<style lang="scss" scoped></style>
