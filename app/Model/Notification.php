<?php

namespace App\Model;

use App\Enums\NotificationEvent;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Notification extends Model
{
    protected $guarded = ['id'];

    protected $casts = [
        'read_at' => 'date:Y-m-d h:i A',
        'created_at' => 'date:Y-m-d h:i A',
        'updated_at' => 'date:Y-m-d h:i A',
        'additional' => 'array',
        'type' => NotificationEvent::class
    ];

    protected $appends = [
        'type_label',
        'type_color'
    ];

    // Define the accessor for status_label
    protected function typeLabel(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->type?->label(),
        );
    }

    // Define the accessor for status_color
    protected function typeColor(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->type?->color(),
        );
    }

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }
}
