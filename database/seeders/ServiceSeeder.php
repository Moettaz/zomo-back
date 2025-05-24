<?php

namespace Database\Seeders;

use App\Models\Service;
use Illuminate\Database\Seeder;

class ServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Service::create([
            'nom' => 'Trajet',
            'description' => 'Service de transport de personnes d\'un point à un autre',
            'prix' => 5
        ]);

        Service::create([
            'nom' => 'Demenagement',
            'description' => 'Service complet de déménagement incluant emballage, transport et déballage',
            'prix' => 15
        ]);

        Service::create([
            'nom' => 'Colis',
            'description' => 'Service de livraison de colis et paquets de toutes tailles',
            'prix' => 15
        ]);
    }
}
