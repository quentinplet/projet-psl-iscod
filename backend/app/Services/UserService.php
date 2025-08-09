<?php

namespace App\Services;

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;

class UserService
{
    /**
     * Récupère une liste paginée et filtrée d'utilisateurs.
     */
    public function getFilteredUsers(User $currentUser, array $filters): LengthAwarePaginator
    {
        $search = $filters['search'] ?? '';
        $perPage = $filters['per_page'] ?? 10;
        $sortField = $filters['sort_field'] ?? 'updated_at';
        $sortDirection = $filters['sort_direction'] ?? 'desc';
        $roleFilter = $filters['role_filter'] ?? null;
        $retailStoreId = $filters['retail_store_id'] ?? null;

        $query = User::query(); // On commence la requête depuis le modèle User

        $query->visibleForUser($currentUser);


        // Déterminer si une jointure est nécessaire
        $needsJoin = !empty($search) || $sortField === 'retail_store_name' || $retailStoreId !== null;

        if ($needsJoin) {
            $query->leftJoin('retail_stores', 'users.retail_store_id', '=', 'retail_stores.id')
                ->select('users.*'); // Important pour ne pas écraser les colonnes de User
        }

        // Filtre de recherche
        $query->when($search, function ($q) use ($search, $needsJoin) {
            $q->where(function ($sq) use ($search, $needsJoin) {
                $sq->where('users.name', 'like', "%$search%")
                    ->orWhere('users.email', 'like', "%$search%")
                    ->orWhere('users.role', 'like', "%$search%");

                if ($needsJoin) {
                    $sq->orWhere('retail_stores.name', 'like', "%$search%");
                }
            });
        });

        // Filtre par rôle
        $query->when($roleFilter, function ($q) use ($roleFilter) {
            return $q->where('users.role', $roleFilter);
        });

        // Filtre par point de vente
        $query->when($retailStoreId, function ($q) use ($retailStoreId) {
            return $q->where('users.retail_store_id', $retailStoreId);
        });

        // Tri
        if ($sortField === 'retail_store_name') {
            $query->orderBy('retail_stores.name', $sortDirection);
        } else {
            $query->orderBy('users.' . $sortField, $sortDirection);
        }

        // Charger la relation retailStore si l'utilisateur est un manager
        if ($currentUser->hasRole(UserRole::MANAGER->value)) {
            $query->with('retailStore');
        }

        return $query->paginate($perPage);
    }

    /**
     * Récupère tous les rôles d'utilisateur disponibles.
     */
    public function getAllUserRoles(): array
    {
        return UserRole::cases();
    }

    /**
     * Récupère les rôles spécifiques qu'un administrateur peut voir ou gérer.
     */
    public function getRolesForAdminUser(User $user): array
    {
        if ($user->hasRole(UserRole::ADMIN->value)) {
            return [UserRole::MANAGER->value, UserRole::LOGISTICIAN->value];
        }

        return [];
    }
}
