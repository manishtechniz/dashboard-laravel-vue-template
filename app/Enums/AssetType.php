<?php

namespace App\Enums;

enum AssetType: string
{
    case IMAGE = 'image';
    case VIDEO = 'video';
    case IMAGE_URL = 'image_url';
    case VIDEO_URL = 'video_url';

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
            self::IMAGE => 'Image',
            self::VIDEO => 'Video',
            self::IMAGE_URL => 'Image URL',
            self::VIDEO_URL => 'Video URL',
        };
    }

    public function badge(): string
    {
        return match ($this) {
            self::IMAGE => 'badge badge-primary',
            self::VIDEO => 'badge badge-secondary',
            self::IMAGE_URL => 'badge badge-success',
            self::VIDEO_URL => 'badge badge-warning',
        };
    }
}
