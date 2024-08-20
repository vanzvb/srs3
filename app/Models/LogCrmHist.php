<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LogCrmHist extends Model
{
    use HasFactory;

    protected $table = 'log_crm_hist';

    const UPDATED_AT = null;
}
