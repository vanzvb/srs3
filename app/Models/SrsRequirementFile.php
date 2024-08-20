<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SrsRequirementFile extends Model
{
    protected $hidden = [
        'name',
    ];

    protected $casts = [
        'item1' => 'encrypted'
    ];

    public function requirement()
    {
        return $this->belongsTo(SrsRequirement::class, 'srs_requirement_id');
    }
}
