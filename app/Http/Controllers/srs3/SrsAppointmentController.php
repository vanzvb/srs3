<?php

namespace App\Http\Controllers\srs3;

use App\Http\Controllers\Controller;
use App\Jobs\SendAppointmentSetNotification;
use App\Mail\RequestApproved;
use App\Models\SRS3_Model\SrsRequest;
use App\Models\SrsAppointment;
use App\Models\SrsApptResend;
use App\Models\SrsApptReset;
use App\Models\SrsApptTimeslot;
use App\Models\SrsCalendarBlock;
use Carbon\Carbon;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;

class SrsAppointmentController extends Controller
{
    private $colors = [
        '#3ec2a1',
        '#1c8c2f',
        '#d8bb37',
        '#d83f37',
        '#d8375a',
        'purple',
        'orange',
        'chocolate',
        'darkblue',
        'darkseagreen',
        'fuchsia',
        'lightcoral'
    ];

    private function getRandomColor($lastColor)
    {
        $color = '';

        do {
            $color = $this->colors[rand(0, (count($this->colors) - 1))];
        } while($color == $lastColor);

        return $color;
    }

    private function insertApptReset($appointmentID)
    {
        $apptReset = new SrsApptReset();
        $apptReset->appointment_id = $appointmentID;
        $apptReset->action_by = auth()->id();
        $apptReset->ip_address = request()->ip();

        return $apptReset;
    }

    private function isSaturday($date)
    {
        return date('N', strtotime($date)) == 6;
    }

    public function index()
    {
        $this->authorize('access', SrsAppointment::class);

        // L79 - L89 replaced by list()
        // $appointments = SrsAppointment::with(['request' => function ($query) {
        //                                     $query->select('request_id', 'first_name', 'last_name', 'middle_name');
        //                                 }, 'timeslot'])
        //                                 ->whereHas('request', function ($query) {
        //                                     $query->where('status', 3)
        //                                           ->whereDoesntHave('statuses', function ($q) {
        //                                                 $q->where('status_id', 4);
        //                                             })
        //                                           ->where('created_at', '>=', now()->subMonths(3));
        //                                 })
        //                                 ->get();
        
        return view('srs.admin.appointments');
    }

    // Released on 02-21-23 [RC], purpose - get list of appointments per date vs previous, similiar function index(), L74
    public function list(Request $request)
    {
        $srsAppointments = SrsAppointment::with(['request' => function ($query) {
                                $query->select('request_id', 'first_name', 'last_name', 'middle_name', 'status');
                            }, 'timeslot'])
                            ->where('date', '>=', $request->start)
                            ->where('date', '<=', $request->end)
                            ->orderBy('srs_appt_timeslot_id')
                            ->get();
            
        $events = [];
        $lastDateTime = '';
        $lastColor = '';
        
        foreach ($srsAppointments as $srsAppointment) {
            $apptDateTime = $srsAppointment->date->format("M d, Y") . ' ' . $srsAppointment->timeslot->time->format("h:i A");
            $event = [
                'title' => $srsAppointment->request->first_name. ' ' .$srsAppointment->request->last_name,
                'start' => $srsAppointment->date->format('Y-m-d') . ' ' . $srsAppointment->timeslot->time->format('H:i:s'),
                'end' => $srsAppointment->date->format('Y-m-d') . ' ' . $srsAppointment->timeslot->time->addMinutes(5)->format('H:i:s'),
                'extendedProps' => [
                    'srs' => '<a href="/srs/i/request/' . $srsAppointment->request->request_id . '" target="_blank">'. $srsAppointment->request->request_id .'</a>',
                    'date' => $srsAppointment->date->format("M d, Y"),
                    'time' => $srsAppointment->timeslot->time->format("h:i A"),
                    'name' => $srsAppointment->request->last_name . ', ' . $srsAppointment->request->first_name. ' ' .$srsAppointment->request->middle_name,
                ],
            ];

            if ($lastDateTime == $apptDateTime) {
                $lastColor = $this->getRandomColor($lastColor);
                $event['backgroundColor'] = $lastColor;
            }

            // checks if the appointment is already processed
            if($srsAppointment->request->status == 4) {
                $event['backgroundColor'] = '#808080'; // gray
            }

            $events[] = $event;
            $lastDateTime = $apptDateTime;
        }

        return response()->json($events);
    }

