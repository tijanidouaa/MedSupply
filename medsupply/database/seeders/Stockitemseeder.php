<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\StockItem;
use App\Models\Hospital;

class StockItemSeeder extends Seeder
{
    public function run(): void
    {
        $hospitals = Hospital::all();

        $items = [
            ['name' => 'Gants chirurgicaux stériles (M)', 'category' => 'consommables', 'price' => 45.00,  'min_quantity' => 100],
            ['name' => 'Masques chirurgicaux',            'category' => 'consommables', 'price' => 35.00,  'min_quantity' => 50],
            ['name' => 'Seringues 5ml',                   'category' => 'consommables', 'price' => 55.00,  'min_quantity' => 50],
            ['name' => 'Seringues 10ml',                  'category' => 'consommables', 'price' => 65.00,  'min_quantity' => 50],
            ['name' => 'Compresses stériles',             'category' => 'consommables', 'price' => 28.00,  'min_quantity' => 80],
            ['name' => 'Sparadrap médical',               'category' => 'consommables', 'price' => 12.00,  'min_quantity' => 30],
            ['name' => 'Paracétamol 1g IV',               'category' => 'medicaments',  'price' => 85.00,  'min_quantity' => 40],
            ['name' => 'Amoxicilline 500mg',              'category' => 'medicaments',  'price' => 45.00,  'min_quantity' => 50],
            ['name' => 'Sérum physiologique 500ml',       'category' => 'medicaments',  'price' => 18.00,  'min_quantity' => 30],
            ['name' => 'Chlorhexidine 250ml',             'category' => 'medicaments',  'price' => 25.00,  'min_quantity' => 20],
            ['name' => 'Tensiomètre électronique',        'category' => 'equipements',  'price' => 850.00, 'min_quantity' => 3],
            ['name' => 'Oxymètre de pouls',               'category' => 'equipements',  'price' => 320.00, 'min_quantity' => 5],
            ['name' => 'Savon antiseptique 500ml',        'category' => 'autres',       'price' => 22.00,  'min_quantity' => 60],
            ['name' => 'Poubelle DASRI 30L',              'category' => 'autres',       'price' => 85.00,  'min_quantity' => 10],
        ];

        $quantities = [
            [500, 200, 8,  120, 300, 0,  150, 200, 5,   90,  15, 20, 400, 50],
            [200, 150, 60, 80,  180, 20, 80,  120, 40,  60,  8,  12, 200, 30],
            [350, 100, 0,  50,  90,  15, 120, 90,  0,   45,  10, 18, 150, 20],
            [120, 80,  30, 200, 60,  5,  60,  150, 25,  30,  5,  8,  100, 15],
            [280, 120, 45, 90,  150, 10, 90,  80,  15,  20,  12, 15, 300, 25],
            [80,  60,  12, 30,  40,  0,  40,  60,  8,   15,  3,  5,  80,  10],
        ];

        foreach ($hospitals as $hi => $hospital) {
            foreach ($items as $ii => $item) {
                StockItem::create([
                    'name'         => $item['name'],
                    'category'     => $item['category'],
                    'quantity'     => $quantities[$hi % 6][$ii] ?? rand(0, 200),
                    'min_quantity' => $item['min_quantity'],
                    'price'        => $item['price'],
                    'hospital_id'  => $hospital->id,
                ]);
            }
        }
    }
}