<?php

namespace Database\Seeders;

use App\Model\MobileAppRole;
use App\Model\Role;
use Illuminate\Database\Seeder;

class MobileAppRoleSeeder extends Seeder
{
    public function run(): void
    {
        MobileAppRole::create([
            'name' => 'administrator',
            'type' => 'system',
            'permissions' => json_encode(['*']),
        ]);

        MobileAppRole::create([
            'name' => 'Staff',
            'type' => 'custom',
            'permissions' => json_encode(['qr_scan']),
        ]);

        $this->command->info('✅ Default mobile roles seeded successfully.');
    }
}