    public function create(Request $request)
    {
        
        if (!$request->hasValidSignature() || !$request->key) {
            return redirect('home');
        }
            
        try {
            $srn = Crypt::decrypt($request->key);
        } catch(DecryptException $e) {

            return redirect('home');
        }
        
        $request->merge(['srn' => $srn]);

        $data = $request->validate([
            'expires' => 'required',
            'key' => 'required|string',
            'signature' => 'required|string',
            'srn' => 'required|string|exists:srs3_requests,request_id'
        ]);

        $srsRequest = SrsRequest::where('request_id', $data['srn'])
                                ->where('status', 2)
                                ->whereDoesntHave('appointment')
                                ->first();
        
        if (!$srsRequest) {
            return redirect('/');
        }


        return view('srs3.appointment.create', ['srsNumber' => $srsRequest->request_id, 'srn' => Crypt::encrypt($srsRequest->request_id)]);
    }

    public function store(Request $request)
    {
        dd('v3');
        try {
            $srn = Crypt::decrypt($request->srn);
        } catch(DecryptException $e) {
            abort(403);
            // return redirect('/');
        }

        $request->merge(['req_id' => $srn]);
        
        $data = $request->validate([
            'date' => 'required|date',
            'timeslot' => 'required|date_format:h:i A',
            'srn' => 'required|string',
            'req_id' => 'required|exists:srs_requests,request_id'
        ]);

        if (date('N', strtotime($data['date'])) == 7) {
            abort(400);
        }

        $dateTime = Carbon::createFromFormat('Y-m-d h:i A', $data['date'].' '.$data['timeslot']);
        
        $appointmentDays = app('App\Http\Controllers\appointments\ApptConfigController')->getDaysAllowed();

        if (!in_array($dateTime->format('N'), $appointmentDays)) {
            return response()->json(['status' => 0, 'msg' => "No Appointment on ". $dateTime->format('l')]);
        }

        if (SrsCalendarBlock::where('start', '<=', $dateTime->format('Y-m-d H:i:s'))
                            ->where('end', '>=', $dateTime->format('Y-m-d H:i:s'))
                            ->exists()) {
            abort(400);
        }

        // $timeslot = Carbon::createFromFormat('h:i A', $data['timeslot'])->format('H:i:s');
        //$apptTimeslot = SrsApptTimeslot::where('time', $timeslot)->select('id', 'time')->first();
        $apptTimeslot = SrsApptTimeslot::withCount(['appointments' => function ($q) use ($data) {
                                            $q->where('date', $data['date']);
                                        }])
                                        ->where('time', $dateTime->format('H:i:s'))
                                        // ->when($this->isSaturday($data['date']), function ($q) {
                                        //     $q->where('time', '<=', '15:00:00');
                                        // })
                                        // ->having('appointments_count', '<', 10)
                                        //->select('id', 'time', 'appointments_count')
                                        ->first();
        
        if (!$apptTimeslot) {
            abort(400);
        }

        // if ($apptTimeslot->appointments_count >= 3) {

        //     return response()->json(['status' => 0, 'msg' => 'Time Slot Full']);
        // }
        
        $maxEntryPerTimeslot = app('App\Http\Controllers\appointments\ApptConfigController')->getCashierCount();


        if ($apptTimeslot->appointments_count >= $maxEntryPerTimeslot) {

            return response()->json(['status' => 0, 'msg' => 'Time Slot Full']);
        }

        $dailyApptTime = app('App\Http\Controllers\appointments\ApptConfigController')->getDailyTimeAllowed();


        if ($apptTimeslot->time->format('H:i:s') < $dailyApptTime[0] || $apptTimeslot->time->format('H:i:s') > $dailyApptTime[1]) {
            
            return response()->json(['status' => 0, 'msg' => 'Time Slot Unavailable']);
        }
        
        if (SrsAppointment::where('srs_request_id', $data['req_id'])
                            ->where('date', $data['date'])
                            ->exists()) {
            abort(400);
        }

        $appointment = new SrsAppointment();
        $appointment->srs_request_id = $data['req_id'];
        $appointment->date = $data['date'];

        $apptTimeslot->appointments()->save($appointment);
        $srsRequest = SrsRequest::where('request_id', $data['req_id'])->first();
        $srsRequest->statuses()->attach(3, ['action_by' => $srsRequest->first_name.' '.$srsRequest->last_name]);
        $srsRequest->status = 3;
        $srsRequest->save();

        dispatch(new SendAppointmentSetNotification($srsRequest, $srsRequest->email, $appointment->date->format('M d, Y'), $apptTimeslot->time->format('h:i A')))->delay(12);

        return response()->json(['status' => 1, 'msg' => 'Appointment Set!', 'date' => $appointment->date->format('M d, Y'), 'time' => $apptTimeslot->time->format('h:i A')]);
    }

