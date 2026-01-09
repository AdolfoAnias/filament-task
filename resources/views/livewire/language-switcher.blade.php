<div class="flex items-center">
    <x-filament::dropdown placement="bottom-end">
        <x-slot name="trigger">
            {{-- Icono simple que SIEMPRE funciona --}}
            <button class="fi-btn text-gray-500 hover:text-gray-700 p-1 rounded-lg hover:bg-gray-100 transition-all">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5h18M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                </svg>
            </button>
        </x-slot>

        <x-filament::dropdown.list>
            <x-filament::dropdown.list.item :color="app()->getLocale() === 'en' ? 'primary' : null" :href="route('lang.switch', 'en')" tag="a">
                🇬🇧 English
            </x-filament::dropdown.list.item>
            <x-filament::dropdown.list.item :color="app()->getLocale() === 'es' ? 'primary' : null" :href="route('lang.switch', 'es')" tag="a">
                🇪🇸 Español
            </x-filament::dropdown.list.item>
        </x-filament::dropdown.list>
    </x-filament::dropdown>
</div>
