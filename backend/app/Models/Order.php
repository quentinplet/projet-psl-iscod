<?php

namespace App\Models;

use App\Enums\OrderStatus;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Order extends Model
{
    use HasFactory;

    protected $fillable = ['status', 'total_price', 'user_id'];


    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function scopeSearch(Builder $query, $search)
    {
        if (!empty($search)) {
            $query->where('status', 'like', '%' . $search . '%')
                ->orWhere('id', 'like', '%' . $search . '%')
                ->orWhereHas('user', function (Builder $query) use ($search) {
                    $query->where('name', 'like', '%' . $search . '%');
                });
        }
        return $query;
    }

    public function scopeFilterByStatus(Builder $query, $status)
    {
        if ($status && $status !== 'Tous les statuts') {
            $query->where('status', $status);
        }
        return $query;
    }

    /**
     * Scope pour appliquer le tri dynamique, y compris sur les relations.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $sortField Le champ sur lequel trier. Peut être 'user.name' ou 'order.user.name'.
     * @param string $sortDirection La direction du tri ('asc' ou 'desc').
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeSortByField(Builder $query, string $sortField, string $sortDirection): Builder
    {

        // La condition a été modifiée pour correspondre au champ envoyé par le frontend
        if ($sortField === 'order.user.name' || $sortField === 'user.name') {
            // Si le tri est sur 'user.name', on effectue une jointure explicite
            $query->join('users', 'orders.created_by', '=', 'users.id')
                // Et on trie sur la colonne 'name' de la table 'users'
                ->orderBy('users.name', $sortDirection)
                // TRÈS IMPORTANT : Sélectionner toutes les colonnes de 'orders'
                // pour éviter les conflits de colonnes (ex: 'id' est dans les deux tables)
                // et s'assurer que le modèle Order est correctement hydraté.
                ->select('orders.*');
        } else {
            // Pour tous les autres champs de tri (qui sont des colonnes directes de la table 'orders')
            $query->orderBy($sortField, $sortDirection);
        }

        return $query;
    }
}
