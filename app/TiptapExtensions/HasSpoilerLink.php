<?php

namespace App\TiptapExtensions;

use DOMElement;
use Tiptap\Core\Mark;
use Tiptap\Utils\HTML;

class HasSpoilerLink extends Mark
{

    public static $name = 'hasSpoilerLink';

    public function addOptions()
    {
        return [
            'HTMLAttributes' => [],
        ];
    }

    public function parseHTML()
    {
        return [
            [
                'tag' => 'span[data-has-spoiler]',
            ]
        ];
    }

    public function addAttributes(): array
    {
        return [
            ...parent::addAttributes(),
            'spoiler' => [
                'parseHTML' => fn(DOMElement $DOMNode) => $DOMNode->getAttribute('data-has-spoiler'),
                'renderHTML' => fn($attributes) => ['data-has-spoiler' => $attributes->spoiler ?? null],
            ]
        ];
    }

    public function renderHTML($mark, $HTMLAttributes = []): array
    {
        return [
            'span',
            HTML::mergeAttributes($this->options['HTMLAttributes'], $HTMLAttributes),
            0,
        ];
    }
}
