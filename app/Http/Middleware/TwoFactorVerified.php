<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Users who enabled TOTP must pass the challenge once per session
 * before reaching any panel route (admin / marketplace / producer).
 */
class TwoFactorVerified
{
    public const SESSION_KEY = 'two_factor_passed';

    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if ($user && $user->hasTwoFactorEnabled() && !$request->session()->get(self::SESSION_KEY)) {
            return redirect()->route('two-factor.challenge');
        }

        return $next($request);
    }
}
