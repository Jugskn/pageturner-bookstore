<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Notifications\TwoFactorCodeNotification;
use App\Notifications\TwoFactorToggledNotification;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class TwoFactorController extends Controller
{
    public function challenge(): View|RedirectResponse
    {
        if (! Auth::check() || ! Auth::user()->two_factor_enabled) {
            return redirect()->route('home');
        }

        if (session('two_factor_verified')) {
            return redirect()->intended(route('dashboard'));
        }

        return view('auth.two-factor-challenge');
    }

    public function verify(Request $request): RedirectResponse
    {
        $request->validate([
            'code' => ['required', 'string', 'size:6'],
        ]);

        $user = Auth::user();

        if (
            $user->two_factor_code === $request->input('code')
            && $user->two_factor_expires_at
            && $user->two_factor_expires_at->isFuture()
        ) {
            $user->resetTwoFactorCode();
            $request->session()->put('two_factor_verified', true);
            return redirect()->intended(route('dashboard'));
        }

        return back()->withErrors(['code' => 'The code is invalid or has expired. Please request a new one.']);
    }

    public function resend(Request $request): RedirectResponse
    {
        $user = Auth::user();
        $user->generateTwoFactorCode();
        $user->notify(new TwoFactorCodeNotification($user->two_factor_code));

        return back()->with('status', 'A new 6-digit code has been sent to your email.');
    }

    public function enable(Request $request): RedirectResponse
    {
        $user = $request->user();
        $user->two_factor_enabled = true;
        $user->save();

        $user->notify(new TwoFactorToggledNotification(true));

        return back()->with('status', 'Two-factor authentication has been enabled. You will receive an OTP via email on your next login.');
    }

    public function disable(Request $request): RedirectResponse
    {
        $user = $request->user();
        $user->two_factor_enabled = false;
        $user->two_factor_code = null;
        $user->two_factor_expires_at = null;
        $user->save();

        $user->notify(new TwoFactorToggledNotification(false));

        return back()->with('status', 'Two-factor authentication has been disabled.');
    }
}
