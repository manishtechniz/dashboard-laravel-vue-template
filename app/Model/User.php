<?php

namespace App\Model;

use App\Traits\ResolvesImageUrls;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Support\Facades\Storage;

class User extends Authenticatable
{
    use HasFactory, Notifiable, ResolvesImageUrls;

    protected $guarded = ['id'];
    protected $appends = ['avatar_url', 'avatar_preview_url'];

    protected $hidden = ['password', 'remember_token'];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'last_login_at'     => 'datetime',
        'theme_config'      => 'array',
        'is_active'         => 'boolean',
    ];

    protected function avatarUrl(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->getImageUrl(
                $this->avatar
            )
        );
    }

    protected function avatarPreviewUrl(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->previewURL()
        );
    }
}
