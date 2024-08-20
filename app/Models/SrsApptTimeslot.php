<?php

namespace App\Models;

use Carbon\Carbon;
use App\Models\SrsAppointment;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SrsApptTimeslot extends Model
{
    protected $appends = ['formattedTime'];

    public function getTimeAttribute()
    {
        return Carbon::createFromFormat('H:i:s', $this->attributes['time']);
        //return Carbon::createFromFormat('H:i:s', $this->attributes['time'])->format('h:i A');
    }  

    public function appointments()
    {
        return $this->hasMany(SrsAppointment::class);
    }

    public function getFormattedTimeAttribute()
    {
         return $this->time->format('h:i A');
    }

}
