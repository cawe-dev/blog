<?php

namespace App\Filament\Resources\Posts\Pages;

use App\Filament\Resources\Posts\PostResource;
use App\Services\PostReferenceParser;
use Filament\Resources\Pages\CreateRecord;

class CreatePost extends CreateRecord
{
    protected static string $resource = PostResource::class;

      protected function afterCreate(): void
    {
        $parser = app(PostReferenceParser::class);

        $syncData = $parser->getSyncData($this->data['content'] ?? []);

        $this->record->references()->sync($syncData);
    }
}
