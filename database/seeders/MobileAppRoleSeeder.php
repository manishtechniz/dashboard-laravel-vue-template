<?php

namespace Database\Seeders;

use App\Model\MobileAppRole;
use Illuminate\Database\Seeder;

class MobileAppRoleSeeder extends Seeder
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
            [
                'id' => 2,
                'name' => 'Staff',
                'type' => 'custom',
                'permissions' => ['can_qr_scan', 'can_booking_check_in'],
            ],
        ];

        foreach ($roles as $role) {
            MobileAppRole::updateOrCreate(
                ['id' => $role['id']],
                [
                    'name' => $role['name'],
                    'type' => $role['type'],
                    'permissions' => $role['permissions'],
                ]
            );
        }

        $this->command->info('✅ Default mobile roles seeded successfully.');
    }
}
