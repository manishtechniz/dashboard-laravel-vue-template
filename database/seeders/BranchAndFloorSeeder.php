<?php

namespace Database\Seeders;

use App\Model\Branch;
use App\Model\Floor;
use Illuminate\Database\Seeder;

class BranchAndFloorSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Main Branch for club_id = 1
        $branch = Branch::updateOrCreate(
            ['id' => 1],
            [
                'club_id'     => 1,
                'name'        => 'Midnight Club - Main Arena',
                'description' => 'Flagship nightlife destination featuring acoustic engineering and luxury lounges.',
                'address'     => 'SCO 34-36, Sector 29, Gurugram, Haryana 122002',
                'phone'       => '+91 9711891515',
                'is_active'   => true,
            ]
        );

        // 2. Floors for the Branch
        $floors = [
            [
                'id'        => 1,
                'branch_id' => $branch->id,
                'name'      => 'Ground Floor - Main Dance Arena & Island Bar',
                'level'     => 0,
                'is_active' => true,
            ],
            [
                'id'        => 2,
                'branch_id' => $branch->id,
                'name'      => '1st Floor - VIP Mezzanine & DJ Lounge',
                'level'     => 1,
                'is_active' => true,
            ],
            [
                'id'        => 3,
                'branch_id' => $branch->id,
                'name'      => 'Rooftop Sky Bar & Open Terrace',
                'level'     => 2,
                'is_active' => true,
            ],
        ];

        foreach ($floors as $floor) {
            Floor::updateOrCreate(['id' => $floor['id']], $floor);
        }

        $this->command->info('✅ Branch & Floors seeded successfully for club_id=1.');
    }
}
