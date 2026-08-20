<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserRole
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        if (!Auth::check() || !in_array(Auth::user()->role, $roles)) {
            abort(403, 'ဤစာမျက်နှာသို့ ဝင်ရောက်ကြည့်ရှုရန် အခွင့်အရေး မရှိပါ။');
        }

        return $next($request);
    }
}
