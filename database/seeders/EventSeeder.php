<?php

namespace Database\Seeders;

use App\Model\Event;
use Illuminate\Database\Seeder;

class EventSeeder extends Seeder
{
    public function run(): void
    {
        $events = [
            [
                'id'             => 1,
                'club_id'        => 1,
                'name'           => 'Bollywood Night ft. DJ Shadow',
                'description'    => 'Experience the biggest Bollywood dance explosion in Gurugram featuring award-winning DJ Shadow spinning chart-topping remixes.',
                'event_date'     => '2026-08-03',
                'is_active'      => true,
                'image'          => 'events/9lybUDBoLMNFDFmSKXptmCkZJUxXzn8TZ0jaNHiM.webp',
                'featured_image' => 'events/iIFZ7hy5wvW5HhyrjeouvptkZi5us2eHCjrbp5Zk.webp',
            ],
            [
                'id'             => 2,
                'club_id'        => 1,
                'name'           => 'Neon EDM Carnival & Glow Party',
                'description'    => 'Ultraviolet lasers, glow paint artists, immersive bass drops, and headline electronic dance music producers.',
                'event_date'     => '2026-08-03',
                'is_active'      => true,
                'image'          => 'events/S4skwAqMUvMMbcAbpLp7LTLjfNvGnZL1XoRSi5UH.webp',
                'featured_image' => 'events/Pi3A9F3cEbeozvsqItbLtrsRV3GBP0xEL6AdvjbE.webp',
            ],
            [
                'id'             => 3,
                'club_id'        => 1,
                'name'           => 'Midnight Saturday Extravaganza',
                'description'    => 'Our signature weekend residency with celebrity guest bartenders, acrobatic aerialists, and resident DJ sets.',
                'event_date'     => '2026-08-03',
                'is_active'      => true,
                'image'          => 'events/q9jvDTCOoRErzDe0L95PFxfT2NI6scoCK7CXuwny.webp',
                'featured_image' => 'events/saturday_party_feat.jpg',
            ],
            [
                'id'             => 4,
                'club_id'        => 1,
                'name'           => 'Techno Underground: Deep Minimal Sessions',
                'description'    => 'A dedicated deep-tech journey with hypnotic lighting and German underground techno grooves till early sunrise.',
                'event_date'     => '2026-08-03',
                'is_active'      => true,
                'image'          => 'events/RWWCcuEfMAXCS1oA3Vo8oSFZWzopf1TW0qPbDfjy.webp',
                'featured_image' => 'events/techno_underground_feat.jpg',
            ],
            [
                'id'             => 5,
                'club_id'        => 1,
                'name'           => 'Independence Eve Gala 2026',
                'description'    => 'Patriotic laser choreography, luxury VIP table packages, and high-energy commercial hits all night long.',
                'event_date'     => '2026-08-03',
                'is_active'      => true,
                'image'          => 'events/m7ucvAR8rz1bzz5WMrHMg0dlJm0zasgbmcjLI21q.webp',
                'featured_image' => 'events/independence_gala_feat.jpg',
            ],
            [
                'id'             => 6,
                'club_id'        => 1,
                'name'           => 'Ladies & Champagne Night',
                'description'    => 'Complimentary welcome bubbles for ladies, artisanal tapas, and irresistible R&B, Hip-Hop, and Commercial jams.',
                'event_date'     => '2026-08-03',
                'is_active'      => true,
                'image'          => 'events/oWtgJOACgm2HwQkPgrETPGvreKjVXjvqzm1JrmY7.webp',
                'featured_image' => 'events/ladies_night_feat.jpg',
            ],
            [
                'id'             => 7,
                'club_id'        => 1,
                'name'           => 'Retro 90s & 2000s Pop Blast',
                'description'    => 'A nostalgic throwback celebration honoring the golden anthems of pop, disco, and rock classics.',
                'event_date'     => '2026-08-03',
                'is_active'      => true,
                'image'          => 'events/ZuTX3oxxhz4svGOjQSUc9r1ZlQgon9AERemAyKH0.jpg',
                'featured_image' => 'events/retro_night_feat.jpg',
            ],
            [
                'id'             => 8,
                'club_id'        => 1,
                'name'           => 'Sunburn Club Showcase - Live in Gurugram',
                'description'    => 'Official festival club takeover with state-of-the-art CO2 cannons, confetti blasts, and international festival DJs.',
                'event_date'     => '2026-08-03',
                'is_active'      => true,
                'image'          => 'events/PaV4Rb0dY6v43dnQ0L4xe4QviB1nOMT2lO7Tpk4H.webp',
                'featured_image' => 'events/sunburn_showcase_feat.jpg',
            ],
        ];

        foreach ($events as $event) {
            Event::updateOrCreate(['id' => $event['id']], $event);
        }

        $this->command->info('✅ Events seeded successfully for club_id=1.');
    }
}
