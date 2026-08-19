<?php

namespace App\Models\Supabase;

use Illuminate\Database\Eloquent\Model;

class ClubTable extends Model
{
    /**
     * The database connection that should be used by the model.
     *
     * @var string
     */
    protected $connection = 'supabase';

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'club_tables';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'table_number',
        'table_type',
        'service_staff',
        'booking_time',
        'status',
        'bill_amount',
        'guest_name',
        'is_locked',
        'lock_password',
        'locked_by_name'
    ];
}