    public function getAppointments(Request $request)
    {
        if (!$request->ajax()) {
            abort(404);
        }

        $appointments = SrsAppointment::with(['request' => function ($query) {
                                            $query->select('request_id');
                                        }, 'timeslot'])->get();


        return $appointments;
    }

    public function reset(Request $request)
    {
        $this->authorize('reset', SrsAppointment::class);

        $data = $request->validate([
            'request_id' => 'required|string|exists:srs_requests,request_id'
        ]);

        $srsRequest = SrsRequest::with('appointment.timeslot')
                                ->where('request_id', $data['request_id'])
                                ->select('request_id', 'email')
                                ->first();

        if (!$srsRequest) {

            return response()->json(['status' => 0, 'error_msg' => 'SRS View: Reset Appointment: L197 Error <br><br> Please try again']);
            // return redirect('/');
        }

        if (!$srsRequest->appointment) {
            $srsRequest->load(['appointmentResets.creator' => function ($q) {
                $q->select('id', 'name');
            }]);

            try {
                $apptReset = $srsRequest->appointmentResets->sortByDesc('created_at')->first();
               
                return response()->json(['status' => 2, 'srs' => $srsRequest->request_id, 'msg' => 'Reset '.$apptReset->created_at->diffForHumans() . ' by ' . $apptReset->creator->name]);
            } catch (\Exception $e) {

                return response()->json(['status' => 0, 'error_msg' => 'SRS View: Reset Appointment: L205 Error']);
            }
        }
        
        $dateTime = $srsRequest->appointment->date->format('M d, Y') . ' ' . $srsRequest->appointment->timeslot->time->format('h:i A');

        
        
        try {
            $srsRequest->appointmentResets()->save($this->insertApptReset($srsRequest->appointment->id));

            $srsRequest->appointment()->delete();
            $srsRequest->status = 2;
            $srsRequest->statuses()->detach(3);
            $srsRequest->save();

            $srn = Crypt::encrypt($srsRequest->request_id);
            $url = URL::temporarySignedRoute('request.appointment', now()->addDays(3), ['key' => $srn]);


            dispatch(new \App\Jobs\AppointmentResetNotificationJob($srsRequest, $srsRequest->email, $url, $dateTime))->delay(now()->addMinute());
    
        } catch(\Exception $e) {

            return response()->json(['status' => 0, 'error_msg' => 'SRS View: Reset Appointment: L230 Error']);
        }


        return response()->json(['status' => 1, 'srs' => $srsRequest->request_id]);
    }

    public function resend(Request $request)
    {
        $this->authorize('resend', SrsAppointment::class);

        $data = $request->validate([
            'request_id' => 'required|string|exists:srs_requests,request_id'
        ]);

        $srsRequest = SrsRequest::where('request_id', $data['request_id'])
                                ->select('request_id', 'email')
                                ->first();

        if (!$srsRequest) {

            return response()->json(['status' => 0, 'error_msg' => 'SRS View: Resend Approval Email: L262 Error <br><br> Please try again']);
        }
        

        // $srsRequest->load(['appointmentResends' => function ($q) {
        //     $q->latest();
        // }])->first();

        $srsRequest->load(['latestApptResend.creator' => function ($q) {
            $q->select('id', 'name');
        }]);

        
        if ($srsRequest->latestApptResend) {
            if ($srsRequest->latestApptResend->created_at->isSameDay(now())) {

                return response()->json(['status' => 2, 'srs' => $srsRequest->request_id, 'msg' => 'Resent '.$srsRequest->latestApptResend->created_at->diffForHumans() . ' by ' . $srsRequest->latestApptResend->creator->name]);
            }
        }
        

        try {
            $apptResend = new SrsApptResend();
            $apptResend->action_by = auth()->id();
            $apptResend->ip_address = request()->ip();

            $srsRequest->appointmentResends()->save($apptResend);

            $srn = Crypt::encrypt($srsRequest->request_id);
            $url = URL::temporarySignedRoute('request.appointment', now()->addDays(3), ['key' => $srn]);
            
            dispatch(new \App\Jobs\SendApprovedNotificationJob($srsRequest, $srsRequest->email, $url, 'resend'))->delay(now()->addSeconds(20));

        } catch (\Exception $e) {

            return response()->json(['status' => 0, 'error_msg' => 'SRS View: Resend Approval Email: L273 Error']);
        }

        return response()->json(['status' => 1, 'srs' => $srsRequest->request_id]);
    }

