<?php

namespace App\Models\CRMXI3_Model;

use Illuminate\Database\Eloquent\Factories\BelongsToRelationship;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

use App\Models\CRMXI3_Model\CRMXIMain;
use App\Models\CRMXI3_Model\CRXMIVehicleOwner;

class CRXMIVehicle extends Model
{
    use HasFactory;

    protected $table = 'crmxi3_vehicles';
    protected $primaryKey = 'id';

    public function crmxiAccount()
    {
        return $this->belongsTo(CRMXIMain::class, 'account_id');
    }

    public function vehicleOwner() 
    {
        return $this->hasOne(CRXMIVehicleOwner::class, 'vehicle_id','id');
    }
    
}
