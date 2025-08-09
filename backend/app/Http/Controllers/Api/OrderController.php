<?php

namespace App\Http\Controllers\Api;

use App\Enums\OrderStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreOrderRequest;
use App\Http\Resources\OrderListResource;
use App\Models\Order;
use App\Services\DataQueryService;
use App\Services\OrderService;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules\Enum; // Pour valider l'Enum
use App\Services\OrderQueryService;


class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(OrderQueryService $orderQueryService, DataQueryService $dataQueryService)
    {
        /** @var \App\Models\User $user */
        $user = request()->user();
        $params = request()->only(['search', 'per_page', 'sort_field', 'sort_direction', 'status', 'retail_store_id']);

        $baseQuery = $orderQueryService->filteredQuery($user, $params);;
        $orders = $dataQueryService->getFilteredList($baseQuery, $params);

        return OrderListResource::collection($orders);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreOrderRequest $request, OrderService $orderService)
    {
        /** @var \App\Models\User $user */
        $user = $request->user();

        $cartItems = $request->input('cart_items');

        try {
            // Déléguez la création de la commande au service
            $order = $orderService->createOrderFromCart($cartItems, $user);

            return response()->json([
                'message' => 'La commande a été créée avec succès.',
                'order_id' => $order->id,
                'total_price' => $order->total_price,
                'status' => $order->status,
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Erreur lors de la création de la commande : ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $order = Order::findOrFail($id);
        $order->load(['user.retailStore', 'items.product']);
        return new OrderListResource($order);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validatedData = $request->validate([
            'status' => ['required', 'string', new Enum(OrderStatus::class)],
        ]);

        /** @var \App\Models\User $user */
        $user = $request->user();

        $order = Order::findOrFail($id);
        $order->status = $validatedData['status'];
        $order->save();

        return response()->json([
            'message' => 'La commande a été mise à jour avec succès.',
            'order' => new OrderListResource($order),
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $order = Order::findOrFail($id);
        $order->delete();

        return response()->json([
            'message' => 'La commande a été supprimée avec succès.',
        ], 200);
    }

    public function getOrderStatuses()
    {
        return response()->json([
            'statuses' => OrderStatus::cases(),
        ]);
    }
}
