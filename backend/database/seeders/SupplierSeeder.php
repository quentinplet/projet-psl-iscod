<?php

namespace Database\Seeders;

use App\Models\Supplier;
use App\Models\Address;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SupplierSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $suppliers = [
            [
                'name' => 'Rayonnages Pro France',
                'email' => 'contact@rayonnagespro.fr',
                'phone' => '01 42 73 85 96',
                'siret' => '12345678901234',
                'description' => 'Spécialiste des rayonnages métalliques et solutions de stockage pour points de vente. Plus de 20 ans d\'expérience dans l\'aménagement commercial.'
            ],
            [
                'name' => 'PLV Solutions',
                'email' => 'commercial@plvsolutions.fr',
                'phone' => '02 41 86 72 34',
                'siret' => '23456789012345',
                'description' => 'Conception et fabrication de supports publicitaires sur lieu de vente. PLV carton, plastique et métal pour tous secteurs d\'activité.'
            ],
            [
                'name' => 'Bureau Direct',
                'email' => 'commandes@bureaudirect.fr',
                'phone' => '03 20 45 67 89',
                'siret' => '34567890123456',
                'description' => 'Distributeur de fournitures de bureau et consommables informatiques. Livraison rapide partout en France métropolitaine.'
            ],
            [
                'name' => 'Mobilier Commercial Ouest',
                'email' => 'info@mobilierco.fr',
                'phone' => '02 99 38 21 45',
                'siret' => '45678901234567',
                'description' => 'Fabricant de mobilier sur mesure pour magasins et grandes surfaces. Spécialiste de l\'agencement commercial depuis 1985.'
            ],
            [
                'name' => 'Étiquetage & Signalétique',
                'email' => 'vente@etiquetage-sign.fr',
                'phone' => '04 72 91 56 78',
                'siret' => '56789012345678',
                'description' => 'Production d\'étiquettes de prix, signalétique intérieure et extérieure. Solutions d\'identification pour le retail et la grande distribution.'
            ],
            [
                'name' => 'Emballages Carton Sud',
                'email' => 'contact@emballages-sud.fr',
                'phone' => '04 91 23 45 67',
                'siret' => '67890123456789',
                'description' => 'Fabrication d\'emballages carton personnalisés, présentoirs et supports promotionnels. Impression offset et numérique haute qualité.'
            ],
            [
                'name' => 'Display & Merchandising',
                'email' => 'commercial@display-merch.fr',
                'phone' => '01 48 92 73 85',
                'siret' => '78901234567890',
                'description' => 'Création de concepts merchandising et supports de communication. Accompagnement global des enseignes dans leur stratégie point de vente.'
            ],
            [
                'name' => 'Équipements Magasins Pro',
                'email' => 'devis@equipmagasins.fr',
                'phone' => '05 61 34 78 92',
                'siret' => '89012345678901',
                'description' => 'Équipement complet de magasins : caisses, balances, étiqueteuses, mobilier d\'accueil. Installation et maintenance assurées.'
            ],
            [
                'name' => 'Supports Publicitaires France',
                'email' => 'info@supports-pub.fr',
                'phone' => '03 87 45 62 18',
                'siret' => '90123456789012',
                'description' => 'Spécialiste des supports publicitaires rigides et souples. Kakémonos, banderoles, panneaux et habillage de vitrine sur mesure.'
            ],
            [
                'name' => 'Logistique & Conditionnement',
                'email' => 'contact@logicond.fr',
                'phone' => '02 32 67 89 43',
                'siret' => '01234567890123',
                'description' => 'Services de conditionnement, palettisation et préparation de commandes. Solutions logistiques adaptées aux besoins de la grande distribution.'
            ]
        ];

        foreach ($suppliers as $index => $supplierData) {
            // Créer une adresse fictive pour chaque fournisseur
            $address = Address::create([
                'street' => ($index + 1) . ' Rue de l\'Industrie',
                'city' => ['Paris', 'Lyon', 'Marseille', 'Toulouse', 'Nice', 'Nantes', 'Strasbourg', 'Montpellier', 'Bordeaux', 'Lille'][$index],
                'postal_code' => ['75001', '69001', '13001', '31000', '06000', '44000', '67000', '34000', '33000', '59000'][$index],
                'country' => 'France'
            ]);

            // Créer le fournisseur et l'associer à l'adresse
            Supplier::create([
                'name' => $supplierData['name'],
                'email' => $supplierData['email'],
                'phone' => $supplierData['phone'],
                'siret' => $supplierData['siret'],
                'description' => $supplierData['description'],
                'address_id' => $address->id,
            ]);
        }
    }
}
