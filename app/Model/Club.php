<?php

namespace App\Model;

use App\Traits\ResolvesImageUrls;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Club extends Model
{
    use ResolvesImageUrls;

    protected $guarded = ['id'];

    protected $casts = [
        'is_active' => 'boolean',
        'created_at' => 'date:Y-m-d h:i A',
        'updated_at' => 'date:Y-m-d h:i A',
    ];

    protected $appends = ['image_url', 'featured_image_url'];

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

    /**
     * Recalculate and save the average rating and individual rating percentages for this club.
     */
    public function recalculateRating(): void
    {
        $stats = $this->reviews()
            ->where('is_active', 1)
            ->selectRaw('
                COUNT(id) as total_count, 
                AVG(rating) as avg_rating,
                SUM(CASE WHEN rating = 5 THEN 1 ELSE 0 END) as count_5,
                SUM(CASE WHEN rating = 4 THEN 1 ELSE 0 END) as count_4,
                SUM(CASE WHEN rating = 3 THEN 1 ELSE 0 END) as count_3,
                SUM(CASE WHEN rating = 2 THEN 1 ELSE 0 END) as count_2,
                SUM(CASE WHEN rating = 1 THEN 1 ELSE 0 END) as count_1
            ')
            ->first();

        $totalCount = (int) ($stats->total_count ?? 0);

        // Helper function to safely calculate percentages and prevent division by zero
        $getPercentage = function ($count) use ($totalCount) {
            return $totalCount > 0 ? round(($count / $totalCount) * 100, 2) : 0;
        };

        $this->update([
            'average_rating'   => round($stats->avg_rating ?? 0, 2),
            'review_count'     => $totalCount,
            'rating_5_percent' => $getPercentage($stats->count_5 ?? 0),
            'rating_4_percent' => $getPercentage($stats->count_4 ?? 0),
            'rating_3_percent' => $getPercentage($stats->count_3 ?? 0),
            'rating_2_percent' => $getPercentage($stats->count_2 ?? 0),
            'rating_1_percent' => $getPercentage($stats->count_1 ?? 0),
        ]);
    }

    public function branches(): HasMany
    {
        return $this->hasMany(Branch::class);
    }

    public function events(): HasMany
    {
        return $this->hasMany(Event::class);
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    public function tables(): HasMany
    {
        return $this->hasMany(ClubTable::class);
    }
}
