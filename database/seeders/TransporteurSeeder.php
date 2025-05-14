<?php

namespace Database\Seeders;

use App\Models\Transporteur;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class TransporteurSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $transporteurs = [
            [
                'user_id' => 1,
                'email' => 'john.doe@example.com',
                'username' => 'john_driver',
                'password' => Hash::make('password123'),
                'phone' => '23456789',
                'points' => 100,
                'image_url' => 'transporteurs/john.png',
                'service_id' => 1,
                'disponibilite' => true,
                'note_moyenne' => 4.5,
                'gender' => 'male',
                'vehicule_type' => 'confort'
            ],
            [
                'user_id' => 2,
                'email' => 'sarah.smith@example.com',
                'username' => 'sarah_taxi',
                'password' => Hash::make('password123'),
                'phone' => '23456790',
                'points' => 150,
                'image_url' => 'transporteurs/sarah.png',
                'service_id' => 2,
                'disponibilite' => true,
                'note_moyenne' => 4.8,
                'gender' => 'female',
                'vehicule_type' => 'taxi'
            ],
            [
                'user_id' => 3,
                'email' => 'mike.rider@example.com',
                'username' => 'mike_moto',
                'password' => Hash::make('password123'),
                'phone' => '23456791',
                'points' => 80,
                'image_url' => 'transporteurs/mike.png',
                'service_id' => 3,
                'disponibilite' => true,
                'note_moyenne' => 4.2,
                'gender' => 'male',
                'vehicule_type' => 'moto'
            ],
            [
                'user_id' => 4,
                'email' => 'alex.luxe@example.com',
                'username' => 'alex_luxe',
                'password' => Hash::make('password123'),
                'phone' => '23456792',
                'points' => 200,
                'image_url' => 'transporteurs/alex.png',
                'service_id' => 4,
                'disponibilite' => true,
                'note_moyenne' => 4.9,
                'gender' => 'male',
                'vehicule_type' => 'luxe'
            ]
        ];

        foreach ($transporteurs as $transporteur) {
            Transporteur::create($transporteur);
        }
    }
}
