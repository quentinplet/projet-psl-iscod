<?php

namespace Database\Factories;

use App\Models\Order;
use App\Enums\OrderStatus; // N'oubliez pas d'importer votre Enum OrderStatus
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Order>
 */
class OrderFactory extends Factory
{
    protected $model = Order::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // Récupère un utilisateur existant avec le rôle 'acheteur' aléatoirement.
        // Si aucun utilisateur 'acheteur' n'existe, il en crée un par défaut avec ce rôle.
        $user = User::where('role', 'acheteur')->inRandomOrder()->first();

        // --- LIGNE DE DÉBOGAGE TEMPORAIRE ---
        // Vérifiez la valeur de $user ici. Si c'est null, le premier appel n'a rien trouvé.
        // Si c'est un objet User, alors le problème n'est pas là.
        // dump('User found by role "acheteur": ' . ($user ? $user->id : 'null'));
        // --- FIN LIGNE DE DÉBOGAGE ---

        // Si aucun utilisateur 'acheteur' n'est trouvé, créez-en un.
        // Assurez-vous que votre UserFactory a une méthode `acheteur()` ou `client()`
        // pour créer des utilisateurs avec ce rôle.
        if (!$user) {
            $user = User::factory()->create(['role' => 'acheteur']); // Crée un user avec le rôle 'acheteur'
            // dump('New "acheteur" user created with ID: ' . $user->id); // Debug si un nouvel user est créé
        }

        return [
            'total_price' => 0.00, // Sera calculé après l'ajout des order items
            'status' => fake()->randomElement(array_column(OrderStatus::cases(), 'value')), // Statut aléatoire de l'Enum
            'created_by' => $user->id,
            'updated_by' => null, // Sera mis à jour par un logisticien/admin s'il y a un changement de statut
        ];
    }

    /**
     * Indique que la commande doit avoir un statut spécifique.
     */
    public function withStatus(OrderStatus $status): Factory
    {
        return $this->state(fn(array $attributes) => [
            'status' => $status->value,
        ]);
    }

    /**
     * Indique que la commande doit être créée par un utilisateur spécifique.
     */
    public function forUser(User $user): Factory
    {
        return $this->state(fn(array $attributes) => [
            'created_by' => $user->id,
        ]);
    }
}
