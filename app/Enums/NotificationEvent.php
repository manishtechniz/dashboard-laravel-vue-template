<?php

namespace App\Enums;

enum NotificationEvent: string
{
    case BOOKING_STATUS = 'booking_status';
    case OFFER = 'offer';
    case EVENT = 'event';
    case PAYMENT = 'payment';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    public static function details()
    {
        return collect(self::cases())->map(fn($case) => [
            'value' => $case->value,
            'label' => $case->label(),
        ]);
    }

    public function label(): string
    {
        return match ($this) {
            self::BOOKING_STATUS => 'Booking Status',
            self::OFFER => 'Offer',
            self::EVENT => 'Event',
            self::PAYMENT => 'Payment'
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::BOOKING_STATUS => 'badge badge-success',
            self::OFFER => 'badge badge-success',
            self::EVENT => 'badge badge-success',
            self::PAYMENT => 'badge badge-success',
        };
    }
}
