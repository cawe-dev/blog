<?php

namespace App\Enums;

enum ChangeLogType: string
{
    case FEATURE = 'feature';
    case IMPROVEMENT = 'improviment';
    case FIX = 'fix';

    public function label(): string
    {
        return match ($this) {
            ChangeLogType::FEATURE => 'Novidade',
            ChangeLogType::IMPROVEMENT => 'Melhoria',
            ChangeLogType::FIX => 'Correção',
        };
    }
}
