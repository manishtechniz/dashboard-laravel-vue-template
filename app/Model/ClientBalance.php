<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class ClientBalance extends Model
{
    protected $guarded = ['id'];

    protected $casts = [
        'total_due' => 'decimal:2',
        'total_advance' => 'decimal:2',
    ];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }
}
