<?php

namespace App\Filament\Resources\Posts\Pages;

use App\Filament\Resources\Posts\PostResource;
use App\Services\PostReferenceParser;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;

class CreatePost extends CreateRecord
{
    protected static string $resource = PostResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['user_id'] = Auth::id();

        return $data;
    }

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
