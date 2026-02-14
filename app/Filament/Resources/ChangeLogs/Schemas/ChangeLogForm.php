<?php

namespace App\Filament\Resources\ChangeLogs\Schemas;

use App\Enums\ChangeLogType;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class ChangeLogForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('title')
                    ->required(),
                Select::make('type')
                    ->options(ChangeLogType::class)
                    ->required(),
                TextInput::make('version')
                    ->required(),
                TextInput::make('commit')
                    ->required(),
                TextInput::make('pull_request')
                    ->required(),
                Select::make('post_id')
                    ->relationship('post', 'slug'),
            ]);
    }
}
