<?php

namespace App\Filament\Concerns;

use App\Services\PostReferenceParser;
use Illuminate\Database\Eloquent\Model;

trait SyncsPostReferences
{
    protected function syncReferences(Model $record): void
    {
        $record->refresh();
        $record->load('contents');

        $parser = app(PostReferenceParser::class);

        foreach ($record->contents as $contentPost) {
            $contentBody = $contentPost->body->data ?? [];

            if (empty($contentBody)) {
                $contentPost->references()->detach();
                continue;
            }

            $syncData = $parser->getSyncData($contentBody);

            $contentPost->references()->detach();
            foreach ($syncData as $item) {
                $contentPost->references()->attach($item['reference_id'], [
                    'term' => $item['term'],
                    'context' => $item['context'],
                ]);
            }
        }
    }
}
