<?php

namespace App\Filament\Resources\ChangeLogs\Pages;

use App\Filament\Resources\ChangeLogs\ChangeLogResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListChangeLogs extends ListRecords
{
    protected static string $resource = ChangeLogResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
