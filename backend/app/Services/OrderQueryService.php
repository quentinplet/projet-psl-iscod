<?php

namespace App\Services;

use App\Models\Order;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class OrderQueryService
{
    public function filteredQuery(User $user, array $params): Builder
    {
        $query = Order::with(['user.retailStore', 'items.product']);

        // Si l'utilisateur est acheteur, on filtre sur son propre ID
        if ($user->hasRole('acheteur')) {
            $query->where('user_id', $user->id);
        }

        // Si un retail_store_id est fourni, on filtre via la relation user
        if (!empty($params['retail_store_id'])) {
            $query->whereHas('user', function (Builder $q) use ($params) {
                $q->where('retail_store_id', $params['retail_store_id']);
            });
        }

        return $query;
    }
}
