<?php

namespace App\Model;

use App\Traits\ResolvesFileUrls;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Casts\Attribute;

class ClubStaff extends Model
{
    use ResolvesFileUrls;

    protected $table = 'club_staff';

    protected $guarded = ['id'];

    protected $casts = [
        'social_accounts' => 'array',
        'is_active' => 'boolean',
        'created_at' => 'date:Y-m-d h:i A',
        'updated_at' => 'date:Y-m-d h:i A',
    ];

    protected $appends = ['avatar_url'];

    public function club(): BelongsTo
    {
        return $this->belongsTo(Club::class);
    }

    protected function avatarUrl(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->getFileUrl(
                $this->avatar
            )
        );
    }
}
