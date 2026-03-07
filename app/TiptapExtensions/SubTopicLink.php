<?php

namespace App\TiptapExtensions;

use DOMElement;
use Tiptap\Core\Mark;
use Tiptap\Utils\HTML;

class SubTopicLink extends Mark
{
    public static $name = 'subTopicLink';

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
                'tag' => 'h2[data-subTopic]',
            ],
        ];
    }

    public function addAttributes(): array
    {
        return [
            ...parent::addAttributes(),
            'subTopicId' => [
                'parseHTML' => fn (DOMElement $DOMNode) => $DOMNode->getAttribute('id'),
                'renderHTML' => fn ($attributes) => ['id' => $attributes->subTopicId ?? null],
            ],
        ];
    }

    public function renderHTML($mark, $HTMLAttributes = []): array
    {
        return [
            'h2',
            HTML::mergeAttributes($this->options['HTMLAttributes'], $HTMLAttributes, ['class' => 'cursor-pointer']),
            0,
        ];
    }
}
