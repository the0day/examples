<?php

namespace App\Http\Middleware;

use App\Models\User;
use Auth;
use Cache;
use Carbon\Carbon;
use Closure;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class UserActivity
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return Response|RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check()) {
            $now = Carbon::now();

            $key = 'online.' . Auth::user()->id;
            $lastSeen = Cache::get($key);

            if (!$lastSeen || $lastSeen < $now->unix()) {
                $ttl = $now->addMinute(1);
                Cache::put($key, $ttl->unix(), $ttl);
                User::where('id', Auth::user()->id)->update(['last_seen_at' => Carbon::now()]);
            }
        }

        return $next($request);
    }
}
