<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ClientGuest extends Model
{
    protected $table = 'client_guests';

    protected $guarded = ['id'];

    protected $casts = [
        'created_at' => 'date:Y-m-d h:i A',
        'updated_at' => 'date:Y-m-d h:i A',
    ];

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }
}
