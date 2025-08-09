<?php

namespace Database\Factories;

use App\Models\OrderItem;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\OrderItem>
 */
class OrderItemFactory extends Factory
{
    protected $model = OrderItem::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // Récupère un produit existant aléatoire.
        // Si aucun produit n'existe, il en crée un par défaut.
        $product = Product::inRandomOrder()->first() ?? Product::factory()->create();

        $quantity = fake()->numberBetween(1, 5);
        $unitPrice = $product->price; // Le prix unitaire est basé sur le prix du produit

        return [
            // Liera à une commande générée si non spécifiée, ou à une commande existante via forOrder()
            'order_id' => Order::factory(),
            'product_id' => $product->id,
            'quantity' => $quantity,
            'unit_price' => $unitPrice,
        ];
    }

    /**
     * Indique que la ligne de commande appartient à une commande spécifique.
     */
    public function forOrder(Order $order): Factory
    {
        return $this->state(fn(array $attributes) => [
            'order_id' => $order->id,
        ]);
    }

    /**
     * Indique que la ligne de commande est pour un produit spécifique avec une quantité optionnelle.
     */
    public function forProduct(Product $product, int $quantity = null): Factory
    {
        return $this->state(fn(array $attributes) => [
            'product_id' => $product->id,
            'quantity' => $quantity ?? fake()->numberBetween(1, 5),
            'unit_price' => $product->price,
        ]);
    }
}
