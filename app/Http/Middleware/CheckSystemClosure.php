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
        
        // Logic to logout users has been removed because the new requirement
        // is to allow them to navigate but disable questions.
        // The closure time state is passed to the frontend via HandleInertiaRequests.

        return $next($request);
    }
}
