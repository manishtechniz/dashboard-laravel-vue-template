<?php

namespace Database\Seeders;

use App\Model\Role;
use Illuminate\Database\Seeder;  
class RoleSeeder extends Seeder
{
    public function run(): void
    {
        // Global default theme (white / clean)
        Role::create([
            'name' => 'Administrator',
            'permission_type' => 'all',
        ]);

        $this->command->info('✅ Default roles seeded successfully.');
    }
}
