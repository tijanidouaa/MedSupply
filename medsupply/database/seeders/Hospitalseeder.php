<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Hospital;
use App\Models\User;

class HospitalSeeder extends Seeder
{
    public function run(): void
    {
        $chiefs = User::where('role', 'hospital_chief')->get();

        $hospitals = [
            [
                'name'          => 'CHU Ibn Rochd',
                'address'       => 'Rue des Hôpitaux, Quartier des Hôpitaux',
                'city'          => 'Casablanca',
                'phone'         => '0522225252',
                'contact_email' => 'direction@chu-ibnrochd.ma',
                'is_active'     => true,
            ],
            [
                'name'          => 'Hôpital Cheikh Khalifa',
                'address'       => 'Boulevard Allal El Fassi, Agdal',
                'city'          => 'Rabat',
                'phone'         => '0537714141',
                'contact_email' => 'contact@hopital-khalifa.ma',
                'is_active'     => true,
            ],
            [
                'name'          => 'Hôpital Avicenne',
                'address'       => 'Avenue Ibn Sina, Agdal',
                'city'          => 'Rabat',
                'phone'         => '0537677272',
                'contact_email' => 'direction@avicenne.ma',
                'is_active'     => true,
            ],
            [
                'name'          => 'CHU Hassan II',
                'address'       => 'Route de Sidi Harazem',
                'city'          => 'Fès',
                'phone'         => '0535612222',
                'contact_email' => 'chu@hassan2-fes.ma',
                'is_active'     => true,
            ],
            [
                'name'          => 'Hôpital Mohammed V',
                'address'       => 'Avenue Mohammed V, Centre-ville',
                'city'          => 'Marrakech',
                'phone'         => '0524438080',
                'contact_email' => 'contact@hopital-mv-marrakech.ma',
                'is_active'     => true,
            ],
            [
                'name'          => 'Hôpital Al Farabi',
                'address'       => 'Quartier Industriel, Rue 4',
                'city'          => 'Oujda',
                'phone'         => '0536682222',
                'contact_email' => 'alfarabi@sante-oujda.ma',
                'is_active'     => false,
            ],
        ];

        foreach ($hospitals as $i => $data) {
            $data['user_id'] = $chiefs->get($i % $chiefs->count())?->id;
            Hospital::create($data);
        }
    }
}