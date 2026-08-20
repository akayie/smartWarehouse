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
        // Admin, Warehouse Manager သို့မဟုတ် Manager ဖြစ်ပါက ခွင့်ပြုမည်
        $allowedRoles = ['admin', 'warehouse_manager', 'manager'];

        if (Auth::check() && in_array(Auth::user()->role, $allowedRoles)) {
            return $next($request);
        }

        // ခွင့်ပြုချက်မရှိပါက Home သို့ ပြန်ညွှန်းမည်
        return redirect('/')->with('error', 'Access denied! Authorized staff only.');
    }
}
