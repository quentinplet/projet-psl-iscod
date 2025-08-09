<x-app-layout>
    <div class="container lg:w-2/3 xl:w-2/3 mx-auto">
        <h1 class="text-3xl font-bold mb-6">Your Cart Items</h1>

        <div x-data="{
            cartItems: {{ json_encode(
                $products->map(
                    fn($product) => [
                        'id' => $product->id,
                        'slug' => $product->slug,
                        'image' => $product->image,
                        'title' => $product->title,
                        'price' => $product->price,
                        'quantity' => $cartItems[$product->id]['quantity'],
                        'href' => route('product.view', $product->slug),
                        'removeFromCartUrl' => route('cart.remove', $product),
                        'updateQuantityUrl' => route('cart.update-quantity', $product),
                    ],
                ),
            ) }},
            get cartTotal() {
                return this.cartItems.reduce((acc, item) => acc + (item.price * item.quantity), 0).toFixed(2);
            }
        }" class="bg-white rounded-lg p-3">
            <template x-if="cartItems.length">
                <div>
                    <template x-for="product of cartItems" :key="product.id">
                        <div x-data="productItem(product)">
                            <div>
                                <div x-data="productItem(product)" class="w-full flex flex-col sm:flex-row items-center gap-4">
                                    <a href="/src/product.html"
                                        class="w-36 h-32 flex items-center justify-center overflow-hidden">
                                        <img :src="product.image" class="object-cover" alt="" />
                                    </a>
                                    <div class="flex-1 flex flex-col justify-between">
                                        <div class="flex justify-between mb-3">
                                            <h3 x-text="product.title"></h3>
                                            <span class="text-lg font-semibold">
                                                $<span x-text="product.price"></span>
                                            </span>
                                        </div>
                                        <div class="flex justify-between items-center">
                                            <div class="flex items-center">
                                                Qty:
                                                <input type="number" min="1" x-model="product.quantity"
                                                    @change="changeQuantity()"
                                                    class="ml-3 py-1 border-2 px-1 outline-hidden border-gray-200 focus:border-purple-500 focus:ring focus:ring-purple-600 w-16" />
                                            </div>
                                            <a @click.prevent="removeItemFromCart()" href="#"
                                                class="text-purple-600 hover:text-purple-500">Remove</a>
                                        </div>
                                    </div>
                                </div>
                                <!--/ Product Item -->
                                <hr class="my-5" />
                            </div>
                        </div>
                    </template>
                    <div class="border-t border-gray-300 pt-4">
                        <div class="flex justify-between">
                            <span class="font-semibold">Subtotal</span>
                            <span class="text-xl" x-text="`$${cartTotal}`"></span>
                        </div>
                        <p class="text-gray-500 mb-6">
                            Shipping and taxes calculated at checkout.
                        </p>
                        <form action="{{ route('cart.checkout') }}" method="post">
                            @csrf
                            <button type="submit" class="btn-primary w-full py-3 text-lg">
                                Proceed to Checkout
                            </button>
                        </form>
                    </div>
                </div>
            </template>
            <template x-if="!cartItems.length">
                <div class="text-center py-8 text-gray-500">
                    Your cart is empty.
                </div>
            </template>

        </div>

    </div>
    </div>
</x-app-layout>
