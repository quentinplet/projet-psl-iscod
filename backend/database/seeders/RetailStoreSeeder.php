<?php

namespace Database\Seeders;

use App\Models\Address;
use App\Models\RetailStore;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RetailStoreSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Créer des magasins spécifiques avec adresses réelles
        $specificStores = [
            [
                'name' => 'Carrefour Paris Belleville',
                'phone' => '01 42 08 35 67',
                'email' => 'carrefour.belleville@carrefour.fr',
                'description' => 'Hypermarché Carrefour situé dans le 19ème arrondissement',
                'address' => [
                    'street' => '2 Rue du Plateau',
                    'city' => 'Paris',
                    'postal_code' => '75019',
                    'country' => 'France',
                    'additional_info' => 'Centre Commercial Belleville'
                ]
            ],
            [
                'name' => 'Leclerc Lyon Part-Dieu',
                'phone' => '04 78 95 45 23',
                'email' => 'lyon.partdieu@leclerc.fr',
                'description' => 'Supermarché E.Leclerc au cœur de Lyon',
                'address' => [
                    'street' => '17 Rue du Docteur Bouchut',
                    'city' => 'Lyon',
                    'postal_code' => '69003',
                    'country' => 'France',
                    'additional_info' => 'Quartier Part-Dieu'
                ]
            ],
            [
                'name' => 'Intermarché Marseille Vieux-Port',
                'phone' => '04 91 33 78 45',
                'email' => 'marseille.vieuxport@intermarche.fr',
                'description' => 'Supermarché Intermarché proche du Vieux-Port',
                'address' => [
                    'street' => '45 La Canebière',
                    'city' => 'Marseille',
                    'postal_code' => '13001',
                    'country' => 'France',
                    'additional_info' => 'Face au Vieux-Port'
                ]
            ],
            [
                'name' => 'Super U Toulouse Centre',
                'phone' => '05 61 23 67 89',
                'email' => 'toulouse.centre@superu.fr',
                'description' => 'Supermarché Super U en centre-ville',
                'address' => [
                    'street' => '12 Place du Capitole',
                    'city' => 'Toulouse',
                    'postal_code' => '31000',
                    'country' => 'France',
                    'additional_info' => 'Place du Capitole'
                ]
            ],
            [
                'name' => 'Casino Nice Libération',
                'phone' => '04 93 85 24 17',
                'email' => 'nice.liberation@casino.fr',
                'description' => 'Supermarché Casino - Temporairement fermé pour rénovation',
                'address' => [
                    'street' => '8 Avenue de la Libération',
                    'city' => 'Nice',
                    'postal_code' => '06000',
                    'country' => 'France',
                    'additional_info' => 'Avenue de la Libération'
                ]
            ]
        ];

        // Créer les magasins spécifiques
        foreach ($specificStores as $storeData) {
            $addressData = $storeData['address'];
            unset($storeData['address']);

            $adress = Address::create($addressData);
            $storeData['address_id'] = $adress->id;
            RetailStore::create($storeData);
        }
    }
}
