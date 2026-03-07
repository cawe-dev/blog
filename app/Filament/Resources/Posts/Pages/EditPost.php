<?php

namespace App\Filament\Resources\Posts\Pages;

use App\Filament\Concerns\PinAction;
use App\Filament\Concerns\PublishAction;
use App\Filament\Concerns\SyncsPostReferences;
use App\Filament\Resources\Posts\PostResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditPost extends EditRecord
{
    use PinAction, PublishAction, SyncsPostReferences;

    protected static string $resource = PostResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
            self::publish(),
            self::pin(),
        ];
    }

    protected function afterSave(): void
    {
        $this->syncReferences($this->record);
    }
}
