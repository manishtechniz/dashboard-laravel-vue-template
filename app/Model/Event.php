<?php

namespace App\Model;

use App\Traits\ResolvesImageUrls;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Event extends Model
{
    use ResolvesImageUrls;

    protected $guarded = ['id'];

    protected $appends = ['image_url', 'featured_image_url'];

    protected $casts = [
        'event_date' => 'date',
        'cover_charge' => 'decimal:2',
        'created_at' => 'date:Y-m-d h:i A',
        'updated_at' => 'date:Y-m-d h:i A',
    ];

    protected function imageUrl(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->getImageUrl(
                $this->image
            )
        );
    }

    protected function featuredImageUrl(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->getImageUrl(
                $this->featured_image
            )
        );
    }

    public function club(): BelongsTo
    {
        return $this->belongsTo(Club::class);
    }

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }

    public function coupon(): HasOne
    {
        return $this->hasOne(PromoCode::class);
    }
}
