<x-app-layout>
    <!-- Product List -->
    <div class="grid gap-8 grig-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 p-5">
        @foreach ($products as $product)
            <!-- Product Item -->
            <!-- prettier-ignore -->
            <div x-data="productItem({ 
            id: {{ $product->id }},
            title: '{{ $product->title }}',
            price: '{{ $product->price }}',
            image: '{{ $product->image }}',
            addToCartUrl: '{{ route('cart.add', $product) }}'
            })"
            class="row-span-3 grid grid-rows-subgrid border border-1 border-gray-200 rounded-md hover:border-purple-600 transition-colors bg-white">
            <a href="{{ route('product.view', $product->slug) }}" class="block overflow-hidden aspect-w-4 aspect-h-3">
                <img src="{{ $product->image }}" alt=""
                    class="rounded-lg hover:scale-105 hover:rotate-1 transition-transform object-cover object-center" />
            </a>
            <div class="p-4 grid space-y-2">
                <h3 class="text-lg">
                    <a href="{{ route('product.view', $product->slug) }}">
                        {{ $product->title }}
                    </a>
                </h3>
                <h5 class="font-bold">${{ $product->price }}</h5>
            </div>
            <div class="flex justify-between py-3 px-4">
                <button class="btn-primary" @click="addToCart()">
                    Add to Cart
                </button>
            </div>
    </div>
    <!--/ Product Item -->
    @endforeach
    </div>
    <!--/ Product pagination -->
    {{ $products->links() }}
</x-app-layout>
