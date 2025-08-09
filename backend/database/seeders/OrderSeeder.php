<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
use App\Enums\OrderStatus;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Carbon\Carbon; // Pour les timestamps

class OrderSeeder extends Seeder
{
    /**
     * Exécute les seeds de la base de données.
     */
    public function run(): void
    {
        $ordersData = [
            [
                'order_details' => [
                    'total_price' => 270.97,
                    'status' => OrderStatus::Pending->value,
                    'user_id' => 1, // ID d'un utilisateur 'acheteur'
                    'created_at' => '2025-07-20 10:30:00',
                    'updated_at' => '2025-07-20 11:00:00',
                    'expected_delivery_date' => '2025-07-25', // Date de livraison prévue
                ],
                'items' => [
                    ['product_id' => 1, 'quantity' => 1, 'unit_price' => 189.99], // Rayonnage
                    ['product_id' => 4, 'quantity' => 2, 'unit_price' => 29.95],  // Cartouche encre
                    ['product_id' => 8, 'quantity' => 1, 'unit_price' => 15.00],  // Bac rangement
                ],
            ],
            [
                'order_details' => [
                    'total_price' => 91.00,
                    'status' => OrderStatus::Pending->value,
                    'user_id' => 10, // ID d'un autre utilisateur 'acheteur'

                    'created_at' => '2025-07-22 14:15:00',
                    'updated_at' => '2025-07-22 14:15:00',
                    'expected_delivery_date' => '2025-08-27', // Date de livraison prévue
                ],
                'items' => [
                    ['product_id' => 2, 'quantity' => 2, 'unit_price' => 45.50],  // Présentoir PLV
                ],
            ],
            [
                'order_details' => [
                    'total_price' => 17.97,
                    'status' => OrderStatus::Pending->value,
                    'user_id' => 1,
                    'created_at' => '2025-07-24 09:00:00',
                    'updated_at' => '2025-07-24 09:30:00',
                ],
                'items' => [
                    ['product_id' => 3, 'quantity' => 3, 'unit_price' => 5.99],   // Ramette papier
                ],
            ],
            [
                'order_details' => [
                    'total_price' => 37.50,
                    'status' => OrderStatus::Shipped->value,
                    'user_id' => 11, // ID d'un utilisateur 'acheteur'
                    'created_at' => '2025-07-18 16:40:00',
                    'updated_at' => '2025-07-19 10:00:00',
                    'expected_delivery_date' => '2025-07-30', // Date de livraison prévue
                ],
                'items' => [
                    ['product_id' => 6, 'quantity' => 3, 'unit_price' => 12.50],  // Film étirable
                ],
            ],
            [
                'order_details' => [
                    'total_price' => 79.60,
                    'status' => OrderStatus::Delivered->value,
                    'user_id' => 12, // ID d'un utilisateur 'acheteur'
                    'created_at' => '2025-07-15 11:20:00',
                    'updated_at' => '2025-07-17 15:00:00',
                    'expected_delivery_date' => '2025-07-22', // Date de livraison prévue
                ],
                'items' => [
                    ['product_id' => 7, 'quantity' => 4, 'unit_price' => 19.90],  // Étiquettes adhésives
                ],
            ],
            [
                'order_details' => [
                    'total_price' => 17.50,
                    'status' => OrderStatus::ReadyForShipment->value,
                    'user_id' => 13,

                    'created_at' => '2025-07-28 08:00:00',
                    'updated_at' => '2025-07-28 08:00:00',
                    'expected_delivery_date' => '2025-08-02', // Date de livraison prévue
                ],
                'items' => [
                    ['product_id' => 5, 'quantity' => 2, 'unit_price' => 8.75],   // Stylo bille
                ],
            ],
            [
                'order_details' => [
                    'total_price' => 5.98,
                    'status' => OrderStatus::Canceled->value,
                    'user_id' => 14,
                    'created_at' => '2025-07-21 13:00:00',
                    'updated_at' => '2025-07-21 13:10:00',
                    'expected_delivery_date' => '2025-07-26', // Date de livraison prévue
                ],
                'items' => [
                    ['product_id' => 9, 'quantity' => 2, 'unit_price' => 2.99],   // Ruban adhésif
                ],
            ],
            [
                'order_details' => [
                    'total_price' => 70.00,
                    'status' => OrderStatus::Pending->value,
                    'user_id' => 14,
                    'created_at' => '2025-07-23 10:00:00',
                    'updated_at' => '2025-07-23 11:00:00',
                    'expected_delivery_date' => '2025-07-28', // Date de livraison prévue
                ],
                'items' => [
                    ['product_id' => 10, 'quantity' => 2, 'unit_price' => 35.00], // Pistolet à étiqueter
                ],
            ],
            [
                'order_details' => [
                    'total_price' => 23.00,
                    'status' => OrderStatus::Shipped->value,
                    'user_id' => 10,
                    'created_at' => '2025-07-19 09:00:00',
                    'updated_at' => '2025-07-20 09:00:00',
                    'expected_delivery_date' => '2025-07-24', // Date de livraison prévue
                ],
                'items' => [
                    ['product_id' => 11, 'quantity' => 2, 'unit_price' => 11.50], // Porte-affiches
                ],
            ],
            [
                'order_details' => [
                    'total_price' => 15.80,
                    'status' => OrderStatus::Delivered->value,
                    'user_id' => 11,
                    'created_at' => '2025-07-16 14:00:00',
                    'updated_at' => '2025-07-17 10:00:00',
                    'expected_delivery_date' => '2025-07-21', // Date de livraison prévue
                    'delivered_at' => Carbon::now(), // Date de livraison effective
                ],
                'items' => [
                    ['product_id' => 12, 'quantity' => 2, 'unit_price' => 7.90],  // Agrafeuse
                ],
            ],
        ];

        foreach ($ordersData as $orderData) {
            // Crée la commande principale
            $order = Order::create($orderData['order_details']);

            // Prépare les données des OrderItems pour l'insertion en masse
            $itemsToInsert = [];
            foreach ($orderData['items'] as $item) {
                $itemsToInsert[] = array_merge($item, [
                    'order_id' => $order->id,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ]);
            }

            // Insère toutes les lignes de commande en une seule requête
            OrderItem::insert($itemsToInsert);
        }
    }
}
