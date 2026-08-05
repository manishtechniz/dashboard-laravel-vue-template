<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Temporarily disable foreign key checks for clean, repeatable seeding
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        $this->call([
            // 1. Core System & Security Roles
            RoleSeeder::class,
            AdminUserSeeder::class,
            MobileAppRoleSeeder::class,

            ClientSeeder::class,

            // 2. Club & Venue Structure (Single Club: Mid Night Club, club_id=1)
            ClubSeeder::class,
            TableSeeder::class,

            // 3. Events, Promo Vouchers & Clients
            EventSeeder::class,
            PromoCodeSeeder::class,

            // 4. Operations, Bookings, Financials & Reviews
            BookingSeeder::class,
            ReviewSeeder::class,

            // 5. Customer Feedback, Notifications & System Configuration
            ComplaintAndFeatureSeeder::class,
            NotificationAndSettingSeeder::class,
        ]);

        // Re-enable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $this->command->newLine();
        $this->command->info('🎉 ======================================================= 🎉');
        $this->command->info('🍾 Mid Night Club (club_id=1) Database Seeded Successfully! 🍾');
        $this->command->info('---------------------------------------------------------');
        $this->command->info('📱 Tester Client (client_id: 1):');
        $this->command->line('   Phone:    9876543210');
        $this->command->line('   Email:    test@email.com');
        $this->command->line('   Password: password');
        $this->command->info('🔑 Admin Portal:');
        $this->command->line('   Email:    admin@admin.com');
        $this->command->line('   Password: password');
        $this->command->info('🎉 ======================================================= 🎉');
    }
}
