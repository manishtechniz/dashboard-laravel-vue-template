<?php

namespace Database\Seeders;

use App\Model\Club;
use Illuminate\Database\Seeder;

class ClubSeeder extends Seeder
{
    public function run(): void
    {
        Club::updateOrCreate(
            ['id' => 1],
            [
                'name'             => 'Mid Night Club',
                'description'      => 'Best & Most Happening Night Club of Gurugram, Opens Till 7 AM. Premium lounge, world-class sound, signature cocktails & VIP experiences. 📞 For Reservations: +91 9711891515',
                'address'          => 'SCO 34-36, Sector 29',
                'city'             => 'Gurugram',
                'logo'             => 'clubs/midnight_logo.png',
                'image'            => 'clubs/midnight_banner.jpg',
                'featured_image'   => 'clubs/midnight_featured.jpg',
                'average_rating'   => 4.8,
                'review_count'     => 0,
                'rating_5_percent' => 80.00,
                'rating_4_percent' => 15.00,
                'rating_3_percent' => 5.00,
                'rating_2_percent' => 0.00,
                'rating_1_percent' => 0.00,
                'is_active'        => true,
            ]
        );

        $this->command->info('✅ Single Club (Mid Night Club, club_id=1) seeded successfully.');
    }
}
