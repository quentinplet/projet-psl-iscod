<?php
/** @var \Illuminate\Database\Eloquent\Collection $orders */
?>

<x-app-layout>
    <div class="container lg:w-2/3 xl:w-2/3 mx-auto">
        <h1 class="text-3xl font-bold mb-6">Order #{{ $order->id }} Details</h1>

        <div class="bg-white p-3 rounded-md shadow-md">
            <div>
                <table class="table-sm">
                    <tbody>
                        <tr>
                            <td class="font-bold">Order #</td>
                            <td>{{ $order->id }}</td>
                        </tr>
                        <tr>
                            <td class="font-bold">Order Date</td>
                            <td>{{ $order->created_at }}/td>
                        </tr>
                        <tr>
                            <td class="font-bold">Status</td>
                            <td>
                                <span
                                    class="text-white p-1 rounded {{ $order->isPaid() ? 'bg-emerald-500' : 'bg-gray-400' }}">{{ $order->status }}</span>
                            </td>
                        </tr>
                        <tr>
                            <td class="font-bold">SubTotal</td>
                            <td>${{ $order->total_price }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <hr class="my-5" />

            <!-- Order Items -->
            @foreach ($order->items as $item)
                <!-- Product Item -->
                <div class="flex gap-6">
                    <a href="{{ route('product.view', $item->product) }}"
                        class="w-16 h-16 flex items-center border border-gray-300">
                        <img src="{{ $item->product->image }}" alt="" />
                    </a>
                    <div class="flex-1 flex flex-col justify-between pb-3">
                        <h3 class="text-ellipsis mb-4">
                            {{ $item->product->title }}
                        </h3>
                    </div>
                    <div class="flex flex-col justify-between items-end pb-3 w-32">
                        <div class="text-lg mb-4">${{ $item->unit_price }}</div>
                        <div class="text-gray-500 text-sm">
                            Qty: {{ $item->quantity }}
                        </div>
                    </div>
                </div>
                <!--/ Product Item -->
                <hr class="my-2" />
            @endforeach


            <!--/ Order Items -->

            {{-- @if (!$order->isPaid()) --}}
            <form action="{{ route('cart.checkout-order', $order) }}" method="post">
                @csrf
                <button class="btn-primary w-full py-3 px-2 flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-1" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                    </svg>
                    Make Payment
                </button>
            </form>
            {{-- @endif --}}

        </div>
    </div>
</x-app-layout>
