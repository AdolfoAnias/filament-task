<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;

Route::get('lang/{lang}', function (string $lang) {
    // valida que el idioma exista
    if (! in_array($lang, config('app.available_locales', []))) {
        abort(404);
    }

    // guarda en sesión
    session()->put('current_lang', $lang);

    if (auth()->check()) {
        auth()->user()->update(['language' => $lang]);
    }

    // vuelve a la página anterior
    return redirect()->back();
})->name('lang.switch');

Route::get('/', function () {
    return view('welcome');
});
