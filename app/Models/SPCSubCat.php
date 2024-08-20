<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SPCSubCat extends Model
{
    use HasFactory;

    protected $table = 'spc_subcat';

    protected $fillable = [
        'name',
        'category_id',
        'status'
    ];

    public function category()
    {
        return $this->belongsTo(SPCCategory::class, 'category_id', 'id');
    }

    public function requiredFiles()
    {
        return $this->belongsToMany(SrsRequirement::class, 'srs_subcat_requirement', 'sub_category_id', 'requirement_id');
    }
}
