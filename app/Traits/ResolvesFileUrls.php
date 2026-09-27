<?php

namespace App\Traits;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Vite;
use Illuminate\Support\Str;

trait ResolvesFileUrls
{
    /**
     * Convert a database image path into a fully qualified URL.
     */
    protected function getFileUrl(?string $path, ?string $type = 'image', ?string $fallbackUrl = null)
    {
        if (! $path) {
            return $fallbackUrl ?? $this->resolvePreviewUrl($type);
        }

        if (Str::startsWith($path, ['http://', 'https://'])) {
            return $path;
        }

        return Storage::url($path);
    }

    /**
     * Summary of previewURL 
     */
    protected function imagePreviewURL()
    {
        return Vite::asset('resources/images/preview-image.webp');
        return Storage::disk('public')->url('preview-image.webp');
    }

    protected function audioPreviewURL()
    {
        return null;
    }

    protected function videoPreviewURL()
    {
        return null;
    }

    protected function resolvePreviewUrl(?string $fileType)
    {
        $methodName = $fileType . 'PreviewURL';

        if (! method_exists($this, $methodName)) {
            return null;
        }

        return $this->{$methodName}();
    }
}
