<?php

namespace Database\Seeders;

use App\Model\Booking;
use App\Model\Client;
use App\Model\Club;
use App\Model\Review;
use Illuminate\Database\Seeder;

class ReviewSeeder extends Seeder
{
    public function run(): void
    {
        $club = Club::find(1);
        if (!$club) {
            $this->command->error('❌ Club ID 1 not found.');
            return;
        }

        $clients = Client::all();
        $bookings = Booking::where('club_id', 1)->where('status', 'checked_in')->get();

        $reviewsData = [
            [
                'rating'   => 5,
                'comment'  => 'Best night club in Gurugram! The sound system is incredible and the VIP table service was flawless. DJ Shadow set the floor on fire!',
                'remark'   => 'Verified VIP Guest',
            ],
            [
                'rating'   => 5,
                'comment'  => 'Unmatched luxury vibes, open till 7 AM! Cocktails were masterfully crafted and staff was extremely courteous.',
                'remark'   => 'Verified Reservation',
            ],
            [
                'rating'   => 5,
                'comment'  => 'The Neon EDM night was an out-of-world experience. Visuals, bass, and bottle presentation with sparklers were top notch.',
                'remark'   => 'Event Attendee',
            ],
            [
                'rating'   => 4,
                'comment'  => 'Great music and energetic crowd. Valet parking took around 10 minutes due to weekend rush, but club experience inside was 10/10.',
                'remark'   => 'Verified Reservation',
            ],
            [
                'rating'   => 5,
                'comment'  => 'Standing table was right near the DJ booth, totally worth the spend! Will definitely book again next weekend.',
                'remark'   => 'Verified Reservation',
            ],
            [
                'rating'   => 5,
                'comment'  => 'Celebrated my birthday here at the Normal Table. Seamless QR entry and great hospitality by the managers.',
                'remark'   => 'Birthday Group',
            ],
            [
                'rating'   => 4,
                'comment'  => 'Super ambience and lighting! High energy place for party animals. Food quality in lounge was surprisingly good.',
                'remark'   => 'Verified Guest',
            ],
            [
                'rating'   => 5,
                'comment'  => 'Elite crowd and top-tier security. Felt very safe and premium throughout the night.',
                'remark'   => 'VIP Member',
            ],
            [
                'rating'   => 5,
                'comment'  => 'The acoustic clarity in the main arena is unmatched in NCR. Mid Night Club never disappoints!',
                'remark'   => 'Regular Guest',
            ],
            [
                'rating'   => 3,
                'comment'  => 'Music and ambience were great, but bar counter was super crowded around 1:30 AM. VIP table service is highly recommended.',
                'remark'   => 'Verified Guest',
            ],
            [
                'rating'   => 5,
                'comment'  => 'Fantastic Bollywood remixes and awesome DJ track selection. Had the time of our lives!',
                'remark'   => 'Event Attendee',
            ],
            [
                'rating'   => 5,
                'comment'  => 'Seamless reservation via the app. Instant QR code scan at the gate and we were escorted straight to our VIP table.',
                'remark'   => 'App Booking',
            ],
            [
                'rating'   => 4,
                'comment'  => 'Great cocktail menu and lovely rooftop terrace for quick fresh air breaks.',
                'remark'   => 'Verified Guest',
            ],
            [
                'rating'   => 5,
                'comment'  => 'The premier nightlife spot in Gurugram hands down. If you love deep techno and bass music, this is the place.',
                'remark'   => 'Music Enthusiast',
            ],
            [
                'rating'   => 4,
                'comment'  => 'Good crowd and prompt table service. Bottle prices are fair given the premium luxury vibe.',
                'remark'   => 'Verified Guest',
            ],
        ];

        $testerClient = $clients->firstWhere('id', 1) ?? $clients->first();

        foreach ($reviewsData as $index => $data) {
            // Majority of reviews from tester client (ID 1), others distributed
            $client = ($index % 3 === 0) 
                ? ($clients->get($index % $clients->count()) ?? $testerClient)
                : $testerClient;

            // Pick a matching booking for this client if possible
            $booking = $bookings->firstWhere('client_id', $client->id) 
                ?? $bookings->get($index % max(1, $bookings->count()));

            Review::updateOrCreate(
                [
                    'client_id' => $client->id,
                    'club_id'   => 1,
                    'comment'   => $data['comment'],
                ],
                [
                    'booking_id'    => $booking?->id,
                    'rating'        => $data['rating'],
                    'is_active'     => true,
                    'is_anonymous'  => false,
                    'remark'        => $data['remark'],
                    'created_at'    => now()->subDays(rand(1, 30)),
                    'updated_at'    => now()->subDays(rand(1, 30)),
                ]
            );
        }

        // Recalculate Club Rating automatically
        $club->recalculateRating();

        $this->command->info('✅ Reviews seeded and Club ratings recalculated successfully (Avg Rating: ' . $club->fresh()->average_rating . ' / 5.0).');
    }
}
