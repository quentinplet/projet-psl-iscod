<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Rayonnages et Gondoles',
                'description' => 'Rayonnages métalliques, gondoles centrales et murales, têtes de gondoles pour l\'agencement des points de vente.'
            ],
            [
                'name' => 'PLV et Signalétique',
                'description' => 'Publicité sur lieu de vente, supports promotionnels, panneaux d\'affichage et signalétique intérieure.'
            ],
            [
                'name' => 'Fournitures de Bureau',
                'description' => 'Papeterie, consommables informatiques, classement et organisation pour les espaces administratifs.'
            ],
            [
                'name' => 'Mobilier Commercial',
                'description' => 'Comptoirs d\'accueil, vitrines, présentoirs et mobilier d\'agencement sur mesure pour magasins.'
            ],
            [
                'name' => 'Étiquetage et Prix',
                'description' => 'Étiquettes de prix, porte-étiquettes, systèmes d\'étiquetage électronique et supports d\'information produit.'
            ],
            [
                'name' => 'Emballages et Conditionnement',
                'description' => 'Emballages carton, sachets plastique, matériaux de protection et solutions de conditionnement.'
            ],
            [
                'name' => 'Équipements de Caisse',
                'description' => 'Terminaux de paiement, balances, imprimantes tickets, tiroirs-caisses et accessoires de caisse.'
            ],
            [
                'name' => 'Matériel de Manutention',
                'description' => 'Transpalettes, diables, chariots de transport, sangles et équipements de manutention pour entrepôts.'
            ],
            [
                'name' => 'Sécurité et Surveillance',
                'description' => 'Antivols, caméras de surveillance, miroirs de sécurité et systèmes de protection contre le vol.'
            ],
            [
                'name' => 'Nettoyage et Entretien',
                'description' => 'Produits d\'entretien, matériel de nettoyage, poubelles et équipements d\'hygiène pour points de vente.'
            ]
        ];

        foreach ($categories as $categoryData) {
            Category::create([
                'name' => $categoryData['name'],
                'slug' => Str::slug($categoryData['name']),
                'description' => $categoryData['description'],
                'is_active' => true,
            ]);
        }
    }
}
