<?php

namespace App\Enums;

enum BookingStatus: string
{
    case PENDING = 'pending';
    case CONFIRMED = 'confirmed';
    case CHECKED_IN = 'checked_in';
    case COMPLETED = 'completed';
    case CANCELLED = 'cancelled';

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
            self::PENDING => 'Pending',
            self::CONFIRMED => 'Confirmed',
            self::CHECKED_IN => 'Checked In',
            self::COMPLETED => 'Checked In',
            self::CANCELLED => 'Cancelled',
        };
    }

    public function notificationDescription(): string
    {
        return match ($this) {
            self::PENDING => 'Your booking has been received and is currently being processed.',
            self::CONFIRMED => 'Your reservation is successfully confirmed.',
            self::CHECKED_IN => 'Check-in is complete. Thank you for choosing our services.',
            self::COMPLETED => 'Check-in is complete. Thank you for choosing our services.',
            self::CANCELLED => 'Your booking has been cancelled as requested.',
        };
    }

    public function notificationTitle(): string
    {
        return match ($this) {
            self::PENDING => 'Request Received! 📨',
            self::CONFIRMED => 'You\'re Confirmed! 🎉',
            self::CHECKED_IN => 'Welcome In! ✨',
            self::COMPLETED => 'Welcome In! ✨',
            self::CANCELLED => 'Booking Cancelled 😔',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::PENDING => 'bg-yellow-100 text-yellow-700',
            self::CONFIRMED => 'bg-orange-100 text-orange-700',
            self::CHECKED_IN => 'bg-green-100 text-green-700',
            self::COMPLETED => 'bg-green-100 text-green-700',
            self::CANCELLED => 'bg-red-100 text-red-700',
        };
    }
}
