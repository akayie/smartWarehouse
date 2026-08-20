<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class CheckUserRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        // Login ဝင်ထားပြီး user role သည် ပေးပို့ထားသော roles array ထဲတွင် ပါဝင်ပါက ခွင့်ပြုမည်
        if (Auth::check() && in_array(Auth::user()->role, $roles)) {
            return $next($request);
        }

        abort(403, 'ဤစာမျက်နှာသို့ ဝင်ရောက်ကြည့်ရှုရန် အခွင့်အရေး မရှိပါ။');
    }
}
