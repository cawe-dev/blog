<?php

namespace App\Providers;

use Filament\Support\Assets\Css;
use Filament\Support\Assets\Js;
use Filament\Support\Facades\FilamentAsset;
use Illuminate\Support\ServiceProvider;

class FilamentServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        FilamentAsset::register([
            Css::make('reference-link-styles', resource_path('css/filament/plugins/reference-link.css')),
            Js::make('reference-link-script', public_path('js/filament/plugins/reference-link.js'))
                ->loadedOnRequest(),
        ]);
    }
}
