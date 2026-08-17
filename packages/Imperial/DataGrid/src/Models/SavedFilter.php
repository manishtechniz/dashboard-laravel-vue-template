<?php

namespace Imperial\DataGrid\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SavedFilter extends Model
{
    use HasFactory;

    /**
     * Deinfine model table name.
     *
     * @var string
     */
    protected $table = 'datagrid_saved_filters';

    /**
     * Fillable property for the model.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'src',
        'name',
        'applied',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'applied' => 'json',
    ];
}
