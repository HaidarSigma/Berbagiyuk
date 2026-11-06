<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsurePin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
public function handle(Request $request, Closure $next)
{
    $u = $request->user();
    if (! $u->hasVerifiedEmail()) return redirect()->route('verification.notice');
    if (! $u->pin) return redirect('/pin/buat');
    if (! $request->session()->get('pin_ok')) return redirect('/pin/masuk');
    return $next($request);
}
}
