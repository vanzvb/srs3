<?php

namespace App\Models;

use App\Models\SRS3_Model\SrsRequest;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SrsAppointment extends Model
{
	use SoftDeletes;

    public function timeslot()
    {
        return $this->belongsTo(SrsApptTimeslot::class, 'srs_appt_timeslot_id');
    }

    public function request()
    {
        return $this->belongsTo(SrsRequest::class, 'srs_request_id');
    }

    public function getDateAttribute()
    {
        //return Carbon::createFromFormat('Y-m-d', $this->attributes['date'])->format('M d, Y');
        return Carbon::createFromFormat('Y-m-d', $this->attributes['date']);
    }  
}
