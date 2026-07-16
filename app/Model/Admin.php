<?php

namespace App\Model; 

use Illuminate\Database\Eloquent\Builder;

class Admin extends User
{
    protected $table = 'users'; 

    protected static function booted()
    {
        static::addGlobalScope('admin', function (Builder $builder) {
            $builder->where('user_type', 'admin');
        });

        static::creating(function ($admin) {
            $admin->user_type = 'admin';
        });
    }


}
