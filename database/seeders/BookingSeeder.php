<?php

namespace Database\Seeders;

use App\Model\Booking;
use App\Model\BookingGuest;
use App\Model\Client;
use App\Model\Club;
use App\Model\ClubTable;
use App\Model\Event;
use App\Model\Payment;
use App\Model\Transaction;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class BookingSeeder extends Seeder
{
    public function run(): void
    {
        $club = Club::find(1);
        $clients = Client::all();
        $tables = ClubTable::where('club_id', 1)->get()->keyBy('id');
        $events = Event::where('club_id', 1)->get();

        if ($clients->isEmpty() || $tables->isEmpty()) {
            $this->command->error('❌ Please seed Clients, Club and Tables first.');
            return;
        }

        $tableKeys = [1, 4, 5]; // User specified table IDs
        $paymentMethods = ['cash'];
        $gateways = ['cash'];

        $specialRequestsPool = [
            'Near the DJ booth please!',
            'Birthday celebration, arrange sparklers with champagne.',
            'Quiet corner booth if available.',
            'VIP bottle service with mixers ready at arrival.',
            'Anniversary celebration for 4 guests.',
            'Need ice bucket and tonic water setup.',
            'Extra seating space required.',
            null,
            null,
        ];

        // Anchor date: Current reference time
        $baseDate = Carbon::create(2026, 8, 3, 21, 0, 0);

        // Pre-defined seed distribution:
        // 1. Historical past 11 months (Aug 2025 - Jun 2026): ~24 bookings
        // 2. Last month (July 2026): ~16 bookings
        // 3. Current month (August 2026): ~22 bookings (including today & upcoming)
        $bookingConfigs = [];

        // Past 11 months
        for ($m = 11; $m >= 2; $m--) {
            $monthDate = $baseDate->copy()->subMonths($m);
            for ($k = 0; $k < 2; $k++) {
                $day = rand(2, 26);
                $bDate = $monthDate->copy()->setDay($day);
                $bookingConfigs[] = [
                    'date'           => $bDate->toDateString(),
                    'created_at'     => $bDate->copy()->subDays(rand(1, 4))->toDateTimeString(),
                    'status'         => 'checked_in',
                    'payment_status' => 'paid',
                ];
            }
        }

        // Last month (July 2026)
        for ($i = 1; $i <= 16; $i++) {
            $bDate = Carbon::create(2026, 7, rand(1, 30), rand(20, 23), 0, 0);
            $bookingConfigs[] = [
                'date'           => $bDate->toDateString(),
                'created_at'     => $bDate->copy()->subDays(rand(1, 5))->toDateTimeString(),
                'status'         => ($i % 6 == 0) ? 'cancelled' : 'checked_in',
                'payment_status' => ($i % 6 == 0) ? 'refunded' : 'paid',
            ];
        }

        // Current month: Yesterday, Today, This Month (Aug 1 - Aug 3)
        $bookingConfigs[] = [
            'client_id'      => 1,
            'date'           => '2026-08-01',
            'created_at'     => '2026-07-29 14:30:00',
            'status'         => 'checked_in',
            'payment_status' => 'paid',
            'event_id'       => 1,
        ];
        $bookingConfigs[] = [
            'client_id'      => 2,
            'date'           => '2026-08-01',
            'created_at'     => '2026-07-30 18:15:00',
            'status'         => 'checked_in',
            'payment_status' => 'paid',
            'event_id'       => 1,
        ];
        $bookingConfigs[] = [
            'client_id'      => 1,
            'date'           => '2026-08-02',
            'created_at'     => '2026-08-01 11:20:00',
            'status'         => 'checked_in',
            'payment_status' => 'paid',
            'event_id'       => 2,
        ];
        $bookingConfigs[] = [
            'client_id'      => 3,
            'date'           => '2026-08-02',
            'created_at'     => '2026-08-01 19:45:00',
            'status'         => 'checked_in',
            'payment_status' => 'paid',
            'event_id'       => 2,
        ];

        // TODAY: Aug 3, 2026
        $bookingConfigs[] = [
            'client_id'      => 1,
            'date'           => '2026-08-03',
            'created_at'     => '2026-08-03 10:15:00',
            'status'         => 'confirmed',
            'payment_status' => 'paid',
        ];
        $bookingConfigs[] = [
            'client_id'      => 1,
            'date'           => '2026-08-03',
            'created_at'     => '2026-08-03 14:00:00',
            'status'         => 'confirmed',
            'payment_status' => 'paid',
        ];
        $bookingConfigs[] = [
            'client_id'      => 1,
            'date'           => '2026-08-03',
            'created_at'     => '2026-08-03 17:30:00',
            'status'         => 'pending',
            'payment_status' => 'pending',
        ];

        // UPCOMING DATES in August 2026
        $bookingConfigs[] = [
            'client_id'      => 1,
            'date'           => '2026-08-08',
            'created_at'     => '2026-08-03 12:00:00',
            'status'         => 'confirmed',
            'payment_status' => 'paid',
            'event_id'       => 3,
        ];
        $bookingConfigs[] = [
            'client_id'      => 1,
            'date'           => '2026-08-08',
            'created_at'     => '2026-08-03 15:45:00',
            'status'         => 'confirmed',
            'payment_status' => 'paid',
            'event_id'       => 3,
        ];
        $bookingConfigs[] = [
            'client_id'      => 1,
            'date'           => '2026-08-08',
            'created_at'     => '2026-08-03 18:20:00',
            'status'         => 'pending',
            'payment_status' => 'pending',
            'event_id'       => 3,
        ];
        $bookingConfigs[] = [
            'client_id'      => 1,
            'date'           => '2026-08-14',
            'created_at'     => '2026-08-03 11:10:00',
            'status'         => 'confirmed',
            'payment_status' => 'paid',
            'event_id'       => 4,
        ];
        $bookingConfigs[] = [
            'client_id'      => 1,
            'date'           => '2026-08-15',
            'created_at'     => '2026-08-03 09:30:00',
            'status'         => 'confirmed',
            'payment_status' => 'paid',
            'event_id'       => 5,
        ];
        $bookingConfigs[] = [
            'client_id'      => 4,
            'date'           => '2026-08-15',
            'created_at'     => '2026-08-03 16:15:00',
            'status'         => 'confirmed',
            'payment_status' => 'paid',
            'event_id'       => 5,
        ];
        $bookingConfigs[] = [
            'client_id'      => 1,
            'date'           => '2026-08-20',
            'created_at'     => '2026-08-03 14:50:00',
            'status'         => 'confirmed',
            'payment_status' => 'paid',
            'event_id'       => 6,
        ];
        $bookingConfigs[] = [
            'client_id'      => 1,
            'date'           => '2026-08-22',
            'created_at'     => '2026-08-03 13:00:00',
            'status'         => 'confirmed',
            'payment_status' => 'paid',
            'event_id'       => 7,
        ];
        $bookingConfigs[] = [
            'client_id'      => 1,
            'date'           => '2026-08-29',
            'created_at'     => '2026-08-03 15:00:00',
            'status'         => 'confirmed',
            'payment_status' => 'paid',
            'event_id'       => 8,
        ];

        $guestNamePool = [
            'Rohan Varma',
            'Kavita Joshi',
            'Ankush Singhal',
            'Simran Bedi',
            'Kunal Sehgal',
            'Tara Deshpande',
            'Sahil Kapoor',
            'Nikita Roy',
            'Gautam Gambhir',
            'Preeti Nair',
            'Abhishek Ray',
            'Sonali Thakur'
        ];

        $testerClient = $clients->firstWhere('id', 1) ?? $clients->first();
        $bookingIdCounter = 1;

        foreach ($bookingConfigs as $cfg) {
            $client = isset($cfg['client_id'])
                ? ($clients->firstWhere('id', $cfg['client_id']) ?? $testerClient)
                : ((rand(1, 100) <= 80) ? $testerClient : $clients->random());

            $tableId = $tableKeys[array_rand($tableKeys)];
            $table = $tables[$tableId];

            $eventId = $cfg['event_id'] ?? (rand(0, 1) ? $events->random()->id : null);
            $basePrice = (float) $table->price;
            $hasDiscount = (rand(1, 10) <= 4);

            $discountType = null;
            $discountCode = null;
            $discountAmount = 0.00;

            if ($hasDiscount) {
                if (rand(0, 1) === 0) {
                    $discountType = 'percentage';
                    $discountCode = 'MIDNIGHT20';
                    $discountAmount = round($basePrice * 0.20, 2);
                } else {
                    $discountType = 'fixed';
                    $discountCode = 'FLAT5000';
                    $discountAmount = 5000.00;
                }
            }

            $spendAmount = max(0, $basePrice - $discountAmount);
            $taxRate = 5.00; // 5% GST
            $taxAmount = round($spendAmount * ($taxRate / 100), 2);
            $totalAmountExclTax = $spendAmount;
            $totalAmountInclTax = $spendAmount + $taxAmount;

            $method = $paymentMethods[array_rand($paymentMethods)];
            $gateway = $gateways[array_rand($gateways)];
            $status = $cfg['status'];
            $paymentStatus = $cfg['payment_status'];

            $qrCode = 'MNC-BK-' . strtoupper(Str::random(10)) . '-' . str_pad($bookingIdCounter, 4, '0', STR_PAD_LEFT);

            $booking = Booking::updateOrCreate(
                ['id' => $bookingIdCounter],
                [
                    'client_id'             => $client->id,
                    'table_id'              => $tableId,
                    'event_id'              => $eventId,
                    'club_id'               => 1,
                    'client_name'           => $client->name,
                    'client_phone'          => $client->phone,
                    'client_email'          => $client->email,
                    'club_name'             => 'Mid Night Club',
                    'base_price'            => $basePrice,
                    'spend_amount'          => $spendAmount,
                    'discount_type'         => $discountType,
                    'discount_code'         => $discountCode,
                    'discount_source'       => $discountCode ? 'PROMO' : null,
                    'discount_note'         => $discountCode ? 'Applied at checkout' : null,
                    'discount_amount'       => $discountAmount,
                    'max_discount_amount'   => $discountAmount > 0 ? $discountAmount : null,
                    'tax_rate'              => $taxRate,
                    'tax_amount'            => $taxAmount,
                    'total_amount_excl_tax' => $totalAmountExclTax,
                    'total_amount_incl_tax' => $totalAmountInclTax,
                    'payment_status'        => $paymentStatus,
                    'payment_gateway'       => $gateway,
                    'payment_method'        => $method,
                    'booking_date'          => $cfg['date'],
                    'start_time'            => '21:00:00',
                    'end_time'              => '04:00:00',
                    'guest_count'           => rand(2, $table->capacity),
                    'status'                => $status,
                    'special_requests'      => $specialRequestsPool[array_rand($specialRequestsPool)],
                    'qr_code'               => $qrCode,
                    'created_at'            => $cfg['created_at'],
                    'updated_at'            => $cfg['created_at'],
                ]
            );

            // Seed 1-3 Booking Guests
            $numGuests = rand(1, min(3, $table->capacity - 1));
            for ($g = 1; $g <= $numGuests; $g++) {
                $gName = $guestNamePool[array_rand($guestNamePool)];
                BookingGuest::updateOrCreate(
                    [
                        'booking_id' => $booking->id,
                        'name'       => $gName,
                    ],
                    [
                        'client_id'  => $client->id,
                        'email'      => strtolower(str_replace(' ', '.', $gName)) . '@example.com',
                        'phone'      => '+9198' . rand(10000000, 99999999),
                        'age'        => (string) rand(22, 35),
                        'gender'     => (rand(0, 1) ? 'Male' : 'Female'),
                        'created_at' => $cfg['created_at'],
                        'updated_at' => $cfg['created_at'],
                    ]
                );
            }

            // Seed Payment & Transaction if Paid or Refunded
            if (in_array($paymentStatus, ['paid', 'refunded', 'completed'])) {
                $payment = Payment::updateOrCreate(
                    ['booking_id' => $booking->id],
                    [
                        'amount'                => $totalAmountInclTax,
                        'payment_method'        => $method,
                        'status'                => ($paymentStatus === 'refunded' ? 'refunded' : 'completed'),
                        'transaction_reference' => 'TXN-' . rand(10000000, 99999999),
                        'created_at'            => $cfg['created_at'],
                        'updated_at'            => $cfg['created_at'],
                    ]
                );

                Transaction::updateOrCreate(
                    ['booking_id' => $booking->id],
                    [
                        'payment_id'       => $payment->id,
                        'amount'           => $totalAmountInclTax,
                        'type'             => ($paymentStatus === 'refunded' ? 'refund' : 'charge'),
                        'status'           => 'success',
                        'reference'        => 'PG-' . strtoupper(Str::random(12)),
                        'response_payload' => [
                            'gateway'      => $gateway,
                            'auth_code'    => 'AUTH' . rand(1000, 9999),
                            'payment_id'   => 'pay_' . Str::random(14),
                            'order_id'     => 'order_' . Str::random(14),
                            'amount_cents' => (int) ($totalAmountInclTax * 100),
                            'currency'     => 'INR',
                        ],
                        'created_at'       => $cfg['created_at'],
                        'updated_at'       => $cfg['created_at'],
                    ]
                );
            }

            $bookingIdCounter++;
        }

        $this->command->info('✅ Bookings (' . ($bookingIdCounter - 1) . ' total), Guests, Payments & Transactions seeded successfully.');
    }
}
