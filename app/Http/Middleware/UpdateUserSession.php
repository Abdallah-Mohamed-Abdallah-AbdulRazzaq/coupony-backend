<?php

namespace App\Http\Middleware;

use App\Domain\User\Models\Session;
use Closure;


class UpdateUserSession
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle($request, Closure $next)
    {
        if ($user = $request->user()) {
            Session::where('user_id', $user->id)
                ->where('token', optional($user->currentAccessToken()))
                ->update([
                    'last_activity' => now(),
                ]);
        }

        return $next($request);
    }
}
