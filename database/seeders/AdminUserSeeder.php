<?php

namespace Database\Seeders;

use App\Model\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        $users = [
            [
                'name'         => 'Super Admin',
                'email'        => 'admin@admin.com',
                'phone'        => '9876543210',
                'password'     => Hash::make('password'),
                'role_id'      => 1,
                'is_active'    => true,
                'user_type'    => 'admin',
            ],
        ];

        foreach ($users as $data) {
            User::updateOrCreate(
                ['email' => $data['email']],
                $data
            );
        }

        $this->command->info('✅ Admin users seeded successfully.');
        $this->command->line('🔑 Default Login: admin@admin.com / password');
    }
}
