<?php

namespace App\Traits;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

trait ResolvesImageUrls
{
    /**
     * Convert a database image path into a fully qualified URL.
     */
    protected function getImageUrl(?string $path, ?string $fallbackUrl = null)
    {
        if (! $path) {
            return $fallbackUrl ?? $this->previewURL();
        }

        if (Str::startsWith($path, ['http://', 'https://'])) {
            return $path;
        }

        return Storage::url($path);
    }

    /**
     * Summary of previewURL 
     */
    protected function previewURL()
    {
        return Storage::disk('public')->url('preview-image.webp');
    }
}
