<?php

namespace App\Filament\Resources\ChangeLogs\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class ChangeLogInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('title'),
                TextEntry::make('type')
                    ->badge(),
                TextEntry::make('version'),
                TextEntry::make('commit'),
                TextEntry::make('pull_request'),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('post.title')
                    ->label('Post')
                    ->placeholder('-'),
            ]);
    }
}
