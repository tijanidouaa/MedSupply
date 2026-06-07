<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\Supplier;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $suppliers = Supplier::all();
        $s1 = $suppliers->get(0)->id;
        $s2 = $suppliers->get(1)->id;
        $s3 = $suppliers->get(2)->id;
        $s4 = $suppliers->get(3)->id;

        $products = [
            // Consommables
            ['name' => 'Gants chirurgicaux stériles (M)', 'category' => 'consommables', 'price' => 45.00,  'stock' => 500,  'min_stock' => 100, 'supplier_id' => $s1],
            ['name' => 'Gants chirurgicaux stériles (L)', 'category' => 'consommables', 'price' => 45.00,  'stock' => 350,  'min_stock' => 100, 'supplier_id' => $s1],
            ['name' => 'Masques chirurgicaux (boîte 50)', 'category' => 'consommables', 'price' => 35.00,  'stock' => 200,  'min_stock' => 50,  'supplier_id' => $s1],
            ['name' => 'Seringues 5ml (boîte 100)',       'category' => 'consommables', 'price' => 55.00,  'stock' => 8,    'min_stock' => 50,  'supplier_id' => $s1],
            ['name' => 'Seringues 10ml (boîte 100)',      'category' => 'consommables', 'price' => 65.00,  'stock' => 120,  'min_stock' => 50,  'supplier_id' => $s1],
            ['name' => 'Compresses stériles (boîte 100)', 'category' => 'consommables', 'price' => 28.00,  'stock' => 300,  'min_stock' => 80,  'supplier_id' => $s1],
            ['name' => 'Sparadrap médical 5m',            'category' => 'consommables', 'price' => 12.00,  'stock' => 0,    'min_stock' => 30,  'supplier_id' => $s1],
            ['name' => 'Cathéters IV 18G (boîte 50)',     'category' => 'consommables', 'price' => 180.00, 'stock' => 60,   'min_stock' => 20,  'supplier_id' => $s2],
            ['name' => 'Tubulures perfusion (boîte 20)',  'category' => 'consommables', 'price' => 95.00,  'stock' => 40,   'min_stock' => 20,  'supplier_id' => $s2],

            // Médicaments
            ['name' => 'Paracétamol 1g IV (boîte 12)',   'category' => 'medicaments',  'price' => 85.00,  'stock' => 150,  'min_stock' => 40,  'supplier_id' => $s3],
            ['name' => 'Amoxicilline 500mg (boîte 24)',  'category' => 'medicaments',  'price' => 45.00,  'stock' => 200,  'min_stock' => 50,  'supplier_id' => $s3],
            ['name' => 'Ibuprofène 400mg (boîte 30)',    'category' => 'medicaments',  'price' => 32.00,  'stock' => 180,  'min_stock' => 40,  'supplier_id' => $s3],
            ['name' => 'Sérum physiologique 500ml',      'category' => 'medicaments',  'price' => 18.00,  'stock' => 5,    'min_stock' => 30,  'supplier_id' => $s3],
            ['name' => 'Chlorhexidine 250ml',            'category' => 'medicaments',  'price' => 25.00,  'stock' => 90,   'min_stock' => 20,  'supplier_id' => $s3],
            ['name' => 'Morphine 10mg/ml (ampoule)',     'category' => 'medicaments',  'price' => 120.00, 'stock' => 30,   'min_stock' => 10,  'supplier_id' => $s3],

            // Équipements
            ['name' => 'Tensiomètre électronique',       'category' => 'equipements',  'price' => 850.00, 'stock' => 15,   'min_stock' => 3,   'supplier_id' => $s2],
            ['name' => 'Oxymètre de pouls',              'category' => 'equipements',  'price' => 320.00, 'stock' => 20,   'min_stock' => 5,   'supplier_id' => $s2],
            ['name' => 'Thermomètre infrarouge',         'category' => 'equipements',  'price' => 180.00, 'stock' => 25,   'min_stock' => 5,   'supplier_id' => $s2],
            ['name' => 'Stéthoscope professionnel',      'category' => 'equipements',  'price' => 650.00, 'stock' => 10,   'min_stock' => 2,   'supplier_id' => $s4],
            ['name' => 'Défibrillateur AED',             'category' => 'equipements',  'price' => 8500.00,'stock' => 3,    'min_stock' => 1,   'supplier_id' => $s4],
            ['name' => 'Lit médical électrique',         'category' => 'equipements',  'price' => 4200.00,'stock' => 5,    'min_stock' => 1,   'supplier_id' => $s4],

            // Autres
            ['name' => 'Savon antiseptique 500ml',       'category' => 'autres',       'price' => 22.00,  'stock' => 400,  'min_stock' => 60,  'supplier_id' => $s1],
            ['name' => 'Poubelle DASRI 30L',             'category' => 'autres',       'price' => 85.00,  'stock' => 50,   'min_stock' => 10,  'supplier_id' => $s2],
            ['name' => 'Tablier de protection',          'category' => 'autres',       'price' => 35.00,  'stock' => 80,   'min_stock' => 20,  'supplier_id' => $s1],
        ];

        foreach ($products as $product) {
            Product::create($product);
        }
    }
}