<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\SPCSubCat;

class SrsRequirement extends Model
{
    public function subCategories()
    {
        return $this->belongsToMany(SPCSubCat::class, 'srs_subcat_requirement', 'requirement_id', 'sub_category_id');
    }
}
