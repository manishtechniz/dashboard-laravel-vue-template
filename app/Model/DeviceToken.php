<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DeviceToken extends Model
{
    protected $fillable = [
        'client_id',
        'token',
        'device_type',
    ];

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }
}
