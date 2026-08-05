<?php

namespace Database\Seeders;

use App\Model\AuditLog;
use App\Model\Client;
use App\Model\Notification;
use App\Model\Setting;
use Illuminate\Database\Seeder;

class NotificationAndSettingSeeder extends Seeder
{
    public function run(): void
    {
        $clients = Client::all();

        // 1. Client Notifications
        if ($clients->isNotEmpty()) {
            $notifications = [
                [
                    'title'      => 'VIP Table Confirmed! 🎉',
                    'body'       => 'Your VIP table reservation for Mid Night Club has been confirmed. Show your QR code at the entrance.',
                    'type'       => 'booking_status',
                    'remark'     => 'Automated Booking Confirmation',
                    'additional' => json_encode(['club_id' => 1, 'route' => '/my-bookings']),
                    'read'       => false,
                    'created_at' => now()->subMinutes(35),
                ],
                [
                    'title'      => 'Upcoming Event: Neon EDM Carnival! ⚡',
                    'body'       => 'Get ready for ultraviolet lasers, international guest DJs, and bass music this weekend. Limited VIP tables left!',
                    'type'       => 'event_alert',
                    'remark'     => 'Event Campaign Broadcast',
                    'additional' => json_encode(['club_id' => 1, 'event_id' => 2, 'route' => '/events/2']),
                    'read'       => false,
                    'created_at' => now()->subHours(3),
                ],
                [
                    'title'      => 'Exclusive Promo: 20% Off! 🎁',
                    'body'       => 'Use coupon code MIDNIGHT20 to unlock 20% off on your next weekend reservation.',
                    'type'       => 'promo',
                    'remark'     => 'VIP Promotion Push',
                    'additional' => json_encode(['promo_code' => 'MIDNIGHT20']),
                    'read'       => false,
                    'created_at' => now()->subDay(),
                ],
                [
                    'title'      => 'Check-in Complete ✨',
                    'body'       => 'Welcome to Mid Night Club! Your table host is ready to serve you. Have an unforgettable night!',
                    'type'       => 'booking_status',
                    'remark'     => 'Entry Gate Scan',
                    'additional' => json_encode(['club_id' => 1]),
                    'read'       => true,
                    'created_at' => now()->subDays(2),
                ],
                [
                    'title'      => 'Payment Receipt #TXN-98214 💳',
                    'body'       => 'Your payment of ₹42,000 was successfully processed for VIP Table booking.',
                    'type'       => 'payment',
                    'remark'     => 'Payment Gateway Webhook',
                    'additional' => json_encode(['club_id' => 1, 'booking_id' => 1]),
                    'read'       => true,
                    'created_at' => now()->subDays(2),
                ],
            ];

            foreach ($clients->take(8) as $client) {
                foreach ($notifications as $n) {
                    $isTester = ($client->id === 1);
                    $isRead = $isTester ? $n['read'] : (bool) rand(0, 1);

                    Notification::create([
                        'client_id'  => $client->id,
                        'title'      => $n['title'],
                        'body'       => $n['body'],
                        'created_by' => 1,
                        'remark'     => $n['remark'],
                        'type'       => $n['type'],
                        'additional' => $n['additional'],
                        'read_at'    => $isRead ? now()->subHours(rand(1, 24)) : null,
                        'created_at' => $n['created_at'] ?? now()->subDays(rand(1, 10)),
                        'updated_at' => $n['created_at'] ?? now()->subDays(rand(1, 10)),
                    ]);
                }
            }
        }

        // 2. Global System & Club Settings
        $settings = [
            ['key' => 'app_name', 'value' => 'Mid Night Club Imperial Portal', 'type' => 'string'],
            ['key' => 'contact_email', 'value' => 'reservations@midnightclub.com', 'type' => 'string'],
            ['key' => 'contact_phone', 'value' => '+91 9711891515', 'type' => 'string'],
            ['key' => 'currency_symbol', 'value' => '₹', 'type' => 'string'],
            ['key' => 'currency_code', 'value' => 'INR', 'type' => 'string'],
            ['key' => 'default_tax_rate', 'value' => '5.00', 'type' => 'decimal'],
            ['key' => 'opening_hours', 'value' => '08:00 PM - 07:00 AM', 'type' => 'string'],
            ['key' => 'cancellation_cutoff_hours', 'value' => '6', 'type' => 'integer'],
            ['key' => 'enable_fcm_notifications', 'value' => '1', 'type' => 'boolean'],
            ['key' => 'enable_sms_alerts', 'value' => '1', 'type' => 'boolean'],
        ];

        foreach ($settings as $setting) {
            Setting::updateOrCreate(['key' => $setting['key']], $setting);
        }

        // 3. Admin Audit Logs
        $auditLogs = [
            [
                'user_id'    => 1,
                'action'     => 'CREATE',
                'model_type' => 'App\Model\ClubTable',
                'model_id'   => 1,
                'old_values' => null,
                'new_values' => ['name' => 'VIP table', 'price' => 40000.00, 'capacity' => 6],
                'ip_address' => '127.0.0.1',
            ],
            [
                'user_id'    => 1,
                'action'     => 'CREATE',
                'model_type' => 'App\Model\PromoCode',
                'model_id'   => 1,
                'old_values' => null,
                'new_values' => ['code' => 'MIDNIGHT20', 'discount' => '20%'],
                'ip_address' => '127.0.0.1',
            ],
            [
                'user_id'    => 2,
                'action'     => 'UPDATE',
                'model_type' => 'App\Model\Booking',
                'model_id'   => 1,
                'old_values' => ['status' => 'pending'],
                'new_values' => ['status' => 'confirmed'],
                'ip_address' => '192.168.1.45',
            ],
            [
                'user_id'    => 3,
                'action'     => 'UPDATE',
                'model_type' => 'App\Model\Booking',
                'model_id'   => 1,
                'old_values' => ['status' => 'confirmed'],
                'new_values' => ['status' => 'checked_in'],
                'ip_address' => '192.168.1.50',
            ],
        ];

        foreach ($auditLogs as $log) {
            AuditLog::create([
                'user_id'    => $log['user_id'],
                'action'     => $log['action'],
                'model_type' => $log['model_type'],
                'model_id'   => $log['model_id'],
                'old_values' => $log['old_values'],
                'new_values' => $log['new_values'],
                'ip_address' => $log['ip_address'],
                'created_at' => now()->subDays(rand(1, 7)),
                'updated_at' => now()->subDays(rand(1, 7)),
            ]);
        }

        $this->command->info('✅ Notifications, Settings and Audit Logs seeded successfully.');
    }
}
