<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SrsApptReset extends Model
{
    use HasFactory;

    public function creator()
    {
        return $this->belongsTo(SrsUser::class, 'action_by');
    }
}
