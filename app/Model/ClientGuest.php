<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ClientGuest extends Model
{
    protected $table = 'client_guests';

    protected $fillable = [
        'client_id',
        'name',
        'email',
        'phone',
        'age',
        'gender',
    ];

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }
}
