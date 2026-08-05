<?php

namespace Database\Seeders;

use App\Model\Booking;
use App\Model\Client;
use App\Model\Complaint;
use App\Model\FeatureRequest;
use Illuminate\Database\Seeder;

class ComplaintAndFeatureSeeder extends Seeder
{
    public function run(): void
    {
        $clients = Client::all();
        $bookings = Booking::where('club_id', 1)->get();

        if ($clients->isEmpty()) {
            return;
        }

        // 1. Complaints & Feedback Tickets
        $complaints = [
            [
                'client_id' => 1,
                'message'   => 'Valet parking took around 15 minutes during the rush hour on Saturday night.',
                'remark'    => 'Noted. Added 2 additional valet drivers for peak weekend entry hours.',
                'is_active' => true,
            ],
            [
                'client_id' => 1,
                'message'   => 'Air conditioning near Standing Table #4 was slightly cold during the midnight set.',
                'remark'    => 'HVAC technician calibrated duct airflow and adjusted zone temperature to 23°C.',
                'is_active' => false,
            ],
            [
                'client_id' => 2,
                'message'   => 'Requested bottle mixer replacement took longer than usual at table 5.',
                'remark'    => 'Addressed with head steward. Expedited bar runner assigned to normal tables.',
                'is_active' => false,
            ],
            [
                'client_id' => 3,
                'message'   => 'Booking confirmation SMS was slightly delayed by network operator.',
                'remark'    => 'Switched default transactional SMS gateway provider to redundant route.',
                'is_active' => false,
            ],
        ];

        foreach ($complaints as $c) {
            $client = $clients->firstWhere('id', $c['client_id']) ?? $clients->first();
            $booking = $bookings->firstWhere('client_id', $client->id) ?? $bookings->first();

            Complaint::updateOrCreate(
                [
                    'client_id' => $client->id,
                    'message'   => $c['message'],
                ],
                [
                    'club_id'    => 1,
                    'booking_id' => $booking?->id,
                    'remark'     => $c['remark'],
                    'is_active'  => $c['is_active'],
                    'created_at' => now()->subDays(rand(2, 20)),
                    'updated_at' => now()->subDays(rand(1, 10)),
                ]
            );
        }

        // 2. Feature Requests
        $features = [
            [
                'client_id'   => 1,
                'title'       => 'Apple Wallet & Google Pay Pass Integration',
                'description' => 'Allow clients to add their booking QR passes directly to Apple Wallet / Google Wallet with live lockscreen notifications on event day.',
                'status'      => 'in_progress',
                'priority'    => 'high',
            ],
            [
                'client_id'   => 1,
                'title'       => 'In-App Table Pre-Ordering & Drink Menu',
                'description' => 'Enable VIP table guests to pre-select their premium liquor brands, champagnes, and platter choices before arrival.',
                'status'      => 'planned',
                'priority'    => 'high',
            ],
            [
                'client_id'   => 1,
                'title'       => 'Live DJ Song Request Feature',
                'description' => 'A dedicated section in the app where verified table guests can send track suggestions to the resident DJ.',
                'status'      => 'reviewing',
                'priority'    => 'medium',
            ],
            [
                'client_id'   => 2,
                'title'       => 'Split Table Bill With Invited Guests',
                'description' => 'Feature to split the table minimum spend or cover charge directly amongst booking guests via UPI links.',
                'status'      => 'planned',
                'priority'    => 'medium',
            ],
            [
                'client_id'   => 3,
                'title'       => 'VIP Valet Fast-Track QR',
                'description' => 'Scan QR code when ready to leave to have vehicle waiting at portico automatically.',
                'status'      => 'completed',
                'priority'    => 'high',
            ],
        ];

        foreach ($features as $f) {
            $client = $clients->firstWhere('id', $f['client_id']) ?? $clients->first();

            FeatureRequest::updateOrCreate(
                [
                    'client_id' => $client->id,
                    'title'     => $f['title'],
                ],
                [
                    'description' => $f['description'],
                    'status'      => $f['status'],
                    'priority'    => $f['priority'],
                    'created_at'  => now()->subDays(rand(3, 30)),
                    'updated_at'  => now()->subDays(rand(1, 5)),
                ]
            );
        }

        $this->command->info('✅ Complaints and Feature Requests seeded successfully.');
    }
}
