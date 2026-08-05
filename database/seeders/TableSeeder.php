<?php

namespace Database\Seeders;

use App\Model\ClubTable;
use Illuminate\Database\Seeder;

class TableSeeder extends Seeder
{
    public function run(): void
    {
        $tables = [
            [
                'id'           => 1,
                'club_id'      => 1,
                'total_tables' => 3,
                'cover_charge' => 0.00,
                'name'         => 'VIP table',
                'label'        => '40k+ above spend',
                'price'        => 40000.00,
                'capacity'     => 6,
                'status'       => 'active',
                'image'        => 'tables/a3da41A5j9VK0VNJu0kKjH5uzxAcXmjMDI3jHMcj.jpg',
                'created_at'   => '2026-07-20 19:32:06',
                'updated_at'   => '2026-07-31 07:03:20',
            ],
            [
                'id'           => 4,
                'club_id'      => 1,
                'total_tables' => 6,
                'cover_charge' => 0.00,
                'name'         => 'Standing Table',
                'label'        => '20k+ above spend',
                'price'        => 20000.00,
                'capacity'     => 5,
                'status'       => 'active',
                'image'        => 'tables/8OLjKhS1PJoxw6uibLNN8TnAZYKEgvLoY4vpWNpX.webp',
                'created_at'   => '2026-07-21 23:29:21',
                'updated_at'   => '2026-07-31 07:03:20',
            ],
            [
                'id'           => 5,
                'club_id'      => 1,
                'total_tables' => 4,
                'cover_charge' => 0.00,
                'name'         => 'Normal Table',
                'label'        => '60k+ above spend',
                'price'        => 60000.00,
                'capacity'     => 5,
                'status'       => 'active',
                'image'        => 'tables/v1riUt2uWUzKs1yJ0Z74ECrnxaelc2vzqNwbhUeC.png',
                'created_at'   => '2026-07-21 23:51:05',
                'updated_at'   => '2026-07-31 07:03:20',
            ],
        ];

        foreach ($tables as $data) {
            ClubTable::updateOrCreate(
                ['id' => $data['id']],
                $data
            );
        }

        $this->command->info('✅ Specific Tables seeded successfully (IDs: 1, 4, 5) for club_id=1.');
    }
}
