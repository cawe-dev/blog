<?php

namespace App\Enums;

enum PostType: string
{
    case PERSONAL = 'personal';
    case PROFESSIONAL = 'professional';
    case BOTH = 'both';

    public function label(): string
    {
        return match ($this) {
            PostType::PERSONAL => 'Pessoal',
            PostType::PROFESSIONAL => 'Profissional',
            PostType::BOTH => 'Profissional/Pessoal',
        };
    }
}
