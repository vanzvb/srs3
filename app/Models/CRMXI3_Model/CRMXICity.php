<?php

namespace App\Models\CRMXI3_Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\CRMXI3_Model\CRMXIMain;

class CRMXICity extends Model
{
    use HasFactory;

    protected $table = 'crmx_bl_city';

    public function crmxiAccount()
    {
        return $this->belongsTo(CRMXIMain::class, 'city');
    }

}
