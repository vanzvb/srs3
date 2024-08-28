<?php

namespace App\Http\Controllers\Auth;

use App\Models\SrsUser;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use App\Models\LogHoaHist;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    // protected $redirectTo = RouteServiceProvider::HOME;
    protected $redirectTo = '/dashboard';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function login(Request $request)
    {
        
        $this->validateLogin($request);
        
        $user = SrsUser::where('email', $request->email)->first();
        
        // If the class is using the ThrottlesLogins trait, we can automatically throttle
        // the login attempts for this application. We'll key this by the username and
        // the IP address of the client making these requests into this application.
        if (method_exists($this, 'hasTooManyLoginAttempts') &&
            $this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);

            return $this->sendLockoutResponse($request);
        }
        
        if (Auth::validate([
            'email' => $request->email,
            'password' => $request->password
        ])) {
            if($user->status != 1) {
                return back()->withErrors(['email' => 'Account does not have a valid license.']);
            }

            // if this return false, user is considered offline
            // if(Cache::has('user-is-online-' . $user->id)) {
            //     return back()->withErrors(['email' => 'Account is currently in use. Please try again later.']);
            // }

            // if($user->is_logged_in == 1) {
            //     return back()->withErrors(['email' => "Account $user->name is currently in use. Please try again later."]);
            // }

            if(Cache::has('user-is-online-' . $user->id) && $user->is_logged_in == 1) {
                return back()->withErrors(['email' => "Account $user->name is currently in use. Please try again later."]);
            }
        }
        
        if ($this->attemptLogin($request)) {
            
            if ($request->hasSession()) {
                $request->session()->put('auth.password_confirmed_at', time());
            }
            
            if(auth()->user()->role_id == 7) {
                LogHoaHist::create([
                    'action_by' => Auth::user()->name,
                    'action' => 'HOA Logged In, email: ' . $request->email . ' name: ' . Auth::user()->name,
                    'ip_address' => $request->ip()
                ]);
            }   

            $exceptUsers = [
                'itqa@atomitsoln.com',
                'srsadmin@atomitsoln.com',
                'lito.tampis@atomitsoln.com',
                'mike.sujeco@atomitsoln.com',
                // 'test@test.com'
            ];

            // check if user is not in the exceptUsers
            if(!in_array(Auth::user()->email, $exceptUsers)){
                $ipUrl = 'http://ip-api.com/json/' . $request->ip();
                $ipLocation = json_decode(file_get_contents($ipUrl), true);

                if($ipLocation['status'] == 'success') {
                    $ipLocation = $ipLocation['city'] . ', ' . $ipLocation['regionName'] . ', ' . $ipLocation['country'];
                } else {
                    $ipLocation = 'Unknown';
                }

                $user->update([
                    'is_logged_in' => 1,
                    'location' => $ipLocation,
                    'login_at' => now(),
                    'ip_address' => $request->ip(),
                ]);
            }

            // $expiresAt = now()->addMinutes(5);
            // Cache::put('user-is-online-' . Auth::user()->id, true, $expiresAt);

            Auth::logoutOtherDevices($request->password);

            return $this->sendLoginResponse($request);
        }
        
        // If the login attempt was unsuccessful we will increment the number of attempts
        // to login and redirect the user back to the login form. Of course, when this
        // user surpasses their maximum number of attempts they will get locked out.
        $this->incrementLoginAttempts($request);

        return $this->sendFailedLoginResponse($request);
    }
}
