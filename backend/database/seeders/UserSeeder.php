<?php

namespace Database\Seeders;

use App\Enums\UserRole;
use App\Models\Customer;
use App\Models\RetailStore;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        // Récupère tous les magasins créés (ceux du RetailStoreSeeder)
        $retailStores = RetailStore::with('address')->get();


        //One Manager for testing
        User::create([
            'name' => 'Gestionnaire',
            'email' => 'gestionnaire@example.com',
            'email_verified_at' => now(),
            'password' => bcrypt('iscod123#'),
            'role' => UserRole::MANAGER,
        ]);

        //One Customer for testing
        User::create([
            'name' => 'Acheteur',
            'email' => 'acheteur@example.com',
            'email_verified_at' => now(),
            'password' => bcrypt('iscod123#'),
            'role' => UserRole::CUSTOMER,
            'retail_store_id' => $retailStores->random()->id,
        ]);

        //Manager
        User::factory()->count(3)->create([
            'role' => UserRole::MANAGER,
        ]);

        //Logistician
        User::factory()->count(3)->create([
            'role' => UserRole::LOGISTICIAN,
        ]);

        User::factory()->count(5)->create([
            'role' => UserRole::CUSTOMER,
        ])->each(function ($user) use ($retailStores) {
            $user->retail_store_id = $retailStores->random()->id;
            $user->save();
        });
    }
}
