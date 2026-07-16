<?php

namespace Database\Seeders;

use App\Model\Branch;
use App\Model\Booking;
use App\Model\Client;
use App\Model\Club;
use App\Model\ClubTable;
use App\Model\Event;
use App\Model\Floor;
use App\Model\Payment;
use App\Model\PromoCode;
use App\Model\Setting;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class ClubBookingSystemSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Seed Clients
        $client1 = Client::create([
            'name' => 'John Doe',
            'email' => 'john@doe.com',
            'phone' => '9876543210',
            'password' => Hash::make('password'),
            'is_active' => true,
        ]);

        $client2 = Client::create([
            'name' => 'Jane Smith',
            'email' => 'jane@smith.com',
            'phone' => '8765432109',
            'password' => Hash::make('password'),
            'is_active' => true,
        ]);

        // 2. Seed Clubs
        $club1 = Club::create([
            'name' => 'Club Imperial',
            'description' => 'A luxury clubbing experience.',
            'address' => '123 Elite Road',
            'city' => 'Metropolis',
            'is_active' => true,
        ]);

        // 3. Seed Branches
        $branch1 = Branch::create([
            'club_id' => $club1->id,
            'name' => 'Downtown Branch',
            'description' => 'Heart of the city.',
            'address' => '456 Central Ave',
            'phone' => '555-1234',
            'is_active' => true,
        ]);

        // 4. Seed Floors
        $floor1 = Floor::create([
            'branch_id' => $branch1->id,
            'name' => 'VVIP Floor',
            'level' => 1,
            'is_active' => true,
        ]);

        $floor2 = Floor::create([
            'branch_id' => $branch1->id,
            'name' => 'Main Dance Floor',
            'level' => 0,
            'is_active' => true,
        ]);

        // 5. Seed Tables
        $table1 = ClubTable::create([
            'floor_id' => $floor1->id,
            'name' => 'V1',
            'capacity' => 6,
            'status' => 'available',
            'x_position' => 10,
            'y_position' => 20,
        ]);

        $table2 = ClubTable::create([
            'floor_id' => $floor2->id,
            'name' => 'M1',
            'capacity' => 4,
            'status' => 'available',
            'x_position' => 50,
            'y_position' => 60,
        ]);

        // 6. Seed Events
        $event1 = Event::create([
            'club_id' => $club1->id,
            'name' => 'Neon Nights DJ Jam',
            'description' => 'Neon themed music party with world-class DJs.',
            'start_time' => now()->addDays(2)->setHour(21)->setMinute(0),
            'end_time' => now()->addDays(3)->setHour(4)->setMinute(0),
            'cover_charge' => 25.00,
            'capacity' => 500,
        ]);

        // 7. Seed Bookings
        $booking1 = Booking::create([
            'client_id' => $client1->id,
            'table_id' => $table1->id,
            'event_id' => $event1->id,
            'booking_date' => now()->addDays(2)->toDateString(),
            'start_time' => '21:00:00',
            'end_time' => '02:00:00',
            'guest_count' => 4,
            'status' => 'confirmed',
            'special_requests' => 'VVIP service requested.',
            'qr_code' => 'IMP-CONF1234',
        ]);

        // 8. Seed Payments
        Payment::create([
            'booking_id' => $booking1->id,
            'amount' => 150.00,
            'payment_method' => 'Stripe',
            'status' => 'completed',
            'transaction_reference' => 'ch_test123',
        ]);

        // 9. Seed Promo Codes
        PromoCode::create([
            'code' => 'IMPERIAL10',
            'type' => 'percentage',
            'value' => 10.00,
            'start_date' => now()->toDateString(),
            'end_date' => now()->addMonth()->toDateString(),
            'is_active' => true,
        ]);

        // 10. Seed Settings
        Setting::create([
            'key' => 'system_name',
            'value' => 'Imperial Booking Engine',
            'type' => 'string',
        ]);
        Setting::create([
            'key' => 'contact_email',
            'value' => 'contact@imperial.com',
            'type' => 'string',
        ]);
    }
}
