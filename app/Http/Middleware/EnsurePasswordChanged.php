<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EnsurePasswordChanged
{
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();

        if ($user && $user->must_change_password) {
            if (! $request->routeIs('password.change') && ! $request->routeIs('password.change.post')) {
                return redirect()->route('password.change');
            }
        }

        return $next($request);
    }
}
