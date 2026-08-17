<?php

namespace App\Enums;

enum PaymentMethod: string
{
    case WALLET = 'wallet';
    case CASH = 'cash';
    case ONLINE = 'online';

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
            self::WALLET => 'Wallet',
            self::CASH => 'Cash',
            self::ONLINE => 'Online',
        };
    }

    public function badge(): string
    {
        return match ($this) {
            self::WALLET => 'badge badge-primary',
            self::CASH => 'badge badge-secondary',
            self::ONLINE => 'badge badge-secondary',
        };
    }
}
