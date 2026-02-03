<?php

namespace App\TiptapExtensions;

use DOMElement;
use Tiptap\Core\Mark;
use Tiptap\Utils\HTML;

class ReferenceLink  extends Mark
{

    public static $name = 'referenceLink';

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
                'tag' => 'span[data-reference-id]',
            ],
        ];
    }

    public function addAttributes(): array
    {
        return [
            ...parent::addAttributes(),
            'referenceId' => [
                'parseHTML' => fn(DOMElement $DOMNode) => $DOMNode->getAttribute('data-reference-id'),
                'renderHTML' => fn($attributes) => ['data-reference-id' => $attributes->referenceId ?? null],
            ],
            'term' => [
                'parseHTML' => fn(DOMElement $DOMNode) => $DOMNode->getAttribute('data-term'),
                'renderHTML' => fn($attributes) => ['data-term' => $attributes->term ?? null],
            ],
            'context' => [
                'parseHTML' => fn(DOMElement $DOMNode) => $DOMNode->getAttribute('data-context'),
                'renderHTML' => fn($attributes) => ['data-context' => $attributes->context ?? null],
            ],
        ];
    }

    public function renderHTML($mark, $HTMLAttributes = [])
    {
        return [
            'span',
            HTML::mergeAttributes($this->options['HTMLAttributes'], $HTMLAttributes),
            0,
        ];
    }
}
