<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FeatureRequest extends Model
{
    protected $table = 'feature_requests';

    protected $fillable = [
        'client_id',
        'title',
        'description',
        'status',
        'priority',
    ];

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }
}
