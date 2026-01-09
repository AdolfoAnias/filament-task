<?php

namespace App\Livewire;

use Filament\Panel;
use Livewire\Component;
use Illuminate\Support\Facades\App;

class LanguageSwitcher extends Component
{
    public $locale;

    public function mount()
    {
        $this->locale = session('filament.locale', config('app.locale'));
    }

    public function setLocale($locale)
    {
        session(['filament.locale' => $locale]);

        App::setLocale($locale);

        foreach (config('filament-panels', []) as $panelClass) {
            $panel = new $panelClass();
            $panel->setLocale($locale);
        }

        $this->locale = $locale;

        return redirect(request()->url());
    }

    public function render()
    {
        $locales = [
            'es' => ['name' => 'Español', 'flag' => 'es'],
            'en' => ['name' => 'English', 'flag' => 'gb'], // o 'us' para USA
            //'es' => 'Español',
            //'en' => 'English'
        ];

        return view('livewire.language-switcher', compact('locales'));
    }
}
