<?php

namespace App\Filament\Resources\Posts\Pages;

use App\Filament\Concerns\PublishAction;
use App\Filament\Resources\Posts\PostResource;
use App\Services\PostReferenceParser;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditPost extends EditRecord
{
    use PublishAction;

    protected static string $resource = PostResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
            self::publish(),
        ];
    }

    protected function afterSave(): void
    {
        $parser = app(PostReferenceParser::class);

        $syncData = $parser->getSyncData($this->data['content'] ?? []);

        $this->record->references()->sync($syncData);
    }
}
