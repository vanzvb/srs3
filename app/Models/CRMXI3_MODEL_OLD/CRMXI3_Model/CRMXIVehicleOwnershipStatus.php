<?php

namespace App\Models\CRMXI3_Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\CRMXI3_Model\CRXMIVehicleOwner;


class CRMXIVehicleOwnershipStatus extends Model
{
    use HasFactory;

    protected $table = 'crmxi3_vehicle_ownership_status';
    protected $primaryKey = 'id';

    public function vehicle () {
        return $this->belongsTo(CRXMIVehicleOwner::class, 'vehicle_ownership_status_id');
    }
}
