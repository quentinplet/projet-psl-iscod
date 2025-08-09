<template>
    <h2 class="w-fit text-2xl font-semibold mb-4 text-indigo-600">
        Contenu du Panier<br />
        ({{ cartStore.cartCount }} articles)
    </h2>

    <div
        v-if="cartStore.uniqueCartItemsCount === 0"
        class="text-center text-gray-500 py-8"
    >
        Votre panier est vide.
    </div>

    <div v-else>
        <article
            v-for="item in cartStore.cartItems"
            :key="item.product_id"
            class="flex items-center border-b last:border-b-0"
        >
            <div class="card-body grid gap-4">
                <div class="flex items-center gap-4">
                    <img
                        :src="getImageUrl(item.image_path)"
                        :alt="item.name"
                        class="w-18 h-18 object-contain rounded-md"
                    />
                    <div>
                        <p class="font-semibold text-gray-800">
                            {{ item.name }}
                        </p>
                        <p class="text-sm text-gray-600">
                            {{ item.price.toFixed(2) }} € / unité
                        </p>
                    </div>
                </div>
                <div class="flex items-center gap-3">
                    <button
                        @click="
                            cartStore.updateQuantity(
                                item.product_id,
                                item.quantity - 1
                            )
                        "
                        class="bg-gray-200 cursor-pointer hover:bg-gray-300 text-gray-800 font-bold py-1 px-3 rounded-full"
                    >
                        -
                    </button>
                    <span class="font-bold text-md">{{ item.quantity }}</span>
                    <button
                        @click="
                            cartStore.updateQuantity(
                                item.product_id,
                                item.quantity + 1
                            )
                        "
                        class="bg-gray-200 cursor-pointer hover:bg-gray-300 text-gray-800 font-bold py-1 px-3 rounded-full"
                    >
                        +
                    </button>
                    <button
                        @click="cartStore.removeItem(item.product_id)"
                        class="btn btn-error text-white rounded-lg"
                    >
                        Supprimer
                    </button>
                </div>
            </div>
        </article>

        <div class="mt-2 pt-4 flex justify-between items-center">
            <p class="text-xl font-bold text-gray-800">Total du panier :</p>
            <p class="text-xl font-bold color-primary">
                {{ cartStore.cartTotal }} €
            </p>
        </div>

        <div class="mt-6 flex gap-4">
            <button
                @click="cartStore.clearCart()"
                class="btn btn-neutral rounded-lg flex-auto h-14"
            >
                Vider le panier
            </button>
            <button
                @click="handlePlaceOrder()"
                :disabled="loading"
                class="btn btn-primary rounded-lg flex-auto h-14"
            >
                Passer la commande
            </button>
        </div>
    </div>
</template>

<script setup>
import { useCartStore } from "@/stores/cart";
import { onMounted } from "vue";
import { getImageUrl } from "@/utils";
import { storeToRefs } from "pinia";

const cartStore = useCartStore();

const { loading } = storeToRefs(cartStore);

const emit = defineEmits(["orderPlaced"]);

const handlePlaceOrder = async () => {
    try {
        await cartStore.placeOrder();
        emit("orderPlaced");
    } catch (error) {
        console.error("Erreur lors de la commande :", error);
        // Vous pouvez ajouter une alerte à l'utilisateur ici si vous le souhaitez
        alert("Une erreur est survenue lors du passage de votre commande.");
    }
};

// Au montage du composant, charge le panier depuis le localStorage
onMounted(() => {
    cartStore.loadCartFromLocalStorage();
});
</script>

<style scoped></style>
