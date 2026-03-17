<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureTwoFactorVerified
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if ($user
            && $user->two_factor_enabled
            && ! $request->session()->get('two_factor_verified')
        ) {
            return redirect()->route('two-factor.challenge');
        }

        return $next($request);
    }
}
