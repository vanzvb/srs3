<?php

namespace App\Models\CRMXI3_Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\CRMXI3_Model\CRXMIVehicleOwner;

class CRMXICategory extends Model
{
    use HasFactory;
    
    protected $table = 'crmxi3_categories';
    protected $primaryKey = 'id';

    public function vehicle () {
        return $this->belongsTo(CRXMIVehicleOwner::class, 'category_id');
    }
}
