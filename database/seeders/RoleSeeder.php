<?php

namespace Database\Seeders;

use App\Model\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        $roles = [
            [
                'id' => 1,
                'name' => 'Administrator',
                'type' => 'system',
                'permissions' => ['*'],
            ],
        ];

        foreach ($roles as $role) {
            Role::updateOrCreate(
                ['id' => $role['id']],
                [
                    'name' => $role['name'],
                    'type' => $role['type'],
                    'permissions' => $role['permissions'],
                ]
            );
        }

        $this->command->info('✅ Default admin roles seeded successfully.');
    }
}
