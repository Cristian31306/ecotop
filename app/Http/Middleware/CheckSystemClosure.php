<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Setting;
use Carbon\Carbon;

class CheckSystemClosure
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        $closureTimeStr = Setting::where('key', 'system_closure_time')->value('value');
        
        if ($closureTimeStr) {
            $closureTime = Carbon::parse($closureTimeStr);
            if (Carbon::now()->greaterThanOrEqualTo($closureTime)) {
                // System is closed
                if (Auth::check()) {
                    $user = Auth::user();
                    // Admin exemption
                    if ($user->role !== 'admin') {
                        Auth::guard('web')->logout();
                        $request->session()->invalidate();
                        $request->session()->regenerateToken();
                        
                        return redirect()->route('login')->with('status', 'El sistema se encuentra cerrado actualmente.');
                    }
                } else {
                    // Prevent login if not admin (the actual credentials check happens later, 
                    // so we block the login POST request entirely unless they somehow have an admin backdoor,
                    // but since they are not logged in, we can't know their role until they submit.
                    // A better approach is to let them submit, and if they are not admin, logout immediately.
                    // We can handle that in the LoginRequest or AuthenticatedSessionController,
                    // but we can also just block normal users post-login in this middleware.
                    // This middleware runs on every request in the 'web' group.
                }
            }
        }

        return $next($request);
    }
}
