<?php

namespace App\Filament\Resources\Posts\Pages;

use App\Filament\Concerns\PostInvite;
use App\Filament\Resources\Posts\PostResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewPost extends ViewRecord
{
    use PostInvite;

    protected static string $resource = PostResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
            self::createInvite(),
        ];
    }
}
