<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\SrsAppointment;
use App\Models\SrsApptTimeslot;
use App\Models\SrsCalendarBlock;

class SrsApptTimeSlotController extends Controller
{
    private function isSunday($date)
    {
        return date('N', strtotime($date)) == 7;
    }

    private function isSaturday($date)
    {
        return date('N', strtotime($date)) == 6;
    }

    private function isBlocked($date)
    {
        // return SrsCalendarBlock::where('start', '<=', $date)
        //                         ->where('end', '>=', $date)
        //                         ->exists();

        // return SrsCalendarBlock::whereDate('start', $date)
        //                         ->orWhereDate('end', $date)
        //                         ->exists();

        return SrsCalendarBlock::where(function ($q) use ($date) {
                                    $q->whereDate('start', $date)
                                        ->orWhereDate('end', $date);
                                })
                                ->orWhere(function ($q) use ($date) {
                                    $q->where('start', '<=', $date)
                                        ->where('end', '>=', $date);
                                })
                                ->exists();
    }

    public function getAvailable(Request $request)
    {
        $request->validate([
            'date' => 'required|date',
            'time' => 'required|integer',
        ]);

        $appointmentDays = app('App\Http\Controllers\appointments\ApptConfigController')->getDaysAllowed();

        $requestDate = Carbon::createFromFormat('Y-m-d', $request->date);

        if (!in_array($requestDate->format('N'), $appointmentDays)) {
            return response()->json(['weekend' => "No Appointment on ". $requestDate->format('l')], 400);
        }
    

        $maxEntryPerTimeslot = app('App\Http\Controllers\appointments\ApptConfigController')->getCashierCount();


        // $slots = SrsAppointment::with('timeslot')->first();
        // $slots = SrsApptTimeSlot::with('appointments')->find(3);
        if ($this->isSunday($request->date)) {
            return response()->json(['weekend' => "No appointment on Sundays"], 400);
        }

        if ($this->isBlocked($request->date)) {
            $from = $request->date.' '.$request->time.':00:00';
            $to = $request->date.' '.$request->time.':59:00';
            $blocked = SrsCalendarBlock::where('start', '<=', $from)
                                        ->where('end', '>=', $to)
                                        ->first();

            if ($blocked) {

                return response()->json(['blocked' => $blocked->title, 'datetime' => Carbon::parse($blocked->start)->format('M d h:i A').' to '.Carbon::parse($blocked->end)->format('M d, Y h:i A')], 400);
            }
                     
            $slots = SrsApptTimeslot::withCount(['appointments' => function ($query) use ($request) {
                                        $query->where('date', $request->date);
                                    }])
                                    // ->whereHas('appointments', function ($query) use ($request) {
                                    //     $query->where('date', $request->date);
                                    // })
                                    ->having('appointments_count', '>=', $maxEntryPerTimeslot)
                                    ->pluck('id');

            $blockedTime = SrsCalendarBlock::whereBetween('start', [$from, $to])
                                            ->orWhereBetween('end', [$from, $to])
                                            ->first();
                                            
            if ($blockedTime) {
                $blockedSlots = SrsApptTimeslot::whereBetween('time', [Carbon::parse($blockedTime->start)->format('H:i:s'), Carbon::parse($blockedTime->end)->format('H:i:s')])->pluck('id');

                $slots = $slots->merge($blockedSlots);
            }

        } else {
            $slots = SrsApptTimeslot::withCount(['appointments' => function ($query) use ($request) {
                                            $query->where('date', $request->date);
                                    }])
                                    // ->whereHas('appointments', function ($query) use ($request) {
                                    //     $query->where('date', $request->date);
                                    // })
                                    ->having('appointments_count', '>=', $maxEntryPerTimeslot)
                                    ->pluck('id');
        }
        
        // if ($this->isSaturday($request->date)) {

        //     if ($request->time > 15) {
        //         return response()->json(['weekend' => "Appointments on Saturdays until 3 PM only"], 400);
        //     }

        //     $until3pmSlots = SrsApptTimeslot::where('time', '>', '15:00:00')->pluck('id');

        //     $slots = $slots->merge($until3pmSlots);
        // }

        $dailyApptTime = app('App\Http\Controllers\appointments\ApptConfigController')->getDailyTimeAllowed();

        $exceptedTimeSlots = SrsApptTimeslot::where('time', '<', $dailyApptTime[0])
                                            ->orWhere('time', '>', $dailyApptTime[1])
                                            ->pluck('id');

        $slots = $slots->merge($exceptedTimeSlots);

        
        $available = SrsApptTimeSlot::whereNotIn('id', $slots)
        ->whereTime('time', '>=', $request->time.':00')
        ->whereTime('time', '<', ($request->time + 1).':00')
        ->orderBy('time')
        ->select('time')
        ->get();

        if($available->count() > 0) {
            return $available;
        } else {
            return response()->json([
                'no_timeslots' => 'No available slot for this time. Please pick another timeslot.'
            ], 400);
        }
    }
}
