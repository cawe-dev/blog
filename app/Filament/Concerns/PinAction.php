<?php

namespace App\Filament\Concerns;

use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Model;

trait PinAction
{
    protected static function pin(): Action
    {
        return Action::make('pin')
            ->color('info')
            ->action(function (Model $record) {
                $record->update(['pinned_at' => now()]);
                Notification::make()->title('Post pined successfully!')->success()->send();
            })
            ->visible(fn (Model $record) => is_null($record->pinned_at));
    }
}
