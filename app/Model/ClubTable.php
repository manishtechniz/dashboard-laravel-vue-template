<?php

namespace App\Model;

use App\Traits\ResolvesFileUrls;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ClubTable extends Model
{
    use ResolvesFileUrls;

    protected $table = 'tables';

    protected $appends = ['image_url', 'parsed_disclaimer_list'];

    protected $guarded = ['id'];

    protected $casts = [
        'price' => 'decimal:2',
        'cover_charge' => 'decimal:2',
        'late_cover_charge' => 'decimal:2',
        'created_at' => 'date:Y-m-d h:i A',
        'updated_at' => 'date:Y-m-d h:i A',
    ];

    public function club(): BelongsTo
    {
        return $this->belongsTo(Club::class);
    }

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class, 'table_id');
    }

    protected function imageUrl(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->getFileUrl(
                $this->image
            )
        );
    }

    protected function parsedDisclaimerList(): Attribute
    {
        return Attribute::make(
            get: function () {
                $text = $this->disclaimer;
                if (empty($text)) return [];

                return array_values(array_filter(array_map('trim', explode("\n", $text))));
            }
        );
    }
}
