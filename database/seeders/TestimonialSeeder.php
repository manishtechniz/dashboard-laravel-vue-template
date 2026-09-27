<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class TestimonialSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = Carbon::now();

        $testimonials = [
            [
                'club_id' => 1,
                'client_id' => 1,
                'rating' => 5,
                'title' => 'Top-tier Facilities on Golf Course Road',
                'comment' => 'The gym facilities and trainers here on Golf Course Road are world-class. Clean locker rooms and great equipment.',
                'is_published' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'club_id' => 1,
                'client_id' => 1, 
                'rating' => 4,
                'title' => 'Great Networking at Cyber Hub',
                'comment' => 'Frequent evening sports events make it very easy to unwind after work in Cyber City. Highly recommended!',
                'is_published' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'club_id' => 1,
                'client_id' => 1, 
                'rating' => 5,
                'title' => 'Best Swimming Pool in Sector 56',
                'comment' => 'Clean, temperature-regulated pool. My family and I spend almost every weekend here.',
                'is_published' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'club_id' => 1,
                'client_id' => 1, 
                'rating' => 5,
                'title' => 'Superb Badminton Courts',
                'comment' => 'The wooden flooring on the indoor badminton courts near Sector 48 is impeccably maintained.',
                'is_published' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'club_id' => 1,
                'client_id' => 1, 
                'rating' => 4,
                'title' => 'Delicious Food & Courteous Staff',
                'comment' => 'Club lounge food quality is impressive. Service is prompt even during peak Sunday brunch hours.',
                'is_published' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'club_id' => 1,
                'client_id' => 1, 
                'rating' => 5,
                'title' => 'Calm Vibe near Golf Course Extension',
                'comment' => 'A peaceful escape from the daily city traffic. The tennis coaches are experienced and patient with beginners.',
                'is_published' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'club_id' => 1,
                'client_id' => 1, 
                'rating' => 4,
                'title' => 'Great Weekend Events for Kids',
                'comment' => 'They host engaging weekend sports workshops in Sector 29. My kids genuinely look forward to them.',
                'is_published' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'club_id' => 1,
                'client_id' => 1, 
                'rating' => 5,
                'title' => 'Excellent Spa & Wellness Services',
                'comment' => 'The post-workout sauna and physiotherapy sessions here are unmatched across Millennium City.',
                'is_published' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'club_id' => 1,
                'client_id' => 1, 
                'rating' => 4,
                'title' => 'Hassle-free Valet & Parking',
                'comment' => 'Parking is usually a nightmare around MG Road, but this club manages parking and entry seamlessly.',
                'is_published' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'club_id' => 1,
                'client_id' => 1,  
                'rating' => 5,
                'title' => 'Premium Ambience in Sector 43',
                'comment' => 'The gym crowd is disciplined, and the cafeteria serves healthy, protein-rich meal options.',
                'is_published' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ];

        DB::table('testimonials')->insert($testimonials);
    }
}
