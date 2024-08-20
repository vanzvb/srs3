<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SrsVehicle extends Model
{
    protected $table = 'srs_vehicles';

    protected $casts = [
        'plate_no' => 'encrypted',
        'brand' => 'encrypted',
        'series' => 'encrypted',
        'year_model' => 'encrypted',
        'sticker_no' => 'encrypted',
        'color' => 'encrypted',
        'type' => 'encrypted',
        'req1' => 'encrypted',
        'cr' => 'encrypted',
    ];

    protected $hidden = [
        'plate_no',
        'brand',
        'series',
        'year_model',
        'sticker_no',
        'color',
        'type',
        'req1',
        'cr',
    ];
}
