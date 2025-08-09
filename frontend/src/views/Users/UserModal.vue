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
                                        user.id
                                            ? `Update users: "${props.user.name}"`
                                            : "Create new users"
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
                                <div
                                    class="bg-white px-4 pt-5 pb-4 grid gap-1 sm:p-6"
                                >
                                    <CustomInput
                                        class="mb-2"
                                        v-model="user.name"
                                        label="Name"
                                        :errors="errors?.errors.name"
                                    />
                                    <CustomInput
                                        type="email"
                                        class="mb-2"
                                        v-model="user.email"
                                        label="Email"
                                        :errors="errors?.errors.email"
                                    />
                                    <CustomInput
                                        type="password"
                                        class="mb-2"
                                        v-model="user.password"
                                        label="Password"
                                        :errors="errors?.errors.password"
                                    />
                                    <CustomInput
                                        type="select"
                                        class="mb-2"
                                        v-model="user.role"
                                        label="Sélectionner le rôle"
                                        :errors="errors?.errors.role"
                                        :select-options="selectOpt"
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
import { useUsersStore } from "@/stores/users";
import {
    TransitionRoot,
    TransitionChild,
    Dialog,
    DialogPanel,
    DialogTitle,
} from "@headlessui/vue";
import Spinner from "@/components/core/Spinner.vue";
import CustomInput from "@/components/core/CustomInput.vue";
import { useErrorStore } from "@/stores/error";
import { storeToRefs } from "pinia";
import { onMounted } from "vue";
import { useAuthStore } from "@/stores/auth";

const usersStore = useUsersStore();
const errorStore = useErrorStore();
const authStore = useAuthStore();

const { errors } = storeToRefs(errorStore);
const { loading } = storeToRefs(usersStore);

const props = defineProps({
    modelValue: Boolean,
    user: {
        required: true,
        type: Object,
    },
    authUser: {
        required: true,
        type: Object,
    },
});
const user = ref({
    id: props.user.id,
    name: props.user.name,
    email: props.user.email,
    role: props.user.role,
});

const selectOpt = ref([]);

onMounted(async () => {
    const roles = await usersStore.getUserRoles();
    selectOpt.value = roles.map((role) => ({
        key: role,
        text: role.charAt(0).toUpperCase() + role.slice(1),
    }));
});

const emit = defineEmits(["update:modelValue", "close"]);

const show = computed({
    get: () => props.modelValue,
    set: (value) => {
        emit("update:modelValue", value);
    },
});

onUpdated(() => {
    user.value = {
        id: props.user.id,
        name: props.user.name,
        email: props.user.email,
        role: props.user.role,
    };
});

function closeModal() {
    show.value = false;
    loading.value = false;
    errorStore.clearErrors();
    emit("close");
}

async function onSubmit() {
    if (user.value.id) {
        await usersStore.update(user.value.id, user.value);
    } else {
        await usersStore.create(user.value);
    }
    closeModal();
    await usersStore.fetchAll();
}
</script>

<style lang="scss" scoped></style>
