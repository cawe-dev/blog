<?php

namespace App\Support\Post;

use Illuminate\Contracts\Support\Arrayable;
use JsonSerializable;

class Content implements Arrayable, JsonSerializable
{
    public function __construct(public array $data) {}

    public function toArray(): array
    {
        return $this->data;
    }

    public function jsonSerialize(): mixed
    {
        return $this->data;
    }

    protected function toPlainText(): string
    {
        if (empty($this->data)) return '';

        $text = '';

        array_walk_recursive($this->data, function ($value, $key) use (&$text) {
            if ($key === 'text') {
                $text .= $value . ' ';
            }
        });

        return trim($text);
    }

    public function estimatedReadTime(): int
    {
        return str_word_count($this->toPlainText()) / 200 > 1 ? str_word_count($this->toPlainText()) / 200 : 1;
    }

    public static function from(mixed $data): self
    {
        if ($data instanceof self) {
            return $data;
        }

        if (is_string($data)) {
            $decoded = json_decode($data, true);
            return new self(is_array($decoded) ? $decoded : []);
        }

        return new self(is_array($data) ? $data : []);
    }

    public function hasSpoiler(): bool
    {
        $spoiler = false;

        array_walk_recursive($this->data, function ($value, $key) use (&$spoiler) {
            if ($key === 'type' && $value === 'hasSpoilerLink') {
                $spoiler = true;
            }
        });

        return $spoiler;
    }
}
