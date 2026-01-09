<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Filament\Facades\Filament;

class SetFilamentLocale
{
    public function handle(Request $request, Closure $next)
    {
        $locale = session('filament.locale', config('app.locale'));
        app()->setLocale($locale);

        // **IMPORTANTE**: Establecer locale en Filament
        //Filament::setLocale($locale);

        return $next($request);
    }
}
