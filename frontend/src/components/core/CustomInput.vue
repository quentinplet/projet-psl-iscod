<template>
    <div>
        <label class="sr-only">{{ label }}</label>
        <div
            class="flex rounded-md w-full"
            :class="type === 'checkbox' ? 'items-center' : 'shadow-sm'"
        >
            <span
                v-if="prepend"
                class="inline-flex items-center px-3 rounded-l-md border border-r-0 border-gray-300 bg-gray-50 text-gray-500 text-sm"
            >
                {{ prepend }}
            </span>
            <template v-if="type === 'textarea'">
                <textarea
                    :name="name"
                    :required="required"
                    :class="inputClasses"
                    :value="props.modelValue"
                    :placeholder="label"
                    @input="emit('update:modelValue', $event.target.value)"
                    rows="5"
                    cols="33"
                ></textarea>
            </template>
            <template v-else-if="type === 'select'">
                <select
                    :name="name"
                    :required="required"
                    class="select w-full cursor-pointer"
                    :value="props.modelValue"
                    @change="onChange($event.target.value)"
                >
                    <option v-if="label" value="" disabled selected>
                        {{ label }}
                    </option>
                    <option
                        v-for="option in selectOptions"
                        :key="option.key"
                        :value="option.key"
                    >
                        {{ option.text }}
                    </option>
                </select>
            </template>
            <template v-else-if="type === 'file'">
                <input
                    :type="type"
                    :name="name"
                    :required="required"
                    :value="props.modelValue"
                    @input="emit('change', $event.target.files[0])"
                    :class="inputClasses"
                    :placeholder="label"
                />
            </template>
            <template v-else-if="type === 'richtext'">
                <ckeditor
                    v-if="editor"
                    :model-value="props.modelValue"
                    @input="onChange"
                    :editor="editor"
                    :config="config"
                />
            </template>
            <template v-else-if="type === 'checkbox'">
                <input
                    :id="id"
                    :type="type"
                    :name="name"
                    :required="required"
                    :checked="props.modelValue"
                    @change="emit('update:modelValue', $event.target.checked)"
                    class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border border-gray-300 rounded"
                />
                <label :for="id" class="ml-2 block text-sm text-gray-900">
                    {{ label }}
                </label>
            </template>

            <template v-else>
                <input
                    :type="type"
                    :name="name"
                    :required="required"
                    :class="inputClasses"
                    :value="props.modelValue"
                    :placeholder="label"
                    @input="emit('update:modelValue', $event.target.value)"
                    step="0.01"
                />
            </template>
            <span
                v-if="append"
                class="inline-flex items-center px-3 rounded-r-md border border-l-0 border-gray-300 bg-gray-50 text-gray-500 text-sm"
            >
                {{ append }}
            </span>
        </div>
        <small v-if="errors && errors" class="text-red-600">{{
            errors[0]
        }}</small>
    </div>
</template>

<script setup>
import { Ckeditor, useCKEditorCloud } from "@ckeditor/ckeditor5-vue";
import { computed, watch } from "vue";

const cloud = useCKEditorCloud({
    version: "44.3.0",
    premium: false,
});

const editor = computed(() => {
    if (!cloud.data.value) {
        return null;
    }

    return cloud.data.value.CKEditor.ClassicEditor;
});

const config = computed(() => {
    if (!cloud.data.value) {
        return null;
    }

    const { Essentials, Paragraph, Bold, Italic } = cloud.data.value.CKEditor;

    return {
        licenseKey: "GPL",
        plugins: [Essentials, Paragraph, Bold, Italic],
        toolbar: ["undo", "redo", "|", "bold", "italic", "|"],
    };
});

const props = defineProps({
    modelValue: [String, Number, File, Boolean],
    label: String,
    type: {
        type: String,
        default: "text",
    },
    name: String,
    required: Boolean,
    prepend: {
        type: String,
        default: "",
    },
    append: {
        type: String,
        default: "",
    },
    errors: {
        type: Object,
        required: false,
    },
    editorConfig: {
        type: Object,
        default: () => ({}),
    },
    selectOptions: {
        type: Array,
        default: () => [],
    },
});

const id = computed(() => {
    if (props.id) return props.id;

    return `id-${Math.floor(1000000 + Math.random() * 1000000)}`;
});

// Watch les erreurs et affiche-les dans la console
watch(
    () => props.errors,
    (newErrors) => {
        console.log("Erreurs mises à jour : ", newErrors);
    },
    { deep: true }
);

const inputClasses = computed(() => {
    const cls = [
        `block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm`,
    ];

    if (props.append && !props.prepend) {
        cls.push("rounded-l-md");
    } else if (props.prepend && !props.append) {
        cls.push("rounded-r-md");
    } else if (props.prepend && props.append) {
        cls.push("rounded-none");
    } else {
        cls.push("rounded-md");
    }

    if (props.errors && props.errors.length) {
        cls.push("border-red-500 focus:border-red-500");
    }

    return cls.join(" ");
});

const emit = defineEmits(["update:modelValue", "change"]);
function onChange(value) {
    emit("update:modelValue", value);
    emit("change", value);
}
</script>

<style lang="scss" scoped></style>
