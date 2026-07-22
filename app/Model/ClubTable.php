<?php

namespace App\Model;

use App\Traits\ResolvesImageUrls;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ClubTable extends Model
{
    use ResolvesImageUrls;

    protected $table = 'tables';

    protected $appends = ['image_url'];

    protected $fillable = [
        'club_id',
        'name',
        'capacity',
        'total_tables',
        'status',
        'image',
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
            get: fn() => $this->getImageUrl(
                $this->image
            )
        );
    }
}
