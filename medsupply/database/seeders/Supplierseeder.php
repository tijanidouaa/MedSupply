<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Supplier;
use App\Models\User;

class SupplierSeeder extends Seeder
{
    public function run(): void
    {
        $supplierUsers = User::where('role', 'supplier')->get();

        $suppliers = [
            [
                'name'     => 'MedCo Maroc',
                'email'    => 'contact@medco.ma',
                'phone'    => '0522334455',
                'verified' => true,
                'rating'   => 4.7,
            ],
            [
                'name'     => 'SanteEquip SA',
                'email'    => 'contact@santeequip.ma',
                'phone'    => '0537889900',
                'verified' => true,
                'rating'   => 4.2,
            ],
            [
                'name'     => 'PharmaTech Maroc',
                'email'    => 'contact@pharmatech.ma',
                'phone'    => '0528112233',
                'verified' => true,
                'rating'   => 4.5,
            ],
            [
                'name'     => 'BioMedical Solutions',
                'email'    => 'contact@biomedical.ma',
                'phone'    => '0535667788',
                'verified' => false,
                'rating'   => 0,
            ],
        ];

        foreach ($suppliers as $i => $data) {
            $data['user_id'] = $supplierUsers->get($i)?->id;
            Supplier::create($data);
        }
    }
}