<?php

namespace App\Enums;

enum ContentPostViewMode: string
{
    case DEFAULT = 'default';
    case CONCEPT = 'concept';
    case TECHNICAL = 'technical';

    public function label(): string
    {
        return match ($this) {
            ContentPostViewMode::DEFAULT => 'padrão',
            ContentPostViewMode::CONCEPT => 'conceito',
            ContentPostViewMode::TECHNICAL => 'técnico',
        };
    }
}
