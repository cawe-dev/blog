<?php

namespace App\Filament\Resources\ChangeLogs;

use App\Filament\Resources\ChangeLogs\Pages\CreateChangeLog;
use App\Filament\Resources\ChangeLogs\Pages\EditChangeLog;
use App\Filament\Resources\ChangeLogs\Pages\ListChangeLogs;
use App\Filament\Resources\ChangeLogs\Pages\ViewChangeLog;
use App\Filament\Resources\ChangeLogs\Schemas\ChangeLogForm;
use App\Filament\Resources\ChangeLogs\Schemas\ChangeLogInfolist;
use App\Filament\Resources\ChangeLogs\Tables\ChangeLogsTable;
use App\Models\ChangeLog;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class ChangeLogResource extends Resource
{
    protected static ?string $model = ChangeLog::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'ChangeLog';

    public static function form(Schema $schema): Schema
    {
        return ChangeLogForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return ChangeLogInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ChangeLogsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListChangeLogs::route('/'),
            'create' => CreateChangeLog::route('/create'),
            'view' => ViewChangeLog::route('/{record}'),
            'edit' => EditChangeLog::route('/{record}/edit'),
        ];
    }
}
