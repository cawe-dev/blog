<?php

namespace App\Filament\Concerns;

use App\Services\InviteService;
use Filament\Actions\Action;
use Filament\Notifications\Notification;

trait PostInvite
{
    protected static function createInvite(): Action
    {
        return Action::make(name: 'createInvite')
            ->color('info')
            ->icon('heroicon-o-link')
            ->action(function ($record, $livewire) {
                $token = InviteService::create($record->id);
                $inviteUrl = route('blog.post.invite', ['invite' => $token]);

                $livewire->js(
                    'window.navigator.clipboard.writeText("'.$inviteUrl.'");'
                );

                Notification::make()
                    ->title('Link Copied!')
                    ->success()
                    ->send();
            });
    }
}
