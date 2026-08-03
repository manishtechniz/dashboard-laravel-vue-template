<?php

namespace App\Model;

use App\Jobs\RecalculateClubRatingJob;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Review extends Model
{
    protected $guarded = ['id'];

    protected $casts = [
        'created_at' => 'date:Y-m-d h:i A',
        'updated_at' => 'date:Y-m-d h:i A',
    ];

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function club(): BelongsTo
    {
        return $this->belongsTo(Club::class);
    }

    protected static function booted(): void
    {
        static::saved(function (Review $review) {
            if ($review->club_id) {
                RecalculateClubRatingJob::dispatch($review->club_id);
            }
        });

        static::deleted(function (Review $review) {
            if ($review->club_id) {
                RecalculateClubRatingJob::dispatch($review->club_id);
            }
        });
    }
}
