<?php

namespace App\Services;

use App\Enums\OrderStatus;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User; // Importez le modèle User pour le typage
use Illuminate\Support\Facades\DB;

class OrderService
{
    public function createOrderFromCart(array $cartItems, User $user): Order
    {
        // On récupère les informations de la bdd des produits dans le panier
        $productIds = collect($cartItems)->pluck('product_id')->toArray();
        $products = Product::whereIn('id', $productIds)->get()->keyBy('id');

        $orderItemsData = [];
        $totalPrice = 0;

        foreach ($cartItems as $item) {
            $productId = $item['product_id'];
            $quantity = $item['quantity'];

            if ($quantity <= 0) {
                throw new \Exception("La quantité pour le produit ID $productId est invalide.");
            }

            $product = $products->get($productId);


            if (!$product || !$product->quantity < 0) {
                throw new \Exception("Le produit ID $productId n'existe pas ou n'est pas disponible.");
            }

            $itemUnitPrice = $product->price;
            $orderItemsData[] = [
                'product_id' => $product->id,
                'quantity' => $quantity,
                'unit_price' => $itemUnitPrice,
            ];

            $totalPrice += $itemUnitPrice * $quantity;
        }

        return DB::transaction(function () use ($totalPrice, $user, $orderItemsData) {
            $orderData = [
                'total_price' => $totalPrice,
                'status' => OrderStatus::Pending,
                'user_id' => $user->id,
            ];

            $order = Order::create($orderData);


            $orderItemsToInsert = collect($orderItemsData)->map(function ($item) use ($order) {
                $now = now();
                $item['order_id'] = $order->id;
                $item['created_at'] = $now;
                $item['updated_at'] = $now;
                return $item;
            })->all();

            OrderItem::insert($orderItemsToInsert); // Utiliser insert() pour l'efficacité

            return $order;
        });
    }
}
