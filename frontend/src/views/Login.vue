<template>
  <section class="absolute top-4 left-2">
    <h2 class="text-lg font-semibold">Pour tester</h2>
    <div class="bg-gray-100 border-2 mb-3 w-full p-4 rounded">
      <p class="font-semibold">Gestionnaire</p>
      <p>Email : gestionnaire@example.com</p>
      <p>Mot de passe : iscod123#</p>
    </div>
    <div class="bg-gray-100 border-2 mb-4 w-full p-4 rounded">
      <p class="font-semibold">Acheteur</p>
      <p>Email : acheteur@example.com</p>
      <p>Mot de passe : iscod123#</p>
    </div>
  </section>
  <GuestLayout title="Connexion à votre espace">
    <form class="space-y-6" @submit.prevent="login">
      <div
        v-if="errors"
        class="flex items-center justify-between py-3 px-5 bg-red-500 text-white rounded"
      >
        {{ errors.message || errors.error }}
        <span
          @click="errors = ''"
          class="w-8 h-8 flex items-center justify-center rounded-full transition-colors cursor-pointer hover:bg-black/20"
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
        </span>
      </div>
      <div>
        <label for="email" class="block text-sm/6 font-medium text-gray-900"
          >Adresse Email</label
        >
        <div class="mt-2">
          <input
            type="email"
            name="email"
            id="email"
            autocomplete="email"
            required=""
            v-model="formData.email"
            class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6"
          />
        </div>
      </div>

      <div>
        <div class="flex items-center justify-between">
          <label
            for="password"
            class="block text-sm/6 font-medium text-gray-900"
            >Mot de passe</label
          >
          <div class="text-sm">
            <RouterLink
              :to="{ name: 'requestPassword' }"
              class="font-semibold text-indigo-600 hover:text-indigo-500"
              >Mot de passe oublié ?</RouterLink
            >
          </div>
        </div>
        <div class="mt-2">
          <input
            type="password"
            name="password"
            id="password"
            autocomplete="current-password"
            required=""
            v-model="formData.password"
            class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6"
          />
        </div>
      </div>

      <div class="flex items-center justify-between">
        <div class="flex items-center">
          <input
            id="remember-me"
            name="remember-me"
            type="checkbox"
            class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded"
            v-model="formData.remember"
          />
          <label for="remember-me" class="ml-2 block text-sm text-gray-900"
            >Se souvenir de moi</label
          >
        </div>
      </div>

      <div>
        <button
          type="submit"
          :disabled="isLoading"
          class="flex w-full justify-center rounded-md bg-indigo-600 px-3 py-1.5 text-sm/6 font-semibold text-white shadow-xs hover:bg-indigo-500 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600"
          :class="{
            'cursor-not-allowed': isLoading,
            'hover:bg-indigo-500': isLoading,
          }"
        >
          <svg
            v-if="isLoading"
            class="mr-3 -ml-1 size-5 animate-spin text-white"
            xmlns="http://www.w3.org/2000/svg"
            fill="none"
            viewBox="0 0 24 24"
          >
            <circle
              class="opacity-25"
              cx="12"
              cy="12"
              r="10"
              stroke="currentColor"
              stroke-width="4"
            ></circle>
            <path
              class="opacity-75"
              fill="currentColor"
              d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"
            ></path>
          </svg>
          Se connecter
        </button>
      </div>
    </form>
  </GuestLayout>
</template>

<script setup>
import { RouterLink, useRouter } from "vue-router";
import GuestLayout from "@/components/GuestLayout.vue";
import { ref } from "vue";
import { storeToRefs } from "pinia";
import { useAuthStore } from "@/stores/auth";
import { useErrorStore } from "@/stores/error";
const { errors } = storeToRefs(useErrorStore());
const { isLoading } = storeToRefs(useAuthStore());
const { authenticate } = useAuthStore();

const router = useRouter();

const formData = ref({
  email: "",
  password: "",
  remember: false,
});

async function login() {
  await authenticate("login", formData.value);
}
</script>
