<?php

namespace App\Model;

use App\Enums\BookingStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Facades\Crypt;

class Booking extends Model
{
    protected $guarded = [
        'id',
        'created_at',
    ];

    // Append this to the JSON response automatically
    protected $appends = ['plain_qr_code'];

    protected function casts(): array
    {
        return [
            'status' => BookingStatus::class,
            'booking_date' => 'date',
            'created_at' => 'date:Y-m-d',
            'updated_at' => 'date:Y-m-d',
        ];
    }

    public function getPlainQrCodeAttribute(): ?string
    {
        // Using getRawOriginal() reads the true value from the database without triggering getQrCodeAttribute()
        return $this->getRawOriginal('qr_code');
    }

    /**
     * Intercept the physical qr_code column and encrypt it on output.
     */
    public function getQrCodeAttribute($value): ?string
    {
        if (empty($value)) {
            return null;
        }

        return Crypt::encryptString((string) $value);
    }

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function table(): BelongsTo
    {
        return $this->belongsTo(ClubTable::class, 'table_id');
    }

    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }

    public function club(): BelongsTo
    {
        return $this->belongsTo(Club::class);
    }

    public function guests(): HasMany
    {
        return $this->hasMany(BookingGuest::class);
    }

    public function payment(): HasOne
    {
        return $this->hasOne(Payment::class);
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class);
    }
}
