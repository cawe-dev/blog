<?php

namespace App\TiptapExtensions;

use DOMElement;
use Tiptap\Core\Mark;
use Tiptap\Utils\HTML;

class MediaIndexerLink extends Mark
{

    public static $name = 'mediaIndexerLink';

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
                'tag' => 'img[data-type]',
            ],
            [
                'tag' => 'iframe[data-type]',
            ],
        ];
    }

    public function addAttributes(): array
    {
        return [
            ...parent::addAttributes(),
            'type' => [
                'parseHTML' => fn(DOMElement $DOMNode) => $DOMNode->getAttribute('data-type'),
                'renderHTML' => fn($attributes) => ['data-type' => $attributes->type ?? null],
            ],
            'url' => [
                'parseHTML' => fn(DOMElement $DOMNode) => $DOMNode->getAttribute('src'),
                'renderHTML' => fn($attributes) => ['src' => $attributes->url ?? null],
            ],
        ];
    }

    public function renderHTML($mark, $HTMLAttributes = []): array
    {
        return [
            $mark->attrs->type,
            HTML::mergeAttributes($this->options['HTMLAttributes'], $HTMLAttributes),
            0,
        ];
    }
}
