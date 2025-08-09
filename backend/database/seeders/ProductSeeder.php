<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Models\Category;
use App\Models\Supplier;
use App\Models\User;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Récupérer les catégories et fournisseurs existants par nom
        $categories = Category::all()->keyBy('name');
        $suppliers = Supplier::all()->keyBy('name');


        $productsData = [
            [
                'name' => 'Rayonnage métallique lourd 5 niveaux',
                'description' => 'Rayonnage robuste pour stockage intensif en entrepôt ou réserve magasin. Chaque niveau supporte jusqu\'à 500kg. Montage facile sans vis.',
                'reference' => 'RAY-MET-001',
                'price' => 189.99,
                'quantity' => 50,
                'category_name' => 'Rayonnages et Gondoles',
                'supplier_name' => 'Rayonnages Pro France',
                'image_path' => 'products/rayonnage_metal.jpg',
                'image_mime' => 'image/jpeg',
                'image_size' => 150000,
            ],
            [
                'name' => 'Présentoir PLV carton cosmétiques',
                'description' => 'Présentoir de sol en carton recyclé, 4 étagères ajustables, idéal pour les lancements de produits ou les promotions saisonnières en point de vente.',
                'reference' => 'PLV-COS-002',
                'price' => 45.50,
                'quantity' => 200,
                'category_name' => 'PLV et Signalétique',
                'supplier_name' => 'PLV Solutions',
                'image_path' => 'products/plv_cosmetiques.jpg',
                'image_mime' => 'image/jpg',
                'image_size' => 120000,
            ],
            [
                'name' => 'Ramette papier A4 80g/m² (500 feuilles)',
                'description' => 'Papier blanc standard de qualité supérieure pour imprimantes laser et jet d\'encre. Certifié FSC, pour un usage quotidien au bureau.',
                'reference' => 'PAP-A4-003',
                'price' => 5.99,
                'quantity' => 1500,
                'category_name' => 'Fournitures de Bureau',
                'supplier_name' => 'Bureau Direct',
                'image_path' => 'products/ramette_papier_a4.jpg',
                'image_mime' => 'image/jpeg',
                'image_size' => 80000,
            ],
            [
                'name' => 'Cartouche encre noire compatible HP Laserjet',
                'description' => 'Cartouche d\'encre noire haute capacité compatible avec les modèles HP Laserjet Pro 400 M401/M425. Rendement optimisé pour des impressions nettes.',
                'reference' => 'ENC-HP-004',
                'price' => 29.95,
                'quantity' => 300,
                'category_name' => 'Fournitures de Bureau',
                'supplier_name' => 'Bureau Direct',
                'image_path' => 'products/cartouche_encre_hp.jpg',
                'image_mime' => 'image/jpg',
                'image_size' => 95000,
            ],
            [
                'name' => 'Stylo bille noir (lot de 10)',
                'description' => 'Stylos bille à encre gel, pointe moyenne 0.7mm, pour une écriture fluide et sans bavure. Corps ergonomique pour un confort optimal.',
                'reference' => 'STY-BIL-005',
                'price' => 8.75,
                'quantity' => 800,
                'category_name' => 'Fournitures de Bureau',
                'supplier_name' => 'Bureau Direct',
                'image_path' => 'products/stylo_bille_noir.jpg',
                'image_mime' => 'image/jpeg',
                'image_size' => 30000,
            ],
            [
                'name' => 'Rouleau film étirable manuel (500mm x 200m)',
                'description' => 'Film étirable transparent en polyéthylène pour le conditionnement et la protection des palettes. Idéal pour sécuriser les marchandises durant le transport.',
                'reference' => 'FILM-ETI-007',
                'price' => 12.50,
                'quantity' => 250,
                'category_name' => 'Emballages et Conditionnement',
                'supplier_name' => 'Emballages Carton Sud',
                'image_path' => 'products/film_etirable_manuel.jpg',
                'image_mime' => 'image/jpeg',
                'image_size' => 70000,
            ],
            [
                'name' => 'Étiquettes adhésives imprimante (100 feuilles A4)',
                'description' => 'Feuilles d\'étiquettes autocollantes pré-découpées (24 étiquettes/feuille) compatibles avec toutes les imprimantes laser et jet d\'encre. Finition mate.',
                'reference' => 'ETIQ-ADH-008',
                'price' => 19.90,
                'quantity' => 400,
                'category_name' => 'Étiquetage et Prix',
                'supplier_name' => 'Étiquetage & Signalétique',
                'image_path' => 'products/etiquettes_imprimante.jpg',
                'image_mime' => 'image/jpg',
                'image_size' => 60000,
            ],
            [
                'name' => 'Bac rangement plastique couvercle (60L)',
                'description' => 'Bac de rangement robuste en plastique avec couvercle à clipser. Empilable, idéal pour le stockage de petits articles ou de documents d\'archives.',
                'reference' => 'BAC-RAN-009',
                'price' => 15.00,
                'quantity' => 150,
                'category_name' => 'Rayonnages et Gondoles',
                'supplier_name' => 'Rayonnages Pro France',
                'image_path' => 'products/bac_rangement_plastique.jpg',
                'image_mime' => 'image/jpeg',
                'image_size' => 100000,
            ],
            [
                'name' => 'Ruban adhésif emballage marron (50mm x 66m)',
                'description' => 'Ruban adhésif polypropylène haute résistance pour la fermeture sécurisée des cartons d\'expédition. Adhésion forte et durable.',
                'reference' => 'RUB-ADH-010',
                'price' => 2.99,
                'quantity' => 1000,
                'category_name' => 'Emballages et Conditionnement',
                'supplier_name' => 'Emballages Carton Sud',
                'image_path' => 'products/ruban_adhesif_marron.jpg',
                'image_mime' => 'image/jpeg',
                'image_size' => 40000,
            ],
            [
                'name' => 'Pistolet à étiqueter avec rouleaux prix',
                'description' => 'Pistolet manuel ergonomique pour l\'étiquetage rapide des prix. Livré avec 5 rouleaux d\'étiquettes blanches (1000 étiquettes/rouleau).',
                'reference' => 'PIST-ETI-011',
                'price' => 35.00,
                'quantity' => 75,
                'category_name' => 'Étiquetage et Prix',
                'supplier_name' => 'Étiquetage & Signalétique',
                'image_path' => 'products/pistolet_etiqueteur.jpg',
                'image_mime' => 'image/jpeg',
                'image_size' => 85000,
            ],
            [
                'name' => 'Porte-affiches plexiglas A3 vertical',
                'description' => 'Support d\'affichage transparent en plexiglas pour documents au format A3. Base stable, idéal pour les promotions ou informations en magasin.',
                'reference' => 'PT-AFF-012',
                'price' => 11.50,
                'quantity' => 200,
                'category_name' => 'PLV et Signalétique',
                'supplier_name' => 'PLV Solutions',
                'image_path' => 'products/porte_affiches_a3.jpg',
                'image_mime' => 'image/jpg',
                'image_size' => 90000,
            ],
            [
                'name' => 'Agrafeuse de bureau capacité 20 feuilles',
                'description' => 'Agrafeuse compacte et robuste en métal, pour agrafer jusqu\'à 20 feuilles de 80g/m². Utilise des agrafes standard 24/6 ou 26/6.',
                'reference' => 'AGRA-BUR-013',
                'price' => 7.90,
                'quantity' => 300,
                'category_name' => 'Fournitures de Bureau',
                'supplier_name' => 'Bureau Direct',
                'image_path' => 'products/agrafeuse_bureau.jpg',
                'image_mime' => 'image/jpeg',
                'image_size' => 25000,
            ],
            [
                'name' => 'Ampoules LED E27 (pack de 5)',
                'description' => 'Lot de 5 ampoules LED basse consommation, culot E27. Lumière blanche froide (6000K), équivalent 60W, durée de vie 15 000 heures.',
                'reference' => 'AMP-LED-015',
                'price' => 14.99,
                'quantity' => 180,
                'category_name' => 'Mobilier Commercial',
                'supplier_name' => 'Équipements Magasins Pro',
                'image_path' => 'products/ampoules_led_e27.jpg',
                'image_mime' => 'image/jpeg',
                'image_size' => 60000,
            ],
            [
                'name' => 'Gants manutention anti-coupure taille L',
                'description' => 'Gants de protection haute performance, résistants aux coupures (Niveau 5). Idéaux pour la manipulation de matériaux coupants et la logistique.',
                'reference' => 'GAN-MAN-018',
                'price' => 9.50,
                'quantity' => 200,
                'category_name' => 'Matériel de Manutention',
                'supplier_name' => 'Logistique & Conditionnement',
                'image_path' => 'products/gants_manutention.jpg',
                'image_mime' => 'image/jpeg',
                'image_size' => 55000,
            ],
            [
                'name' => 'Kit nettoyage ordinateur (spray + chiffon)',
                'description' => 'Kit complet pour l\'entretien et le nettoyage des écrans, claviers et surfaces d\'ordinateurs. Inclus un spray nettoyant antistatique et un chiffon microfibre.',
                'reference' => 'KIT-NET-020',
                'price' => 11.00,
                'quantity' => 250,
                'category_name' => 'Nettoyage et Entretien',
                'supplier_name' => 'Bureau Direct',
                'image_path' => 'products/kit_nettoyage_pc.jpg',
                'image_mime' => 'image/jpeg',
                'image_size' => 35000,
            ],
        ];

        foreach ($productsData as $productData) {
            $slug = Str::slug($productData['name']);
            $category = $categories->get($productData['category_name']);
            $supplier = $suppliers->get($productData['supplier_name']);

            if ($category && $supplier) {
                DB::table('products')->insert([
                    'name' => $productData['name'],
                    'slug' => $slug,
                    'image_path' => $productData['image_path'],
                    'image_mime' => 'image/jpeg',
                    'image_size' => $productData['image_size'],
                    'description' => $productData['description'],
                    'reference' => $productData['reference'],
                    'price' => $productData['price'],
                    'quantity' => $productData['quantity'],
                    'category_id' => $category->id,
                    'supplier_id' => $supplier->id,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            } else {
                $this->command->warn("Could not find category '{$productData['category_name']}' or supplier '{$productData['supplier_name']}' for product '{$productData['name']}'. Skipping product seeding.");
            }
        }
    }
}
