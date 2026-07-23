<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class PromoCode extends Model
{
    protected $guarded = [
        'id',
        'created_at',
    ];

    protected $casts = [
        'value' => 'decimal:2',
        'min_spend' => 'decimal:2',
        'max_discount' => 'decimal:2',
        'is_active' => 'boolean',
        'start_date' => 'date',
        'end_date' => 'date',
    ];
}
