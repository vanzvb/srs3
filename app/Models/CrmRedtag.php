<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CrmRedtag extends Model
{
    use HasFactory;

    protected $table = 'crm_redtag';
    public $timestamps = false;

    public function crm()
    {
        return $this->belongsTo(CrmMain::class, 'crm_id');
    }
}
