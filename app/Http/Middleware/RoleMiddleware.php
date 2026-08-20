<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $user = auth()->user();

        // User role ကို စစ်ဆေးခြင်း (Case-insensitive & space handling)
        $userRole = strtolower(trim($user->role));
        $allowedRoles = array_map(fn($role) => strtolower(trim($role)), $roles);

        if (in_array($userRole, $allowedRoles)) {
            return $next($request);
        }

        abort(403, 'ဤစာမျက်နှာသို့ ဝင်ရောက်ကြည့်ရှုခွင့် မရှိပါ။');
    }
}
