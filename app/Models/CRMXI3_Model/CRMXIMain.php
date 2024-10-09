<?php

namespace App\Models\CRMXI3_Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\CRMXI3_Model\CRXMIVehicle;
use App\Models\CRMXI3_Model\CRMXICity;
use App\Models\CRMXI3_Model\CRMXIHoa;
use App\Models\SrsUser;

class CRMXIMain extends Model
{
    use HasFactory;

    protected $table = 'crmxi3_mains';
    protected $primaryKey = 'crm_id';

    public function vehicles()
    {
        return $this->hasMany(CRXMIVehicle::class, 'account_id', 'account_id');
    }

    public function cities()
    {
        return $this->hasOne(CRMXICity::class, 'bl_id', 'city');
    }

    public function hoas()
    {
        return $this->hasOne(CRMXIHoa::class, 'id', 'hoa');
    }
    public function creator()
    {
        return $this->belongsTo(SrsUser::class, 'created_by');
    }

    // Requirements for SRS 3.0

    public function CRMXIvehicles()
    {
        return $this->hasMany(CRXMIVehicle::class, 'crm_id', 'customer_id')->where('assoc_crm', 1);
    }

    public function CRMXIcategory()
    {
        return $this->belongsTo(CRMXICategory::class, 'category_id');
    }

    public function CRMXIsubCategory()
    {
        return $this->belongsTo(CRMXISubcat::class, 'sub_category_id');
    }
}
