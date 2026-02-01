<?php

namespace App\Enums;

enum PostType: string
{
    case PERSONAL = 'personal';
    case PROFESSEONAL = 'professional';
    case BOTH = 'both';

    public function label(): string
    {
        return match ($this) {
            PostType::PERSONAL => 'Pessoal',
            PostType::PROFESSEONAL => 'Profissional',
            PostType::BOTH => 'Ambos',
        };
    }
}
