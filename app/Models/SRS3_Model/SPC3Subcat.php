<?php

namespace App\Models\SRS3_Model;

use App\Models\CRMXI3_Model\CRMXICategory;
use App\Models\CRMXI3_Model\CRMXIHoa;
use App\Models\CRMXI3_Model\CRMXIHoaType;
use App\Models\CRMXI3_Model\CRMXISubcat;
use App\Models\SrsRequirement;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SPC3Subcat extends Model
{
    use HasFactory,SoftDeletes;

    protected $table = 'spc3_subcat';

    protected $fillable = [
        'name',
        'category_id',
        'sub_category_id',
        'hoa_id',
        'hoa_type_id',
        'status'
    ];

    public function category()
    {
         return $this->belongsTo(CRMXICategory::class, 'category_id', 'id');
    }

    public function subCat()
    {
         return $this->belongsTo(CRMXISubcat::class, 'sub_category_id', 'id');
    }

    public function hoa()
    {
         return $this->belongsTo(CRMXIHoa::class, 'hoa_id', 'id');
    }

    public function hoaType()
    {
         return $this->belongsTo(CRMXIHoaType::class, 'hoa_type_id', 'id');
    }

    public function requiredFiles()
    {
        return $this->belongsToMany(SRS3Requirement::class, 'srs3_subcat_requirement', 'sub_category_id', 'requirement_id');
    }
}
