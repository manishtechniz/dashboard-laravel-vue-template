<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BookingGuest extends Model
{
    protected $guarded = ['id'];

    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class);
    }
}
