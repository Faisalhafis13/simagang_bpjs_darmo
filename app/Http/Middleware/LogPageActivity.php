<?php

namespace App\Http\Middleware;

use App\Helpers\ActivityLogger;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LogPageActivity
{
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        if (
            Auth::check()
            && $request->isMethod('GET')
            && $response->isSuccessful()
            && str_contains(
                (string) $response->headers->get('Content-Type'),
                'text/html'
            )
        ) {
            $routeName = $request->route()?->getName() ?? $request->path();
            $pageName = str($routeName)
                ->replace(['back-office.', 'peserta.', 'mentor.'], '')
                ->replace(['.', '-', '_'], ' ')
                ->title()
                ->toString();

            ActivityLogger::log(
                $pageName,
                'VIEW',
                "Membuka halaman {$pageName}"
            );
        }

        return $response;
    }
}