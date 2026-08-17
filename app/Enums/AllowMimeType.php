<?php

namespace App\Enums;

enum AllowMimeType: string
{
    case JPG = 'jpg';
    case JPEG = 'jpeg';
    case PNG = 'png';
    case WEBP = 'webp';
    case MP4 = 'mp4';

    public static function imageValues(): array
    {
        return [
            self::JPG->value,
            self::JPEG->value,
            self::PNG->value,
            self::WEBP->value,
        ];
    }

    public static function imageDetails()
    {
        return collect(self::imageValues())->map(fn($value) => [
            'value' => $value,
            'label' => self::tryFrom($value)?->label(),
        ]);
    }

    public static function videoValues(): array
    {
        return [
            self::MP4->value,
        ];
    }

    public static function videoDetails()
    {
        return collect(self::videoValues())->map(fn($value) => [
            'value' => $value,
            'label' => self::tryFrom($value)?->label(),
        ]);
    }

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    public function details()
    {
        return collect(self::cases())->map(fn($case) => [
            'value' => $case->value,
            'label' => $case->label(),
        ]);
    }

    public function label(): string
    {
        return match ($this) {
            self::JPG => 'Image (JPG)',
            self::JPEG => 'Image (JPEG)',
            self::PNG => 'Image (PNG)',
            self::WEBP => 'Image (WebP)',
            self::MP4 => 'Video (MP4)',
        };
    }

    public function badge(): string
    {
        return match ($this) {
            self::JPG => 'badge badge-primary',
            self::JPEG => 'badge badge-secondary',
            self::PNG => 'badge badge-success',
            self::WEBP => 'badge badge-warning',
            self::MP4 => 'badge badge-danger',
        };
    }
}
