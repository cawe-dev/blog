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
            Js::make('reference-link-script', public_path('js/filament/plugins/marks/reference-link.js')),

            Css::make('media-indexer-link-styles', resource_path('css/filament/plugins/media-indexer-link.css')),
            Js::make('media-indexer-link-script', public_path('js/filament/plugins/marks/media-indexer-link.js'))
                ->loadedOnRequest(),
        ]);
    }
}
