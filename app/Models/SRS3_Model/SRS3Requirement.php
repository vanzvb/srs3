<?php

namespace App\Models\SRS3_Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SRS3Requirement extends Model
{
    use HasFactory;

    protected $table = 'srs3_requirements';

    protected $fillable = [
        'sub_category_id',
        'requirement_id',
    ];

    public function subCategories()
    {
        return $this->belongsToMany(SPC3Subcat::class, 'srs3_subcat_requirement', 'requirement_id', 'sub_category_id');
    }

}
