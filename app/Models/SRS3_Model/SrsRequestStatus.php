<?php

namespace App\Models\SRS3_Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SrsRequestStatus extends Model
{
    use HasFactory;

    public function requests()
    {
        return $this->belongsToMany(SrsRequest::class, 'srs_request_status_logs', 'status_id', 'request_id')
                    ->withPivot('action_by')
                    ->withTimestamps();
    }
}
