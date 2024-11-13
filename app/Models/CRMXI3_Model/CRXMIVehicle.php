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

    // protected $fillable = [
    //     'srs_request_id', 'req_type', 'plate_no', 'brand', 'series', 'year_model', 'crm_id',
    //     'old_sticker_no', 'color', 'type', 'cr_from_crm', 'plate_no_remarks',
    //     'color_remarks', 'account_id', 'address_id', 'red_tag', 'vehicle_ownership_status_id',
    // ];

    public function crmxiAccount()
    {
        return $this->belongsTo(CRMXIMain::class, 'account_id');
    }

    public function vehicleOwner() 
    {
        return $this->hasOne(CRXMIVehicleOwner::class, 'vehicle_id','id');
    }

    public function vehicleAddress() 
    {
        return $this->hasOne(CRMXIAddress::class, 'id','address_id');
    }

    public function vehicleOwnershipStatus() 
    {
        return $this->hasOne(CRMXIVehicleOwnershipStatus::class, 'id', 'vehicle_ownership_status_id');
    }
    
}