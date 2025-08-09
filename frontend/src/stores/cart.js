// stores/cart.js
import { defineStore } from "pinia";
import { useOrdersStore } from "./orders";
import { useToast } from "vue-toastification";

const toast = useToast();

export const useCartStore = defineStore("cart", {
    // État du panier
    state: () => ({
        // Chaque élément du panier contiendra : { product_id, name, price, quantity, image_path }
        items: [],
        loading: false,
    }),

    // Getters pour récupérer des données calculées à partir de l'état
    getters: {
        // Retourne tous les articles du panier
        cartItems: (state) => state.items,

        // Calcule le prix total du panier
        cartTotal: (state) => {
            return state.items
                .reduce((total, item) => total + item.price * item.quantity, 0)
                .toFixed(2);
        },

        // Calcule le nombre total d'articles (quantité cumulée) dans le panier
        cartCount: (state) => {
            return state.items.reduce(
                (count, item) => count + item.quantity,
                0
            );
        },

        // Calcule le nombre d'articles uniques dans le panier
        uniqueCartItemsCount: (state) => {
            return state.items.length;
        },
    },

    // Actions pour modifier l'état du panier
    actions: {
        /**
         * Ajoute un produit au panier. Si le produit existe déjà, incrémente sa quantité.
         * @param {Object} product - L'objet produit à ajouter (doit avoir id, name, price, image_path)
         * @param {number} [quantity=1] - La quantité à ajouter (par défaut 1)
         */
        addItem(product, quantity = 1) {
            const existingItem = this.items.find(
                (item) => item.product_id === product.id
            );

            if (existingItem) {
                existingItem.quantity += quantity;
            } else {
                this.items.push({
                    product_id: product.id,
                    name: product.name,
                    price: parseFloat(product.price),
                    quantity: quantity,
                    image_path: product.image_path,
                });
            }
            toast.success(`${product.name} a été ajouté au panier !`);
            // Persister le panier dans le localStorage
            this.saveCartToLocalStorage();
        },

        /**
         * Met à jour la quantité d'un produit spécifique dans le panier.
         * Si la quantité est <= 0, l'article est supprimé.
         * @param {number} productId - L'ID du produit à mettre à jour
         * @param {number} newQuantity - La nouvelle quantité
         */
        updateQuantity(productId, newQuantity) {
            const item = this.items.find(
                (item) => item.product_id === productId
            );

            if (item) {
                if (newQuantity > 0) {
                    item.quantity = newQuantity;
                } else {
                    // Si la quantité est 0 ou moins, supprime l'article
                    this.removeItem(productId);
                }
            }
            this.saveCartToLocalStorage();
        },

        /**
         * Supprime un produit du panier.
         * @param {number} productId - L'ID du produit à supprimer
         */
        removeItem(productId) {
            this.items = this.items.filter(
                (item) => item.product_id !== productId
            );
            this.saveCartToLocalStorage();
        },

        /**
         * Vide complètement le panier.
         */
        clearCart() {
            this.items = [];
            this.saveCartToLocalStorage();
        },

        /**
         * Persiste le panier dans le localStorage du navigateur.
         * Utile pour maintenir le panier après un rafraîchissement de page.
         */
        saveCartToLocalStorage() {
            localStorage.setItem("cart", JSON.stringify(this.items));
        },

        /**
         * Charge le panier depuis le localStorage au démarrage du store.
         */
        loadCartFromLocalStorage() {
            const storedCart = localStorage.getItem("cart");
            if (storedCart) {
                this.items = JSON.parse(storedCart);
            }
        },

        async placeOrder() {
            const orderItemsPayload = this.items.map((item) => ({
                product_id: item.product_id,
                quantity: item.quantity,
            }));

            const cartItemsPayload = {
                cart_items: orderItemsPayload,
            };

            this.loading = true;
            const ordersStore = useOrdersStore();

            try {
                await ordersStore.createOrder(cartItemsPayload);
            } catch (error) {
                throw new Error(
                    "Erreur lors de la création de la commande : " +
                        error.message
                );
            } finally {
                this.loading = false;
                this.clearCart(); // Vide le panier après la commande
            }
        },
    },
});
