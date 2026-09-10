<?php

namespace App\Model;

use App\Traits\ResolvesFileUrls;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Support\Facades\Storage;

class User extends Authenticatable
{
    use HasFactory, Notifiable, ResolvesFileUrls;

    protected $guarded = ['id'];
    protected $appends = ['avatar_url', 'avatar_preview_url'];

    protected $hidden = ['password', 'remember_token'];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'last_login_at'     => 'datetime',
        'theme_config'      => 'array',
        'is_active'         => 'boolean',
        'created_at' => 'date:Y-m-d h:i A',
        'updated_at' => 'date:Y-m-d h:i A',
    ];

    protected function avatarUrl(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->getFileUrl(
                $this->avatar,
                'image',
                previewProfileURL()
            )
        );
    }

    protected function avatarPreviewUrl(): Attribute
    {
        return Attribute::make(
            get: fn() => Storage::url('avatar-preview.png')
        );
    }

    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id');
    }

    public function hasPermission($permission)
    {
        $tablePermission = $this->role?->permissions ?? [];

        if (in_array('*', $tablePermission)) {
            return true;
        }

        return in_array($permission, $tablePermission);
    }
}
