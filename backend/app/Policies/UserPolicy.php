<?php

namespace App\Policies;

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class UserPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return false;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, User $model): bool
    {
        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user, array $data)
    {
        // Seulement les administrateurs peuvent créer des gestionnaires ou des logisticiens
        if (
            $user->hasRole(UserRole::ADMIN->value) &&
            in_array($data['role'], [UserRole::MANAGER->value, UserRole::LOGISTICIAN->value])
        ) {
            return true;
        }

        if ($user->hasRole(UserRole::MANAGER->value)) {
            // Les gestionnaires ne peuvent créer que des acheteurs
            return $data['role'] === UserRole::CUSTOMER->value;
        }

        return Response::deny('You do not have permission to create this user.');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, User $model, array $data)
    {
        // Seulement les administrateurs peuvent mettre à jour les gestionnaires ou les logisticiens
        if ($user->hasRole(UserRole::ADMIN->value) && in_array($model->role->value, [UserRole::MANAGER->value, UserRole::LOGISTICIAN->value])) {
            return true;
        }
        // Les gestionnaires peuvent mettre à jour les acheteurs, mais pas les autres gestionnaires ou logisticiens
        if ($user->hasRole(UserRole::MANAGER->value) && $model->role->value === UserRole::CUSTOMER->value && $data['role'] === UserRole::CUSTOMER->value) {
            return true;
        }

        return Response::deny('You do not have permission to update this user.');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, User $model)
    {
        // Only admins can delete managers or logisticians
        if ($user->hasRole(UserRole::ADMIN->value) && in_array($model->role->value, [UserRole::MANAGER->value, UserRole::LOGISTICIAN->value])) {
            return true;
        }
        // Managers can delete customers, but not other managers or logisticians
        if ($user->hasRole(UserRole::MANAGER->value) && $model->role->value === UserRole::CUSTOMER->value) {
            return true;
        }
        return Response::deny('You do not have permission to delete this user.');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, User $model): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, User $model): bool
    {
        return false;
    }
}
