<?php

namespace App\Filament\Concerns;

use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Model;

trait PublishAction
{
    protected static function publish(): Action
    {
        return  Action::make('Publish')
            ->color('success')
            ->action(function (Model $record) {
                $record->update(['published_at' => now()]);
                Notification::make()->title('Post published successfully!')->success()->send();
            })
            ->visible(fn(Model $record) => is_null($record->published_at));
    }
}
