<?php

namespace App\Models;

use App\Enums\UserRole;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Builder; // N'oubliez pas d'importer Builder

class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable, HasApiTokens;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'retail_store_id',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'role' => UserRole::class,
        ];
    }

    public function retailStore()
    {
        return $this->belongsTo(RetailStore::class);
    }

    public function hasRole(string $role): bool
    {
        return $this->role->value === $role;
    }

    /**
     * Scope pour filtrer les utilisateurs visibles par un utilisateur donné.
     */
    public function scopeVisibleForUser(Builder $query, User $user): Builder
    {
        if ($user->hasRole(UserRole::ADMIN->value)) {
            // Un admin voit les gestionnaires et les logisticiens
            $query->whereIn('role', [UserRole::MANAGER->value, UserRole::LOGISTICIAN->value]);
        }

        if ($user->hasRole(UserRole::MANAGER->value)) {
            // Un gestionnaire ne voit que les clients
            $query->where('role', UserRole::CUSTOMER->value);
        }

        return $query;
    }
}
