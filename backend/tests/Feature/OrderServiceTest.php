<?php

use App\Enums\OrderStatus;
use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use App\Models\Supplier;
use App\Models\User;
use App\Services\OrderService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OrderServiceTest extends TestCase
{
    use RefreshDatabase;

    public function testCreateOrderFromCart()
    {
        /**
         * Préparation des données pour le test PLAN
         */

        // Créer un utilisateur de type acheteur pour la commande
        $user = User::factory()->create(['role' => 'acheteur']);

        $cartItems = [
            ['product_id' => 1, 'quantity' => 3],
            ['product_id' => 2, 'quantity' => 2],
        ];

        //Mock catégories et fournisseurs
        $category = Category::factory()->create();
        $supplier = Supplier::factory()->create();

        // Mock des produits
        Product::factory()->create([
            'price' => 100,
            'quantity' => 10,
            'category_id' => $category->id,
            'supplier_id' => $supplier->id,
        ]);
        Product::factory()->create([
            'price' => 100,
            'quantity' => 5,
            'category_id' => $category->id,
            'supplier_id' => $supplier->id,
        ]);



        /**
         * Exécution du service pour créer la commande à partir du panier DO
         */

        $orderService = new OrderService();
        $order = $orderService->createOrderFromCart($cartItems, $user);


        /**
         * Assertions pour vérifier que la commande a été créée correctement ACT
         */

        // Assertions : vérifier que les lignes de commande ont été créées correctement
        $this->assertCount(2, $order->items);
        $this->assertEquals(100, $order->items[0]->unit_price);
        $this->assertEquals(100, $order->items[1]->unit_price);
        $this->assertEquals(3, $order->items[0]->quantity);
        $this->assertEquals(2, $order->items[1]->quantity);

        // Assertions : vérifier que la commande a été créée correctement
        $this->assertInstanceOf(Order::class, $order);
        $this->assertEquals(500, $order->total_price);
        $this->assertEquals(OrderStatus::Pending, $order->status);
        $this->assertEquals($user->id, $order->user_id);

        // Vérifier que les items de la commande ont été enregistrés dans la base de données
        $this->assertDatabaseHas('order_items', [
            'order_id' => $order->id,
            'product_id' => 1,
            'quantity' => 3,
            'unit_price' => 100,
        ]);
        $this->assertDatabaseHas('order_items', [
            'order_id' => $order->id,
            'product_id' => 2,
            'quantity' => 2,
            'unit_price' => 100,
        ]);

        // Vérifier que la commande a été enregistrée dans la base de données
        $this->assertDatabaseHas('orders', [
            'id' => $order->id,
            'total_price' => 500,
            'status' => OrderStatus::Pending,
            'user_id' => $user->id,
        ]);
    }
}
