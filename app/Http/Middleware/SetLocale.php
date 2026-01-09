<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    public function handle(Request $request, Closure $next): Response
    {
        // 1) Primero intenta por usuario logueado:
        if ($request->user() && $request->user()->language) {
            app()->setLocale($request->user()->language);
        } else {
            // 2) Si no, usa lo que haya en sesión o el idioma por defecto
            $locale = session('current_lang', config('app.locale'));
            app()->setLocale($locale);
        }

        return $next($request);
    }
}
