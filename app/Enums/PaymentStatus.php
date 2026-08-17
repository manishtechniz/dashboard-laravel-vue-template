<?php

namespace App\Enums;

enum PaymentStatus: string
{
    case PENDING = 'pending';
    case PAID = 'paid';
    case FAILED = 'failed';
    // case REFUNDED = 'refunded';
    // case PARTIAL_REFUND = 'partial_refund';

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
            self::PAID => 'Paid',
            self::FAILED => 'Failed',
            // self::REFUNDED => 'Refund',
            // self::PARTIAL_REFUND => 'Partial Refund',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::PENDING => 'badge badge-warning',
            self::PAID => 'badge badge-success',
            self::FAILED => 'badge badge-danger',
            // self::REFUNDED => 'badge badge-info',
            // self::PARTIAL_REFUND => 'badge badge-warning',
        };
    }
}
