<?php

namespace App\Model;

use App\Traits\ResolvesFileUrls;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class ClubAsset extends Model
{
    use ResolvesFileUrls;

    protected $guarded = ['id'];

    protected $casts = [
        'is_active' => 'boolean',
        'file_size' => 'integer',
        'width'     => 'integer',
        'height'    => 'integer',
        'created_at' => 'date:Y-m-d h:i A',
        'updated_at' => 'date:Y-m-d h:i A',
    ];

    protected $appends = ['file_url', 'formatted_size'];

    /**
     * Get the full URL for the asset.
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
