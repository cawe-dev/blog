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
        $this->record->refresh();
        $this->record->load('contents');

        $parser = app(PostReferenceParser::class);

        foreach ($this->record->contents as $contentPost) {
            $contentBody = $contentPost->body->data ?? [];

            if (empty($contentBody)) {
                continue;
            }

            $syncData = $parser->getSyncData($contentBody);

            $contentPost->references()->sync($syncData);
        }
    }
}
