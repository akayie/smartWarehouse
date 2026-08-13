<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // User သည် Login ဝင်ထားပြီး role က 'admin' ဖြစ်မှ ပေးဝင်မည်
        if (Auth::check() && Auth::user()->role === 'admin') {
            return $next($request);
        }

        // Admin မဟုတ်ပါက Home သို့ ပြန်ညွှန်းမည်
        return redirect('/')->with('error', 'Access denied! Admin only.');
    }
}
