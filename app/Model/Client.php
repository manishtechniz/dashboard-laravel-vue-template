<?php

namespace App\Model;

use App\Traits\ResolvesFileUrls;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Client extends Model
{
    use HasApiTokens, HasFactory, Notifiable, ResolvesFileUrls;

    protected $guarded = ['id'];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'created_at' => 'date:Y-m-d h:i A',
        'updated_at' => 'date:Y-m-d h:i A',
    ];

    /**
     * Route notifications for the FCM channel.
     */
    public function routeNotificationForFcm($notification)
    {
        return $this->fcm_token;
    }

    protected function avatar(): Attribute
    {
        return Attribute::make(
            get: fn($avatar) => $this->getFileUrl(
                $avatar
            )
        );
    }

    public function hasAppPermission($permission)
    {
        $tablePermission = $this->role?->permissions ?? [];

        if (in_array('*', $tablePermission)) {
            return true;
        }

        return in_array($permission, $tablePermission);
    }

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }

    public function notifications(): HasMany
    {
        return $this->hasMany(Notification::class);
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    public function complaints(): HasMany
    {
        return $this->hasMany(Complaint::class);
    }

    public function guests(): HasMany
    {
        return $this->hasMany(ClientGuest::class);
    }

    public function featureRequests(): HasMany
    {
        return $this->hasMany(FeatureRequest::class);
    }

    public function role(): BelongsTo
    {
        return $this->belongsTo(MobileAppRole::class, 'role_id');
    }

    public function bookingGuest(): HasMany
    {
        return $this->hasMany(BookingGuest::class);
    }
}
