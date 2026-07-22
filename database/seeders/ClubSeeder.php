<?php

namespace Database\Seeders;

use App\Model\Club;
use App\Model\Role;
use Illuminate\Database\Seeder;

class ClubSeeder extends Seeder
{
    public function run(): void
    {
        // Global default theme (white / clean)
        Club::create([
            'name' => 'Mid Night Club',
            'description' => 'Best & Most Happening Night Club of Gurugram, Opens Till 7 AM. 📞 For Reservations: +91 9711891515',
            'address' => 'GB Road',
            'city' => 'Gurugram',
            'is_active' => true
        ]);

        $this->command->info('✅ Club seeded successfully.');
    }
}
