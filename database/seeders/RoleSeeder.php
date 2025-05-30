<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            [
                'name' => 'Admin',
                'slug' => 'admin'
            ],
            [
                'name' => 'Client',
                'slug' => 'client'
            ],
            [
                'name' => 'Chauffeur',
                'slug' => 'chauffeur'
            ],
            [
                'name' => 'Transporteur',
                'slug' => 'transporteur'
            ]
        ];

        foreach ($roles as $role) {
            Role::updateOrCreate(
                ['name' => $role['name']],
                $role
            );
        }
    }
} 