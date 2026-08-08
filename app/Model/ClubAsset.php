<?php

namespace App\Model;

use App\Traits\ResolvesImageUrls;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class ClubAsset extends Model
{
    use ResolvesImageUrls;

    protected $guarded = ['id'];

    protected $casts = [
        'is_active' => 'boolean',
        'file_size' => 'integer',
        'width'     => 'integer',
        'height'    => 'integer',
        'created_at' => 'date:Y-m-d h:i A',
        'updated_at' => 'date:Y-m-d h:i A',
    ];

    protected $appends = ['url', 'formatted_size'];

    /**
     * Get the full URL for the asset.
     */
    protected function url(): Attribute
    {
        return Attribute::make(
            get: function () {
                if (! $this->file_path) {
                    return $this->previewURL();
                }

                if (str_starts_with($this->file_path, 'http://') || str_starts_with($this->file_path, 'https://')) {
                    return $this->file_path;
                }

                return Storage::disk('public')->url($this->file_path);
            }
        );
    }

    /**
     * Human-readable file size.
     */
    protected function formattedSize(): Attribute
    {
        return Attribute::make(
            get: function () {
                $bytes = $this->file_size ?? 0;
                if ($bytes >= 1073741824) {
                    return number_format($bytes / 1073741824, 2) . ' GB';
                } elseif ($bytes >= 1048576) {
                    return number_format($bytes / 1048576, 2) . ' MB';
                } elseif ($bytes >= 1024) {
                    return number_format($bytes / 1024, 1) . ' KB';
                } elseif ($bytes > 0) {
                    return $bytes . ' B';
                }
                return '0 B';
            }
        );
    }

    /**
     * Relationship to Club.
     */
    public function club(): BelongsTo
    {
        return $this->belongsTo(Club::class);
    }
}

