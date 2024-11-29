<?php

namespace App\Http\Controllers\appointments;

use Illuminate\Http\Request;
use App\Models\SrsAppointment;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
use App\Models\appointments\ApptConfig;

class ApptConfigController extends Controller
{
    public function getCashierCount()
    {
        return Cache::remember('appt_config_cashier_count', 1800, function () {
            $apptConfig = ApptConfig::where('name', 'cashier_count')->first();

            return $apptConfig->value;
        });
    }

    public function getDailyTimeAllowed()
    {
        return Cache::remember('appt_config_daily_time_allowed', 1800, function () {
            $apptConfig = ApptConfig::where('name', 'daily_time_allowed')->first();

            return explode(' - ', $apptConfig->value);
        });
    }

    public function getDaysAllowed()
    {
        return Cache::remember('appt_config_days_allowed', 1800, function () {
            $apptConfig = ApptConfig::where('name', 'days_allowed')->first();

            return unserialize($apptConfig->value);
        });
    }

    public function index()
    {
        $this->authorize('accessConfig', SrsAppointment::class);

        $apptConfigs = ApptConfig::all()->keyBy('name');
        $apptConfigs['daily_time_allowed']->value = explode(' - ', $apptConfigs['daily_time_allowed']->value);
        $apptConfigs['days_allowed']->value = unserialize($apptConfigs['days_allowed']->value);

        return view('srs.admin.appt_config', compact('apptConfigs'));
    }

    public function updateCashierCount(Request $request)
    {
        $data = $request->validate([
            'cashier_count' => 'required|integer'
        ]);

        $apptConfig = ApptConfig::where('name', 'cashier_count')->first();
        $apptConfig->value = $data['cashier_count'];
        $apptConfig->save();

        return back();
    }

    public function update(Request $request)
    {
        $request->validate([
            'daily_time_from' => 'required',
            'daily_time_to' => 'required',
        ]);

        if (count(explode(':', $request->daily_time_from)) == 2) {
            $request->merge(['daily_time_from' => $request->daily_time_from.':00']);
        }

        if (count(explode(':', $request->daily_time_to)) == 2) {
            $request->merge(['daily_time_to' => $request->daily_time_to.':00']);
        }

        $data = $request->validate([
            'daily_time_from' => 'required|date_format:H:i:s',
            'daily_time_to' => 'required|date_format:H:i:s',
            'cashier_count' => 'required|integer',
            'chck_monday' => 'sometimes|accepted',
            'chck_tuesday' => 'sometimes|accepted',
            'chck_wednesday' => 'sometimes|accepted',
            'chck_thursday' => 'sometimes|accepted',
            'chck_friday' => 'sometimes|accepted',
            'chck_saturday' => 'sometimes|accepted',
            'chck_sunday' => 'sometimes|accepted',
        ]);

        $daysAllowed = [];
        
        if (isset($data['chck_monday'])) {
            $daysAllowed[] = 1;
        }

        if (isset($data['chck_tuesday'])) {
            $daysAllowed[] = 2;
        }

        if (isset($data['chck_wednesday'])) {
            $daysAllowed[] = 3;
        }

        if (isset($data['chck_thursday'])) {
            $daysAllowed[] = 4;
        }

        if (isset($data['chck_friday'])) {
            $daysAllowed[] = 5;
        }

        if (isset($data['chck_saturday'])) {
            $daysAllowed[] = 6;
        }

        if (isset($data['chck_sunday'])) {
            $daysAllowed[] = 7;
        }
        
        // ApptConfig::where('name', 'daily_time_allowed')->update(['value' => $data['daily_time_from'] . ' - ' . $data['daily_time_to']]);
        // ApptConfig::where('name', 'cashier_count')->update(['value' => $data['cashier_count']]);
        // ApptConfig::where('name', 'days_allowed')->update(['value' => serialize($daysAllowed)]);

        $serializedDaysAllowed = serialize($daysAllowed);

        $dailyTimeAllowed = ApptConfig::where('name', 'daily_time_allowed')->first();
        $cashierCount = ApptConfig::where('name', 'cashier_count')->first();
        $daysAllowedConfig = ApptConfig::where('name', 'days_allowed')->first();


        $old = json_encode([
            'daily_time_allowed' => $dailyTimeAllowed->value,
            'cashier_count' => $cashierCount->value,
            'days_allowed' => unserialize($daysAllowedConfig->value),
        ]);

        $new = json_encode([
            'daily_time_allowed' => $data['daily_time_from'] . ' - ' . $data['daily_time_to'],
            'cashier_count' => $data['cashier_count'],
            'days_allowed' => $daysAllowed
        ]);

        $dailyTimeAllowed->value = $data['daily_time_from'] . ' - ' . $data['daily_time_to'];
        $cashierCount->value = $data['cashier_count'];
        $daysAllowedConfig->value = $serializedDaysAllowed;

        $dailyTimeAllowed->save();
        $cashierCount->save();
        $daysAllowedConfig->save();

        DB::table('log_appt_conf_hist')
            ->insert([
                'action_by' => auth()->user()->name,
                'action' => 'Updated Appt Config, FROM ' . $old . ' TO '. $new,
                'ip_address' => request()->ip(),
                'created_at' => now()
            ]);

        return back()->with('updateSuccess', 'Appointment Configuration Updated Successfully!');
    }
}
