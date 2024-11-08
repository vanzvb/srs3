<?php

namespace App\Models\CRMXI3_Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\CRMXI3_Model\CRMXIMain;
use App\Models\CRMXI3_Model\CRXMIVehicleOwner;


class CRMXIHoa extends Model
{
    use HasFactory;
    
    protected $table = 'crmxi3_hoas';

    public function crmxiAccount()
    {
        return $this->belongsTo(CRMXIMain::class, 'hoa');
    }

    public function vehicle () {
        return $this->belongsTo(CRXMIVehicleOwner::class, 'hoa');
    }

    public function hoaType()
    {
        return $this->belongsTo(CRMXIHoaType::class, 'type');
    }
}
