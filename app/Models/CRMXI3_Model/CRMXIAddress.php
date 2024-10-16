<?php

namespace App\Models\CRMXI3_Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\CRMXI3_Model\CRMXIMain;

class CRMXIAddress extends Model
{
    use HasFactory;

    protected $table = 'crmxi3_address';


    public function crmxiAccount()
    {
        return $this->belongsTo(CRMXIMain::class, 'account_id');
    }

    public function CRMXIcategory()
    {
        return $this->belongsTo(CRMXICategory::class, 'category_id');
    }

    public function CRMXIsubCategory()
    {
        return $this->belongsTo(CRMXISubcat::class, 'sub_category_id');
    }

    public function CRMXIhoa()
    {
        return $this->belongsTo(CRMXIHoa::class, 'hoa');
    }
}
