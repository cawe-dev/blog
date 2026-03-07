<?php

namespace App\Services;

class PostReferenceParser
{
    public function getSyncData(array $content): array
    {
        $references = [];

        $this->extractReferences($content, $references);

        return $references;
    }

    private function extractReferences(array $node, array &$references): void
    {
        if (isset($node['marks'], $node['text']) && is_array($node['marks'])) {
            foreach ($node['marks'] as $mark) {
                if (($mark['type'] ?? '') === 'referenceLink') {
                    $this->mapMarkToReference($mark, $node['text'], $references);
                }
            }
        }

        if (isset($node['content']) && is_array($node['content'])) {
            foreach ($node['content'] as $child) {
                $this->extractReferences($child, $references);
            }
        }
    }

    private function mapMarkToReference(array $mark, string $text, array &$references): void
    {
        $attributes = $mark['attrs'] ?? [];
        $referenceId = $attributes['referenceId'] ?? null;

        if ($referenceId) {
            $references[] = [
                'reference_id' => $referenceId,
                'term' => $text,
                'context' => $attributes['context'] ?? null,
            ];
        }
    }
}
