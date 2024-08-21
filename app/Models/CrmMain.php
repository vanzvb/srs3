<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\SPCSubCat;
use App\Models\SPCCategory;

class CrmMain extends Model
{
    use HasFactory;
    protected $primaryKey = 'crm_id';

    public function requests()
    {
        return $this->hasMany(SrsRequest::class, 'customer_id', 'customer_id');
    }

    public function creator()
    {
        return $this->belongsTo(SrsUser::class, 'created_by');
    }

    public function vehicles()
    {
        // return $this->hasMany(CrmVehicle::class, 'crm_id', 'customer_id');
        return $this->hasMany(CrmVehicle::class, 'crm_id', 'customer_id')->where('assoc_crm', 1);
    }

    public function redTags()
    {
        return $this->hasMany(CrmRedtag::class, 'crm_id');
    }

    public function category()
    {
        return $this->belongsTo(SrsCategory::class);
    }

    public function subCategory()
    {
        return $this->belongsTo(SrsSubCategory::class);
    }

    public function spc_category()
    {
        return $this->belongsTo(SPCCategory::class, 'category_id', 'id');
    }

    public function spc_subcat()
    {
        return $this->belongsTo(SPCSubCat::class, 'sub_category_id', 'id');
    }
}
