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
            [
                'name'         => 'Vikram Oberoi',
                'email'        => 'manager@midnightclub.com',
                'phone'        => '9811223344',
                'password'     => Hash::make('password'),
                'role_id'      => 2,
                'is_active'    => true,
                'user_type'    => 'admin',
            ],
            [
                'name'         => 'Arjun Kapoor',
                'email'        => 'supervisor@midnightclub.com',
                'phone'        => '9822334455',
                'password'     => Hash::make('password'),
                'role_id'      => 3,
                'is_active'    => true,
                'user_type'    => 'admin',
            ],
            [
                'name'         => 'Simran Kaur',
                'email'        => 'reception@midnightclub.com',
                'phone'        => '9833445566',
                'password'     => Hash::make('password'),
                'role_id'      => 4,
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
