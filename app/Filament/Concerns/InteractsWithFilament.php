<?php

namespace App\Filament\Concerns;

use Illuminate\Support\Collection;

trait InteractsWithFilament
{
    public function commitsToOptions(Collection $commits): Collection
    {
        return $commits->mapWithKeys(function ($commit) {
            return [$commit->sha => $commit->message];
        });
    }
}
