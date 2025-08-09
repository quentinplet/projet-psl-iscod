<template>
    <div :class="[label ? 'mb-2 grid space-y-1' : '']">
        <label :for="name" v-if="label" class="label">{{ label }}</label>
        <select
            :name="name"
            :required="required"
            :class="[
                'select w-full cursor-pointer',
                label
                    ? 'focus:outline-none focus:ring-indigo-500 focus:border-indigo-500'
                    : '',
            ]"
            v-model="selectValue"
        >
            <option
                v-for="option in selectOptions"
                :key="option.key"
                :value="option.key"
                :disabled="option.disabled"
            >
                {{ option.text }}
            </option>
        </select>
        <p v-if="errors && errors" class="text-red-600">{{ errors[0] }}</p>
    </div>
</template>

<script setup>
import { onMounted } from "vue";

const props = defineProps({
    name: {
        type: String,
        required: true,
    },
    firstOptionDisabled: {
        type: Boolean,
        default: false,
    },
    label: {
        type: String,
        default: "",
    },
    selectOptions: {
        type: Array,
        default: () => [],
    },
    errors: {
        type: Object,
        default: null,
    },
    required: {
        type: Boolean,
        default: false,
    },
});

const selectValue = defineModel({
    type: Number,
    default: "",
});
</script>

<style lang="scss" scoped></style>
