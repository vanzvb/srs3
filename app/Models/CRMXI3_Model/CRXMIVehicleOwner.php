<?php

namespace App\Models\CRMXI3_Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\CRMXI3_Model\CRXMIVehicle;
use App\Models\CRMXI3_Model\CRMXICategory;
use App\Models\CRMXI3_Model\CRMXISubcat;
use App\Models\CRMXI3_Model\CRMXIHoa;
use App\Models\CRMXI3_Model\CRMXIHoaType;
use App\Models\CRMXI3_Model\CRMXIVehicleOwnershipStatus;


class CRXMIVehicleOwner extends Model
{
    use HasFactory;

    protected $table = 'crmxi3_vehicle_owners';
    protected $primaryKey = 'id';

    public function vehicles()
    {
        return $this->belongsTo(CRXMIVehicle::class, 'id');
    }

    public function categories() {
        return $this->hasOne(CRMXICategory::class, 'id','category_id');
    }

    public function subcats() {
        return $this->hasOne(CRMXISubcat::class, 'id', 'sub_category_id');
    }

    public function hoas() {
        return $this->hasOne(CRMXIHoa::class, 'id','hoa');
    }

    public function hoaTypes() {
        return $this->hasOne(CRMXIHoaType::class, 'id','hoa_type');
    }

    public function vos() {
        return $this->hasOne(CRMXIVehicleOwnershipStatus::class, 'id', 'vehicle_ownership_status_id');
    }
}
