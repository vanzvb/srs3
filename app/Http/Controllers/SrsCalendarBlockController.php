<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\SrsCalendarBlock;
use Illuminate\Support\Facades\Auth;

class SrsCalendarBlockController extends Controller
{
    public function index()
    {
        $events = SrsCalendarBlock::orderBy('start', 'DESC')->get();

        return view('srs.calendar_blocker.index', compact('events'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string',
            'start' => 'required|date',
            'end' => 'required|date',
            'time_start' => 'required|date_format:H:i',
            'time_end' => 'required|date_format:H:i',
        ]);


        // $start_date = $data['start'];
        // $start_time = $data['time_start'];

        // $end_date = $data['end'];
        // $end_time = $request['time_end'];

        // $combinedDT = date('Y-m-d H:i:s', strtotime("$start_date $start_time"));
        // $combinedDT2 = date('Y-m-d H:i:s', strtotime("$end_date $end_time"));


        // $insertArr = [
        //     'title' => $request->title,
        //     'start' => $combinedDT,
        //     'end' =>  $combinedDT2,
        //     'time_start' => $request->time_start,
        //     'time_end' => $request->time_end
        // ];
        
        $calendarBlock = new SrsCalendarBlock();
        $calendarBlock->title = $data['title'];
        $calendarBlock->start = $data['start'].' '.$data['time_start'];
        $calendarBlock->end = $data['end'].' '.$data['time_end'];
        $calendarBlock->time_start = $data['time_start'];
        $calendarBlock->time_end = $data['time_end'];
        $calendarBlock->created_by = Auth::user()->name;
        $calendarBlock->save();

        return back()
                ->withInput()
                ->with('success', 'Successfully Added');

    }

    public function edit($id)
    {
        $event = SrsCalendarBlock::find($id);
        $event->setAttribute('formattedTimeStart', Carbon::parse($event->time_start)->format('H:i'));
        $event->setAttribute('formattedTimeEnd', Carbon::parse($event->time_end)->format('H:i'));
        
        return response()->json($event);
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'id' => 'required|exists:srs_calendar_blocks,id',
            'event_name' => 'required|string',
            'start' => 'required|date',
            'end' => 'required|date',
            'time_start' => 'required|date_format:H:i',
            'time_end' => 'required|date_format:H:i',
        ]);


        // $start_date = $request->start;
        // $start_time = $request->time_start;
        // $combinedDT = date('Y-m-d H:i:s', strtotime("$start_date $start_time"));

        // $end_date = $request->end;
        // $end_time = $request->time_end;

        // $combinedDT2 = date('Y-m-d H:i:s', strtotime("$end_date $end_time"));

        $event = SrsCalendarBlock::find($request->id);
        $event->title = $data['event_name'];
        $event->start = $data['start'].' '.$data['time_start'];;
        $event->end = $data['end'].' '.$data['time_end'];
        $event->time_start = $data['time_start'];
        $event->time_end = $data['time_end'];
        $event->save();

        return back()
                ->withInput()
                ->with('success', 'Successfully Update');
    }

    public function destroy(Request $request)
    {
        $delete = SrsCalendarBlock::find($request->delete_id);
        $delete->delete();

        return redirect('/srs/calendar-blocker')->with('success', 'Deleted');
    }
}
