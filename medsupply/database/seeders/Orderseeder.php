<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Order;
use App\Models\Hospital;
use App\Models\Supplier;

class OrderSeeder extends Seeder
{
    public function run(): void
    {
        $hospitals = Hospital::all();
        $suppliers = Supplier::all();

        $orders = [
            ['reference' => 'ORD-2026-001', 'hospital' => 0, 'supplier' => 0, 'total' => 2350.00, 'status' => 'delivered',  'days' => 30],
            ['reference' => 'ORD-2026-002', 'hospital' => 1, 'supplier' => 1, 'total' => 8500.00, 'status' => 'delivered',  'days' => 28],
            ['reference' => 'ORD-2026-003', 'hospital' => 2, 'supplier' => 2, 'total' => 1200.00, 'status' => 'delivered',  'days' => 25],
            ['reference' => 'ORD-2026-004', 'hospital' => 3, 'supplier' => 0, 'total' => 4700.00, 'status' => 'confirmed',  'days' => 20],
            ['reference' => 'ORD-2026-005', 'hospital' => 0, 'supplier' => 1, 'total' => 3200.00, 'status' => 'confirmed',  'days' => 18],
            ['reference' => 'ORD-2026-006', 'hospital' => 4, 'supplier' => 2, 'total' => 950.00,  'status' => 'validated',  'days' => 15],
            ['reference' => 'ORD-2026-007', 'hospital' => 1, 'supplier' => 3, 'total' => 12500.00,'status' => 'validated',  'days' => 12],
            ['reference' => 'ORD-2026-008', 'hospital' => 2, 'supplier' => 0, 'total' => 680.00,  'status' => 'pending',    'days' => 10],
            ['reference' => 'ORD-2026-009', 'hospital' => 3, 'supplier' => 1, 'total' => 2100.00, 'status' => 'pending',    'days' => 8],
            ['reference' => 'ORD-2026-010', 'hospital' => 0, 'supplier' => 2, 'total' => 5600.00, 'status' => 'pending',    'days' => 7],
            ['reference' => 'ORD-2026-011', 'hospital' => 4, 'supplier' => 0, 'total' => 1800.00, 'status' => 'cancelled',  'days' => 20],
            ['reference' => 'ORD-2026-012', 'hospital' => 5, 'supplier' => 1, 'total' => 3400.00, 'status' => 'pending',    'days' => 5],
            ['reference' => 'ORD-2026-013', 'hospital' => 1, 'supplier' => 2, 'total' => 760.00,  'status' => 'pending',    'days' => 4],
            ['reference' => 'ORD-2026-014', 'hospital' => 2, 'supplier' => 3, 'total' => 9200.00, 'status' => 'validated',  'days' => 3],
            ['reference' => 'ORD-2026-015', 'hospital' => 0, 'supplier' => 0, 'total' => 420.00,  'status' => 'pending',    'days' => 2],
        ];

        foreach ($orders as $o) {
            $hospital = $hospitals->get($o['hospital'] % $hospitals->count());
            $supplier = $suppliers->get($o['supplier'] % $suppliers->count());

            if (!$hospital || !$supplier) continue;

            Order::create([
                'reference'   => $o['reference'],
                'hospital_id' => $hospital->id,
                'supplier_id' => $supplier->id,
                'total'       => $o['total'],
                'status'      => $o['status'],
                'created_at'  => now()->subDays($o['days']),
                'updated_at'  => now()->subDays($o['days']),
            ]);
        }
    }
}