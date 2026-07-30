<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MobileAppRole extends Model
{
    protected $guarded = ['id'];

    protected $hidden = ['created_at', "updated_at"];

    /**
     * The attributes that are castable.
     *
     * @var array
     */
    protected $casts = [
        'permissions' => 'array',
    ];
}
