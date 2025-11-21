<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SmartHome extends Model
{
    protected $table = 'smart_homes';

    protected $fillable = [
        'object',
        'status',
    ];
}
