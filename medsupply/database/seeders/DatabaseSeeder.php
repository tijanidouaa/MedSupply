<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            HospitalSeeder::class,
            SupplierSeeder::class,
            ProductSeeder::class,
            StockItemSeeder::class,
            OrderSeeder::class,
        ]);
    }
}