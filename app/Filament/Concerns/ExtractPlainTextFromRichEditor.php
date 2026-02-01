<?php

namespace App\Filament\Concerns;

trait ExtractPlainTextFromRichEditor
{
    protected static function extractPlainText(array | string $state): string
    {
        if (! is_array($state)) return (string) $state;

        $text = '';

        array_walk_recursive($state, function ($value, $key) use (&$text) {
            if ($key === 'text') {
                $text .= $value;
            }
        });

        return $text;
    }
}
