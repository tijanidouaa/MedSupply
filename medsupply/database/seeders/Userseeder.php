<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name'     => 'Administrateur',
            'email'    => 'tijanidouaa@medsupply.ma',
            'password' => Hash::make('douaa2005'),
            'role'     => 'admin',
        ]);

        User::create([
            'name'     => 'Dr. Khalid Benali',
            'email'    => 'chef.chu@medsupply.ma',
            'password' => Hash::make('password'),
            'role'     => 'hospital_chief',
        ]);
        User::create([
            'name'     => 'Dr. Fatima Zohra Alami',
            'email'    => 'chef.hmimad@medsupply.ma',
            'password' => Hash::make('password'),
            'role'     => 'hospital_chief',
        ]);
        User::create([
            'name'     => 'Dr. Youssef Rachidi',
            'email'    => 'chef.avicenne@medsupply.ma',
            'password' => Hash::make('password'),
            'role'     => 'hospital_chief',
        ]);
        User::create([
            'name'     => 'Dr. Samira Idrissi',
            'email'    => 'chef.hassan2@medsupply.ma',
            'password' => Hash::make('password'),
            'role'     => 'hospital_chief',
        ]);

        // Fournisseurs
        User::create([
            'name'     => 'MedCo Maroc',
            'email'    => 'contact@medco.ma',
            'password' => Hash::make('password'),
            'role'     => 'supplier',
        ]);
        User::create([
            'name'     => 'SanteEquip SA',
            'email'    => 'contact@santeequip.ma',
            'password' => Hash::make('password'),
            'role'     => 'supplier',
        ]);
        User::create([
            'name'     => 'PharmaTech Maroc',
            'email'    => 'contact@pharmatech.ma',
            'password' => Hash::make('password'),
            'role'     => 'supplier',
        ]);
        User::create([
            'name'     => 'BioMedical Solutions',
            'email'    => 'contact@biomedical.ma',
            'password' => Hash::make('password'),
            'role'     => 'supplier',
        ]);
    }
}