    public function apptResendBulk(Request $request) {
        $auth_emails = [
            'test@test.com',
            'itqa@atomitsoln.com',
            'lito.tampis@atomitsoln.com',
            'srsadmin@atomitsoln.com'
        ];

        if(!in_array(auth()->user()->email, $auth_emails)) {
            abort(404);
        }

        try {
            $currentDate = date('Y-m-d');

			$srs_numbers = SrsRequest::query()
			    ->leftJoin('srs_appointments', 'srs_requests.request_id', '=', 'srs_appointments.srs_request_id')
			    ->whereBetween('srs_requests.created_at', ['2024-01-01', '2024-03-31'])
			    ->where('srs_requests.status', 2)
			    ->where('appt_resend', null)
			    ->whereNotIn('srs_requests.email', [
			        'test@test.com',
			        'ivan.deposoy@atomitsoln.com',
			        'srsadmin@atomitsoln.com',
			        'itqa@atomitsoln.com'
			    ])
			    ->where('srs_requests.category_id', 1)
			    ->where('srs_requests.customer_id', null)
			    ->whereNull('srs_appointments.srs_request_id')
			    ->select('srs_requests.request_id', 'srs_requests.email')
			    ->orderBy('srs_requests.created_at', 'desc')
			    ->limit($request->count);

            //dd($srs_numbers->count());
        } catch (\Exception $e) {
            dd($e);
        }
 
        $sentEmails = [];

        foreach($srs_numbers->get() as $number) {
            $sent = $this->apptSendMail($number->request_id, $request->email, $request->pw);

            $sentEmails[] = $sent;

            sleep(12);
        }

        $updated_srs_numbers = SrsRequest::query()
		    ->leftJoin('srs_appointments', 'srs_requests.request_id', '=', 'srs_appointments.srs_request_id')
		    ->whereBetween('srs_requests.created_at', ['2024-01-01', '2024-03-31'])
		    ->where('srs_requests.status', 2)
		    ->where('appt_resend', null)
		    ->whereNotIn('srs_requests.email', [
		        'test@test.com',
		        'ivan.deposoy@atomitsoln.com',
		        'srsadmin@atomitsoln.com',
		        'itqa@atomitsoln.com'
		    ])
		    ->where('srs_requests.category_id', 1)
		    ->where('srs_requests.customer_id', null)
		    ->whereNull('srs_appointments.srs_request_id') // Filtering for records without appointments
		    ->select('srs_requests.request_id', 'srs_requests.email')
		    ->orderBy('srs_requests.created_at', 'desc')
		    ->limit($request->count);

        $srs_count = $updated_srs_numbers->count();

        dd($sentEmails, $srs_count);
    }

    public function apptSendMail($request_id, $senderEmail, $senderPW) {
        $srsRequest = SrsRequest::where('request_id', $request_id)
        ->first();

        $sentToEmails = [];

        // Get the current date in 'Y-m-d' format (year-month-day)
        $currentDate = date('Y-m-d');

        $srsRequestDate = substr($srsRequest->appt_resend_at, 0, 10); // Assuming the date format is 'Y-m-d H:i:s'

        // Compare the dates
        if ($srsRequestDate === $currentDate) {
            // Dates match (same year, month, and day)
            $sentToEmails[] = 'Attempting to re-send appointment email to ' . $srsRequest->email . '.' . ' SRS # ' . $srsRequest->request_id;

            return $sentToEmails;
        }

        $srsRequest->load(['latestApptResend.creator' => function ($q) {
            $q->select('id', 'name');
        }]);

        if ($srsRequest->latestApptResend && $srsRequest->latestApptResend->created_at->isSameDay(now())) {
            $sentToEmails[] = 'Attempting to re-send appointment email to ' . $srsRequest->email . '.' . ' SRS # ' . $srsRequest->request_id;

            return $sentToEmails;
        }
        
        // dd('ok');

        try {
            $mailFrom = "bffhai@zn.donotreply.notification.znergee.com";

            $apptResend = new SrsApptResend();
            $apptResend->action_by = auth()->id();
            $apptResend->ip_address = request()->ip();

            $srsRequest->appointmentResends()->save($apptResend);

            $srn = Crypt::encrypt($srsRequest->request_id);
            $url = URL::temporarySignedRoute('request.appointment', now()->addDays(3), ['key' => $srn]);

            $sentToEmails[] = 'Attempting to re-send appointment email to ' . $srsRequest->email . '.' . ' SRS # ' . $srsRequest->request_id;

            Mail::mailer('smtp_2')->to($srsRequest->email)->send(new RequestApproved($srsRequest, $url, $mailFrom));

            $srsRequest->update([
                'appt_resend' => 1,
                'appt_resend_at' => now()
            ]);
            
            return $sentToEmails;
        } catch (\Exception $e) {
            dd($e);
        }
    }
}
