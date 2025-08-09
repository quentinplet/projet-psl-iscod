<template>
    <TransitionRoot appear :show="show" as="template">
        <Dialog as="div" @close="closeModal" class="relative z-10">
            <TransitionChild
                as="template"
                enter="duration-300 ease-out"
                enter-from="opacity-0"
                enter-to="opacity-100"
                leave="duration-200 ease-in"
                leave-from="opacity-100"
                leave-to="opacity-0"
            >
                <div class="fixed inset-0 bg-black/50" />
            </TransitionChild>

            <div class="fixed inset-0 overflow-y-auto">
                <div
                    class="flex min-h-full items-center justify-center p-4 text-center"
                >
                    <TransitionChild
                        as="template"
                        enter="duration-300 ease-out"
                        enter-from="opacity-0 scale-95"
                        enter-to="opacity-100 scale-100"
                        leave="duration-200 ease-in"
                        leave-from="opacity-100 scale-100"
                        leave-to="opacity-0 scale-95"
                    >
                        <DialogPanel
                            class="w-full max-w-md transform overflow-hidden rounded-2xl bg-white text-left align-middle shadow-xl transition-all"
                        >
                            <Spinner
                                v-if="loading"
                                class="absolute left-0 top-0 bg-white right-0 bottom-0 flex items-center justify-center"
                            />
                            <header
                                class="py-3 px-4 flex justify-between items-center"
                            >
                                <DialogTitle
                                    as="h3"
                                    class="text-lg leading-6 font-medium text-gray-900"
                                >
                                    {{
                                        product.id
                                            ? `Update product: "${props.product.title}"`
                                            : "Create new product"
                                    }}
                                </DialogTitle>
                                <button
                                    @click="closeModal"
                                    class="w-8 h-8 flex items-center justify-center rounded-full transition-colors cursor-pointer hover:bg-[rgba(0,0,0,.2)]"
                                >
                                    <svg
                                        xmlns="http://www.w3.org/2000/svg"
                                        fill="none"
                                        viewBox="0 0 24 24"
                                        stroke-width="1.5"
                                        stroke="currentColor"
                                        class="size-6"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            d="M6 18 18 6M6 6l12 12"
                                        />
                                    </svg>
                                </button>
                            </header>
                            <form @submit.prevent="onSubmit">
                                <div class="bg-white px-4 pt-5 pb-4">
                                    <CustomInput
                                        class="mb-2"
                                        v-model="product.title"
                                        label="Product Title"
                                    />
                                    <CustomInput
                                        type="file"
                                        class="mb-2"
                                        label="Product Image"
                                        @change="
                                            (file) => (product.image = file)
                                        "
                                    />
                                    <CustomInput
                                        type="textarea"
                                        class="mb-2"
                                        v-model="product.description"
                                        label="Description"
                                    />
                                    <CustomInput
                                        type="number"
                                        class="mb-2"
                                        v-model="product.price"
                                        label="Product Price"
                                        prepend="$"
                                    />
                                </div>
                                <footer
                                    class="bg-gray-50 px-4 py-3 flex justify-end align-items-center sm:px-6"
                                >
                                    <button
                                        type="submit"
                                        class="px-4 py-2 mt-3 order-2 w-full inline-flex justify-center border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto"
                                    >
                                        Submit
                                    </button>
                                    <button
                                        type="button"
                                        @click.prevent="closeModal"
                                        ref="cancelButtonRef"
                                        class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto"
                                    >
                                        Cancel
                                    </button>
                                </footer>
                            </form>
                        </DialogPanel>
                    </TransitionChild>
                </div>
            </div>
        </Dialog>
    </TransitionRoot>
</template>

<script setup>
import { computed, onUpdated, ref } from "vue";
import { useProductsStore } from "@/stores/products";
import {
    TransitionRoot,
    TransitionChild,
    Dialog,
    DialogPanel,
    DialogTitle,
} from "@headlessui/vue";
import Spinner from "@/components/core/Spinner.vue";
import CustomInput from "@/components/core/CustomInput.vue";

const productsStore = useProductsStore();
const loading = ref(false);

const props = defineProps({
    modelValue: Boolean,
    product: {
        required: true,
        type: Object,
    },
});

const product = ref({
    id: props.product.id,
    title: props.product.title,
    image: props.product.image,
    description: props.product.description,
    price: props.product.price,
});

const emit = defineEmits(["update:modelValue", "close"]);

const show = computed({
    get: () => props.modelValue,
    set: (value) => {
        emit("update:modelValue", value);
    },
});

onUpdated(() => {
    product.value = {
        id: props.product.id,
        title: props.product.title,
        image: props.product.image,
        description: props.product.description,
        price: props.product.price,
    };
});

function closeModal() {
    show.value = false;
    loading.value = false;
    emit("close");
}

async function onSubmit() {
    loading.value = true;

    if (product.value.id) {
        await productsStore.updateProduct(product.value);
    } else {
        // Create new product
        await productsStore.createProduct(product.value);
    }
    closeModal();
    await productsStore.fetchAllProducts();
}
</script>

<style lang="scss" scoped></style>
