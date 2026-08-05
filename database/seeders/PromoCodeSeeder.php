<?php

namespace Database\Seeders;

use App\Model\PromoCode;
use Illuminate\Database\Seeder;

class PromoCodeSeeder extends Seeder
{
    public function run(): void
    {
        $promos = [
            [
                'id'           => 1,
                'event_id'     => null,
                'code'         => 'MIDNIGHT20',
                'label'        => '20% Off Weekend Special',
                'description'  => 'Get 20% discount on bookings over ₹20,000 across all tables.',
                'visibility'   => 'public',
                'type'         => 'percentage',
                'value'        => 20.00,
                'start_date'   => '2026-06-01',
                'end_date'     => '2026-12-31',
                'min_spend'    => 20000.00,
                'max_discount' => 8000.00,
                'usage_limit'  => 500,
                'used_count'   => 42,
                'is_active'    => true,
            ],
            [
                'id'           => 2,
                'event_id'     => null,
                'code'         => 'FLAT5000',
                'label'        => 'Flat ₹5,000 Off on VIP Table',
                'description'  => 'Instant ₹5,000 cash discount on VIP & Normal table reservations.',
                'visibility'   => 'public',
                'type'         => 'fixed',
                'value'        => 5000.00,
                'start_date'   => '2026-07-01',
                'end_date'     => '2026-10-31',
                'min_spend'    => 40000.00,
                'max_discount' => 5000.00,
                'usage_limit'  => 200,
                'used_count'   => 19,
                'is_active'    => true,
            ],
            [
                'id'           => 3,
                'event_id'     => null,
                'code'         => 'VIPNIGHT',
                'label'        => '₹10,000 VIP Executive Discount',
                'description'  => 'Exclusive promo for high-roller VIP table packages above ₹60,000.',
                'visibility'   => 'private',
                'type'         => 'fixed',
                'value'        => 10000.00,
                'start_date'   => '2026-05-01',
                'end_date'     => '2026-12-31',
                'min_spend'    => 60000.00,
                'max_discount' => 10000.00,
                'usage_limit'  => 100,
                'used_count'   => 15,
                'is_active'    => true,
            ],
            [
                'id'           => 4,
                'event_id'     => null,
                'code'         => 'WEEKEND15',
                'label'        => '15% Off Friday & Saturday',
                'description'  => 'Weekend vibes with 15% off on all reservations.',
                'visibility'   => 'public',
                'type'         => 'percentage',
                'value'        => 15.00,
                'start_date'   => '2026-07-15',
                'end_date'     => '2026-09-30',
                'min_spend'    => 20000.00,
                'max_discount' => 5000.00,
                'usage_limit'  => 300,
                'used_count'   => 28,
                'is_active'    => true,
            ],
            [
                'id'           => 5,
                'event_id'     => 1,
                'code'         => 'BOLLYWOOD50',
                'label'        => 'Bollywood Gala Pass ₹3,000 Off',
                'description'  => 'Valid exclusively for Bollywood Night with DJ Shadow.',
                'visibility'   => 'public',
                'type'         => 'fixed',
                'value'        => 3000.00,
                'start_date'   => '2026-07-20',
                'end_date'     => '2026-08-05',
                'min_spend'    => 20000.00,
                'max_discount' => 3000.00,
                'usage_limit'  => 150,
                'used_count'   => 35,
                'is_active'    => true,
            ],
            [
                'id'           => 6,
                'event_id'     => 2,
                'code'         => 'EDM25',
                'label'        => '25% Off Neon EDM Festival',
                'description'  => 'Special early bird voucher for the Neon EDM Carnival.',
                'visibility'   => 'public',
                'type'         => 'percentage',
                'value'        => 25.00,
                'start_date'   => '2026-07-22',
                'end_date'     => '2026-08-05',
                'min_spend'    => 20000.00,
                'max_discount' => 10000.00,
                'usage_limit'  => 100,
                'used_count'   => 22,
                'is_active'    => true,
            ],
        ];

        foreach ($promos as $promo) {
            PromoCode::updateOrCreate(['id' => $promo['id']], $promo);
        }

        $this->command->info('✅ Promo Codes seeded successfully.');
    }
}
