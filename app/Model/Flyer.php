<?php

namespace App\Model;

use App\Traits\ResolvesFileUrls;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Flyer extends Model
{
    use ResolvesFileUrls;

    protected $guarded = ['id'];

    protected $casts = [
        'is_active' => 'boolean',
        'created_at' => 'date:Y-m-d h:i A',
        'updated_at' => 'date:Y-m-d h:i A',
    ];

    protected $appends = ['file_url', 'audio_url'];

    /**
     * Get the full URL for the media file (image/video).
     */
    protected function fileUrl(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->getFileUrl(
                $this->file_path,
                $this->file_type
            )
        );
    }

    /**
     * Get the full URL for the attached audio file.
     */
    protected function audioUrl(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->getFileUrl(
                $this->audio_path,
                'audio',
            )
        );
    }
}
