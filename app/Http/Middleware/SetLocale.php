<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth()->user();

        $lang = $user?->role === 'producer'
            ? ($user->lang ?? 'en')
            : ($user?->lang ?? session('lang') ?? 'uz');

        app()->setLocale($lang);

        return $next($request);
    }
}
