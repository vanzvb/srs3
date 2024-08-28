<?php

namespace App\Http\Controllers\Auth;

use Exception;
use App\Models\SrsUser;
use Illuminate\Http\Request;
use App\Mail\SendUnlockPasscode;
use Illuminate\Support\Facades\URL;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;

class UnlockAccountController extends Controller
{
    public function index()
    {
        return view('auth.unlock_account', ['unlock' => false]);
    }

    public function submit(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:srs_users,email',
        ]);

        $user = SrsUser::where('email', $request->email)->first();

        if ($user->is_logged_in == 0) {
            return redirect()->back()->with('error', 'Your account is already unlocked.');
        }

        $passcode = $this->generatePasscode($user);
        $url = URL::temporarySignedRoute(
            'unlock-account.unlock', now()->addHours(1), ['email' => $user->email]
        );

        Mail::mailer('smtp_2')
            ->to($user->email)
            ->send(new SendUnlockPasscode($user, $passcode, $url));

        return redirect()->back()->with('success', 'A passcode has been sent to your email.');
    }

    public function unlock(Request $request)
    {
        if (!$request->hasValidSignature()) {
            abort(401);
        }

        $user = SrsUser::where('email', $request->email)->first();

        if ($user->passcode == null) {
            abort(401, 'Your account is already unlocked.');
            // return redirect()->route('login')->with('error', 'Your account is already unlocked.');
        }

        $url = URL::temporarySignedRoute(
            'unlock-account.unlock-account', now()->addHours(1), ['email' => $request->email]
        );

        return view('auth.unlock_account', ['unlock' => true, 'url' => $url]);
    }

    public function unlockAccount(Request $request)
    {
        if (!$request->hasValidSignature()) {
            abort(401);
        }

        $request->validate([
            'passcode' => 'required|digits:6',
        ]);

        try {
            $user = SrsUser::where('email', $request->email)->firstOrFail();

            if ($user->passcode !== $request->passcode) {
                return redirect()->back()->with('error', 'Invalid passcode.');
            }

            // check if generated_at is more than 60 minutes
            if ($user->generated_at->diffInMinutes(now()) > 60) {
                return redirect()->back()->with('error', 'Passcode has expired.');
            }

            $user->update([
                'is_logged_in' => 0,
                'passcode' => null,
                'generated_at' => null,
            ]);
        } catch (Exception $e) {
            return redirect()->route('login')->with('error', 'Your account does not exist.');
        }

        return redirect()->route('login')->with('success', 'Your account has been unlocked.');
    }

    private function generatePasscode(SrsUser $user)
    {   
        $passcode = rand(100000, 999999);

        $user->update([
            'passcode' => $passcode,
            'generated_at' => now(),
        ]);

        return $passcode;
    }
}
