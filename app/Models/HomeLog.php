<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HomeLog extends Model
{
    protected $guarded = [];

    protected $casts = [
        'payload' => 'array',
        'is_vpn' => 'boolean',
    ];
}
