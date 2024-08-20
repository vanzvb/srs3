<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LogVehicleHist extends Model
{
    use HasFactory;

    protected $table = 'log_vehicle_hist';

    const UPDATED_AT = null;
}
